<?PHP
$pgSecurityLevel = 50;
if (!isset($gloConnected)):
    define('_RootPATH', '../');
    require('../wtk/wtkLogin.php');
endif;

$pgSQL =<<<SQLVAR
SELECT `UID`, `WebsiteName`, `IconHTML`
  FROM `SocialSites`
WHERE `DelDate` IS NULL
SQLVAR;
$pgHideReset = ' class="hide"';
$pgFilterValue = wtkFilterRequest('wtkFilter');
if ($pgFilterValue != ''):
    $pgSQL .= " AND lower(`WebsiteName`) LIKE lower('%" . $pgFilterValue . "%')" . "\n";
    $pgHideReset = '';
endif;  // $pgFilterValue != ''

$pgSQL .= ' ORDER BY `WebsiteName` ASC';

$gloEditPage = 'socialSiteEdit';
$gloAddPage  = $gloEditPage;
$gloDelPage  = 'SocialSitesDelDate'; // have DelDate at end if should DelDate instead of DELETE

// If you want phone version to show less columns...
// if ($gloDeviceType == 'phone'):
//     $pgSQL = wtkReplace($pgSQL, ', `ExtraColumns`','');
// endif;

// put in columns you want sortable here:
//wtkSetHeaderSort('ColumnName', 'Column Header');
//wtkFillSuppressArray('ColumnName');

//$gloColumnAlignArray = array (
//    'Priority'   => 'center'
//);

/*
$gloMoreButtons = array(
    'User Logins' => array(
            'act' => 'pageName',
            'img' => 'arrow-right'
            )
    );
*/

$pgHtm  = '<div class="container">' . "\n";
$pgHtm .= '    <h4>Social Media Sites</h4><br>' . "\n";
$pgHtm .=<<<htmVAR
    <h5> Website Name Quick Filters <small id="filterReset"$pgHideReset>
        <button onclick="JavaScript:wtkBrowseReset('socialSiteList','SocialSites','$gloRNG')" type="button" class="btn btn-small btn-save waves-effect waves-light right">Reset List</button>
        </small>
    </h5>
    <form method="post" name="wtkFilterForm" id="wtkFilterForm" role="search" class="wtk-search card b-shadow">
        <input type="hidden" id="Filter" name="Filter" value="Y">
        <div class="input-field">
           <div class="filter-width">
              <input value="$pgFilterValue" name="wtkFilter" id="wtkFilter" type="search" placeholder="enter partial value to search for">
           </div>
           
           <button onclick="Javascript:wtkBrowseFilter('socialSiteList','SocialSites')" id="wtkFilterBtn" type="button" class="btn waves-effect waves-light"><i class="material-icons">search</i></button>
        </div>
    </form>
htmVAR;

$pgHtm .= '    <div class="wtk-list card b-shadow">' . "\n";
$pgHtm .= wtkBuildDataBrowse($pgSQL, [], 'SocialSites', '/admin/socialSiteList.php');
//$pgHtm  = wtkReplace($pgHtm, 'There is no data available.','no users yet');
$pgHtm .= '</div></div>' . "\n";

echo $pgHtm;
exit;
?>
