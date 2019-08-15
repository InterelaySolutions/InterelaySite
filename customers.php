<?php
/* Page for displaying all customers and allowing edits, additionally allowing customers to edit their own details. */
session_start();
$loggedin = $_SESSION["loggedin"];

if($loggedin != 1 || $_SESSION["accesslevel"] <= 0) {
    header("Location: customerportal.php?error=denied"); /* Redirect browser */
    exit();
};
/* Connection file for SQL, local or remote, use $connect for mysqli_query() */
require "includes/sql_connect.php";

/* ----- PROCESS BLOCK FOR ADDING NEW CUSTOMERS AND CHANGING DETAILS  ----

if GET variables action=addnew and form=submit are set then begin processing new customer form */

if(((isset($_GET["action"]) && (($_GET["action"] == "addnew") || ($_GET["action"] == "changeem") || ($_GET["action"] == "changepass") || ($_GET["action"] == "changeadd") || ($_GET["action"] == "changemark") || ($_GET["action"] == "changedevices")) && (isset($_GET["form"]) && $_GET["form"] == "submit")))) {
    if (isset($_GET["uid"])){
    $uid = $_GET["uid"];
} else {
    $uid = $_SESSION["uid"];
}
    $error = null;
    $success = null;
    $valid = 0;
    $uniquevalid = 0;
    $matchingvalid = 0;
    
    /* Set post data to form for form population if theres an error and for easier manipulation */
    $form = $_POST;
    
    /* CHECK FORM FOR EMPTY FIELDS CEPT NOT REQUIRED FIELDS */    
    foreach ($form as $finputname => $checkitem) {
        // Do not check the second line address, mobile number, devices and marketing fields as these are not required
        if (($finputname == "secondlineadd") || ($finputname == "mobileno") || ($finputname == "devices") || ($finputname == "marketing")) {
            
        } else {
            if (empty($checkitem)) {
                $error[$finputname] = 1;
                
            }
            // If error isn't set and theres no empty required fields then set $valid to true
            if(!isset($error)) {
                $valid = 1;
            }

        };
            
    };
    
    // Check for identical email address in database (email addresses must be unique as they are the username)
    if(isset($_GET["action"]) && (($_GET["action"] == "addnew") || ($_GET["action"] == "changeem"))) {
        if (mysqli_query($connect,"SELECT * FROM users WHERE username=".$form["emailaddress"])) {
            $error["emailaddress"] = 2;
        } else {
            $uniquevalid = 1;
        };
    };
    
    // Check form data to make sure password and confirm password fields match
    if(isset($_GET["action"]) && (($_GET["action"] == "addnew") || ($_GET["action"] == "changepass"))) {
        if($form["password"] != $form["confirmpassword"]) {
            $error["matchingpasswords"] =1;
        } else {
            $matchingvalid = 1;
        };
    };
                
    /* ALL FIELDS ARE VALIDATED BEGIN ADDING TO DATABASE 
    
    Convert devices to a comma seperated string and convert marketing checkbox to usable value */
    if((isset($_GET["action"]) && ($_GET["action"] == "addnew") && (isset($_GET["form"]) && ($_GET["form"] == "submit")))) {
        if ($valid == 1 && $uniquevalid == 1 && $matchingvalid == 1) {
             
        if (isset($form["devices"])) {
            $form["devices"] = implode(',',$form["devices"]);
        };
        // Set marketing variable within the form data depending on checked or not
        if (!isset($form["marketing"])) {
            $form["marketing"] = 0; } else {
            $form["marketing"] = 1;
        };

        // Hash the password entered using php function
        $password = password_hash($form["password"],PASSWORD_DEFAULT);

        // Sanitise all fields being entered into database to avoid insertion attacks
        foreach($form as $fieldname => $formitem) {
            $form[$fieldname] = htmlspecialchars($formitem);
        };
        
        // SQL for adding into user table (MUST BE DONE FIRST, generated userid will be used for key in userdetails table)
        $addnewusersql = "INSERT INTO users (username, password, accesspriv, emailaddress) VALUES ('".$form['emailaddress']."', '".$password."', '0', '".$form['emailaddress']."')";
        
        /* 
        Final check to make sure data is correct and database is running before adding everything else.
        PHP will now check the userid based upon the email address entered, store the single record and use the userid to link between the two tables (users and userdetails) and return a success variable to display success message above the form, ready to enter another customer. 
        */
        if (mysqli_query($connect,$addnewusersql)) {
        $searchnewusersql = "SELECT * FROM users WHERE username='".$form['emailaddress']."'";                
        $newusercheck = mysqli_fetch_assoc(mysqli_query($connect, $searchnewusersql));
        $addnewuserdetails =  "INSERT INTO userdetails (userdetid,firstnames,surname,firstlineadd,secondlineadd,town,county,country,postcode,contactno,mobileno,prevcustomer,ownerof, marketing) values ('".$newusercheck['uid']."','".$form['firstnames']."','". $form['surname']."','".$form['firstlineadd']."','".$form['secondlineadd']."','".$form['town']."','".$form['county']."','".$form['country']."','".$form['postcode']."','".$form['contactno']."','".$form['mobileno']."','0','".$form["devices"]."','".$form["marketing"]."')";
        mysqli_query($connect, $addnewuserdetails);

        $success = 1;
        } else {
            echo "error: ". $addnewusersql . " ". mysqli_error($connect);
        };
    };
        // EDITING TABLES BLOCK IN THE FOLLOWING ORDER - 1) Change Email 2) Change Password 3) Change Address 4) Change Marketing Preferences 5) Change registered devices
        } elseif(isset($_GET["action"]) && ($_GET["action"] == "changeem") && (isset($_GET["form"]) && ($_GET["form"] == "submit"))) {
            if ($valid == 1 && $uniquevalid == 1) {
                foreach($form as $fieldname => $formitem) {
                    $form[$fieldname] = htmlspecialchars($formitem);
                };

                $changeemailsql = "UPDATE users SET username='".$form["emailaddress"]."',emailaddress='".$form["emailaddress"]."' WHERE uid='".$uid."'";
                if(mysqli_query($connect, $changeemailsql)) {
                    $success = 1;
                }
            }
        } elseif(isset($_GET["action"]) && (($_GET["action"] == "changepass"))) {
            if($valid == 1 && $matchingvalid == 1) {
                $password = password_hash($form["password"],PASSWORD_DEFAULT); 
                $changepasswordsql = "UPDATE users SET password='".$password."' WHERE uid='".$uid."'";
                if(mysqli_query($connect, $changepasswordsql)) {
                    $success = 1;
                    session_unset();
                    session_destroy();
                    $loggedin = 0;
                    $_SESSION = null;
                };
            }
        } elseif(isset($_GET["action"]) && ($_GET["action"] == "changeadd") && (isset($_GET["form"]) && ($_GET["form"] == "submit"))) {
                if ($valid == 1) {
                    foreach($form as $fieldname => $formitem) {
                        $form[$fieldname] = htmlspecialchars($formitem);
                    };

                    $changeaddsql = "UPDATE userdetails SET firstnames='".$form["firstnames"]."',surname='".$form["surname"]."',firstlineadd='".$form["firstlineadd"]."',secondlineadd='".$form["secondlineadd"]."',town='".$form["town"]."',county='".$form["county"]."',country='".$form["country"]."',postcode='".$form["postcode"]."',contactno='".$form["contactno"]."',mobileno='".$form["mobileno"]."' WHERE userdetid='".$uid."'";

                    if(mysqli_query($connect, $changeaddsql)) {
                        $success = 1;
                    } else {
                        echo "error: ". $changeaddsql . " ". mysqli_error($connect);
                    }; 
                };

        } elseif(isset($_GET["action"]) && (($_GET["action"] == "changemark"))){
            if (!isset($form["marketing"])) {
                $form["marketing"] = 0; } else {
                $form["marketing"] = 1;
            };

            $changemarketingsql = "UPDATE userdetails SET marketing='".$form["marketing"]."' WHERE userdetid='".$uid."'";
            if(mysqli_query($connect, $changemarketingsql)) {
                    $success = 1;
            }; 
        } elseif(isset($_GET["action"]) && (($_GET["action"] == "changedevices"))&& (isset($_GET["form"]) && ($_GET["form"] == "submit"))) {
            if (isset($form["devices"])) {
                $form["devices"] = implode(',',$form["devices"]);
            };

            $changedevicessql = "UPDATE userdetails SET ownerof='".$form["devices"]."' WHERE userdetid='".$uid."'";

            if(mysqli_query($connect, $changedevicessql)) {
                    $success = 1;
            }; 

        };
    };
            


