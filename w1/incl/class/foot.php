<?
class foot {

  function userup($village) {
    global $zeit,$db;
    $db->no_query("UPDATE `user` SET `last_activity` = '$zeit' WHERE id = '".$village['userid']."'");
}

  //Gebäude ausbauen
  function buildmaker($hid,$village) {
    global $zeit,$building, $reload, $db, $config;
    $result = $db->fetch("SELECT * FROM build WHERE village='$hid' AND end_time <= '$zeit'");
    while ($build = $result->fetch_array()) {
        if($village[$build['what']] <= $building->level_max[$build['what']]) {
            $pointss = round($building->points[$build['what']] * 0.5 * $village[$build['what']]) + 1;
            $sql = "UPDATE village SET ".$build['what']." = ".$build['what']." + 1, points=points+'".$pointss."'";
            if($build['what'] == "wood" || $build['what'] == "clay" || $build['what'] == "iron") {
                $sql .= ", arb".$build['what']." = arb".$build['what']." + ".$config['worker'];
            }
            $sql .= " WHERE id = '".$village['id']."'";
            $db->no_query($sql);
            $db->no_query("UPDATE user SET points = points+'".$pointss."' WHERE id = '".$village['userid']."'");
        }
        $db->no_query("DELETE FROM build WHERE id='".$build['id']."'");
    }
$reload->ressourcereload($village['userid']);
}

  ///Händlerbewegung zu Dorf
  function moveahandlertovil($hid) {
    global $zeit,$db;
    $result = $db->fetch("SELECT * FROM market WHERE to_village='$hid' AND end_time <= '$zeit'");
    while ($market = $result->fetch_array()) {
        foot::moveahandler($market['from_village']);
    }
}


  //Händlerbewegungen von DOrf
  function moveahandler($hid) {
    global $zeit, $reload, $db;
    $result = $db->fetch("SELECT * FROM market WHERE from_village='$hid' AND end_time <= '$zeit'");
    while ($market = $result->fetch_array()) {
        if($market['type'] == "send") {
            $village = $db->assoc("SELECT * FROM village WHERE id = '".$market['to_village']."'");
            $village['max_res'] = round($village['storage'] * 3000);
            $get_res = $market['wood'].",".$market['clay'].",".$market['iron'].",".$market['gold'];    //Jetzt schon hollen bevor noch was abgezogen wird^^
            if($market['wood']+$village['wood_r'] > $village['max_res']) { $market['wood'] = $village['max_res']-$village['wood_r']; }
            if($market['clay']+$village['clay_r'] > $village['max_res']) { $market['clay'] = $village['max_res']-$village['clay_r']; }
            if($market['iron']+$village['iron_r'] > $village['max_res']) { $market['iron'] = $village['max_res']-$village['iron_r']; }
            $db->no_query("UPDATE village SET wood_r = wood_r + '".$market['wood']."', clay_r = clay_r + '".$market['clay']."', iron_r = iron_r + '".$market['iron']."', gold = gold + '".$market['gold']."' WHERE id = '".$market['to_village']."'");
            $db->no_query("UPDATE market SET end_time = end_time + '".$market['move_time']."', type = 'return' WHERE id = '".$market['id']."'");
            $from_u = givevilluid($hid);
            $to_u = givevilluid($market['to_village']);
            $db->no_query("INSERT INTO `report`(`userid`, `type`, `att`, `deff`, `booty`, `readed`, `time`) VALUES ('$from_u', 'market_send', '$hid', '".$village['id']."', '$get_res', '0', '$zeit')");
            $db->no_query("UPDATE user SET report = '1' WHERE id = '$from_u'");
            $db->no_query("INSERT INTO `report`(`userid`, `type`, `att`, `deff`, `booty`, `readed`, `time`) VALUES ('$to_u', 'market_get', '$hid', '".$village['id']."', '$get_res', '0', '$zeit')");
            $db->no_query("UPDATE user SET report = '1' WHERE id = '$to_u'");
            $reload->ressourcereload(givevilluid($market['to_village']));
        } elseif($market['type'] == "return") {
            $db->no_query("DELETE FROM market WHERE id = '".$market['id']."'");
        }
    }
}

