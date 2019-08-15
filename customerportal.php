<?php 
session_start();
if (!isset($_SESSION["loggedin"])) {
    header("Location: login.php?error=2");
};

if (!isset($_SESSION["loggedin"])) {
    $loggedin = 0;
} else {
    $loggedin = 1;
};

$name = explode(" ",$_SESSION["fname"]);

if (isset($_GET["error"]) && $_GET["error"] == "denied"){
    $error = "You do not have permission to view that page";
};
?><html>
<head>
<title>Welcome to the customer portal - Book an Appointment here</title>
<link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
<?php require "includes/navbar.php"; ?>
<div class="slide_inverted" style="background-image: url('slide3_v1.jpg'); background-repeat:no-repeat; background-size:cover; height:700px; text-align:center">
    <div style="background-image: url(footerstripe.gif); border: 1px solid grey; border-radius:5px; width:90%;display:inline-block; margin-top:20px; box-shadow: 1px 1px 4px black; padding-left: 10px; padding-top:0px; text-align:left;">
        <?php if (isset($error)) { echo "<p style=\"color:red;font-weight:bold\">".$error."</p>"; }; ?>
        <p style="text-align:left;">Welcome <?php echo $name[0]; ?>!</p>
        <p>Please choose from an option below:</p>
        <div style="margin-top:-20px">
        <h2>Appointments:</h2>
        <div style="clear:both;float:left; width:45%"><a href="bookings.php?action=book">Book an appointment</a></div><div style="float:right; width:45%"><a href="bookings.php?action=cancel">Cancel an Appointment</a></div><br/><br/>
        <h2>Support and Managed IT Services:</h2>
        <div style="clear:both;float:left; width:45%"><a href="support.php?action=new">View or create a support ticket</a></div><div style="float:right; width:45%"><a href="conatct.php?action=new&type=consult">Request a consultation</a></div><br/><br/>
        <h2>Your Details:</h2>
        <div style="clear:both;float:left; width:45%"><a href="customers.php?action=changeadd">Change your address or phone number</a></div><div style="float:right; width:45%"><a href="customers.php?action=changepass">Change password</a></div>
        <div style="clear:both;float:left; width:45%"><a href="customers.php?action=changeem">Change email address</a></div><div style="float:right; width:45%"><a href="customers.php?action=changemark">Change marketing preferences</a></div>
        <div style="clear:both;float:left; width:45%"><a href="customers.php?action=changedevices">Add or change your registered devices</a></div><div style="float:right; width:45%">&nbsp;</div>
        </div>
        <br/><br/><br /><br />
        <div>
<?php if($_SESSION["accesslevel"] > 0) { ?>
        <h2 style="clear:both;">Admin Panel:</h2>
        <div style="clear:both;float:left; width:45%"><a href="customers.php?action=viewall">Customer Manager</a></div><div style="float:right; width:45%"><a href="book.php?action=viewall">View Upcoming Appointments</a></div>
        <div style="clear:both;float:left; width:45%"><a href="customers.php?action=sendemails">Send Mass Email</a></div><div style="float:right; width:45%"><a href="news.php?action=update">Update Blog</a></div>
        </div>
        <div style="clear:both;float:left; width:45%"><a href="customers.php?action=addnew">Create New Customer</a></div><div style="float:right; width:45%">&nbsp;</div>
        </div>
<div>&nbsp;<br /><br /> <br /></div>
        <?php }; ?>
    </div>
</div>
</body>
</html>