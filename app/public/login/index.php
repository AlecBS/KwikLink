<?php
$gloLoginRequired = false;
define('_RootPATH', '../');
require('../wtk/wtkLogin.php');

if ($gloCoName == 'Your Company Name'):
    $gloCoName = wtkSqlGetOneResult('SELECT `CoName` FROM `wtkCompanySettings` WHERE `UID` = ?', [1]);
endif;

wtkSearchReplace('<!-- @wtkMenu@ -->', wtkNavBar($gloCoName));
// Or instead use data-driven Menu Bar:  wtkSearchReplace('<!-- @wtkMenu@ -->', wtkMenu('WTK-Admin'));

$pgRememberMe = wtkGetCookie('rememberMe'); // 2ENHANCE save in phone storage
if ($pgRememberMe == 'Y'):
    $pgChecked = 'CHECKED';
    $pgEmail = wtkDecode(wtkGetCookie('UserEmail'));
    $pgPW = wtkDecode(wtkGetCookie('UserPW'));
else:
    $pgChecked = '';
    $pgEmail = '';
    $pgPW = '';
endif;

wtkSearchReplace('@myEmail@', $pgEmail);
wtkSearchReplace('@myPW@', $pgPW);
wtkSearchReplace('@rememberMe@', $pgChecked);
if (wtkGetParam('App') == 'new'):
    $pgReplace = '<p id="upgMsg" class="green-text">' . wtkLang('Thank you for upgrading to newest version!') . '</p>';
    wtkSearchReplace('<div id="LoginErrMsg"></div>', '<div id="LoginErrMsg">' . $pgReplace . '</div>');
endif;

$pgHtm  = '';
// $pgHtm .= wtkFormHidden('pgDebugVar', 'Y');  // uncomment to turn on JavaScript debugging
$pgHtm .= wtkFormHidden('pgSiteVar', 'publicApp');

$pgMobile = wtkGetParam('mobile');
if ($pgMobile != ''): // this makes website work for iOS and Android apps; have Xcode point to this page ?mobile=ios
    $pgHtm .= wtkFormHidden('AccessMethod', $pgMobile);
    if ($pgMobile == 'ios'):
        wtkSearchReplace('id="myNavbar"','id="myNavbar" style="margin-top:20px"');
    endif;
endif;
//wtkSearchReplace('wtkLight.css','wtkDark.css');

if (($gloDeviceType == 'phone') || ($pgMobile == 'ios')):
    wtkSearchReplace('id="loginPage" class="full-page valign-wrapper"','id="loginPage" class="white" style="height:100%"');
    wtkSearchReplace('class="card b-shadow"','');
    wtkSearchReplace('"bg-second"','""');
    wtkSearchReplace('<form class="card-content">','<form class="container">');

    wtkSearchReplace('id="forgotPW" class="hide full-page valign-wrapper"','id="forgotPW" class="hide"');
    wtkSearchReplace('<div class="card-content"><p id="langForgotMsg">','<div class="container"><p id="langForgotMsg"><br>');

    wtkSearchReplace('id="resetPWdiv" class="hide full-page valign-wrapper"','id="resetPWdiv" class="hide"');
    wtkSearchReplace('<div class="card-content"><p id="langEmailMsg">','<div class="container"><p id="langEmailMsg"><br>');

    wtkSearchReplace('id="registerPage" class="hide full-page valign-wrapper"','id="registerPage" class="hide"');
    wtkSearchReplace('name="wtkRegisterForm" class="card-content">','name="wtkRegisterForm" class="container"><br>');
    if ($pgMobile == ''):
        $pgHtm .= wtkFormHidden('AccessMethod', 'pwa');
    endif;
else:
    wtkSearchReplace('id="loginPage" class="full-page valign-wrapper">','id="loginPage" class="full-page"><br>');
endif;

$pgVersion = 4;
wtkSearchReplace('wtkLibrary.js','wtkLibrary.js?v=' . $pgVersion);
wtkSearchReplace('wtkUtils.js','wtkUtils.js?v=' . $pgVersion);
wtkSearchReplace('wtkFileUpload.js','wtkFileUpload.js?v=' . $pgVersion);

wtkMergePage($pgHtm, $gloCoName, '../wtk/htm/spa.htm');
?>
