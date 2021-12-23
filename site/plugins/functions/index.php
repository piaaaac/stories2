<?php

/**
 * Die and inspect variable
 */
function kill ($var, $continue = false) {
  $msg = "<pre>". print_r($var, true) ."</pre>";
  if (isset($continue) && $continue === true) {
    echo $msg;
  } else {
    die($msg);
  }
}


