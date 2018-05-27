<?php
require("../lib/db.php");

$questions = file_get_contents("../data/questions.txt");
foreach (explode("\n", $questions) as $line) {
    if (empty($line)) continue;
    list($question, $answers) = explode("? ", $line);
    $question .= "?";
    $answers = explode(", ", $answers);

    $q = R::dispense("question");
    $q->text = $question;
    $q->hash = create_hash($question);
    $correct = true;
    foreach ($answers as $answer) {
        if ($answer[0] == "_") {
            $answer = substr($answer, 1);
            $correct = true;
            $q->multi = true;
        }
        $q->add_answer($answer, $correct);
        $correct = false;
    }
    R::store($q);
    echo ".";
}
echo "\n";
