<?php

return [


  // --- Kirby

  "debug" => true,
  "whoops" => true,
  "languages" => true,
  "panel.install" => false,
  "routes" => require_once "routes.php",
  // 'api' => [
  //   'slug' => 'restapi',
  //   // 'basicAuth' => true,
  // ],
  // "hooks" => require_once "hooks.php", // currently []
  "assets" => [
    "version" => "0.0.12",
  ],
  "thumbs" => [
    "presets" => [
      "default" => ["width" => 1024, "quality" => 80],
      // "speaker" => ["crop" => 300, "width" => 300, "height" => 300, "greyscale" => true] /*"blur" => true*/
    ]
  ],

  // --- custom options
  'mapbox.token'        => "pk.eyJ1IjoicGlhYWFhYyIsImEiOiIxaHI5SmNnIn0.68S9KEJ3TeuhobReU_uDeQ",
  'mapbox.style.withBg' => "mapbox://styles/piaaaac/clo2p2b6o00is01pf9ovp59k8",
  'mapbox.style.empty'  => "mapbox://styles/piaaaac/clr0wgqob019e01o37ytp8q40",


  // Kirby SEO plugin settings
  "tobimori.seo.canonicalBase" => "https://movinglines.org",
  "tobimori.seo.lang" => "en_US",

];
