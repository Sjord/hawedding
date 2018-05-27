<?php

require("../lib/db.php");

R::wipe("treinhint");
$data = file_get_contents("../data/treinhints.txt");
$lines = explode("\n", $data);
$index = 0;
foreach ($lines as $line) {
    $hint = R::dispense("treinhint");
    $hint->text = $line;
    $hint->index = $index++;
    R::store($hint);
}
