<?php
$loggedin = 0;

session_start();
$loggedin = $_SESSION["loggedin"];

if(!$loggedin || $_SESSION["accesslevel"] < 0) {
    header("Location: customerportal.php?error=denied"); /* Redirect browser */ 
    exit();
};
/* Connection file for SQL, local or remote */
require "includes/sql_connect.php";

if(isset($_GET["uid"])) {
    $uid = $_GET["uid"];
    $customersql = "SELECT * FROM users WHERE uid='".$uid."'";
    $customerdetailssql = "SELECT * FROM userdetails WHERE userdetid='".$uid."'";
    $customer = mysqli_fetch_assoc(mysqli_query($connect, $customersql));
    $customerdetails = mysqli_fetch_assoc(mysqli_query($connect, $customerdetailssql));
    
    
}
?>
<html><head>
    <link rel="stylesheet" type="text/css" href="main.css">
    <title>Interelay Solutions - Displaying all customers</title>
          <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA11CZJpg-ulZnZzsaNP9c4j3yKsffznfU"></script>
      <script type="text/javascript">

          function getPosition(callback) {
            var geocoder = new google.maps.Geocoder();
            var postcode = document.getElementById("postcode").textContent;

            geocoder.geocode({'address': postcode}, function(results, status) 
            {   
              if (status == google.maps.GeocoderStatus.OK) 
              {
                callback({
                  latt: results[0].geometry.location.lat(),
                  long: results[0].geometry.location.lng()
                });
              }
            });
          }

          function setup_map(latitude, longitude) { 
            var _position = { lat: latitude, lng: longitude};
            
            var mapOptions = {
              zoom: 16,
              center: _position
            }

            var map = new google.maps.Map(document.getElementById('map'), mapOptions);

            var marker = new google.maps.Marker({
              position: mapOptions.center,
              map: map
            });
          }

          window.onload = function() {
             getPosition(function(position){
              setup_map(51.5073509, -0.12775829999998223);
                setup_map(position.latt, position.long);
             })
          };
            
      </script> 
    </head>
<body>
<?php require "includes/navbar.php"; ?>
<div class="general_slide">
    <div class="island" style="width:90%" style="text-align:left;">
        <h2 style="font-size:xx-large;"><?php echo $customerdetails["firstnames"]." ".$customerdetails["surname"];?></h2>
        <div style="clear:both; text-align:left;">[<a href="customers.php?action=changeadd&uid=<?php echo $uid ?>">Edit Address</a>] - [<a href="customers.php?action=changeem&uid=<?php echo $uid ?>">Edit Email</a>] - [<a href="customers.php?action=changepass&uid=<?php echo $uid ?>">Edit Password</a>] - [<a href="customers.php?action=changemark&uid=<?php echo $uid ?>">Edit Marketing Preferences</a>] - [<a href="customers.php?action=changedevices&uid=<?php echo $uid ?>">Edit Devices</a>]</div>
<div style="float:left">
<p id="textbox" style="min-width:300px;">
<?php 
    echo $customerdetails["firstlineadd"]."<br />";
    echo (isset($customerdetails["secondlineadd"]) ? $customerdetails["secondlineadd"]."<br />" : "");
    echo $customerdetails["town"]."<br />";
    echo $customerdetails["county"]."<br />";
    echo $customerdetails["country"]."<br />";
    echo "<span id='postcode'>".$customerdetails["postcode"]."</span><br />";
?>
</p>
<p id="textbox"><?php echo $customerdetails["contactno"];
    echo (isset($customerdetails["mobileno"]) ? "<br />".$customerdetails["mobileno"] : "") ?></p>
<p id="textbox"><?php echo "<a href='mailto:".$customer["emailaddress"]."'>".$customer["emailaddress"]."</a>"; ?></p>
        </div>
        <div id="map" style="width:300px;height:220px; float:left; border:2px solid grey; border-radius:5px; margin-left:50px; margin-top:20px"></div>
    </div>
    <div class="island" style="width:90%" style="text-align:left;">
<h2>Upcoming Appointments</h2>
<?php 
        $futureappointmentsql = "SELECT * FROM appointments WHERE appdatetime > '".date( 'Y-m-d H:i:s', time())."' AND linkedid='".$uid."'";
        $futureappointments = mysqli_query($connect, $futureappointmentsql);
