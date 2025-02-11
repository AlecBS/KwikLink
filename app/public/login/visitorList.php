<?PHP
$pgSecurityLevel = 80;
if (!isset($gloConnected)):
    define('_RootPATH', '../');
    require('../wtk/wtkLogin.php');
endif;
$gloIconSize = 'btn-small';

$pgSQL =<<<SQLVAR
SELECT DATE_FORMAT(v.`AddDate`,'$gloSqlDateTime') AS `FirstVisit`,
    CASE WHEN v.`IPaddress` = 'no-IP' THEN 'no-IP'
      ELSE
           CONCAT('<a target="_blank" href="https://dnschecker.org/ip-location.php?ip=',
            v.`IPaddress`,'">',v.`IPaddress`,'</a>')
    END AS `IPAddress`, v.`ReferDomain`,
    (SELECT COUNT(h.`UID`)
        FROM `wtkVisitorHistory` h
        WHERE h.`VisitorUID` = v.`UID`) AS `Visits`
FROM `wtkVisitors` v
WHERE v.`KwikUserUID` = :UserUID
ORDER BY v.`UID` DESC
SQLVAR;
$pgSqlFilter = array(
    'UserUID' => $gloUserUID
);

$gloColumnAlignArray = array (
    'Visits' => 'center'
);
$pgSQL = wtkSqlPrep($pgSQL);
if ($gloDeviceType == 'phone'):
    $pgSQL = wtkReplace($pgSQL, ', v.`ReferDomain`, COUNT(v.`UID`) AS `Visits`','');
endif;

wtkSetHeaderSort('ReferDomain', 'From Domain');
wtkSetHeaderSort('FirstVisit', 'First Visit', 'v.`AddDate`');

$pgHtm =<<<htmVAR
<div class="container">
    <h4>Past Visitors <small>to your KwikLink card</small></h4>
    <div class="wtk-list card b-shadow">
htmVAR;
$pgHtm .= wtkBuildDataBrowse($pgSQL, $pgSqlFilter);
$pgHtm .= '</div></div>' . "\n";

echo $pgHtm;
exit;
?>
