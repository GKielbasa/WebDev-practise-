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
            <h1>Test wysyłania e-maila </h1>
        </header>
        <main>
            <article>
                <?php 
                
                $to ='jakismail@mail.com';
                $from = 'Ebooki uczące sztuki <no-reply@domena.pl>'; 
                $replyTo = 'Biuro <biuro@domena.pl>';
                $subject = 'Darmowy, świetny ebook - HTML na przykładach';
            
                $message ='
                <!DOCTYPE html>
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
                </html>               
                ';
                
                $headers ='MIME-Version: 1.0'."\r\n"; 
                $headers .='Content-Type: text/html; charset=utf-8'."\r\n";
                $headers .= 'FROM:' . $from . "\r\n";
                $headers .= 'Reply-To: ' . $replyTo . "\r\n";
                mail($to, $subject, $message, $headers);                  
                
                ?>
            </article>
        </main>
    </div>
</body>
</html>