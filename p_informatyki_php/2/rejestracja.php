<?php
    session_start();
    if(isset($_POST['email']))
    {
        //udana walidacja? tak ! jeżeli na koncu flaga nadal bedzie maial true to jest ok
        $wszystko_OK = true;

        //walidacja nickname
        $nick = $_POST['nick'];
        if (strlen($nick)<3 || strlen($nick)>20){
            $wszystko_OK = false;
            $_SESSION['e_nick'] = "Nick musi posiadać od 3 do 20 znaków !";
        }
        if (ctype_alnum($nick)==false){
            $wszystko_OK = false;
            $_SESSION['e_nick'] = "Nick może składać się tylko z liter i cyfr (bez polskich znaków)";
        }

        //walidacja email wspolcześnie mail walidowany jest w formie inputu mail
        //ale zrobie bo czemu nie pomimo że sie nie wykona tak czy inaczej 
        $email = $_POST['email'];
        $emailBezpieczny = filter_var($email, FILTER_SANITIZE_EMAIL); //sanityzacja emaila
        if ((filter_var($emailBezpieczny, FILTER_VALIDATE_EMAIL)==false) || ($emailBezpieczny!=$email)){
            $wszystko_OK = false;
            $_SESSION['e_email'] = "Podaj poprawny adres email";
        }        

        //walidacja hasla
        $haslo1 = $_POST['haslo1'];
        $haslo2 = $_POST['haslo2'];

        if(strlen($haslo1)<8 || (strlen($haslo1)>20)){
            $wszystko_OK = false;
            $_SESSION['e_haslo'] = "Hasło musi posiadać od 8 do 20 znaków !";
        }
        if($haslo1 != $haslo2){
            $wszystko_OK = false;
            $_SESSION['e_haslo'] = "Podane hasła nie są identyczne !";
        }

        //hashowanie hasla
        $haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);
        
        //czy zaakceptowano regulamin
        if(!isset($_POST['regulamin'])){
            $wszystko_OK = false;
            $_SESSION['e_regulamin'] = "Potwierdź akceptację regulaminu !";
        }

        //bot or not
        $secret = "6LfRQ24mAAAAAGUI5kK6D-E4c35FHAdqo3HJS03X";
        $sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        $odpowiedz = json_decode($sprawdz);
        if ($odpowiedz->success==false){
            $wszystko_OK = false;
            $_SESSION['e_bot'] = "Potwierdz że nie jesteś robotem !";
        }
        //zapamietaj wprowadzone dane 
        $_SESSION['fr_nick'] = $nick; // fr=formulaz rejestracji
        $_SESSION['fr_email'] = $email;
        $_SESSION['fr_haslo1'] = $haslo1;
        $_SESSION['fr_haslo2'] = $haslo2;
        if(isset($_POST['regulamin'])) $_SESSION['fr_regulamin'] = true;




        //bedziemy zapisywac dane do bazy danych
        require_once "connect.php";
        mysqli_report(MYSQLI_REPORT_STRICT);
        try {
            $DBconnect = new mysqli($host, $db_user, $db_password, $db_name);
            if ($DBconnect->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
            } else {
                //czy email juz istnieje
                $rezultat = $DBconnect->query("SELECT id FROM uzytkownicy WHERE email='$email'");
                if (!$rezultat) throw new Exception($DBconnect->error);
                $ile_takich_maili = $rezultat->num_rows;
                if($ile_takich_maili>0){
                    $wszystko_OK = false;
                    $_SESSION['e_email'] = "Istnieje już konto przypisane do tego adresu email !";
                }

                //czy login juz istnieje
                $rezultat = $DBconnect->query("SELECT id FROM uzytkownicy WHERE user='$nick'");
                if (!$rezultat) throw new Exception($DBconnect->error);
                $ile_takich_nikow = $rezultat->num_rows;
                if($ile_takich_nikow>0){
                    $wszystko_OK = false;
                    $_SESSION['e_nick'] = "Podany login jest już zajęty. Wybież inny nick !";
                }

                if($wszystko_OK == true){
                    //testy zaliczone mozna dodac do bazy 
                    if ($DBconnect-> query("INSERT INTO uzytkownicy VALUES (NULL, '$nick', '$haslo_hash', '$email', 100, 100, 100, now() + INTERVAL 14 DAY)")){
                        $_SESSION['udanarejestracja'] = true;
                        header('Location: witamy.php'); // niema jeszcze
                    }else {
                        throw new Exception($DBconnect->error);
                    }

                
                }
                $DBconnect->close();
            }
        } catch(Exception $e) {
            echo '<span style="color:red;">Błąd serwera ! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie !</span>';
            //echo '<br />Informacja developerska: '.$e;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Osadonicy - załóż darmowe konto</title>
    <style>
        .error{
            color:red;
            margin-top:10px;
            margin-bottom:10px;
        }
    </style>
</head>
<body>
    
    <form method="post"> <!--bez podania action="lokalizacja" skrypt bedzie prztwarzanyw aktualnym dokumencie  -->
        Nickname: <br /> <input type="text" name="nick" placeholder="nickname" value="<?php 
        if (isset($_SESSION['fr_nick'])){
            echo $_SESSION['fr_nick'];
            unset($_SESSION['fr_nick']);
        }?>" /> <br/>
        <?php 
            if (isset($_SESSION['e_nick'])){
                echo '<div class="error">'.$_SESSION['e_nick'].'</div>';
                unset($_SESSION['e_nick']);
            }
        ?>

        Adres email: <br/> <input type="email" name="email"placeholder="email@email.com" value="<?php 
        if (isset($_SESSION['fr_email'])){
            echo $_SESSION['fr_email'];
            unset($_SESSION['fr_email']);
        }?>" /><br/>
        <?php 
            if (isset($_SESSION['e_email'])){
                echo '<div class="error">'.$_SESSION['e_email'].'</div>';
                unset($_SESSION['e_email']);
            }
        ?>

        Hasło: <br/> <input type="password" name="haslo1" value="<?php 
        if (isset($_SESSION['fr_haslo1'])){
            echo $_SESSION['fr_haslo1'];
            unset($_SESSION['fr_haslo1']);
        }?>"  /><br/>
        <?php 
            if (isset($_SESSION['e_haslo'])){
                echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
                unset($_SESSION['e_haslo']);
            }
        ?>

        Potwierdz hasło: <br/> <input type="password" name="haslo2" value="<?php 
        if (isset($_SESSION['fr_haslo2'])){
            echo $_SESSION['fr_haslo2'];
            unset($_SESSION['fr_haslo2']);
        }?>" /><br/>
        <label>
            <input type="checkbox" name="regulamin" <?php 
                if(isset($_SESSION['fr_regulamin'])){
                    echo "checked";
                    unset($_SESSION['fr_regulamin']);
                }
            ?> />Akceptuje regulamin<br/>
        </label>
        <?php 
            if (isset($_SESSION['e_regulamin'])){
                echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
                unset($_SESSION['e_regulamin']);
            }
        ?>
        <form action="?" method="POST">
        <div class="g-recaptcha" data-sitekey="6LfRQ24mAAAAAH9K20xgqn_I-FiX5Rb1CZ1V3gU5"></div><br/>
        <?php 
            if (isset($_SESSION['e_bot'])){
                echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
                unset($_SESSION['e_bot']);
            }
        ?>

        <input type="submit" value="Zarejestruj się">
    </form>

</body>
</html>