<?php
$pgSecurityLevel = 1;
define('_RootPATH', '../');
require('../wtk/wtkLogin.php');

/*
$pgSQL =<<<SQLVAR
SELECT `PersonalURL`
 FROM `wtkUsers`
WHERE `UID` = ?
SQLVAR;
$pgSQL = wtkSqlPrep($pgSQL);
$pgURL = wtkSqlGetOneResult($pgSQL, [$gloUserUID]);
*/

$pgURL = $gloWebBaseURL . '/card.php?id=' . $gloUserUID ;

$pgQRimage  = '<img src="/login/makeQRcode.php?pw=LowCodeOrDie&url=' . $pgURL . '"';
$pgQRimage .= ' class="responsive-img transparent">';

$pgHtm = '<h3 class="center">Scan QR Code</h3><br>' . "\n";

if ($gloDeviceType == 'phone'):
	$pgHtm .= $pgQRimage;
else:
	$pgHtm .=<<<htmVAR
	<div class="container">
		<div class="card">
			<div class="card-content">
				<div class="center">
					$pgQRimage
				</div>
			</div>
		</div>
	</div>
	<br>
htmVAR;
endif; // not a phone

echo $pgHtm;
?>
