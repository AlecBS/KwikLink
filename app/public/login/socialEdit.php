<?PHP
$pgSecurityLevel = 1;
if (!isset($gloConnected)):
    define('_RootPATH', '../');
    require('../wtk/wtkLogin.php');
endif;
// See SQL to generate table below

$pgSQL =<<<SQLVAR
SELECT `UID`, `SocialUID`, `SocialLink`
  FROM `UserLinks`
WHERE `UID` = ?
SQLVAR;
$pgSQL = wtkSqlPrep($pgSQL);
if ($gloWTKmode != 'ADD'):
    wtkSqlGetRow($pgSQL, [$gloId]);
endif;
$pgMode = ucwords(strtolower($gloWTKmode));

$pgHtm =<<<htmVAR
<form id="FsocialDIV" name="FsocialDIV" method="POST">
    <span id="formMsg" class="red-text">$gloFormMsg</span>
    <div class="row">
        <div class="col s12">
            <h4>$pgMode Note</h4>
            <br>
        </div>
htmVAR;

$pgHtm .= wtkFormPrimeField('UserLinks', 'UserUID', $gloUserUID);
$pgHtm .= wtkFormText('UserLinks', 'SocialLink', 'text', 'Link to Social Site', 's12');

$pgSQL  = "SELECT `UID`, `WebsiteName` FROM `SocialSites` WHERE `DelDate` IS NULL ORDER BY `WebsiteName` ASC";
$pgHtm .= wtkFormSelect('UserLinks', 'SocialUID', $pgSQL, [], 'WebsiteName', 'UID');

$pgHtm .= wtkFormHidden('wtkMode', $gloWTKmode);
$pgHtm .= wtkFormHidden('ID1', $gloId);
$pgHtm .= wtkFormHidden('rng', $gloRNG);
$pgHtm .= wtkFormHidden('wtkGoToURL', '../../login/socialList.php');
$pgHtm .= wtkFormWriteUpdField();

$pgBtns = wtkModalUpdateBtns('../wtk/lib/Save','socialDIV');

$pgHtm .=<<<htmVAR
    </div>
</form>
<div id="modFooter" class="modal-footer right">
    $pgBtns
</div>
htmVAR;

wtkProtoType($pgHtm);
echo $pgHtm;
exit;
?>
