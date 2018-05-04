<?php

class Target {
    function __construct($data) {
        $this->center = $data["target"];
        $this->polygon = $data["polygon"];
    }

    function get_corners() {
        $corners = [];
        foreach ($this->polygon as $offset) {
            $corners[] = add_points($this->center, $offset);
        }
        return $corners;
    }

    function get_bearings_to_corners($from) {
        $bearings = [];
        foreach ($this->get_corners() as $corner) {
            $bearings[] = get_bearing($from, $corner);
        }
        return $bearings;
    }

    function get_bearings_around_polygon($from) {
        $center_bearing = get_bearing($from, $this->center);
        $bearings = $this->get_bearings_to_corners($from);
        return get_widest_bearings($center_bearing, $bearings);
    }

    function is_close($point) {
        $distance = get_distance($point, $this->center);
        foreach ($this->get_corners() as $corner) {
            if (get_distance($this->center, $corner) >= $distance) {
                return true;
            }
        }
        return false;
    }

    function scale($factor) {
        $scaled = [];
        foreach ($this->polygon as $offset) {
            $scaled[] = ["x" => $offset["x"] * $factor, "y" => $offset["y"] * $factor];
        }
        return new Target(["target" => $this->center, "polygon" => $scaled]);
    }
}

function get_distance($point_a, $point_b) {
    return sqrt(($point_b['x'] - $point_a['x']) ** 2 + ($point_b["y"] - $point_a["y"]) ** 2);
}

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
    return new Target(json_decode(file_get_contents("../../data/target.json"), true));
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

function hint($target) {
    global $cities;

    do {
        $key = array_rand($cities);
        $from = $cities[$key];
    } while ($target->is_close($from));

    list($left, $right) = $target->get_bearings_around_polygon($from);

    return [$key, $from, ceil(fmod($left, 360)), floor(fmod($right, 360))];
}

$target = get_target();
if (isset($_POST["factor"])) {
    $target = $target->scale($_POST["factor"]);
}

print_r($point);
list($city, $from, $low, $high) = hint($target);
echo "Tussen $high en $low graden vanaf $city";
?>
<form method="POST">
<input type="submit" name="factor" value="1.0">
<input type="submit" name="factor" value="0.5">
<input type="submit" name="factor" value="0.1">
<!-- <input type="text" name="cityname"> -->
<div style="position: relative">
    <input type="image" src="map.png" name="map">
    <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
      viewBox="0 0 1920 1090" preserveAspectRatio="xMidYMid slice"
      style="width:1920px; height:1090px; overflow: visible; position:absolute; top:0; left:0; z-index: 1; pointer-events: none;">
      <?php 
        echo svg_path($from, $low); 
        echo svg_path($from, $high); 

        foreach ($target->get_corners() as $corner) {
            echo svg_path($target->center, $corner);
        }
      ?>
    </svg>
</div>
</form>