<?php

class Event {
    public $title;
    public $dataEvento;
    public $attendees;
    public $description;

    public function __construct($title, $dataEvento, $attendees, $description) {
        $this->title = $title;
        $this->dataEvento = $dataEvento;
        $this->attendees = $attendees;
        $this->description = $description;
    }
}

class EventController {
    private $conn;

    public function __construct($host, $username, $password, $database) {
        $this->conn = new mysqli($host, $username, $password, $database);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
    public function getEventById($id) {
        $id = $this->conn->real_escape_string($id);

        $sql = "SELECT * FROM eventi WHERE id = $id";
        $result = $this->conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return new Event($row['nome_evento'], $row['data_evento'], $row['attendees'], $row['description']);
        } else {
            // Return null if no event is found with the specified ID
            return null;
        }
    }
    public function addEvent(Event $event) {
        
        $title = $this->conn->real_escape_string($event->title);
        $dataEvento = $this->conn->real_escape_string($event->dataEvento);
        $attendees = $this->conn->real_escape_string($event->attendees);
        $description = $this->conn->real_escape_string($event->description);
        $sql = "INSERT INTO `eventi` (nome_evento, data_evento, attendees, description) VALUES ('$title', '$dataEvento', '$attendees', '$description')";
        $this->conn->query($sql);
        header('Location: admin_dashboard.php');
    }

    public function editEvent($id, Event $event) {
        $title = $this->conn->real_escape_string($event->title);
        $dataEvento = $this->conn->real_escape_string($event->dataEvento);
        $attendees = $this->conn->real_escape_string($event->attendees);
        $description = $this->conn->real_escape_string($event->description);
        $sql = "UPDATE `eventi` SET nome_evento='$title', data_evento='$dataEvento', attendees='$attendees', description='$description' WHERE id=$id";
        $this->conn->query($sql);
        header('Location: admin_dashboard.php');
    }

    public function deleteEvent($id) {
        $sql = "DELETE FROM `eventi` WHERE id=$id";
        $this->conn->query($sql);
        header('Location: admin_dashboard.php');
    }


    public function closeConnection() {
        $this->conn->close();
    }
}
?>
