<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once('auth_session.php');
require_once('functions.php');
require_once('get_eventi.php');
require_once('event_crud.php');
require_once('header.php');

var_dump($_SESSION['tipo_user']);
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$eventController = new EventController($_ENV['DB_HOST'], $_ENV['DB_USER'], '', $_ENV['DB_NAME']);

$events = getEventi($_SESSION['email']);
// controlla se l'utente è un admin se non lo è chiude sessione è lo manda alla login page
if (!isAdmin()) {
    header('Location: logout.php');
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
    <main>
        <div class="container">
            <h1 class="dashboard-title">Admin Dashboard</h1>
            <h2 class="dashboard-title">Gestisci Eventi</h2>
            <div id="events-container">
            <?php 
            // messaggio ricevuto se non ci sono eventi disponibili
            if (count($events) < 1) { ?>
                <h3 class="dashboard-title">Non ci sono eventi disponibili al momento.</h3>
            <?php } 
            else { ?>
                <?php
                     
                    foreach ($events as $event) { ?>
                        <div class='event-card' value="<?php echo $event['id']; ?>">
                            <h3><?php echo $event['nome_evento']; ?></h3><br/>
                            <span><?php echo $event['data_evento']; ?></span><br/>
                            <div class="buttons-container">
                                <form method="post">
                                    <button class="crud-button" type="submit" name="edit_event" value="<?php echo $event['id']; ?>">Modifica</button>
                                </form>
                                <form method="post" onsubmit="return confirm('Are you sure you want to submit this form?');">
                                    <button class="crud-button delete" type="submit" name="delete_event" value="<?php echo $event['id']; ?>">Elimina</button>
                                </form>
                            </div>

                        </div>
            <?php   } ?>
    <?php   } ?>
            </div>
            <br/>
            <div id="creation_container">
                <a id="creation_button" href="create_event.php">Crea Nuovo Evento</a>
                <br/>
                <a class="logout-interaction" href="logout.php">Logout</a>
            </div>
        </div>
    </main>
    
</body>
</html>