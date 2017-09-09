<?
if(!isset($_SESSION)) {
  session_start();
}

/********************
Konfigurationsdatei! GLOBAL
********************/

//error_reporting(0);


/********************
Datenbankkonfiguration
********************/

//Globale Datenbank
$dbconf['global']['server'] = "localhost";    //Datenbankhost
$dbconf['global']['user'] = "vl";             //Datenbankuser
$dbconf['global']['pw'] = "";                 //Datenbankpasswort
$dbconf['global']['name'] = "vl_main";        //Datenbankname

//Datenbank Welt 1
$dbconf['w1']['server'] = "localhost";        //Datenbankhost
$dbconf['w1']['user'] = "vl";                 //Datenbankuser
$dbconf['w1']['pw'] = "";                     //Datenbankpasswort
$dbconf['w1']['name'] = "vl_w1";              //Datenbankname

//Datenbank Welt 2
$dbconf['w2']['server'] = "localhost";        //Datenbankhost
$dbconf['w2']['user'] = "vl";                 //Datenbankuser
$dbconf['w2']['pw'] = "";                     //Datenbankpasswort
$dbconf['w2']['name'] = "vl_w1";              //Datenbankname

//Starte Globale Datenbank
if(!isset($nodb)) {
  $dblogin = new db("global");
}


/********************
System Konfiguration
********************/

$config['board'] = "";  //Forumslink
$config['url'] = "http://villages-online.intern/";  //Haupturl
$config['cdn'] = "http://villages-online.intern/cdn/";  //Grafikurl
$config['email'] = "webmaster@villages-online.intern";  //SystemEmail
$config['help'] = "http://villages-online.intern/help/";  //Hilfeseite
$config['w1'] = "http://villages-online.intern/w1/";  //Welt1Url
$config['w2'] = "http://villages-online.intern/w2/";  //Welt1Url
//Zeichenschlüssel zur Passwortverschlüsselung
//(Achtung nur ändern bei Reset aller DBS!)
$config['sec_pw'] = "hiwgkkgvmz";
//Zeichenschlüssel zur Passwortverschlüsselung Teil 2
$config['sec_pwz'] = "ximajzsysz";
//Meldung die beim Start oder bei Spielstopp angezeigt wird
$config['error'] = "";
$config['stop'] = false;  //Auf true wenn Welten paussiert werden sollen
//Seiten die auch bei Stopp noch aufgerufen werden dürfen
$config['allowed'] = array("map", "admin", "friends", "splayer", "sally",
    "ally", "mail", "ranking", "settings", "extra", "note", "forum", "shout");
//Soviele Datensätze werden in der Rangliste pro Seite angezeigt
$config['ranksite'] = 15;
//Tutorial
$config['tutorial'] = 0;
//Facebook
$config['fb']['appId'] = '';
$config['fb']['secret'] = '';


/********************
Ausehen bearbeiten
********************/

$config['name'] = "Villages-Online";   //Gamename
$config['slogan'] = "Villages-Online, dass neue Mittelalterbrowsergame!";
$config['description'] = "
  In diesem kostenlose, Echtzeit Multiplayer Mittelalter Browsergame beginnst
  du mit einem kleinen Dorf. Dieses baust du aus um deinen Machtbereich zu
  erweitern. Das Ziel ist es Alleinherscher oder Herscher mit deiner Allianz zu
  werden! ";
$config['keyword'] = "
  Mittelalter, Browsergame, MiddleWar, Allianz, Dörfer, Spieler, Multiplayer,
  Echtzeit, Dorf";  //Keywords durch Kommas getrennt


/********************
Gebäude bearbeiten
********************/

//Format: $name, $de, $points, $level_max, $wood, $clay, $iron, $food, $time, $need
if(isset($building)) {
  $building->add_building("main", "Hauptgebäude", "10", "15", "90", "80", "70", "5", "1080", "");
  $building->add_building("barracks", "Barracke", "16", "13", "200", "170", "90", "7", "1800", "main:2");
  $building->add_building("stable", "Stall", "20", 10, 270, 240, 260, 8, 6000, "main:7,barracks:3,smith:3");
  $building->add_building("garage", "Werkstatt", 24, 7, 300, 240, 260, 8, 6000, "main:6,smith:5");
  $building->add_building("smith", "Schmiede", 19, 10, 220, 180, 240, 4, 6000, "main:3,barracks:1");
  $building->add_building("settlerplace", "Siedlerstätte", 512, 1, 15000, 25000, 10000, 80, 64800, "main:14,smith:10,market:8");
  $building->add_building("place", "Dorfplatz", 0, 1, 10, 40, 30, 0, 2000, "");
  $building->add_building("market", "Marktplatz", "10", "13", "100", 100, 100, 9, 2700, "main:2,barracks:2");
  $building->add_building("wood", "Holzfäller", 6, 20, 50, 60, 40, 5, 900, "");
  $building->add_building("clay", "Lehmgrube", 6, 20, 65, 50, 40, 10, 900, "");
  $building->add_building("iron", "Eisenmine", 5, 20, 45, 40, 30, 1, 1080, "");
  $building->add_building("farm", "Farm", 5, 30, 45, 40, 30, 0, 1440, "");
  $building->add_building("storage", "Speicher", 6, 30, 60, 50, 30, 0, 1224, "");
  $building->add_building("wall", "Wall", 8, 20, 50, 100, 20, 5, 3600, "barracks:1");
}


/********************
Einheiten bearbeiten
********************/

//Format: type, name, de, wood, clay, iron, food, time, move_time, att, deff, deffcav, booty
if(isset($units)) {
  $units->add_unit(1, "spear", "Speerk&auml;mpfer", "50", "30", "10", "1", "1000", "720", "10", "15", "20", "25");
  $units->add_unit(1, "sword", "Schwertk&auml;mpfer", 30, 30, 70, 1, 1500, 900, 25, 50, 24, 15);
  $units->add_unit(1, "axe", "Axtk&auml;mpfer", 60, 30, 40, 1, 1250, 720, 40, 10, 5, 10);
  $units->add_unit(1, "archer", "Bogensch&uuml;tze", 100, 30, 60, 1, 1250, 1080, 15, 50, 40, 10);
  $units->add_unit(2, "spy", "Späher", 50, 50, 20, 2, 1250, 360, 0, 2, 1, 0);
  $units->add_unit(2, "light", "leichter Reiter", 125, 100, 250, 4, 2400, 390, 130, 30, 40, 80);
  $units->add_unit(2, "marcher", "berittener Bogensch&uuml;tze", 250, 100, 150, 5, 1250, 600, 120, 40, 30, 50);
  $units->add_unit(2, "heavy", "schwerer Reiter", 200, 150, 600, 6, 3600, 450, 150, 200, 80, 50);
  $units->add_unit(3, "ram", "Ramme", 300, 200, 200, 5, 1250, 1200, 2, 20, 50, 0);
  $units->add_unit(3, "catapult", "Katapult", 320, 400, 100, 8, 1250, 1440, 100, 100, 50, 0);
  $units->add_unit(5, "settler", "Siedler", 2800, 3000, 2500, 10, 12500, 1800, 30, 100, 50, 0);
}


/********************
DO NOT EDIT!
********************/

$config['image_url'] = $config['cdn']."img/";
?>
