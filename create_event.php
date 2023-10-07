<?php
    require('db.php');
    include('event_crud.php');
    require_once("send_mail.php");
    $eventController = new EventController('localhost', 'root', '', 'db-edusogno');
    $errors = array();

    // When form submitted, insert values into the database.
    if (isset($_POST['submit'])) {
        // // Validate nome
        if (empty($_POST['nome_evento'])) {
            $errors['title'] = "* Il titolo è richiesto.";
        } elseif (strlen($_POST['nome_evento']) < 3 || strlen($_POST['nome_evento']) > 25) {
            $errors['title'] = "* Il nome deve essere lungo tra 3 e 25 caratteri.";
        }

        // // Validate cognome
        // if (empty($_POST['cognome'])) {
        //     $errors['cognome'] = "* Il cognome è richiesto.";
        // } elseif (strlen($_POST['cognome']) < 3 || strlen($_POST['cognome']) > 50) {
        //     $errors['cognome'] = "* Il cognome deve essere lungo tra 3 e 50 caratteri.";
        // }

        // // Validate email
        // if (empty($_POST['email'])) {
        //     $errors['email'] = "* L'email è richiesta.";
        // } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        //     $errors['email'] = "* L'email non è valida.";
        // }

        // // Validate password
        // if (empty($_POST['password'])) {
        //     $errors['password'] = "* La password è richiesta.";
        // } elseif (strlen($_POST['password']) < 6 || strlen($_POST['password']) > 20) {
        //     $errors['password'] = "* La password deve essere lunga tra 6 e 20 caratteri.";
        // }
        // Validation code for attendees
        if (empty($_POST['attendees'])) {
            $errors['attendees'] = "* L'elenco dei partecipanti è richiesto.";
        } else {
            $attendeesArray = explode(',', $_POST['attendees']);
            var_dump($attendeesArray);
            foreach ($attendeesArray as $email) {
                $trimmedEmail = trim($email);
                $pattern = "/^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/";
                if (!preg_match($pattern, $trimmedEmail) || strlen($trimmedEmail) > 50) {
                    $errors['attendees'] = "* L'elenco dei partecipanti contiene un'email non valida o troppo lunga.";
                    break;
                }
            }
        }
        // If no validation errors, proceed with registration
        if (empty($errors)) {
            $title = mysqli_real_escape_string($con, $_POST['nome_evento']);
            $dataEvento = mysqli_real_escape_string($con, $_POST['data_evento']);
        
            $attendees = isset($_POST['attendees']) ? implode(', ', (array)$_POST['attendees']) : '';
           
            $description = mysqli_real_escape_string($con, $_POST['description']);
        
            $eventController->addEvent(new Event($title, $dataEvento, $attendees, $description));
            $to      = $attendeesArray;
            $subject = 'Creazione nuovo evento';
            $message = 'E stato creato un nuovo evento di cui fai parte!' ;
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: simcictilen@gmail.com' . "\r\n";
            // Chiama la funzione per inviare l'email
            try {
                
                sendEventEmail($to, $subject, $message, $headers);
                
            } catch (Exception $e) {
                echo "Errore nell'invio dell'email: " . $e->getMessage();
            }
        }
         // If registration is successful, set a flag
        //  $creationnSuccessful = ($stmt->affected_rows > 0);
    }
// Set values for valid fields or empty strings for fields with errors
$titleValue = (isset($_POST['title']) && !isset($errors['title'])) ? htmlspecialchars($_POST['title']) : '';
$dataEventoValue = (isset($_POST['data_evento']) && !isset($errors['data_evento'])) ? htmlspecialchars($_POST['data_evento']) : '';
$attendeesValue = (isset($_POST['attendees']) && !isset($errors['attendees'])) ? htmlspecialchars($_POST['attendees']) : '';
$descriptionValue = (isset($_POST['description']) && !isset($errors['description'])) ? htmlspecialchars($_POST['description']) : '';

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Registration</title>
    <link rel="stylesheet" href="style.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.17.9/tagify.min.js" integrity="sha512-E6nwMgRlXtH01Lbn4sgPAn+WoV2UoEBlpgg9Ghs/YYOmxNpnOAS49+14JMxIKxKSH3DqsAUi13vo/y1wo9S/1g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
<?php
    // Check if registration was successful
    // if (isset($creationnSuccessful) && $creationnSuccessful) {
    //     // Display success message
    //     echo "<div class='form'>
    //           <h3>Creazione evento avvenuta con successo.</h3><br/>
    //           <p class='link'>Clicca qui per <a href='login.php'>accedere</a>.</p>
    //           </div>";
    // } else {
        // Display the registration form
    
?>
<form class="form" action="" method="post">
        <h1 class="login-title">Crea un nuovo evento</h1>
        <div class="login-input-box">

            <label for="nome_evento">Inserisci il titolo</label>
            <input type="text" class="login-input" name="nome_evento" value="<?php echo $titleValue; ?>" required />
            <?php echo isset($errors['title']) ? "<span class='error'>" . $errors['title'] . "</span>" : ""; ?>
        </div>
        <div class="login-input-box">

            <label for="data_evento">Inserisci la data</label>
            <input type="datetime-local" class="login-input" name="data_evento" value="<?php echo $dataEventoValue; ?>" required />
            <?php if (isset($errors['data_evento'])) { echo "<span class='error'>" . $errors['data_evento'] . "</span>"; } ?>
        </div>

        <div class="login-input-box">
            
            <label for="attendees">Inserisci le mail dei partecipanti</label>
            <input type="text" class="login-input" name="attendees"  value="<?php echo $attendeesValue; ?>" required />
            <?php if (isset($errors['attendees'])) { echo "<span class='error'>" . $errors['attendees'] . "</span>"; } ?>
        </div>

        <div class="login-input-box">

            <label for="description">Inserisci la descrizione</label>
            <input type="text" class="login-input" name="description" value="<?php echo $descriptionValue; ?>" required />
            <?php if (isset($errors['description'])) { echo "<span class='error'>" . $errors['description'] . "</span>"; } ?>
        </div>

        <input type="submit" name="submit" value="CREA" class="login-button">
    </form>
    <?php
    // } // End of conditional statement
?>
</body>
</html>