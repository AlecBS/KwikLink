<?PHP
$pgSecurityLevel = 1;
if (!isset($gloConnected)):
    define('_RootPATH', '../');
    require('../wtk/wtkLogin.php');
endif;
// See SQL to generate table below

$pgSQL =<<<SQLVAR
SELECT `UID`, `WebsiteName`,`WebsiteLink`,`WebsiteDesc`
 FROM `UserWebsites`
WHERE `UID` = ?
SQLVAR;
$pgSQL = wtkSqlPrep($pgSQL);
if ($gloWTKmode != 'ADD'):
    wtkSqlGetRow($pgSQL, [$gloId]);
endif;
$pgMode = ucwords(strtolower($gloWTKmode));

$pgHtm =<<<htmVAR
<form id="FwebsiteDIV" name="FwebsiteDIV" method="POST">
    <span id="formMsg" class="red-text">$gloFormMsg</span>
    <div class="row">
        <div class="col s12">
            <h4>$pgMode Website</h4>
            <br>
        </div>
htmVAR;

$pgHtm .= wtkFormPrimeField('UserWebsites', 'UserUID', $gloUserUID);
$pgHtm .= wtkFormText('UserWebsites', 'WebsiteName', 'text', '', 's12','Y');
$pgHtm .= wtkFormText('UserWebsites', 'WebsiteLink', 'text', '', 's12','Y');
$pgHtm .= wtkFormText('UserWebsites', 'WebsiteDesc', 'text', 'Description', 's12','Y');

$pgHtm .= wtkFormHidden('wtkMode', $gloWTKmode);
$pgHtm .= wtkFormHidden('ID1', $gloId);
$pgHtm .= wtkFormHidden('rng', $gloRNG);
$pgHtm .= wtkFormHidden('wtkGoToURL', '../../login/websiteList.php');
$pgHtm .= wtkFormWriteUpdField();

$pgBtns = wtkModalUpdateBtns('../wtk/lib/Save','websiteDIV');

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
