<html><head>
        <title>Kent Software Development, Computer Repairs, Managed IT Services & Network Administration - Interelay Solutions</title>
         	
        <link rel='icon' href='images/interelay_small.ico' type='image/x-icon'>
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700|Rubik:400,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">  
        <link href="https://fonts.googleapis.com/css?family=Raleway|Oxygen:300|Roboto:300|Sintony&display=swap" rel="stylesheet"> 
        <script src="js/jquery.js"></script>
        <link href="css/material-components-web.min.css" rel="stylesheet">
        <script src="js/material-components-web.min.js"></script>
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="canonical" href="http://www.interelay.com" />
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

                if (id == "#top") {
                    pos = pos -120;
                }
    
                // animated top scrolling
                $('body, html').animate({scrollTop: pos});
            });
  </script>
  <style>
  h1 {
      font-family: 'Roboto';
      font-size: 32px;
  }
  
  </style>
    </head>
    <body style="background-color:black;">
    <?php include 'includes/header.php' ?>
        <!-- Slide #1 -- Three Divs aligned into three columns -->
        <div class="slide" style="background-image: url('images/serverroom-slide1-v2.jpg'); background-repeat:no-repeat; background-size:cover; position:relative; top:-43px; padding-bottom:50px;">
            <a class="smoothScroll" id="top"></a>
