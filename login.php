<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CuegnietCiné</title>
    <link rel="stylesheet" href="navebar.css">
    <link rel="stylesheet" href="logins.css">
    <script src="https://kit.fontawesome.com/f2f214af03.js" crossorigin="anonymous"></script>
</head>
<header>
<nav>
    <ul class="onglet-liens">
        <li><a class="href" href="index.php"><i class="fa-solid fa-house fa-xs" style="color: #ffffff;"></i>&ensp;Accueil</a></li>
        <li><a class="href" href="search.php"><i class="fa-solid fa-magnifying-glass fa-xs" style="color: #ffffff;"></i>&ensp;Rechercher</a></li>
        <li><a class="href" href="login.php" id="redirectssobutton">S'identifier</a></li>
        <li class="href" id="logoutbutton" style="display: none;">Déconnexion</li>
        <li class="href" id="monCompteText" style="display: none;position: absolute;right: 0;"><i class="fa-solid fa-circle-user" style="color: #ffffff;">Mon Compte</i></li>
        <li><a class="href" href="type.php?type=action" style="color: #ffffff">Action</a></li>
        <li><a class="href" href="type.php?type=drame" style="color: #ffffff">Drame</a></li>
    </ul>
</nav>
</header>
<body>
<form action="login.php" method="post">
    <h3>Login Here</h3>

    <label for="email">Email</label>
    <input type="email" placeholder="email" name="email" id="username">

    <label for="password">Password</label>
    <input type="password" placeholder="Password" name="password"  id="password">

    <button type="submit">Log In</button>
    <div class="social">
        <a href="register.php">S'inscrire</a>
    </div>
</form>
<?php
session_start();

if(isset($_COOKIE['user_email']) && isset($_COOKIE['user_firstname'])) {
    $_SESSION['user_email'] = $_COOKIE['user_email'];
    $_SESSION['user_firstname'] = $_COOKIE['user_firstname'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    require_once "C:\wamp64\config\config.php";
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE, DB_USERNAME, DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM user WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $stored_password = $user['password'];
            if (password_verify($password, $stored_password)) {
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_firstname'] = $user['firstname'];

                setcookie("user_email", $email, time() + 10000000, "/");
                setcookie("user_firstname", $user['firstname'], time() + 10000000, "/");

                header("Location: index.php");
                exit();
            } else {
                echo 'Identifiant ou mot de passe incorrect';
            }
        } else {
            echo 'Identifiant ou mot de passe incorrect';
        }
    } catch (PDOException $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
}
?>
</body>
</html>
