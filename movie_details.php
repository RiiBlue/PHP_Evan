<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CuegnietCiné</title>
    <link rel="stylesheet" href="navebar.css">
    <link rel="stylesheet" href="movie_details.css">
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
<div class="content-wrapper">
    <?php
    if (isset($_GET['id'])) {
        $film_id = $_GET['id'];
        require_once "C:\wamp64\config\config.php";

        try {
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE, DB_USERNAME, DB_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT title, picture, video, synopsis, director, producer_id, actor, date, price, type, time FROM movie WHERE id = ?");
            $stmt->execute([$film_id]);
            $film = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($film) {
                echo '<h1>' . $film['title'] . '</h1>';
                echo '<video controls><source src="' . $film['video'] . '" type="video/mp4"></video>';
                ?>
                <div class="image" >
                    <?php
                    echo '<img src="' . $film['picture'] . '" alt="Image du film ' . $film['title'] . '">';?>
                    <div class="film-details">
                        <div class="film-info">
                            <p><strong>Date de Sortie:</strong> <?php echo $film['date']; ?></>
                            <p><strong>Durée:</strong> <?php echo $film['time']; ?></p>
                            <p href="type.php"><strong>Type:</strong> <?php echo $film['type']; ?></p>
                            <p><strong>Prix:</strong> <?php echo $film['price'];?>€</p>
                            <?php if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) { ?>
                                <a class="button" href="login.php">Veuillez vous connecter</a>
                            <?php } else { ?>
                                <form action="cart.php" method="post">
                                    <input type="hidden" name="action" value="add">
                                    <input type="hidden" name="id" value="<?php echo $film_id; ?>">
                                    <button type="submit" class="button">Ajouter au panier</button>
                                </form>
                            <?php } ?>
                        </div>
                        <div class="film-casting">
                            <p><strong>Réalisateur:</strong> <?php echo $film['director']; ?></p>
                            <?php 
                                $stmt = $pdo->prepare("SELECT name FROM producer WHERE id = ?");
                                $stmt->execute([$film['producer_id']]);
                                $producer_name = $stmt->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <?php echo '<p><strong>Producteur:</strong> <a href="producer_details.php?id=' . urlencode($film['producer_id']) . '">' . $producer_name['name'] . '</a></p>';?>
                            <p><strong>Acteurs:</strong> <?php echo $film['actor']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="synopsis" >
                    <h2>Synopsis</h2>
                    <?php echo $film['synopsis']; ?>
                </div>
            <?php
            } else {
                ?>
                <p>Aucun film trouvé avec cet ID.</p>
                <?php
            }
        } catch (PDOException $e) {
            echo "Erreur de connexion à la base de données : " . $e->getMessage();
        }
    } else {
        ?>
        <p>Aucun film sélectionné.</p>
        <?php
    }
    ?>
</body>
</html>