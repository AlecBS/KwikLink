<?php
function wtkTop2GetGet($fncGetVariable) {
    return isset($_GET[$fncGetVariable]) ? stripslashes(urldecode($_GET[$fncGetVariable])) : '';
} // end of wtkTopGetGet

$pgPassedId = wtkTop2GetGet('id');
if ($pgPassedId != ''):
	$gloLoginRequired = false;
else:
	$pgSecurityLevel = 1;
endif;
define('_RootPATH', '../');
require('../wtk/wtkLogin.php');

if ($gloLoginRequired != false):
	$pgPassedId = $gloUserUID;
endif;

$pgSQL =<<<SQLVAR
SELECT `KwikSlug`
 FROM `wtkUsers`
WHERE `UID` = ?
SQLVAR;
$pgSQL = wtkSqlPrep($pgSQL);
$pgKwikSlug = wtkSqlGetOneResult($pgSQL, [$pgPassedId]);

if ($pgKwikSlug != ''):
    $pgURL = $gloWebBaseURL . '/' . $pgKwikSlug;
else:
    $pgURL = $gloWebBaseURL . '/card.php?id=' . $pgPassedId;
endif;

$pgQRimage  = '<img src="/login/makeQRcode.php?pw=LowCodeOrDie&url=' . $pgURL . '"';
$pgQRimage .= ' class="responsive-img transparent">';

$pgHtm = '<h3 class="center">Scan QR Code<small><br>for KwikLink</small></h3><br>' . "\n";

if ($gloDeviceType == 'phone'):
	$pgHtm .= $pgQRimage;
else:
	if ($gloLoginRequired == false):
		$pgHtm .=<<<htmVAR
<div class="center">
	$pgQRimage
</div>
htmVAR;
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
	endif;
endif; // not a phone

if ($gloLoginRequired == false):
	wtkMergePage($pgHtm, $gloCoName, '../wtk/htm/minibox.htm');
else:
	echo $pgHtm;
endif;
exit;
?>
