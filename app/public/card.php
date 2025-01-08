<?php
$gloLoginRequired = false;
require('wtk/wtkLogin.php');

$gloId = wtkGetParam('id',1);
$pgFrom = wtkGetParam('p');

$pgSQL =<<<SQLVAR
SELECT COALESCE(`FullName`,'') AS `UserName`,
    `Title`, `FilePath`, `NewFileName`,`CellPhone`,`Email`,
    `Address`, `Address2`, `City`, `State`, `Zipcode`,
    `PersonalURL`, `ShowAddressLink`,`ShowEmail`,`ShowLocale`,
    `BackgroundType`,`BackgroundColor`,`BackgroundColor2`,`BackgroundImage`
FROM `wtkUsers`
WHERE `UID` = :UserUID
SQLVAR;
$pgSqlFilter = array('UserUID' => $gloId);
wtkSqlGetRow(wtkSqlPrep($pgSQL), $pgSqlFilter);

$pgUserName = wtkSqlValue('UserName');
$pgTitle = wtkSqlValue('Title');
$pgFilePath = wtkSqlValue('FilePath');
$pgNewFileName = wtkSqlValue('NewFileName');
$pgPhoto = $pgFilePath . $pgNewFileName;
$pgCellPhone = wtkSqlValue('CellPhone');
$pgAddress = wtkSqlValue('Address');
$pgAddress2 = wtkSqlValue('Address2');
$pgCity = wtkSqlValue('City');
$pgState = wtkSqlValue('State');
$pgZipcode = wtkSqlValue('Zipcode');
$pgEmail = wtkSqlValue('Email');
$pgPersonalURL = wtkSqlValue('PersonalURL'); // Calendar Link
$pgShowAddressLink = wtkSqlValue('ShowAddressLink');
$pgShowEmail = wtkSqlValue('ShowEmail');
$pgShowLocale = wtkSqlValue('ShowLocale');
$pgBackgroundType = wtkSqlValue('BackgroundType');
$pgBackgroundColor = wtkSqlValue('BackgroundColor');
$pgBackgroundColor2 = wtkSqlValue('BackgroundColor2');
$pgBackgroundImage = wtkSqlValue('BackgroundImage');

$pgCMain = wtkLoadInclude('login/cardMain.htm');
if ($pgFrom == 'dashboard'):
    $pgTmp = $pgCMain;
else:
    $pgFrame = wtkLoadInclude('login/cardFrame.htm');
    $pgTmp = wtkReplace($pgFrame, '@CardMain@', $pgCMain);
endif;

if ($pgPhoto == ''):
    $pgTmp = wtkReplace($pgTmp, '@UserPhoto@', '');
    $pgTmp = wtkReplace($pgTmp, '@UserWithPhoto@','');
    $pgTmp = wtkReplace($pgTmp, 'class="card-image"','');
    $pgUserInfo =<<<htmVAR
  <span class="card-title">
      <span style="font-size:18px">@UserName@</span>
      @UserTitle@
  </span>
htmVAR;
    $pgTmp = wtkReplace($pgTmp, '@UserWithoutPhoto@',$pgUserInfo);
else:
    $pgTmp = wtkReplace($pgTmp, '@UserWithoutPhoto@','');
    $pgUserInfo =<<<htmVAR
    <div class="card-image">
        @UserPhoto@
      <span class="card-title">
          <span style="font-size:18px">@UserName@</span>
          @UserTitle@
      </span>
    </div>
htmVAR;
    $pgTmp = wtkReplace($pgTmp, '@UserWithPhoto@',$pgUserInfo);
    $pgTmp = wtkReplace($pgTmp, '@UserPhoto@', '<img src="' . $pgPhoto . '">');
endif;

$pgTmp = wtkReplace($pgTmp, '@UserName@', $pgUserName);
if ($pgTitle == ''):
    $pgTmp = wtkReplace($pgTmp, '@UserTitle@', '');
else:
    $pgTmp = wtkReplace($pgTmp, '@UserTitle@', '<span style="font-size:14px"><br>' . $pgTitle . '</span>');
