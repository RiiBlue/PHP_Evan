<?php
session_start();

if (isset($_POST['delete']) && isset($_POST['movie_id'])) {
    require_once "C:\wamp64\config\config.php";

    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE, DB_USERNAME, DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare("DELETE FROM link_movie_cart WHERE movie_id = ? AND cart_id = (SELECT id FROM cart WHERE id_user = ?)");
        $stmt->execute([$_POST['movie_id'], $_SESSION['user_id']]);

        header("Location: cart.php");
        exit();
    } catch (PDOException $e) {
        echo "Erreur de base de données : " . $e->getMessage();
    }
} else {
    header("Location: cart.php");
    exit();
}
?>
