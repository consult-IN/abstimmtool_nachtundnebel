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

<?php

require("php/sec/mysql.php");

if (isset($_POST["view"])) {
    header("Location: wahl_view_partic.php?id=" . $_POST["id"]);
}

?>

<body>

    <div>

        <div class="card mx-auto vert_center" style="width: 18rem; vertical-align: middle;">
            <img class="card-img-top" src="res/Logo consult.IN_Transparent.png" alt="Card image cap" style="padding: 10px 10px 10px 10px;">
            <div class="card-body">
                <form action="wahl_partic_selected.php" method="post">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email eingeben: ("all" für alle Einträge)</label>
                        <div class="form-group">
                            <input type="text" class="form-control" id="exampleInputEmail1" name="id" aria-describedby="emailHelp" placeholder="">
                            </select>
                        </div>
                        <button type="submit" name="view" class="btn btn-primary">Ansehen</button>
                </form>
            </div>
        </div>

        <a href="wahlleiter.php" class="btn btn-secondary btn-lg" tabindex="-1" role="button" aria-disabled="true">Zurück</a>

    </div>

    <!-- Optional JavaScript; choose one of the two! -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous">
    </script>
    <!-- Option 2: Separate Popper and Bootstrap JS 
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
    <script src="bootstrap-5.0.0-beta1-dist/js/bootstrap.min.js"></script> -->

</body>

</html>