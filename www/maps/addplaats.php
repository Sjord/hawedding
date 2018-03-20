<?php
function svg_path($from, $to) {
    if (!is_array($to)) {
        $len = 1000;
        $to = ["x" => $from["x"] + $len * sin(deg2rad($to)), "y" => $from["y"] + $len * -cos(deg2rad($to))];
    }
    return sprintf('<path style="fill:none;fill-rule:evenodd;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" d="M %d,%d %d,%d" />', $from['x'], $from['y'], $to['x'], $to['y']);
}

function get_cities() {
    return json_decode(file_get_contents("../../data/cities.json"), true);
}

function get_bearing($from, $to) {
    $rad = atan2($to['x'] - $from['x'], $from['y'] - $to['y']);
    return fmod(360.0 + (180 * $rad / pi()), 360.0);
}

$cities = get_cities();
$alkmaar = $cities['Alkmaar'];
$norwich = $cities['Norwich'];
if (!empty($_POST)) {
    $point = ["x" => $_POST["map_x"], "y" => $_POST["map_y"]];
    echo get_bearing($norwich, $point);
}

if (!empty($_POST['cityname'])) {
    $cities = get_cities();
    $cityname = $_POST['cityname'];
    $cities[$cityname] = ["x" => $_POST["map_x"], "y" => $_POST["map_y"]];
    file_put_contents("../../data/cities.json", json_encode($cities));
}
?>
<form method="POST">
<input type="text" name="cityname">
<div style="position: relative">
    <input type="image" src="map.png" name="map">
    <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
      viewBox="0 0 1920 1090" preserveAspectRatio="xMidYMid slice"
      style="width:1920px; height:1090px; overflow: visible; position:absolute; top:0; left:0; z-index: 1; pointer-events: none;">
      <?php 
          $bearing = get_bearing($norwich, $point);
          echo svg_path($cities['Norwich'], $bearing); 
      ?>
    </svg>
</div>
</form>
