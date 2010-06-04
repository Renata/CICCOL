<?php
/**
 * Diagram
 * Class to draw diagrams from a xml file/data
 *
 * @author Diogo Resende <me@diogoresende.net>
 * @version 2.0
 * @
 *
 **/
class Diagram {
var $prepared = false;

var $padding = array(
'top'=> 2,
'left'   => 2,
'right'  => 2,
'bottom' => 2
);
var $margin = array(
'top'=> 10,
'left'   => 10,
'right'  => 10,
'bottom' => 10
);
var $border = array(
'top'=> 1,
'left'   => 1,
'right'  => 1,
'bottom' => 1,
'middle' => 1,
'connection' => 1
);
var $color = array(
'background' => '#f',
'border' => '#0',
'connection' => '#0',
'name'   => array(
'background' => '#f4f4f4',
'text'   => '#0',
'connection' => '#0'
),
'data'   => array(
'background' => '#f0f0f0',
'text'   => '#0'
)
);
var $font = array(
'name'   => 3,
'data'   => 2,
'connection' => 2
);
var $align = array(
'name' => 'left',
'data' => 'left'
);
var $width = 100;

/**
 * If this is true, the node will have the width necessary
 * to show the name in only one line. It will still have the
 * normal width if the name is small
 **/
var $fit_name = true;

/**
 * If zero, no arrow is drawed, if greather than zero, an
 * arrow will be drawed with that size
 **/
var $arrow_thickness = 3;

/**
 * Diagram::Diagram()
 *
 * Class constructor
 **/
function Diagram($xml_file = '') {
if (strlen($xml_file) > 0) {
$this->loadXmlFile($xml_file);
}
}

/**
 * Diagram::loadXmlFile()
 *
 * Load data from XML file
 **/
function loadXmlFile($file) {
if (($fp = fopen($file, 'r')) !== false) {
$data = '';
while (!feof($fp)) {
$data .= fread($fp, 4096);
}
fclose($fp);

$this->loadXmlData($data);
}
}

/**
 * Diagram::loadXmlData()
 *
 * Load data from a XML string
 **/
function loadXmlData($data) {
if (!class_exists('XmlParser')) {
/*...............**/
require dirname(__FILE__) . '/class.xmlparser.php';
}
$xml = new XmlParser($data);

$this->struct = $xml->struct;

$xml = null;

$this->prepared = false;
}

/**
 * Diagram::loadFromArray()
 *
 * Load data from an associative array
 **/
function loadFromArray($array) {
$this->struct = array(array('childs' => array()));
$this->__addArrayToNode($this->struct[0]['childs'], $array);
}

/**
 * Diagram::setDefaultBorder()
 *
 * Set border sizes. You can omit borders. The ones available are:
 * - left,top,right,bottom,middle,connection
 *
 * Example: setDefaultBorder(array('left' => 2, 'right' => 2));
 **/
function setDefaultBorder($borders) {
foreach ($borders as $k => $v) {
if (isset($this->border[$k])) {
$this->border[$k] = (int) $v;
}
}
}

/**
 * Diagram::setDefaultFont()
 *
 * Set fonts. You can omit fonts. The ones available are:
 * - name,data,connection
 *
 * Example: setDefaultFont(array('connection' => 1, 'name' => 2, 'data' => 3));
 **/
function setDefaultFont($fonts) {
foreach ($fonts as $k => $v) {
if (isset($this->font[$k])) {
$this->font[$k] = $v;
}
}
}

/**
 * Diagram::setDefaultAlign()
 *
 * Set alignments. You can omit alignments. The ones available are:
 * - name,data
 * The possible alignments are:
 * - left,center,right
 *
 * Example: setDefaultAlign(array('name' => 'center'));
 **/
function setDefaultAlign($aligns) {
foreach ($aligns as $k => $v) {
if (isset($this->align[$k])) {
$this->align[$k] = $v;
}
}
}

/**
 * Diagram::setDefaultColor()
 *
 * Set colors. You can omit colors. The ones available are:
 * - background,border,connection
 *
 * Example: setDefaultColor(array('background' => '#ffffff', 'border' => '#000'));
 **/
function setDefaultColor($colors) {
foreach ($colors as $k => $v) {
if (isset($this->color[$k])) {
$this->color[$k] = $v;
}
}
}

/**
 * Diagram::setDefaultDataColor()
 *
 * Set data colors. You can omit colors. The ones available are:
 * - background,text
 *
 * Example: setDefaultDataColor(array('text' => '#000'));
 **/
function setDefaultDataColor($colors) {
foreach ($colors as $k => $v) {
if (isset($this->color['data'][$k])) {
$this->color['data'][$k] = $v;
}
}
}

/**
 * Diagram::setDefaultNameColor()
 *
 * Set name colors. You can omit colors. The ones available are:
 * - background,text,connection
 *
 * Example: setDefaultNameColor(array('text' => '#000'));
 **/
function setDefaultNameColor($colors) {
foreach ($colors as $k => $v) {
if (isset($this->color['name'][$k])) {
$this->color['name'][$k] = $v;
}
}
}

/**
 * Diagram::Draw()
 *
 * Draw all the diagram and output it to stdout
 * or a file
 **/
function Draw($output_file = '') {
if (!$this->prepared) {
$this->__prepare($this->struct[0], true);
$this->prepared = true;
}

$w = $this->struct[0]['attrib']['TOTALWIDTH'];
$h = $this->struct[0]['attrib']['CHILDHEIGHT'];

$im = imagecreatetruecolor($w, $h);

$this->__drawBackground($im, $this->struct[0]['attrib'], $w, $h);
$this->__drawNode($im, $this->struct[0]['childs'][0], 0, 0, $this->struct[0]['childs'][0]['attrib']['NODEHEIGHT']);

/**
 * Output
 **/
if (strlen($output_file) > 0 && is_dir(dirname($output_file))) {
imagepng($im, $output_file);
} else {
header('Content-Type: image/png');
imagepng($im);
}
}

/**
 * Diagram::__addArrayToNode()
 *
 * Load an array to a node with default values
 **/
function __addArrayToNode(&$node_childs, &$array) {
foreach ($array as $k => $v) {
if (is_array($v)) {
$node_childs[] = array(
'tag'=> 'NODE',
'attrib' => array(),
'data'   => $k,
'childs' => array()
);
$this->__addArrayToNode($node_childs[count($node_childs) - 1]['childs'], $v);
} else {
$node_childs[] = array(
'tag'=> 'NODE',
'attrib' => array(),
'data'   => $v,
'childs' => array()
);
}
}
}

/**
 * Diagram::__drawBackground()
 *
 * Draw diagram background
 **/
function __drawBackground(&$im, &$diagram_atr, $w, $h) {
if (isset($diagram_atr['BGCOLOR2'])) {
$this->__drawDegrade($im, 0, 0, $w, $h, $diagram_atr['BGCOLOR'], $diagram_atr['BGCOLOR2']);
} else {
$c = $this->__allocateColor($im, $diagram_atr['BGCOLOR']);

imagefilledrectangle($im, 0, 0, $w, $h, $c);
}
}

/**
 * Diagram::__drawNode()
 *
 * Draw a node
 **/
function __drawNode(&$im, &$node, $left = 0, $top = 0, $total_height = 0, $parent_tail_left = null, $parent_tail_top = null) {
$x = round(($node['attrib']['TOTALWIDTH'] - $node['attrib']['NODEWIDTH']) / 2);

$x += $left + $node['attrib']['MARGINLEFT'];
$y = $top + $node['attrib']['MARGINTOP'];

$this->__drawNodeBackground($im, $node['attrib'], $x, $y, (strlen($node['data']) > 0));
$this->__drawNodeBorder($im, $node['attrib'], $x, $y, (strlen($node['data']) > 0));

if ($parent_tail_left != null) {
$this->__drawNodeParentConnection($im,
  $node,
  $x + round($node['attrib']['WIDTH'] / 2),
  $y,
  $parent_tail_left,
  $parent_tail_top,
  $node['attrib']['CONNECTIONCOLOR'],
  $node['attrib']['BORDERCONNECTION'],
  $node['attrib']['ARROW']);
}
$this->__drawNodeData($im, $node, $x, $y);

$node_tail_left = ($left + round($node['attrib']['TOTALWIDTH'] / 2));
$node_tail_top = ($top + $node['attrib']['NODEHEIGHT'] - $node['attrib']['MARGINBOTTOM']);

$left_offset = $left;
$top_offset = $top + $total_height;
for ($i = 0; $i < count($node['childs']); $i++) {
$this->__drawNode($im, $node['childs'][$i], $left_offset, $top_offset, $node['attrib']['TOTALHEIGHT'], $node_tail_left, $node_tail_top);

$left_offset += $node['childs'][$i]['attrib']['TOTALWIDTH'];
}
}

function __drawNodeParentConnection(&$im, &$node, $node_tail_left, $node_tail_top, $parent_tail_left, $parent_tail_top, $color, $thickness, $arrow_thickness) {
$offset = round($thickness / 2);
$middle = round(($node_tail_top - $parent_tail_top) / 2);

$c = $this->__allocateColor($im, $color);

for ($i = 0; $i < $thickness; $i++) {
imageline($im, $parent_tail_left - $offset + $i, $parent_tail_top, $parent_tail_left - $offset + $i, $parent_tail_top + $middle, $c);
}

for ($i = 0; $i < $thickness; $i++) {
imageline($im, $parent_tail_left - $offset, $parent_tail_top + $middle - $i, $node_tail_left - $offset, $parent_tail_top + $middle - $i, $c);
}
$j = $i;

for ($i = 0; $i < $thickness; $i++) {
imageline($im, $node_tail_left - $offset + $i, $parent_tail_top + $middle - $j + 1, $node_tail_left - $offset + $i, $node_tail_top - $arrow_thickness, $c);
}

/**
 * Arrow
 **/
imagesetpixel($im, $node_tail_left - 1, $node_tail_top - 1, $c);
$offset += $arrow_thickness - 1;
for ($i = 0; $i < $offset; $i++) {
imageline($im, $node_tail_left - 2 - $i, $node_tail_top - 2 - $i, $node_tail_left + $i, $node_tail_top - 2 - $i, $c);
}

/**
 * Connection text
 **/
if (isset($node['attrib']['CONNECTIONNAME']) && strlen($node['attrib']['CONNECTIONNAME']) > 0) {
if ($parent_tail_left > $node_tail_left) {
$tmp = $parent_tail_left;
$parent_tail_left = $node_tail_left;
$node_tail_left = $tmp;
}
$this->__drawText($im, $parent_tail_left, $parent_tail_top + $middle - $thickness - imagefontheight($node['attrib']['CONNECTIONFONT']), ($node_tail_left - $parent_tail_left), $node['attrib']['CONNECTIONFONT'], $node['attrib']['CONNECTIONNAMECOLOR'], 'center', $node['attrib']['CONNECTIONNAME']);
}
}

/**
 * Diagram::__drawNodeData()
 *
 * Draw the text on the node
 **/
function __drawNodeData(&$im, &$node, $left, $top) {
$left += $node['attrib']['BORDERLEFT'] + $node['attrib']['PADDINGLEFT'];
$top += $node['attrib']['BORDERTOP'] + $node['attrib']['PADDINGTOP'];
if (isset($node['attrib']['NAME'])) {
$top = $this->__drawText($im, $left, $top, $node['attrib']['WIDTH'], $node['attrib']['NAMEFONT'], $node['attrib']['NAMECOLOR'], $node['attrib']['NAMEALIGN'], $node['attrib']['NAME']);

$top += $node['attrib']['PADDINGBOTTOM'] + $node['attrib']['BORDERMIDDLE'] + $node['attrib']['PADDINGTOP'];
}
if (strlen($node['data']) > 0) {
$this->__drawText($im, $left, $top, $node['attrib']['WIDTH'], $node['attrib']['FONT'], $node['attrib']['COLOR'], $node['attrib']['ALIGN'], $node['data']);
}
}

/**
 * Diagram::__drawText()
 *
 * Draw text on a certain position with a maximum with, font
 * and color
 * Returns the new vertical position
 **/
function __drawText(&$im, $x, $y, $max_width, $font, $color, $align, $text) {
$text = explode("\n", wordwrap($text, floor($max_width / imagefontwidth($font)), "\n"));
$c = $this->__allocateColor($im, $color);
for ($i = 0; $i < count($text); $i++) {
switch ($align) {
case 'center':
imagestring($im, $font, $x + round(($max_width - (imagefontwidth($font) * strlen($text[$i]))) / 2), $y, $text[$i], $c);
break;
case 'right':
imagestring($im, $font, $x + $max_width - (imagefontwidth($font) * strlen($text[$i])), $y, $text[$i], $c);
break;
default:
imagestring($im, $font, $x, $y, $text[$i], $c);
}
$y += imagefontheight($font);
}
return $y;
}

/**
 * Diagram::__drawNodeBorder()
 *
 * Draw the node border
 **/
function __drawNodeBorder(&$im, &$node_atr, $left, $top, $has_data) {
$width = $node_atr['BORDERLEFT'] + $node_atr['PADDINGLEFT'] + $node_atr['WIDTH'] + $node_atr['PADDINGRIGHT'];
$height = $node_atr['BORDERTOP'] + $node_atr['PADDINGTOP'] + $node_atr['HEIGHT'] + $node_atr['PADDINGBOTTOM'];

$c = $this->__allocateColor($im, $node_atr['BORDERCOLOR']);

for ($i = 0; $i < $node_atr['BORDERLEFT']; $i++) {
imageline($im, $left + $i, $top, $left + $i, $top + $height - 1, $c);
}

for ($i = 0; $i < $node_atr['BORDERTOP']; $i++) {
imageline($im, $left, $top + $i, $left + $width - 1, $top + $i, $c);
}

for ($i = 0; $i < $node_atr['BORDERRIGHT']; $i++) {
imageline($im, $left + $width - $i, $top, $left + $width - $i, $top + $height, $c);
}

for ($i = 0; $i < $node_atr['BORDERBOTTOM']; $i++) {
imageline($im, $left, $top + $height + $i, $left + $width, $top + $height + $i, $c);
}

if ($has_data && isset($node_atr['NAMEHEIGHT'])) {
$height = $node_atr['BORDERTOP'] + $node_atr['PADDINGTOP'] + $node_atr['NAMEHEIGHT'] + $node_atr['PADDINGBOTTOM'];
for ($i = 0; $i < $node_atr['BORDERMIDDLE']; $i++) {
imageline($im, $left, $top + $height + $i, $left + $width, $top + $height + $i, $c);
}
}
}

/**
 * Diagram::__drawNodeBackground()
 *
 * Draw the node background(s)
 **/
function __drawNodeBackground(&$im, &$node_atr, $left, $top, $has_data) {
$width = $node_atr['BORDERLEFT'] + $node_atr['PADDINGLEFT'] + $node_atr['WIDTH'] + $node_atr['PADDINGRIGHT'];
$height = $node_atr['BORDERTOP'] + $node_atr['PADDINGTOP'] + $node_atr['HEIGHT'] + $node_atr['PADDINGBOTTOM'];

if ($has_data) {
if (isset($node_atr['BGCOLOR2'])) {
$this->__drawDegrade($im, $left, $top, $left + $width, $top + $height, $node_atr['BGCOLOR'], $node_atr['BGCOLOR2']);
} else {
$c = $this->__allocateColor($im, $node_atr['BGCOLOR']);
imagefilledrectangle($im, $left, $top, $left + $width, $top + $height, $c);
}
}
if (isset($node_atr['NAMEHEIGHT'])) {
$height = $node_atr['BORDERTOP'] + $node_atr['PADDINGTOP'] + $node_atr['NAMEHEIGHT'] + $node_atr['PADDINGBOTTOM'];

if (isset($node_atr['NAMEBGCOLOR2'])) {
$this->__drawDegrade($im, $left, $top, $left + $width, $top + $height, $node_atr['NAMEBGCOLOR'], $node_atr['NAMEBGCOLOR2']);
} else {
$c = $this->__allocateColor($im, $node_atr['NAMEBGCOLOR']);
imagefilledrectangle($im, $left, $top, $left + $width, $top + $height, $c);
}
}
}

/**
 * Diagram::__prepare()
 *
 * Prepare the diagram structure and add the individual
 * settings to every node
 **/
function __prepare(&$node, $is_diagram = false) {
/**
 * Padding
 **/
if ($this->__checkParam($node['attrib'], 'PADDING')) {
/**
 * this ensures that if there is a global atribute for
 * padding, it is spreaded thru all paddings
 **/
$this->__setParam($node['attrib'],
  array(
'PADDINGLEFT',
'PADDINGTOP',
'PADDINGRIGHT',
'PADDINGBOTTOM'
  ),
  $node['attrib']['PADDING']);
}
$this->__checkParam($node['attrib'], 'PADDINGLEFT', $this->padding['left']);
$this->__checkParam($node['attrib'], 'PADDINGTOP', $this->padding['top']);
$this->__checkParam($node['attrib'], 'PADDINGRIGHT', $this->padding['right']);
$this->__checkParam($node['attrib'], 'PADDINGBOTTOM', $this->padding['bottom']);

/**
 * Borders
 **/
if ($this->__checkParam($node['attrib'], 'BORDER')) {
/**
 * this ensures that if there is a global atribute for
 * border, it is spreaded thru all borders
 **/
$this->__setParam($node['attrib'],
  array(
'BORDERLEFT',
'BORDERTOP',
'BORDERRIGHT',
'BORDERBOTTOM',
'BORDERMIDDLE',
'BORDERCONNECTION'
  ),
  $node['attrib']['BORDER']);
}
$this->__checkParam($node['attrib'], 'BORDERLEFT', $this->border['left']);
$this->__checkParam($node['attrib'], 'BORDERTOP', $this->border['top']);
$this->__checkParam($node['attrib'], 'BORDERRIGHT', $this->border['right']);
$this->__checkParam($node['attrib'], 'BORDERBOTTOM', $this->border['bottom']);
$this->__checkParam($node['attrib'], 'BORDERMIDDLE', $this->border['middle']);
$this->__checkParam($node['attrib'], 'BORDERCONNECTION', $this->border['connection']);

/**
 * Margins
 **/
if ($this->__checkParam($node['attrib'], 'MARGIN')) {
/**
 * this ensures that if there is a global atribute for
 * margin, it is spreaded thru all margins
 **/
$this->__setParam($node['attrib'],
  array(
'MARGINLEFT',
'MARGINTOP',
'MARGINRIGHT',
'MARGINBOTTOM'
  ),
  $node['attrib']['MARGIN']);
}
$this->__checkParam($node['attrib'], 'MARGINLEFT', $this->margin['left']);
$this->__checkParam($node['attrib'], 'MARGINTOP', $this->margin['top']);
$this->__checkParam($node['attrib'], 'MARGINRIGHT', $this->margin['right']);
$this->__checkParam($node['attrib'], 'MARGINBOTTOM', $this->margin['bottom']);
if (!$is_diagram) {
/**
 * Alignments
 **/
$this->__checkParam($node['attrib'], 'NAMEALIGN', $this->align['name']);
$this->__checkParam($node['attrib'], 'ALIGN', $this->align['data']);

/**
 * Colors
 **/
$this->__checkColorParam($node['attrib'], 'NAMECOLOR', $this->color['name']['text']);
$this->__checkColorParam($node['attrib'], 'NAMEBGCOLOR', $this->color['name']['background']);
$this->__checkColorParam($node['attrib'], 'COLOR', $this->color['data']['text']);
$this->__checkColorParam($node['attrib'], 'BGCOLOR', $this->color['data']['background']);
$this->__checkColorParam($node['attrib'], 'BORDERCOLOR', $this->color['border']);
$this->__checkColorParam($node['attrib'], 'CONNECTIONCOLOR', $this->color['connection']);
$this->__checkColorParam($node['attrib'], 'CONNECTIONNAMECOLOR', $this->color['name']['connection']);

/**
 * Colors (Degrade support)
 **/
if (isset($node['attrib']['BGCOLOR2'])) {
$this->__checkColorParam($node['attrib'], 'BGCOLOR2', '');
}
if (isset($node['attrib']['NAMEBGCOLOR2'])) {
$this->__checkColorParam($node['attrib'], 'NAMEBGCOLOR2', '');
}

/**
 * Fonts
 **/
$this->__checkParam($node['attrib'], 'NAMEFONT', $this->font['name']);
$this->__checkParam($node['attrib'], 'CONNECTIONFONT', $this->font['connection']);
$this->__checkParam($node['attrib'], 'FONT', $this->font['data']);

/**
 * Misc
 **/
$this->__checkParam($node['attrib'], 'ARROW', $this->arrow_thickness);
$this->__checkBooleanParam($node['attrib'], 'FITNAME', $this->fit_name);
$this->__checkParam($node['attrib'], 'CONNECTIONNAME', '');
} else {
/**
 * Colors
 **/
$this->__checkColorParam($node['attrib'], 'BGCOLOR', $this->color['background']);

/**
 * Colors (Degrade support)
 **/
if (isset($node['attrib']['BGCOLOR2'])) {
$this->__checkColorParam($node['attrib'], 'BGCOLOR2', '');
}

/**
 * Global node dimensions
 **/
if (isset($node['attrib']['WIDTH']) && (int) $node['attrib']['WIDTH'] > 0) {
$this->width = (int) $node['attrib']['WIDTH'];
}

/**
 * Global alignments
 **/
if (isset($node['attrib']['ALIGN'])) {
$this->align['data'] = $node['attrib']['ALIGN'];
}
if (isset($node['attrib']['NAMEALIGN'])) {
$this->align['name'] = $node['attrib']['NAMEALIGN'];
}
}
/**
 * Node dimensions
 **/
$this->__checkParam($node['attrib'], 'WIDTH', $this->width);
$this->__calculateNodeWidth($node['attrib']);
$this->__calculateNodeHeight($node['attrib'], $node['data']);

$node_width = 0;
$node_height = 0;
$child_height = 0;

for ($i = 0; $i < count($node['childs']); $i++) {
$this->__prepare($node['childs'][$i]);

$node_width += $node['childs'][$i]['attrib']['TOTALWIDTH'];
if ($node['childs'][$i]['attrib']['CHILDHEIGHT'] > $child_height) {
$child_height = $node['childs'][$i]['attrib']['CHILDHEIGHT'];
}
if ($node['childs'][$i]['attrib']['NODEHEIGHT'] > $node_height) {
$node_height = $node['childs'][$i]['attrib']['NODEHEIGHT'];
}
}

$node['attrib']['TOTALHEIGHT'] = $node_height;
$node['attrib']['CHILDHEIGHT'] = $node['attrib']['TOTALHEIGHT'] + $child_height;

if ($node_width > $node['attrib']['NODEWIDTH']) {
$node['attrib']['TOTALWIDTH'] = $node_width;
} else {
$node['attrib']['TOTALWIDTH'] = $node['attrib']['NODEWIDTH'];
}
}

/**
 * Diagram::__drawDegrade()
 *
 * Draw a degrade from a color to another inside a rectangle
 **/
function __drawDegrade(&$im, $x1, $y1, $x2, $y2, $color_start, $color_end) {
$steps = ($y2 - $y1);
$inc_r = ($color_end[0] - $color_start[0]) / $steps;
$inc_g = ($color_end[1] - $color_start[1]) / $steps;
$inc_b = ($color_end[2] - $color_start[2]) / $steps;

$c = $color_start;
while ($y2 > $y1) {
$c[0] += $inc_r;
$c[1] += $inc_g;
$c[2] += $inc_b;
//print_r($c);
$cl = $this->__allocateColor($im, $c);
imageline($im, $x1, $y1, $x2, $y1++, $cl);
}
}

/**
 * Diagram::__totalLines()
 *
 * Returns the number of lines that a certain amount of
 * text needs with a certain font and a certain maximum
 * width
 **/
function __totalLines($text, $font, $max_width) {
$text = wordwrap($text, floor($max_width / imagefontwidth($font)), "\n");

return substr_count($text, "\n") + 1;
}

/**
 * Diagram::__calculateNodeWidth()
 *
 * Based on Diagram::fit_title and node atributes, this
 * function returns the width of the node (not including
 * paddings, margins or borders)
 **/
function __calculateNodeWidth(&$node_atr) {
if (isset($node_atr['NAME']) && strlen($node_atr['NAME']) > 0 && $node_atr['FITNAME']) {
/**
 * Node wants it's title to fit in one line
 **/
$w = strlen($node_atr['NAME']) * imagefontwidth($node_atr['NAMEFONT']);
if ($node_atr['WIDTH'] < $w) {
$node_atr['WIDTH'] = $w;
}
} else {
$this->__checkParam($node_atr, 'WIDTH', $this->width);
}
$node_atr['NODEWIDTH'] = $node_atr['MARGINLEFT'] + $node_atr['BORDERLEFT'] + $node_atr['PADDINGLEFT'] +
 $node_atr['WIDTH'] +
 $node_atr['PADDINGRIGHT'] + $node_atr['BORDERRIGHT'] + $node_atr['MARGINRIGHT'];
}

/**
 * Diagram::__calculateNodeHeight()
 *
 * Based on node width, it returns the height (including
 * middle border and 1 padding top and 1 padding bottom
 *   ____|____
 *  |_________| <--- from here (top of title)
 *  | |
 *  | |
 *  |_________| <--- to here (bottom of data)
 **/
function __calculateNodeHeight(&$node_atr, &$node_data) {
$height = 0;
/**
 * Name
 **/
if (isset($node_atr['NAME'])) {
/**
 * At least it will fill one line
 **/
$height = imagefontheight($node_atr['NAMEFONT']);
if (!$node_atr['FITNAME']) {
/**
 * Multiply the size of one line by the number of lines
 **/
$height *= $this->__totalLines($node_atr['NAME'], $node_atr['NAMEFONT'], $node_atr['WIDTH']);
}
$this->__setParam($node_atr, 'NAMEHEIGHT', $height);
}

/**
 * Data
 **/
if (strlen($node_data) > 0) {
if (isset($node_atr['NAME'])) {
$height += $node_atr['PADDINGBOTTOM'] + $node_atr['BORDERMIDDLE'] + $node_atr['PADDINGTOP'];
}
$height += (imagefontheight($node_atr['FONT']) * $this->__totalLines($node_data, $node_atr['FONT'], $node_atr['WIDTH']));
}

$node_atr['HEIGHT'] = $height;
$node_atr['NODEHEIGHT'] = $node_atr['MARGINTOP'] + $node_atr['BORDERTOP'] + $node_atr['PADDINGTOP'] +
  $node_atr['HEIGHT'] +
  $node_atr['PADDINGBOTTOM'] + $node_atr['BORDERBOTTOM'] + $node_atr['MARGINBOTTOM'];
}

/**
 * Diagram::__checkParam()
 *
 * Check for existance of a parameter in a node and atribute
 * the default one if not found.
 * If default_value is null it returns only the existance of
 * the parameter
 **/
function __checkParam(&$atributes, $param, $default_value = null) {
if ($default_value == null) {
return isset($atributes[$param]);
} elseif (!isset($atributes[$param])) {
$atributes[$param] = $default_value;
return false;
}
return true;
}

/**
 * Diagram::__checkColorParam()
 *
 * Checks the parameter with Diagram::__checkParam() and
 * then converts it to a color array
 **/
function __checkColorParam(&$atributes, $param, $default_value) {
if (!isset($atributes[$param])) {
$atributes[$param] = $default_value;
}
$atributes[$param] = $this->__decodeRgbColor($atributes[$param]);
}

/**
 * Diagram::__checkBooleanParam()
 *
 * Checks the parameter with Diagram::__checkParam() and
 * then converts it to a boolean
 **/
function __checkBooleanParam(&$atributes, $param, $default_value) {
if (!isset($atributes[$param])) {
$atributes[$param] = (bool) $default_value;
} else {
$atributes[$param] = (bool) $atributes[$param];
}
}

/**
 * Diagram::__setParam()
 *
 * Set a value to a parameter or an array of parameters
 **/
function __setParam(&$node_attributes, $atribute_name, $value) {
if (is_array($atribute_name)) {
for ($i = 0; $i < count($atribute_name); $i++) {
$node_attributes[$atribute_name[$i]] = $value;
}
} else {
$node_attributes[$atribute_name] = $value;
}
}

/**
 * Diagram::__decodeRgbColor()
 *
 * Given an RGB string (#abcdef, #abc, #a) returns an array
 * with the Red, Green and Blue componements
 **/
function __decodeRgbColor($color) {
if (preg_match('/^\#[0-9a-f]+$/', $color)) {
$color = substr($color, 1);
switch (strlen($color)) {
case 6:
return array(hexdec(substr($color, 0, 2)),
 hexdec(substr($color, 2, 2)),
 hexdec(substr($color, 4)));
case 3:
return array(hexdec($color[0] . $color[0]),
 hexdec($color[1] . $color[1]),
 hexdec($color[2] . $color[2]));
case 1:
return array(hexdec($color[0] . $color[0]),
 hexdec($color[0] . $color[0]),
 hexdec($color[0] . $color[0]));
default:
return array(0, 0, 0); // black
}
} else {
return array(0, 0, 0); // black
}
}

/**
 * Diagram::__allocateColor()
 *
 * Allocate a color on the diagram image or return a pointer
 * if the color is already allocated
 **/
function __allocateColor(&$im, $color) {
$c = imagecolorexact($im, $color[0], $color[1], $color[2]);
if ($c >= 0) {
return $c;
}
return imagecolorallocate($im, $color[0], $color[1], $color[2]);
}
}
?>
