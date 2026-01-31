<?php
/*  
#006600 - darker color that works well for lines in HP
#44783b - last fede fraga green
*/
$lineColor = "#44783b";

if (!$kirby->user()) {
  die("You must log in to see this page. <a href='" . url('/panel') . "'>Do</a>");
}

$storySlug = $_GET["story"];
$storyPage = page("stories/$storySlug");
if (!$storyPage) {
  $storyPage = page("stories")->drafts()->findBy("slug", $storySlug);
}
if (!$storyPage) {
  die('Story not found.');
}
$numLegs = $storyPage->legs()->toStructure()->count();

$openrouteservice_apikey = "5b3ce3597851110001cf624837bcd0c908f0494794652a5a7720f62a";

function getPageThumb($sp, $w = 150, $h = 150)
{
  $coverImage = $sp->files()->findBy("name", "cover");
  if (!$coverImage) {
    return "https://placehold.co/50x50/transparent/png?text=No+cover";
  } else {
    return
      $coverImage->thumb(['width' => $w, 'height' => $h,/*  'crop' => true */])->url();
  }
}

$stateLabel = [
  "listed" => "<span class='color-listed'>●</span>", // Listed
  "unlisted" => "<span class='color-unlisted'>●</span>", // Unlisted
  "draft" => "<span class='color-draft'>○</span>", // Draft
];
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

  <!-- Turf.js -->
  <?= js(['assets/lib/turf-6.5.0.min.js']) ?>

  <!--
  geojson2svg - https://github.com/gagan-bansal/geojson2svg
  This creates a global variable 'GeoJSON2SVG' as a Class. 
  -->
  <script src="<?= $kirby->url('assets') ?>/lib/geojson2svg-master/dist/geojson2svg.min.js"></script>
  <script src="<?= $kirby->url('assets') ?>/lib/reproject/reproject.min.js"></script>
  <script src="<?= $kirby->url('assets') ?>/lib/proj4js/2.2.2/proj4.js"></script>

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
            <p>
              <a href="<?= $site->pagePanelUrl($storyPage->id(), true) ?>">↖ Edit in the panel</a>
              &nbsp; | &nbsp;
              <a href="<?= $storyPage->url() ?>" target="_blank">View story</a>
            </p>
            <a class="d-flex align-items-center justify-content-between no-u pointer"
              onclick="togglePagesList()">
              <h1><?= $storyPage->title() ?></h1>
              <span>▼</span>
            </a>
            <div id="pages-list" style="display: none;">
              <hr class="bleed" />
              <?php foreach (page("stories")->childrenAndDrafts() as $sp): ?>
                <a class="d-block hover-white-soft-bg no-u" href="<?= $site->url() ?>/story-tools?story=<?= $sp->slug() ?>">
                  <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                      <img src="<?= getPageThumb($sp) ?>" alt="" style="width: 50px; height: 50px; object-fit: cover;">
                      <span class="ml-3"><?= $sp->title() ?></span>
                    </div>
                    <div class="font-sans-s pr-2">
                      <?= $stateLabel[$sp->status()] ?>
                    </div>
                  </div>
                </a>
              <?php endforeach ?>
            </div>
            <hr class="bleed" />
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
          $legFrom = $storyPage->legs()->toStructure()->nth($i - 1);
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

        $geojsonUse = $legTo->geojsonUse()->bool();
        $geojsonLeg = $legTo->geojsonLeg()->value();
      ?>

        <div class="leg-item">

          <form id="leg-geojson-form-<?= $i ?>" class="leg-geojson-form">

            <div class="container-fluid">
              <div class="row">
                <div class="col-6 my-2">
                  <div>
                    <p
                      class="font-weight-600 pointer"
                      onmouseover="highlightLeg(<?= $i + 1 ?>);"
                      onmouseout="highlightLeg(null);"
                      onclick="fitBoundsForLeg(<?= $i ?>);">
                      <?= $startPlace ?> &rarr; <?= $legTo->place()->value() ?> by <?= $legTo->transport()->value() ?>
                    </p>
                    <div class="opacity-50">
                      <p class="font-sans-xs mb-1"><a class="pointer" onclick="copyGeojsonPoint(<?= $startLon ?>, <?= $startLat ?>)">(Point)</a> [<?= $startLon ?>, <?= $startLat ?>] <?= $startPlace ?></p>
                      <p class="font-sans-xs mb-1"><a class="pointer" onclick="copyGeojsonPoint(<?= $arrivalLon ?>, <?= $arrivalLat ?>)">(Point)</a> [<?= $arrivalLon ?>, <?= $arrivalLat ?>] <?= $legTo->place()->value() ?></p>
                      <p class="font-sans-xs mb-1"><a class="pointer" onclick="copyGeojsonLine(<?= $startLon ?>, <?= $startLat ?>, <?= $arrivalLon ?>, <?= $arrivalLat ?>)">(Line)</a></p>
                    </div>
                  </div>
                  <!--                   
                  <p>
                    <a class="" href="<?= $apiCall ?>" onclick="handleRouteApiCall(event, '<?= $targetId ?>', '<?= $apiCall ?>')">generate route line</a>
                  </p>
                   -->
                  <div>
                    <!-- <label>
                      <div class="switch small">
                        <input type="checkbox" name="use" <?= $geojsonUse ? "checked" : "" ?> id="leg-<?= $i ?>-use">
                        <span class="slider round"></span>
                      </div>
                      <span class="ml-2">Use route line</span>
                    </label> -->
                  </div>
                </div>
                <div class="col-6 my-2">

                  <?php /*  
                <!-- OLD -->
                <textarea id="<?= $targetId ?>"><?= $geojsonLeg ?></textarea>
                <button onclick="previewGeoJson('<?= $targetId ?>')">&nbsp;&raquo;&nbsp;</button>
                */ ?>

                  <textarea id="<?= $targetId ?>" name="geojson"><?= $geojsonLeg ?></textarea>
                  <input type="hidden" name="storyUid" value="<?= $storyPage->uid() ?>">
                  <input type="hidden" name="legIndex" value="<?= $i ?>">
                  <input type="hidden" name="textareaId" value="<?= $targetId ?>">
                  <div class="buttons">
                    <div>
                      Route
                      <a class="" href="<?= $apiCall ?>" onclick="handleRouteApiCall(event, '<?= $targetId ?>', '<?= $apiCall ?>')">↻</a>
                    </div>
                    <div>
                      <label>
                        <div class="switch small">
                          <input type="checkbox" name="use" <?= $geojsonUse ? "checked" : "" ?> id="leg-<?= $i ?>-use">
                          <span class="slider round"></span>
                        </div>
                        <!-- <span class="ml-2">Use route line</span> -->
                      </label>
                      <button type="submit" class="button very-small green-dark">Save</button>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </form>

        </div>

      <?php endfor ?>

      <?php if ($numLegs): ?>

        <!---------------------------------- GEOJSON2SVG -->
        <div class="container-fluid mt-5">
          <div class="row">
            <div class="col-6">
              <div class="svg-square-container p-3">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  id="svg-map-target"
                  viewBox="0 0 800 800"
                  preserveAspectRatio="xMidYMid meet"
                  width="800"
                  height="800"
                  x="0"
                  y="0"
                  class="geojson2svg-test"
                  style="display: block;"></svg>
              </div>
            </div>
            <div class="col-6">
              <div class="mb-4">
                <a class="button small green-dark" onclick="refresh()">Reload</a>
                <p class="font-sans-xs py-2">Updates the image with latest lines settings</p>
              </div>
              <div class="mb-4">
                <a class="button small green-dark" id="save-svg-button">Save SVG</a>
                <p class="font-sans-xs py-2">Appears in homepage and other line previews on the site</p>
              </div>
              <div class="mb-4">
                <a class="button small green-dark" id="save-png-button">Save PNG</a>
                <p class="font-sans-xs py-2">For the panel and sharing on social media</p>
              </div>
            </div>
          </div>
        </div>
        <!---------------------------------- GEOJSON2SVG -->

      <?php else: ?>

        <div class="container-fluid mt-5">
          <div class="row">
            <div class="col-12">
              <p>This story has no line yet.</p>
              <p><a class="button small green-dark" href="<?= $site->pagePanelUrl($storyPage->id(), true) ?>">Add places</a></p>
            </div>
          </div>
        </div>

      <?php endif ?>

      <div class="spacer py-5"></div>

    </div>

    <div class="right-mapbox-preview">
      <div id="mapbox-container">
        <div id="buttons-container">
          <a class="button small grey-light" href="https://geojson.io" target="_blank">Open geojson.io</a>
          <a class="button small green-dark" onclick="fitBounds(state.geojsonRoute)">Fit</a>
        </div>
      </div>
    </div>
  </div>

  <div id="message-container" class=""></div>
  <!--   
  <div id="message-container" class="visible error">Error while saving</div>
  <div id="message-container" class="visible success">Saved successfully.</div> 
  -->

  <script>
    var numLegs = <?= $numLegs ?>;

    // -----------------------------
    // MAPBOX INIT
    // -----------------------------

    const mbToken = "<?= option('mapbox.token') ?>";
    const mbStyle = "<?= option('mapbox.style.withBg') ?>";
    const state = {}

    const legObjects = getLegObjects();
    state.geojsonRoute = getInitialRoute2(legObjects);

    mapboxgl.accessToken = mbToken;
    const map = new mapboxgl.Map({
      container: 'mapbox-container',
      style: mbStyle,
      center: [17.77091423340742, 36.0515231640615],
      zoom: 1,
      attributionControl: false, // Disable default
    });
    map.addControl(new mapboxgl.AttributionControl({
      compact: true
    }));

    if (numLegs === 0) {
      alert("This story has no legs defined yet.");
    }

    // --- prepare data for mapbox
    var mbRoute = {
      'type': 'geojson',
      'data': state.geojsonRoute
    }

    function fitBounds(geojson) {

      // fit to bounds
      var coordinates = [];
      geojson.features.forEach(feature => {
        feature.geometry.coordinates.forEach(coo => {
          coordinates.push(coo)
        })
      })
      var bounds = getBounds(coordinates)
      map.fitBounds(bounds, {
        padding: 90,
      })
    }

    function fitBoundsForLeg(legIndex) {
      // fit to bounds of a specific leg
      var feature = state.geojsonRoute.features[legIndex];
      var coordinates = feature.geometry.coordinates;
      var bounds = getBounds(coordinates)
      map.fitBounds(bounds, {
        padding: 90,
      })
    }

    map.on('load', () => {
      map.addSource('route', mbRoute);
      map.addLayer({
        'id': 'route',
        'type': 'line',
        'source': 'route',
        'layout': {
          'line-join': 'round',
          'line-cap': 'round',
        },
        'paint': {
          'line-color': '<?= $lineColor ?>',
          'line-width': 3,
          'line-dasharray': ['get', 'dasharray'],
        }
      });

      fitBounds(state.geojsonRoute);
    });

    function getBasicLineString(legIndex) {
      var leg = legObjects[legIndex];
      console.log("getBasicLineString for leg:", legIndex, leg);
      const geojson = {
        "type": "Feature",
        "geometry": {
          "type": "LineString",
          "coordinates": [
            [leg.from.lon, leg.from.lat],
            [leg.to.lon, leg.to.lat],
          ]
        },
        "properties": {}
      };
      return geojson;
    }

    // ---------------------------------- Save leg geojson -----------------------------


    document.addEventListener("DOMContentLoaded", () => {

      document.querySelectorAll(".leg-geojson-form").forEach(form => {

        form.addEventListener("submit", async (event) => {
          event.preventDefault();

          showMessage("Saving…");

          const formData = new FormData(form);

          try {
            const response = await fetch("/update-leg-geojson", {
              method: "POST",
              body: formData
            });

            const result = await response.json();
            console.log("Result from saving leg geojson:", result);

            // --- Handle non-OK HTTP status ---
            if (!response.ok) {
              showMessage(result.message || "Error saving", "error");
              return;
            }

            // --- Handle API-level failure ---
            if (result.status !== "success") {
              showMessage(result.message || "Error saving", "error");
              return;
            }

            // --- Handle missing geojson ---
            if (!result.geojsonLegSaved || result.legIndex === undefined) {
              showMessage("Saved but no geojson was returned", "success");
              return;
            }

            // --- At this point we can update the map ---
            const use = result.newUse === "1" || result.newUse === true || result.newUse === "true";
            let newGeojson = JSON.parse(result.geojsonLegSaved);
            if (!use) {
              newGeojson = getBasicLineString(result.legIndex);
            }
            const resUpdate = updateLegOnMap({
              geojsonLeg: newGeojson,
              legIndex: result.legIndex,
            });
            if (!resUpdate) {
              showMessage("Saved but error updating map on frontend", "error");
              return;
            }

            // put the returned geojson back into the textarea
            const targetTextarea = form.querySelector(`textarea#${result.textareaId}`);
            targetTextarea.value = result.geojsonLegSaved;
            showMessage("Saved, map updated", "success");

          } catch (err) {
            console.error("Error (38745923)", err);
            showMessage("Something went wrong. See console for details (38745923)", "error");
          }

        });

      });

    });

    function updateLegOnMap({
      geojsonLeg,
      legIndex
    }) {
      let legGeometry = geojsonLeg.geometry;
      if (geojsonLeg.type == "FeatureCollection") {
        legGeometry = geojsonLeg.features[0].geometry;
      }
      state.geojsonRoute.features[legIndex].geometry = legGeometry;
      map.getSource("route").setData(state.geojsonRoute);
      return true;
    }

    // --------------- Handle feedback messages after server-side actions ---------------

    function showMessage(message, type = "") {
      const container = document.getElementById("message-container");
      container.textContent = message;
      let styleClass = "";
      if (type === "error") {
        styleClass = "error";
      } else if (type === "success") {
        styleClass = "success";
      }
      container.className = "visible " + styleClass;
      clearTimeout(state.msgTimeout);
      state.msgTimeout = setTimeout(() => {
        container.className = "";
      }, 5000);
    }

    // ---------------------------------- Test geojson2svg -----------------------------
    // 
    // 
    const simplified = turf.simplify(state.geojsonRoute, {
      tolerance: 0.05, // adjust for more/less simplification
      highQuality: false
    });

    // via https://rawgit.com/gagan-bansal/geojson2svg/master/examples/world.html

    drawGeoJSON(simplified);
    // drawGeoJSON(state.geojsonRoute);

    function drawGeoJSON(geojson) {
      var geojson3857 = reproject.reproject(
        geojson, 'EPSG:4326', 'EPSG:3857', proj4.defs);
      var svgElement = document.getElementById('svg-map-target');
      var converter = new GeoJSON2SVG({
        viewportSize: {
          width: 800,
          height: 800
        },
        attributes: {
          'style': 'stroke:<?= $lineColor ?>; fill: none;stroke-width:1.5px;',
          'vector-effect': 'non-scaling-stroke',
        },
        explode: false,
      });
      var svgElements = converter.convert(geojson3857);
      svgElements.forEach(function(svgStr) {
        var svg = parseSVG(svgStr);
        svgElement.appendChild(svg);
      });

      const bbox = getSvgElementBBox(svgElement);
      console.log("SVG BBOX", bbox);
      svgElement.setAttribute("viewBox", `${bbox.x} ${bbox.y} ${bbox.width} ${bbox.height}`);

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
    // ---------------------------------- Test geojson2svg -----------------------------
    // 
    // and then...
    // 
    // ---------------------------------- Save SVG -------------------------------------
    const button = document.getElementById('save-svg-button');

    // Replace this with however you get your SVG string
    function getSvgCode() {
      // Example: SVG element in DOM
      const svgEl = document.querySelector('#svg-map-target');
      return svgEl ? svgEl.outerHTML : '';
    }

    button.addEventListener('click', async () => {
      const svg = getSvgCode();
      if (!svg) {
        alert('No SVG found.');
        return;
      }

      try {
        const res = await fetch('<?= url("save-story-svg") ?>', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            id: '<?= $storyPage->id() ?>',
            svg: svg,
          }),
          credentials: 'same-origin', // if you rely on session auth
        });

        const json = await res.json();
        if (json.status === 'success') {
          showMessage(json.message, 'success');
        } else {
          console.error('Error saving SVG:', json.message);
        }
      } catch (err) {
        console.error('Request failed (23754123):', err);
        showMessage('SVG NOT saved. See console for details (23754123).', 'error');
      }
    });
    // 
    // 
    // ---------------------------------- Save SVG -----------------------------

    function getSvgElementBBox(svgEl) {
      // Ensure it's in the DOM; otherwise getBBox() won't work
      let needsAttach = !document.body.contains(svgEl);
      let temp;
      if (needsAttach) {
        temp = document.createElement("div");
        temp.style.position = "absolute";
        temp.style.left = "-9999px";
        temp.style.top = "-9999px";
        temp.style.opacity = "0";
        document.body.appendChild(temp);
        temp.appendChild(svgEl);
      }
      const bbox = svgEl.getBBox();
      if (needsAttach) {
        temp.remove();
      }
      return {
        x: bbox.x,
        y: bbox.y,
        width: bbox.width,
        height: bbox.height
      };
    }


    // save PNG ----------------------------------------------------------------

    document.getElementById("save-png-button").addEventListener("click", async () => {
      const svgEl = document.querySelector(".svg-square-container svg");

      // 1. Serialize DOM node → SVG string
      const svgString = new XMLSerializer().serializeToString(svgEl);

      // 2. Call the conversion function
      const pngBlob = await svgStringToPngBlob(svgString);

      // 3. Upload to Kirby
      try {
        const result = await uploadCoverViaRoute("<?= $storyPage->uid() ?>", pngBlob);
        console.log("Uploaded!", result);
        console.log("result should be parsed JSON", result);
        if (result.status === "success") {
          showMessage(result.message, 'success');
        }


        // update the cover image preview on the page
        // document.querySelector(".cover-image-preview img").src = result.url;


      } catch (err) {
        console.error(err);
        showMessage('PNG NOT saved. See console for details.', 'error');
      }

      // Or Download (for testing)
      // const url = URL.createObjectURL(pngBlob);
      // const a = document.createElement("a");
      // a.href = url;
      // a.download = "export.png";
      // a.click();
      // URL.revokeObjectURL(url);
    });

    async function svgStringToPngBlob(svgString, {
      width,
      height,
      scale = 1
    } = {}) {
      // 1. Create a Blob from SVG
      const svgBlob = new Blob([svgString], {
        type: 'image/svg+xml'
      });
      const url = URL.createObjectURL(svgBlob);

      try {
        // 2. Load into an <img>
        const img = new Image();
        img.decoding = 'async';
        img.src = url;

        await new Promise((resolve, reject) => {
          img.onload = resolve;
          img.onerror = reject;
        });

        const w = (width || img.width) * scale;
        const h = (height || img.height) * scale;

        // 3. Draw to canvas
        const canvas = document.createElement('canvas');
        canvas.width = w;
        canvas.height = h;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0, w, h);

        // 4. Export as PNG Blob
        return await new Promise(resolve =>
          canvas.toBlob(resolve, 'image/png')
        );
      } finally {
        URL.revokeObjectURL(url);
      }
    }

    async function uploadCoverViaRoute(slug, pngBlob) {
      const form = new FormData();
      form.append("file", pngBlob, "cover.png");

      const res = await fetch(`/story-upload/${slug}`, {
        method: "POST",
        body: form
      });

      const json = await res.json();
      console.log(json);
      return json;
    }

    // save PNG ----------------------------------------------------------------




    // -----------------------------
    // FUNCTIONS
    // -----------------------------

    copyGeojsonPoint = (lon, lat) => {
      geojson = {
        "type": "FeatureCollection",
        "features": [{
          "type": "Feature",
          "geometry": {
            "type": "Point",
            "coordinates": [lon, lat]
          },
          "properties": {}
        }]
      };
      navigator.clipboard.writeText(JSON.stringify(geojson));
      showMessage("point copied, paste it on geojson.io", "success");
    }
    copyGeojsonLine = (lon1, lat1, lon2, lat2) => {
      geojson = {
        "type": "FeatureCollection",
        "features": [{
          "type": "Feature",
          "geometry": {
            "type": "LineString",
            "coordinates": [
              [lon1, lat1],
              [lon2, lat2]
            ]
          },
          "properties": {}
        }]
      };
      navigator.clipboard.writeText(JSON.stringify(geojson));
      showMessage("line copied, paste it on geojson.io", "success");
    }

    function togglePagesList() {
      var listEl = document.getElementById("pages-list");
      if (listEl.style.display === "none") {
        listEl.style.display = "block";
      } else {
        listEl.style.display = "none";
      }
    }

    function handleRouteApiCall(e, contentTarget, url) {
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


    function previewGeoJson(textAreaId) {
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












    function getLegObjects() {
      if (!numLegs) {
        return [];
      }

      // --- data from kirby
      var startLon = <?= $storyPage->departureLon()->value() ?? "null" ?>;
      var startLat = <?= $storyPage->departureLat()->value() ?? "null" ?>;
      var startPlace = `<?= $storyPage->departurePlace()->value() ?? "null" ?>`;
      var legs = <?= $storyPage->legs()->toStructure()->toJson() ?? "null" ?>;

      // --- prepare js objects
      var legObjects = [];
      for (var i = 0; i < legs.length; i++) {
        var lonFrom = (i > 0) ? (legs[i - 1].lon) : (startLon)
        var latFrom = (i > 0) ? (legs[i - 1].lat) : (startLat)
        var lonTo = legs[i].lon
        var latTo = legs[i].lat
        var legObject = {
          from: {
            lon: lonFrom,
            lat: latFrom,
            name: (i > 0) ? (legs[i - 1].place) : (startPlace),
          },
          to: {
            lon: lonTo,
            lat: latTo,
            name: legs[i].place,
          },
          transport: legs[i].transport,
          useGeojson: legs[i].geojsonuse === "true" || legs[i].geojsonuse === true,
          geojsonLeg: legs[i].geojsonleg,
        };
        legObjects.push(legObject);
      }
      return legObjects;
    }

    function getInitialRoute2(legObjects) {
      if (legObjects.length === 0) {
        return null;
      }

      // --- prepare data for mapbox
      var geojson = {
        'type': 'FeatureCollection',
        'features': []
      };

      legObjects.forEach((leg, i) => {
        var geometry = {
          'type': 'LineString',
          'coordinates': [
            [leg.from.lon, leg.from.lat],
            [leg.to.lon, leg.to.lat],
          ],
        };
        if (leg.useGeojson) {
          // console.log(leg, "using geojson for leg " + i);
          try {
            var geojsonLeg = JSON.parse(leg.geojsonLeg);
            // console.log("geojsonLeg", geojsonLeg)
            geometry = geojsonLeg.features[0].geometry;
          } catch (e) {
            console.error("Error parsing geojson for leg " + i, leg, e);
          }
        }

        var feature = {
          'type': 'Feature',
          'properties': {
            "dasharray": kirbyTransportToDashArray(leg.transport),
            "legIndex": i + 1,
          },
          'geometry': geometry,
        }
        geojson.features.push(feature);
        // console.log("feature", feature)
      });
      return geojson;
    }




    // function getInitialRoute() {
    //   if (!numLegs) {
    //     return null;
    //   }

    //   // --- data from kirby
    //   var startLon = <?= $storyPage->departureLon()->value() ?? "null" ?>;
    //   var startLat = <?= $storyPage->departureLat()->value() ?? "null" ?>;
    //   var startPlace = `<?= $storyPage->departurePlace()->value() ?? "null" ?>`;
    //   var legs = <?= $storyPage->legs()->toStructure()->toJson() ?? "null" ?>;
    //   console.log("legs", legs)

    //   // --- prepare data for mapbox
    //   var geojson = {
    //     'type': 'FeatureCollection',
    //     'features': []
    //   };

    //   for (var i = 0; i < legs.length; i++) {
    //     var lonFrom = (i > 0) ? (legs[i - 1].lon) : (startLon)
    //     var latFrom = (i > 0) ? (legs[i - 1].lat) : (startLat)
    //     var lonTo = legs[i].lon
    //     var latTo = legs[i].lat

    //     var geometry = {
    //       'type': 'LineString',
    //       'coordinates': [
    //         [lonFrom, latFrom],
    //         [lonTo, latTo],
    //       ],
    //     };
    //     if (legs[i].geojsonuse == "true" && legs[i].geojsonleg != "") {
    //       console.log(legs[i], "using geojson for leg " + i);
    //       try {
    //         var geojsonLeg = JSON.parse(legs[i].geojsonleg);
    //         console.log("geojsonLeg", geojsonLeg)
    //         geometry = geojsonLeg.features[0].geometry;
    //       } catch (e) {
    //         console.error("Error parsing geojson for leg " + i, e);
    //       }
    //     }

    //     var feature = {
    //       'type': 'Feature',
    //       'properties': {
    //         "dasharray": kirbyTransportToDashArray(legs[i].transport),
    //         "legIndex": i + 1,
    //       },
    //       'geometry': geometry,
    //     }
    //     geojson.features.push(feature);
    //     console.log("feature", feature)
    //   }

    //   return geojson;
    // }


    function highlightLeg(index) {
      if (index === null) {
        // All segments fully visible
        map.setPaintProperty('route', 'line-color', "<?= $lineColor ?>");
      } else {
        // Dim all segments except the active one
        map.setPaintProperty('route', "line-color", [
          "case", ["==", ["get", "legIndex"], index], "<?= $lineColor ?>", "rgba(173, 173, 160, 0.5)",
        ]);
      }

    }

    function refresh() {
      location.reload();
    }
  </script>

</body>

</html>