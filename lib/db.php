<?php
require("rb.php");
R::setup();

function create_hash($text) {
    return substr(sha1($text), 0, 12);
}

class Model_Question extends RedBean_SimpleModel {
    function add_answer($text, $correct) {
        $a = R::dispense('answer');
        $a->text = $text;
        $a->correct = $correct;
        $a->hash = create_hash($this->text.$text);
        $this->bean->xownAnswerList[] = $a;
    }

    function get_answer_by_hash($hash) {
        foreach ($this->bean->xownAnswerList as $a) {
            if ($a->hash == $hash) {
                return $a;
            }
        }
    }

    function output() {
        $answers = $this->bean->xownAnswerList;
        shuffle($answers); // TODO misschien deterministisch maken?
        echo '<form method="POST">';
        echo '<input type="hidden" name="question_hash" value="'.$this->hash.'">';
        echo $this->text;

        $type="radio";
        if ($this->multi) {
            $type="checkbox";
        }

        foreach ($answers as $answer) {
            echo '<div class="answer"><input type="'.$type.'" name="answer_hash[]" value="'.$answer->hash.'" id="'.$answer->hash.'"><label for="'.$answer->hash.'">'.$answer->text.'</label></div>';
        }
        echo '<input type="submit">';
        echo '</form>';
    }

    function output_answer() {
        $answers = $this->bean->xownAnswerList;
        echo '<div class="question answered">';
        echo $this->text;
        echo '</div>';
    }
}

