<?php

// https://getkirby.com/docs/reference/templates/helpers#deactivate-a-helper-globally
define('KIRBY_HELPER_DUMP', false);

require 'kirby/bootstrap.php';

echo (new Kirby)->render();