  //Rekrutierungen
  function rekruitwache($hid) {
    global $zeit, $db;
    $result = $db->fetch("SELECT * FROM train WHERE village='$hid' AND time_start <= '$zeit'");
    while ($train = $result->fetch_array()) {
        if($train['end_time'] <= $zeit) {
            $rekrutiert = $train['times'] - $train['finished'];
            $db->no_query("DELETE FROM train WHERE id='".$train['id']."'");
        } else {
            $timeverg = $zeit - $train['time_start'];
            $u_time = $train['build_time'];
            $timeverg = $timeverg - ($u_time * $train['finished']);
            $rekrutiert = floor($timeverg / $u_time);
            $db->no_query("UPDATE train SET finished = finished + '$rekrutiert' WHERE id='".$train['id']."'");
        }
    $db->no_query("UPDATE village SET ".$train['what']." = ".$train['what']." + '$rekrutiert' WHERE id = '$hid'");
    }
}

  //Rohstoffe
  public static function generateres($hid,$village) {
    global $zeit, $db, $config;
    $ress_update = $db->query("SELECT ress_times FROM village WHERE id = '$hid'");
    if($ress_update < $zeit) {
        $ress_update = $zeit - $ress_update;
        $_SESSION['ress'] = $ress_update;
        $village['max_res'] = getmaxress($village['storage']);
        $wood = (($village['wood'] * $village['wood'] * 2.69 * $config['speed'] * ($village['arbwood'] / 2 / 100 + 1)) * $ress_update / 3600);
        if($wood+$village['wood_r'] > $village['max_res']) { $wood = $village['max_res']-$village['wood_r']; }
        $clay = (($village['clay'] * $village['clay'] * 2.69 * $config['speed'] * ($village['arbclay'] / 2 / 100 + 1)) * $ress_update / 3600);
        if($clay+$village['clay_r'] > $village['max_res']) { $clay = $village['max_res']-$village['clay_r']; }
        $iron = (($village['iron'] * $village['iron'] * 2.69 * $config['speed'] * ($village['arbiron'] / 2 / 100 + 1)) * $ress_update / 3600);
        if($iron+$village['iron_r'] > $village['max_res']) { $iron = $village['max_res']-$village['iron_r']; }
        $db->no_query("UPDATE village SET wood_r = wood_r + '$wood', clay_r = clay_r + '$clay', iron_r = iron_r + '$iron', ress_times = '".time()."' WHERE id = '$hid'");
    }
}


  //Forschung in der Schmiede
  function forschemich($hid, $village) {
    global $zeit, $db;
    $smith = $db->assoc("SELECT * FROM `smith` WHERE villageid = '$hid' AND end_time <= '$zeit'");
    if($smith['id'] > 0) {
        $db->no_query("UPDATE village SET `s".$smith['unit']."` = `s".$smith['unit']."`    +'1' WHERE id = '$hid'");
        $db->no_query("DELETE FROM `smith` WHERE id = '".$smith['id']."'");
    }
}

