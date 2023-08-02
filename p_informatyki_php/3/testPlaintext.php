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
                //$from = 'Ebooki uczące sztuki <no-reply@domena.pl>'; // niebedzie ogonków 
                $from = '=?UTF-8?B?'.base64_encode('Ebooki uczące sztuki').'?= <no-reply@domena.pl>' ;

                //$replyTo = 'Biuro <biuro@domena.pl>';
                $replyTo = '=?UTF-8?B?' . base64_encode('Biuro') . '?= <biuro@domena.pl>';

                $subject = 'Darmowy, świetny ebook - HTML na przykładach';
                $subject = '=?UTF-8?B?'.base64_encode('Darmowy, świetny ebook - HTML na przykładach').'?=';

                $message = base64_encode('<p>Dzień dobry! ' . "\r\n\r\n" . ' Oto link do naszego świetnego ebooka: <a href="https://domena.pl/ebook.pdf">POBIERZ EBOOKA</a></p>');
                
                $headers ='Content-Type: text/plain; charset=utf-8'."\r\n"; 
                $headers .='Content-Transfer-Encoding: base64'."\r\n";
                $headers .= 'FROM:' . $from . "\r\n";
                $headers .= 'Reply-To: ' . $replyTo . "\r\n";
                mail($to, $subject, $message, $headers);                  
                
                ?>
            </article>
        </main>
    </div>
</body>
</html>