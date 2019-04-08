<?php use Illuminate\Support\Facades\DB; ?>
<?php use storage\framework\sessions; ?>
<!DOCTYPE html>
<html>

<head>
    
    <meta name="keywords" content="HTML">
    <meta charset="utf-8">
    <link rel="icon" href="/frontend/images/5121.png">
   
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
    <meta name="HandheldFriendly" content="True">
  

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets\css\reset.css">
    <link rel="stylesheet" type="text/css" href="assets\css\style.css?time=<?= time(); ?>">



    
       <!-- set the page title -->
    <title>@yield('title')</title>
	
</head>

	<body @yield('class')>
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
                                    <li><a href="/faq">FAQ</a></li>
                                    
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
        <!-- contain main informative part of the site -->
    	@yield('content')

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<script>
    
    jQuery(document).ready(function($) {
        $(document).on('click', '.upgrade', function(event) {
            event.preventDefault();
                $('.upgrade_account').show();
            /* Act on the event */
        });

        $(document).on('click', '.close_upgrade', function(event) {
            event.preventDefault();
                $('.upgrade_account').hide();
            /* Act on the event */
        });

        // $(document).on('click', 'a.upgrade_memebership', function(event) {
        //     event.preventDefault();
        //     if($(this).hasClass('disabled')){
        //         $(this).off('click');                            
        //     }else{
        //         var membership_name = $(this).attr('data-name');
        //         var membership_cost = $(this).attr('data-price');
        //         var membership_id = $(this).attr('data-id');

        //         $('input[name=membership_name]').val(membership_name);
        //         $('input[name=membership_cost]').val(membership_cost);
        //         $('input[name=membership_id]').val(membership_id);
        //         $('.pay_option_div').show();
        //     }
        // });

       


        $( "input[name='payment_method_']" ).on( "click", function() {
          var payment_mehtod = $( "input:checked" ).val()
          // console.log(payment_mehtod);
            if(payment_mehtod == 'stripe'){


                $('input[name="number"]').attr('required', 'true');
                $('input[name="cvc"]').attr('required', 'true');
                $('input[name="exp_month"]').attr('required', 'true');
                $('input[name="exp_year"]').attr('required', 'true');
                $('input[name="client_name"]').attr('required', 'true');
                
                console.log('testing');
                $(".strip_data").show();

                $(".payment_stripe").val('Pay');
                
                Stripe.setPublishableKey('pk_test_fu9pPdhwW3qilZxpvQ1UjF24');

                var $form = $("#form_data");

                $form.submit(function(event) {
                    
                    event.preventDefault();
                  $('#charge-error').addClass('hidden');
                  $form.find('input[type="submit"]').prop('disabled', true);
                    // console.log("loop");
                  Stripe.card.createToken({
                    number: $('input[name="number"]').val(),
                    cvc: $('input[name="cvc"]').val(),
                    exp_month: $('input[name="exp_month"]').val(),
                    exp_year: $('input[name="exp_year"]').val(),
                    name: $('input[name="client_name"]').val()
                  }, stripeResponseHandler);
                  return false;
                });

                function stripeResponseHandler(status, response){
                  // console.log(response.error);
                  if(response.error){
                    $('#charge-error').removeClass('hidden');
                    $('#charge-error').text(response.error.message);
                    // console.log("fail");
                    $form.find('input[type="submit"]').prop('disabled', false);
                  }
                  else{
                      var token = response.id;
                      $form.append($('<input type="hidden" name="stripe_token" >').val(token));

                    // console.log("testing");

                      $form.get(0).submit();

                  }
                }

            }else{
                $('input[name="number"]').removeAttr('required');
                $('input[name="cvc"]').removeAttr('required');
                $('input[name="exp_month"]').removeAttr('required');
                $('input[name="exp_year"]').removeAttr('required');
                $('input[name="client_name"]').removeAttr('required');
                $(".strip_data").hide();

                $(".payment_stripe").val('Proceed to checkout');            
            }
        
        });

      $('.disabled').find('div.freebtn form input[type="submit"]').attr('disabled','disabled');
      $('.disabled').find('div.freebtn form input[type="submit"]').css('cursor', 'not-allowed');

    });

</script>

        </body>
        </html>