<br><br>
            <div id="three_way_container">
                <section>
                    <div class="equal-div33"><img src="images/softdev.png" style="width:100%;max-width:150px; height:auto;">
                    <header>
                        <h3>Bespoke software development</h3>
                    </header>
                    <article>
                        <p>We have a proven track record to develop custom software solutions for your company.  We perform a design brief for you which outlines the features of your solution and how to integrate it into your regular workflows.  We can create a full-stack custom solution for you, using proven technologies (PHP, Python, CSS, SQL, Javascript/JQuery)</p>
                    </article>
                    </div>
                    <div class="equal-div33"><img src="images/website-design.png" style="width:100%; max-width:150px; height:auto;">
                    <header><h3>IT Services Consultancy</h3></header>
                    <article>
                        <p>Not sure how you want to get from A to B?  We can help provide consulations which will provide an informed pathway to get you to your goals.  IT project management can be tricky and overly costly, let us put you on the right track to avoid redevelopment or recreation at a later stage.</p>
                    </article>
                </div>
                <div class="equal-div33"><img src="images/Circle-icons-computer.png" style="width:100%;max-width:150px; height:auto;">
                <header><h3>Hardware and Software Challenges</h3></header>
                <article>
                    <p>Our IT support technicians have over ten years of experience in handling software and hardware faults.  Performing ad-hoc repairs and ensure a resolution before leaving the premises.  We provide a call-out service for home users and a contracted service for regular maintenence of business infrastructure (Hardware and Software based).  Contact us now too see what managed IT solutions we can provide for you, or if you're a customer, please call now and book an appointment!</p>
                </article>
            </div>
            <div class="equal-div33"><img src="images/network.png" style="width:100%;max-width:150px; height:auto;">
            <h3>Providing Networking and Administrative services</h3><p>Our technicians have considerable experience in providing Network Administrative assistance, ranging from Active Directory to Windows Server to Linux.  We can provide IT management solutions for your business, removing the need to manage your network infrastructure within the company, saving you time, money and resources.</p>
        </div>
                </section>
            </div>
        </div>
        <!-- Slide #2 -- Side by Side Divs arranged in a stacked format -->
        <div class="slide_inverted" style="background-image: url('images/slide2_v2.jpg'); background-repeat:no-repeat; background-size:cover;top:-29px;position: relative; padding-top:0px; padding-bottom:40px; margin-top:-30px">
            <div class="container">
                <section>
                    <article>
                    <h2>We also offer...</h2>
                        <div class="slide2_right">
                            <h2>Data Recovery</h2>
                            <p>Our extensive experience with data recovery tools gives our technicians a unique insight how to effectively recover data.  With cloud storage becoming increasingly popular, we provide consultancy services to ensure you find the right cloud service to meet your companies needs.</p>
                        </div> 
                    </article>
                    <div class="slide2_left" style=""><img src="images/data_recovery.png" style="width:100%; max-width:150px; height:auto;"></div>
                    <div class="slide2_right">
                        <article>
                            <h2>Customised PCs, built by us, for you</h2>
                            <p>We provide a hardware-requirements consultancy service, where we will assess your needs and build a computer specifically tailored to your needs.  Our PCs come with a one-year warranty and free telephone customer support, helping you every step of the way, making sure you're happy with the hardware.</p>
                        </article>
                    </div>
                    <div class="slide2_left"><img src="images/custompc.png" style="width:100%; max-width:150px; height:auto;"></div>
                    <div class="slide2_right">
                        <article>
                            <h2>Complete website app solution</h2>
                            <p>Need a website that has specific requirements? Need a custom performance driven site that is in-line with your companies aims? Then look no further!  We provide a complete website design and creation experience, we will integrate any platform or app into your website and provide a website tailored to your specific needs.  We will provide a design brief before any work is carried out and work with you to create the exact website you need.</p>
                        </article>
                    </div>
                    <div class="slide2_left"><img src="images/Circle-icons-browser.png" style="width:100%; max-width:150px; height:auto;"></div>
                </section>
            </div>
        </div>
        <!-- Dummy Div to create a precise anchor for the About Us Link -->
        <div id="aboutus" style="margin:0px; padding:0px; font-size:0px; position:relative; top:-20px;"></div>
        <!-- Slide #3 -- Information Dump Slide -->
        <div class="slide" style="background-image: url('images/slide3_v1.jpg'); background-repeat:no-repeat; background-size:cover; margin:0px; position: relative;top: -47px;padding-top: 10px;margin-top: 20px; padding-bottom:100px; text-shadow: 1px 1px 3px gray;">
            <div class="container">
                <section>
                    <h2 style="text-align:left;color:white">We have been providing services to community for over 10 years</h2>
                    <div class="slide3">
                        <article>
                            <header><h2>Our Reputation</h2></header>
                            For the past ten years, we have been providing cost-effective solutions to the local community, providing call out services and fixing IT problems.  Ranging from virus removal to computer maintence, we provide an adapatable and flexible service to you and pride ourselves on doing our job well.  
                        </article>
                    </div>
                    <div class="slide3">
                        <article>
                            <header><h2>Call out service</h2></header>
                            One of our most unique features is our call out service, we will come to you, at a time of your choosing.  We will come to you after work hours if required to ensure no disruption to your daily life.  Call us now to arrange an appointment to come to you and in 90% of all cases, we will provide a fix on-site.  Even if we don't, we will take your computer away, fix it on our premises and deliver it back to you.
                        </article>
                    </div>
                    <div class="slide3">
                        <article>
                            <header><h2>No Fix, No Fee!</h2></header>
                            If we cannot provide a fix to your computer,we will not charge you for our services, this means there is no risk to you and proves our ability to provide a solution to any situation or problem.  We are proud to say that in the vast majority of cases, we do provide an effective solution, but even if we can't there is no risk to you.
                        </article>
                    </div>
                </section>
            </div>
        </div>
        <!-- Slide #4 -- Contact Us Footer -->
        <div id="footer" style="position:relative; top:-46px;">
        <footer>
            <a class="smoothScroll" id="contactus"></a>
            <div class="container" style="color:grey; text-align:center;">
                <h2 style="text-align:left;">Contact us now for an consultation</h2>
                <div id="footer_left">
                    <h4>Address:</h4>
                    4 Hamond Hill<br>
                    Chatham<br>
                    Kent<br>
                    ME4 6AP<br>
                </div>
                <div id="footer_right">
                    <h4>Call Us:</h4>
                    07999 047488<br>
                    01634 815500<br>
                    <br>
                    <h4>Email Us:</h4>
                    <a href="contact-us" style="color: grey; text-decoration:underline">Send a Message</a>
                </div>
                </div>
            <div style="clear:both; text-align:center; font-size:x-small;">
            All Code and Images are Copyright Interelay Solutions - <?php echo date("Y") ?>
            </div>
        </footer>
        </div> 
        <script src="js/stickyfill.min.js"></script>
        <script>
            var sidebar = document.getElementById('navbar');
Stickyfill.add(sidebar);
        </script>
</body></html>