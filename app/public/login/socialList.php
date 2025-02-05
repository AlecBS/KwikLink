<?PHP
$pgSecurityLevel = 1;
if (!isset($gloConnected)):
    define('_RootPATH', '../');
    require('../wtk/wtkLogin.php');
endif;
$gloSkipFooter = true;

if ($gloDeviceType == 'phone'):
    $pgSQL =<<<SQLVAR
SELECT ul.`UID`,
    CONCAT('<a class="btn-floating ', ss.`ButtonColor`,'">', ss.`IconHTML`, '</a>') AS `Button`,
    CONCAT('<a draggable="true" ondragstart="wtkDragStart(', ul.`UID`,
        ',', ROW_NUMBER() OVER(ORDER BY `Priority`),');" ondrop="wtkDropId(', ul.`UID`,
        ',', ROW_NUMBER() OVER(ORDER BY `Priority`),')" ondragover="wtkDragOver(event)" class="btn btn-floating ">',
        '<i class="material-icons" alt="drag to change priorty" title="drag to change priorty">drag_handle</i></a>')
        AS `Prioritize`
  FROM `SocialSites` ss
    INNER JOIN `UserLinks` ul ON ul.`SocialUID` = ss.`UID`
WHERE ul.`UserUID` = :UserUID
ORDER BY ul.`Priority` ASC
SQLVAR;
else: // not phone
    $pgSQL =<<<SQLVAR
SELECT ul.`UID`,
    CONCAT('<a class="btn-floating ', ss.`ButtonColor`,'">', ss.`IconHTML`, '</a>') AS `Button`,
    CONCAT('<a draggable="true" ondragstart="wtkDragStart(', ul.`UID`,
        ',', ROW_NUMBER() OVER(ORDER BY `Priority`),');" ondrop="wtkDropId(', ul.`UID`,
        ',', ROW_NUMBER() OVER(ORDER BY `Priority`),')" ondragover="wtkDragOver(event)" class="btn btn-floating ">',
        '<i class="material-icons" alt="drag to change priorty" title="drag to change priorty">drag_handle</i></a>')
        AS `Prioritize`,
    ul.`SocialLink`
  FROM `SocialSites` ss
    INNER JOIN `UserLinks` ul ON ul.`SocialUID` = ss.`UID`
WHERE ul.`UserUID` = :UserUID
ORDER BY ul.`Priority` ASC
SQLVAR;
endif;
$pgSqlFilter = array('UserUID' => $gloUserUID);

$gloEditPage = '/login/socialEdit';
$gloAddPage  = $gloEditPage;
$gloDelPage  = 'UserLinks';

wtkSearchReplace('No data.','no social media links yet');
$pgHtm = wtkBuildDataBrowse($pgSQL, $pgSqlFilter, 'UserLinks', '/login/socialList.php', 'Y');

echo $pgHtm;
exit;
?>
