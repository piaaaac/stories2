<?php

return [



  [
    'pattern' => 'story-upload/(:any)',
    'method'  => 'POST',
    'action'  => function ($slug) {

      // Find the page
      $page = page('stories/' . $slug);
      if (!$page) {
        return [
          'status' => 'error',
          'message' => 'Page not found'
        ];
      }

      // Ensure a file was uploaded
      if (!isset($_FILES['file'])) {
        return [
          'status' => 'error',
          'message' => 'No file uploaded'
        ];
      }

      $upload = $_FILES['file'];

      // Save as cover.png (overwrite if exists)
      try {
        $file = $page->createFile([
          'source'   => $upload['tmp_name'],
          'filename' => 'cover.png',
          'template' => 'image'
        ]);

        return [
          'status' => 'ok',
          'message' => 'File saved',
          'url'     => $file->url()
        ];
      } catch (Exception $e) {

        // If file exists, overwrite manually
        $target = $page->root() . '/cover.png';
        move_uploaded_file($upload['tmp_name'], $target);

        return [
          'status' => 'ok',
          'message' => 'File overwritten',
          'url'     => $page->file('cover.png')->url()
        ];
      }
    }
  ],


  [
    'pattern' => 'save-story-svg',
    'method'  => 'POST',
    'language' => false,
    'action'  => function () {
      // Basic auth check - adapt to your needs
      if ($user = kirby()->user()) {
        // get JSON body
        $data = json_decode(file_get_contents('php://input'), true);

        $pageId  = $data['id']  ?? null;
        $svgCode = $data['svg'] ?? null;

        if (!$pageId || !$svgCode) {
          return [
            'status'  => 'error',
            'message' => 'Missing page id or svg.'
          ];
        }

        if (!$page = page($pageId)) {
          return [
            'status'  => 'error',
            'message' => 'Page not found.'
          ];
        }

        try {
          $page->update([
            'cachedSvg' => $svgCode
          ]);

          return [
            'status'  => 'ok',
            'message' => 'SVG saved.'
          ];
        } catch (Exception $e) {
          return [
            'status'  => 'error',
            'message' => $e->getMessage()
          ];
        }
      }

      return [
        'status'  => 'error',
        'message' => 'Not authenticated.'
      ];
    }
  ],





  // [
  //   "pattern" => "testone",
  //   "action"  => function ($data) {
  //     return "<pre>test one</pre>";
  //   }
  // ],



  // [
  //   "pattern" => "add_to_json_cache",
  //   "action"  => function ($data) {
  //     // ..
  //     // ..
  //     // ..
  //     return "<pre>" . print_r($data) . "</pre>";
  //     // via https://stackoverflow.com/a/7895384/2501713

  //     $data[] = $_POST["data"];

  //     $inp = file_get_contents("results.json");
  //     $tempArray = json_decode($inp);
  //     array_push($tempArray, $data);
  //     $jsonData = json_encode($tempArray);
  //     file_put_contents("results.json", $jsonData);
  //     // ..
  //     // ..
  //     // ..
  //   },
  //   "method" => "POST",

  // ],



  // [
  //   "pattern" => "story_cache_read/(:any)",
  //   "action"  => function ($slug) {
  //     $p = page("stories/$slug");
  //     if (!$p) {
  //       return "<pre>Page $slug not found.</pre>";
  //     }
  //     return "<pre>" . print_r($p->cachedApiCalls()->value()) . "</pre>";
  //   }
  // ],
  // [
  //   "pattern" => "story_cache_update/(:any)",
  //   "action"  => function ($slug) {
  //     $p = page("stories/$slug");
  //     if (!$p) {
  //       return "<pre>Page $slug not found.</pre>";
  //     }


  //     try {

  //       $newPage = $p->update([
  //         "cachedApiCalls" => '{"cacheKey":"requestUrl=https%3A%2F%2Fapi.openrouteservice.org%2Fv2%2Fdirections%2Fdriving-car%2Fgeojson&coordinates=5.56667%2C50.63333%2C3.96667%2C50.28333&geometry_simplify=true&instructions=false","response":{"type":"FeatureCollection","features":[{"bbox":[3.904836,50.282437,5.567235,50.659092],"type":"Feature","properties":{"summary":{"distance":151596.3,"duration":5857.5},"way_points":[0,843]},"geometry":{"coordinates":[[5.567225,50.633338],[5.567235,50.632925],[5.56717,50.632893],[5.564678,50.63266],[5.56504,50.631617],[5.56485,50.631576],[5.563941,50.631217],[5.563634,50.630937],[5.562994,50.6304]],"type":"LineString"}}],"bbox":[3.904836,50.282437,5.567235,50.659092],"metadata":{"attribution":"openrouteservice.org | OpenStreetMap contributors","service":"routing","timestamp":1648680919918,"query":{"coordinates":[[5.56667,50.63333],[3.96667,50.28333]],"profile":"driving-car","format":"geojson","geometry_simplify":true},"engine":{"version":"6.7.0","build_date":"2022-02-18T19:37:41Z","graph_date":"2022-03-13T11:00:24Z"}}}}',
  //       ]);

  //       $msg = "The page has been updated";
  //       return "<pre>$msg</pre>";
  //     } catch (Exception $e) {

  //       $msg = "Error: " . $e->getMessage();
  //       return "<pre>$msg</pre>";
  //     }
  //   },
  //   // "method" => "POST",
  // ],
];
