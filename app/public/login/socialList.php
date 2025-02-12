<?PHP
$pgSecurityLevel = 1;
if (!isset($gloConnected)):
    define('_RootPATH', '../');
    require('../wtk/wtkLogin.php');
endif;
$gloSkipFooter = true;

$pgSQL =<<<SQLVAR
SELECT ul.`UID`,
    CONCAT('<a class="btn-floating ', ss.`ButtonColor`,'">', ss.`IconHTML`, '</a>') AS `Button`,
    CONCAT('<a class="btn btn-floating wtkdrag" draggable="true" data-id="', ul.`UID`,
        '" data-pos="', ROW_NUMBER() OVER(ORDER BY `Priority`),
        '" ondragstart="wtkDragStart(this);" ondrop="wtkDropId(this)" ondragover="wtkDragOver(event)">',
        '<i class="material-icons" alt="drag to change priorty" title="drag to change priorty">drag_handle</i></a>')
        AS `Prioritize`, ul.`SocialLink`
  FROM `SocialSites` ss
    INNER JOIN `UserLinks` ul ON ul.`SocialUID` = ss.`UID`
WHERE ul.`UserUID` = :UserUID
ORDER BY ul.`Priority` ASC
SQLVAR;

if ($gloDeviceType == 'phone'):
    $pgSQL = wtkReplace($pgSQL, ', ul.`SocialLink`','');
    $pgPhoneJS =<<<htmVAR
<script type="text/javascript">
wtkInitiatePhoneTouches();
</script>
htmVAR;
else: // not phone
    $pgPhoneJS = '';
endif;
$pgSqlFilter = array('UserUID' => $gloUserUID);

$gloEditPage = '/login/socialEdit';
$gloAddPage  = $gloEditPage;
$gloDelPage  = 'UserLinks';

wtkSearchReplace('No data.','no social media links yet');
wtkSearchReplace('</table>','</table>' . $pgPhoneJS);
$pgHtm = wtkBuildDataBrowse($pgSQL, $pgSqlFilter, 'UserLinks', '/login/socialList.php', 'Y');

echo $pgHtm;
exit;
?>
