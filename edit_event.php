<?php
    require_once('db.php');
    require_once __DIR__ . '/vendor/autoload.php';
    require_once('auth_session.php');
    require_once('event_crud.php');
    require_once('functions.php');
    require_once("send_mail.php");
    require_once('header.php');
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    $eventController = new EventController($_ENV['DB_HOST'], $_ENV['DB_USER'], '', $_ENV['DB_NAME']);
    // $eventController = new EventController('localhost', 'root', '', 'db-edusogno');
    if (!isAdmin()) {
        header('Location: logout.php');
    }
   

    $id = $_SESSION['event_id'];

    $eventById = $eventController->getEventById($id);
    
    $nomeEvento = $eventById->title;
    $dataEvento = $eventById->dataEvento;
    $attendees = $eventById->attendees;
    $description = $eventById->description;
    // $attendees = isset($_POST['attendees']) ? implode(', ', (array)$_POST['attendees']) : '';
    $errors = array();

    // When form submitted, insert values into the database.
    if (isset($_POST['submit'])) {
    
        // Validation code for attendees
        if (empty($_POST['attendees'])) {
            $errors['attendees'] = "* L'elenco dei partecipanti è richiesto.";
        } else {
            $attendeesArray = explode(',', $_POST['attendees']);
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
            
            $editedNomeEvento = $_POST['nome_evento'];
            $editedDataEvento = $_POST['data_evento'];
            $editedAttendees = $_POST['attendees'];
            $editedDescription = $_POST['description'];
            
            $eventController->editEvent($id, new Event($editedNomeEvento, $editedDataEvento, $editedAttendees, $editedDescription));

            $to      = $attendeesArray;
            $subject = 'Modifica Evento';
            $message = "Un evento a cui partecipi e' stato modificato" ;
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: ' . $_ENV['SENDER_EMAIL'] . "\r\n";
            $_SESSION['modifica_evento'] = false;
            // Chiama la funzione per inviare l'email
            try {
                
                sendEventEmail($to, $subject, $message, $headers);
                
            } catch (Exception $e) {
                echo "Errore nell'invio dell'email: " . $e->getMessage();
            }
        }
        $eventController->closeConnection();
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

<form class="form" action="" method="post">
        <h1 class="login-title">Modifica evento</h1>
        <div class="login-input-box">

            <label for="nome_evento">Inserisci il titolo</label>
            <input type="text" class="login-input" name="nome_evento" value="<?php echo $nomeEvento; ?>" required />
            <?php echo isset($errors['title']) ? "<span class='error'>" . $errors['title'] . "</span>" : ""; ?>
        </div>
        <div class="login-input-box">

            <label for="data_evento">Inserisci la data</label>
            <input type="datetime-local" class="login-input" name="data_evento" value="<?php echo $dataEvento; ?>" required />
            <?php if (isset($errors['data_evento'])) { echo "<span class='error'>" . $errors['data_evento'] . "</span>"; } ?>
        </div>

        <div class="login-input-box">
            
            <label for="attendees">Inserisci le mail dei partecipanti</label>
            <input type="text" class="login-input" name="attendees"  value="<?php echo $attendees; ?>" required />
            <?php if (isset($errors['attendees'])) { echo "<span class='error'>" . $errors['attendees'] . "</span>"; } ?>
        </div>

        <div class="login-input-box">

            <label for="description">Inserisci la descrizione</label>
            <input type="text" class="login-input" name="description" value="<?php echo $description; ?>" required />
            <?php if (isset($errors['description'])) { echo "<span class='error'>" . $errors['description'] . "</span>"; } ?>
        </div>

        <input type="submit" name="submit" value="Modifica" class="login-button">
    </form>
    <?php
    // } // End of conditional statement
?>


</body>
</html>