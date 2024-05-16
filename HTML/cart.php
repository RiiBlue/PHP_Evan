<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link rel="stylesheet" href="./../CSS/navbar.css">
    <link rel="stylesheet" href="./../CSS/cart.css">
    <script src="https://kit.fontawesome.com/f2f214af03.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="wrapper">
        <header>
            <?php include 'header.php' ?>
        </header>

        <?php
        if (!isset($_SESSION['logged_in'])) {
            echo 'Connectez-vous';
        } elseif (isset($_POST['action']) && $_POST['action'] == "add" && isset($_POST['id'])) {
            require_once "C:/wamp64/config/config.php";

            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE, DB_USERNAME, DB_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT * FROM link_movie_cart WHERE cart_id = (SELECT id FROM cart WHERE id_user = ?) AND movie_id = ?");
            $stmt->execute([$_SESSION['user_id'], $_POST['id']]);
            $existing_item = $stmt->fetch();

            if (!$existing_item) {
                $stmt = $pdo->prepare("SELECT id FROM cart WHERE id_user = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $cart_id = $stmt->fetchColumn();

                $insert_query = "INSERT INTO link_movie_cart (cart_id, movie_id) VALUES (:cart_id, :movie_id)";
                $stmt = $pdo->prepare($insert_query);
                $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
                $stmt->bindParam(':movie_id', $_POST['id'], PDO::PARAM_INT);
                $stmt->execute();
            } else {
                echo "Ce film est déjà dans votre panier.";
            }
        }

        if (isset($_SESSION['logged_in'])) {
            require_once "C:/wamp64/config/config.php";

            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE, DB_USERNAME, DB_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT movie.id, movie.picture, movie.title, producer.name, movie.price FROM user JOIN cart ON user.id = cart.id_user JOIN link_movie_cart ON cart.id = link_movie_cart.cart_id JOIN movie ON link_movie_cart.movie_id = movie.id JOIN producer ON movie.producer_id = producer.id WHERE user.id = ?");
            $stmt->execute([$_SESSION['user_id']]);

            $total_price = 0;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $movie_id = $row['id'];
                $picture = $row['picture'];
                $title = $row['title'];
                $price = $row['price'];
                $producer = $row['name'];
                $total_price += $price;
        ?>
                <div class="container"></div>
                <div class="movie">
                    <a href="movie_details.php?id=<?php echo $movie_id; ?>">
                        <img class="image" src="<?php echo $picture; ?>" alt="Image du film <?php echo $movie_id; ?>">
                    </a>
                    <div class="text-info">
                        <a href="movie_details.php?id=<?php echo $movie_id; ?>">
                            <p class="title"><?php echo $title; ?></p>
                            <p class="price"><?php echo $price; ?>€</p>
                            <p class="producer">Réalisateur: <?php echo $producer; ?></p>
                        </a>
                    </div>
                    <form action="movie_delete.php" method="post">
                        <input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>">
                        <button type="submit" name="delete">Supprimer</button>
                    </form>
                </div>
        <?php
            }
        ?>
                <div class="total-price">
                    <p>Prix Total: <?php echo $total_price; ?>€</p>
                    <form action="all_delete_cart.php" method="post">
                        <button type="submit" name="delete_all">Tout Supprimer</button>
                    </form>
                </div>
        <?php
        } else {
            echo 'Votre panier est vide.';
        }
        if(!isset($_COOKIE["user_firstname"])) {
            header("Location: index.php");
            exit;
        }
        ?>
        
    </div>
    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>

</html>
