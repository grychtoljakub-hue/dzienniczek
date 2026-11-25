<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$pdo = new PDO(
'mysql:host=localhost;dbname=twoja_baza;charset=utf8mb4',
'uzytkownik',
);

$uzytkownicy = $pdo->query("SELECT DISTINCT uzytkownik FROM dane")->fetchAll(PDO::FETCH_COLUMN);
    

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moja strona</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

</head>

<body>

    <header>
        <div class="tytul">
            Generator dzienniczka
        </div>

<nav>
    <div class="menu">
        <a href="#">Menu ▼</a>

        <div class="lista">
            <a href="#formularz">użytkownicy</a>
            <a href="#miejsca">wynik</a>
            <a href="#stopka">Kontakt</a>
        </div>
    </div>
</nav>
    </header>

    <section class="formularz" id="formularz">
        <h2>wybierz swojego wojownika</h2>


<div class="formularz" id="formularz">

    
<form method="POST">

    <label>Wybierz użytkownika:</label>

    <select name="uzytkownik" required>
        <option value="">Wybierz użytkownika</option>

        <?php
        foreach ($uzytkownicy as $uzytkownik) {
            echo '<option value="' . htmlspecialchars($uzytkownik) . '">';
            echo htmlspecialchars($uzytkownik);
            echo '</option>';
        }
        ?>

    </select>

    <br><br>

    <label for="data_od">Data od:</label>
    <input type="date" id="data_od" name="data_od" required>

    <br><br>

    <label for="data_do">Data do:</label>
    <input type="date" id="data_do" name="data_do" required>

    <br><br>

    <button type="submit" name="akcja" value="szukaj">
        Szukaj
    </button>

</form>

<?php

if (isset($_POST['uzytkownik'])) {
    echo "Wybrany użytkownik: " .
    htmlspecialchars($_POST['uzytkownik']);

    echo "<br>Data od: " .
    htmlspecialchars($_POST['data_od']);

    echo "<br>Data do: " .
    htmlspecialchars($_POST['data_do']);
}

?>
</div>
    </section>

    <div class="wynik" id="miejsca" style="display: none;">
    <h2>Wynik</h2>

    <div class="wyniki">

    </div>
</div>

    <footer>
        <p>Autorzy fk jj jg hcz © 2026</p>
    </footer>
    <?php
    
        $uzytkownik = $_POST['uzytkownik'];
        $od = $_POST['od'];
        $do = $_POST['do'];
        $generuj = $_POST['generuj'];
        $zapytanie = "SELECT * FROM `dane` WHERE `uzytkownik` = '$uzytkownik' AND `data` BETWEEN '$od' AND '$do'";
        $wynik = $pdo->query($zapytanie);

    $html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>';
    $i = 0;
    while ($row = $wynik->fetch(PDO::FETCH_ASSOC)) {
        $i++;
        $zawartosc = $row['zawartosc'];
        $html .= "<p><h1>DZIEN $i</h1></p><p>$zawartosc</p><P>-----------------------------</P>";
    };


$html .= '
</body>
';

$options = new Options();
$options->set('defaultFont', 'DejaVu Sans');

$dompdf = new \Dompdf\Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$pdo = NULL;
    ?>
</body>
</html>