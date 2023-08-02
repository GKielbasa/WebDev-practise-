<?php 
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (isset($_POST['email'])) {

    //walidacja poprawnego maila
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    if (empty($email)) {
        //echo $_POST['email'] . "<br>" . $email;
        $_SESSION['given_email'] = $_POST['email'];
        header('Location: index.php');
    }else {
        require_once 'database.php';
        //echo $_POST['email'] . "<br>" . $email;

        //czy emial jest juz w bazie ?
        $alreadySub = $db->prepare('SELECT * FROM users WHERE email = :email');
        $alreadySub->bindValue(':email', $email, PDO::PARAM_STR);
        $alreadySub->execute();
        if ($alreadySub->rowCount() == 0){
            //zapis do bazy
            $query = $db->prepare('INSERT INTO users VALUES (NULL, :email)');
            $query->bindValue(':email', $email, PDO::PARAM_STR);
            $query->execute();
            $alreadyOnTheList = 0;

            try {
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;

                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 465;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->SMTPAuth = true;
                $mail->Username = 'phpmailer981@gmail.com';
                $mail->Password = 'cprcmrsirrseuoid';

                $mail->CharSet = 'UTF-8';
                $mail->setFrom('no-reply@domain.pl', 'Ebooki uczące sztuki');
                $mail->addAddress($email);
                $mail->addReplyTo('biuro@domena.pl', 'Biuro');

                $mail->isHTML(true);
                $mail->Subject = 'Darmowy, świetny ebook - HTML na przykładach';
                $mail->Body = '<!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>wysyłanie e-mailw php</title>
                </head>
                <body>
                    <h1>Dzien dobry !</h1>
                    <p>oto link do naszego świetnego ebooka: <a href="https://domena.pl/book.pdf">POBIERZ EBOOKA</a></p>
                    <hr>
                    <p>Administratorem danych bla bla bla </p>
                    <p>Lorem, ipsum dolor sit amet consectetur !</p>
                    <p>Wypisz sie z subskrypcji</p>
                </body>
                </html>  ';
                //w mailerze mozna podpiac plik html czytaj manual
                $mail->addAttachment('img/html-ebook.jpg');

                $mail->send();

            } catch (Exception $e){
                echo "Błąd wysyłania maila: {$mail->ErrorInfo}";
            }

        } else {
            $alreadyOnTheList = 1;
        }
    }

} else {
    header('Location: index.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletter</title>
    <link rel="stylesheet" type ="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Newsletter</h1>
        </header>

        <main>
            <article>
                <?php 
                if($alreadyOnTheList){
                    echo "<p>Podany przez ciebie adres emial jest juz na naszej liscie subskrybentów</p>";
                } else {
                    echo "<p>Dziękujemy za zapisanie się na listę mailingową. Link do darmowego ebooka został wysłany na podany przez ciebie adres emial !</p>";
                }
                ?>
            </article>
        </main>
    </div>
</body>
</html>