<?php
    // Prendo l'email della sessione attiva per definire chi è loggato
    $mail = $_SESSION['email'];

    
    // Funzione per mandare l'email del reset della password
    function sendResetEmail($to, $subject, $message, $headers) {
        
        mail($to, $subject, $message, $headers);
     
    
    }

    function sendEventEmail($to, $subject, $message, $headers) {
       
            //foreach che gestisce il caso in cui ci siano più soggeti a cui mandare mail
            foreach ($to as $recipient) {
                mail($recipient, $subject, $message, $headers);
            }
  
    }
?>