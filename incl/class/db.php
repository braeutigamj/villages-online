<?
//Macht alle DB-Abfragen
class db {
	var $connection;

	function __construct($world = "global") {
		global $dbconf;
		$connection = mysqli_connect($dbconf[$world]['server'],$dbconf[$world]['user'],$dbconf[$world]['pw']);
		mysqli_select_db($connection,$dbconf[$world]['name']);
		$this->connection = $connection;
	}

	//mysql_query("ASD");
	function query($string) {
		$result = mysqli_query($this->connection, $string) or die($string);
		$result = mysqli_fetch_assoc($result);
		if($result <= 0 ) {
			$return = false;
		} else {
			$return = array_values($result);
			$return = $return[0];
		}
		return $return;
	}
	//while ($babal = $result->fetch_array()) { }
	function fetch($string) {
		$result = mysqli_query($this->connection, $string) or die($string);
		//$return = $result->fetch_array();
		return $result;
	}

	//Auslessen der ganzen Zeile
	function assoc($string) {
		$result = mysqli_query($this->connection, $string) or die($string);
		$return = mysqli_fetch_assoc($result);
		return $return;
	}

	//Ob mindestens 1 Ergebnis
	function exist($string) {
		if(!$result = mysqli_query($this->connection, $string)) {
			return false;
		} else {
			if(mysqli_affected_rows($this->connection) < 1) {
				return false;
			} else {
				return true;
			}
		}
	}

	//gleich mysql_query nur mit geringeren Speicherverbrauch (wenn möglich verwenden!)
	function no_query($string) {
		mysqli_real_query($this->connection, $string) or die($string);
	}
								//or sleep(10)
}
?>
