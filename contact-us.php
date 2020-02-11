<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Interelay Solutions - Contact Us</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700|Rubik:400,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">  
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link href="css/material-components-web.min.css" rel="stylesheet">
        <script src="js/material-components-web.min.js"></script>
        <style>
            label {
                font-family: "Montserrat";
                font-size:14px;
            }

            input {
                font-family: "Arial";
            }
            
            button {
                background-color:#343f7c !important;
            }

            form table {
                border: 1px solid lightgray;
                border-radius: 5px;
                padding:20px;
                background-color: #f0f0f0;
                max-width: 600px;
                width:100%;
                margin-right: auto;
            }

            form button {
                padding:7px;
                border-radius: 5px;
                border:1px solid grey;
                background-color: #343f7c !important;
            }

            form label {
                color: black;
            }

            input, textarea {
                border-radius: 5px;
                border:1px solid black;
                padding:10px;
                background-color: black;
                color: lightgray;
                font-family: "Arial";
                font-size: 14px;
            }

            button:focus, button:active {
                background-color:midnightblue !important;
            }

            tr {
                vertical-align: middle;
            }

            .small-island {
                width: 95%;
                background-color: white;
                margin-left: auto;
                margin-right: auto;
                display: block !important;
                min-width: 370px;
                z-index: 2;
                box-shadow: none !important;
                border: none !important;
            }

            form {
                display:block !important;
            }

            h2 {
                color:black;
                padding-top:0px;
                font-family: "Montserrat";
                margin-top:0px;
            }
            p {
                font-size: 14px;
            }
        </style>
    </head>
    <body style="background-image: url('images/serverroom-slide1-v2.jpg');">
    <div class="logolinkbox" style="position:fixed; left:0px; top:0px; width:310px; height:77px;float: left;z-index:1"><a style="position:fixed; left:0px; top:0px; width:310px; height:77px;float: left;z-index:1" href="./" >&nbsp;</a></div>
    <div id="logobar" class="logobar" style="margin-bottom:0px;"><img src="images/logo.png" style="float:left; height:60px; margin-right:7px; margin-left:7px;"/><h1 style="color:white; float:left; margin:14px; font-family: 'Montserrat'">Interelay</h1> <p style="color:white; position:relative; margin:14px; font-family: 'Montserrat'; top: 25px;left: -11px;">Solutions</p></div>
        <div id="navbar" class="navbar" style="float:none;z-index:1;">
            <button class="mdc-button mdc-button--raised" style="margin-left:10px; visibility:hidden !important;"><a href="#top">Return to Top</a></button> <div id="loginpane" style="float:right;"> <!--<a href="login.php">Login and book an appointment</a> | ---><button class="mdc-button mdc-button--raised" style="margin-right:10px;top:-7px;"><a href="/osTicket/">Support Helpdesk</a></button> 
			
			
			</div></div>
        <?php 

//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
require_once "vendor/autoload.php";
require_once "/../credentials/creds.php";

//PHPMailer Object
$mail = new PHPMailer;

//From email address and name
$mail->setFrom("support@interelay.com","Web Query");
$mail->Subject = "Testing";

//To address and name
$mail->addAddress("support@interelay.com"); //Recipient name is optional

//Address to which recipient will reply
$mail->addReplyTo("noreply@interelay.com", "Reply");

//Send HTML or Plain Text email
$mail->isHTML(true);

$mail->Subject = "Subject Text";
$mail->Body = "Mail body in HTML";
$mail->AltBody = "This is the plain text version of the email content";


$mail->SMTPDebug = 0;
$mail->IsSMTP(); 
$mail->Username = $theUser;  
$mail->Password = $thePass;                                   // Set mailer to use SMTP
$mail->Host = 'mail.interelay.com';                 // Specify main and backup server
$mail->Port = 587;                                    // Set the SMTP port
$mail->SMTPAuth = true;                               // Enable SMTP authentication


// SMTP password
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   

$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);

if(!$mail->send()) 
{
    echo "Mailer Error: " . $mail->ErrorInfo;
} 
else 
{
    echo "Message has been sent successfully";
}


        $success = "";
        if (isset($_POST["name"])) {
            foreach ($_POST as $key => $value) {
                $_POST[$key] = htmlentities($value);
            }            
            // the message
            $msg = "Name: ".$_POST["name"]."\n
            Phone Number: ".$_POST["number"]."\n
            Email: ".$_POST["email"]."\n
            Message: ".$_POST["message"];
            $headers = 'From: support@interelay.com' . "\r\n" .
            'Reply-To: '.$_POST["email"]."\r\n". 'X-Mailer: PHP/';
            // send email
            if (mail("support@interelay.com","Website Query",$msg, $headers)) {
                $success = "<p style='color:green;font-weight:bold;float: right;position: relative;top: 30px;margin-left: -300px;'>Message Sent!</p>";
            } else {
                $success = "<p style='color:darkred;font-weight:bold;float: right;position: relative;top: 30px;margin-left: -300px;'>Error: Please contact the admininstrator</p>";
            };


        } 
        ?>
        <div>
            <div class="island small-island">
            <header>
                <h2>Contact Us</h1>
            </header>
            <section>
                <p>
                    Use the form below to send us a message and we'll aim to get back to you within 24 hours.
                </p>
            </section>
            <div>
        <form method="POST" action="contact-us">
            <table>
                <tr>
                    <td><label>Name:</label></td>
                    <td><input type="text" required placeholder="Please enter your name" name="name" style="width:200px;" /></td>  
                </tr>
                <tr>
                  <td><label>Phone Number:</label></td>
                    <td><input type="text" placeholder="Please enter your number" name="number" pattern="[0-9]{11,12}" style="width:200px;" /></td> 
                </tr>
                <tr>
                    <td><label>Email Address:</label></td>
                    <td><input type="email" required placeholder="Please enter your email" name="email"  style="width:200px;" /></td>
                </tr>
                <tr style="vertical-align: top;">
                    <td><label>Message:</label></td>
                    <td><textarea name="message" style="height:200px;max-width:500px;width:94%"></textarea></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right;"><button class="mdc-button mdc-button--raised" type="submit" style="font-size: 12px;">Send Message</button>
                    <?php echo $success ?>
                </td>
                </tr>
            </table>
        </form>
        </div>
        </div>
        
        </div>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        
        <script src="" async defer></script>
    </body>
</html>