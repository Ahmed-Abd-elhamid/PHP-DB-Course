<?php 
    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'fred', 'zap');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
<?php 
    if( !isset($_GET["name"]) && empty($_GET["name"])){
        die ("Name parameter missing");
    }
    if ( isset($_POST["logout"])){
        header("Location: index.php");
        return;
    }else if ( isset($_POST["add"])){
        if ( empty($_POST["make"]) ){
            $failure = "Make is required";   
        }else if ( (!is_numeric($_POST["year"]) || !is_numeric($_POST["mileage"])) ){
            $failure = "Mileage and year must be numeric";
        }else{
            $success = "Record inserted";
            try {
                $stmt = $pdo->prepare('INSERT INTO autos (make, year, mileage) VALUES ( :mk, :yr, :mi)');
                $stmt->execute(array(
                    ':mk' => htmlentities($_POST['make']),
                    ':yr' => htmlentities($_POST['year']),
                    ':mi' => htmlentities($_POST['mileage']))
                );
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
<title>Ahmed Abdelhamid - 4bd51b84</title>
</head>
<body>
<div class="container">
<?php echo "<h1>Tracking Autos for ".$_GET["name"]."</h1>";?>
<?php 
    if ( isset($failure) && $failure !== false ) {
        echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
    }
    if ( isset($success) && $success !== false ) {
        echo('<p style="color: green;">'.htmlentities($success)."</p>\n");
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
    <input type="submit" name="logout" value="Logout">
</form>
<div>
    <h1>Automobilies</h1>
    <?php 
        $stmt = $pdo->query("SELECT * FROM autos;");
        echo "<ul>";
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            echo "<li>".$row['year']." ".$row['make']." / ".$row['mileage']."</li>";
        }
        echo "</ul>";
    ?>
</div>
</div>
</body>
</html>