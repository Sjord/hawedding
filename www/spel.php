<?php
if (empty($_COOKIE)) {
    header("Location: login.php");
}
date_default_timezone_set('Europe/Amsterdam');

require("../lib/db.php");

$echtenaam = $_COOKIE['echtenaam'];
$bruiloftnaam = $_COOKIE['bruiloftnaam'];
$teamnaam = $_COOKIE['team'];

$team = R::findOne("team", "naam=?", [$teamnaam]);
if ($_POST['volgenderonde'] && mag_naar_volgende_ronde($team->ronde, $team)) {
    $team->ronde += 1;
    R::store($team);
}
$ronde = $team->ronde;

include("../lib/header.php");
?>
<header>
    Ingelogged als <?= htmlentities("$bruiloftnaam ($echtenaam) van $teamnaam") ?>
    <h1>Ronde <?php echo $ronde; ?></h1>
</header>
<form method="GET"><input type="submit" value="Ververs pagina"><input type="hidden" name="q" value="<?php echo uniqid(); ?>"></form>
<div class="intro">
    <?php
        include("../lib/ronde$ronde.php");
    ?>
</div>

<?php
function show_hint($ronde, $index, $correct) {
    $hint = R::findOne("hint", "ronde=? AND `index`=? AND correct=?", [$ronde, $index, 0+$correct]);
    if ($correct) {
        echo '<div class="hint">Goede locatiehint: '.$hint->text.'</div>';
    } else {
        echo '<div class="hint">Matige locatiehint: '.$hint->text.'</div>';
    }
}

function is_correctly_answered($question) {
    $correct_answers = R::find("answer", "question_id=? AND correct=1", [$question->id]);
    $given_hashes = $_POST["answer_hash"];
    if (count($correct_answers) != count($given_hashes)) {
        return false;
    }
    foreach ($correct_answers as $a) {
        if (!in_array($a->hash, $given_hashes)) {
            return false;
        }
    }
    return true;
}

if (!empty($_POST["question_hash"]) && !empty($_POST["answer_hash"])) {
    // Er is een antwoord opgestuurd.
    $question = R::findOne("question", "hash=?", [$_POST["question_hash"]]);
    $correct = is_correctly_answered($question);

    $koppel = R::findOne("koppel", "ronde=? AND team_id=? AND question_id=?", [$ronde, $team->id, $question->id]);
    // TODO output iets als de vraag al beantwoord was
    // TODO log gegeven antwoord
    if (!$koppel->answered) {
        $koppel->answered = true;
        $koppel->correct = $correct;
        $koppel->answered_by = $echtenaam;
        R::store($koppel);
    }
}

function output_koppel($koppel) {
    global $ronde;
    $classes = ["question"];
    if ($koppel->answered) $classes[] = "answered";
    if ($koppel->correct) $classes[] = "correct";
    echo '<div class="'.implode(" ", $classes).'">';
    echo '<h2>Vraag '.($koppel->index+1).'</h2>';

    $question = $koppel->question;
    if ($koppel->answered) {
        $question->output_answer();

        if ($koppel->correct) {
            echo '<h3>Goed beantwoord!</h3>';
            show_hint($ronde, $koppel->index, true);
        } else {
            echo '<h3>Fout beantwoord!</h3>';
            show_hint($ronde, $koppel->index, false);
        }
    } else {
        $question->output();
    }

    echo '</div>';
}

$koppels = R::find("koppel", "ronde=? AND team_id=?", [$ronde, $team->id]);
foreach ($koppels as $koppel) {
    output_koppel($koppel);
}

// Volgende ronde logica

function ronde_genoeg_vragen_beantwoord($ronde, $team) {
    $vragen = R::count("koppel", "ronde=? AND team_id=?", [$ronde, $team->id]);
    $beantwoord = R::count("koppel", "ronde=? AND team_id=? AND answered=1", [$ronde, $team->id]);
    return ($beantwoord * 2) >= $vragen;
}

function tijd_volgende_ronde($ronde) {
    // TODO maak dynamisch
    $next_round_time = strtotime("2018-05-12 11:00:00") + $ronde * 45 * 60;
    return $next_round_time;
}

function ronde_tijd_is_om($ronde) {
    $next_round_time = tijd_volgende_ronde($ronde);
    return strtotime("now") >= $next_round_time;
}

function mag_naar_volgende_ronde($ronde, $team) {
    $enough_answered = ronde_genoeg_vragen_beantwoord($ronde, $team);
    $time_elapsed = ronde_tijd_is_om($ronde);
    return $enough_answered && $time_elapsed;
}

$disabled = "";
if (!mag_naar_volgende_ronde($ronde, $team)) {
    $disabled = ' disabled="disabled" ';
}

if ($ronde <= 5) {
?>
<div class="rondeknop">
<form method="POST"><input type="submit" name="volgenderonde" value="Naar volgende ronde" <?php echo $disabled; ?>>
<?php
if (!ronde_genoeg_vragen_beantwoord($ronde, $team)) {
    echo "Je kan nog niet naar de volgende ronde omdat je nog niet genoeg vragen hebt beantwoord. ";
}
if (!ronde_tijd_is_om($ronde)) {
    echo "De volgende ronde begint pas om ".strftime("%H:%M", tijd_volgende_ronde($ronde))." uur.";
}
?>
</form>
</div>
<?php
}

include("../lib/footer.php");
