<?php

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
function getToPlace($story)
{
  $legs = $story->legs()->toStructure();
  if ($legs->count() > 0) {
    return $legs->last()->place();
  } else {
    return "Unknown";
  }
}
