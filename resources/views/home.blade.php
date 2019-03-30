<!DOCTYPE html>
<html lang="en">
<head>
    <title>LEADS-MEMBERSHIP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets\css\reset.css">
    <link rel="stylesheet" type="text/css" href="assets\css\style.css">
</head>

<body data-spy="scroll" data-offset="0" data-target="#navigation">


    <header id="main-header">
    <div class="container-fluid top">
        <div class="container">
            <div class="row">
                <div id="top1" class="col-6 col-sm-6 col-md-6 col-lg-6">
                    <ul>
                        <li><a href=""><i class="fab fa-facebook-square"></i></a></li>
                        <li><a href=""><i class="fab fa-twitter-square"></i></a></li>
                        <li><a href=""><i class="fab fa-google-plus-square"></i></a></li>
                        <li><a href=""><i class="fab fa-youtube-square"></i></a></li>
                    </ul>
                </div>
                <div id="top2" class="col-6 col-sm-6 col-md-6 col-lg-6">    
                    <p>Call Now: +1 (602) 399 7964</p>
                </div>      
            </div>
        </div>  
    </div>
    <div class="navigation">
        <div class="container-fluid">
            <div class="container">
                <div class="row">
                    <div id="logo" class="col-4 col-sm-4 col-md-4 col-lg-4">
                        <img src="assets\images\logo.png">
                    </div>
                    <div id="nav" class="col-8 col-sm-8 col-md-8 col-lg-8s">
                        <nav>
                            <div class="toggle">
                                <div class="menu"><i class="fas fa-bars"></i>
                                </div>
                            </div>
                            <ul>
                                <li><a href="/">Home</a></li>
                                @if (!Auth::user())
                                    <li><a href="/signin">Login</a></li>
                                    <li><a href="/signup"><button>BECOME A MEMBER</button></a></li>
                                @else
                                <li><a href="{{ url('/profile') }}">Profile</a></li>
                                <li><a href="{{ url('/logout') }}">Logout</a></li>
                                @endif
                            </ul>
                        </nav>
                            <script
                            src="https://code.jquery.com/jquery-3.3.1.js"></script>
                            <script type="text/javascript">
                            $(document).ready(function() {
                                $('.menu').click(function() {
                                    
                                    $('ul').toggleClass('active');
                                });
                            });
                            </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </header>
    
    <div class="body_wrapper">
        <div class="container-fluid">
            <div class="banner">
                <div class="row">
                    <div class="col-lg-6">
                        <h2>Automated Mailing System
                        + Contact Over 100,000 Prospects Every Month</h2>
                        <p>This is Photoshop's version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet nibh vulputate a 
                        amet mauris. Morbi accumsan ipsum velit. </p>
                        <div class="buttonban">
                            <a href=""><button>LOGIN</button></a>
                            <span><a href=""><button>SIGNUP</button></a></span>
                        </div>

                    </div>
                </div>
            </div>
            <div class="mid_banner">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h2>Our Features</h2>
                        </div>
                        <div class="col-12">
                            <h5>See What We Do</h5>
                        </div>
                        <div class="col-lg-4">
                            <div class="borderimage">
                                <div class="row">
                                    <div class="col-12">
                                        <img src="assets/images/speaker.png">
                                    </div>
                                    <div class="col-12">
                                        <h3>Updated Daily</h3>
                                    </div>
                                    <div class="col-12">
                                        <p>This is Photoshop's version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="borderimage1">
                                <div class="row">
                                    <div class="col-12">
                                        <img src="assets/images/flower.png">
                                    </div>
                                    <div class="col-12">
                                        <h3>Advertising Whole</h3>
                                    </div>
                                    <div class="col-12">
                                        <p>This is Photoshop's version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="borderimage">
                                <div class="row">
                                    <div class="col-12">
                                        <img src="assets/images/plus.png">
                                    </div>
                                    <div class="col-12">
                                        <h3>Automatic Campaigns</h3>
                                    </div>
                                    <div class="col-12">
                                        <p>This is Photoshop's version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="campus">
                <div class="row">
                    <div class="col-lg-6">
                        <h1>Set Your Campaign on “Automatic” and Let Our System Do The Work For You</h1>
                        <p>This is Photoshop's version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt auctor a ornare odio. </p>
                        <div class="buttonban1">
                            <a href=""><button>LOGIN</button></a>
                            <span><a href=""><button>SIGNUP</button></a></span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <img src="assets/images/mail.png">
                    </div>
                </div>
            </div>
            <div class="container company">
                <div class="row">
                    <div class="col-lg-6">
                        <img src="assets/images/graph.png">
                    </div>
                    <div class="col-lg-6">
                        <h1>See Our Company Overview & About Us</h1>
                        <ul>
                            <li class="red">This is Photoshop's version  of Lorem Ipsum. </li>
                            <li class="yellow">Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudi </li>
                            <li class="green">Lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagi </li>
                            <li class="blue">Sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus </li>
                            <li class="purple">A sit amet mauris. Morbi accumsan ipsum velit. Nam nec tellus </li>
                        </ul>
                        <div class="buttonban3">
                            <a href=""><button>LOGIN</button></a>
                            <span><a href=""><button>SIGNUP</button></a></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="contactus">
                <div class="row input">
                    <div class="col-12">
                        <h2>CONTACT US</h2>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-lg-4"></div>
                            <div class="col-lg-4">
                            <input type="email" name="email" placeholder="YOUR EMAIL ADDRESS">
                            <input type="text" name="email" placeholder="FULL NAME">
                            <textarea>MESSAGE</textarea>
                            <div class="row">
                                <div class="col-12 but">
                                    <a href=""><button>SEND</button></a>
                                </div>
                            </div>
                            </div>
                            <div class="col-lg-4"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="deposit">
                <div class="row">
                    <div class="col-12">
                        <h1>Let’s Start With Last Deposit  $20</h1>
                        <div class="buttonban2">
                            <a href=""><button>LOGIN</button></a>
                            <span><a href=""><button>SIGNUP</button></a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <img src="assets/images/logo2.png">
                    <p>This is Photoshop's version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor,</p>
                </div>
                <div class="col-lg-4 mid">
                    <h5>CONTACT</h5>
                    <p>Address: 265 Ponce De Leon Ave NE,<br> Suite 3217 Atlanta, Georgia 30308, USA<br>
                    Tel# +1 123 456 78971<br>
                    Email: info@domainname.com</p>
                    <div id="top1">
                    <ul>
                        <li><a href=""><i class="fab fa-facebook-square"></i></a></li>
                        <li><a href=""><i class="fab fa-twitter-square"></i></a></li>
                        <li><a href=""><i class="fab fa-google-plus-square"></i></a></li>
                        <li><a href=""><i class="fab fa-youtube-square"></i></a></li>
                    </ul>
                    </div>
                </div>
                <div class="col-lg-4 mid">
                    <h5>You can trust us</h5>
                    <p>This is Photoshop's version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. </p>
                </div>
            </div>
        </div>
    </footer>


    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script type="text/javascript">

  $(function() {
    var body = $("body");
    $(window).scroll(function() {    
        var scroll = $(window).scrollTop();
    
        if (scroll >= 300) {
            body.removeClass('stickyheader').addClass("stickyheader");
        } else {
            body.removeClass("stickyheader");
        }
    });
});
</script>
</body>
</html>
