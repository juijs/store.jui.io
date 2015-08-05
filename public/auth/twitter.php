<?php

require_once __DIR__."/../../bootstrap.php";

use OAuth\OAuth1\Service\Twitter;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;

// We need to use a persistent storage to save the token, because oauth1 requires the token secret received before'
// the redirect (request token request) in the access token request.
$storage = new Session();
// Setup the credentials for the requests
$credentials = new Credentials(
    $servicesCredentials['twitter']['key'],
    $servicesCredentials['twitter']['secret'],
    $currentUri->getAbsoluteUri()
);
// Instantiate the twitter service using the credentials, http client and storage mechanism for the token
/** @var $twitterService Twitter */
$twitterService = $serviceFactory->createService('twitter', $credentials, $storage);
if (!empty($_GET['oauth_token'])) {
    $token = $storage->retrieveAccessToken('Twitter');
    // This was a callback request from twitter, get the token
    $twitterService->requestAccessToken(
        $_GET['oauth_token'],
        $_GET['oauth_verifier'],
        $token->getRequestTokenSecret()
    );
    // Send a request now that we have access token
    $result = json_decode($twitterService->request('account/verify_credentials.json'));
    //echo 'result: <pre>' . print_r($result, true) . '</pre>';

	if ($result->id) {
		$_SESSION['login'] = true;
		$_SESSION['login_type'] = 'twitter';
		$_SESSION['userid'] = $result->id;
		$_SESSION['username'] = $result->name;
		$_SESSION['avatar'] = $result->profile_image_url;

		include_once "save.user.php";
	}

	include_once "callback.php";

} elseif (!empty($_GET['go']) && $_GET['go'] === 'go') {
    // extra request needed for oauth1 to request a request token :-)
    $token = $twitterService->requestRequestToken();
    $url = $twitterService->getAuthorizationUri(array('oauth_token' => $token->getRequestToken()));
    header('Location: ' . $url);
} else {
    $url = $currentUri->getRelativeUri() . '?go=go';
	header('Location: ' . $url);
    //echo "<a href='$url'>Login with Twitter!</a>";
}
?>
