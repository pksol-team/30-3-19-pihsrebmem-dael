<?php use Illuminate\Support\Facades\DB; ?>
<?php use storage\framework\sessions; ?>
<!DOCTYPE html>
<html>

<head>
    <!-- set the encoding of your site -->
    <meta charset="utf-8">
    <link rel="icon" href="/frontend/images/5121.png">
    <!-- set the viewport width and initial-scale on mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- set the HandheldFriendly -->
    <meta name="HandheldFriendly" content="True">
    <!-- set the description -->






    
       <!-- set the page title -->
    <title>@yield('title')</title>
	
</head>

	<body>

        <!-- contain main informative part of the site -->
    	@yield('content')


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

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
    });

</script>

        </body>
        </html>
