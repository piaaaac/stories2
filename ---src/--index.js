
// ----------------------------------------------------------------
// On execution
// ----------------------------------------------------------------

$(document).ready(function () {});

const dataFolder = "v0-dev";
const mapUrl = "assets/data/world-110m.v1.json";
const legsDataUrl = "assets/data/"+ dataFolder +"/legs.json";

// -----------------------------------------------------------------------------
// Functions
// -----------------------------------------------------------------------------

function initStory (id) {
  Promise.all([
    fetch(mapUrl),
    fetch(legsDataUrl),
  ]).then(function (responses) {
    return Promise.all(responses.map(function (response) {
      return response.json();
    }));
  }).then(function (rawDatasets) {
    console.log(rawDatasets);
  }).catch(function (error) {
    console.log(error);
  });
}

export function add(a, b) {
  return a + b;
}

// ----------------------------------------------------------------
// Events
// ----------------------------------------------------------------

