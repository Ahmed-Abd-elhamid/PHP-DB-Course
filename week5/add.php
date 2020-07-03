<?php
    session_start();
    if (isset($_SESSION['name'])){
        require_once "pdo.php";
    }else{
        die ("ACCESS DENIED");
    }

    if ( isset($_POST["add"])){
        if ( empty($_POST["make"]) || empty($_POST["model"]) || empty($_POST["year"]) || empty($_POST["mileage"])){
            $_SESSION["failure"] = "All fields are required";
            header("Location: add.php");
            return;  
        }else if ( (!is_numeric($_POST["year"]) || !is_numeric($_POST["mileage"])) ){
            $_SESSION["failure"] = "Mileage and year must be numeric";
            header("Location: add.php");
            return;
        }else{
            try {
                $stmt = $pdo->prepare('INSERT INTO autos (make, model, year, mileage) VALUES (:mk, :mo, :yr, :mi)');
                $stmt->execute(array(
                    ':mk' => htmlentities($_POST['make']),
                    ':mo' => htmlentities($_POST['model']),
                    ':yr' => htmlentities($_POST['year']),
                    ':mi' => htmlentities($_POST['mileage']))
                );
                $_SESSION["success"] = "added";
                header("Location: index.php");
                return;
            }catch (Exception $ex){
                error_log("Insert Error ".$ex);
                return;
            }
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Ahmed Abdelhamid - 48c1462e</title>
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
    <label for="mod">Model:</label>
    <input type="text" name="model" id="mod"><br/>
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