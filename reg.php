<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Registration</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
<?php
    require('db.php');
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

            $query = "INSERT INTO `utenti` (email, password, nome, cognome) VALUES (?, ?, ?, ?)";
            $stmt = $con->prepare($query);
            $stmt->bind_param("ssss", $email, $hashedPassword, $nome, $cognome);

            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "<div class='form'>
                      <h3>Registrazione avvenuta con successo.</h3><br/>
                      <p class='link'>Clicca qui per <a href='login.php'>accedere</a>.</p>
                      </div>";
            } else {
                echo "<div class='form'>
                      <h3>Registrazione fallita.</h3><br/>
                      <p class='link'>Clicca qui per riprovare a <a href='registration.php'>registrarti</a> di nuovo.</p>
                      </div>";
            }
        }
    } else {
?>
    <form class="form" action="" method="post">
    <h1 class="login-title">Crea il tuo account</h1>

    <label for="nome">Inserisci il nome</label>
    <input type="text" class="login-input" name="nome" placeholder="Mario" required />
    <?php echo isset($errors['nome']) ? "<span class='error'>" . $errors['nome'] . "</span>" : ""; ?>

    <label for="cognome">Inserisci il cognome</label>
    <input type="text" class="login-input" name="cognome" placeholder="Rossi" required />
    <?php echo isset($errors['cognome']) ? "<span class='error'>" . $errors['cognome'] . "</span>" : ""; ?>

    <label for="email">Inserisci l'email</label>
    <input type="text" class="login-input" name="email" placeholder="name@example.com" required />
    <?php echo isset($errors['email']) ? "<span class='error'>" . $errors['email'] . "</span>" : ""; ?>

    <label for="password">Inserisci la password</label>
    <input type="password" class="login-input" name="password" placeholder="Scrivila qui" required />
    <?php echo isset($errors['password']) ? "<span class='error'>" . $errors['password'] . "</span>" : ""; ?>

    <input type="submit" name="submit" value="REGISTRATI" class="login-button">
    <p class="link"><a href="login.php">Hai già un account? Accedi</a></p>
</form>

<?php
    }
?>
</body>
</html>
