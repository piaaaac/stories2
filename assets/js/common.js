
// ----------------------------------------------------------------
// On execution
// ----------------------------------------------------------------

$(document).ready(function () {});

const dataFolder = "v0-dev";
const mapUrl = window.siteUrl +"/assets/data/world-110m.v1.json";
const legsDataUrl = window.siteUrl +"/assets/data/"+ dataFolder +"/legs.json";

// -----------------------------------------------------------------------------
// Functions
// -----------------------------------------------------------------------------



// -----------------------------------------------------------------------------
// StorySvg class
// -----------------------------------------------------------------------------

function StorySvg () {
  // defined now
  this.margin = 20;
  // defined later
  this.id = null;
  this.svg = null;
  this.projection = null;
  this.width = null;
  this.height = null;
  this.worldData = null;
  this.legsData = null;

  /**
   * Initiate the instance, read data, draw map
   * 
   * @param id (num) story id as indicated in spreadsheet 
   * https://docs.google.com/spreadsheets/d/15JBdiWsz_ywoZaXGAwhusKVe-kJHTOhrCk3PwfKpGyY/edit#gid=2117676923
   * 
   * */
  this.init = function (id) {
    this.id = id;
    this.width = $("#svg-container").width();
    this.height = window.innerHeight*0.9;
    this.svg = d3.select("#svg-container").append("svg")
      .attr("id", "story-svg")
      .attr("width", this.width)
      .attr("height", this.height);
    this.drawMap(this.worldData)
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
      that.worldData = rawDatasets[0];
      that.legsData = rawDatasets[1];
      that.init();
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
  this.drawMap = function (topology) {
    this.projection = d3.geoMercator()
      .fitExtent([[this.margin, this.margin], [this.width-this.margin*2, this.height-this.margin*2]], topology)
      .scale(153)
      .translate([this.width / 2, this.height / 2])
      .precision(.1)
    var path = d3.geoPath().projection(this.projection);
    this.svg.append("path")
      .datum(topojson.feature(topology, topology.objects.land))
      .attr("d", path)
      .attr("class", "land-boundary");
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
  $("#story-svg").remove();
}

function handleResizeEnd () {
  drawMap(window.world);
}











