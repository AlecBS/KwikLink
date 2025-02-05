<?php
$gloLoginRequired = false;
if (!isset($gloConnected)):
	require('wtk/wtkLogin.php');
endif;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>KwikLink</title>
	<link rel="stylesheet" href="/wtk/css/materialIcons.css"/>
	<link rel="stylesheet" href="/wtk/css/materialize.min.css">
	<link rel="stylesheet" href="/wtk/css/wtkBlue.css">
	<link rel="stylesheet" href="/wtk/css/wtkLight.css">
	<link rel="stylesheet" href="/wtk/css/wtkGlobal.css">
	<link rel="shortcut icon" href="/imgs/favicon/favicon.ico" type="image/x-icon" />
	<link rel="apple-touch-icon" href="/imgs/favicon/apple-touch-icon.png" />
	<link rel="apple-touch-icon" sizes="57x57" href="/imgs/favicon/apple-touch-icon-57x57.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="/imgs/favicon/apple-touch-icon-72x72.png" />
	<link rel="apple-touch-icon" sizes="76x76" href="/imgs/favicon/apple-touch-icon-76x76.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="/imgs/favicon/apple-touch-icon-114x114.png" />
	<link rel="apple-touch-icon" sizes="120x120" href="/imgs/favicon/apple-touch-icon-120x120.png" />
	<link rel="apple-touch-icon" sizes="144x144" href="/imgs/favicon/apple-touch-icon-144x144.png" />
	<link rel="apple-touch-icon" sizes="152x152" href="/imgs/favicon/apple-touch-icon-152x152.png" />
	<script type="text/javascript" src="/wtk/js/wtkUtils.js" defer></script>
	<script type="text/javascript" src="/wtk/js/jquery.min.js" defer></script>
	<script type="text/javascript" src="/wtk/js/materialize.min.js" defer></script>
	<script type="text/javascript" src="/wtk/js/wtkPaths.js" defer></script>
	<script type="text/javascript" src="/wtk/js/chart382.min.js" defer></script>
	<script type="text/javascript" src="/wtk/js/wtkColors.js" defer></script>
</head>
<body class="bg-second" onload="JavaScript:wtkStartMaterializeCSS()">
	<div id="mainPage"><br>
		<div class="row"><div class="col m6 offset-m3 s12">
            <br><br>
			<div class="card b-shadow">
				<div class="card-content"><br>
    				<h2>Welcome to KwikLink!</h2>
                    <br>
                    <p>The marketing website will be built within the next week or two.
                    Meanwhile feel free to create a free account, build your
                    KwikLink card, and start sharing it!</p>
                    <p>Create an account or login
                        <a href="/login/" class="btn b-shadow waves-effect waves-light">Here</a>
                    </p>
				</div>
				<div class="card-action">
					<p>This site was built in only 18.7 hours using
						<a href="https://wizardstoolkit.com/" target="_blank" style="text-transform:initial;color:#343be6">Wizard&rsquo;s Toolkit</a></p>
				</div>
			</div>
		</div></div>
	</div>
	<!-- preloader -->
	<div id="loaderDiv1" class="wrapper-load active">
		<div id="loaderDiv2" class="preloader-wrapper big">
			<div class="spinner-layer spinner-custom">
				<div class="circle-clipper left">
					<div class="circle"></div>
				</div>
				<div class="gap-patch">
					<div class="circle"></div>
				</div>
				<div class="circle-clipper right">
					<div class="circle"></div>
				</div>
			</div>
		</div>
	</div>
	<!-- end preloader -->
	<div id="modalAlert" class="modal">
		<div class="modal-content card center">
			<i id="modIcon" class="material-icons large red-text text-darken-1">warning</i>
			<h4 id="modHdr">Oops!</h4>
			<p id="modText"></p>
			<a class="btn b-shadow center modal-close waves-effect" id="langClose">Close</a>
		</div>
	</div>
	<div id="modalWTK" class="modal content"></div>
	<script type="text/javascript" src="/wtk/js/wtkLibrary.js" defer></script>
	<script type="text/javascript" src="/wtk/js/wtkClientVars.js" defer></script>
	<script type="text/javascript" src="/wtk/js/wtkChart.js" defer></script>
	<script type="text/javascript" src="/wtk/js/wtkFileUpload.js" defer></script>
</body>
</html>
