<?PHP
$pgSecurityLevel = 1;
if (!isset($gloConnected)):
    define('_RootPATH', '../');
    require('../wtk/wtkLogin.php');
endif;
// See SQL to generate table below

$pgSQL =<<<SQLVAR
SELECT COUNT(`UID`) AS `UTotalViews`,
    SUM(IF (DATE_FORMAT(`AddDate`,'%Y-%m-%d') = CURRENT_DATE,1,0)) AS `UViewsToday`,
    SUM(IF (`AddDate` > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 WEEK),1,0)) AS `UViewsLast7Days`,
    SUM(IF (`AddDate` > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 MONTH),1,0)) AS `UViewsLastMonth`,
    SUM(IF (`AddDate` > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 YEAR),1,0)) AS `UViewsLastYear`,
    SUM(`PagesB4Signup`) AS `TotalViews`,
    SUM(IF (DATE_FORMAT(`AddDate`,'%Y-%m-%d') = CURRENT_DATE,`PagesB4Signup`,0)) AS `ViewsToday`,
    SUM(IF (`AddDate` > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 WEEK),`PagesB4Signup`,0)) AS `ViewsLast7Days`,
    SUM(IF (`AddDate` > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 MONTH),`PagesB4Signup`,0)) AS `ViewsLastMonth`,
    SUM(IF (`AddDate` > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 YEAR),`PagesB4Signup`,0)) AS `ViewsLastYear`
 FROM `wtkVisitors`
WHERE `KwikUserUID` = :UserUID
SQLVAR;
$pgSqlFilter = array('UserUID' => $gloUserUID);
wtkSqlGetRow($pgSQL, $pgSqlFilter);

$pgUTotalViews = wtkSqlValue('UTotalViews');
if ($pgUTotalViews == 0): // No views yet
    $pgStats = '<br><br><h3>No views for your KwikLink yet</h3><br>';
else:
    $pgUViewsToday = wtkSqlValue('UViewsToday');
    $pgUViewsLast7Days = wtkSqlValue('UViewsLast7Days');
    $pgUViewsLastMonth = wtkSqlValue('UViewsLastMonth');
    $pgUViewsLastYear = wtkSqlValue('UViewsLastYear');

    $pgTotalViews = wtkSqlValue('TotalViews');
    $pgViewsToday = wtkSqlValue('ViewsToday');
    $pgViewsLast7Days = wtkSqlValue('ViewsLast7Days');
    $pgViewsLastMonth = wtkSqlValue('ViewsLastMonth');
    $pgViewsLastYear = wtkSqlValue('ViewsLastYear');

    $pgStats =<<<htmVAR
<div class="wtk-list card b-shadow">
    <div class="card-content">
        <h4>Visits to your KwikLink Card</h4>
        <p>see <a onclick="JavaScript:ajaxGo('visitorList');">detailed report</a></p>
<table class="striped centered">
   <thead>
     <tr>
         <th>&nbsp;</th>
         <th>Unique Visitors</th>
         <th>Total Visits</th>
     </tr>
   </thead>
   <tbody>
     <tr>
       <td>All Time</td>
       <td>$pgUTotalViews</td>
       <td>$pgTotalViews</td>
     </tr>
     <tr>
       <td>Today</td>
       <td>$pgUViewsToday</td>
       <td>$pgViewsToday</td>
     </tr>
     <tr>
       <td>Last 7 Days</td>
       <td>$pgUViewsLast7Days</td>
       <td>$pgViewsLast7Days</td>
     </tr>
     <tr>
       <td>Last 30 Days</td>
       <td>$pgUViewsLastMonth</td>
       <td>$pgViewsLastMonth</td>
     </tr>
     <tr>
       <td>Last Year</td>
       <td>$pgUViewsLastYear</td>
       <td>$pgViewsLastYear</td>
     </tr>
  </tbody>
</table>
    </div>
</div><br>
htmVAR;
endif;

$pgHtm =<<<htmVAR
<br>
<a class="btn btn-large waves-effect waves-light" onclick="JavaScript:wtkModal('/login/customURL','EDIT')">Customize Your URL</a>
<span class="hide-on-small-only">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
<span class="hide-on-med-and-up"><br><br></span>
<a class="btn btn-large waves-effect waves-light" onclick="JavaScript:ajaxGo('/login/userEdit')">Update Your Card</a>
<br><br><br>
$pgStats
<br><br><br><br><br><br>
<a onclick="JavaScript:wtkModal('/wtk/deleteAccount','DEL')" class="btn red waves-effect waves-light">Delete My Account</a>
<br><br>
htmVAR;

echo $pgHtm;
exit;
?>
