<?php 
    session_start();

    if (!isset($_SESSION['zalogowany'])){
        header('Location: index.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Osadnicy</title>
</head>
<body>
    <?php 
        echo "<p>Witaj ".$_SESSION['user'].'! [<a href="logout.php">Wyloguj się</a>]</p>';
        echo " <p><b>Drewno</b>: ".$_SESSION['drewno'];
        echo "| <b>Kamień</b>: ".$_SESSION['kamien'];
        echo "| <b>Zboże</b>: ".$_SESSION['zboze']."</p>";

        echo "| <p><b>Email</b>: ".$_SESSION['email'];
        echo "<br /><b>Data wygaśnięcia premium</b>: ".$_SESSION['dnipremium']."</p>";
        $dataCzas = new DateTime('2023-07-20 16:00:00');
        echo "Data i czas serwera: ".$dataCzas->format('Y-m-d H:i:s')."<br>";
        $koniec = DateTime::createFromFormat('Y-m-d H:i:s', $_SESSION['dnipremium']);

        $roznica = $dataCzas->diff($koniec);
        if ($dataCzas<$koniec){
            echo "Pozostało premium: ".$roznica->format('%y lat, %m mies, %d dni, %h godzin, %i minut, %s sekund');
        } else {
            echo "Premium nieaktywne od:".$roznica->format('%y lat, %m mies, %d dni, %h godzin, %i minut, %s sekund');
        }




        // if ($_SESSION['dnipremium']){
        //     echo "<br /><b>Dni premium</b>: ".$_SESSION['dnipremium']."</p>";          
        // }else {
        //     echo "<br /><b>Dni premium</b>: 0 </p>";
        // }       

    ?>
</body>
</html>

<!-- Zmienne sesyjne są przechowywane na serwerze, a klient posiada tylko identyfikator sesji, tzw. PHPSESSID to ID jest zapisane w cookie lub przekazywane poprzez URL metodą GET - można podejrzeć wartość swojego PHPSESSID metodą echo session_id(); -->