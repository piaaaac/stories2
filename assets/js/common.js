
// ----------------------------------------------------------------
// To do
// ----------------------------------------------------------------
/*

- save / retrieve from cache



*/
// ----------------------------------------------------------------
// On execution
// ----------------------------------------------------------------

$(document).ready(function () {});

const dataFolder = "v0-dev";
const mapUrl = window.siteUrl +"/assets/data/world-110m.v1.json";
const legsDataUrl = window.siteUrl +"/assets/data/"+ dataFolder +"/legs.json";

// -----------------------------------------------------------------------------
// Data binding
// -----------------------------------------------------------------------------

var lineTypes = [
  {"lineType": "wheels",  "aliases": ["bus", "minibus", "car", "taxi", "police car", "truck"]},
  {"lineType": "train",   "aliases": ["train"]},
  {"lineType": "water",   "aliases": ["small boat", "boat", "ship"]},
  {"lineType": "plane",   "aliases": ["plane"]},
  {"lineType": "walking", "aliases": ["walking"]},
  {"lineType": "mixed",   "aliases": ["mixed"]},
  {"lineType": "unknown", "aliases": ["unknown"]},
];

// -----------------------------------------------------------------------------
// StorySvg class
// -----------------------------------------------------------------------------

/**
 * StorySvg Class
 * 
 * @param id (num) story id as indicated in spreadsheet 
 * https://docs.google.com/spreadsheets/d/15JBdiWsz_ywoZaXGAwhusKVe-kJHTOhrCk3PwfKpGyY/edit#gid=2117676923
 * 
 * */
