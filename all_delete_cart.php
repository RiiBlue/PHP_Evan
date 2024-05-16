<?php
session_start();

if (isset($_SESSION['logged_in'])) {
    require_once "C:/wamp64/config/config.php";

    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE, DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT id FROM cart WHERE id_user = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $cart_id = $stmt->fetchColumn();

    $delete_query = "DELETE FROM link_movie_cart WHERE cart_id = :cart_id";
    $stmt = $pdo->prepare($delete_query);
    $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: cart.php");
    exit();
} else {
    header("Location: login.php");
    exit();
}
?>
