<?php
if (!empty($_POST['echtenaam']) && !empty($_POST['bruiloftnaam'])) {
    setcookie("echtenaam", $_POST["echtenaam"]);
    setcookie("bruiloftnaam", $_POST["bruiloftnaam"]);
    setcookie("team", $_POST["team"]);
}
?>
<form method="POST">
<label>Je echte naam:</label><input type="text" name="echtenaam">
<label>Je bruiloft naam:</label><input type="text" name="bruiloftnaam">
<label>Team:</label>
<select name="team">
<option>De Schoonfamilie</option>
<option>De Rugbyvrienden</option>
<option>De Bruidsmeisjes</option>
<option>De Partycrashers</option>
</select>
<input type="submit">
</form>
