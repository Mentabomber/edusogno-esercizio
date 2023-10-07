<?php
function getEventi($mail) {


define("DB_SERVERNAME", "localhost");
define("DB_USERNAME","root");
define("DB_PASSWORD", "");
define("DB_NAME", "db-edusogno");


$conn = new mysqli(DB_SERVERNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);


if ($conn && $conn->connect_error) {
    
    echo "Connection failed: " . $conn->connect_error;

    return;
}

$currentPath = $_SERVER['REQUEST_URI'];

// Check if the current path is equal to a specific path
if ($currentPath == '/Progetto-Edusogno/Edusogno_login/admin_dashboard.php') {
    
    $sql = "SELECT nome_evento, data_evento, id FROM eventi";

    $stmt = $conn->prepare($sql);
    
} else {
    
    $sql = "SELECT nome_evento, data_evento FROM eventi WHERE attendees LIKE ?";

    $stmt = $conn->prepare($sql);

    $arg = '%' . $mail . '%';
    $stmt->bind_param("s", $arg);
}


$stmt -> execute();


$result = $stmt -> get_result();

if ($result && $result->num_rows > 0) {

    // If there are results, create an array to store them
    $eventi = [];
    
    // While there are results, add them to the array
    while($row = $result->fetch_assoc()) {
        
        $eventi[] = $row;
    }
    
    return $eventi;
}

$conn->close();

return [];
}

?>