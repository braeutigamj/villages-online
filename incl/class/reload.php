<?
//Lädt alle möglichen Punkte neu
class reload {

  // Neuberechnung der Bauernhofplätze von Dorf
  function bh($hid) {
    global $building, $units, $db;
    //Plätze der Gebäude:
    $bh = 0;
    $village = $db->assoc("SELECT * FROM village WHERE id = '$hid'");
    foreach($building->name as $item => $key) {
      for($i = 0; $i < $village[$item]; $i++) {
        $bh += round($building->food[$item]*$i*0.224);
      }
    }  //Jetzt Berechnen der Einheiten
    foreach($units->name as $item => $key) {
      $bh += $units->food[$item] * $village[$item];
    }  //Jetzt Unterstützungen Berechnen
    $result= $db->fetch("SELECT * FROM support WHERE from_village = '$hid'");
    while ($support = $result->fetch_array()) {
      $unit = explode(",", $support['units']);
      $i = 0;
      foreach($units->name as $item => $key) {
        $bh += $units->food[$item] * $unit[$i];
      $i++;
      }
    }
    $db->no_query("UPDATE village SET food = '$bh' WHERE id = '$hid'");
    return $bh;
  }

  // Berechnet alle Punkte einer Allianz
  function ally_points($allyid) {
    global $db;
    $result = $db->fetch("SELECT player_id, level FROM ally_user_list WHERE ally_id = '$allyid'");
    $gespoints = 0;
    while ($player = $result->fetch_array()) {
        if($player['level'] >= 2) {
          $is = true;
        }
        $playerpoints = $db->query("SELECT points FROM user WHERE id = '".$player['player_id']."'");
        $gespoints += $playerpoints;
    }
    if($is) {
      $db->no_query("UPDATE ally SET `points` = '$gespoints' WHERE id = '$allyid'");
    } else {
      $db->no_query("DELETE FROM `ally` WHERE id = '$allyid'");
    }
    return $gespoints;
  }

  //Berechnet alle Punkte aller Allianzen neu! BUGGY
  function all_ally_points() {
    global $db;
    $ges = 0;
    $result = $db->fetch("SELECT id FROM ally");
    while ($id = $result->fetch_array()) {
      $ges += $this->ally_points($id['id']);
    }
    return $ges;
  }

  /* Berechnet Punkte eines Dorfes neu
  */
  function village_points($hid) {
  global $building, $db;
    $villagepoints = 0;
    $village = $db->assoc("SELECT * FROM village WHERE id = '$hid'");
    foreach($building->name as $item => $key) {
      for($i = 0; $i < $village[$item]; $i++) {
        $villagepoints += round($building->points[$item] * 0.5 * $village[$item]) + 1;
      }
    }
    $db->no_query("UPDATE village SET `points` = '$villagepoints' WHERE id = '$hid'");
    return $villagepoints;
  }

  //Berechnet die Punkte eines Spielers neu
  function user_points($id) {
  global $db;
    $gesp = 0;
    $result = $db->fetch("SELECT id FROM village WHERE userid = '$id'");
    while($villages = $result->fetch_array()) {
      $gesp += $this->village_points($villages['id']);
    }
    $db->no_query("UPDATE user SET `points` = '$gesp'");
  }
  //Berechnet die Resourcen eines Users neu
  function ressourcereload($userid) {
  }

}  //Klasse Ende
?>
