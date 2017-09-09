<?
require_once "incl/function.php";
require_once "config.php";
$reload = new reload;
$zeit = time();
//Aktualliesiert die aktuelle Runde
$startdate = date("Y-m-d");
$result = $dblogin->fetch("SELECT * FROM `rounds` WHERE `start_date` = '$startdate'");
while($data = $result->fetch_array()) {
	$mm = explode("-", $startdate);
	if($zeit >= (tosek($data['start_time'])+mktime(0, 0, 0, $mm[1], $mm[2], $mm[0]))) {	//Alle zu bearbeitenden Datensätze
		$db = new db('w'.$data['world']);
		//leert alle Datebanken
		$db->no_query("TRUNCATE TABLE `ally`");
		$db->no_query("TRUNCATE TABLE `ally_board_answer`");
		$db->no_query("TRUNCATE TABLE `ally_board_thread`");
		$db->no_query("TRUNCATE TABLE `ally_categorie`");
		$db->no_query("TRUNCATE TABLE `ally_invite`");
		$db->no_query("TRUNCATE TABLE `ally_user_list`");
		$db->no_query("TRUNCATE TABLE `build`");
		$db->no_query("TRUNCATE TABLE `friends`");
		$db->no_query("TRUNCATE TABLE `group`");
		$db->no_query("TRUNCATE TABLE `market`");
		$db->no_query("TRUNCATE TABLE `market_angebot`");
		$db->no_query("TRUNCATE TABLE `message`");
		$db->no_query("TRUNCATE TABLE `message_answer`");
		$db->no_query("TRUNCATE TABLE `mountain`");
		$db->no_query("TRUNCATE TABLE `movement`");
		$db->no_query("TRUNCATE TABLE `report`");
		$db->no_query("TRUNCATE TABLE `ressource`");
		$db->no_query("TRUNCATE TABLE `shoutbox`");
		$db->no_query("TRUNCATE TABLE `smith`");
		$db->no_query("TRUNCATE TABLE `support`");
		$db->no_query("TRUNCATE TABLE `train`");
		$db->no_query("TRUNCATE TABLE `user`");
		$db->no_query("TRUNCATE TABLE `village`");
		$db->no_query("TRUNCATE TABLE `session`");
		//Schreibe die neuen Weltdaten
		$rconfig = unserialize($data['config']);
		$dblogin->no_query("UPDATE `worlds` SET `startdate` = '$startdate', `enddate` = '".$data['end_date']."', `endtime` = '".$data['end_time']."', `speed` = '".$rconfig['speed']."', `w_bonus` = '".$rconfig['wall']."', `max_ally`= '".$rconfig['ally']."', `tutorial` = '".$rconfig['tutorial']."' WHERE id = '".$data['world']."'");
		echo "true";
	}
}
?>
