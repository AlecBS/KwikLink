<?php
$gloLoginRequired = false;
require('wtk/wtkLogin.php');
$gloId = wtkGetParam('id',1);

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

$pgTmp = wtkLoadInclude('login/card.htm');
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
<table class="table-basic centered" width="90%"><tbody><tr>
    <td>
        <a href="$pgPersonalURL" target="_blank" class="btn-floating waves-effect"><i class="material-icons">date_range</i></a>
    </td>
    <td>
    	<small>my schedule</small>
    </td>
</tr></tbody></table>
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
        $pgTmp = wtkReplace($pgTmp, 'class="card b-shadow"','class="card b-shadow my-bkgrnd"');
        $pgTmp = wtkReplace($pgTmp, '</head>', $pgCSS . '</head>');
        break;
    case 'I': // Image background
//        code
        break;
    default: // 'N': // none
        // /do nothing
        break;
endswitch;

//  END  Background Functionality

echo $pgTmp;
/*
$pgTmp = wtkReplace($pgTmp, '@UserName@', $pgUserName);
$pgTmp = wtkReplace($pgTmp, '@UserName@', $pgUserName);

@CalendarLink@
<div class="col s6">
    <table class="table-basic centered" width="90%"><tr>
        <td>
            <a href="https://calendly.com/alec-sherman/30min" target="_blank" class="btn-floating deep-purple darken-2 waves-effect waves-purple"><i class="material-icons">date_range</i></a>
        </td>
        <td>
            <small>Schedule<br>demo</small>
        </td>
    </tr></table>
</div>


@WebsiteLinks@
<br>
<ul class="collection with-header">
    <li class="collection-header"><h6>Websites</h6></li>
    <li class="collection-item"><a target="_blank" href="https://wizardstoolkit.com">Wizard&rsquo;s Toolkit</a>
        <br>low-code development library</li>
    <li class="collection-item"><a target="_blank" href="https://programminglabs.com/">Programming Labs</a>
        <br>agile software dev team</li>
    <li class="collection-item"><a target="_blank" href="https://intellimuse.app">Intellimuse</a>
        <br>customizable AI companions</li>
    <li class="collection-item"><a target="_blank" href="https://wizardsabacus.com">Wizard&rsquo;s Abacus</a>
        <br>time tracking and payroll</li>
    <li class="collection-item"><a target="_blank" href="https://wizbits.me/">WizBits</a>
        <br>shortened URL service</li>
    <li class="collection-item"><a target="_blank" href="https://extragood.info/">Mage Page</a>
        <br>landing pages</li>
    <li class="collection-item"><a target="_blank" href="https://github.com/AlecBS">GitHub</a>
        <br>making the world a better place one line of code at a time</li>
</ul>

@SocialMedia@
<small>Social Profile</small>
<br>
<a href="https://www.linkedin.com/in/alecsherman/" target="_blank" class="btn-floating blue darken-3"><i class="fab fa-linkedin"></i></a>
<a href="https://youtube.com/BusinessOfProgramming" target="_blank" class="btn-floating red "><i class="fab fa-youtube"></i></a>
<a href="https://www.facebook.com/profile.php?id=100067646815322" target="_blank" class="btn-floating indigo darken-2 m-t-10"><i class="fab fa-facebook"></i></a>
<br>
*/

?>
