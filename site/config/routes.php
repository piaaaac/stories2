<?php

return [

  // ---------------------------------------------------------------------------

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
          'status' => 'success',
          'message' => 'File saved',
          'url'     => $file->url()
        ];
      } catch (Exception $e) {

        // If file exists, overwrite manually
        $target = $page->root() . '/cover.png';
        move_uploaded_file($upload['tmp_name'], $target);

        return [
          'status' => 'success',
          'message' => 'File overwritten',
          'url'     => $page->file('cover.png')->url()
        ];
      }
    }
  ],

  // ---------------------------------------------------------------------------

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
            'status'  => 'success',
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

  // ---------------------------------------------------------------------------

  [
    'pattern' => 'update-leg-geojson',
    'method'  => 'POST',
    'language' => false,
    'action'  => function () {

      $storyPage = page('stories/' . get("storyUid"));
      if (!$storyPage) {
        return [
          'status' => 'error',
          'message' => 'Story page not found'
        ];
      }
      $legIndex  = intval(get("legIndex"));
      $newUse = get('use') === 'on' ? true : false;
      $newGeojson = get('geojson');
      // Decode → encode removes all whitespace safely
      $minifiedGeojson = json_encode(json_decode($newGeojson, true));

      $structure = $storyPage->legs()->toStructure();

      // For debugging
      // $currentGeojson = $structure->nth($legIndex)->geojsonLeg()->value();
      // $currentUse = $structure->nth($legIndex)->geojsonUse()->bool();
      // return [
      //   "debug" => [
      //     "page" => $storyPage->title(),
      //     "legIndex" => $legIndex,
      //     "current" => $currentGeojson,
      //     "new" => $newGeojson,
      //     "currentuse" => $currentUse,
      //     "newuse" => $newUse,
      //     "minified" => $minified,
      //   ]
      // ];

      $newItems = [];
      $i = 0;
      foreach ($structure as $item) {
        if ($i === $legIndex) {
          $newItems[] = $item->toArray() + ['geojsonLeg' => $minifiedGeojson, 'geojsonUse' => $newUse];
        } else {
          $newItems[] = $item->toArray();
        }
        $i++;
      }

      $updatedPage = $storyPage->update([
        'legs' => Yaml::encode($newItems)
      ]);

      $structureItem = $updatedPage->legs()->toStructure()->nth($legIndex);



      return [
        'status' => 'success',
        'message' => "Leg $legIndex updated successfully",
        'geojsonLegSaved' => $structureItem->geojsonLeg()->value(),
        'legIndex' => $legIndex,
        'textareaId' => get('textareaId'),
      ];

      // $go = site()->url() . "/story-tools?story=" . $storyPage->slug();
      // go($go);
    }
  ],

  // ---------------------------------------------------------------------------

];