/* IF VIEWALL is ACTION then select all users and userdetails and present in a table format */
if(isset($_GET["action"]) && $_GET["action"] == "viewall") {
$customersql = "SELECT * FROM users";
};

/* If CHANGEADD is ACTION then request from server the details of the customer based on uID */
if(isset($_GET["action"]) && ($_GET["action"] == "changeadd" || $_GET["action"] == "changepass" || $_GET["action"] == "changeem" || $_GET["action"] == "changemark" || $_GET["action"] == "changedevices")) {
if (isset($_GET["uid"])){
    $uid = $_GET["uid"];
} else {
    $uid = $_SESSION["uid"];
}
$customersql = "SELECT * FROM users WHERE uid='".$uid."'";
};

if (isset($customersql)) {
$customers = mysqli_query($connect, $customersql);
};

/* Function to check devices in exploded string and return true if they match */
function checkdevices($input,$input2) {
    if(isset($input) && isset($input2)) {
        foreach ($input as $devicearr) {
            if ($devicearr == $input2) {
                return true;
            };                           
        };
    } else {
        return false;
    };
};
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
        <h2 style="text-align:left;">Showing all customer records...</h2>
        <div class="table">
        <span class="table-row">
        <span class="table-cell-nob">First Names</span>
        <span class="table-cell-nob">Surname</span>
        <span class="table-cell-nob">Address 1</span>
        <span class="table-cell-nob">Address 2</span>
        <span class="table-cell-nob">Town</span>
        <span class="table-cell-nob">County</span>
        <span class="table-cell-nob">Country</span>
        <span class="table-cell-nob">Post Code</span>
        <span class="table-cell-nob">Phone #</span>
        <span class="table-cell-nob">Mobile #</span>
        <span class="table-cell-nob">Previous Customer</span>
        <span class="table-cell-nob">Devices</span>
        <span class="table-cell-nob">Marketing</span> 
        <span class="table-cell-nob">&nbsp;</span> 
        </span>
            <?php
        while($customer = mysqli_fetch_assoc($customers)){
            $userid = $customer["uid"];
            echo "<span class='table-row'>";
            $customerdetailssql = "SELECT * FROM userdetails WHERE userdetid='$userid'";
            $customerdetails = mysqli_fetch_assoc(mysqli_query($connect,$customerdetailssql));
            foreach($customerdetails as $key => $item){
                if($key == "userdetid") {} 
                elseif($key == "ownerof") {
                    $icons = explode(",", $item);
                    echo "<span class='table-cell'>";
                    foreach ($icons as $image) {
                        echo "<img style='width:15px;max-width:15px height:auto' src='icons/".$image.".png' title='".ucfirst($image)."'/>&nbsp;";
                    }
                    echo "</span>";
                } else {
                if ($item == "1") {
                    $item = "Yes";
                } else if ($item == "0") {
                    $item = "No";
                };
                echo "<span class='table-cell'>".$item."</span>";
                };
            };
            echo "<span class='table-cell'><a href='customerdetails.php?uid=".$userid."'>Details</a></span>";
            
            echo "</span>";
        };
            mysqli_free_result($customers);
            mysqli_close($connect);
        } elseif ($_GET["action"] == "changeadd") {
            $customer = mysqli_fetch_assoc($customers);
            $userid = $customer["uid"];
            $customerdetailssql = "SELECT * FROM userdetails WHERE userdetid='$userid'";
            $customerdetails = mysqli_fetch_assoc(mysqli_query($connect,$customerdetailssql));
            $form = $customerdetails;
            if(isset($_GET["form"]) && $_GET["form"] == "submit"){
                $form = $_POST;
            };
            ?>
            <h2 style="text-align:left;">Change your address</h2>
                <?php if(isset($success) == 1) {
            print_r("<p style='text-align:left' id='success'>Address Changed!</p>");
            };
            if(isset($error)) {
                print_r("<p style='text-align:left' id='required'>Error: Please fill in the required fields</p>");
            }; ?>
            <div class="table">
            <form action="customers.php?action=changeadd&<?php echo (isset($_GET["uid"]) ? "uid=".$uid."&" : "") ?>form=submit" method="post" style="text-align:left;">
                <p>
                    <label><span id="required">*</span> First name(s): &nbsp;&nbsp;</label>
                    <input type="text" value="<?php echo $form["firstnames"] ?>" name="firstnames"/><?php echo ((isset($error["firstnames"]) == 1) ? " <span id='required'> Please fill in your first name</span>" : ""); ?>
                </p>
                <p>
                    <label><span id="required">*</span> Surname: &nbsp;&nbsp;</label>
                    <input type="text" value="<?php echo $form["surname"] ?>" name="surname"/><?php echo ((isset($error["surname"]) == 1) ? " <span id='required'> Please fill in yoursurname</span>" : ""); ?>
                </p>
                <p>
                    <label><span id="required">*</span> First line of Address: &nbsp;&nbsp;</label>
                    <input type="text" value="<?php echo $form["firstlineadd"] ?>" name="firstlineadd"/><?php echo ((isset($error["firstlineadd"]) == 1) ? " <span id='required'> Please fill in the first line of the address</span>" : ""); ?>
                </p>
                <p>
                    <label>Second line of Address: &nbsp;&nbsp;</label>
                    <input type="text" value="<?php echo $form["secondlineadd"] ?>" name="secondlineadd"/>
                </p>
                <p>
                    <label><span id="required">*</span> Town: &nbsp;&nbsp;</label>
                    <input type="text" value="<?php echo $form["town"] ?>" name="town"/><?php echo ((isset($error["town"]) == 1) ? " <span id='required'> Please fill in the town</span>" : ""); ?>
                </p>
                <p>
                    <label><span id="required">*</span> County: &nbsp;&nbsp;</label>
                    <input type="text" value="<?php echo $form["county"] ?>" name="county"/><?php echo ((isset($error["county"]) == 1) ? " <span id='required'> Please fill in the county</span>" : ""); ?>
                </p>
                 <p>
                    <label><span id="required">*</span> Country: &nbsp;&nbsp;</label>
                    <input type="text" value="<?php echo $form["country"] ?>" name="country"/><?php echo ((isset($error["country"]) == 1) ? " <span id='required'> Please fill in the country</span>" : ""); ?>
                </p>
                <p>
                    <label><span id="required">*</span> Postal Code: &nbsp;&nbsp;</label>
                    <input type="text" value="<?php echo $form["postcode"] ?>" name="postcode"/><?php echo ((isset($error["postcode"]) == 1) ? " <span id='required'> Please fill in the Post Code</span>" : ""); ?>
                </p>
                <p>
                    <label><span id="required">*</span> Phone Number: &nbsp;&nbsp;</label>
                    <input type="text" value="<?php echo $form["contactno"] ?>" name="contactno"/><?php echo ((isset($error["contactno"]) == 1) ? " <span id='required'> Please fill in the contact number</span>" : ""); ?>
                </p>
                <p>
                    <label>Mobile Number: &nbsp;&nbsp;</label>
                    <input type="text" value="<?php echo $form["mobileno"] ?>" name="mobileno"/>
                </p>
                <p>
                    <label>&nbsp;&nbsp;</label>
                    <input type="submit" value="Change Address"/>
                </p>
            </form>
            </div>
        
        <?php
        mysqli_free_result($customers);
        mysqli_close($connect);
        
        } elseif ($_GET["action"] == "changepass") { 
?>
            <h2 style="text-align:left;">Change your password</h2>
            <p style="text-align:left">Note: When you change your password you will be logged out of the site.</p>
            <?php
            if(isset($success) == 1) {
            print_r("<p style='text-align:left' id='success'>Password Changed!</p>");
            };
            if(isset($error)) {
                print_r("<p style='text-align:left' id='required'>Error: Please fill in the required fields</p>");
            };
            if(isset($error["matchingpasswords"]) == 1) {
                print_r("<p style='text-align:left' id='required'>Error: Passwords do not match</p>");
            }; ?>
            <div class="table">
            <form action="customers.php?action=changepass&<?php echo (isset($_GET["uid"]) ? "uid=".$uid."&" : "") ?>form=submit" method="post" style="text-align:left;">
                <p>
                    <label><span id="required">*</span> New Password: &nbsp;&nbsp;</label>
                    <input type="password" value="" name="password"/><?php echo ((isset($error["password"]) == 1) ? " <span id='required'> Please fill in the password</span>" : ""); ?>
                </p>  
                <p>
                    <label><span id="required">*</span> Confirm Password: &nbsp;&nbsp;</label>
                    <input type="password" value="" name="confirmpassword"/><?php echo ((isset($error["confirmpassword"]) == 1) ? " <span id='required'> Please fill in the password</span>" : ""); ?>
                </p>
                <p>
                    <label>&nbsp;&nbsp;</label>
                    <input type="submit" value="Change Password"/>
                </p>
            </form>
            </div>
<?php
        
        } elseif ($_GET["action"] == "changeem") {
            $customer = mysqli_fetch_assoc($customers);
            $email = $customer["emailaddress"];
            if (isset($_GET["form"]) && $_GET["form"] == "submit"){
            $email = $form["emailaddress"];
            }
            ?>
            <h2 style="text-align:left;">Change your email address</h2>
            <?php
            if(isset($error)) {
                print_r("<p style='text-align:left' id='required'>Error: Please fill in the required fields</p>");
            };
            if(isset($success) == 1) {
            print_r("<p style='text-align:left' id='success'>Email address changed to $email</p>");
            }; 
            ?>
            <div class="table">
            <form action="customers.php?action=changeem&<?php echo (isset($_GET["uid"]) ? "uid=".$uid."&" : "") ?>form=submit" method="post" style="text-align:left;">
                <p>
                    <label><span id="required">*</span> Email Address: &nbsp;&nbsp;</label>
                    <input type="text" value="<?php echo $customer["emailaddress"] ?>" name="emailaddress"/><?php echo ((isset($error["emailaddress"]) && $error["emailaddress"] == 1) ? " <span id='required'> Please fill in the email address</span>" : "");
                     echo ((isset($error["emailaddress"]) && $error["emailaddress"] == 2) ? " <span id='required'> This email address is already in use</span>" : "")?>
                </p> 
                <p>
                    <label>&nbsp;&nbsp;</label>
                    <input type="submit" value="Change Email"/>
                </p>
            </form>
            </div>            
<?php
        } elseif ($_GET["action"] == "changemark") {
        
            $customer = mysqli_fetch_assoc($customers);
            $userid = $customer["uid"];
            $customerdetailssql = "SELECT * FROM userdetails WHERE userdetid='$userid'";
            $customerdetails = mysqli_fetch_assoc(mysqli_query($connect,$customerdetailssql));
            ?>
            <h2 style="text-align:left;">Change your marketing preferences</h2>
            <?php if(isset($success) == 1) {
            print_r("<p style='text-align:left' id='success'>Marketing preferences updated</p>");
            }; ?>
            <div class="table">
            <form action="customers.php?action=changemark&<?php echo (isset($_GET["uid"]) ? "uid=".$uid."&" : "") ?>form=submit" method="post" style="text-align:left;">
                <p>
                    <label>Contactable for Marketing? &nbsp;&nbsp;</label>
                    <input type="checkbox" name="marketing" <?php echo ($customerdetails['marketing'] ? "checked" : "") ?> />
                </p>  
                <p>
                    <label>&nbsp;&nbsp;</label>
                    <input type="submit" value="Change Preferences"/>
                </p>
            </form>
            </div>             
<?php
        mysqli_free_result($customers);
        mysqli_close($connect);
        } elseif ($_GET["action"] == "changedevices") {
            $customer = mysqli_fetch_assoc($customers);
            $userid = $customer["uid"];
            $customerdetailssql = "SELECT * FROM userdetails WHERE userdetid='$userid'";
            $customerdetails = mysqli_fetch_assoc(mysqli_query($connect,$customerdetailssql));
            $devices = explode(",",$customerdetails["ownerof"]);
?>
            <h2 style="text-align:left;">Change your registered devices</h2>
         <?php if(isset($success) == 1) {
            print_r("<p style='text-align:left' id='success'>Registered devices updated</p>");
            };?>
            <div class="table">
            <form action="customers.php?action=changedevices&<?php echo (isset($_GET["uid"]) ? "uid=".$uid."&" : "") ?>form=submit" method="post" style="text-align:left;">
                <p>
                    <label>Computer: &nbsp;&nbsp;</label>
                    <input type="checkbox" name="devices[]" value="computer" <?php echo (checkdevices($devices,"computer") ? "checked" : ""); ?>/>
                </p>  
                                <p>
                    <label>Laptop: &nbsp;&nbsp;</label>
                    <input type="checkbox" name="devices[]" value="laptop" <?php echo (checkdevices($devices,"laptop") ? "checked" : ""); ?>/>
                </p>  
                                <p>
                    <label>Tablet: &nbsp;&nbsp;</label>
                    <input type="checkbox" name="devices[]" value="tablet" <?php echo (checkdevices($devices,"tablet") ? "checked" : ""); ?>/>
                </p>  
                                <p>
                    <label>Smartphone: &nbsp;&nbsp;</label>
                    <input type="checkbox" name="devices[]" value="smartphone" <?php echo (checkdevices($devices,"smartphone") ? "checked" : ""); ?>/>
                </p>  
                                <p>
                    <label>Virtual Reality: &nbsp;&nbsp;</label>
                    <input type="checkbox" name="devices[]" value="VR" <?php echo (checkdevices($devices,"VR") ? "checked" : ""); ?>/>
                </p>  
                                <p>
                    <label>Gaming Consoles: &nbsp;&nbsp;</label>
                    <input type="checkbox" name="devices[]" value="console" <?php echo (checkdevices($devices,"console") ? "checked" : ""); ?>/>
                </p>                   
                <p>
                    <label>&nbsp;&nbsp;</label>
                    <input type="submit" value="Change Registered Devices"/>
                </p>
            </form>
            </div>  
<?php
        } elseif ($_GET["action"] == "addnew") {
            if(!isset($error)) {
            $marketing = null;
            $form = null;
            $email = "";
            $devices = null;
            } else {
                $devices = null;
                $marketing = null;
                if (isset($form["devices"])){
                    $devices = implode(',',$form["devices"]);
                    $devices = explode(',',$devices);
                }
                if (isset($form["marketing"])){
                    $marketing = $form["marketing"];
                }
            };
            ?>
            <h2 style="text-align:left;">Add a new customer</h2>
    <?php if(isset($success) == 1) {
            print_r("<p style='text-align:left' id='success'>Customer added to the database</p>");
            };
            if(isset($error)) {
                print_r("<p style='text-align:left' id='required'>Error: Please fill in the required fields</p>");
            };
            if(isset($error["matchingpasswords"]) == 1) {
                print_r("<p style='text-align:left' id='required'>Error: Passwords do not match</p>");
            }; ?>
            <p style="text-align:left;"><span id="required">*</span> indicates a required field</p>
            <div class="table">
            <form action="customers.php?action=addnew&form=submit" method="post" style="text-align:left;">
                <p>
                    <label><span id="required">*</span> Email Address: &nbsp;&nbsp;</label>
                    <input type="text" value="<?php echo $form["emailaddress"] ?>" name="emailaddress"/><?php echo ((isset($error["emailaddress"]) && $error["emailaddress"] == 1) ? " <span id='required'> Please fill in the email address</span>" : "");
                     echo ((isset($error["emailaddress"]) && $error["emailaddress"] == 2) ? " <span id='required'> This email address is already in use</span>" : "")?>
                </p>
                <p>
                    <label><span id="required">*</span> New Password: &nbsp;&nbsp;</label>
                    <input type="password" value="" name="password"/><?php echo ((isset($error["password"]) == 1) ? " <span id='required'> Please fill in the password</span>" : ""); ?>
                </p>  
                <p>
                    <label><span id="required">*</span> Confirm Password: &nbsp;&nbsp;</label>
                    <input type="password" value="" name="confirmpassword"/><?php echo ((isset($error["confirmpassword"]) == 1) ? " <span id='required'> Please fill in the password</span>" : ""); ?>
                </p>
                <br />
                 <p>
                    <label><span id="required">*</span> First Name(s): &nbsp;&nbsp;</label>
                    <input type="text" value="<?php echo $form["firstnames"] ?>" name="firstnames"/><?php echo ((isset($error["firstnames"]) == 1) ? " <span id='required'> Please fill in your First Name(s)</span>" : ""); ?>
                </p>
                <p>
                    <label><span id="required">*</span> Surname: &nbsp;&nbsp;</label>
                    <input type="text" value="<?php echo $form["surname"] ?>" name="surname"/><?php echo ((isset($error["surname"]) == 1) ? " <span id='required'> Please fill in your Surname</span>" : ""); ?>
                </p>
                <p>
                    <label><span id="required">*</span> First line of Address: &nbsp;&nbsp;</label>
                    <input type="text" value="<?php echo $form["firstlineadd"] ?>" name="firstlineadd"/><?php echo ((isset($error["firstlineadd"]) == 1) ? " <span id='required'> Please fill in the first line of the address</span>" : ""); ?>
                </p>
                <p>
                    <label>Second line of Address: &nbsp;&nbsp;</label>
                    <input type="text" value="<?php echo $form["secondlineadd"] ?>" name="secondlineadd"/>
                </p>
                <p>
                    <label><span id="required">*</span> Town: &nbsp;&nbsp;</label>
                    <input type="text" value="<?php echo $form["town"] ?>" name="town"/><?php echo ((isset($error["town"]) == 1) ? " <span id='required'> Please fill in the town</span>" : ""); ?>
                </p>
                <p>
                    <label><span id="required">*</span> County: &nbsp;&nbsp;</label>
                    <input type="text" value="<?php echo $form["county"] ?>" name="county"/><?php echo ((isset($error["county"]) == 1) ? " <span id='required'> Please fill in the county</span>" : ""); ?>
                </p>
                 <p>
                    <label><span id="required">*</span> Country: &nbsp;&nbsp;</label>
                    <input type="text" value="<?php echo $form["country"] ?>" name="country"/><?php echo ((isset($error["country"]) == 1) ? " <span id='required'> Please fill in the country</span>" : ""); ?>
                </p>
                <p>
                    <label><span id="required">*</span> Postal Code: &nbsp;&nbsp;</label>
                    <input type="text" value="<?php echo $form["postcode"] ?>" name="postcode"/><?php echo ((isset($error["postcode"]) == 1) ? " <span id='required'> Please fill in the Post Code</span>" : ""); ?>
                </p>
                <p>
                    <label><span id="required">*</span> Phone Number: &nbsp;&nbsp;</label>
                    <input type="text" value="<?php echo $form["contactno"] ?>" name="contactno"/><?php echo ((isset($error["contactno"]) == 1) ? " <span id='required'> Please fill in the contact number</span>" : ""); ?>
                </p>
                <p>
                    <label>Mobile Number: &nbsp;&nbsp;</label>
                    <input type="text" value="<?php echo $form["mobileno"] ?>" name="mobileno"/>
                </p>
                <br />
                 <p>
                    <label>Contactable for Marketing? &nbsp;&nbsp;</label>
                    <input type="checkbox" name="marketing" <?php echo ($marketing ? "checked" : "") ?> />
                </p>
                <br />
                <p>
                    <label>Computer: &nbsp;&nbsp;</label>
                    <input type="checkbox" name="devices[]" value="computer" <?php echo (checkdevices($devices,"computer") ? "checked" : ""); ?>/>
                </p>  
                                <p>
                    <label>Laptop: &nbsp;&nbsp;</label>
                    <input type="checkbox" name="devices[]" value="laptop" <?php echo (checkdevices($devices,"laptop") ? "checked" : ""); ?>/>
                </p>  
                                <p>
                    <label>Tablet: &nbsp;&nbsp;</label>
                    <input type="checkbox" name="devices[]" value="tablet" <?php echo (checkdevices($devices,"tablet") ? "checked" : ""); ?>/>
                </p>  
                                <p>
                    <label>Smartphone: &nbsp;&nbsp;</label>
                    <input type="checkbox" name="devices[]" value="smartphone" <?php echo (checkdevices($devices,"smartphone") ? "checked" : ""); ?>/>
                </p>  
                                <p>
                    <label>Virtual Reality: &nbsp;&nbsp;</label>
                    <input type="checkbox" name="devices[]" value="VR" <?php echo (checkdevices($devices,"VR") ? "checked" : ""); ?>/>
                </p>  
                                <p>
                    <label>Gaming Consoles: &nbsp;&nbsp;</label>
                    <input type="checkbox" name="devices[]" value="console" <?php echo (checkdevices($devices,"console") ? "checked" : ""); ?>/>
                </p>  
                <p>
                    <label>&nbsp;&nbsp;</label>
                    <input type="submit" value="Add New Customer"/>
                </p>
            </form>
            </div> 
     <?php }; ?>
        </div>
    </div>
</div>    
</body>
</html>