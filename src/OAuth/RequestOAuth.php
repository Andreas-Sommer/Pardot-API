<?php
require_once ('PardotOAuth.php');

$code = isset($_GET['code']) ? $_GET['code'] : '';
$html = '<form>
<label>client_id</label>
<input type="text" name="client_id"><br>
<label>client_secret</label>
<input type="text" name="client_secret"><br>
<label>Domain</label>
<input type="text" name="domain"><br>
<input type="hidden" name="code" value="' . $code . '">
<input type="submit" value="Submit">
</form>';

$oAuth = null;
if(isset($_GET['client_id']) && isset($_GET['client_secret']))
{
    $oAuth = new \CyberDuck\Pardot\OAuth\PardotOAuth(
        $_GET['client_id'],
        $_GET['client_secret'],
        sprintf('https://%s%s', $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']),
        $_GET['domain']
    );
    $html = '';
}

if ($oAuth === null) {

    echo $html;
    return;
}

if(isset($_GET['code']) && $_GET['code'] !== '')
{
    $html = $oAuth->getAccessToken($_GET['code']);
}
else
{
    $html .= '<a href="' . $oAuth->getAuthorizationUri() . '" target="_blank">Connect with Pardot</a><br>';
}

echo $html;


