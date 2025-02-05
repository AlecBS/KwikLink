<?PHP
$pgSecurityLevel = 1;
define('_RootPATH', '../');
require('../wtk/wtkLogin.php');

$pgSlug = wtkGetParam('newSlug');

$pgSQL =<<<SQLVAR
SELECT COUNT(*)
 FROM `wtkUsers`
WHERE `UID` <> :UserUID AND `KwikSlug` = :KwikSlug
SQLVAR;

$pgSqlFilter = array(
    'UserUID' => $gloUserUID,
    'KwikSlug' => $pgSlug
);

$pgCount = wtkSqlGetOneResult($pgSQL, $pgSqlFilter);

if ($pgCount == 0):
    $pgSQL =<<<SQLVAR
UPDATE `wtkUsers`
  SET `KwikSlug` = :KwikSlug
WHERE `UID` = :UserUID
SQLVAR;
    wtkSqlExec($pgSQL, $pgSqlFilter);
    $pgJSON = '{"result":"ok"}';
else:
    $pgJSON = '{"result":"failure"}';
endif;

echo $pgJSON;
exit;
?>
