<?php
require("../lib/db.php");

R::wipe("hint");
$data = file_get_contents("../data/hints.txt");
$lines = explode("\n", $data);
foreach ($lines as $line) {
    if (preg_match("~Ronde (\d) \((.*)\)~", $line, $matches)) {
        $ronde = $matches[1];
        $correct = $matches[2] == "goed";
        $i = 0;
        continue;
    }
    else if (preg_match("~^([A-Z].*?)( \((.*)\))?$~", $line, $matches)) {
        $text = $matches[1];
        $quality = $matches[3];
        $hint = R::dispense("hint");
        $hint->ronde = $ronde;
        $hint->correct = $correct;
        $hint->text = $text;
        $hint->quality = $quality;
        $hint->index = $i;
        $i++;
        R::store($hint);
    } else if (!empty($line)) {
        echo "Failed to parse: $line\n";
    }
}
