<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CuegnietCiné</title>
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="accounts.css">
    <script src="https://kit.fontawesome.com/f2f214af03.js" crossorigin="anonymous"></script>
</head>
<header>
    <?php include'header.php' ?>
</header>
<body>
<div class="texte">
        <?php 
            echo ("Bienvenue " . $_COOKIE["user_firstname"] . ". Vous êtes connecté !");
        ?>
    </div>
</body>