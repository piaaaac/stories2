module.exports = {
  options: {
    viewportSize: {width: 200, height: 100},
    r:2,
    mapExtent: {left: -180, bottom: -90, right: 180, top: 90}
  },
  precision: 0.00000001,
  mercatorExtent: {
    left: -20037508.342789244,
    right: 20037508.342789244,
    bottom: -20037508.342789244,
    top: 20037508.342789244
  },
  geojsons: [
    { type: 'Point',
      geojson: {type:'Point',coordinates:[50,50]},
      path: ["M127.77777777777777,22.22222222222222 m-2,0 a2,2 0 1,1 4,0 a2,2 0 1,1 -4,0"],
      svg: ['<svg d="M127.77777777777777,22.22222222222222 m-2,0 a2,2 0 1,1 4,0 a2,2 0 1,1 -4,0"/>'],
      path_explode: ["M127.77777777777777,22.22222222222222 m-2,0 a2,2 0 1,1 4,0 a2,2 0 1,1 -4,0"],
      svg_explode: ['<svg d="M127.77777777777777,22.22222222222222 m-2,0 a2,2 0 1,1 4,0 a2,2 0 1,1 -4,0"/>']
    },
    { type: 'LineString',
      geojson: {type:'LineString',coordinates:[[10,10],[15,20],[30,10]]},
      path: ['M105.55555555555556,44.44444444444444 108.33333333333333,38.888888888888886 116.66666666666666,44.44444444444444'],
      svg: ['<path d="M105.55555555555556,44.44444444444444 108.33333333333333,38.888888888888886 116.66666666666666,44.44444444444444" />'],
      path_explode: ['M105.55555555555556,44.44444444444444 108.33333333333333,38.888888888888886 116.66666666666666,44.44444444444444'],
      svg_explode: ['<path d="M105.55555555555556,44.44444444444444 108.33333333333333,38.888888888888886 116.66666666666666,44.44444444444444" />']
    },
    { type: 'Polygon',
      geojson:{
        "type": "Polygon", 
        "coordinates": [
          [[30, 10], [40, 40], [20, 40], [10, 20], [30, 10]] 
        ]
      },
      path:['M116.66666666666666,44.44444444444444 122.22222222222221,27.77777777777778 111.11111111111111,27.77777777777778 105.55555555555556,38.888888888888886 116.66666666666666,44.44444444444444Z'],
      svg: ['<path fill-rule="evenodd" d="M116.66666666666666,44.44444444444444 122.22222222222221,27.77777777777778 111.11111111111111,27.77777777777778 105.55555555555556,38.888888888888886 116.66666666666666,44.44444444444444Z " />'],
      path_explode:['M116.66666666666666,44.44444444444444 122.22222222222221,27.77777777777778 111.11111111111111,27.77777777777778 105.55555555555556,38.888888888888886 116.66666666666666,44.44444444444444Z'],
      svg_explode: ['<path fill-rule="evenodd" d="M116.66666666666666,44.44444444444444 122.22222222222221,27.77777777777778 111.11111111111111,27.77777777777778 105.55555555555556,38.888888888888886 116.66666666666666,44.44444444444444Z " />']
    },
    { type: 'GeometryCollection',
      geojson: {
        type: 'GeometryCollection',
        geometries: [
          {type:'LineString',coordinates:[[10,10],[15,20],[30,10]]},
          {"type": "Polygon", "coordinates": [[[30, 10], [40, 40], [20, 40], [10, 20], [30, 10]]]}
        ]
      },
      path: ['M105.55555555555556,44.44444444444444 108.33333333333333,38.888888888888886 116.66666666666666,44.44444444444444', 'M116.66666666666666,44.44444444444444 122.22222222222221,27.77777777777778 111.11111111111111,27.77777777777778 105.55555555555556,38.888888888888886 116.66666666666666,44.44444444444444Z'],
      svg: ['<path fill-rule="evenodd" d="M105.55555555555556,44.44444444444444 108.33333333333333,38.888888888888886 116.66666666666666,44.44444444444444"/>', '<path fill-rule="evenodd" d="M116.66666666666666,44.44444444444444 122.22222222222221,27.77777777777778 111.11111111111111,27.77777777777778 105.55555555555556,38.888888888888886 116.66666666666666,44.44444444444444Z" />'],
      path_explode: ['M105.55555555555556,44.44444444444444 108.33333333333333,38.888888888888886 116.66666666666666,44.44444444444444', 'M116.66666666666666,44.44444444444444 122.22222222222221,27.77777777777778 111.11111111111111,27.77777777777778 105.55555555555556,38.888888888888886 116.66666666666666,44.44444444444444Z'],
      svg_explode: ['<path fill-rule="evenodd" d="M105.55555555555556,44.44444444444444 108.33333333333333,38.888888888888886 116.66666666666666,44.44444444444444"/>', '<path fill-rule="evenodd" d="M116.66666666666666,44.44444444444444 122.22222222222221,27.77777777777778 111.11111111111111,27.77777777777778 105.55555555555556,38.888888888888886 116.66666666666666,44.44444444444444Z" />'],
    },
    { type: 'Polygon with hole',
      geojson:{
        "type": "Polygon", 
        "coordinates": [
          [[35, 10], [45, 45], [15, 40], [10, 20], [35, 10]], 
          [[20, 30], [35, 35], [30, 20], [20, 30]]
        ]
      },
      path: ['M119.44444444444444,44.44444444444444 125,25 108.33333333333333,27.77777777777778 105.55555555555556,38.888888888888886 119.44444444444444,44.44444444444444 M111.11111111111111,33.333333333333336 119.44444444444444,30.555555555555554 116.66666666666666,38.888888888888886 111.11111111111111,33.333333333333336Z'],
      svg: ['<path fill-rule="evenodd" d="M119.44444444444444,44.44444444444444 125,25 108.33333333333333,27.77777777777778 105.55555555555556,38.888888888888886 119.44444444444444,44.44444444444444 M111.11111111111111,33.333333333333336 119.44444444444444,30.555555555555554 116.66666666666666,38.888888888888886 111.11111111111111,33.333333333333336Z" />'],
      path_explode: ['M119.44444444444444,44.44444444444444 125,25 108.33333333333333,27.77777777777778 105.55555555555556,38.888888888888886 119.44444444444444,44.44444444444444 M111.11111111111111,33.333333333333336 119.44444444444444,30.555555555555554 116.66666666666666,38.888888888888886 111.11111111111111,33.333333333333336Z'],
      svg_explode: ['<path fill-rule="evenodd" d="M119.44444444444444,44.44444444444444 125,25 108.33333333333333,27.77777777777778 105.55555555555556,38.888888888888886 119.44444444444444,44.44444444444444 M111.11111111111111,33.333333333333336 119.44444444444444,30.555555555555554 116.66666666666666,38.888888888888886 111.11111111111111,33.333333333333336Z" />']
    },
    {
      type: 'MultiPoint',
      geojson: {
        "type": "MultiPoint", 
        "coordinates": [
          [10, 40], [40, 30], [20, 20], [30, 10]
	        ]
      },
      path: [' M105.55555555555556,27.77777777777778 m-2,0 a2,2 0 1,1 4,0 a2,2 0 1,1 -4,0 M122.22222222222221,33.333333333333336 m-2,0 a2,2 0 1,1 4,0 a2,2 0 1,1 -4,0 M111.11111111111111,38.888888888888886 m-2,0 a2,2 0 1,1 4,0 a2,2 0 1,1 -4,0 M116.66666666666666,44.44444444444444 m-2,0 a2,2 0 1,1 4,0 a2,2 0 1,1 -4,0 '],
      svg: ['<path d="M105.55555555555556,27.77777777777778 m-2,0 a2,2 0 1,1 4,0 a2,2 0 1,1 -4,0 M122.22222222222221,33.333333333333336 m-2,0 a2,2 0 1,1 4,0 a2,2 0 1,1 -4,0 M111.11111111111111,38.888888888888886 m-2,0 a2,2 0 1,1 4,0 a2,2 0 1,1 -4,0 M116.66666666666666,44.44444444444444 m-2,0 a2,2 0 1,1 4,0 a2,2 0 1,1 -4,0" />'],
      path_explode: ['M105.55555555555556,27.77777777777778 m-2,0 a2,2 0 1,1 4,0 a2,2 0 1,1 -4,0', 'M122.22222222222221,33.333333333333336 m-2,0 a2,2 0 1,1 4,0 a2,2 0 1,1 -4,0', 'M111.11111111111111,38.888888888888886 m-2,0 a2,2 0 1,1 4,0 a2,2 0 1,1 -4,0', 'M116.66666666666666,44.44444444444444 m-2,0 a2,2 0 1,1 4,0 a2,2 0 1,1 -4,0'],
      svg_explode: ['<path d="105.55555555555556,27.77777777777778 m-2,0 a2,2 0 1,1 4,0 a2,2 0 1,1 -4,0"/>', '<path d="122.22222222222221,33.333333333333336 m-2,0 a2,2 0 1,1 4,0 a2,2 0 1,1 -4,0"/>', '<path d="111.11111111111111,38.888888888888886 m-2,0 a2,2 0 1,1 4,0 a2,2 0 1,1 -4,0"/>', '<path d="116.66666666666666,44.44444444444444 m-2,0 a2,2 0 1,1 4,0 a2,2 0 1,1 -4,0"/>']
    },
    {
      type: 'MultiLineString',
      geojson: {
			  "type": "MultiLineString", 
			  "coordinates": [
			    [[10, 10], [20, 20], [10, 40]], 
			    [[40, 40], [30, 30], [40, 20], [30, 10]]
			  ]
			},
      path: ['M105.55555555555556,44.44444444444444 111.11111111111111,38.888888888888886 105.55555555555556,27.77777777777778 M122.22222222222221,27.77777777777778 116.66666666666666,33.333333333333336 122.22222222222221,38.888888888888886 116.66666666666666,44.44444444444444'],
      svg:['<path d="M105.55555555555556,44.44444444444444 111.11111111111111,38.888888888888886 105.55555555555556,27.77777777777778 M122.22222222222221,27.77777777777778 116.66666666666666,33.333333333333336 122.22222222222221,38.888888888888886 116.66666666666666,44.44444444444444" />'],
      path_explode: ['M105.55555555555556,44.44444444444444 111.11111111111111,38.888888888888886 105.55555555555556,27.77777777777778', 'M122.22222222222221,27.77777777777778 116.66666666666666,33.333333333333336 122.22222222222221,38.888888888888886 116.66666666666666,44.44444444444444'],
      svg_explode: ['<path d="M105.55555555555556,44.44444444444444 111.11111111111111,38.888888888888886 105.55555555555556,27.77777777777778" />', '<path d="M122.22222222222221,27.77777777777778 116.66666666666666,33.333333333333336 122.22222222222221,38.888888888888886 116.66666666666666,44.44444444444444" />']
    },
    {
      type: 'MultiPolygon',
      geojson:	{
			  "type": "MultiPolygon", 
			  "coordinates": [
			    [
			      [[30, 20], [45, 40], [10, 40], [30, 20]]
			    ], 
			    [
			      [[15, 5], [40, 10], [10, 20], [5, 10], [15, 5]]
			    ]
			  ]
			},
      path: ['M116.66666666666666,38.888888888888886 125,27.77777777777778 105.55555555555556,27.77777777777778 116.66666666666666,38.888888888888886 M108.33333333333333,47.22222222222222 122.22222222222221,44.44444444444444 105.55555555555556,38.888888888888886 102.77777777777777,44.44444444444444 108.33333333333333,47.22222222222222Z'],
      svg:['<path fill-rule="evenodd" d="M116.66666666666666,38.888888888888886 125,27.77777777777778 105.55555555555556,27.77777777777778 116.66666666666666,38.888888888888886 M108.33333333333333,47.22222222222222 122.22222222222221,44.44444444444444 105.55555555555556,38.888888888888886 102.77777777777777,44.44444444444444 108.33333333333333,47.22222222222222Z" />'],
      path_explode: ['M116.66666666666666,38.888888888888886 125,27.77777777777778 105.55555555555556,27.77777777777778 116.66666666666666,38.888888888888886', 'M108.33333333333333,47.22222222222222 122.22222222222221,44.44444444444444 105.55555555555556,38.888888888888886 102.77777777777777,44.44444444444444 108.33333333333333,47.22222222222222Z'],
      svg_explode: ['<path fill-rule="evenodd" d="M116.66666666666666,38.888888888888886 125,27.77777777777778 105.55555555555556,27.77777777777778 116.66666666666666,38.888888888888886" />','<path d="M108.33333333333333,47.22222222222222 122.22222222222221,44.44444444444444 105.55555555555556,38.888888888888886 102.77777777777777,44.44444444444444 108.33333333333333,47.22222222222222Z" />']
    },
    {
      type: 'MultiPolygon with holes',
      geojson:	{
			  "type": "MultiPolygon", 
			  "coordinates": [
			    [
			      [[40, 40], [20, 45], [45, 30], [40, 40]]
			    ], 
			    [
			      [[20, 35], [10, 30], [10, 10], [30, 5], [45, 20], [20, 35]], 
			      [[30, 20], [20, 15], [20, 25], [30, 20]]
			    ]
			  ]
			},
      path: ['M122.22222222222221,27.77777777777778 111.11111111111111,25 125,33.333333333333336 122.22222222222221,27.77777777777778 M111.11111111111111,30.555555555555554 105.55555555555556,33.333333333333336 105.55555555555556,44.44444444444444 116.66666666666666,47.22222222222222 125,38.888888888888886 111.11111111111111,30.555555555555554 M116.66666666666666,38.888888888888886 111.11111111111111,41.666666666666664 111.11111111111111,36.11111111111111 116.66666666666666,38.888888888888886Z'],
      svg:['<path fill-rule="evenodd" d="M122.22222222222221,27.77777777777778 111.11111111111111,25 125,33.333333333333336 122.22222222222221,27.77777777777778 M111.11111111111111,30.555555555555554 105.55555555555556,33.333333333333336 105.55555555555556,44.44444444444444 116.66666666666666,47.22222222222222 125,38.888888888888886 111.11111111111111,30.555555555555554 M116.66666666666666,38.888888888888886 111.11111111111111,41.666666666666664 111.11111111111111,36.11111111111111 116.66666666666666,38.888888888888886Z" />'],
    },
  ],
  feature: {
    type: 'Feature',
    geojson: {
      type: "Feature",
      geometry: {
			  "type": "MultiLineString", 
	  		  "coordinates": [
		  	    [[10, 10], [20, 20], [10, 40]], 
			      [[40, 40], [30, 30], [40, 20], [30, 10]]
		  	  ]
		  	},
      id: "feature1",
      properties: {
        prop1: 'val1',
        prop2: 23.45
      }
    },
    path: ['M105.55555555555556,44.44444444444444 111.11111111111111,38.888888888888886 105.55555555555556,27.77777777777778 M122.22222222222221,27.77777777777778 116.66666666666666,33.333333333333336 122.22222222222221,38.888888888888886 116.66666666666666,44.44444444444444'],
    svg:['<path d="M105.55555555555556,44.44444444444444 111.11111111111111,38.888888888888886 105.55555555555556,27.77777777777778 M122.22222222222221,27.77777777777778 116.66666666666666,33.333333333333336 122.22222222222221,38.888888888888886 116.66666666666666,44.44444444444444" />']
  },
  featureCollection: {
    type: 'FeatureCollection',
		geojson: {
		  "type": "FeatureCollection",
		  "features": [
		    {
		      "type": "Feature",
		      "geometry": {
		        "type": "LineString",
		        "coordinates": [
		          [102.0, 0.0], [103.0, 1.0], [104.0, 0.0], [105.0, 1.0]
		        ]
		      },
		      "properties": {
		        "prop1": 0.0,
		        "prop0": "value0"
		      }
		    },
		    {
		      "type": "Feature",
		      "geometry": {
		        "type": "Polygon",
		        "coordinates": [
		          [
		            [100.0, 0.0], [101.0, 0.0], [101.0, 1.0], [100.0, 1.0],
		            [100.0, 0.0]
		          ]
		        ]
		      },
		      "properties": {
		        "prop1": {
		          "this": "that"
		        },
		        "prop0": "value0"
		      }
		    }
		  ]
		},
    path: ['M156.66666666666666,50 157.22222222222223,49.44444444444444 157.77777777777777,50 158.33333333333334,49.44444444444444', 'M155.55555555555554,50 156.11111111111111,50 156.11111111111111,49.44444444444444 155.55555555555554,49.44444444444444 155.55555555555554,50Z'],
    svg: ['<path d="M156.66666666666666,50 157.22222222222223,49.44444444444444 157.77777777777777,50 158.33333333333334,49.44444444444444" />','<path d="M155.55555555555554,50 156.11111111111111,50 156.11111111111111,49.44444444444444 155.55555555555554,49.44444444444444 155.55555555555554,50Z" />']
  },
  "Polygon fit to width": {
    type: 'Polygon fit to width',
    svgSize: {width: 300, height: 100},
    geojson:{
      "type": "Polygon", 
      "coordinates": [
        [[30, 10], [40, 40], [20, 40], [10, 20], [30, 10]] 
      ]
    },
    path:['M175,66.66666666666667 183.33333333333334,41.66666666666667 166.66666666666669,41.66666666666667 158.33333333333334,58.333333333333336 175,66.66666666666667Z'],
    svg: ['<path fill-rule="evenodd" d="M175,66.66666666666667 183.33333333333334,41.66666666666667 166.66666666666669,41.66666666666667 158.33333333333334,58.333333333333336 175,66.66666666666667Z " />'],
    path_explode:['M175,66.66666666666667 183.33333333333334,41.66666666666667 166.66666666666669,41.66666666666667 158.33333333333334,58.333333333333336 175,66.66666666666667Z'],
    svg_explode: ['<path fill-rule="evenodd" d="M175,66.66666666666667 183.33333333333334,41.66666666666667 166.66666666666669,41.66666666666667 158.33333333333334,58.333333333333336 175,66.66666666666667Z " />']
  },
  "Default values": {
    type: "Default values",
    geojson: {
      type: 'Polygon',
      coordinates: [
        [[1600652.2828257163, 757749.2273689711], [2021361.6864487636, 1523623.8113293173], [2970403.829505404, 1443225.2764322374], [3048675.346458529, 846502.6396460842], [2373583.512737826, 570838.6644076401], [1600652.2828257163, 757749.2273689711]]
      ]
    },
    path: ['M138.2249984726979,123.15948293350895 140.91249847232382,118.26706092824489 146.9749984714799,118.78064836091235 147.4749984714103,122.59252437872642 143.1624984720106,124.3534713101955 138.2249984726979,123.15948293350895Z'], 
    svg: ['<path fill-rule="evenodd" d="M138.2249984726979,123.15948293350895 140.91249847232382,118.26706092824489 146.9749984714799,118.78064836091235 147.4749984714103,122.59252437872642 143.1624984720106,124.3534713101955 138.2249984726979,123.15948293350895Z" />'],
    path_explode: ['M138.2249984726979,123.15948293350895 140.91249847232382,118.26706092824489 146.9749984714799,118.78064836091235 147.4749984714103,122.59252437872642 143.1624984720106,124.3534713101955 138.2249984726979,123.15948293350895Z'], 
    svg_explode: ['M138.2249984726979,123.15948293350895 140.91249847232382,118.26706092824489 146.9749984714799,118.78064836091235 147.4749984714103,122.59252437872642 143.1624984720106,124.3534713101955 138.2249984726979,123.15948293350895Z'] 
  },
  "Extent from geojson": {
    type: "Extent from geojson",
    geojson: {
      type: 'Feature',
      geometry: {
        type: 'LineString',
        coordinates: [
          [10, 40], [40, 30], [20, 20], [30, 10]
        ]
      }
    },
    svg: ['<path d="M0,0 256,85.33333333333333 85.33333333333333,170.66666666666666 170.66666666666666,256"/>']
  },
  "coordinateConverter option": {
    type: "coordinateConverter option",
    geojson: {
      type:'LineString',
      coordinates:[[-71, 41], [51, 51]]
    },
    svg: [ '<path d="M0,94.77680169959633 800,0"/>' ]
  }
}
