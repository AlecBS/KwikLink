<?PHP
$pgSecurityLevel = 1;
if (!isset($gloConnected)):
    define('_RootPATH', '../');
    require('../wtk/wtkLogin.php');
endif;

// See SQL to generate table below
$pgSQL =<<<SQLVAR
SELECT ul.`UID`,
    CONCAT('<a class="btn-floating ', ss.`ButtonColor`,'">', ss.`IconHTML`, '</a>') AS `Button`,
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
$pgHtm = wtkBuildDataBrowse($pgSQL, $pgSqlFilter, 'socialLinks', '/login/socialLinks.php', 'Y');

echo $pgHtm;
exit;
?>
