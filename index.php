<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CuegnietCiné</title>
    <link rel="stylesheet" href="navebar.css">
    <link rel="stylesheet" href="index.css">
    <script src="https://kit.fontawesome.com/f2f214af03.js" crossorigin="anonymous"></script>
</head>
<header>
<nav>
    <ul class="onglet-liens">
        <li><a class="href" href="index.php"><i class="fa-solid fa-house-chimney-window" style="color: #ffffff;"></i>&ensp;Accueil</a></li>
        <li><a class="href" href="search.php"><i class="fa-solid fa-magnifying-glass-arrow-right" style="color: #ffffff;"></i>&ensp;Rechercher</a></li>
        <?php
        session_start();
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {?>
            <li><a class="href" href="login.php" id="redirectssobutton"><i class="fa-solid fa-right-to-bracket"></i>S'identifier</a></li>
            <?php
        } else {?>
            <li><a class="href" href="logout.php" id="logoutbutton"><i class="fa-solid fa-arrow-right-to-bracket"></i>Déconnexion</a></li>
            <li> <a class="href" href="account.php" ><i class="fa-solid fa-user" style="color: #ffffff;"></i>Mon compte</a></li>';
            <li> <a class="href" href="cart.php" ><i class="fa-solid fa-basket-shopping" style="color: #ffffff;"></i>Mon Panier</a></li>
            <?php
        }?>
        <li><a class="href" href="type.php?type=action" >Action</a></li>
        <li><a class="href" href="type.php?type=drame" >Drame</a></li>
    </ul>
</nav>
</header>
<body>
<div class="movie-container">
    <?php
    require_once "C:\wamp64\config\config.php";
    
    try {
        $pdo = new PDO("mysql:host=" . 'localhost' . ";dbname=" . 'ProjetEvanPHP', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->query("SELECT id, picture, title, price FROM movie LIMIT 20");

        $counter = 0;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $movie_id = $row['id'];
            $picture = $row['picture'];
            $title = $row['title'];
            $price = $row['price'];
            ?>

    <div class="movie">
        <a href="movie_details.php?id=<?php echo $movie_id; ?>">
            <img class="image" src="<?php echo $picture; ?>" alt="Image du film <?php echo $movie_id; ?>">
            <p class="title"><?php echo $title; ?></p>
            <p class="title"><?php echo $price; ?>€</p>
        </a>
        <?php if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) { ?>
            <a class="button" href="login.php">Veuillez vous connecter</a>
        <?php } else { ?>
            <form action="cart.php" method="post">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="id" value="<?php echo $movie_id; ?>">
                <button type="submit" class="button">Ajouter au panier</button>
            </form>
        <?php } ?>
    </div>



            <?php
            $counter++;

            if ($counter % 3 == 0) {?>
                </div><div class="movie-container">';
                <?php
            }
        }
    } catch(PDOException $e) {
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
    }
    ?>
</div>
</body>
</html>