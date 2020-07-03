<?php
    session_start();
    if (isset($_SESSION['name'])){
        $pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'fred', 'zap');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }else{
        die ("Not logged in");
    }
?>
<?php 
    if ( isset($_POST["logout"])){

    }else if ( isset($_POST["add"])){
        if ( empty($_POST["make"]) ){
            $_SESSION["failure"] = "Make is required";
            header("Location: add.php");
            return;  
        }else if ( (!is_numeric($_POST["year"]) || !is_numeric($_POST["mileage"])) ){
            $_SESSION["failure"] = "Mileage and year must be numeric";
            header("Location: add.php");
            return;
        }else{
            if(isset($_POST["make"]) && isset($_POST["year"]) && isset($_POST["mileage"])){
                try {
                    $stmt = $pdo->prepare('INSERT INTO autos (make, year, mileage) VALUES ( :mk, :yr, :mi)');
                    $stmt->execute(array(
                        ':mk' => htmlentities($_POST['make']),
                        ':yr' => htmlentities($_POST['year']),
                        ':mi' => htmlentities($_POST['mileage']))
                    );
                    $_SESSION["success"] = "Record inserted";
                    header("Location: view.php");
                    return;
                }catch (Exception $ex){
                    error_log("Insert Error ".$ex);
                    return;
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Ahmed Abdelhamid - 8760f393</title>
</head>
<body>
<div class="container">
<?php echo "<h1>Tracking Autos for ".$_SESSION['name']."</h1>";?>
<?php 
    if ( isset($_SESSION["failure"]) && !empty($_SESSION["failure"]) ) {
        echo('<p style="color: red;">'.htmlentities($_SESSION["failure"])."</p>\n");
        unset($_SESSION["failure"]);
    }
?>
<form method="POST">
    <label for="mak">Make:</label>
    <input type="text" name="make" id="mak"><br/>
    <label for="yea">Year</label>
    <input type="text" name="year" id="yea"><br/>
    <label for="mil">Mileage</label>
    <input type="text" name="mileage" id="mil"><br/>
    <input type="submit" name ="add" value="Add">
    <input type="submit" name="cancel" value="Cancel">
</form>
</div>
</body>
</html>
