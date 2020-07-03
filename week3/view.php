<?php
    session_start();
    if (isset($_SESSION['name'])){
        $pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'fred', 'zap');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }else{
        die ("Not logged in");
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
<div>
    <h1>Automobilies</h1>
    <?php 
        if ( isset($_SESSION["success"]) && !empty($_SESSION["success"]) ) {
            echo('<p style="color: green;">'.htmlentities($_SESSION["success"])."</p>\n");
            unset($_SESSION["success"]);
        }
        $stmt = $pdo->query("SELECT * FROM autos;");
        echo "<ul>";
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            echo "<li>".$row['year']." ".$row['make']." / ".$row['mileage']."</li>";
        }
        echo "</ul>";
    ?>
</div>
<p><a href="add.php">Add New</a> | <a href="logout.php">Logout</a></p>
</div>
</body>
</html>