<?php 
    require_once "connect.php"; 
    session_start();

    if ((!isset($_POST['login'])) || (!isset($_POST['haslo']))){
        header('Location: index.php');
        exit();
    }

    try {
        $DBconnect = new mysqli($host, $db_user, $db_password, $db_name);
        if ($DBconnect->connect_errno!=0){
            throw new Exception("Error: ".$DBconnect->connect_errno);
    } else {
        $login = $_POST['login'];
        $haslo = password_hash($_POST['haslo'], PASSWORD_DEFAULT);


        //zabezpieczamy przed SQLi - znaki specialne zostana zamienone na encje HTML
        $login = htmlentities($login, ENT_QUOTES, "UTF-8");
        // $haslo  = htmlentities($haslo, ENT_QUOTES, "UTF-8");

        //sprintf to funkcja która zmeinia po loleji zmienne %s na podane ponizej w kolejnosci takiej w jakiej sa zapiosane
        if (
            $rezultat = @$DBconnect->query(
                sprintf(
                    "SELECT * FROM uzytkownicy WHERE user='%s'",
                    mysqli_real_escape_string($DBconnect, $login) //,
                    //mysqli_real_escape_string($DBconnect, $haslo)
                )
            )
        ) {
            $ilu_userow = $rezultat->num_rows;
            if ($ilu_userow > 0) {
                $wiersz = $rezultat->fetch_assoc();
                if (password_verify($_POST['haslo'], $wiersz['pass'])) {
                    $_SESSION['zalogowany'] = true;
                    $_SESSION['id'] = $wiersz['id'];
                    $_SESSION['user'] = $wiersz['user'];
                    $_SESSION['drewno'] = $wiersz['drewno'];
                    $_SESSION['kamien'] = $wiersz['kamien'];
                    $_SESSION['zboze'] = $wiersz['zboze'];
                    $_SESSION['email'] = $wiersz['email'];
                    $_SESSION['dnipremium'] = $wiersz['dnipremium'];

                    unset($_SESSION['blad']);
                    $rezultat->free_result();
                    header('Location: gra.php');
                } else {
                    $_SESSION['blad'] = '<span style="color:red">Niepradwidłowy login lub hasło! </span>';
                    header('Location: index.php');
                    var_dump($haslo, $wiersz['pass']);
                }
            } else {
                $_SESSION['blad'] = '<span style="color:red">Niepradwidłowy login lub hasło! </span>';
                header('Location: index.php');
            }

            $DBconnect->close();
        }
    }
    } catch (Exception $e)  {
        echo "Nie udało się nawiązać połączenia z bazą danych";
    }  
?>