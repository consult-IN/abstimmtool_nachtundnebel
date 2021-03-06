<?php
session_start();
if (!isset($_SESSION["email"])) {
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

<?php

require("php/sec/mysql.php");
require("php/vote.php");

if (isset($_POST["vote"])) {

    $stmt = $mysql->prepare("SELECT * FROM wahlen WHERE ACTIVE = :active_nr");
    $active_nr = 1;
    $stmt->bindParam(":active_nr", $active_nr);
    $stmt->execute();
    $count = $stmt->rowCount();
    if ($count == 1) {

        $row = $stmt->fetch();
        $wahlid = $row["WAHL_ID"];

        $stmt = $mysql->prepare("SELECT * FROM wahlen WHERE ACTIVE = :active_nr");
        $active_nr = 1;
        $stmt->bindParam(":active_nr", $active_nr);
        $stmt->execute();
        $count = $stmt->rowCount();
        if ($count == 1) {

            $row = $stmt->fetch();
            $elements = explode(',', $row["ITEMS"]);
            $elem_amount = count($elements);

            $selected = array();

            $n = 0;

            for ($k = 0; $k < $elem_amount * 2; $k = $k + 2) {

                $selected[$k] = $elements[$n];
                if ($_POST[$n] != NULL) {
                    $selected[($k + 1)] = $_POST[$n];
                } else {
                    $selected[($k + 1)] = 0;
                }
                $n++;
            }

            $result = vote($selected, $_SESSION["email"], $wahlid);

            if ($result == 0) {
                echo "<script>alert('Stimme erfolgreich abgegeben!')</script>";
            }
        }
    }
}

?>

<body>

    <div class="main-content mx-auto">

        <div id="top-image"><img class="img-fluid mx-auto d-block" src="res/Logo consult.IN_Transparent.png" style="max-width: 40%; margin-top: 5px;"></div>
        <div id="button-handler" style="text-align: center; margin-top: 25px; margin-bottom: 10px;">

            <a href="stimme_weitergeben.php" class="btn btn-secondary btn-lg" tabindex="-1" role="button" aria-disabled="true">Stimme weitergeben</a>
            <?php

            require("php/sec/mysql.php");

            if ($_SESSION["level"] >= 1) {
                echo '<a href="wahlleiter.php" class="btn btn-secondary btn-lg" tabindex="-1" role="button" aria-disabled="true">Wahlleiter</a>';
            }

            $stmt = $mysql->prepare("SELECT * FROM stimmen_weitergaben WHERE ZU = :email");
            $stmt->bindParam(":email", $_SESSION["email"]);
            $stmt->execute();
            $count = $stmt->rowCount();
            echo '<p>Du hast ' . $count . ' zus??tzliche Stimmen</p>';

            $db_erg_entry = mysqli_query($db_link, 'SELECT * FROM active_member');

            if (!$db_erg_entry) {
                die('Ung??ltige Abfrage: ' . mysqli_error());
            }

            while ($zeile = mysqli_fetch_array($db_erg_entry, MYSQLI_ASSOC)) {

                echo '<p><u>Maximale Anzahl an Stimmen pro Person: ' . ($zeile["active_mem"] * 0.05) . '</u></p>';
            }

            $stimmen = $count;

            $stmt = $mysql->prepare("SELECT * FROM wahlen WHERE ACTIVE = :active_nr");
            $active_nr = 1;
            $stmt->bindParam(":active_nr", $active_nr);
            $stmt->execute();
            $count = $stmt->rowCount();
            if ($count == 1) {

                $row = $stmt->fetch();
                $elements = explode(',', $row["ITEMS"]);

                echo ' 
            
            <div class="card mx-auto" style="width: 18rem;">
            <div class="card-body">
            <h5 class="card-title"> ' . $row["TITLE"] . '</h5>
            <form action="voting.php" method="post">
            <div class="form-group">';

                for ($i = 0; $i < count($elements); $i++) {
                    echo '
                <label for="basic-url" class="form-label">Auswahl ' . ($i + 1) . ':</label>
                <div class="input-group mb-3">
                    <div class="hstack gap-3 mx-auto">
                    <div class="bg-light border mx-auto"><span class="input-group-text" id="basic-addon3">' . $elements[$i] . '</span></div>
                    <div class="bg-light border ms-auto mx-auto"><input type="number" class="form-control" id="' . $i . '" name="' . $i . '" aria-describedby="basic-addon3" min="0"></div> 
                    </div>
                </div>';
                };

                echo '
            </div>

            <button type="submit" name="vote" class="btn btn-primary">Abstimmen</button>
            </form>
            </div>
            </div>
            
            ';
            } else {
                echo '<p>Keine aktive Wahl. M??glicherweise musst du diese Seite aktualisieren.</p>';
            }



            ?>

        </div>

        <a href="php/logout.php" class="btn btn-secondary btn-lg" tabindex="-1" role="button" aria-disabled="true">Logout</a>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous">
    </script>
    <!-- Option 2: Separate Popper and Bootstrap JS 
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
    <script src="bootstrap-5.0.0-beta1-dist/js/bootstrap.min.js"></script> -->

</body>

</html>