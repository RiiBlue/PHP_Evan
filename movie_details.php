<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CuegnietCiné</title>
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="movie_details.css">
    <script src="https://kit.fontawesome.com/f2f214af03.js" crossorigin="anonymous"></script>
</head>
<body>
<header>
    <?php include'header.php' ?>
</header>
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
                    // Votre code PHP pour récupérer les détails du film

                    // En dehors des balises PHP, vous pouvez inclure du HTML en fermant temporairement les balises PHP
                    if (isset($film['picture']) && isset($film['title'])) {
                        $picture = $film['picture'];
                        $title = $film['title'];
                ?>
                    <img src="<?php echo $picture; ?>" alt="Image du film <?php echo $title; ?>">
                <?php
                    }
                ?>
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
                            <p><strong>Producteur:</strong> <a href="producer_details.php?id=<?php echo urlencode($film['producer_id']); ?>"><?php echo $producer_name['name']; ?></a></p>
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