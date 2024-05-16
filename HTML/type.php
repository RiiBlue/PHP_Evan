<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CuegnietCiné</title>
    <link rel="stylesheet" href="./../CSS/navbar.css">
    <link rel="stylesheet" href="./../CSS/index.css">
    <script src="https://kit.fontawesome.com/f2f214af03.js" crossorigin="anonymous"></script>
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

        if(isset($_GET['type'])) {
            $type = $_GET['type'];

            $stmt = $pdo->prepare("SELECT id, picture, title, price FROM movie WHERE type = ?");
            $stmt->execute([$type]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as $row) {
                $movie_id = $row['id'];
                $picture = $row['picture'];
                $title = $row['title'];
                $price = $row['price'];
                ?>

                <div class="movie" >
                    <a href="movie_details.php?id=<?php echo $movie_id; ?>">
                        <img class="image" src="<?php echo $picture; ?>" alt="Image du film <?php echo $movie_id; ?>">
                        <p class="title"><?php echo $title; ?></p>
                        <p class="title"><?php echo $price;?>€</p>
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
            }
        } else {
            ?>
            <p>Aucun type sélectionné.</p>
            <?php
        }
    } catch (PDOException $e) {
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
    }
    ?>
</div>
<?php include'footer.php' ?>
</body>
</html>
