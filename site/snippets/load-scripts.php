
<?= css(['assets/css/bootstrap-custom.css']) ?>
<?= css(['assets/css/index.css']) ?>
<?= js(['assets/js/functions-polyfills.js']) ?>
<?= js(['assets/js/mapbox-utils-ap.js']) ?>

<!-- jQuery -->
<!-- <script src="<?= $kirby->url('assets') ?>/lib/jquery-3.6.0.min.js"></script> -->
<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

<!-- lodash (used for scroll debounce) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.15/lodash.min.js"></script>

<!-- mapbox -->
<link href="https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.js"></script>

<!-- handlebars -->
<script src="<?= $kirby->url('assets') ?>/lib/handlebars.js/2.0.0/handlebars.js"></script>

<!-- d3 & mapping -->
<script src="<?= $kirby->url('assets') ?>/lib/topojson-3.0.2.js"></script>
<script src="<?= $kirby->url('assets') ?>/lib/d3-v/d3-selection.js"></script>
<script src="<?= $kirby->url('assets') ?>/lib/d3-v/d3-array.js"></script>
<script src="<?= $kirby->url('assets') ?>/lib/d3-v/d3-geo.js"></script>

<!--
geojson2svg - https://github.com/gagan-bansal/geojson2svg
This creates a global variable 'GeoJSON2SVG' as a Class. 
-->
<script src="<?= $kirby->url('assets') ?>/lib/geojson2svg-master/dist/geojson2svg.min.js"></script>
<script src="<?= $kirby->url('assets') ?>/lib/reproject/reproject.min.js"></script>
<script src="<?= $kirby->url('assets') ?>/lib/proj4js/2.2.2/proj4.js"></script>

<!--
turf
will expose a global variable named 'turf'
for later: https://turfjs.org/docs/#simplify
-->
<script src="<?= $kirby->url('assets') ?>/lib/turf-6.5.0.min.js"></script>

