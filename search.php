<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CuegnietCiné</title>
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="searchs.css">
    <script src="https://kit.fontawesome.com/f2f214af03.js" crossorigin="anonymous"></script>
</head>
<header>
<nav>
    <ul class="onglet-liens">
        <li><a class="href" href="index.php"><i class="fa-solid fa-house-chimney-window" style="color: #ffffff;"></i>&ensp;Accueil</a></li>
        <li><a class="href" href="search.php"><i class="fa-solid fa-magnifying-glass-arrow-right" style="color: #ffffff;"></i>&ensp;Rechercher</a></li>
        <?php
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

<h1>Recherche de films</h1>

<?php
require_once "C:/wamp64/config/config.php";

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE, DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['recherche']) && !empty($_POST['recherche'])) {
        $recherche = $_POST['recherche'];
        $stmt = $pdo->prepare("SELECT movie.id, movie.picture, movie.title, movie.price FROM movie JOIN producer ON producer.id = movie.producer_id WHERE title LIKE ? OR type LIKE ? OR producer.name LIKE ?");
        $stmt->execute(["%$recherche%", "%$recherche%", "%$recherche%"]);
        $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $stmt = $pdo->query("SELECT id, picture, title, price FROM movie");
        $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}
?>

<form method="post">
    <input type="text" name="recherche" placeholder="Rechercher un film, type ou producteur">
    <button type="submit">Rechercher</button>
</form>

<?php if (!empty($resultats)): ?>
    <h2>Résultats de la recherche :</h2>
    <div class="film-container">
    <?php foreach ($resultats as $film): ?>
        <a href="movie_details.php?id=<?php echo $film['id']; ?>" class="film">
            <img src="<?php echo $film['picture']; ?>" alt="<?php echo $film['title']; ?>">
            <div>
                <h2><?php echo $film['title']; ?></h2>
                <p>Prix : <?php echo $film['price']; ?>€</p>
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <form action="cart.php" method="post">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="id" value="<?php echo $film['id']; ?>">
                        <button type="submit" class="button">Ajouter au panier</button>
                    </form>
                <?php
            endif; ?>
            </div>
        </a>
    <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>Aucun résultat trouvé.</p>
<?php endif; ?>

</body>
</html>
