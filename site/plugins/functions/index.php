<?php

// ---------------------------------------------
// Site methods
// ---------------------------------------------
// 
Kirby::plugin('biatch/site-methods', [
  'siteMethods' => [
    'pagePanelUrl' => function ($id, $includeDrafts = false) {
      $p = page($id);
      if (!$p && $includeDrafts) {
        $parentId = dirname($id);
        $pp = page($parentId);
        if ($pp) {
          $p = $pp->drafts()->find($id);
        }
      }
      if (!$p) {
        return 'Page not found.';
      }
      return $p->panel()->url();
    }
  ]
]);

/**
 * Die and inspect variable
 */
function kill($var, $continue = false)
{
  $msg = "<pre>" . print_r($var, true) . "</pre>";
  if (isset($continue) && $continue === true) {
    echo $msg;
  } else {
    die($msg);
  }
}

function getFromPlace($story)
{
  $legs = $story->legs()->toStructure();
  if ($legs->count() > 0) {
    return $story->departurePlace()->value();
  } else {
    return "Unknown";
  }
}
function getFromCountry($story)
{
  return countryCode2Name($story->departureCountry()->value());
}
function getToPlace($story)
{
  $legs = $story->legs()->toStructure();
  if ($legs->count() > 0) {
    return $legs->last()->place();
  } else {
    return "Unknown";
  }
}
function getToCountry($story)
{
  $legs = $story->legs()->toStructure();
  if ($legs->count() > 0) {
    return countryCode2Name($legs->last()->country()->value());
  } else {
    return "Unknown";
  }
}

function countryCode2Name($countryCode)
{
  $country = site()->countries()->toStructure()->findBy("code", $countryCode);
  if ($country) {
    return $country->name();
  } else {
    return $countryCode;
  }
}