if (mysqli_num_rows($futureappointments) > 0) { ?>
<div class="table">
<span class="table-row">
<span class="table-cell-nob">Date</span>
<span class="table-cell-nob">Problem</span>
<span class="table-cell-nob">Assigned Technician</span>
<span class="table-cell-nob">&nbsp;</span>
<span class="table-cell-nob">&nbsp;</span>
</span>
<?php while ($row = mysqli_fetch_assoc($futureappointments)) { 
    $techsql = "SELECT * FROM userdetails WHERE userdetid='".$row["technician"]."'";
    $tech = mysqli_fetch_assoc(mysqli_query($connect, $techsql));
    ?>
<span class="table-row">
<span class="table-cell"><?php echo $row["appdatetime"]; ?></span>
<span class="table-cell"><?php echo $row["details"]; ?></span>
<span class="table-cell"><?php echo $tech["firstnames"]." ".$tech["surname"]; ?></span>
<span class="table-cell"><?php echo "<a href='book.php?appid=".$row["appid"]."&action=view'>View</a>"; ?></span>
<span class="table-cell"><?php echo "<a href='book.php?appid=".$row["appid"]."&action=edit'>Edit</a>"; ?></span>
</span>
    <?php }; ?>
    </div>
<?php } else {
    echo "<p>There are no upcoming appointments</p>";
}; ?>
<h2>Past Appointments</h2>
<?php 
        $pastappointmentsql = "SELECT * FROM appointments WHERE appdatetime < '".date( 'Y-m-d H:i:s', time())."'";
        $pastappointments = mysqli_query($connect, $pastappointmentsql);
if (mysqli_num_rows($pastappointments) > 0) { ?>
<div class="table">
<span class="table-row">
<span class="table-cell-nob">Date</span>
<span class="table-cell-nob">Problem</span>
<span class="table-cell-nob">Assigned Technician</span>
<span class="table-cell-nob">&nbsp;</span>
</span>
<?php while ($row = mysqli_fetch_assoc($pastappointments)) { 
    $techsql = "SELECT * FROM userdetails WHERE userdetid='".$row["technician"]."'";
    $tech = mysqli_fetch_assoc(mysqli_query($connect, $techsql));
    ?>
<span class="table-row">
<span class="table-cell"><?php echo $row["appdatetime"]; ?></span>
<span class="table-cell"><?php echo $row["details"]; ?></span>
<span class="table-cell"><?php echo $tech["firstnames"]." ".$tech["surname"]; ?></span>
<span class="table-cell"><?php echo "<a href='book.php?appid=".$row["appid"]."&action=view'>View</a>"; ?></span>
</span>
<?php     }; ?>
    </div>
<?php } else {
    echo "<p>There are no previous appointments</p>";
}; ?>
        </div>
    <div class="island" style="width:90%" style="text-align:left;">
<h2>Invoices</h2>
<?php 
        $invoicessqldesc = "SELECT * FROM invoices WHERE linkedid='".$uid."' ORDER BY date DESC";
        $invoices = mysqli_query($connect, $invoicessqldesc);
        $invoicessqlasc = "SELECT * FROM invoices WHERE linkedid='".$uid."' ORDER BY date ASC";
        $invoicesasc = mysqli_query($connect, $invoicessqlasc);
