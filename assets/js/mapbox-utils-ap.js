// --------------------------------
// Mapbox line styles
// --------------------------------

// LABELS VIA FRAGA
// On foot · Air transport · Sea transport · Land transport

var lineStyles = {
  walk: { dashArray: [0, 3] }, // OK
  ground: { dashArray: [1] },
  sea: { dashArray: [6, 5] }, // OK
  air: { dashArray: [1] },
  other: { dashArray: [1] },
};

function kirbyTransportToDashArray(kirbyTransport) {
  var lineStyle;
  switch (kirbyTransport) {
    case "plane":
      lineStyle = "air";
      break;
    case "boat":
      lineStyle = "sea";
      break;
    case "bus":
    case "car":
    case "truck":
    case "train":
    case "taxi":
      lineStyle = "ground";
      break;
    case "walk":
      lineStyle = "walk";
      break;
    default:
      lineStyle = "other";
      break;
  }

  var dashArray = lineStyles[lineStyle].dashArray;
  // console.log("kirbyTransport:", kirbyTransport)
  // console.log("lineStyle:", lineStyle)
  // console.log("dashArray:", dashArray)

  return dashArray;
}

function randomLineStyle() {
  var styleNames = Object.keys(lineStyles);
  var styleName = styleNames[Math.floor(Math.random() * styleNames.length)];
  var lineStyle = lineStyles[styleName];
  var dashArray = lineStyle.dashArray;
  return dashArray;
}

// --------------------------------
// Mapbox general utilities
// --------------------------------

function getEmptySource() {
  return {
    type: "geojson",
    data: {
      type: "Feature",
      properties: {},
      geometry: {
        type: "LineString",
        coordinates: [[76.993894, 31.781929]],
      },
    },
  };
}

function getBounds(coordinates) {
  console.log("getBounds coordinates", coordinates);

  // Create a 'LngLatBounds' with both corners at the first coordinate.
  const bounds = new mapboxgl.LngLatBounds(coordinates[0], coordinates[0]);

  // Extend the 'LngLatBounds' to include every coordinate in the bounds result.
  for (const coord of coordinates) {
    bounds.extend(coord);
  }

  return bounds;
}
