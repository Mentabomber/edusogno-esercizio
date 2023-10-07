<?php
    require('db.php');
    include("auth_session.php");
    
?>

<?php
    
    
    // When form submitted, check and create user session.
    if (isset($_POST['submit'])) {
      
        $mail = $_SESSION['email'];
        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($con, $password);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query    = "UPDATE `utenti` SET password = ? WHERE email LIKE ?";

        $stmt = $con->prepare($query);
        $arg = '%' . $mail . '%';
        $stmt->bind_param("ss", $hashedPassword, $arg);
        
        // Execute the query
        $stmt -> execute();

        if ($stmt->affected_rows > 0) {
            echo "<div class='form'>
                  <h3>Password cambiata con successo.</h3><br/>
                  <p class='link'>Click here to <a href='login.php'>Login</a></p>
                  </div>";
        } else {
            echo "<div class='form'>
                  <h3>Required fields are missing.</h3><br/>
                  <p class='link'>Click here to <a href='registration.php'>registration</a> again.</p>
                  </div>";
        }
    } else {
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Cambio Password <?php echo var_dump($_SESSION['email']); echo $_SESSION['email'] ?></title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
    <form class="form" method="post" name="change_pswd">
        <h1 class="login-title">Cambio Password</h1>
        <input type="password" class="login-input" name="password" placeholder="Password"/>
        <input type="submit" value="Cambia Password" name="submit" class="login-button"/>
    </form>
<?php
    }
?>
</body>
</html>