function StorySvg (id) {
  
  // defined now
  this.story_id = id;
  this.margin = 20;
  
  // initialized in init() & initGeo()
  this.width = null;
  this.height = null;
  this.svg = null;
  this.projection = null;
  this.worldData = null;
  this.legs = null;
  this.requests = [];
  this.routes = [];


  /**
   * Draw the line using the data in the initialized instance
   * 
   * */
  this.drawLine = function () {

    // handy: https://github.com/d3/d3-geo/issues/62

    var that = this;
    var points = this.legs.map(d => [d.lon, d.lat]);
    var path = d3.geoPath(this.projection);

    // one line per trip
    this.legs.forEach(function (leg, i, a) {
      
      // Straight lines
      //
      // if (i > 0) {
      //   var prevLeg = a[i-1];
      //   var legPoints = [[prevLeg.lon, prevLeg.lat], [leg.lon, leg.lat]];
      //   var legName = "leg-"+ prevLeg.leg_id +"-"+ leg.leg_id;
      //   var ls = turf.lineString(legPoints, {"name": legName});
      //   that.svg.append("path")
      //     .datum(ls)
      //     .attr("d", path)
      //     .attr("class", "leg-line")
      //     .attr("data-line-type", d => leg.lineType)
      // }

      // With api call
      // 
      if (i > 0) {
        var legFrom = a[i-1];
        var legTo = leg;
        that.addRoute(legFrom, legTo, function (geoJson) {
          that.svg.append("path")
            .datum(geoJson)
            .attr("d", path)
            .attr("class", "leg-line")
            .attr("data-line-type", d => leg.lineType);
        });
      }

    
    });

    // dots
    this.svg.selectAll("circle")
      .data(points)
      .enter()
      .append("circle")
      .attr("d", path)
      .attr("cx", d => this.projection(d)[0] )
      .attr("cy", d => this.projection(d)[1] )
      .attr("r", "3px")
      .attr("class", "leg-dot");
  }


  /**
   * Read data, draw map
   * 
   * */
  this.init = function () {
    this.width = $("#svg-container").width();
    this.height = window.innerHeight*0.999;
    this.svg = d3.select("#svg-container").append("svg")
      .attr("id", "story-svg")
      .attr("width", this.width)
      .attr("height", this.height);
    this.initGeo(this.worldData);
  }


  /**
   * Read data files and initiate the story svg
   * 
   * */
  this.readDataAndInit = function () {
    var that = this;
    Promise.all([
      fetch(mapUrl),
      fetch(legsDataUrl),
    ]).then(function (responses) {
      return Promise.all(responses.map(function (response) {
        return response.json();
      }));
    }).then(function (rawDatasets) {
      
      // initialize story instance with data
      // this remains the whole time
      that.worldData = rawDatasets[0];
      var legsData = rawDatasets[1];
      var legsDataStory = legsData.filter(d => parseInt(d.story_id) === parseInt(that.story_id));
      that.legs = cleanLegs(legsDataStory);
      console.log(that.legs);

      // svg
      // this is reiterated on resize
      that.init();
      that.drawLine();

    }).catch(function (error) {
      console.log(error);
    });
  }

  /**
   * Draws a map given the world topology
   * 
   * @param topology (json) A file like world-110m.v1.json
   * 
   * */
  this.initGeo = function (topology) {
    var points = this.legs.map(d => [d.lon, d.lat]);
    var lineString = turf.lineString(points, {"name": "line pippo"});
    this.projection = d3.geoMercator()
      .fitExtent([[this.margin, this.margin], [this.width-this.margin*2, this.height-this.margin*2]], lineString)
      .precision(.1)
    var path = d3.geoPath(this.projection);
    this.svg.append("path")
      .datum(topojson.feature(topology, topology.objects.land))
      .attr("d", path)
      .attr("class", "land-boundary");
  }


  /**
   * Returns the geoJson route between 2 points
   * @param points                  (array of coordinates)    [[lon, lat], [lon, lat]]
   * @param callback                (function)                called with data
   * @param routeOptions.service    (string)                  "openrouteservice"
   * @param routeOptions.fromCache  (bool)                    if true searches in cached responses before making the call
   * 
   * */
  this.addRoute = function (leg1, leg2, callback, routeOptions) {
    var defaults = {
      service: "openrouteservice", // https://openrouteservice.org
      fromCache: true,
    };
    var options = Object.assign({}, defaults, routeOptions);

    if (options.service === "openrouteservice") {
      const orsKey = "5b3ce3597851110001cf624837bcd0c908f0494794652a5a7720f62a";
      const points = [[leg1.lon, leg1.lat], [leg2.lon, leg2.lat]];
      const apiUrlPost = "https://api.openrouteservice.org/v2/directions/driving-car/geojson"; // post
      // const apiUrl = "https://api.openrouteservice.org/v2/directions/driving-car?api_key="+ orsKey +"&start="+ points[0].join(",") +"&end="+ points[1].join(",");
      const requestId = "s"+ this.story_id +"-l"+ leg1.leg_id +"-l"+ leg2.leg_id;
      const requestDesc = "from "+ leg1.place +" to "+ leg2.place;

      const requestData = {
        "coordinates": points,
        "geometry_simplify": true,
        "instructions": false,
        // radiuses? (array) eg.: [200,-1,30] // https://openrouteservice.org/dev/#/api-docs/v2/directions/{profile}/geojson/post
      };


      const cacheKeyData = Object.assign({}, {"requestUrl": apiUrlPost}, requestData);
      const cacheKey = new URLSearchParams(cacheKeyData).toString();
      console.log(cacheKey);
      // throw "debug the shit outta this"

      var requestLog = {
        "isResolved": false,
        "resolvedAt": null,
        "requestedAt": Math.floor(new Date()/1000),
        "requestId": requestId,
        "requestDesc": requestDesc,
        "requestData": requestData,
        "cacheKey": cacheKey,
        "apiUrl": apiUrlPost,
      };
      this.requests.push(requestLog);
      
      var that = this;

      fetch(apiUrlPost, {
        "method": "POST",
        "mode": "cors", // no-cors, *cors, same-origin
        "headers": {
          "Content-Type": "application/json",
          "Accept": "application/json, application/geo+json, application/gpx+xml, img/png; charset=utf-8",
          "Authorization": "5b3ce3597851110001cf624837bcd0c908f0494794652a5a7720f62a",
        },
        "body": JSON.stringify(requestData),
      })
      .then(response => response.json())
      .then(data => {
        console.log("Success:", data);
        const route = {
          "requestId": requestId,
          "requestDesc": requestDesc,
          "geoJson": data,
        };
        that.routes.push(route);
        const req = that.requests.find(r => r.requestId === requestId);
        req.isResolved = true;
        req.resolvedAt = Math.floor(new Date()/1000);
        console.log(that.routes.length +"/"+ that.requests.length);

        const cacheItem = {
          "cacheKey": cacheKey,
          "response": data,
        };
        console.log("cache item", JSON.stringify(cacheItem));

        callback(data);
      })
      .catch((error) => {
        console.error("Error:", error);
      });
    }
  }



}

