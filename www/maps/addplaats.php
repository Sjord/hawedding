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

function get_target() {
    return json_decode(file_get_contents("../../data/target.json"), true);
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

function add_points($a, $b) {
    return ["x" => $a["x"] + $b["x"], "y" => $a["y"] + $b["y"]];
}

function angle_sub($a, $b) {
    return fmod($b - $a + 180 + 360, 360) - 180;
}

function get_closest_bearings($target, $bearings) {
    $left = -360;
    $right = 360;
    foreach ($bearings as $bearing) {
        $diff = angle_sub($target, $bearing);
        if ($diff > $left && $diff < 0) {
            $left = $diff;
        }
        if ($diff < $right && $diff > 0) {
            $right = $diff;
        }
    }
    return [$target + $left, $target + $right];
}

function get_widest_bearings($target, $bearings) {
    return get_closest_bearings($target + 180, $bearings);
}

function hint() {
    global $cities;
    $key = array_rand($cities);
    $from = $cities[$key];

    $target = get_target();
    $center = $target["target"];
    $target_bearing = get_bearing($from, $center);

    $bearings = [];
    foreach ($target['polygon'] as $offset) {
        $corner = add_points($center, $offset);
        $bearings[] = get_bearing($from, $corner);
    }
    list($left, $right) = get_widest_bearings($target_bearing, $bearings);

    return [$key, $from, ceil(fmod($left, 360)), floor(fmod($right, 360))];
}

print_r($point);
list($city, $from, $low, $high) = hint();
echo "Tussen $high en $low graden vanaf $city";
?>
<form method="POST">
<input type="text" name="cityname">
<div style="position: relative">
    <input type="image" src="map.png" name="map">
    <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
      viewBox="0 0 1920 1090" preserveAspectRatio="xMidYMid slice"
      style="width:1920px; height:1090px; overflow: visible; position:absolute; top:0; left:0; z-index: 1; pointer-events: none;">
      <?php 
        echo svg_path($from, $low); 
        echo svg_path($from, $high); 

        $target = get_target();
        $center = $target["target"];

        $prev_corner = null;
        foreach ($target['polygon'] as $offset) {
            $corner = add_points($center, $offset);
            if ($prev_corner) {
                echo svg_path($prev_corner, $corner);
            }
            $prev_corner = $corner;
        }
      ?>
    </svg>
</div>
</form>
