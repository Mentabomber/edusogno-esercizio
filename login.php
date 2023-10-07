<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Login</title>
    <link rel="stylesheet" href="style.css"/>
    <link href="https://fonts.cdnfonts.com/css/dm-sans" rel="stylesheet">
</head>
<body>
<?php
    
    require_once('db.php');
    require_once('header.php');
    session_start();

    if(isset($_SESSION['email']))
    {
        header('Location: dashboard.php');
    } 
    // When form submitted, check and create user session.
    if (isset($_POST['email'])) {
        $email = stripslashes($_REQUEST['email']);    // removes backslashes
        $email = mysqli_real_escape_string($con, $email);
        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($con, $password);
        
        // Check user is exist in the database
        $query = "SELECT * FROM `utenti` WHERE email='$email'";
        $result = mysqli_query($con, $query) or die(mysql_error());
        $rows = mysqli_num_rows($result);

        if ($rows == 1) {
            // Fetch the stored hashed password
            $userData = mysqli_fetch_assoc($result);
            $hashedPassword = $userData['password'];

            // Verify the password
            if (password_verify($password, $hashedPassword)) {
                // Password is correct
                // Fetch additional user details
                $queryNome = "SELECT nome FROM `utenti` WHERE email='$email'";
                $queryCognome = "SELECT cognome FROM `utenti` WHERE email='$email'";
                $queryTipoUSer ="SELECT tipo_user FROM `utenti` WHERE email='$email'";

                $risultatoNome = mysqli_query($con, $queryNome) or die(mysql_error());
                $nome = mysqli_fetch_assoc($risultatoNome);

                $risultatoCognome = mysqli_query($con, $queryCognome) or die(mysql_error());
                $cognome = mysqli_fetch_assoc($risultatoCognome);

                $risultatoTipoUser = mysqli_query($con, $queryTipoUSer) or die(mysql_error());
                $tipoUser = mysqli_fetch_assoc($risultatoTipoUser);

                // Set session variables
                $_SESSION['email'] = $email;
                $_SESSION['nome'] = $nome;
                $_SESSION['cognome'] = $cognome;
                $_SESSION['tipo_user'] = $tipoUser;
    
                if ($_SESSION['tipo_user']['tipo_user'] == "admin") {
                    header("Location: admin_dashboard.php");
                }
                else{
                    header("Location: dashboard.php");
                }
               

            } else {
                // la Password non è corretta
                echo "<div class='success-form'>
                      <h3 class='registration-title'>Email o password errata.</h3><br/>
                      <p class='link'>Clicka qui per provare ad <a href='login.php'>Autenticarti</a> di nuovo.</p>
                      </div>";
            }
        } else {
            // l'utente non esiste
            echo "<div class='success-form'>
                  <h3 class='registration-title'>Email o password errata.</h3><br/>
                  <p class='link'Clicka qui per provare dio <a href='login.php'>Autenticarti</a> di nuovo.</p>
                  </div>";
        }
    } else {
?>
<main> 
    <div class="container">
        <h1 class="login-title">Hai già un'account?</h1>
        <form class="login-form" method="post" name="login">
            <div class="input-box">
                <label class="label" for="email">Inserisci l'e-mail</label>
                <input type="text" class="login-input" name="email" placeholder="name@example.com" autofocus="true"/>
            </div>
            <div class="input-box">
                <label class="label" for="password">Inserisci la password</label>
                
                <div class="password-box">
                    <input id="password" type="password" class="login-input" name="password" placeholder="Scrivila qui">
                    <svg id="togglePassword" xmlns="http://www.w3.org/2000/svg" width="25" height="17" viewBox="0 0 25 17" fill="none">
                    <path d="M24.8489 7.69965C22.4952 3.1072 17.8355 0 12.5 0C7.16447 0 2.50345 3.10938 0.151018 7.70009C0.0517306 7.89649 0 8.11348 0 8.33355C0 8.55362 0.0517306 8.77061 0.151018 8.96701C2.50475 13.5595 7.16447 16.6667 12.5 16.6667C17.8355 16.6667 22.4965 13.5573 24.8489 8.96658C24.9482 8.77018 25 8.55319 25 8.33312C25 8.11304 24.9482 7.89605 24.8489 7.69965ZM12.5 14.5833C11.2638 14.5833 10.0555 14.2168 9.02766 13.53C7.99985 12.8433 7.19878 11.8671 6.72573 10.7251C6.25268 9.58307 6.12891 8.3264 6.37007 7.11402C6.61123 5.90164 7.20648 4.78799 8.08056 3.91392C8.95464 3.03984 10.0683 2.44458 11.2807 2.20343C12.493 1.96227 13.7497 2.08604 14.8917 2.55909C16.0338 3.03213 17.0099 3.83321 17.6967 4.86102C18.3834 5.88883 18.75 7.0972 18.75 8.33333C18.7504 9.15421 18.589 9.96711 18.275 10.7256C17.9611 11.484 17.5007 12.1732 16.9203 12.7536C16.3398 13.3341 15.6507 13.7944 14.8922 14.1084C14.1338 14.4223 13.3208 14.5837 12.5 14.5833ZM12.5 4.16667C12.1281 4.17186 11.7586 4.22719 11.4015 4.33116C11.6958 4.73119 11.8371 5.22347 11.7996 5.71873C11.7621 6.21398 11.5484 6.6794 11.1972 7.0306C10.846 7.38179 10.3806 7.5955 9.88537 7.63297C9.39012 7.67043 8.89784 7.52917 8.49781 7.23481C8.27001 8.07404 8.31113 8.96357 8.61538 9.77821C8.91962 10.5928 9.47167 11.2916 10.1938 11.776C10.916 12.2605 11.7719 12.5063 12.641 12.4788C13.5102 12.4514 14.3489 12.152 15.039 11.623C15.7291 11.0939 16.236 10.3617 16.4882 9.52951C16.7404 8.69729 16.7253 7.80693 16.445 6.98376C16.1647 6.16058 15.6333 5.44602 14.9256 4.94067C14.2179 4.43532 13.3696 4.16462 12.5 4.16667Z" fill="#0057FF"/>
                </svg>
                </div>
            
            
            </div>
    
            <div class="input-button-box">
                <input type="submit" value="Login" name="submit" class="login-button"/>
                <p class="link">Non hai ancora un profilo? <a href="registration.php">Registrati</a></p>
            </div>

        </form>
    </div>
</main>
<?php
    }
?>
    <script>
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#password");

        togglePassword.addEventListener("click", function () {
            // toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            

        });

        
    </script>
</body>
</html>


