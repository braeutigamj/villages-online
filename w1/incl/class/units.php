<?
class units {
	var $type;	//Ausbildungsort
	var $name;	//Kürzel z.b. sword
	var $de;	//Name: Schwert
	var $wood;	//Preis
	var $clay;	//Preis
	var $iron;	//Preis
	var $food;	//Preis
	var $time;	//Ausbildungsdauer
	var $move_time;	//Wandergeschwindigkeit
	var $att;	//Angriffsstärke
	var $deff;	//Verteidigungstärke gegen Infanterie
	var $defcav;	//Verteidigungstärke gegen Kavalerie
	var $booty;	//Beute

	function add_unit($type, $name, $de, $wood, $clay, $iron, $food, $time, $move_time, $att, $deff, $deffcav, $booty) {
		$this->type[$name] = $type;
		$this->name[$name] = $name;
		$this->de[$name] = utf8_decode($de);
		$this->wood[$name] = $wood;
		$this->clay[$name] = $clay;
		$this->iron[$name] = $iron;
		$this->food[$name] = $food;
		$this->time[$name] = $time;
		$this->move_time[$name] = $move_time;
		$this->att[$name] = $att;
		$this->deff[$name] = $deff;
		$this->deffcav[$name] = $deffcav;
		$this->booty[$name] = $booty;
	}


}
?>
