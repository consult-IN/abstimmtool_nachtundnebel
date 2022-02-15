<?php
session_start();
if (!isset($_SESSION["email"]) && $_SESSION["level"] >= 1) {
    header("Location: index.php");
    exit;
}
?>
<!doctype html>
<html lang="de">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!-- additional CSS -->
    <link href="style.css" rel="stylesheet">

    <title>Abstimmtool consult.IN</title>
</head>

<body>

    <div class="main-content mx-auto">

        <div id="top-image"><img class="img-fluid mx-auto d-block" src="res/Logo consult.IN_Transparent.png" style="max-width: 40%; margin-top: 5px;"></div>
        <div id="button-handler" style="text-align: center; margin-top: 25px; margin-bottom: 10px;">

            <a href="wahl_add.php" class="btn btn-secondary btn-lg men_opt" tabindex="-1" role="button" aria-disabled="true">Wahl hinzufügen</a>
            <a href="wahl_view_res_selected.php" class="btn btn-secondary btn-lg men_opt" tabindex="-1" role="button" aria-disabled="true">Wahlergebnisse ansehen</a>
            <a href="wahl_partic_selected.php" class="btn btn-secondary btn-lg men_opt" tabindex="-1" role="button" aria-disabled="true">Wahlteilnehmer ansehen</a>
            <a href="wahl_active_members.php" class="btn btn-secondary btn-lg men_opt" tabindex="-1" role="button" aria-disabled="true">SB Mitglieder eingabe</a>
            <a href="activate_weitergabe.php" class="btn btn-secondary btn-lg men_opt" tabindex="-1" role="button" aria-disabled="true">Stimmenweitergabe (aktiv./deaktiv.)</a>

        </div>

        <?php
        require("php/sec/mysql.php");

        $db_erg_entry = mysqli_query($db_link, 'SELECT * FROM wahlen ');

        if (!$db_erg_entry) {
            die('Ungültige Abfrage: ' . mysqli_error());
        }

        echo '<table border="1" class="table">';
        echo "<tr>";
        echo "<td>" . "ID" . "</td>";
        echo "<td>" . "Titel" . "</td>";
        echo "<td>" . "Elemente" . "</td>";
        echo "<td></td>";
        echo "</tr>";

        while ($zeile = mysqli_fetch_array($db_erg_entry, MYSQLI_ASSOC)) {

            echo "<tr>";
            echo "<td>" . $zeile['WAHL_ID'] . "</td>";
            echo "<td>" . $zeile['TITLE'] . "</td>";
            echo "<td>" . $zeile['ITEMS'] . "</td>";

            if ($zeile['ACTIVE'] == 0) {
                echo '<td><a href="activatevote.php?wahlid=' . $zeile["WAHL_ID"] . '" class="btn btn-outline-success active" role="button" aria-pressed="true">Aktivieren</a></td>"';
            } else {
                echo '<td><a href="stopvote.php?wahlid=' . $zeile["WAHL_ID"] . '" class="btn btn-outline-danger active" role="button" aria-pressed="true">Wahl schließen</a></td>"';
            }

            echo "</tr>";
        }
        echo "</table>";




        ?>

        <a href="voting.php" class="btn btn-secondary btn-lg" tabindex="-1" role="button" aria-disabled="true">Zurück</a>

    </div>

    <!-- Optional JavaScript; choose one of the two! -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous">
    </script>
    <!-- Option 2: Separate Popper and Bootstrap JS 
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
    <script src="bootstrap-5.0.0-beta1-dist/js/bootstrap.min.js"></script> -->

</body>

</html>