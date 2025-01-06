<?PHP
$pgSecurityLevel = 1;
if (!isset($gloConnected)):
    define('_RootPATH', '../');
    require('../wtk/wtkLogin.php');
endif;
$gloSkipFooter = true;

$pgSQL =<<<SQLVAR
SELECT `UID`,
    CONCAT('<a draggable="true" ondragstart="wtkDragStart(', `UID`,
        ',', ROW_NUMBER() OVER(ORDER BY `Priority`),');" ondrop="wtkDropId(', `UID`,
        ',', ROW_NUMBER() OVER(ORDER BY `Priority`),',2)" ondragover="wtkDragOver(event)" class="btn btn-floating ">',
        '<i class="material-icons" alt="drag to change priorty" title="drag to change priorty">drag_handle</i></a>')
        AS `Prioritize`,
    CONCAT(`WebsiteName`, '<br>',`WebsiteLink`) AS `Website`, `WebsiteDesc` AS `Description`
  FROM `UserWebsites`
WHERE `UserUID` = :UserUID
ORDER BY `Priority` ASC
SQLVAR;
$pgSqlFilter = array('UserUID' => $gloUserUID);

$gloEditPage = 'login/websiteEdit';
$gloAddPage  = $gloEditPage;
$gloDelPage  = 'UserWebsites';

wtkSearchReplace('No data.','no websites yet');
$pgHtm = wtkBuildDataBrowse($pgSQL, $pgSqlFilter, 'UserWebsites', '/login/websiteList.php', 'Y');

echo $pgHtm;
exit;
?>
