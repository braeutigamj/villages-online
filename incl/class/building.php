<?
class building {
	var $id;
	var $namez;
	var $name;
	var $de;
	var $points;
	var $level_max;
	var $wood;
	var $clay;
	var $iron;
	var $food;
	var $time;
	var $need;
	var $i;

	function add_building($name, $de, $points, $level_max, $wood, $clay, $iron, $food, $time, $need) {
		$this->i = $this->i+1;
		$this->id[$name] = $this->i;
		$this->namez[$this->i] = $name;
		$this->name[$name] = $name;
		$this->de[$name] = $de;
		$this->points[$name] = $points;
		$this->level_max[$name] = $level_max;
		$this->wood[$name] = $wood;
		$this->clay[$name] = $clay;
		$this->iron[$name] = $iron;
		$this->food[$name] = $food;
		$this->time[$name] = $time;
		$this->need[$name] = $need;
	}

	function get_name($id) {
	$name = $this->namez[$id];
	return $name;
	}

	function getneed($name)
	{
		global $hid;
		global $village;
		$need = $this->need[$name];
		if($need != "") {
			$needs = explode(",", $need);
			foreach ($needs as $wert)
			{
				$wert = explode(":", $wert);
				if($wert[1] > $village[$wert[0]])
				{
					$i = 1;
					return false;
				}
			}
			if(!isset($i)) 
			{ 
				return true;
			}
		} else {
			return true;
		}
	}
}
?>
