<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CuegnietCiné</title>
    <link rel="stylesheet" href="navebar.css">
    <link rel="stylesheet" href="registers.css">
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
<form action="register.php" method="post">
    <h3>register Here</h3>

    <label for="name">Name</label>
    <input type="text" placeholder="Name..." name="name"  id="name">

    <label for="firstname">Firstname</label>
    <input type="text" placeholder="Firstname..." name="firstname"  id="firstname">

    <label for="email">Email</label>
    <input type="email" placeholder="exemple@gmail.com..." name="email" id="username">

    <label for="phone">Phone Number</label>
    <input type="text" placeholder="0123456789..." name="phone"  id="phone">

    <label for="password">Password</label>
    <input type="password" placeholder="Password" name="password"  id="password">

    <button type="submit">Register</button>
    <div class="social">
        <a href="login.php">Login</a>
    </div>
</form>
<?php
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = htmlspecialchars($_POST['firstname']);
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $password = htmlspecialchars($_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if (strlen($phone) != 10 || substr($phone, 0, 1) != '0') {
        $error_message = "Le numéro de téléphone doit comporter 10 chiffres et commencer par 0.";
    } else {
        require_once "C:\wamp64\config\config.php";

        try {
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE, DB_USERNAME, DB_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $check_query = "SELECT * FROM user WHERE email = :email";
            $stmt = $pdo->prepare($check_query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $error_message = "Un utilisateur avec cet email existe déjà. Veuillez en choisir un autre.";
            } else {
                $insert_query = "INSERT INTO user (firstname, name, email, phone, password) VALUES (:firstname, :name, :email, :phone, :password)";
                $stmt = $pdo->prepare($insert_query);
                $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
                $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
                $stmt->execute();
                
                $stmt = $pdo->prepare("SELECT id FROM user WHERE email = ?");
                $stmt->execute([$email]);
                $current_user = $stmt->fetch(PDO::FETCH_ASSOC);

                $insert_query = "INSERT INTO cart (id_user) VALUES (:id_user)";
                $stmt = $pdo->prepare($insert_query);
                $stmt->bindParam(':id_user', $current_user['id'], PDO::PARAM_STR);
                $stmt->execute();
                
                header("Location: login.php");
                exit();
            }
        } catch (PDOException $e) {
            $error_message = "Erreur lors de l'inscription : " . $e->getMessage();
            echo $error_message;
        }
    }
}
?>
</body>
</html>