  //Zustimmung aktuallisieren
  function update_agrement($hid, $village) {
    global $zeit, $config, $db;
    if($village['agreement'] < 100) {
        if(($zeit - $village['agreement_time']) > $config['agreement_speed']) {
            $update_time = $zeit - $village['agreement_time'];
            $update_times = floor($update_time/$config['agreement_speed']);
            if($update_times > (100 - $village['agreement'])) {
                $update_times = 100 - $village['agreement'];
            }
            $update_zeit = $update_times * $config['agreement_speed'];
            $update_zeit = $village['agreement_time'] + $update_zeit;
            $db->no_query("UPDATE village SET `agreement` = agreement+'$update_times', `agreement_time` = '$update_zeit' WHERE id = '$hid'");
        }
    } else {
        $db->no_query("UPDATE `village` SET `agreement_time` = '$zeit' WHERE id = '$hid'");
    }
}
  //Führt alle Truppenbewegungen in Spielerdorf aus
  function othervil($hid){
    global $zeit, $db;
    //Führt alle berechnungen mit zu dorf aus
    $result = $db->fetch("SELECT * FROM movement WHERE to_village='$hid' AND end_time <= '$zeit'");
    while ($movement = $result->fetch_array()) {
        $village = $db->assoc("SELECT * FROM village WHERE id = '".$movement['from_village']."'");
        foot::dofightfrom($movement['from_village'], $village['userid'], $village);
    }
}