endif;

if ($pgShowEmail == 'N'):
    $pgTmp = wtkReplace($pgTmp, '@ShowEmail@','');
else:
    $pgEmailInfo  = '<small>Email address</small>' . "\n";
    $pgEmailInfo .= '<h6><a href="mailto:' . $pgEmail . '">' . $pgEmail . '</a></h6>';
    $pgTmp = wtkReplace($pgTmp, '@ShowEmail@',$pgEmailInfo);
endif;

if (($pgShowLocale == 'N') || ($pgState == '' && $pgCity == '')):
    $pgTmp = wtkReplace($pgTmp, '@Location@','');
else:
    $pgLocInfo  = '<br><small>Location</small><h6>';
    if ($pgCity != ''):
        $pgLocInfo .= $pgCity;
        if ($pgState != ''):
            $pgLocInfo .= ', ';
        endif;
    endif;
    if ($pgState != ''):
        $pgLocInfo .= $pgState;
    endif;
    $pgLocInfo .= '</h6>';
    $pgTmp = wtkReplace($pgTmp, '@Location@',$pgLocInfo);
endif;

if ($pgShowAddressLink == 'N'):
    $pgTmp = wtkReplace($pgTmp, '@AddressVCard@','');
else:
    $pgVCFlink =<<<htmVAR
    <div class="col s6">
        <table class="table-basic centered" width="90%"><tr>
            <td>
                <a href="/login/vcard.php?id=$gloId" class="btn-floating waves-effect"><i class="material-icons">person_add</i></a>
            </td>
            <td>
                <small>+ Address<br>Book</small>
            </td>
        </tr></table>
    </div>
htmVAR;
    $pgTmp = wtkReplace($pgTmp, '@AddressVCard@', $pgVCFlink);
endif;

if ($pgPersonalURL == ''):
    $pgTmp = wtkReplace($pgTmp, '@CalendarLink@','');
else:
    $pgCalendar =<<<htmVAR
<div class="col s6">
    <table class="table-basic centered" width="90%"><tbody><tr>
        <td>
            <a href="$pgPersonalURL" target="_blank" class="btn-floating waves-effect"><i class="material-icons">date_range</i></a>
        </td>
        <td>
        	<small>my schedule</small>
        </td>
    </tr></tbody></table>
</div>
htmVAR;
    $pgTmp = wtkReplace($pgTmp, '@CalendarLink@',$pgCalendar);
endif;

// BEGIN Background Functionality
$pgBackgroundType = wtkSqlValue('BackgroundType');
$pgBackgroundColor = wtkSqlValue('BackgroundColor');
$pgBackgroundColor2 = wtkSqlValue('BackgroundColor2');
$pgBackgroundImage = wtkSqlValue('BackgroundImage');
switch ($pgBackgroundType):
    case 'C': // single color
        $pgTmp = wtkReplace($pgTmp, 'max-width:450px',"background:$pgBackgroundColor;max-width:450px");
        break;
    case 'G': // Gradient background
        $pgCSS =<<<htmVAR
<style>
.my-bkgrnd {
    background: linear-gradient(to right, $pgBackgroundColor, $pgBackgroundColor2);
}
</style>
htmVAR;
        $pgTmp = wtkReplace($pgTmp, 'class="card b-shadow','class="card b-shadow my-bkgrnd ');
        if ($pgFrom == 'dashboard'):
            $pgTmp = $pgCSS . $pgTmp;
        else:
            $pgTmp = wtkReplace($pgTmp, '</head>', $pgCSS . '</head>');
        endif;
        break;
    case 'I': // Image background
        $pgCSS =<<<htmVAR
<style>
.my-bkgrnd {
    position: relative;
    overflow: hidden;
}

.my-bkgrnd::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url('imgs/background/$pgBackgroundImage');
    background-size: contain;
    background-position: center;
    background-repeat: no-repeat;
    opacity: 0.27; /* Set the opacity for the background image */
    z-index: 0; /* Ensure the background is behind the text */
}

