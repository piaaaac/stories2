<?php
$from = getFromPlace($page);
$to = getToPlace($page);
?>

<?php snippet("header") ?>

<?php snippet("menu", ["subtitle" => "$from &rarr; $to", "showSwitch" => true]) ?>

<?php snippet('handlebars-templates') ?>

<section>
  <div id="map-container"></div>
</section>

<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <?= $page->text()->kt() ?>
    </div>
  </div>
</div>

<div id="tmp-label" style="position: absolute; bottom: 30px; left: 30px; font-size: 21px; color: black;"><?= $page->title() ?></div>

<svg id="map" xmlns="http://www.w3.org/2000/svg" width="800" height="800" x="0" y="0" class="geojson2svg-test" style="display: none;"></svg>

<script>
  // --------------------------------
  // Kirby > JS data init
  // --------------------------------

  var kirbyData = <?= json_encode($page->content()->toArray()) ?>;
  kirbyData.legs = legs = <?= $page->legs()->toStructure()->toJson() ?>;
  console.log("kirbyData", kirbyData)

  var state = {
    storyPlaces: getStoryPlacesFromKirbyData(kirbyData),
    openPlaceId: null,
    currentMapStyle: null,
  }
  console.log("places", state.storyPlaces)

  // --------------------------------
  // Mapbox init
  // --------------------------------

  // AP
  var mbToken = "pk.eyJ1IjoicGlhYWFhYyIsImEiOiIxaHI5SmNnIn0.68S9KEJ3TeuhobReU_uDeQ"
  var mbStyleWithBg = "mapbox://styles/piaaaac/clo2p2b6o00is01pf9ovp59k8"
  var mbStyleEmpty = "mapbox://styles/piaaaac/clr0wgqob019e01o37ytp8q40"

  // FF
  // mbToken = "pk.eyJ1IjoiZmVkZXJpY2FmcmFnYXBhbmUiLCJhIjoiQUtjSXNWZyJ9.MCoKTvad0nvu3AMTTMdHfw"
  // mbStyle = "mapbox://styles/federicafragapane/cl9mvjnf0007a14me74xoz16o"

  var popupHover, popupClick;
  var basicRouteDS = getBasicRouteDSFromState();
  var pointsDS = getPointsDSFromState();
  console.log("basicRouteDS", basicRouteDS)
  console.log("pointsDS", pointsDS)

  mapboxgl.accessToken = mbToken;
  const map = new mapboxgl.Map({
    container: 'map-container',
    style: mbStyleEmpty,
    center: [state.storyPlaces[0].lon, state.storyPlaces[0].lon],
    zoom: 5,
    attributionControl: false,
    logoPosition: 'top-right',
  });
  // toggleMapStyle();
  // toggleMapStyle();

  // --------------------------------
  // Add data
  // --------------------------------

  map.on('load', () => {


    // --- Stiled attribution
    map.addControl(new mapboxgl.AttributionControl({
      compact: true,
    }), 'top-right');


    // --- Add data sources (addAdditionalSourceAndLayer)
    // --- Add layers       (addAdditionalSourceAndLayer)
    addAdditionalSourceAndLayer()

    // --- fit to bounds

    var coordinates = [];
    basicRouteDS.data.features.forEach(feature => {
      feature.geometry.coordinates.forEach(coo => {
        coordinates.push(coo)
      })
    })
    var bounds = getBounds(coordinates)
    map.fitBounds(bounds, {
      padding: 40,
    })

    // --- Create popups to be used later

    popupClick = new mapboxgl.Popup({
      closeButton: true,
      closeOnClick: true,
      offset: 4,
      className: "ck-map-popup",
    });
    popupHover = new mapboxgl.Popup({
      closeButton: true,
      closeOnClick: false,
      offset: 8,
      className: "ck-map-popup",
    });

    // --- Handle map events


    map.on('click', 'points', function(e) {
      var data = e.features[0].properties;
      popupHover.remove();
      if (state.openPlaceId == data.id) {
        closeStory();
      } else {
        openStory(data.id);
      }
    });
    // map.on('mouseenter', 'points', function (e) {
    map.on('mouseover', 'points', function(e) {
      map.getCanvas().style.cursor = 'pointer';
      popupClick.remove();
      mapPopup(e, popupHover);
    });
    // map.on('mouseleave', 'points', function () {
    map.on('mouseout', 'points', function() {
      map.getCanvas().style.cursor = '';
      popupHover.remove();
    });

    // Add source and layer whenever base style is loaded
    map.on('style.load', () => {
      addAdditionalSourceAndLayer();
    });


  });


  function addAdditionalSourceAndLayer() {

    // --- Add data sources (addAdditionalSourceAndLayer)

    map.addSource('routeDS', basicRouteDS);

    map.addSource('pointsDS', pointsDS);

    // --- Add layers (addAdditionalSourceAndLayer)

    map.addLayer({
      id: "route",
      type: "line",
      source: "routeDS",
      layout: {
        // "line-join": "round",
        // "line-cap": "round",
      },
      paint: {
        "line-color": "#37B678",
        "line-width": 3,
        "line-dasharray": ["get", "dasharray"],
      }
    });

    map.addLayer({
      id: "points",
      type: "circle",
      source: "pointsDS",
      paint: {
        "circle-color": "#FFF",
        "circle-radius": ["interpolate", ["linear"],
          ["zoom"],
          3, 3,
          6, 4,
          18, 20,
        ],
        // "circle-radius": ["step", ['zoom'], 4, 4, 5.5, 7, 8 ],
        "circle-stroke-width": 1.5,
        "circle-stroke-color": "#222",
      }
    });
  }

  // --------------------------------
  // Handle map visibility
  // --------------------------------

  // const current = localStorage.getItem("mapVisible") === "true";
  toggleMapStyle(currentMapVisibility);


  // --------------------------------
  // Functions
  // --------------------------------

  function getStoryPlacesFromKirbyData(kirbyData) {

    // --- data from kirby
    var startLon = +kirbyData.departurelon;
    var startLat = +kirbyData.departurelat;
    var startPlace = kirbyData.departureplace;
    var legs = kirbyData.legs;
    var startPlaceIsValid = (startLon && startLat) ? true : false;

    // --- fit into js format
    var firstPlace = {
      "name": startPlace,
      "lon": startLon,
      "lat": startLat,
      "index": 0,
      "isValidPlace": startPlaceIsValid,
    }
    var places = [firstPlace];
    for (var i = 0; i < legs.length; i++) {
      var leg = legs[i];
      var lonFrom = (i > 0) ? (legs[i - 1].lon) : startLon
      var latFrom = (i > 0) ? (legs[i - 1].lat) : startLat
      var placeFrom = (i > 0) ? (legs[i - 1].place) : startPlace
      var lonTo = leg.lon
      var latTo = leg.lat

      var isValidPlace = false;
      var isValidTrip = false;
      if (leg.lon && leg.lat) {
        isValidPlace = true;
        if (lonFrom && latFrom) {
          isValidTrip = true;
        }
      }

      var place = {
        "name": leg.place,
        "lon": leg.lon,
        "lat": leg.lat,
        "index": i + 1,
        "isValidPlace": isValidPlace,
        "isValidTrip": isValidTrip,
        "tripComments": leg.comments,
        "tripPlaceFrom": placeFrom,
        "tripPlaceTo": leg.place,
        "tripLonFrom": lonFrom,
        "tripLatFrom": latFrom,
        "tripLonTo": leg.lon,
        "tripLatTo": leg.lat,
        "tripTransport": leg.transport,
      }
      places.push(place);
    }
    return places;
  }

  // function getPlaces_v1 () {

  //   // --- data from kirby
  //   var startLon = <?= $page->departureLon()->value() ?>;
  //   var startLat = <?= $page->departureLat()->value() ?>;
  //   var startPlace = `<?= $page->departurePlace()->value() ?>`;
  //   var legs = <?= $page->legs()->toStructure()->toJson() ?>;

  //   // --- fit into js format
  //   var firstPlace = {
  //     "name": startPlace,
  //     "lon": startLon,
  //     "lat": startLat,
  //     "index": 0,
  //   }
  //   var places = [firstPlace];
  //   for (var i = 0; i < legs.length; i++) {
  //     var leg = legs[i];
  //     var lonFrom = (i > 0) ? (legs[i-1].lon) : startLon
  //     var latFrom = (i > 0) ? (legs[i-1].lat) : startLat
  //     var placeFrom = (i > 0) ? (legs[i-1].place) : startPlace
  //     var lonTo = leg.lon
  //     var latTo = leg.lat

  //     var isValidPlace = false;
  //     var isValidTrip = false;
  //     if (leg.lon && leg.lat) { 
  //       isValidPlace = true;
  //       if (lonFrom && latFrom) { 
  //         isValidTrip = true;
  //       }
  //     }

  //     var place = {
  //       "name": leg.place,
  //       "lon": leg.lon,
  //       "lat": leg.lat,
  //       "index": i + 1,
  //       "isValidPlace": isValidPlace,
  //       "isValidTrip": isValidTrip,
  //       "tripComments": leg.comments,
  //       "tripPlaceFrom": placeFrom,
  //       "tripPlaceTo": leg.place,
  //       "tripLonFrom": lonFrom,
  //       "tripLatFrom": latFrom,
  //       "tripLonTo": leg.lon,
  //       "tripLatTo": leg.lat,
  //       "tripTransport": leg.transport,
  //     }
  //     places.push(place);
  //   }
  //   return places;
  // }


  function getBasicRouteDSFromState() {

    // --- prepare data for mapbox
    var mbData = {
      'type': 'geojson',
      'data': {
        'type': 'FeatureCollection',
        'features': []
      }
    }

    var validTripPlaces = state.storyPlaces.filter(e => (e.isValidTrip === true));

    for (var i = 0; i < validTripPlaces.length; i++) {
      var place = validTripPlaces[i];
      var dashArray = kirbyTransportToDashArray(place.tripTransport)
      var feature = {
        'type': 'Feature',
        'properties': {},
        'properties': {
          "dasharray": dashArray,
        },
        'geometry': {
          'type': 'LineString',
          'coordinates': [
            [place.tripLonFrom, place.tripLatFrom],
            [place.tripLonTo, place.tripLatTo],
          ],
        }
      }
      mbData.data.features.push(feature);
    }

    return mbData;

  }


  function getPointsDSFromState() {

    var validPlaces = state.storyPlaces.filter(e => (e.isValidPlace === true));

    return {
      'type': 'geojson',
      'data': {
        "type": "FeatureCollection",
        "features": validPlaces.map(function(e, i) {
          var props = clone(e);
          props.isOpen = state.openStoryId == e.id;
          return {
            "type": "Feature",
            "geometry": {
              "type": "Point",
              "coordinates": [e.lon, e.lat],
            },
            "properties": props,
          }
        }),
      }
    };
  }


  function mapPopup(e, popupObject) {
    var data = e.features[0].properties;
    console.log("mapPopup", data)

    var coordinates = e.features[0].geometry.coordinates.slice();
    while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
      coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
    }
    var markup = templatePopup({
      "place": data,
    });
    popupObject
      .setLngLat(coordinates)
      .setHTML(markup)
      .addTo(map);
  }

  function toggleMapStyle(bool) {
    if (typeof bool == 'undefined') {
      bool = true;
    }
    if (bool === true) {
      state.currentMapStyle = mbStyleWithBg;
    } else {
      state.currentMapStyle = mbStyleEmpty;
    }
    map.setStyle(state.currentMapStyle);
    localStorage.setItem("mapVisible", String(bool));
  }

  // ----------------------------------
  // Test geojson2svg
  // ----------------------------------

  var gj = basicRouteDS.data
  console.log("----------------")
  console.log("Test geojson2svg")
  console.log(gj)
  const converter = new GeoJSON2SVG( /*options*/ );
  const svgStrings = converter.convert(gj, /*options*/ );
  console.log("svgStrings", svgStrings)

  // via https://rawgit.com/gagan-bansal/geojson2svg/master/examples/world.html

  drawGeoJSON(gj)

  function drawGeoJSON(geojson) {
    var geojson3857 = reproject.reproject(
      geojson, 'EPSG:4326', 'EPSG:3857', proj4.defs);
    var svgMap = document.getElementById('map');
    var convertor = new GeoJSON2SVG({
      viewportSize: {
        width: 800,
        height: 800
      },
      attributes: {
        'style': 'stroke:#006600; fill: #F0F8FF;stroke-width:0.5px;',
        'vector-effect': 'non-scaling-stroke',
      },
      explode: false,
    });
    var svgElements = convertor.convert(geojson3857 /*geojson*/ );
    // var parser = new DOMParser();
    svgElements.forEach(function(svgStr) {
      var svg = parseSVG(svgStr);
      svgMap.appendChild(svg);
    });
  }
  //parseSVG from http://stackoverflow.com/questions/3642035/jquerys-append-not-working-with-svg-element
  function parseSVG(s) {
    var div = document.createElementNS('http://www.w3.org/1999/xhtml', 'div');
    div.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg">' + s + '</svg>';
    var frag = document.createDocumentFragment();
    while (div.firstChild.firstChild)
      frag.appendChild(div.firstChild.firstChild);
    return frag;
  }


  // ----------------------------------

  // --- Handlebars templates ---------
  // --- see https://tutorialzine.com/2015/01/learn-handlebars-in-10-minutes

  var templatePopup = Handlebars.compile($("#hb-popup").html());
</script>

<?php snippet("footer") ?>