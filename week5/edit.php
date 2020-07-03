<?php
    session_start();
    if (isset($_SESSION['name'])){
        require_once "pdo.php";
    }else{
        die ("ACCESS DENIED");
    }
if ( isset($_POST["edit"])){
    if ( empty($_POST["make"]) || empty($_POST["model"]) || empty($_POST["year"]) || empty($_POST["mileage"])){
        $_SESSION["error"] = "All fields are required";
        header("Location: edit.php?autos_id=".$_POST['autos_id']);
        return;  
    }else if ( (!is_numeric($_POST["year"]) || !is_numeric($_POST["mileage"])) ){
        $_SESSION["error"] = "Mileage and year must be numeric";
        header("Location: edit.php?autos_id=".$_POST['autos_id']);
        return;
    }else{
        try {
            $stmt = $pdo->prepare('UPDATE autos SET make = :mk, model = :mo, year = :yr, mileage = :mi
            WHERE autos_id = :autos_id');
            $stmt->execute(array(
                ':mk' => htmlentities($_POST['make']),
                ':mo' => htmlentities($_POST['model']),
                ':yr' => htmlentities($_POST['year']),
                ':mi' => htmlentities($_POST['mileage']),
                ':autos_id' => $_POST['autos_id']));
            $_SESSION['success'] = 'Record updated';
            header( 'Location: index.php' ) ;
            return;
        }catch (Exception $ex){
            error_log("Insert Error ".$ex);
            return;
        }
    }
}

// Guardian: Make sure that autos_id is present
if ( ! isset($_GET['autos_id']) ) {
  $_SESSION['error'] = "Missing autos_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM autos where autos_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['autos_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for autos_id';
    header( 'Location: index.php' ) ;
    return;
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

$mo = htmlentities($row['model']);
$mk = htmlentities($row['make']);
$yr = htmlentities($row['year']);
$mi = htmlentities($row['mileage']);
$autos_id = $row['autos_id'];

?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Ahmed Abdelhamid - 48c1462e</title>
</head>
<body>
<div class="container">
<p>Edit Autos</p>
<form method="POST">
    <label for="mak">Make:</label>
    <input type="text" name="make" id="mak" value="<?= $mk ?>"><br/>
    <label for="mod">Model:</label>
    <input type="text" name="model" id="mod" value="<?= $mo ?>"><br/>
    <label for="yea">Year</label>
    <input type="text" name="year" id="yea" value="<?= $yr ?>"><br/>
    <label for="mil">Mileage</label>
    <input type="text" name="mileage" id="mil" value="<?= $mi ?>"><br/>
    <input type="hidden" name="autos_id" value="<?= $autos_id ?>">
    <input type="submit" name ="edit" value="Save"/>
    <a href="index.php">Cancel</a></p>
</form>
</div>
</body>
</html>