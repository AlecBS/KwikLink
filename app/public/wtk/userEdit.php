<?PHP
$pgSecurityLevel = 1;
if (!isset($gloConnected)):
    define('_RootPATH', '../');
    require('wtkLogin.php');
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
    `BackgroundType`,`BackgroundColor`,`BackgroundColor2`,`BackgroundImage`
  FROM `wtkUsers`
WHERE `UID` = ?
SQLVAR;
wtkSqlGetRow($pgSQL, [$gloId]);

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
$pgHtm .= wtkFormCheckbox('wtkUsers', 'ShowEmail', 'Show Email on KwikLink Card',$pgValues,'s12');

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

$pgHtm .=<<<htmVAR
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
htmVAR;


$pgHtm .= wtkFormText('wtkUsers', 'PersonalURL','text', 'Schedule Meeting URL', 'm6 s12', 'N','like calendly.com');
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

//$pgHtm .= wtkFormFile('Upload Photo', 'wtkUsers', 'NewFileName', '../imgscli/', '../../imgscli/');
// function wtkFormFile($fncLabel, $fncTable, $fncField, $fncPathShow, $fncPathSave = '../', $fncColSpan = '1') {
/*
    <input type="file" onchange="showFile()" accept="image/*"><br><br>
    <img id="imgPreview" src="" width="150">
    <div id="imgPreview2"></div>

Clean code:
https://www.digitalocean.com/community/tutorials/js-file-reader
*/

$pgHtm .= wtkFormFile('wtkUsers','FilePath','/imgs/user/','NewFileName','User Photo','m6 s12','myPhoto');

// $pgFilePath = wtkSqlValue('FilePath');
// $pgNewFileName = wtkSqlValue('NewFileName');
// if ($pgNewFileName != ''):
//     $pgPhoto = $pgFilePath . $pgNewFileName;
// else:
//     $pgPhoto = '';
// endif;
/*
$pgHtm .=<<<htmVAR
<div class="input-field col m6 s12">
    <table><tr><td width="150px">
        <img id="imgPreview" src="$pgPhoto" width="150">
      </td><td>
      <label for="wtkUpload" class="fileUpload">
          <input type="file" id="wtkUpload" style="display: none;">
      Choose File
      </label>
      </td></tr>
    </table>
    <input type="hidden" id="wtkfPath" value="/imgs/user/">
    <input type="hidden" id="wtkfColPath" value="FilePath">
    <input type="hidden" id="wtkfColFile" value="NewFileName">
    <input type="hidden" id="wtkfRefresh" value="myPhoto">
    <div id="photoProgressDIV" class="progress hide">
        <div id="photoProgress" class="determinate" style="width: 0%"></div>
    </div>
    <div id="uploadStatus"></div>
    <span id="uploadFileSize"></span>
    <span id="uploadProgress"></span>
</div>
htmVAR;
*/
// var textFile = /text.*/;
//
//  if (file.type.match(textFile)) {
//     fr.onload = function (event) {
//        preview.innerHTML = event.target.result;
//     }
//  } else {
//     preview.innerHTML = "<span class='error'>It doesn't seem to be a text file!</span>";
//  }
//  fr.readAsText(file);

/*
if ($gloId == $gloUserUID):
    $pgLang = wtkGetCookie('wtkLang');
    $pgLangPref = wtkLang('Language Preference');
    $pgTmp =<<<htmVAR
<div class="input-field col m6 s12">
    <select id="wtkLang" name="wtkLang" onchange="JavaScript:wtkLangUpdate(this.value)">
        <option value="eng">English</option>
        <option value="esp">Espa&ntilde;ol</option>
        <option value="hin">हिन्दी</option>
    </select>
    <label for="wtkLang" class="active">$pgLangPref</label>
</div>
htmVAR;
    $pgTmp = wtkReplace($pgTmp, 'value="' . $gloLang . '"','value="' . $gloLang . '" selected');
else:
    $pgSQL = "SELECT `LookupValue`, `LookupDisplay` FROM `wtkLookups` WHERE `LookupType` = 'LangPref' ORDER BY `LookupValue` ASC";
    $pgTmp = wtkFormSelect('wtkUsers', 'LangPref', $pgSQL, [], 'LookupDisplay', 'LookupValue','Language Preference');
endif;
$pgHtm .= $pgTmp . "\n";
*/
$pgHtm .= wtkFormHidden('wtkfImgWidth', 300);
$pgHtm .= wtkFormHidden('wtkfImgHeight', 300);
$pgHtm .= wtkFormHidden('ID1', $gloId);
$pgHtm .= wtkFormHidden('UID', wtkEncode('UID'));
$pgHtm .= wtkFormHidden('HasSelect', 'Y');
$pgHtm .= wtkFormHidden('wtkMode', $gloWTKmode);
$pgHtm .= wtkFormHidden('wtkGoToURL', '../user.php');

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

$pgHtm .= wtkFormWriteUpdField();
$pgHtm .=<<<htmVAR
        </form>
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
