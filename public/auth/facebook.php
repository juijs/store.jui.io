<?php
error_reporting(E_ALL);


require_once __DIR__."/../../bootstrap.php";

use OAuth\OAuth2\Service\Facebook;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;


// Session storage
$storage = new Session();
// Setup the credentials for the requests

$credentials = new Credentials(
	$servicesCredentials['facebook']['key'],
    $servicesCredentials['facebook']['secret'],
    $currentUri->getAbsoluteUri()
);

$facebookService = $serviceFactory->createService('facebook', $credentials, $storage, array());
if (!empty($_GET['code'])) {
    // This was a callback request from facebook, get the token
    $token = $facebookService->requestAccessToken($_GET['code']);
    // Send a request with it
    $result = json_decode($facebookService->request('/v2.5/me'), true);
    // Show some of the resultant data
    //echo 'Your unique facebook user id is: ' . $result['id'];
	//var_dump($result['id']);

	if ($result['id']) {
		$_SESSION['login'] = true;
		$_SESSION['login_type'] = 'facebook';
		$_SESSION['userid'] = $result['id'];
		$_SESSION['username'] = $result['name'];
		$_SESSION['avatar'] = "https://graph.facebook.com/".$result['id']."/picture?type=small";

		include_once "save.user.php";

	} else {
		echo "Login failed.";
	}

	include_once "callback.php";

} elseif (!empty($_GET['go']) && $_GET['go'] === 'go') {
    $url = $facebookService->getAuthorizationUri();
    header('Location: ' . $url);
} else {
    $url = $currentUri->getRelativeUri() . '?go=go';
	header('Location: ' . $url);
    //echo "<a href='$url'>Login with Facebook!</a>";
}
?>