if (mysqli_num_rows($invoices) > 0 && mysqli_num_rows($invoicesasc) > 0) { ?>        
<div style="min-width:300px;max-width:770px; margin:0px; padding:0px; text-align:right;">
<div class="table">
<span class="table-row">
<span class="table-cell-nob">Date</span>
<span class="table-cell-nob">Invoice #</span>
<span class="table-cell-nob">Amount (excl VAT)</span>
<span class="table-cell-nob">Discount (%)</span>
<span class="table-cell-nob">VAT (%)</span>
<span class="table-cell-nob">Total</span>
<span class="table-cell-nob">Outstanding Amount</span>
<span class="table-cell-nob">&nbsp;</span>
</span>
<?php
    $totaldue = 0;
    $totalpaid = 0;
    $totalpaidtwo = 0;
    $paymentstotalsql = "SELECT * FROM payments WHERE linkedid='".$uid."'";
    $payments = mysqli_query($connect, $paymentstotalsql);
 
    while ($payment = mysqli_fetch_assoc($payments)) {
        $paidvat = ($payment["net_paid"]) * ($payment["vat"] / 100);
        $paidtotal = $payment["net_paid"] + $paidvat;
        $totalpaid = $totalpaid + $paidtotal;
    };
    $inc = 0;
    $grosstotal = 0;
    while ($invoiceasc = mysqli_fetch_assoc($invoicesasc)) {
        $discountamt = $invoiceasc["net_total"] * ($invoiceasc["discount"] / 100);
        $vat = ($invoiceasc["net_total"] - $discountamt) * ($invoiceasc["vat"] / 100);
        $grosstotal = $grosstotal + ($invoiceasc["net_total"] - $discountamt + $vat);
        $runningtotal = $grosstotal - $totalpaid;
        $invtotal[$invoiceasc["invoiceno"]] = $runningtotal;
        $inc = $inc+1;
    };
    
    while ($invoice = mysqli_fetch_assoc($invoices)) {   
    $discountamt = $invoice["net_total"] * ($invoice["discount"] / 100);
    $vat = ($invoice["net_total"] - $discountamt) * ($invoice["vat"] / 100);
    $grosstotal = $invoice["net_total"] - $discountamt + $vat;
    $totaldue = $totaldue + $grosstotal;
    if ($invtotal[$invoice["invoiceno"]] < 0) {
        $outstandinginvoice = 0.00;
    } else {
        $outstandinginvoice = abs($invtotal[$invoice["invoiceno"]]);
    };
?>
<span class="table-row">
<span class="table-cell"><?php echo $invoice["date"]; ?></span>
    <span class="table-cell"><?php echo "<a href='invoices.php?action=view&invid=".$invoice["invoiceno"]."'>".$invoice["invoiceno"]."</a>"; ?></span>
<span class="table-cell"><?php echo "£".number_format($invoice["net_total"], 2); ?></span>
<span class="table-cell"><?php echo "£".number_format($discountamt,2)." (".number_format($invoice["discount"],1)."%)"; ?></span>
<span class="table-cell"><?php echo "£".number_format($vat,2)." (".number_format($invoice["vat"],0)."% VAT)" ?></span>
<span class="table-cell"><?php echo "£".number_format($grosstotal,2); ?></span>
<span class="table-cell"><span style="color:<?php echo ($outstandinginvoice == 0 ? "green" : "red"); ?>; font-weight:bold;"><?php echo "£".number_format($outstandinginvoice,2); ?></span> </span>
</span>
<?php };
    $paymentstotalsql = "SELECT * FROM payments WHERE linkedid='".$uid."' ORDER BY date DESC";
    $payments = mysqli_query($connect, $paymentstotalsql);
    $totalpaid = 0;
    while ($payment = mysqli_fetch_assoc($payments)) {
        $paidvat = ($payment["net_paid"]) * ($payment["vat"] / 100);
        $paidtotal = $payment["net_paid"] + $paidvat;
        $totalpaid = $totalpaid + $paidtotal;
    };
    $outstanding = $totaldue - $totalpaid;
    if ($outstanding <= 0) {
        $outstanding = abs($outstanding);
        $credit = 1;
        $totaltext = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Credit";
    } elseif ($outstanding > 0 ) {
        $totaltext = "Total Outstanding";
    };
    ?>
<span class="table-row">
<span class="table-cell-nob">&nbsp;</span>
<span class="table-cell-nob">&nbsp;</span>
<span class="table-cell-nob">&nbsp;</span>
<span class="table-cell-nob">&nbsp;</span>
<span class="table-cell-nob">&nbsp;</span>
<span class="table-cell-nob"><?php echo $totaltext; ?>:</span>
<span class="table-cell" style="color:<?php echo (isset($credit) ? "green" : "red"); ?>; font-weight:bold;"><?php echo "£".number_format($outstanding,2); ?></span>
    </span>
        </div>
<a href="payments.php?action=paynow&uid=1" class="button">Pay Now</a>
</div>
<?php 
} else {
    echo "<p>There are no invoices to display</p>";
}; ?>

<h2>Payments</h2>
<?php
    $paymentstotalsql = "SELECT * FROM payments WHERE linkedid='".$uid."' ORDER BY date DESC";
    $payments = mysqli_query($connect, $paymentstotalsql);
    if(mysqli_num_rows($payments) > 0){ ?>
<div class="table">
<span class="table-row">
<span class="table-cell-nob">Date</span>
<span class="table-cell-nob">Reason</span>
<span class="table-cell-nob">Amount</span>
<span class="table-cell-nob">Payment Type</span>
<span class="table-cell-nob">Payment ID</span>
<?php
    while ($payment = mysqli_fetch_assoc($payments)) {
        $paidvat = ($payment["net_paid"]) * ($payment["vat"] / 100);
        $paidtotal = $payment["net_paid"] + $paidvat;
        
        if ($payment["payment_type"] == "cash") {
            $paytype = "Cash";
        } elseif($payment["payment_type"] == "cc") {
            $paytype = "Credit Card";
        }
?>
</span>
<span class="table-row">
<span class="table-cell"><?php echo $payment["date"]; ?></span>
<span class="table-cell"><?php echo $payment["reason"]; ?></span>
<span class="table-cell"><?php echo "£".number_format($paidtotal,2); ?></span>
<span class="table-cell"><?php echo $paytype; ?></span>
<span class="table-cell"><?php echo $payment["payment_id"]; ?></span>
</span>
<?php
    };
    } else {
        echo "<p>There are no payments to display</p>";
    }; ?>
        </div>
        </div>
<div style="display:inline-block;clear:both;width:400px;">&nbsp;</div>
</div>
        <script src="js/stickyfill.min.js"></script>
        <script>
            var sidebar = document.getElementById('navbar');
Stickyfill.add(sidebar);
        </script>
</body>
</html>

