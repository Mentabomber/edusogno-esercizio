<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard - Client area</title>
    <link rel="stylesheet" href="assets/styles/style.css" />
    <link href="https://fonts.cdnfonts.com/css/dm-sans" rel="stylesheet">
</head>
<body>
    <?php
    require_once __DIR__ . '/vendor/autoload.php';
    require_once('header.php');
    require_once("auth_session.php");
    require_once("get_eventi.php");
    require_once("send_mail.php");
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    $events = getEventi($mail);
    // Controllo se l'email è stata mandata tramite $_SESSION['mail_sent'] e invio di messaggio di successo
    if(isset($_SESSION['mail_sent'])){
        echo("<script> alert('Email inviata con successo.')</script>");
        //reset della sessione mail_sent cosi da non rivedere il messaggio al reload della pagina 
        $_SESSION['mail_sent'] = Null;
    }

    // Se è stato fatto un clic sul pulsante di reset password
    if (isset($_POST['reset_password'])) {
        $_SESSION['password_changed'] = false;
        $to      = $mail;
        $subject = 'Reset Password';
        $message = 'Ciao clicka il link per resettare la tua password <a href="http://localhost/Progetto-Edusogno/Edusogno_login/reset_pswd.php">Qui</a>' ;
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: ' . $_ENV['SENDER_EMAIL'] . "\r\n";
        // Chiama la funzione per inviare l'email
            try {
                sendResetEmail($to, $subject, $message, $headers);
                // do un valore a $_SESSION['mail_sent'] cosi da poter poi mandare un messaggio di mandata mail su schermo
                $_SESSION['mail_sent']='Y';
                // uso header per farsì che nel reload della pagina non venga rimandata un'email tramite la funzione sendResetEmail
                header('Location: '.$_SERVER['REQUEST_URI']);
            } catch (Exception $e) {
                echo "Errore nell'invio dell'email: " . $e->getMessage();
            }

    }
?>
    <main>
        <div class="container">
            <div id="reset_container">
                    <form id="reset_form" method="post">
                        <button id="reset_button" class="btn" type="submit" name="reset_password">Resetta password</button>
                    </form>
                <a class="logout-interaction" href="logout.php">Logout</a>
            </div>
            <h1 class="dashboard-title">Ciao <?php echo $_SESSION['nome']['nome'] . " " . $_SESSION['cognome']['cognome']?> ecco i tuoi eventi</h1>
            <div id="events-container">
                <?php 
                if (count($events) < 1) { ?>
                    <h3 id="no-events-title">Non ci sono eventi disponibili al momento.</h3>
                <?php } 
                else { ?>
                    <?php  
                        foreach ($events as $event) { ?>
                            <div class="event-card">
                                <h3><?php echo $event['nome_evento']; ?></h3><br/>
                                <div class="bottom-card-container">
                                    <span><?php echo $event['data_evento']; ?></span><br/>
                                    <button class="btn">JOIN</button>
                                </div>
                            </div>
                        <?php   } ?>
                <?php   } ?>
            </div>
        </div>
    </main>
</body>
</html>