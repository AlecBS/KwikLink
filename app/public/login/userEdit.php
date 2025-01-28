<?PHP
$pgSecurityLevel = 1;
if (!isset($gloConnected)):
    define('_RootPATH', '../');
    require('../wtk/wtkLogin.php');
endif;

if ($gloId == 0): // from My Profile (wtk/user.php) page
    $gloId = $gloUserUID;
endif;

$pgLang = wtkGetParam('wtkLang');

$pgSQL =<<<SQLVAR
SELECT `UID`, `FullName`, `Title`, `Email`, `CellPhone`,
    `Address`,`Address2`, `City`, `State`, `PersonalURL`,
    `WebPassword`, `FilePath`, `NewFileName`,
    `ShowAddressLink`,`ShowEmail`,`ShowLocale`,
    `BackgroundType`,`BackgroundColor`,`BackgroundColor2`,`BGFilePath`,`BackgroundImage`
  FROM `wtkUsers`
WHERE `UID` = :UserUID
SQLVAR;
$pgSqlFilter = array('UserUID' => $gloId);
wtkSqlGetRow($pgSQL, $pgSqlFilter);

$pgHtm =<<<htmVAR
<div class="container">
    <h4>Edit Your Profile</h4>
    <p>Leave blank anything you do not want visible in you KwikLink.</p><br>
    <div class="card content b-shadow">
        <form id="wtkForm" name="wtkForm" method="POST">
            <span id="formMsg" class="red-text">$gloFormMsg</span>
            <div class="row">
htmVAR;

$pgHtm .= wtkFormText('wtkUsers', 'FullName');
$pgHtm .= wtkFormText('wtkUsers', 'Title');
$pgHtm .= wtkFormText('wtkUsers', 'Email', 'email');
$pgHtm .= wtkFormText('wtkUsers', 'CellPhone', 'tel');

$pgValues = array(
    'checked' => 'Y',
    'not' => 'N'
    );
$pgHtm .= wtkFormCheckbox('wtkUsers', 'ShowEmail', 'Show Email on KwikLink Card',$pgValues);
$pgHtm .= wtkFormText('wtkUsers', 'PersonalURL','text', 'Schedule Meeting URL', 'm6 s12', 'N','like calendly.com');

$pgHtm .=<<<htmVAR
</div>
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <h4>Address Information</h4>
                <p>Check the &ldquo;Show Address Link&rdquo; if you want viewers
                    to see an option to download and import your contact info
                    into their Address Book (VCF file).
                  This allows people to easily add you to their Address Book, so only
                  include the information you want them to have on you!</p>
                <p>By checking the &ldquo;Show Locale&rdquo;, you will show the city and/or
                    state on your KwikLink card.<br>
                <div class="row">
htmVAR;
$pgHtm .= wtkFormCheckbox('wtkUsers', 'ShowAddressLink', '',$pgValues,'m6 s12');
$pgHtm .= wtkFormCheckbox('wtkUsers', 'ShowLocale', '',$pgValues,'m4 s12');
$pgHtm .= '</div><div class="row">' . "\n";
$pgHtm .= wtkFormText('wtkUsers', 'Address');
$pgHtm .= wtkFormText('wtkUsers', 'Address2');
$pgHtm .= wtkFormText('wtkUsers', 'City');
$pgSQL  = "SELECT `LookupValue`, `LookupDisplay` FROM `wtkLookups` WHERE `LookupType` = 'USAstate' ORDER BY `LookupValue` ASC";
$pgHtm .= wtkFormSelect('wtkUsers', 'State', $pgSQL, [], 'LookupDisplay', 'LookupValue');

$pgPhotoUpload = wtkFormFile('wtkUsers','FilePath','/imgs/user/','NewFileName','User Photo','m6 s12','myPhoto','N','accept="image/*"','Y',1);

$pgHtm .=<<<htmVAR
                </div>
            </div>
        </div>
    </div>
    <div class="col m6 s12">
        <br>
        <div class="card">
            <div class="card-content">
                <h4>Primary Photo</h4>
                <p>This will be your primary photo at the top of your KwikLink.</p>
                <div class="row">
                $pgPhotoUpload
                </div>
            </div>
        </div>
    </div>
    <div class="col m6 s12">
        <br>
        <div class="card">
            <div class="card-content">
                <h4>Background Options</h4>
                <p>By default the background will be white but you can choose what you like.</p>
                <br>
                <div class="row">
htmVAR;

$pgBackgroundType = wtkSqlValue('BackgroundType');
$pgValues = array(
    'None' => 'N',
    'Color' => 'C',
    'Gradient' => 'G',
    'Image' => 'I'
);
$pgTmp  = wtkFormRadio('wtkUsers', 'BackgroundType', '', $pgValues, 'm4 s12');
$pgTmp  = wtkReplace($pgTmp, 'type="radio"','type="radio" onclick="JavaScript:showHideBackgroundOptions(this.value)"');
$pgHtm .= $pgTmp;

$pgTmp = wtkFormText('wtkUsers', 'BackgroundColor', 'text', '','m4 s6');
if (($pgBackgroundType == 'N') || ($pgBackgroundType == 'I')):
    $pgTmp = wtkReplace($pgTmp, 'm4 s6','m4 s6 hide');
endif;
$pgTmp = wtkReplace($pgTmp, 'class="input-field col m4 s6','id="bkColor1" class="input-field col m4 s6');
$pgTmp = wtkReplace($pgTmp, 'type="text"','type="text" class="w72"');
//$pgTmp  = wtkReplace($pgTmp, '<input type="text"','<input type="text" oninput="JavaScript:setCssRoot(this.id, this.jscolor)"');
$pgHtm .= $pgTmp;

