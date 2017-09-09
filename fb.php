<?
require 'fb/facebook.php';
require_once "incl/function.php";
$isglobal = 1;
require_once "config.php";
$db = new db("global");
$session = new session;
// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook($config['fb']);

// Get User ID
$user = $facebook->getUser();

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/'.$user);
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $loginUrl = $facebook->getLoginUrl(array('scope' => 'email'));
	header("Location: ".$loginUrl);
}
if(isset($user_profile['id'])) {
if($db->exist("SELECT `id` FROM `login` WHERE `fbid` = '".$user_profile['id']."'")) {
	$userinfo = $db->query("SELECT `id` FROM `login` WHERE `fbid` = '".$user_profile['id']."'");
	$session->create($userinfo);
	header("Location: index.php");
} else {	//Account muss erstellt werden
header("Location: index.php?page=register&fbid=".$user_profile['id']."&user=".$user_profile['username']."&email=".$user_profile['email']."");
exit;
}
}
?>
