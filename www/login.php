<?php
if (!empty($_POST['echtenaam']) && !empty($_POST['bruiloftnaam'])) {
    setcookie("echtenaam", $_POST["echtenaam"]);
    setcookie("bruiloftnaam", $_POST["bruiloftnaam"]);
    setcookie("team", $_POST["team"]);
    header("Location: spel.php");
}
include("../lib/header.php");

$active = strtotime("now") > strtotime("2018-06-01");
?>
<form method="POST">
    <div>
        <label>Je echte naam:</label><input type="text" name="echtenaam">
    </div><div>
        <label>Je bruiloft naam:</label><input type="text" name="bruiloftnaam">
    </div><div>
        <label>Team:</label>
        <select name="team">
            <option>De Schoonfamilie</option>
            <option>De Rugbyvrienden</option>
            <option>De Bruidsmeisjes</option>
            <option>De Partycrashers</option>
        </select>
    </div><div>
        <input type="submit" value="Login" <?php if (!$active) { echo 'disabled="disabled"'; } ?>>
        <?php
        if (!$active) { echo "Je kan nog niet inloggen. Probeer het later nog eens."; }
        ?>
    </div>
</form>
<?php
include("../lib/footer.php");
