<?php

require_once __DIR__."/../../bootstrap.php";

use OAuth\OAuth2\Service\GitHub;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;


// Session storage
$storage = new Session();
// Setup the credentials for the requests

$credentials = new Credentials(
    $servicesCredentials['github']['key'],
    $servicesCredentials['github']['secret'],
    $currentUri->getAbsoluteUri()
);

$gitHub = $serviceFactory->createService('GitHub', $credentials, $storage, array('user'));
if (!empty($_GET['code'])) {
    // This was a callback request from github, get the token
    $gitHub->requestAccessToken($_GET['code']);
    $result = json_decode($gitHub->request('user'), true);
    //echo 'The first email on your github account is ' . $result[0];

	if ($result['login']) {
		$_SESSION['login'] = true;
		$_SESSION['login_type'] = 'github';
		$_SESSION['userid'] = $result['id'];
		$_SESSION['username'] = $result['login'];
		$_SESSION['avatar'] = $result['avatar_url'];

		include_once "save.user.php";
	}

	include_once "callback.php";

} elseif (!empty($_GET['go']) && $_GET['go'] === 'go') {
    $url = $gitHub->getAuthorizationUri();
	header('Location: ' . $url);
} else {
    $url = $currentUri->getRelativeUri() . '?go=go';
	header('Location: ' . $url);
}
?>
