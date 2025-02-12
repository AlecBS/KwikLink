<?PHP
$pgSecurityLevel = 1;
if (!isset($gloConnected)):
    define('_RootPATH', '../');
    require('../wtk/wtkLogin.php');
endif;
$gloSkipFooter = true;

$pgSQL =<<<SQLVAR
SELECT `UID`,
    CONCAT('<a class="btn btn-floating wtkdrag" draggable="true" data-set="2"',
        ' data-id="', `UID`, '"',
        ' data-pos="', ROW_NUMBER() OVER(ORDER BY `Priority`), '"',
        ' ondragstart="wtkDragStart(this);" ondrop="wtkDropId(this)" ondragover="wtkDragOver(event)">',
        '<i class="material-icons" alt="drag to change priorty" title="drag to change priorty">drag_handle</i></a>')
        AS `Prioritize`, `WebsiteName`
  FROM `UserWebsites`
WHERE `UserUID` = :UserUID
ORDER BY `Priority` ASC
SQLVAR;

if ($gloDeviceType == 'phone'):
    $pgPhoneJS =<<<htmVAR
<script type="text/javascript">
wtkInitiatePhoneTouches();
</script>
htmVAR;
else: // not phone
    $pgPhoneJS = '';
endif;

$pgSqlFilter = array('UserUID' => $gloUserUID);

$gloEditPage = '/login/websiteEdit';
$gloAddPage  = $gloEditPage;
$gloDelPage  = 'UserWebsites';

wtkSearchReplace('No data.','no websites yet');
wtkSearchReplace('</table>','</table>' . $pgPhoneJS);
$pgHtm = wtkBuildDataBrowse($pgSQL, $pgSqlFilter, 'UserWebsites', '/login/websiteList.php', 'Y');

echo $pgHtm;
exit;
?>
