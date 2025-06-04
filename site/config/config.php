<?php

return [

  
  // --- Kirby

  "debug" => true,
  "whoops" => true,
  "languages" => true,
  "panel.install" => false,

  "routes" => require_once "routes.php",

  // "hooks" => require_once "hooks.php", // currently []
  
  "thumbs" => [
    "presets" => [
      "default" => ["width" => 1024, "quality" => 80],
      // "speaker" => ["crop" => 300, "width" => 300, "height" => 300, "greyscale" => true] /*"blur" => true*/
    ]
  ],

  // Kirby SEO plugin settings
  "tobimori.seo.canonicalBase" => "https://movinglines.org",
  "tobimori.seo.lang" => "en_US",
  
];