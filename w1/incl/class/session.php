<?
//Erstellt die Session
class session {

	//Erstellt eine neue Session
	function create($userid, $cookie = false, $world = "global") {
		global $_SERVER, $_SESSION, $config;
		$dblogin = new db($world);
		$sessioncode = md5(spezielstring(10).$userid.spezielstring(10));
		$cookiename = md5(spezielstring(20));
		$dblogin->no_query("INSERT INTO `session`(`userid`, `sessioncode`, `client`, `cookiename`) VALUES ('$userid', '$sessioncode', '".$_SERVER['REMOTE_ADDR']."', '$cookiename')");
		$serverurl = explode("/", $config['url']);
		if($cookie) {
			$expire = time()+2592000000;
		} else {
			$expire = time()+86400;
		}
		setcookie($cookiename, $sessioncode, $expire, "/", $serverurl[2], false, true);
	}

	//Checkt ob Sessions vorhanden sind
	function check($world = "global") {
		global $_SERVER, $_COOKIE;
		$dblogin = new db($world);
			if($dblogin->exist("SELECT `userid` FROM `session` WHERE client = '".$_SERVER['REMOTE_ADDR']."'") > 0) {
				$user = $dblogin->assoc("SELECT `userid`, `sessioncode`, `client`, `cookiename` FROM `session` WHERE client = '".$_SERVER['REMOTE_ADDR']."'");
				if(isset($user['sessioncode']) and $user['sessioncode'] == $_COOKIE[$user['cookiename']]) {
					$return = $user['userid'];
				} else {
					$dblogin->no_query("DELETE FROM `session` WHERE userid = '".$user['userid']."'");
					$return = false;
				}
			} else {
				$return = false;
			}
		return $return;
	}

	//Löscht aktuelle Session
	function destroy($userid, $world = "global") {
		global $db, $_SESSION;
		$dblogin = new db($world);
		$user = $dblogin->assoc("SELECT `cookiename` FROM `session` WHERE userid = '$userid'");
		$expire = time()-1;
		setcookie($user['cookiename'], "destroy", $expire);
		$dblogin->no_query("DELETE FROM `session` WHERE userid = '$userid'");
		session_destroy();
		return true;
	}
}
?>
