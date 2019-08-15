<?php
/* Page for displaying all customers and allowing edits, additionally allowing customers to edit their own details. */
session_start();
$loggedin = $_SESSION["loggedin"];

if(!$loggedin || $_SESSION["accesslevel"] < 0) {
    header("Location: customerportal.php?error=denied"); /* Redirect browser */ 
    exit();
};
/* Connection file for SQL, local or remote */
require "includes/sql_connect.php";

/* IF VIEWALL is ACTION then select all users and userdetails and present in a table format */
if(isset($_GET["action"]) && $_GET["action"] == "viewall") {
$bookingssql = "SELECT * FROM appointments WHERE appdatetime > '".date( 'Y-m-d H:i:s', time())."'";
};

$booking = mysqli_query($connect, $bookingssql);

?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="main.css">
    <title>Interelay Solutions - Displaying all customers</title>
    </head>
<body>
<?php require "includes/navbar.php"; ?>
<div class="general_slide">
    <div class="island" style="width:90%">
        <?php if($_GET["action"] == "viewall") { ?>
        <h2 style="text-align:left;">Showing all upcoming appointments</h2>
        <div class="table">
        <span class="table-row">
        <span class="table-cell-nob">Name</span>
        <span class="table-cell-nob">First Line</span>
        <span class="table-cell-nob">Post Code</span>
        <span class="table-cell-nob">Date</span>
        <span class="table-cell-nob">Problem</span>
        <span class="table-cell-nob">Assigned Technician</span>
            </span>
           <?php
    if (mysqli_num_rows($booking) > 0) {
        while($bookings = mysqli_fetch_assoc($booking)){
            $userid = $bookings["linkedid"];
            echo "<span class='table-row'>";
            $customerdetailssql = "SELECT * FROM userdetails WHERE userdetid='$userid'";
            $customerdetails = mysqli_fetch_assoc(mysqli_query($connect,$customerdetailssql));
            foreach($bookings as $key => $item){
                if($key == "appid") {
                } elseif ($key == "linkedid") {
                    echo "<span class='table-cell'>".$customerdetails["firstnames"]." ".$customerdetails["surname"]."</span>";
                    echo "<span class='table-cell'>".$customerdetails["firstlineadd"]."</span>";
                    echo "<span class='table-cell'>".$customerdetails["postcode"]."</span>";
                } elseif ($key == "technician") {
                    $techdetailssql = "SELECT * FROM userdetails WHERE userdetid='$item'";
                    $techdetails = mysqli_fetch_assoc(mysqli_query($connect,$techdetailssql));
                    echo "<span class='table-cell'>".$techdetails["firstnames"]." ".$techdetails["surname"]."</span>";
                } else {
                    echo "<span class='table-cell'>".$item."</span>";
                };
                };
                        echo "<span class='table-cell'><a href='book.php?action=details&uid=".$bookings["linkedid"]."appid=".$bookings["appid"]."'>Details</a>";
            
            echo "</span></span>";
            };

    } else {
        echo "<p id='required'>There are no upcoming appointments to show</p>";
    };
}; 
if (isset($_GET["action"]) && $_GET["action"] == "details"){
    $userid = $_GET["uid"];
    $bookingid = $_GET["appid"];
    $customerdetailssql = "SELECT * FROM userdetails WHERE userdetid='$userid'";
    $customer = mysqli_query($connect, $customerdetailssql);

};
        ?>
                    
        </div>
    </div>
</div>    
</body>
</html>