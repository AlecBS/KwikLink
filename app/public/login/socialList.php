<?PHP
$pgSecurityLevel = 1;
if (!isset($gloConnected)):
    define('_RootPATH', '../');
    require('../wtk/wtkLogin.php');
endif;

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
$pgSqlFilter = array('UserUID' => $gloUserUID);

$gloEditPage = 'login/socialEdit';
$gloAddPage  = $gloEditPage;
$gloDelPage  = 'UserLinks';
$pgHtm = wtkBuildDataBrowse($pgSQL, $pgSqlFilter, 'UserLinks', '/login/socialLinks.php', 'Y');

echo $pgHtm;
exit;
?>
