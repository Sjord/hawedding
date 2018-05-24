<?php
include("../lib/header.php");
require("../lib/db.php");
date_default_timezone_set('Europe/Amsterdam');

if (sha1($_GET['pw']) != "59339d07f67028d4a293886ca797b43cb1e1b636") {
    die("Not authorized");
}

$teams = R::find("team");
foreach ($teams as $team) {
    echo "<h1>".$team->naam."</h1>";
    echo "ronde ".$team->ronde;
    $koppels = R::find("koppel", "team_id=?", [$team->id]);
    echo "<table>";
    foreach ($koppels as $koppel) {
        $class = "unanswered";
        if ($koppel->answered) { $class = "answered wrong"; }
        if ($koppel->correct) { $class = "answered correct"; }
        echo "<tr class='$class'><td>".$koppel->ronde."</td><td>".(1+$koppel->index)."</td><td>".$koppel->answered_by."</td><td>".$koppel->question->text."</td></tr>";
    }
    echo "</table>";
}
?>
<form method="POST"><input type="submit" value="Ververs pagina"></form>
<?php
include("../lib/footer.php");
