<?php
require("../lib/db.php");

$teams = R::find("team");
$questions = R::find("question");
$rondes = [1, 2, 3, 4, 5];

$offset = 1;
foreach ($teams as $team) {
    foreach ($rondes as $ronde) {
        for ($i = 0; $i < 5; $i++) {
            $koppel = R::dispense("koppel");
            $qi = $offset + $i + ($ronde - 1) * 5;
            $koppel->question = $questions[$qi];
            $koppel->ronde = $ronde;
            $koppel->team = $team;
            $koppel->correct = null;
            $koppel->answered = false;
            $koppel->answered_by = null;
            $koppel->index = $i;
            echo "$team->naam $ronde $qi\n";
            R::store($koppel);
        }
    }
    $offset += 2;
}
