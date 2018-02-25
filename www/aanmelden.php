<?php
    $error = false;
    $settings = ["email" => ""]; // TODO move to settings
    if (!empty($_POST)) {
        $fres = file_put_contents("../data/".uniqid().".json", json_encode($_POST));
        $mres = mail($settings["email"], "Inschrijving voor bruiloft", json_encode($_POST));

        if ($fres || $mres) {
            header("Location: bedankt.html");
        } else {
            header("Location: error.html");
        }
    }
?>
<!doctype html>

<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta property="og:image" content="wedding_promo.jpg">
    <title>Humphrey en Angelica gaan trouwen - aanmelden</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id="content">
        <form method="POST">
        <h1>Aanmelden voor de bruiloft</h1>
        <p>
            Met dit formulier kan je je aanmelden voor de bruiloft in het weekend van 1, 2, en 3 juni tussen Humphrey en Angelica.
        </p>
        <h2>Wie ben je?</h2>
            <div class="input">
                <label for="naam">Naam</label>
                <input type="text" name="naam" placeholder="jouw naam" id="naam">
            </div>
            <div class="input">
                <label>Wat is je relatie met het bruidspaar?</label>
                <em>Ik ben <input type="text" name="relatie" placeholder="relatie" id="relatie"> van
                <select name="relatie_subject" id="relatie_subject">
                    <option>Angelica</option>
                    <option>Humphrey</option>
                </select>
                .</em>
            </div>
            <div class="input">
                <label>Neem je een +1 mee? (in overleg met de ceremoniemeesters)</label>
                <input type="radio" name="plusone" value="nee" id="plusone-nee"><label for="plusone-nee">nee</label>
                <input type="radio" name="plusone" value="ja" id="plusone-ja"><label for="plusone-ja">ja, namelijk <input type="text" name="plusone_name" placeholder="naam van +1" id="plusone_name"></label>
            </div>
        <h2>Vertrek</h2>

            <div class="input">
                <label>Welk vervoersmiddel heb je tot je beschikking?</label>
                <input type="checkbox" name="vervoer" value="auto" id="vervoer-auto"><label for="vervoer-auto">Auto</label>
                <input type="checkbox" name="vervoer" value="weekend OV" id="vervoer-weekend-ov"><label for="vervoer-weekend-ov">Weekend OV</label>
                <input type="checkbox" name="vervoer" value="week OV" id="vervoer-week-ov"><label for="vervoer-week-ov">Week OV</label>
            </div>
            <div class="input">
                <label for="vertrekplaats">Vanuit welke plaats vertrek je?</label>
                <input type="text" name="vertrekplaats" value="Haarlem" id="vertrekplaats">
            </div>
            <div class="input">
                <label for="vertrektijd">Hoe laat kan je vrijdag 1 juni op het clubhuis zijn, of vertrekken uit bovenstaande plaats?</label>
                <input type="text" name="vertrektijd" placeholder="18:00" id="vertrektijd">
            </div>

        <h2>Feest</h2>
            <div class="input">
                <label>Op zaterdagavond wordt er een bescheiden diner geserveerd. Waar gaat je voorkeur naar uit?</label>
                <input type="radio" name="eten" value="vlees" id="eten-vlees"><label for="eten-vlees">Vlees</label>
                <input type="radio" name="eten" value="vis" id="eten-vis"><label for="eten-vis">Vis</label>
                <input type="radio" name="eten" value="vega" id="eten-vega"><label for="eten-vega">Vega</label>
            </div>

            <div class="input">
                <label for="karaoke">Heb je een verzoeknummer voor de karaoke-avond?</label>
                <input type="text" name="karaoke" placeholder="naam van artiest en nummer" id="karaoke">
            </div>
        <p>
            Cadeautip: ✉️
Bruidschat aanbetaling 30
        </p>
        <input type="submit" value="Aanmelden">
        </form>
    </div>
</body>
</html>
