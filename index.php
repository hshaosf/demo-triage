<?php
if(isset($_REQUEST['load'])){
	include('settings.local.php');
	include('src/triage.php');

	$t = new Triage($settings);
	$t->exec();
}else{
?><!doctype html>
<html lang="en">
  <head>
    <title>Triage Demo</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/triage.css">
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-5LB8RDN');</script>
  </head>
  <body>
  	<div class="container">
    	<h1>Triage Demo</h1>
    </div>
    <div class="container" id="sr"></div>
  	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    <script>
    	window.setInterval(function(){
    		$.ajax('index.php?load=1')
			  .done(function(data) {
			    $('#sr').prepend('<div class="row"><div class="col-lg-12">'+data+'</div></div>'); 
			  });
				   		
    	}, 5000);
    </script>
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5LB8RDN" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  </body>
</html>
<?php	
}

