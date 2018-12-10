<?php
$path = "";
if (isset($_SERVER['PATH_INFO'])) $path = trim($_SERVER['PATH_INFO'], '/');
if (!file_exists($path . ".php")) {
    header('Location: /audit/');
    exit();
}
include($path . ".php");
