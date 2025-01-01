<?PHP
/**
* Add Custom Client functions here to be included in Wizard's Toolkit environment
*/

// Custom Client functions can go here
/*
Can add here custom report filtering based on Security Levels or Roles
Be careful because these will be used for ALL WTK reports.

if ($gloUserSecLevel == 1):
    $gloUserSQLJoin = '';
    $gloUserSQLWhere = '';
endif;

Can also put in a global search and replace to make sure you do not have cache problems.
For example:
wtkSearchReplace('custom.js','custom4.js');

If you want to globally add wtkPageReadOnlyCheck for all Edit pages you could do it here like:
if (stripos($gloMyPage, 'Edit.php') !== false):
    $gloForceRO = wtkPageReadOnlyCheck($gloMyPage, $gloId);
endif;
*/

/**
* Hard-coded navbar when you do not want to use data-driven menus.
*
* This code is put into ClientFuncs.php because it will likely need to be edited for every website.
*
* To add Notification Bell, add this code just after <a id="hamburger" ...
*/
/*
<span class="counter-icon right" style="margin-right:20px">
    <i class="material-icons small white-text" style="padding: 14px 0px">notifications</i>
    <span id="alertCounter" class="counter-icon-badge">3</span>
</span>
*/
/*
* Example usage:
* <code>
* wtkSearchReplace('<!-- @wtkMenu@ -->', wtkNavBar('Your Company Name'));
* </code>
*
* @param string $fncHeader pass this in for the top-center title to show
* @return html of top navbar and side-menu
*/
function wtkNavBar($fncHeader){
    $fncHtm =<<<htmVAR
    <div class="navbar-fixed">
        <div class="navbar navbar-home">
            <div class="row">
                <div class="col s1 m3" style="margin-top:12px">
                    <a id="backBtn" onclick="JavaScript:wtkGoBack()" class="hide"><i class="material-icons small white-text">navigate_before</i></a>
                </div>
                <div class="col s10 m6 center">
                    <h4 style="padding-top:12px">$fncHeader</h4>
                </div>
                <div class="col s1 m3">
                    <a id="hamburger" data-target="phoneSideBar" class="sidenav-trigger show-on-large hide right"><i class="material-icons small white-text">menu</i></a>
                </div>
            </div>
        </div>
    </div>
	<!-- sidebar -->
	<div class="sidebar-panel">
		<ul id="phoneSideBar" class="collapsible sidenav side-nav">
			<li>
				<div class="user-view">
					<div class="background">
						<img src="/imgs/sunset.jpg">
					</div>
					<img class="circle responsive-img" id="myPhoto" src="/wtk/imgs/noPhotoAvail.png">
					<span class="name" id="myName">@FullName@</span>
				</div>
			</li>
			<li><a class="sidenav-close" onclick="Javascript:goHome();"><i class="material-icons">dashboard</i>Dashboard</a></li>
            <li><a class="sidenav-close" onclick="Javascript:ajaxGo('/login/user');"><i class="material-icons">account_box</i>My Profile</a></li>
			<li><a class="sidenav-close" onclick="Javascript:showBugReport();"><i class="material-icons">bug_report</i>Report Bug</a></li>
			<li><a class="sidenav-close" onclick="Javascript:wtkLogout();"><i class="material-icons">close</i>Log Out</a></li>
		</ul>
	</div>
	<!-- end sidebar -->
htmVAR;
    return $fncHtm;
} // wtkNavBar
?>
