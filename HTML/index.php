<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CuegnietCiné</title>
    <link rel="stylesheet" href="./../CSS/navbar.css">
    <link rel="stylesheet" href="./../CSS/index.css">
</head>
<body>
<header>
    <?php include'header.php' ?>
</header>
<div class="movie-container">
    <?php
    require_once "C:\wamp64\config\config.php";
    
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE, DB_USERNAME, DB_PASSWORD);
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
<?php include 'footer.php'; ?>

</body>
</html>