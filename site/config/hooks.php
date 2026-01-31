<?php

return [

  // ---------------------------------------------------------------------------

  'page.update:after' => function ($newPage, $oldPage) {
    $newPage->save([
      'updated' => date('Y-m-d H:i:s')
    ]);
  }
];
