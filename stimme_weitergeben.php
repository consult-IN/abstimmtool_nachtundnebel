<?php
session_start();
require("php/sec/mysql.php");
$stmt = $mysql->prepare("SELECT * FROM wahlen WHERE ACTIVE = 1"); //verified überprüfen
$stmt->execute();
$count = $stmt->rowCount();
if ($count == 1) {
    header("Location: voting.php");
    exit;
}
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

if (isset($_POST["add"])) {

    if ($_POST["email"] == $_SESSION["email"]) {
        print("<script>alert('Du Wicht! Stimme an dich selber weitergeben? Das geht nicht!')</script>");
    } else {

        $stmt = $mysql->prepare("INSERT INTO stimmen_weitergaben (VON, ZU) VALUES (:von, :zu)");

        $stmt->bindParam(":zu", $_POST["email"]);
        $stmt->bindParam(":von", $_SESSION["email"]);
        $stmt->execute();

        //Personen über Stimmenrückführung informieren!

        $stmt = $mysql->prepare("SELECT * FROM stimmen_weitergaben WHERE stimmen_weitergaben.zu = '" . $_SESSION["email"] . "'");
        $stmt->execute();
        $count = $stmt->rowCount();
        if ($count >= 1) {
            $stmt = $mysql->prepare("DELETE FROM stimmen_weitergaben WHERE stimmen_weitergaben.zu = '" . $_SESSION["email"] . "'");
            $stmt->execute();
        }

        header("Location: stimme_weitergeben.php");
    }
}

?>

<body>

    <div class="main-content mx-auto">

        <div id="top-image"><img class="img-fluid mx-auto d-block" src="res/Logo consult.IN_Transparent.png" style="max-width: 40%; margin-top: 5px;"></div>
        <div id="button-handler" style="text-align: center; margin-top: 25px; margin-bottom: 10px;">

            <?php

            require("php/sec/mysql.php");

            $stmt = $mysql->prepare("SELECT * FROM stimmen_weitergaben WHERE VON = :email");
            $stmt->bindParam(":email", $_SESSION["email"]);
            $stmt->execute();
            $count = $stmt->rowCount();
            if ($count == 1) {
                echo '<a href="rm_stimmenweitergabe.php" class="btn btn-danger btn-lg active" role="button" aria-pressed="true">Du hast deine Stimme bereits weitergegeben! <br> Klicke hier, um deine Stimme zurückzufordern.</a>';
            } else {
                echo '
                <div style="width: 18rem; text-align: left;" class="mx-auto">
                <form action="stimme_weitergeben.php" method="post">
                <div class="form-group">
                    <label for="exampleFormControlInput1">Email (gibt die @consultin.net Adresse der Person ein, an die du deine Stimme weitergeben willst):</label>
                    <input type="email" class="form-control" name="email" id="exampleFormControlInput1" placeholder="@consultin.net" required>
                </div>
                <button type="submit" name="add" class="btn btn-primary">Bestätigen</button>
                </form>
                </div>

                ';
            }

            ?>

        </div>

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