  //Führt alle Truppenbewegungen aus Spielerdorf aus || Eingabe: Dorfid, Userid von from_village!
  function dofightfrom($hid, $userid, $village) {
    global $zeit, $config,$reload,$units, $db;
    $result = $db->fetch("
        SELECT *
        FROM movement
        WHERE from_village='$hid'
          AND end_time <= '$zeit'");
    while ($movement = $result->fetch_array()) {
        if($movement['type'] == "support") {
            $db->no_query("INSERT INTO support (`from_village`, `to_village`, `units`) VALUES ('$hid', '".$movement['to_village']."', '".$movement['units']."')");
        } elseif($movement['type'] == "return") {
            $returnu = explode(",", $movement['units']);
            $sql = "Update village SET";
            $i = 0;
            foreach($units->name as $item => $key) {
                $sql .= " `".$item."` = `".$item."`+'".$returnu[$i]."',";
                $i++;
            }
            $sql = substr($sql, 0, -1);
            $sql .= " WHERE id = '".$movement['to_village']."'";
            $db->no_query($sql);
        } else {    //Wenn keine Unterstützung dan Angriff: folglich Angreifen!
            //Dorfinformation holen
            $to_village = $db->assoc("SELECT * FROM village WHERE id = '".$movement['to_village']."'");
            $to_u = givevilluid($movement['to_village']);
            //Erstmal Zieldorf updaten
            foot::buildmaker($movement['to_village'], $to_village);
            foot::rekruitwache($movement['to_village']);
            foot::forschemich($hid,$to_village);
            foot::generateres($movement['to_village'], $to_village);
            foot::update_agrement($movement['to_village'], $to_village);
            //Neu Holen da wirs ja geupdatet haben!
            $to_village = $db->assoc("SELECT * FROM village WHERE id = '".$movement['to_village']."'");
            //Deff Truppen für Verteidigung auslessen
            $gessupport = array();
            $result = $db->fetch("SELECT * FROM support WHERE to_village='".$movement['to_village']."'");
            while ($support = $result->fetch_array()) {
                $supportt = explode(",", $support['units']);
                $i = 0;
                foreach($units->name as $item => $key) {
                    if(!isset($gessupport[$i])) { $gessupport[$i] = 0; }
                    $gessupport[$i] += $supportt[$i];
                    $i++;
                }
            } //Nun müssen wir nur noch die Dorfeinheiten rein rechen und wieder ins richtige Format bringen
            for($i = 0; $i <= 12;) {
                if($gessupport == "" || !isset($gessupport[$i])) {
                    $gessupport[$i] = 0;
                }
                $i++;
            }
            $gesunit = "";
            $i = 0;
            $supportunit = "";
            $deff_unit = "";
            foreach($units->name as $item => $key) {
                $other['tech']['def'][$item] = $to_village['s'.$item];
                $other['tech']['att'][$item] = $village['s'.$item];
                $gesunit[$i] = $gessupport[$i] + $to_village[$item];
                $deff_unit .= $gesunit[$i].",";    //VerteidigerEinheiten wurden berechnet ;)
                $i++;
            }
            $other['wall'] = $to_village['wall'];
            $other['miliz'] = $to_village['arbmiliz'];
        $fight = make_fight($movement['units'], $deff_unit, $other);    //<= Kampf, jetzt Einheiten wirder auseinanderschicken^^
            //Hab nen bug mit den defs gmacht den fixma mal schnell^^
            $fight['deff'] = $fight['def'];
            $fight['deff_lose'] = $fight['def_lose'];
            $felder = sqrt(bcpow(($to_village['x'] - $village['x']),2) + bcpow(($to_village['y'] - $village['y']),2));
            $time_per_feld = 0;
            $return_u = "";
            $bhatt = 0;    //Berechnet die Bauernhofplätze
            foreach($units->name as $item => $key) {
                $bhatt += $fight['att_lose'][$item] * $units->food[$item];
                $fight['return'][$item] = $fight['att'][$item] - $fight['att_lose'][$item];
                $return_u .= $fight['return'][$item].",";
                if($time_per_feld < $units->move_time[$item] and $fight['return'][$item] > 0) {
                    $time_per_feld = $units->move_time[$item];
                    $travel_time = round(($felder * $time_per_feld) / $config['speed']);
                    $end_time = $travel_time + $movement['end_time'];
                }
            }
            $db->no_query("UPDATE village SET food = food-'$bhatt' WHERE id = '$hid'");
            //Userids werden für Berichte benötigt!
            $from_u = givevilluid($hid);
            $to_u = givevilluid($movement['to_village']);
            if($fight['winner'] == "off") {    //Angreifer hat gewonnen
                //Ramme macht spezialefekt
                if($fight['return']['ram'] >= 1) {
                    $wall_steps = round($fight['return']['ram'] / 4);
                    $end_wall = $to_village['wall'] - $wall_steps;
                    if($end_wall < 0) {
                        $end_wall = 0;
                    }
                    $update_wall = $to_village['wall'].",".$end_wall;    //<-Für Berichte
                    $db->no_query("UPDATE village SET wall = '$end_wall' WHERE id = '".$to_village['id']."'");
                }
                //Katapult macht spezialefekt PS: zielgebäude steht in booty ich mach doch ned für jeden kack ne extra spalte!^^
                if($fight['return']['catapult'] >= 1 and !empty($movement['booty'])) {
                    $kata_steps = round($fight['return']['catapult'] / 6);
                    $end_kata = $to_village[$movement['booty']] - $kata_steps;
                    if($end_kata < 0) {
                        $end_kata = 0;
                    }
                    //So jetzt der Sonderfall bei n paar gebäuden:
                    if($end_kata == 0 and
                      ($movement['booty'] == "main" ||
                       $movement['booty'] == "storage" ||
                       $movement['booty'] == "iron" ||
                       $movement['booty'] == "clay" ||
                       $movement['booty'] == "wood" ||
                       $movement['booty'] == "farm")) {
                        $end_kata = 1;
                    }
                    $update_building =
                        $movement['booty'].",".
                        $to_village[$movement['booty']].",".
                        $end_kata;//<- Für Berichte
                    $db->no_query("
                        UPDATE village
                        SET ".$movement['booty']." = '$end_kata'
                        WHERE id = '".$to_village['id']."'");
                }
                //Siedler macht spezialefekt
                if($fight['return']['settler'] >= 1) {
                    $down = mt_rand(25, 33);
                    $new_agreement = $to_village['agreement'] - $down;
                    if($new_agreement <= 0) {
                        $fight['return']['settler'] =
                            $fight['return']['settler'] - 1;
                        $db->no_query("
                            UPDATE village
                            SET `userid` = '".$userid."',
                              `agreement` = '30',
                              `agreement_time` = '$zeit'
                            WHERE id = '".$to_village['id']."'");
                        $db->no_query("
                            UPDATE village
                            SET food = food-'10'
                            WHERE id = '$hid'");
                        $fight['return']['settler'] = $fight['return']['settler'] - 1;
                        $db->no_query("
                            UPDATE user
                            SET points = points+'".$to_village['points']."'
                            WHERE id = '$userid'");
                    } else {
                        $db->no_query("
                            UPDATE village
                            SET `agreement` = '$new_agreement',
                              `agreement_time` = '$zeit'
                            WHERE id = '".$to_village['id']."'");
                    }
                    $report_agreement = $to_village['agreement'].",".$new_agreement;
                }
                $booty = "0,0,0";
                $db->no_query("INSERT INTO movement (`type`, `from_village`, `to_village`, `to_user`, `start_time`, `end_time`, `units`, `booty`) VALUES ('return', '".$movement['to_village']."', '$hid', '$from_u', '".$movement['end_time']."', '$end_time', '$return_u', '$booty')");    //Überlebende Zurückschicken
                $db->no_query("DELETE FROM support WHERE to_village = '".$movement['to_village']."'");    //Unterstützung töten
                $db->no_query("
                    UPDATE `village`
                    SET `wood_r` = wood_r - '".$to_village['wood_r']."',
                    `clay_r` = clay_r - '".$to_village['clay_r']."',
                    `iron_r` = iron_r - '".$to_village['iron_r']."'
                    WHERE userid = '".givevilluid($movement['to_village'])."'");
                $db->no_query("UPDATE village SET `spear`='0',`sword`='0',`axe`='0',`archer`='0',`spy`='0',`light`='0',`marcher`='0',`heavy`='0',`ram`='0',`catapult`='0',`settler`='0' WHERE id = '".$movement['to_village']."'");    //Eigene Verteidiger Einheiten töten
                //Berichte schreiben
                if(!isset($update_wall)) { $update_wall = ""; }
                if(!isset($update_building)) { $update_building = ""; }
                if(!isset($report_agreement)) { $report_agreement = ""; }
                $db->no_query("INSERT INTO `report`(`userid`, `type`, `att`, `att_lost`, `deff`, `deff_lose`, `booty`, `readed`, `time`, `att_dorf`, `deff_dorf`, `wall`, `what`, `agreement`) VALUES
            ('$from_u', 'attack_won', '".serialize($fight['att'])."', '".serialize($fight['att_lose'])."', '".serialize($fight['deff'])."', '".serialize($fight['deff_lose'])."', '$booty', '0', '$zeit', '$hid', '".$movement['to_village']."', '$update_wall', '$update_building', '$report_agreement')");
                $db->no_query("UPDATE user SET report = '1' WHERE id = '$from_u'");
                $db->no_query("INSERT INTO `report`(`userid`, `type`, `att`, `att_lost`, `deff`, `deff_lose`, `booty`, `readed`, `time`, `att_dorf`, `deff_dorf`, `wall`, `what`, `agreement`) VALUES ('$to_u', 'attack_won', '".serialize($fight['att'])."', '".serialize($fight['att_lose'])."', '".serialize($fight['deff'])."', '".serialize($fight['deff_lose'])."', '$booty', '0', '$zeit', '$hid', '".$movement['to_village']."', '$update_wall', '$update_building', '$report_agreement')");
                $db->no_query("UPDATE user SET report = '1' WHERE id = '$to_u'");
                $canlook = false;
            } else {    //Wenn Verteidiger gewinnt
                if($time_per_feld > 0) {    //Wenns überlebende angreifer gibt dan zurücksenden
                    $db->no_query("INSERT INTO movement (`type`, `from_village`, `to_village`, `to_user`, `start_time`, `end_time`, `units`) VALUES ('return', '".$movement['to_village']."', '$hid', '$from_u', '".$movement['end_time']."', '$end_time', '$return_u')");
                }
                $survival = $fight['deff_lose'];
                $canlook = true;
                $bhdeff = 0;
                foreach($units->name as $item => $key) {
                    $bhdeff += $fight['deff_lose'][$item] * $units->food[$item];
                    $survival[$item] = $survival[$item] - $to_village[$item];
                    if($survival[$item] < 0) {        //Berechnet die Überlebenden Truppen im Dorf selbst
                        $update[$item] = $to_village[$item] - $survival[$item];
                        $survival[$item] = 0;
                    } else {
                        $update[$item] = 0;
                    }
                }
                $db->no_query("UPDATE village SET `spear`='".$update['spear']."',`sword`='".$update['sword']."',`axe`='".$update['axe']."',`archer`='".$update['archer']."',`spy`='".$update['spy']."',`light`='".$update['light']."',`marcher`='".$update['marcher']."',`heavy`='".$update['heavy']."',`ram`='".$update['ram']."',`catapult`='".$update['catapult']."',`settler`='".$update['settler']."' WHERE id = '".$movement['to_village']."'");
                $reload->bh($movement['to_village']);
                //Das ganze jetzt nochmal für die Unterstützung ;)
                $result = $db->fetch("SELECT * FROM support WHERE to_village='".$movement['to_village']."'");
                while ($support = $result->fetch_array()) {
                    $to_support = explode(",", $support['units']);
                    $i = 0;
                    $badunit = false;
                    $returnu = "";
                    foreach($units->name as $item => $key) {
                        $to_support[$item] = $to_support[$i];
                        $survival[$item] = $survival[$item] - $to_support[$item];
                        if($survival[$item] < 0) {
                            $update[$item] = $to_support[$item] - $survival[$item];
                            $badunit = true;
                            $survival[$item] = 0;
                        } else {
                            $update[$item] = 0;
                        }
                    }
                    $returnu = $update[$item].",";
                    $i++;
                    $reload->bh($support['from_village']);
                    if($badunit) {
                        //upadte it
                        $db->no_query("UPDATE support SET units = '$returnu' WHERE id = '".$support['id']."'");
                    } else {
                        $db->no_query("DELETE FROM support WHERE id = '".$support['id']."'");
                    }
                }
                $reload->bh($support['from_village']);
                //Berichte schreiben
                if(!isset($booty) || empty($booty)) {
                    $booty = "0,0,0";
                }
                if($canlook) {
                    $db->no_query("INSERT INTO `report`(`userid`, `type`, `att`, `att_lost`, `deff`, `deff_lose`, `booty`, `readed`, `time`, `att_dorf`, `deff_dorf`) VALUES ('$from_u', 'attack_lost', '".serialize($fight['att'])."', '".serialize($fight['att_lose'])."', '".serialize($fight['deff'])."', '".serialize($fight['deff_lose'])."', '$booty', '0', '$zeit', '$hid', '".$movement['to_village']."')");
                } else {
                    $db->no_query("INSERT INTO `report`(`userid`, `type`, `att`, `att_lost`, `deff`, `deff_lose`, `booty`, `readed`, `time`, `att_dorf`, `deff_dorf`) VALUES ('$from_u', 'attack_lost', '".serialize($fight['att'])."', '".serialize($fight['att_lose'])."', 'N;', 'N;', '$booty', '0', '$zeit', '$hid', '".$movement['to_village']."')");
                }
                $db->no_query("UPDATE user SET report = '1' WHERE id = '$from_u'");
                $db->no_query("INSERT INTO `report`(`userid`, `type`, `att`, `att_lost`, `deff`, `deff_lose`, `booty`, `readed`, `time`, `att_dorf`, `deff_dorf`) VALUES ('$to_u', 'attack_lost', '".serialize($fight['att'])."', '".serialize($fight['att_lose'])."', '".serialize($fight['deff'])."', '".serialize($fight['deff_lose'])."', '$booty', '0', '$zeit', '$hid', '".$movement['to_village']."')");
        $db->no_query("UPDATE user SET report = '1' WHERE id = '$to_u'");
            }
        }
        $reload->bh($hid);
        $reload->village_points($hid);
        $reload->village_points($movement['to_village']);
        $db->no_query("DELETE FROM movement WHERE id = '".$movement['id']."'");
    }
  }
}
?>
