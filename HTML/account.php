<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CuegnietCiné</title>
    <link rel="stylesheet" href="./../CSS/navbar.css">
    <link rel="stylesheet" href="./../CSS/accounts.css">
    <script src="https://kit.fontawesome.com/f2f214af03.js" crossorigin="anonymous"></script>
</head>
<header>
    <?php include 'header.php'; ?>
</header>
<body>
<div class="wrapper"><?php
    if(!isset($_COOKIE["user_firstname"])) {
        header("Location: index.php");
        exit;
        }?>
    <div class="texte">
        <?php
        echo ("Bienvenue " . $_COOKIE["user_firstname"] . ". Vous êtes connecté !");
        ?>
    </div>
    <?php include 'footer.php'; ?>
</div>
</body>
</html>
