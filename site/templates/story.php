<?php
$from = getFromPlace($page);
$to = getToPlace($page);
?>

<?php snippet("header", ["tallMenu" => true]) ?>

<?php snippet("menu", ["subtitle" => "$from &rarr; $to", "showSwitch" => true]) ?>

<?php snippet('handlebars-templates') ?>

<section class="map-area">
  <div id="map-container"></div>
  <div class="info">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="contents">
            <h2><?= $page->title() ?></h2>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <?= $page->text()->kt() ?>
    </div>
  </div>
</div>

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
    activeLegIndex: null,
  }
  console.log("places", state.storyPlaces)

  var paddingValues = {
    top: 80,
    bottom: 80,
    left: 380,
    right: 80
  };

  // --------------------------------
  // Mapbox init
  // --------------------------------

  // AP
  const mbToken = "<?= option('mapbox.token') ?>";
  const mbStyleWithBg = "<?= option('mapbox.style.withBg') ?>";
  const mbStyleEmpty = "<?= option('mapbox.style.empty') ?>";

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
      padding: paddingValues,
    })

    // --- Create popups to be used later

    popupClick = new mapboxgl.Popup({
      closeButton: true,
      closeOnClick: true,
      offset: 4,
      className: "ck-map-popup",
    });
    popupHover = new mapboxgl.Popup({
      closeButton: false,
      closeOnClick: true,
      offset: 8,
      className: "ck-map-popup",
    });

    // --- Handle map events

    map.on('click', 'points', function(e) {
      var data = e.features[0].properties;
      console.log("click point", data)
      popupHover.remove();
      highlightLeg(data.index);
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
      // popupHover.remove();
    });

    // Add source and layer whenever base style is loaded
    map.on('style.load', () => {
      addAdditionalSourceAndLayer();
    });


  });


  function highlightLeg(id) {
    if (state.activeLegIndex == id) {
      id = null;
    }

    if (id === null) {
      // All segments fully visible
      map.setPaintProperty('route', 'line-opacity', 1);
      map.setPaintProperty('points', 'circle-opacity', 1);
      map.setPaintProperty('points', 'circle-stroke-opacity', 1);

      var coordinates = [];
      basicRouteDS.data.features.forEach(feature => {
        feature.geometry.coordinates.forEach(coo => {
          coordinates.push(coo)
        })
      });
      var bounds = getBounds(coordinates)
      map.fitBounds(bounds, {
        padding: paddingValues,
      });

    } else {

      // Highlight one segment

      map.setPaintProperty('route', 'line-opacity', [
        'case', ['==', ['get', 'legIndex'], id], 1, 0.2
      ]);
      map.setPaintProperty('points', "circle-opacity", [
        'case', ['==', ['get', 'legIndex'], id], 1, 0.2
      ]);
      map.setPaintProperty('points', "circle-stroke-opacity", [
        'case', ['==', ['get', 'legIndex'], id], 1, 0.2
      ]);

      // Zoom to segment

      var e = state.storyPlaces[id];
      var bbox = null;
      if (e.geojsonUse && e.geojsonLeg) {
        bbox = turf.bbox(e.geojsonLeg);
      } else {
        var offset = 0.5; // degrees
        bbox = [
          e.tripLonFrom - offset,
          e.tripLatFrom - offset,
          e.tripLonTo + offset,
          e.tripLatTo + offset,
        ];
      }
      map.fitBounds(bbox, {
        padding: paddingValues,
      });
    }
    state.activeLegIndex = id;
  }

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

        "line-opacity": [
          "case",
          ["==", ["get", "legIndex"], state.activeLegIndex],
          1, // highlighted segment
          0.1, // all others
        ],
      }
    });

    map.addLayer({
      id: "points",
      type: "circle",
      source: "pointsDS",
      paint: {
        "circle-color": "#222",
        "circle-radius": ["interpolate", ["linear"],
          ["zoom"],
          3, 3,
          6, 5,
          9, 6,
        ],
        "circle-stroke-width": ["interpolate", ["linear"],
          ["zoom"],
          3, 1,
          6, 1.5,
          9, 2,
        ],
        "circle-stroke-color": "#fff",
        "circle-opacity": [
          "case",
          ["==", ["get", "legIndex"], state.activeLegIndex],
          1, // highlighted segment
          0.1, // all others
        ],
        "circle-stroke-opacity": [
          "case",
          ["==", ["get", "legIndex"], state.activeLegIndex],
          1, // highlighted segment
          0.1, // all others
        ],
      }
    });
    highlightLeg(null);
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
        highlightLeg(state.activeLegIndex + 1);
        break;
    }
  });

  // --- Handlebars templates ---------
  // --- see https://tutorialzine.com/2015/01/learn-handlebars-in-10-minutes

  var templatePopup = Handlebars.compile($("#hb-popup").html());
</script>

<?php snippet("footer") ?>