<?php

# require our components class
require_once('components.php');

# require all our components
$components_directory = dirname(__FILE__) . DIRECTORY_SEPARATOR . "components";
foreach (scandir($components_directory) as $filename) {
  $path = $components_directory . DIRECTORY_SEPARATOR . $filename;
  if (is_file($path)) {
    require $path;
  }
}

# require our api
require_once('api.php');
