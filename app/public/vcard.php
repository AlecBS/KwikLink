<?php
$pgSecurityLevel = 1;
// $gloLoginRequired = false;
require('wtk/wtkLogin.php');

// $gloUserUID = 1;

$pgSQL =<<<SQLVAR
SELECT CONCAT(`FirstName`, ' ', COALESCE(`LastName`,'')) AS `Name`,
    `Title`, `FilePath`, `NewFileName`,`CellPhone`,`Email`,
    `Address`, `Address2`, `City`, `State`, `Zipcode`
FROM `wtkUsers`
WHERE `UID` = :UserUID
SQLVAR;
$pgSqlFilter = array('UserUID' => $gloUserUID);
wtkSqlGetRow(wtkSqlPrep($pgSQL), $pgSqlFilter);

$pgName = wtkSqlValue('Name');
$pgTitle = wtkSqlValue('Title');
$pgFilePath = wtkSqlValue('FilePath');
$pgNewFileName = wtkSqlValue('NewFileName');
$pgPhoto = $pgFilePath . $pgNewFileName;
$pgCellPhone = wtkSqlValue('CellPhone');
$pgAddress = wtkSqlValue('Address');
$pgAddress2 = wtkSqlValue('Address2');
$pgCity = wtkSqlValue('City');
$pgState = wtkSqlValue('State');
$pgZipcode = wtkSqlValue('Zipcode');
$pgEmail = wtkSqlValue('Email');

header('Content-Type: text/x-vcard');
header('Content-Disposition: inline; filename= "'. wtkReplace($pgName,' ','') . '.vcf"');

$pgVCard  = "BEGIN:VCARD\r\n";
$pgVCard .= "VERSION:3.0\r\n";
$pgVCard .= "FN:" . $pgName . "\r\n";
if ($pgTitle != ''):
    $pgVCard .= "TITLE:" . $pgTitle . "\r\n";
endif;
if ($pgEmail != ''):
    $pgVCard .= "EMAIL;TYPE=internet,pref:" . $pgEmail . "\r\n";
endif;
if ($pgCellPhone != ''):
    $pgVCard .= "TEL;TYPE=work,voice:" . $pgCellPhone . "\r\n";
endif;

// BEGIN Loop through and get websites
$pgSQL =<<<SQLVAR
SELECT `WebsiteLink`
  FROM `UserWebsites`
 WHERE `UserUID` = :UserUID
ORDER BY `Priority` ASC
SQLVAR;
$pgPDO = $gloWTKobjConn->prepare($pgSQL);
$pgPDO->execute($pgSqlFilter);
while ($pgPDOrow = $pgPDO->fetch(PDO::FETCH_ASSOC)):
    $pgWebsite = $pgPDOrow['WebsiteLink'];
    $pgVCard .= 'URL:' . $pgWebsite . "\n";
endwhile;
unset($pgPDO);

$pgSQL =<<<SQLVAR
SELECT `SocialLink`
  FROM `UserLinks`
 WHERE `UserUID` = :UserUID
ORDER BY `Priority` ASC
SQLVAR;

$pgPDO = $gloWTKobjConn->prepare($pgSQL);
$pgPDO->execute($pgSqlFilter);
while ($pgPDOrow = $pgPDO->fetch(PDO::FETCH_ASSOC)):
    $pgWebsite = $pgPDOrow['SocialLink'];
    $pgVCard .= 'URL:' . $pgWebsite . "\n";
endwhile;
unset($pgPDO);
//  END  Loop through and get websites

if ($pgPhoto != ''):
    $pgPhotoContent = file_get_contents($image);
    $pgB64vCard     = base64_encode($pgPhotoContent);
    $pgB64mLine     = chunk_split($pgB64vCard,74,"\n");
    $pgPhoto        = preg_replace('/(.+)/', ' $1', $pgB64mLine);
    $pgVCard .= "PHOTO;ENCODING=b;TYPE=JPEG:";
    $pgVCard .= $pgPhoto . "\r\n";
endif;

$pgVCard .= "END:VCARD\r\n";

echo $pgVCard;
exit;
?>
