<?php
session_start();
$loggedout = 0;
if (isset($_GET["action"]) && $_GET["action"] == "logout") {
    session_unset();
    session_destroy();
    $loggedout = 1;
    $error  = "You have been logged out";
};
$loggedin = 0;

if (isset($_GET["error"]) && $_GET["error"] == 1){
    $error = "You have not entered the correct email or password, please try again.";
} elseif(isset($_GET["error"]) && $_GET["error"] == 2){
    $error = "You do not have permission to access this page";
};

if (!isset($_SESSION["loggedin"])) {
    $loggedin = 0;
} else {
    $loggedin = 1;
};
/*
LOGIN - ACTION - LOGOUT
if url action is to log out, remove all session data and set loggedout variable to 1, display "logged out" message further down the page */

/* If user is logged in with a session variable set, then redurect to customer portal */

if ($loggedin == 1) {
   header("Location: customerportal.php"); /* Redirect browser */
exit();
}

/* otherwise display login form and proceed as normal */
 ?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="main.css">
        <title>Interelay Solutions - Computer Repairs, Managed IT Services, Network Administration and Anti-Virus Services in Kent</title>
        <script src="js/jquery.js"></script>
        <script>
            // handle links with @href started with '#' only
            $(document).on('click', 'a[href^="#"]', function(e) {
                // target element id
                var id = $(this).attr('href');

                // target element
                var $id = $(id);
                if ($id.length === 0) {
                    return;
                }
    
                // prevent standard hash navigation (avoid blinking in IE)
                e.preventDefault();
    
                //  top position relative to the document
                var pos = $id.offset().top;
    
                // animated top scrolling
                $('body, html').animate({scrollTop: pos});
            });
  </script>
    </head>
    <body>
<?php require "includes/navbar.php"; ?>
        <!-- Login Slide - Will have a centered div island with login form -->
        <div class="general_slide">
            <a class="smoothScroll" id="top"></a>
            <div style="width:70%;margin: 0 auto; text-align:center;">
                <div class="island">
                <?php if (isset($error)) { echo "<p style=\"color:red;font-weight:bold\">".$error."</p>"; }; ?>
                    <p>Please enter your credentials:</p>
                <form name="loginform" action="loginprocess.php" method="post" style="">
                    <p>
                        <label>Email:</label>
                        <input name="username" type="text">
                    </p>
                    <p>
                        <label>Password:</label> 
                        <input name="password" type="password">
                    </p>
                    <p>
                        <label>Keep me logged in?</label>
                        <input name="savedets" type="checkbox" >
                    </p>
                    <p><label>&nbsp;</label><input name="submit" type="submit" value="Login">
                    </p>
                </form>
                <a href="forgotpassword.php">Forgot Password?</a>
                </div>
            </div>
        </div>
        <!-- Slide #4 -- Contact Us Footer -->
        <div id="footer">
            <a class="smoothScroll" id="contactus"></a>
            <div class="container"  style="color:grey; text-align:center;">
                <h2 style="text-align:left;">Contact us now for an appointment</h2>
                <div id="footer_left"><h4>Address:</h4>
                1 Lion Field<br/>
                Ospringe<br />
                Faversham<br />
                Kent<br />
                ME13 7PP<br /></div>
                <div id="footer_right">
                <h4>Call Us:</h4>
                07939 237669<br />
                01795 534620<br />
                <a href="skype://interelay">SkypeCall</a><br />
                <br />
                <h4>Email Us:</h4>
                <a href="mailto:book@interelay.com">book@interelay.com</a>
                </div>
                </div>
            <div style="clear:both; text-align:center; font-size:x-small;">All Code and Images are Copyright Interelay Solutions - 2017</div>
        </div> 
        <script src="js/stickyfill.min.js"></script>
        <script>
            var sidebar = document.getElementById('navbar');
Stickyfill.add(sidebar);
        </script>
    </body>
</html>