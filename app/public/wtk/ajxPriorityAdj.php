<?PHP
$pgSecurityLevel = 1;
define('_RootPATH', '../');
require('wtkLogin.php');

$pgTable = wtkGetPost('tbl');
$pgColumn = wtkGetPost('col');
$pgFilter = wtkGetPost('filter');
$pgFromId = wtkGetPost('fromId');
$pgToId = wtkGetPost('toId');
$pgFromPos = wtkGetPost('fromPos');
$pgToPos = wtkGetPost('toPos');

switch ($pgTable):
    case 'UserLinks':
    case 'UserWebsites':
        $pgExtraWhere = ' AND `UserUID` = ' . $gloUserUID;
        break;
    case 'wtkWidgetGroup_X_Widget':
        $pgExtraWhere = ' AND `WidgetGroupUID` = ' . $pgFilter . "\n";
        $pgUserUID = wtkSqlGetOneResult("SELECT `UserUID` FROM `wtkWidgetGroup_X_Widget` WHERE `UID` = ?", [$pgFromId],0);
        if ($pgUserUID == 0):
            $pgExtraWhere .= ' AND `UserUID` IS NULL';
        else:
            $pgExtraWhere .= ' AND `UserUID` = ' . $pgUserUID;
        endif;
        break;
    default:
        $pgExtraWhere = '';
        break;
endswitch;

$pgFromPriority = wtkSqlGetOneResult("SELECT `$pgColumn` FROM `$pgTable` WHERE `UID` = ?", [$pgFromId]);
$pgToPriority   = wtkSqlGetOneResult("SELECT `$pgColumn` FROM `$pgTable` WHERE `UID` = ?", [$pgToId]);

if ($pgFromPos < $pgToPos): // moving down
    // All rows above From Priority to the To Priority should be reduced by 10
    $pgWhereAdjust = "`$pgColumn` > :FromPriority AND `$pgColumn` <= :ToPriority";
    $pgAdjustValue = ' - 10';
else: // moving up
    // All rows starting with From and down to just above To Priority should be increased by 10
    $pgWhereAdjust = "`$pgColumn` < :FromPriority AND `$pgColumn` >= :ToPriority";
    $pgAdjustValue = ' + 10';
endif;
// After above is done the From ID should become the To Priority

$pgSQL =<<<SQLVAR
UPDATE `$pgTable`
   SET `$pgColumn` = (`$pgColumn` $pgAdjustValue)
 WHERE $pgWhereAdjust
 $pgExtraWhere
SQLVAR;
$pgSqlFilter = array (
    'FromPriority' => $pgFromPriority,
    'ToPriority' => $pgToPriority
);
wtkSqlExec($pgSQL, $pgSqlFilter);
$pgSqlFilter = array (
    'UID' => $pgFromId
);
$pgSQL = "UPDATE `$pgTable` SET `$pgColumn` = $pgToPriority WHERE `UID` = :UID $pgExtraWhere";
wtkSqlExec($pgSQL, $pgSqlFilter);

$pgJSON = '{"result":"ok"}';
// $pgSQL  = wtkReplace($pgSQL, ':UID',$pgFromId);
// $pgJSON = '{"result":"ok","sql":"' . $pgSQL . '"}';
echo $pgJSON;
exit;
?>
