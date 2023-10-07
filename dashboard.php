<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard - Client area</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://fonts.cdnfonts.com/css/dm-sans" rel="stylesheet">
</head>
<body><?php
    //include auth_session.php file on all user panel pages
    require_once('header.php');
    include("auth_session.php");
    // chiedo i dati degli eventi al database
    require_once("get_eventi.php");
    require_once("send_mail.php");
    $events = getEventi($mail);
    // Controllo se l'email è stata mandata tramite $_SESSION['mail_sent'] e invio di messaggio di successo
    if(isset($_SESSION['mail_sent'])){
        echo("<script> alert('Email inviata con successo.')</script>");
        // echo "L'e-mail è stata mandata con successo!";
        //reset della sessione mail_sent cosi da non rivedere il messaggio al reload della pagina 
        $_SESSION['mail_sent'] = Null;
    }

    // Se è stato fatto un clic sul pulsante di reset password
    if (isset($_POST['reset_password'])) {
        $to      = $mail;
        $subject = 'Reset Password';
        $message = 'Ciao clicka il link per resettare la tua password <a href="http://localhost/Progetto-Edusogno/Edusogno_login/reset_pswd.php">Qui</a>' ;
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: simcictilen@gmail.com' . "\r\n";
        // Chiama la funzione per inviare l'email
            try {
                echo "ciao";
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
            <h1 class="dashboard-title">Ciao <?php echo $_SESSION['nome']['nome'] . " " . $_SESSION['cognome']['cognome']?> ecco i tuoi eventi</h1>
            <div id="events-container">
                <?php 
                // messaggio ricevuto se non ci sono eventi disponibili
                if (count($events) < 1) { ?>
                    <h3>Non ci sono eventi disponibili al momento.</h3>
                <?php } 
                else { ?>
                    <?php  
                        foreach ($events as $event) { ?>
                            <div class="event-card">
                                <h3><?php echo $event['nome_evento']; ?></h3><br/>
                                <span><?php echo $event['data_evento']; ?></span><br/>
                                <button class="btn">JOIN</button>
                            </div>
                        <?php   } ?>
                <?php   } ?>
            </div>
            <br/>
            <div id="reset_container">
                <form id="reset_form" method="post">
                    <p><button id="reset_button" class="btn" type="submit" name="reset_password">Resetta password</button></p>
                </form>
                <p><a class="logout-interaction" href="logout.php">Logout</a></p>
            </div>

        </div>
    </main>
</body>
</html>