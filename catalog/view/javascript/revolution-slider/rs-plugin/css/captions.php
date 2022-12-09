<?php
header("Content-Type: text/css; charset=utf-8");

include_once('../../../../../config.php');
include_once('../../../../../index.php');


require_once '/../../revslideropencart_loader.php';
require_once '/../../inc_php/framework/include_framework.php';



// $styles = sdsconfig::getcaptioncss(GlobalsRevSlider::TABLE_CSS_NAME);
$styles = sdsconfig::getgeneratecss();
echo $styles;
// echo UniteCssParserRev::parseDbArrayToCss($styles, "\n");

?>