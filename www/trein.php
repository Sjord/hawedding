<?php

require("../lib/db.php");
include("../lib/header.php");

// Tijd vanaf dat de eerste hint wordt getoond
$start_time = strtotime("2018-06-01 18:10:00");
$ronde = max(floor((strtotime("now") - $start_time) / (60 * 30)), -1);
$volgende_ronde = $start_time + (($ronde + 1) * 30 * 60);

$hint = R::findOne('treinhint', '`index`=?', [$ronde]);
?>
<header>
<h1>Treinteam hints</h1>
</header>
<p>
<?php
if ($hint) {
    echo '<p>'.$hint->text.'</p>';
} else {
    echo "Er is op dit moment geen hint voor de route per trein. Probeer het later nog eens.";
}
echo " De volgende hint is beschikbaar vrijdag om ".strftime("%H:%M", $volgende_ronde)." uur.";
?>
</p>

<form method="GET"><input type="submit" value="Ververs pagina"><input type="hidden" name="q" value="<?php echo uniqid(); ?>"></form>
<?php

include("../lib/footer.php");
