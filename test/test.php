<?php 

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use redzjovi\Tiki\Tiki;

$tiki = new Tiki();
$track = $tiki->track('030071590590');
var_dump($track);
