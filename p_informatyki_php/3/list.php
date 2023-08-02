<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['logged_id'])) {

    if (isset($_POST['login'])) {

        $login = filter_input(INPUT_POST, 'login');
        $password = filter_input(INPUT_POST, 'password');
        //echo $login . " " . $password;

        $userQuery = $db->prepare('SELECT id, password FROM admins WHERE login = :login');
        $userQuery->bindValue(':login', $login, PDO::PARAM_STR);
        $userQuery->execute();

        //echo $userQuery->rowCount();
        $user = $userQuery->fetch();
        //echo $user['id'] . " " . $user['password'];
        //var_dump($user);
        //print_r($user); // to tablica asociacyjna wiec wyswietla sie podwojnie co jest pod 0 i pod "id" to w rzeczywistosci pierwszy index tablicy 
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['logged_id'] = $user['id'];
            unset($_SESSION['bad_attempt']);
        } else {
            $_SESSION['bad_attempt'] = true;
            $_SESSION['err_bad_attempt'] = '<p style="color: red">nie poprawny login lub hasło !</p>';
            $_SESSION['fr_login'] = $_POST['login'];
            $_SESSION['fr_password'] = $_POST['password'];
            header('Location: admin.php');
            exit();
        }
    } else {
        header('Location: admin.php');
        exit();
    }
}
$usersQuery = $db->query('SELECT * FROM users');
$users = $usersQuery->fetchAll();
//print_r($users);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>

<body>
    <div class="container">

        <header>
            <h1>Newsletter</h1>
        </header>
        <main>
            <article>
                <table>
                    <thead>
                        <tr>
                            <th colspan="2">Łącznie rekordów: <?= $usersQuery->rowCount() ?></th>
                        </tr>
                        <tr>
                            <th>ID</th>
                            <th>E-mail</th>
                        </tr>
                    <tbody>
                        <?php
                        foreach ($users as $user){
                            echo "<tr><td>{$user['id']}</td><td>{$user['email']}</td></tr>";
                        }
                        ?>
                    </tbody>
                    </thead>
                </table>
                <p><a href="logout.php">Wyloguj się !</a></p>
            </article>
        </main>

    </div>
</body>

</html>