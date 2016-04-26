<?php

header('Content-Type: text/plain');

include("../src/Repository.php");

use \Git\Repository as Repository;

$repo = new Repository("/home/thomas");

var_dump($repo->status());