<?php
    require('db.php');
    require_once('header.php');
    $errors = array();

    // When form submitted, insert values into the database.
    if (isset($_POST['submit'])) {
        // Validate nome
        if (empty($_POST['nome'])) {
            $errors['nome'] = "* Il nome è richiesto.";
        } elseif (strlen($_POST['nome']) < 3 || strlen($_POST['nome']) > 50) {
            $errors['nome'] = "* Il nome deve essere lungo tra 3 e 50 caratteri.";
        }

        // Validate cognome
        if (empty($_POST['cognome'])) {
            $errors['cognome'] = "* Il cognome è richiesto.";
        } elseif (strlen($_POST['cognome']) < 3 || strlen($_POST['cognome']) > 50) {
            $errors['cognome'] = "* Il cognome deve essere lungo tra 3 e 50 caratteri.";
        }

        // Validate email
        if (empty($_POST['email'])) {
            $errors['email'] = "* L'email è richiesta.";
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "* L'email non è valida.";
        }

        // Validate password
        if (empty($_POST['password'])) {
            $errors['password'] = "* La password è richiesta.";
        } elseif (strlen($_POST['password']) < 6 || strlen($_POST['password']) > 20) {
            $errors['password'] = "* La password deve essere lunga tra 6 e 20 caratteri.";
        }

        // If no validation errors, proceed with registration
        if (empty($errors)) {
            $nome = mysqli_real_escape_string($con, $_POST['nome']);
            $cognome = mysqli_real_escape_string($con, $_POST['cognome']);
            $email = mysqli_real_escape_string($con, $_POST['email']);
            $password = mysqli_real_escape_string($con, $_POST['password']);
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $user_type = "user";

            $query = "INSERT INTO `utenti` (email, password, nome, cognome, tipo_user) VALUES (?, ?, ?, ?, ?)";
            $stmt = $con->prepare($query);
            $stmt->bind_param("sssss", $email, $hashedPassword, $nome, $cognome, $user_type);

            $stmt->execute();

        }
         // If registration is successful, set a flag
         $registrationSuccessful = ($stmt->affected_rows > 0);
    }
// Set values for valid fields or empty strings for fields with errors
$nomeValue = (isset($_POST['nome']) && !isset($errors['nome'])) ? htmlspecialchars($_POST['nome']) : '';
$cognomeValue = (isset($_POST['cognome']) && !isset($errors['cognome'])) ? htmlspecialchars($_POST['cognome']) : '';
$emailValue = (isset($_POST['email']) && !isset($errors['email'])) ? htmlspecialchars($_POST['email']) : '';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Registration</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
<?php
    // Check if registration was successful
    if (isset($registrationSuccessful) && $registrationSuccessful) {
        // Display success message
        echo "<div class='form'>
              <h3>Registrazione avvenuta con successo.</h3><br/>
              <p class='link'>Clicca qui per <a href='login.php'>accedere</a>.</p>
              </div>";
    } else {
        // Display the registration form
    
?>
<form class="form" action="" method="post">
        <h1 class="login-title">Crea il tuo account</h1>
        <div class="login-input-box">

            <label for="nome">Inserisci il nome</label>
            <input type="text" class="login-input" name="nome" placeholder="Mario" value="<?php echo $nomeValue; ?>" required />
            <?php echo isset($errors['nome']) ? "<span class='error'>" . $errors['nome'] . "</span>" : ""; ?>
        </div>
        <div class="login-input-box">

            <label for="cognome">Inserisci il cognome</label>
            <input type="text" class="login-input" name="cognome" placeholder="Rossi" value="<?php echo $cognomeValue; ?>" required />
            <?php if (isset($errors['cognome'])) { echo "<span class='error'>" . $errors['cognome'] . "</span>"; } ?>
        </div>

        <div class="login-input-box">
            
            <label for="email">Inserisci l'email</label>
            <input type="text" class="login-input" name="email" placeholder="name@example.com" value="<?php echo $emailValue; ?>" required />
            <?php if (isset($errors['email'])) { echo "<span class='error'>" . $errors['email'] . "</span>"; } ?>
        </div>

        <div class="login-input-box">

            <label for="password">Inserisci la password</label>
            <input type="password" class="login-input" name="password" placeholder="Scrivila qui" required />
            <?php if (isset($errors['password'])) { echo "<span class='error'>" . $errors['password'] . "</span>"; } ?>
        </div>

        <input type="submit" name="submit" value="REGISTRATI" class="login-button">
        <p class="link"><a href="login.php">Hai già un account? Accedi</a></p>
    </form>
    <?php
    } // End of conditional statement
?>
</body>
</html>