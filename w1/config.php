<?
/********************
Include Konfiguration Global
********************/
if(!isset($isglobal)) {
  include "../config.php";
}

/********************
Spielkonfiguration
********************/
$configload = $dblogin->assoc("SELECT * FROM `worlds` WHERE id = '1'");
$config = array_merge($configload, $config);
$config['worker'] = 3;    //Arbeiterbonus (pro Stufe gibt es X Arbeiter dazu)
$config['ally'] = 5;    //Preis für eine Allianzgründung
$config['agreement_speedz'] = 3600;  //Zustimmungsspeed
$config['agreement'] = false;    //Zustimmung abhängig vom Gamespeed? (true/false)
if($config['agreement']) {
  $config['agreement_speed'] = round($config['agreement_speedz']/($config['speed']/2))+1;  //Gamespeed Abhängigkeit
} else {
  $config['agreement_speed'] = $config['agreement_speedz'];
}
$config['max_res'] = array(1 => 1000, 1200, 1510, 1900, 2200, 2800, 3400, 4400, 5200, 6400, 7900, 9600, 11200, 14300, 18400, 22100, 27640, 33420, 44200, 50000, 63000, 76000, 95000, 113421, 142031, 178021, 241500, 371500, 400000, 500000);  //Rohstoffspeicher (siehe auch /incl/function.php)
$config['max_food'] = array(1 => 250, 280, 340, 420, 560, 610, 710, 840, 980, 1140, 1354, 1692, 1932, 2100, 2500, 3000, 3502, 4000, 4800, 5400, 6500, 7521, 8821, 10321, 12320, 14521, 20131, 26231, 28000, 30000);        //Farmplätze (siehe auch /incl/function.php)
?>
