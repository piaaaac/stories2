<?php
$ass = $kirby->url("assets");

$storySlug = $_GET["story"];
$storyPage = page("stories/$storySlug");
$numLegs = $storyPage->legs()->toStructure()->count();

$openrouteservice_apikey = "5b3ce3597851110001cf624837bcd0c908f0494794652a5a7720f62a";
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TOOL <?= $storyPage->title() ?></title>
  
  <!-- css + js -->
  <?= css(['assets/css/bootstrap-custom.css']) ?>
  <?= css(['assets/css/index.css']) ?>
  <?= js(['assets/js/functions-polyfills.js']) ?>
  <?= js(['assets/js/mapbox-utils-ap.js']) ?>

  <!-- mapbox -->
  <link href="https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.css" rel="stylesheet">
  <script src="https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.js"></script>


  <style>
    .leg-editor {
      display: flex;
    }

  </style>
</head>
<body id="story-tools">

<div class="structure">
  <div class="left-items">

    <div class="container-fluid">
      <div class="row">
        <div class="col-12 my-5">
          <h1><?= $storyPage->title() ?></h1>
        </div>
      </div>
    </div>

    <?php for ($i = 0; $i < $numLegs; $i++): 

      $legTo = $storyPage->legs()->toStructure()->nth($i);
      $arrivalLon = $legTo->lon()->value();
      $arrivalLat = $legTo->lat()->value();

      $startLon = $storyPage->departureLon()->value();
      $startLat = $storyPage->departureLat()->value();
      $startPlace = $storyPage->departurePlace()->value();
      if ($i > 0) {
        $legFrom = $storyPage->legs()->toStructure()->nth($i-1);
        $startPlace = $legFrom->place()->value();
        $startLon = $legFrom->lon()->value();
        $startLat = $legFrom->lat()->value();
      }

      $apiCall = "https://api.openrouteservice.org/v2/directions/";
      $apiCall .= "driving-car"; // or "foot-walking"
      $apiCall .= "?api_key=$openrouteservice_apikey";
      $apiCall .= "&start=$startLon,$startLat";
      $apiCall .= "&end=$arrivalLon,$arrivalLat";

      $targetId = "leg-$i";
      ?>
    
      <div class="leg-item">

        <div class="container-fluid">
          <div class="row">
            <div class="col-6 my-2">
              <p><span><?= $startPlace ?></span> - <span><?= $legTo->place()->value() ?></span></p>
              <p><a class="font-mon-400-nor-sm" href="<?= $apiCall ?>" onclick="handleApiCall(event, '<?= $targetId ?>', '<?= $apiCall ?>')">generate route line</a></p>
            </div>
            <div class="col-6 my-2">
              <textarea id="<?= $targetId ?>"></textarea>
              <button onclick="previewGeoJson('<?= $targetId ?>')">&nbsp;&raquo;&nbsp;</button>
            </div>
          </div>
        </div>

      </div>

    <?php endfor ?>

  </div>
  <div class="right-mapbox-preview">
    <div id="mapbox-container"></div>
  </div>
</div>


<script>

// -----------------------------
// MAPBOX INIT
// -----------------------------

var mbToken = "pk.eyJ1IjoicGlhYWFhYyIsImEiOiIxaHI5SmNnIn0.68S9KEJ3TeuhobReU_uDeQ"
var mbStyle = "mapbox://styles/piaaaac/clo2p2b6o00is01pf9ovp59k8"

mapboxgl.accessToken = mbToken;
const map = new mapboxgl.Map({
  container: 'mapbox-container',
  style: mbStyle,
  center: [17.77091423340742, 36.0515231640615],
  zoom: 1,
});

var basicRoute = getBasicRoute();

map.on('load', () => {
  map.addSource('route', basicRoute);
  map.addLayer({
    'id': 'route',
    'type': 'line',
    'source': 'route',
    'layout': {
      'line-join': 'round',
      'line-cap': 'round',
    },
    'paint': {
      'line-color': '#37B678',
      'line-width': 3,
      'line-dasharray': ['get', 'dasharray'],
    }
  });


  console.log("basicRoute", basicRoute)
  // fit to bounds
  var coordinates = [];
  basicRoute.data.features.forEach(feature => {
    feature.geometry.coordinates.forEach(coo => {
      coordinates.push(coo)
    })
  })
  var bounds = getBounds(coordinates)
  map.fitBounds(bounds, {
    padding: 20,
  })

});














// -----------------------------
// FUNCTIONS
// -----------------------------

function handleApiCall (e, contentTarget, url) {
  e.preventDefault();

  const request = new Request(url);

  fetch(request)
    .then((response) => {
      if (response.status === 200) {
        return response.json();
      } else {
        throw new Error("Something went wrong on API server!");
      }
    })
    .then((response) => {
      console.log(response);
      var textArea = document.getElementById(contentTarget)
      textArea.value = JSON.stringify(response);
    })
    .catch((error) => {
      console.error(error);
    });

}


function previewGeoJson (textAreaId) {
  var textArea = document.getElementById(textAreaId);
  var geojson = JSON.parse(textArea.value);
  console.log(geojson)

  map.getSource('route').setData({ 
    type: 'geojson',
    tolerance: 2,
    data: geojson,
  });

  // var bounds = getBounds(geojson.features.map())
  // map.fitBounds(bounds, {
  //   padding: 20,
  // })

}


function getBasicRoute () {

  // --- data from kirby
  var startLon = <?= $storyPage->departureLon()->value() ?>;
  var startLat = <?= $storyPage->departureLat()->value() ?>;
  var startPlace = `<?= $storyPage->departurePlace()->value() ?>`;
  var legs = <?= $storyPage->legs()->toStructure()->toJson() ?>;
  console.log("legs", legs)

  // --- prepare data for mapbox
  var mbData = {
    'type': 'geojson',
    'data': {
      'type': 'FeatureCollection',
      'features': []
    }
  }

  for (var i = 0; i < legs.length; i++) {
    var lonFrom = (i > 0) ? (legs[i-1].lon) : (startLon)
    var latFrom = (i > 0) ? (legs[i-1].lat) : (startLat)
    var lonTo = legs[i].lon
    var latTo = legs[i].lat
    var feature = {
      'type': 'Feature',
      'properties': {},
      'properties': {
        "dasharray": randomLineStyle(),
      },
      'geometry': {
        'type': 'LineString',
        'coordinates': [
          [lonFrom,latFrom],
          [lonTo,latTo],
        ],
      }
    }
    mbData.data.features.push(feature);
  }

  return mbData;


  // map.getSource('route').setData(mbData);

}

</script>

</body>
</html>









