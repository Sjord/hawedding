<?php
$echtenaam = $_COOKIE['echtenaam'];
$bruiloftnaam = $_COOKIE['bruiloftnaam'];
$team = $_COOKIE['team'];
?>
<header>
Ingelogged als <?= htmlentities("$bruiloftnaam ($echtenaam) van $team") ?>
</header>

<form method="POST">
<?php
class Answer {
    function __construct($text, $correct) {
        $this->text = $text;
        $this->correct = $correct;
        $this->id = substr(sha1($text), 0, 8);
    }
}

class Question {
    function add_answer($text, $correct) {
        $this->id = substr(sha1($text), 0, 8);
        $this->answers[] = new Answer($text, $correct);
    }

    function get_answer_by_id($id) {
        foreach ($this->answers as $a) {
            if ($a->id == $id) {
                return $a;
            }
        }
    }

    function output() {
        shuffle($this->answers);
        echo '<div class="question">';
        echo $this->text;
        foreach ($this->answers as $answer) {
            echo '<input type="radio" name="'.$this->id.'" value="'.$answer->id.'" id="'.$answer->id.'"><label for="'.$answer->id.'">'.$answer->text.'</label>';
        }
        echo '<input type="submit" name="button_'.$this->id.'">';
        echo '</div>';
    }

    function correctly_answered() {
        $given_id = $_POST[$this->id];
        $answer = $this->get_answer_by_id($given_id);
        return $answer->correct;
    }
}

$q = new Question();
$q->text = "Welke wielrenner werd ook wel 'De Das' genoemd?";
$q->add_answer("Thomas Dekker");
$q->add_answer("Bernard Hinault", 1);
$q->add_answer("Jens Voigt");
$q->add_answer("Lance Armstrong");

$q->output();

if ($q->correctly_answered()) {
    echo '<h1>Correct!</h1>';
}

?>
</form>
