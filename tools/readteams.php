<?php
require("../lib/db.php");

R::wipe("team");
$team_namen = [ "De Schoonfamilie", "De Rugbyvrienden", "De Bruidsmeisjes", "De Partycrashers"];
foreach ($team_namen as $naam) {
    $team = R::dispense("team");
    $team->naam = $naam;
    $team->ronde = 0;
    R::store($team);
}
