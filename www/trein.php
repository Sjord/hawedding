<?php

require("../lib/db.php");
include("../lib/header.php");

function tijd_volgende_ronde($ronde) {
    // TODO maak dynamisch
    $next_round_time = strtotime("2018-05-12 11:00:00") + $ronde * 30 * 60;
    return $next_round_time;
}

function ronde_tijd_is_om($ronde) {
    $next_round_time = tijd_volgende_ronde($ronde);
    return strtotime("now") >= $next_round_time;
}

$start_time = strtotime("2018-05-27 16:00:00");
$ronde = floor((strtotime("now") - $start_time) / (60 * 30));

$hint = R::findOne('treinhint', '`index`=?', [$ronde]);
?>
<header>
<h1>Treinteam hints</h1>
</header>
<p>
<?php
echo $hint->text;
?>
</p>

<form method="GET"><input type="submit" value="Ververs pagina"><input type="hidden" name="q" value="<?php echo uniqid(); ?>"></form>
<?php

include("../lib/footer.php");
