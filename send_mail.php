<?php
    // Getting the $_SESSION email to identify who is logged in
    $mail = $_SESSION['email'];

    
    // Function used to send the pswd reset email
    function sendResetEmail($to, $subject, $message, $headers) {
        
        mail($to, $subject, $message, $headers);
     
    
    }
    // Function to send an email to notify users wherever an event that they are partecipating was modified or created
    function sendEventEmail($to, $subject, $message, $headers) {
       
            //foreach used in the case there are more than 1 partecipants to the same event
            foreach ($to as $recipient) {
                mail($recipient, $subject, $message, $headers);
            }
  
    }
?>