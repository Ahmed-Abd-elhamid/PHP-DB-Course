<?php // Do not put any HTML above this line
    session_start();

if ( isset($_POST['cancel'] ) ) {
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // php123

$failure = false;  // If we have no POST data

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
        $_SESSION['error'] = "User name and password are required";
        header("Location: login.php");
        return;
    } else {
        $check = hash('md5', $salt.htmlentities($_POST['pass']));
        // echo $check;
        if ( $check == $stored_hash ) { 
            if (filter_var(htmlentities($_POST['email']), FILTER_VALIDATE_EMAIL)){
                error_log("Login success ".$_POST['email']);
                $_SESSION['name'] = htmlentities($_POST['email']);
                header("Location: index.php");
                return;
            }else{
                error_log("Login fail ".$_POST['email']." $check");
                $_SESSION['error'] = "Email must have an at-sign (@)";
                header("Location: login.php");
                return;
            }
        } else {
            $_SESSION['error'] = "Incorrect password";
            header("Location: login.php");
            return;
        }
    }
}

// Fall through into the View
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Ahmed Abdelhamid - 48c1462e</title>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php
if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
?>
<form method="POST">
User Name <input type="text" name="email"><br/>
Password <input type="text" name="pass"><br/>
<input type="submit" value="Log In">
<a href="index.php">Cancel</a>
</form>
<p>
For a password hint, view source and find a password hint
in the HTML comments.
</p>
</div>
</body>