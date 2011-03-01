<?php

$base = dirname(__FILE__) . "/..";

set_include_path(
    $base . "/config:" .
    $base . "/system/libraries/db:" .
    get_include_path()
);

?>
