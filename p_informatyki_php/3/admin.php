<?php
session_start();
if(isset($_SESSION['logged_id'])){
    header('Location: list.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel admina</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <header>
            <h1>Admin</h1>
        </header>

        <main>
            <article>
                <form method="post" action="list.php">
                    Login<br />
                    <input type="text" id="login" name='login' placeholder="Login" required value="<?php 
                        if(isset($_SESSION['bad_attempt'])){
                            echo $_SESSION['fr_login'];
                            unset($_SESSION['fr_login']);
                        }
                    ?>" /><br/>
                    Hasło<br>
                        <input type="password" id="password" name="password" placeholder="*********" required value="<?php 
                        if(isset($_SESSION['bad_attempt'])){
                            echo $_SESSION['fr_password'];
                            unset($_SESSION['fr_password']);
                        }
                    ?>" /><br/>
                    <input type="submit" value="zaloguj się">
                </form>
                <?php
                    if (isset($_SESSION['bad_attempt'])){
                    echo $_SESSION['err_bad_attempt'];
                    unset($_SESSION['bad_attempt']);
                    }
                ?>
            </article>
        </main>


    </div>
</body>

</html>