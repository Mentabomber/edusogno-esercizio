<?php
include('auth_session.php');
include('functions.php');
include('get_eventi.php');
include('event_crud.php');
var_dump($_SESSION['event_id']);
$eventController = new EventController('localhost', 'root', '', 'db-edusogno');

$events = getEventi($_SESSION['email']);

if (!isAdmin()) {
    session_destroy();
    header('location: login.php');
}
if (isset($_POST['edit_event'])) {
    // Chiama la funzione per modificare l'evento
        try {
            $_SESSION['event_id'] = intval($_POST['edit_event']);
            header('Location: edit_event.php');
            
        } catch (Exception $e) {
            echo "Errore nel caricamento dell'edit: " . $e->getMessage();
        }
}
if (isset($_POST['delete_event'])) {
    // Chiama la funzione per eliminare l'evento
        try {
            $_SESSION['event_id'] = intval($_POST['delete_event']);
            $eventController->deleteEvent($_SESSION['event_id']);
            // header('Location: edit_event.php');
            
        } catch (Exception $e) {
            echo "Errore nel caricamento dell'edit: " . $e->getMessage();
        }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="form">
        <h1>Admin Dashboard</h1>
        <h2>Gestisci Eventi</h2>
        <?php 
        // messaggio ricevuto se non ci sono eventi disponibili
        if (count($events) < 1) { ?>
            <h3>Non ci sono eventi disponibili al momento.</h3>
        <?php } 
        else { ?>
               <?php  
                foreach ($events as $event) { ?>
                    <div class='card-evento' value="<?php echo $event['id']; ?>">
                        <h3><?php echo $event['nome_evento']; ?></h3><br/>
                        <span><?php echo $event['data_evento']; ?></span><br/>
                        <span><?php echo $event['id']; ?></span><br/>
                        <form method="post">
                            <p><button type="submit" name="edit_event" value="<?php echo $event['id']; ?>">Modifica</button></p>
                            <p><button type="submit" name="delete_event" value="<?php echo $event['id']; ?>">Elimina</button></p>
                        </form>
                    </div>
        <?php   } ?>
<?php   } ?>
        <br/>
        <p><a href="create_event.php">Crea Nuovo Evento</a></p>
        <br/>
        <p><a href="logout.php">Logout</a></p>
    </div>
</body>
</html>