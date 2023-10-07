<?php
    // Prendo l'email della sessione attiva per definire chi è loggato
    $mail = $_SESSION['email'];

    // $multipleMails = $_SESSION['email_array'];

    // function typeOfMailSubmission(){
    //         echo "ciao";
    //         var_dump($_SESSION['reset_password']);
    //        // se la sessione reset password è true manda la mail di reset
    //        if ($_SESSION['reset_password']) {
    //         // Definisco le variabili to, subject, message, e headers per il reset della password
    //         echo "sondentro";
    //         $to      = $mail;
    //         $subject = 'Reset Password';
    //         $message = 'Ciao clicka il link per resettare la tua password <a href="http://localhost/Progetto-Edusogno/Edusogno_login/reset_pswd.php">Qui</a>' ;
    //         $headers = "MIME-Version: 1.0" . "\r\n";
    //         $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    //         $headers .= 'From: simcictilen@gmail.com' . "\r\n";
    //         $_SESSION['reset_password'] = false;
    //        }
    //        // se no manda mail add/modifica evento
    //        elseif ($_SESSION['creazione_evento']) {
    //         // Definisco le variabili to, subject, message, e headers per la creazione nuovo evento
    //         $to      = $mail;
    //         $subject = 'Creazione nuovo evento';
    //         $message = 'E stato creato un nuovo evento di cui fai parte!' ;
    //         $headers = "MIME-Version: 1.0" . "\r\n";
    //         $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    //         $headers .= 'From: simcictilen@gmail.com' . "\r\n";
    //         $_SESSION['creazione_evento'] = false;
    //        }
    //        elseif ($_SESSION['modifica_evento']){
    //         // Definisco le variabili to, subject, message, e headers per la modifica evento
    //         $to      = $mail;
    //         $subject = 'Modifica Evento';
    //         $message = "Un evento a cui fai parte e' stato modificato" ;
    //         $headers = "MIME-Version: 1.0" . "\r\n";
    //         $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    //         $headers .= 'From: simcictilen@gmail.com' . "\r\n";
    //         $_SESSION['modifica_evento'] = false;
    //        }
    // }
     
    

    // Funzione per mandare l'email del reset della password
    function sendResetEmail($to, $subject, $message, $headers) {
        
        mail($to, $subject, $message, $headers);
     
    
    }

    function sendEventEmail($to, $subject, $message, $headers) {
       
            //foreach che gestisce il caso in cui ci siano più soggeti a cui mandare mail
            foreach ($to as $recipient) {
                var_dump($recipient);
                mail($recipient, $subject, $message, $headers);
            }
  
    }
?>