// ----------------------------------------------------------------
// Events
// ----------------------------------------------------------------

// Handle resize
// -------------

handleResizeStartEnd(function () {
  console.log("started");
  handleResizeStart();
}, () => {
  console.log("ended")
  handleResizeEnd();
});

function handleResizeStart () {
  console.log(s)
  $(s.svg.node()).remove();
}

function handleResizeEnd () {
  s.init();
  s.drawLine();
}

// -----------------------------------------------------------------------------
// Functions
// -----------------------------------------------------------------------------

/**
 * Cleans up the legs items for the story
 * 
 * */
function cleanLegs (legs) {
  var allTransports = [];
  lineTypes.forEach(item => {
    var strings = item.aliases;
    allTransports = allTransports.concat(strings);
  });
  console.log("allTransports", allTransports)
  var clean = legs.filter(leg => {

    // --- clean some fields
    leg.country = leg.country.trim();
    leg.transport = leg.transport.trim();
    var matchedItem = lineTypes.find(item => item.aliases.includes(leg.transport));
    leg.lineType = matchedItem ? matchedItem.lineType : "fallback";
    leg.lon = leg.lon.trim();
    leg.lat = leg.lat.trim();

    // --- check coordinates
    var coordinatesMissing = (!leg.lon || !leg.lat);
    if (coordinatesMissing) {
      console.log("-->> missing coordinates", "leg_id: "+ leg.leg_id);
      return false;
    }
    var coorRegex = /^-?\d+\.\d+$/;
    var coordinatesOk = coorRegex.test(leg.lon) && coorRegex.test(leg.lat);
    if (!coordinatesOk) {
      console.log("-->> invalid coordinates", leg.lon, leg.lat, "leg_id: "+ leg.leg_id);
      return false;
    }

    // --- check transport
    if (allTransports.includes(leg.transport) === false) {
      console.log("-->> invalid transport", leg.transport, "leg_id: "+ leg.leg_id);
      return false;
    }

    // --- pass
    return true;
  });

  return clean;
}


// /**
//  * Returns the geoJson route between 2 points
//  * @param points                (array of coordinates)    [[lon, lat], [lon, lat]]
//  * @param routeOptions.service  (string)                  "openrouteservice"
//  * 
//  * */
// function getRouteGeoJson (points, routeOptions) {
//   var defaults = {
//     service: "openrouteservice", // https://openrouteservice.org
//   };
//   var options = Object.assign({}, defaults, routeOptions);

//   if (options.service === "openrouteservice") {
//     const orsKey = "5b3ce3597851110001cf624837bcd0c908f0494794652a5a7720f62a";
//     const apiUrl = "https://api.openrouteservice.org/v2/directions/driving-car?api_key="+ orsKey +"&start="+ points[0].join(",") +"&end="+ points[0].join(",");
//     fetch(apiUrl)
//       .then(response => response.json())
//       .then(data => {
//         console.log(data)
//         return data;
//       });
//   }
// }