.card-content {
    position: relative;
    z-index: 1; /* Ensure the text is above the background image */
}
</style>
htmVAR;
        $pgTmp = wtkReplace($pgTmp, '<div class="card-content">','<div class="my-bkgrnd"><div class="card-content">');
        $pgTmp = wtkReplace($pgTmp, '@SocialMedia@','@SocialMedia@' . "\n" . '</div>');
        if ($pgFrom == 'dashboard'):
            $pgTmp = $pgCSS . $pgTmp;
        else:
            $pgTmp = wtkReplace($pgTmp, '</head>', $pgCSS . '</head>');
        endif;
        break;
    default: // 'N': // none
        // /do nothing
        break;
endswitch;
//  END  Background Functionality

// BEGIN Social Links
$pgSQL =<<<SQLVAR
SELECT CONCAT('<a target="_blank" href="', ul.`SocialLink`,'" class="btn-floating ', ss.`ButtonColor`,'">', ss.`IconHTML`, '</a>') AS `Button`
  FROM `SocialSites` ss
    INNER JOIN `UserLinks` ul ON ul.`SocialUID` = ss.`UID`
WHERE ul.`UserUID` = :UserUID
ORDER BY ul.`Priority` ASC
SQLVAR;

$pgSocialBtns = '';
$pgPDO = $gloWTKobjConn->prepare($pgSQL);
$pgPDO->execute($pgSqlFilter);
while ($gloPDOrow = $pgPDO->fetch(PDO::FETCH_ASSOC)):
    $pgSocialBtns .= $gloPDOrow['Button'] . "\n";
endwhile;
unset($pgPDO);
if ($pgSocialBtns != ''):
    $pgSocialBtns = '<br><small>Social Profile</small><br>' . $pgSocialBtns;
endif;
$pgSocialBtns .= '<a onclick="JavaScript:ajaxGo(\'/login/showQRcode\',' . $gloId . ')"';
$pgSocialBtns .= ' class="btn-floating black right">';
$pgSocialBtns .= '<img src="/imgs/qr_code.svg" style="margin-top:7px"></a>';
$pgSocialBtns .= "\n" . '<br>' . "\n";
$pgTmp = wtkReplace($pgTmp, '@SocialMedia@', $pgSocialBtns);
//  END  Social Links

// BEGIN UserWebsites
$pgSQL =<<<SQLVAR
SELECT `WebsiteName`, `WebsiteLink`, `WebsiteDesc`
  FROM `UserWebsites`
WHERE `UserUID` = :UserUID
ORDER BY `Priority` ASC
SQLVAR;

$pgWebsites = '';
$pgStart = '<li class="collection-item"><a target="_blank" href="';
$pgPDO = $gloWTKobjConn->prepare($pgSQL);
$pgPDO->execute($pgSqlFilter);
while ($gloPDOrow = $pgPDO->fetch(PDO::FETCH_ASSOC)):
    $pgWebsiteName = $gloPDOrow['WebsiteName'];
    $pgWebsiteLink = $gloPDOrow['WebsiteLink'];
    $pgWebsiteDesc = $gloPDOrow['WebsiteDesc'];
    $pgWebsites .= $pgStart . $pgWebsiteLink . '">' . $pgWebsiteName . '</a>' . "\n";
    $pgWebsites .= '<br>' . $pgWebsiteDesc . '</li>' . "\n";
endwhile;
unset($pgPDO);
if ($pgWebsites != ''):
    $pgStart  = '<ul class="collection with-header">' . "\n";
    $pgStart .= '<li class="collection-header"><h6>Websites</h6></li>' . "\n";
    $pgWebsites = $pgStart . $pgWebsites . '</ul>';
endif;
$pgTmp = wtkReplace($pgTmp, '@WebsiteLinks@', $pgWebsites);
//  END  UserWebsites

if ($pgFrom != 'dashboard'):
    wtkTrackVisitor('KwikCard','',$gloId);
endif;
echo $pgTmp;
?>
