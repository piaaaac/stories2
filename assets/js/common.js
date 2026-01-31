// ----------------------------------------------------------------
// On execution
// ----------------------------------------------------------------

$(document).ready(function () {});

// const dataFolder = "v0-dev";
// const mapUrl = window.siteUrl + "/assets/data/world-110m.v1.json";
// const legsDataUrl =
//   window.siteUrl + "/assets/data/" + dataFolder + "/legs.json";
// const cacheFile = window.siteUrl + "/manual-cache.json";

// ----------------------------------------------------------------
// Events
// ----------------------------------------------------------------

// Handle resize
// -------------

handleResizeStartEnd(
  function () {
    console.log("started");
    handleResizeStart();
  },
  () => {
    console.log("ended");
    handleResizeEnd();
  },
);

function handleResizeStart() {
  console.log("resize started");
  // $(s.svg.node()).remove();
}

function handleResizeEnd() {
  console.log("resize ended");
  // s.init();
  // s.drawLine();
}

// -----------------------------------------------------------------------------
// Functions
// -----------------------------------------------------------------------------
