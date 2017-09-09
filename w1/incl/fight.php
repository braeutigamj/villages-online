<?
error_reporting(0);
function restpunkte_berechnen($p_gewinner, $p_verlierer) {
  $restpunkte = $p_gewinner - $p_verlierer;
  $faktor = pow(($p_gewinner/$p_verlierer), 0.113);
  $restpunkte = $restpunkte * $faktor;
  if($restpunkte > $p_gewinner) {
    $restpunkte = $p_gewinner;
  }
  return $restpunkte;
}

//Berechnet den gewinner eines Kampfes
function make_fight($att, $def, $other) {
  global $config, $units;

  $att = explode(",", $att);
  $def = explode(",", $def);
  $i = 0;
  foreach($units->name as $item => $key) {
    $att[$item] = $att[$i];
    $def[$item] = $def[$i];
    $i++;
  }

  $att_fus = 0;
  $att_kal = 0;
  $def_fus = 0;
  $def_kal = 0;
  $att_punkte_pro_einheit = array();
  $def_punkte_pro_einheit_kav = array();
  $def_punkte_pro_einheit_fus = array();

  foreach($units->name as $item => $key) {
    if($units->type[$item] == 2) {
      $att_kal += $att[$item] * $units->att[$item] *
          ($other['tech']['att'][$item] / 2 + 1);
      $def_punkte_pro_einheit_kav[$item] = $def[$item] *
          $units->deffcav[$item] * ($other['tech']['def'][$item] / 2 + 1);
    } else {
      $att_fus += $att[$item] * $units->att[$item] *
          ($other['tech']['att'][$item] / 2 + 1);
      $def_punkte_pro_einheit_fus[$item] = $def[$item] * $units->deff[$item] *
          ($other['tech']['def'][$item] / 2 + 1);
    }
    $att_punkte_pro_einheit[$item] = $att[$item] * $units->att[$item] *
        ($other['tech']['att'][$item] / 2 + 1);
    $def_fus += $def[$item] * $units->deff[$item] *
        ($other['tech']['def'][$item] / 2 + 1);
    $def_kal += $def[$item] * $units->deffcav[$item] *
        ($other['tech']['def'][$item] / 2 + 1);
  }
  $angriffspunkte = $att_fus + $att_kal;
  $prozent_fus = 100 / $angriffspunkte * $att_fus;
  $prozent_kal = 100 / $angriffspunkte * $att_kal;
  $verteidigerpunkte = ($def_fus * $prozent_fus / 100) +
      ($def_kal * $prozent_kal / 100);
  $verteidigerpunkte_ohne_bonus = $verteidigerpunkte;

  //Verteidigungsbonus mit einbeziehen
  $verteidigerpunkte *= ($other['wall'] * $config['w_bonus'])+1;
  $verteidigerpunkte *= ($other['miliz'] / 100) + 1;
  if($angriffspunkte > $verteidigerpunkte) {
    $winner = "off";
    $def_lose = $def;
    $att_lose = array();
    $restpunkte = restpunkte_berechnen($angriffspunkte, $verteidigerpunkte);
    foreach($units->name as $item => $key) {
      $att_lose[$item] = $att[$item] - floor(
          ($restpunkte / array_sum($att_punkte_pro_einheit) *
          $att_punkte_pro_einheit[$item]) /
          ($units->att[$item] * ($other['tech']['att'][$item] / 2 + 1)));
    }
  } else {
    $winner = "deff";
    $att_lose = $att;
    if($verteidigerpunkte > $verteidigerpunkte_ohne_bonus) {
      $verteidigerpunkte = $verteidigerpunkte_ohne_bonus;
    }
    $restpunkte = restpunkte_berechnen($verteidigerpunkte, $angriffspunkte);
    $restpunkte_fus = $restpunkte * ((array_sum($def_punkte_pro_einheit_fus) +
        array_sum($def_punkte_pro_einheit_kav)) /
        array_sum($def_punkte_pro_einheit_fus));
    $restpunkte_kav = $restpunkte * ((array_sum($def_punkte_pro_einheit_fus) +
        array_sum($def_punkte_pro_einheit_kav)) /
        array_sum($def_punkte_pro_einheit_kav));
    $def_lose = array();
    foreach($units->name as $item => $key) {
      if($units->type[$item] == 2) {
        $def_lose[$item] = $def[$item] - floor(
            ($restpunkte_kav / array_sum($def_punkte_pro_einheit_kav) *
            $def_punkte_pro_einheit_kav[$item]) /
            ($units->deffcav[$item] * ($other['tech']['def'][$item] / 2 + 1)));
      } else {
        $def_lose[$item] = $def[$item] - floor(($restpunkte_fus /
            array_sum($def_punkte_pro_einheit_fus) *
            $def_punkte_pro_einheit_fus[$item]) / ($units->deff[$item] *
            ($other['tech']['def'][$item] / 2 + 1)));
      }
    }
    //Späher dürfen evtl. auch bei Niederlage überleben!
    if($att['spy'] > $def['spy']*2) {
      $att_lose['spy'] = $def['spy']*1.5;
    }
  }
  return array(
      "att"=>$att,
      "att_lose"=>$att_lose,
      "def"=>$def,
      "def_lose"=>$def_lose,
      "winner"=>$winner);
}
?>
