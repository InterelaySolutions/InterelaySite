<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Form Testing</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700|Rubik:400,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">  
        <link rel="stylesheet" type="text/css" href="/site/css/main.css">
        <link href="css/material-components-web.min.css" rel="stylesheet">
        <script src="js/material-components-web.min.js"></script>
        <style>
            label {
                font-family: "Arial";
                font-weight: bold;
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
                padding:10px;
                background-color: #f0f0f0;
            }

            form button {
                padding:7px;
                border-radius: 5px;
                border:1px solid grey;
                background-color: lightgrey !important;
            }

            form label {
                color: black;
            }

            input {
                border-radius: 5px;
                border:1px solid black;
                padding:10px;
            }

            form button:focus {
                background-color: darkgray !important;
            }
        </style>
    </head>
    <body style="background-image: url('/site/images/serverroom-slide1-v2.jpg');">
    <div class="logolinkbox" style="position:fixed; left:0px; top:0px; width:310px; height:77px;float: left;z-index:1"><a style="position:fixed; left:0px; top:0px; width:310px; height:77px;float: left;z-index:1" href="./" >&nbsp;</a></div>
    <div id="logobar" class="logobar" style="margin-bottom:0px;"><img src="/site/images/logo.png" style="float:left; height:60px; margin-right:7px; margin-left:7px;"/><h1 style="color:white; float:left; margin:14px; font-family: 'Montserrat'">Interelay</h1> <p style="color:white; position:relative; margin:14px; font-family: 'Montserrat'; top: 25px;left: -11px;">Solutions</p></div>
        <div id="navbar" class="navbar" style="float:none;">
            <button class="mdc-button mdc-button--raised" style="margin-left:10px;"><a href="#top">Return to Top</a></button> <button class="mdc-button mdc-button--raised"><a href="#aboutus">About Us</a></button> <button class="mdc-button mdc-button--raised"><a href="#contactus">Contact Us</a></button>   <div id="loginpane" style="float:right;"> <!--<a href="login.php">Login and book an appointment</a> | ---><button class="mdc-button mdc-button--raised" style="margin-right:10px;top:-7px;"><a href="/osTicket/">Support Helpdesk</a></button> 
			
			
			</div></div>
        <?php isset($_POST["name"]) ? print_r($_POST) : "" ?>
        <div>
        <form method="POST" action="form_test">
            <table>
                <tr>
                    <td><label>Name:</label></td>
                    <td><input type="text" required placeholder="Please enter your name" name="name" /></td>  
                </tr>
                <tr>
                  <td><label>Phone Number:</label></td>
                    <td><input type="text" required placeholder="Please enter your number" name="number" pattern="[0-9]{11,12}" /></td> 
                </tr>
                <tr>
                    <td><label>Date of Birth:</label></td>
                    <td><input type="date" required placeholder="Please enter your name" name="dob" /></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right;"><button type="submit" style="font-size: 12px; font-weight:bold;">SUBMIT</button></td>
                </tr>
            </table>
        </form>
        </div>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        
        <script src="" async defer></script>
    </body>
</html>