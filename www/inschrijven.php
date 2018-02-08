<!doctype html>

<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta property="og:image" content="wedding_promo.jpg">
    <title>Humphrey en Angelica gaan trouwen - inschrijven</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id="content">
        <h1>Inschrijven</h1>
        <form method="POST">
            <div class="input">
                <label>Naam</label>
                <input type="text" name="naam">
            </div>
            <div class="input">
                <label>Naam</label>
                <input type="text" name="naam">
            </div>
            <div class="input">
                <label>Neem je een +1 mee?</label>
                <input type="radio" name="plusone"><label>nee</label>
                <input type="radio" name="plusone"><label>ja, namelijk <input type="text" name="plusone_name"></label>
            </div>
            <div class="input">
                <iframe src="captcha.html" class="captcha"></iframe>
            </div>
        </form>
    </div>
</body>
</html>
