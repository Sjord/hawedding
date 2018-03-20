<form method="POST">
<input type="text" name="cityname">
<input type="image" src="map.png" name="map">
</form>
<?php
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
