<?PHP
$pgSecurityLevel = 1;
define('_RootPATH', '../');
require('../wtk/wtkLogin.php');

$pgCustomURL = wtkSqlGetOneResult('SELECT `KwikSlug` FROM `wtkUsers` WHERE `UID` = ?', [$gloUserUID]);

if ($pgCustomURL == ''):
    $pgDemo = 'yourName';
    $pgLabelActive = '';
else:
    $pgDemo = $pgCustomURL;
    $pgLabelActive = 'active';
endif;

$pgHtm =<<<htmVAR
<form id="FmyCardURL" name="FmyCardURL" method="POST">
    <span id="formMsg" class="red-text">$gloFormMsg</span>
    <div class="row">
        <div class="col s12">
            <h4>Customize Your URL</h4>
            <br><p>This will make it so you can give people your KwikLink URL like:</p>
            <br>
        </div>
        <div class="col m6 s12">
            your custom URL:<br>
            <span class="blue-text">$gloWebBaseURL/<b id="customSlug">$pgDemo</b></span>
        </div>
        <div class="col m6 s12">
            instead of the normal:<br>
            <span class="blue-text">$gloWebBaseURL/<b>card.php?id=$gloUserUID</b></span>
        </div>
        <div class="col s12"><br><br></div>
        <div class="input-field col s12">
            <input type="text" id="customURL" name="customURL" value="$pgCustomURL">
            <label for="customURL" class="$pgLabelActive">Your Short Custom URL</label>
        </div>
        <div class="col s12">
            <br>
            <div class="center">
                <a onclick="JavaScript:verifySlug()" class="btn">Check Availability</a>
            </div>
            <br>
            <p>Customized URL is a premium feature but you are free to give it a 60-day trial.
                <br>After that we&rsquo;ll give you the option to pay
                a one-time $5 fee or have your account switched back to using the normal link.</p>
        </div>
    </div>
</form>

<script type="text/javascript">
function verifySlug(){
    let fncCustomURL = $('#customURL').val();
    waitLoad('on');
    $.ajax({
        type: 'POST',
        url: 'ajxVerifyNewSlug.php',
        data: { apiKey: pgApiKey, newSlug: fncCustomURL},
        success: function(data) {
            waitLoad('off');
            let fncJSON = $.parseJSON(data);
            if (fncJSON.result == 'ok'){
                M.toast({html: 'Success - this is now reserved for your custom URL!', classes: 'rounded green'});
                let fncId = document.getElementById('modalWTK');
                let fncModal = M.Modal.getInstance(fncId);
                fncModal.close();
                $('#shareLink').html('<a href="https://kwiklink.me/' + fncCustomURL + '" target="_blank">https://kwiklink.me/' + fncCustomURL + '</a>')
            } else {
                M.toast({html: 'That custom name is already taken - choose another.', classes: 'rounded orange'});
            }
        }
    })

}
</script>
htmVAR;

echo $pgHtm;
exit;
?>