$pgTmp = wtkFormText('wtkUsers', 'BackgroundColor2', 'text', '','m4 s6');
if ($pgBackgroundType != 'G'):
    $pgTmp = wtkReplace($pgTmp, 'm4 s6','m4 s6 hide');
endif;
$pgTmp = wtkReplace($pgTmp, 'class="input-field col m4 s6','id="bkColor2" class="input-field col m4 s6');
$pgTmp = wtkReplace($pgTmp, 'type="text"','type="text" class="w72"');
//$pgTmp  = wtkReplace($pgTmp, '<input type="text"','<input type="text" oninput="JavaScript:setCssRoot(this.id, this.jscolor)"');
$pgHtm .= $pgTmp;

$pgTmp = wtkFormFile('wtkUsers','BGFilePath','/imgs/background/','BackgroundImage','Background Image','m6 s12','','N','accept="image/*"','Y',2);

$pgTmp = wtkReplace($pgTmp, 'class="input-field col m6','id="bkImg" class="input-field col m6');
if ($pgBackgroundType != 'I'):
    $pgTmp = wtkReplace($pgTmp, 'input-field col','hide input-field col');
endif;
$pgHtm .= $pgTmp;

$pgHtm .= wtkFormHidden('wtkfImgWidth', 300);
$pgHtm .= wtkFormHidden('wtkfImgHeight', 300);
$pgHtm .= wtkFormHidden('ID1', $gloId);
$pgHtm .= wtkFormHidden('UID', wtkEncode('UID'));
$pgHtm .= wtkFormHidden('wtkMode', $gloWTKmode);
//$pgHtm .= wtkFormHidden('wtkGoToURL', '../../login/user.php');
$pgHtm .= wtkFormHidden('wtkGoToURL', 'dashboard');
$pgHtm .= wtkFormWriteUpdField();

$pgHtm .=<<<htmVAR
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col m6 s12">
        <br>
        <div class="card">
            <div class="card-content">
                <h4>Social Media Links</h4>
                <p>Choose which social media links you want on your KwikLink card.</p>
                <br>
                <div id="socialDIV">
htmVAR;

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

$gloEditPage = 'login/socialEdit';
$gloAddPage  = $gloEditPage;
$gloDelPage  = 'UserLinks';
$gloSkipFooter = true;

$pgTmp  = wtkBuildDataBrowse($pgSQL, $pgSqlFilter, 'UserLinks', '/login/socialList.php', 'Y');
$pgHtm .= wtkReplace($pgTmp, 'No data.','no social media links yet');

// BEGIN UserWebsites
$pgHtm .=<<<htmVAR
                </div>
            </div>
        </div>
    </div>
    <div class="col m6 s12">
        <br>
        <div class="card">
            <div class="card-content">
                <h4>Your Websites</h4>
                <p>Choose which websites you want on your KwikLink card.</p>
                <br>
                <div id="websiteDIV">

htmVAR;

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

$gloEditPage = 'login/websiteEdit';
$gloAddPage  = $gloEditPage;
$gloDelPage  = 'UserWebsites';

$pgTmp  = wtkBuildDataBrowse($pgSQL, $pgSqlFilter, 'UserWebsites', '/login/websiteList.php', 'Y');
$pgHtm .= wtkReplace($pgTmp, 'No data.','no websites yet');
//  END  UserWebsites

$pgHtm .=<<<htmVAR
                </div>
            </div>
        </div>
        <br>
    </div>
</div>
htmVAR;

$pgHtm .=<<<htmVAR
<input type="hidden" id="wtkDragTable" value="UserLinks">
<input type="hidden" id="wtkDragLocation" value="table">
<input type="hidden" id="wtkDragColumn" value="Priority">
<input type="hidden" id="wtkDragRefresh" value="/login/socialList">
<input type="hidden" id="wtkDragFilter" value="">

<input type="hidden" id="wtkDragTable2" value="UserWebsites">
<input type="hidden" id="wtkDragLocation2" value="table">
<input type="hidden" id="wtkDragColumn2" value="Priority">
<input type="hidden" id="wtkDragRefresh2" value="/login/websiteList">
<input type="hidden" id="wtkDragFilter2" value="">
htmVAR;

$pgHtm .= '            </div><br>' . "\n";
$pgMode = wtkGetParam('Mode');
if ($pgMode == 'modal'):
    $pgHtm .= '<div class="center">';
    $pgHtm .= wtkModalUpdateBtns('/wtk/lib/Save',''); // if do not want to refresh page
    $pgHtm .= '</div>' . "\n";
    $pgHtm  = wtkReplace($pgHtm, '="wtkForm"','="F"');
else:
    $pgHtm .= wtkUpdateBtns() . "\n";
endif;

$pgHtm .=<<<htmVAR
    </div>
</div>
<script type="text/javascript">
function showHideBackgroundOptions(fncValue){
    if ((fncValue == 'C') || (fncValue == 'G')){
        $('#bkColor1').removeClass('hide');
    } else {
        $('#bkColor1').addClass('hide');
    }
    if (fncValue == 'G'){
        $('#bkColor2').removeClass('hide');
    } else {
        $('#bkColor2').addClass('hide');
    }
    if (fncValue == 'I'){
        $('#bkImg').removeClass('hide');
    } else {
        $('#bkImg').addClass('hide');
    }
}

function makeAPicker(fncElementId){
    $('#' + fncElementId).addClass('jscolor');
    let myPicker = new JSColor('#' + fncElementId, {preset:'dark'});
}

makeAPicker('wtkwtkUsersBackgroundColor');
makeAPicker('wtkwtkUsersBackgroundColor2');

jscolor.trigger('input change');

</script>
htmVAR;

echo $pgHtm;

if ($gloUserSecLevel == 99): // Programmer level\
    // add special debugging code here
endif;
exit;
?>
