function $parcel$export(e, n, v, s) {
  Object.defineProperty(e, n, {get: v, set: s, enumerable: true, configurable: true});
}

$parcel$export(module.exports, "add", () => $349a00930b14e029$export$e16d8520af44a096);
// ----------------------------------------------------------------
// On execution
// ----------------------------------------------------------------
$(document).ready(function() {
});
const $349a00930b14e029$var$dataFolder = "v0-dev";
const $349a00930b14e029$var$mapUrl = "assets/data/world-110m.v1.json";
const $349a00930b14e029$var$legsDataUrl = "assets/data/" + $349a00930b14e029$var$dataFolder + "/legs.json";
// -----------------------------------------------------------------------------
// Functions
// -----------------------------------------------------------------------------
function $349a00930b14e029$var$initStory(id) {
    Promise.all([
        fetch($349a00930b14e029$var$mapUrl),
        fetch($349a00930b14e029$var$legsDataUrl), 
    ]).then(function(responses) {
        return Promise.all(responses.map(function(response) {
            return response.json();
        }));
    }).then(function(rawDatasets) {
        console.log(rawDatasets);
    }).catch(function(error) {
        console.log(error);
    });
}
function $349a00930b14e029$export$e16d8520af44a096(a, b) {
    return a + b;
} // ----------------------------------------------------------------
 // Events
 // ----------------------------------------------------------------


//# sourceMappingURL=index.js.map
