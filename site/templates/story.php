<?php
$from = getFromPlace($page);
$fromCountry = getFromCountry($page);
$to = getToPlace($page);
$toCountry = getToCountry($page);
$subtitle = "$from, $fromCountry → $to, $toCountry";
?>

<?php snippet("header", ["tallMenu" => true]) ?>

<?php snippet("menu", ["subtitle" => "$subtitle", "showSwitch" => true]) ?>

<?php snippet('handlebars-templates') ?>

<section class="map-area">
  <div id="map-container"></div>
  <div class="info">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div id="box-container"></div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  // --------------------------------
  // Kirby > JS data init
  // --------------------------------

  var kirbyData = <?= json_encode($page->content()->toArray()) ?>;
  kirbyData.legs = legs = <?= $page->legs()->toStructure()->toJson() ?>;
  console.log("kirbyData", kirbyData)

  var state = {
    loadCount: 0,
    storyPlaces: getStoryPlacesFromKirbyData(kirbyData),
    openPlaceId: null,
    currentMapStyle: null,
    activeLegIndex: null,
  }
  console.log("places", state.storyPlaces)

  // --------------------------------
  // Mapbox init
  // --------------------------------

  // AP
  const mbToken = "<?= option('mapbox.token') ?>";
  const mbStyleWithBg = "<?= option('mapbox.style.withBg') ?>";
  const mbStyleEmpty = "<?= option('mapbox.style.empty') ?>";

  var popupHover;
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

  // --------------------------------
  // Add data
  // --------------------------------

  map.on('load', () => {

    // --- Stiled attribution
    map.addControl(new mapboxgl.AttributionControl({
      compact: true,
    }), 'top-right');

    // --- Add data + layers
    addAdditionalSourceAndLayer();

    // --- fit to bounds
    fitFullRoute();

    // --- Create popup for later

    popupHover = new mapboxgl.Popup({
      closeButton: false,
      closeOnClick: true,
      offset: 8,
      className: "ck-map-popup",
    });

    // --- Handle map events

    // point events
    map.on('click', 'points', function(e) {

      console.log("event: ", e)
      e.originalEvent.stopPropagation();

      var data = e.features[0].properties;
      console.log("click point", data)
      highlightLeg(data.index);
    });
    map.on('mouseover', 'points', function(e) {
      map.getCanvas().style.cursor = 'pointer';
      mapPopup(e, popupHover);
    });
    map.on('mouseout', 'points', function() {
      map.getCanvas().style.cursor = '';
      popupHover.remove();
    });

    // route events
    map.on('click', 'routeSensi', function(e) {
      var data = e.features[0].properties;
      console.log("click on route", data)
      highlightLeg(data.legIndex);
    });
    map.on('mouseover', 'routeSensi', function(e) {
      map.getCanvas().style.cursor = 'pointer';
    });
    map.on('mouseout', 'routeSensi', function() {
      map.getCanvas().style.cursor = '';
    });

    // Add source and layer whenever base style is loaded
    map.on('style.load', () => {
      addAdditionalSourceAndLayer();
    });
  });


  function fitFullRoute() {
    var coordinates = [];
    basicRouteDS.data.features.forEach(feature => {
      feature.geometry.coordinates.forEach(coo => {
        coordinates.push(coo)
      })
    });
    var bounds = getBounds(coordinates)
    map.fitBounds(bounds, {
      padding: paddingValues(),
    });
  }

  function addAdditionalSourceAndLayer() {
    state.loadCount++;

    // --- Add data sources
    map.addSource('routeDS', basicRouteDS);
    map.addSource('pointsDS', pointsDS);

    // --- Add layers

    // Only for when adding the layer
    var lineColorRule = [
      "case",
      ["==", ["get", "legIndex"], state.activeLegIndex],
      "#37B678", // match → highlight
      "rgba(173, 173, 160, 0.5)" // otherwise → grey
    ];
    if (state.activeLegIndex === null) {
      lineColorRule = "#37B678"; // highlight all
    }

    map.addLayer({
      id: "route",
      type: "line",
      source: "routeDS",
      layout: {
        // "line-join": "round",
        // "line-cap": "round",
      },
      paint: {
        "line-width": 3,
        "line-dasharray": ["get", "dasharray"],
        "line-color": lineColorRule,
        "line-opacity": 1,
      }
    });

    map.addLayer({
      id: "routeSensi",
      type: "line",
      source: "routeDS",
      layout: {
        // "line-join": "round",
        // "line-cap": "round",
      },
      paint: {
        "line-width": 30,
        "line-color": "red",
        "line-opacity": 0,
      }
    });

    map.addLayer({
      id: "points",
      type: "circle",
      source: "pointsDS",
      paint: {
        "circle-radius": ["interpolate", ["linear"],
          ["zoom"], 3, 3, 6, 5, 9, 6,
        ],
        "circle-stroke-width": ["interpolate", ["linear"],
          ["zoom"], 3, 1, 6, 1.5, 9, 2,
        ],
        "circle-color": "#fff",
        "circle-stroke-color": "#222",
      }
    });

    map.addLayer({
      id: "pointsDot",
      type: "circle",
      source: "pointsDS",
      paint: {
        "circle-radius": ["interpolate", ["linear"],
          ["zoom"], 3, 2, 6, 3, 9, 4,
        ],
        "circle-color": "#222",
        "circle-opacity": [
          "case", ["==", ["get", "legIndex"], state.activeLegIndex], 1, 0
        ],
      }
    });

    if (state.loadCount == 1) {
      highlightLeg(null, false);
    }
  }

  function highlightLeg(index, zoom = true) {
    if (state.activeLegIndex == index) {
      state.activeLegIndex = null;
    } else if (index >= state.storyPlaces.length) {
      state.activeLegIndex = null;
    } else if (index < 0) {
      state.activeLegIndex = null;
    } else {
      state.activeLegIndex = index;
    }

    if (state.activeLegIndex === null) {
      // All segments fully visible
      map.setPaintProperty('route', 'line-color', "#37B678");
      map.setPaintProperty('points', 'circle-color', "#fff");
      map.setPaintProperty('points', 'circle-stroke-color', "#222");
      map.setPaintProperty('pointsDot', 'circle-opacity', 0);

      if (zoom) {
        fitFullRoute();
      }

      // Info box
      var markup = templateStoryInfoContents({
        "text": "<?= $page->title() ?> travelled through !!! 21 places on foot, by car and by truck. The trip took 21 days.",
        "name": "<?= $page->title() ?>",
      });
      document.querySelector("#box-container").innerHTML = markup;

    } else {

      // Highlight one segment
      map.setPaintProperty('route', "line-color", [
        "case", ["==", ["get", "legIndex"], state.activeLegIndex], "#37B678", "rgba(173, 173, 160, 0.5)",
      ]);
      map.setPaintProperty('points', "circle-color", "#fff");
      map.setPaintProperty('points', "circle-stroke-color", "#222");
      map.setPaintProperty('pointsDot', 'circle-opacity', [
        "case", ["==", ["get", "legIndex"], state.activeLegIndex], 1, 0,
      ]);

      // Info box
      var e = state.storyPlaces[state.activeLegIndex];
      console.log("highlightLeg", e);
      var markup = templateLegInfoContents({
        "place": e,
        "bars": {
          "transport": 33,
          "trip": 80,
          "permanence": 40,
        }
      });
      document.querySelector("#box-container").innerHTML = markup;

      // Zoom to segment
      if (zoom) {
        var bbox = null;
        if (e.geojsonUse && e.geojsonLeg) {
          bbox = turf.bbox(e.geojsonLeg);
        } else {
          var offset = 0.5;
          var west = Math.min(e.tripLonFrom, e.tripLonTo) || e.lon - offset;
          var south = Math.min(e.tripLatFrom, e.tripLatTo) || e.lat - offset;
          var east = Math.max(e.tripLonFrom, e.tripLonTo) || e.lon + offset;
          var north = Math.max(e.tripLatFrom, e.tripLatTo) || e.lat + offset;
          bbox = [west, south, east, north];
        }
        map.fitBounds(bbox, {
          padding: paddingValues(),
        });
      }
    }
  }


  // --------------------------------
  // Handle map visibility
  // --------------------------------

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
        "geojsonLeg": leg.geojsonleg ? JSON.parse(leg.geojsonleg) : null,
        "geojsonUse": leg.geojsonuse == "true",
      }
      places.push(place);
    }
    return places;
  }


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

      var geometry = {
        'type': 'LineString',
        'coordinates': [
          [place.tripLonFrom, place.tripLatFrom],
          [place.tripLonTo, place.tripLatTo],
        ],
      };
      if (legs[i].geojsonuse == "true" && legs[i].geojsonleg != "") {
        console.log(legs[i], "using geojson for leg " + i);
        try {
          var geojsonLeg = JSON.parse(legs[i].geojsonleg);
          console.log("geojsonLeg", geojsonLeg)
          geometry = geojsonLeg.features[0].geometry;
        } catch (e) {
          console.error("Error parsing geojson for leg " + i, e);
        }
      }

      var feature = {
        'type': 'Feature',
        'properties': {
          "legIndex": place.index,
          "dasharray": dashArray,
        },
        'geometry': geometry,
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
          props.legIndex = e.index;
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

  function paddingValues() {
    return {
      top: 80,
      bottom: 80,
      left: state.activeLegIndex == null ? 280 : 360,
      right: 80
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
      map.scrollZoom.enable();
    } else {
      state.currentMapStyle = mbStyleEmpty;
      map.scrollZoom.disable();
    }
    map.setStyle(state.currentMapStyle);
    localStorage.setItem("mapVisible", String(bool));
  }

  // --- Keyboard

  window.addEventListener('keydown', (e) => {
    switch (e.key) {
      // case 'ArrowUp':
      // case 'ArrowDown':
      case 'ArrowLeft':
        highlightLeg(state.activeLegIndex - 1);
        break;
      case 'ArrowRight':
      case 'Spacebar':
        highlightLeg(state.activeLegIndex + 1);
        break;
    }
  });

  function navigationAction(action) {
    console.log("navigation action", action);
    switch (action) {
      case "highlight-prev-leg":
        highlightLeg(state.activeLegIndex - 1);
        break;
      case "highlight-next-leg":
        highlightLeg(state.activeLegIndex + 1);
        break;
      case "close-leg":
        highlightLeg(null);
        break;
      case "start-story":
        highlightLeg(1);
        break;
    }
  }

  // --- Handlebars templates ---------
  // --- see https://tutorialzine.com/2015/01/learn-handlebars-in-10-minutes

  var templatePopup = Handlebars.compile($("#hb-popup").html());
  var templateStoryInfoContents = Handlebars.compile($("#hb-storyinfocontents").html());
  var templateLegInfoContents = Handlebars.compile($("#hb-leginfocontents").html());
</script>

<?php snippet("footer", ["markup" => false]) ?>