<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
umask(0000);
require 'Doctrine/Common/ClassLoader.php';
$loader = new Doctrine\Common\ClassLoader("JsonRpcFormatter", __DIR__ . "/../src/");
$loader->register();



