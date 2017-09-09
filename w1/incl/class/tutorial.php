<?
class tutorial {

  //Alle Box-Nachrichten
  function message($tut) {
  global $user, $config, $server;
    if($tut == 0) {
      $return = 'Hallo <i>'.$user['name'].'</i>,<br> schön das du dich entschlossen hast '.$config['name'].' auszuprobieren. Dieses Tutorial wird dir helfen dich im Spiel zurechtzufinden, keine Sorge es ist nicht schwer ;)';
    } elseif($tut == 1) {
      $return = "Bitte besuche zuerst das Hauptgebäude und baue den <i>Holzfäller</i>, die <i>Lehmgrube</i> und die <i>Eisenmine</i> auf Stufe 3 aus um Rohstoffe für die weiteren Dörfer zu erhalten.<br> Belohnung: <b><img src='".$config['cdn']."img/res/wood.png' width='25px'>250 <img src='".$config['cdn']."img/res/clay.png' width='25px'>200 <img src='".$config['cdn']."img/res/iron.png' width='25px'>150</b>";
    } elseif($tut == 2) {
      $return = "Herzlichen Glückwunsch, du hast soeben die Grundproduktion für dein Dorf gesichert!<br>Deine Rohstoffe wurden dir soeben gutgeschrieben. Du soltest darauf achten, dass deine Rohstoffquellen immer möglichst hoch ausgebaut sind.<br><br>Baue als nächstes dein Hauptgebäude auf Stufe 5 und errichte eine Barracke um Verteidiger zu rekrutieren.<br> Belohnung: <b><img src='".$config['cdn']."img/res/wood.png' width='25px'>25 <img src='".$config['cdn']."img/res/clay.png' width='25px'>20 <img src='".$config['cdn']."img/res/iron.png' width='25px'>15</b>";
    } elseif($tut == 3) {
      $return = "Nun solltest du deinen Speicher und deine Farm auf Vordermann bringen, um deine Soldaten auch versorgen zu können!<br><b>Auftrag:</b><br>Farm 4, Speicher 5<br> Belohnung: <b><img src='".$config['cdn']."img/res/wood.png' width='25px'>50 <img src='".$config['cdn']."img/res/clay.png' width='25px'>50 <img src='".$config['cdn']."img/res/iron.png' width='25px'>40</b>";
    } elseif($tut == 4) {
      $return = "Als nächstes kannst du dich um die Offensive kümmern. Baue am besten zuerst einen Stall um schnelle Einheiten zu erhalten. Vergiss nicht diese auch zu erforschen! (Solltest du nicht weiter wissen schaue <a href='".$config['help']."help.php?help=building' target='_blank'>hier</a> vorbei.)<br><b>Auftrag:</b><br>Stall 1, Schmiede 3, leichte Reiter 10<br> Belohnung: <b><img src='".$config['cdn']."img/res/wood.png' width='25px'>50 <img src='".$config['cdn']."img/res/clay.png' width='25px'>50 <img src='".$config['cdn']."img/res/iron.png' width='25px'>40</b>";
    } elseif($tut == 5) {
      $return = "Du hast nun eine schnelle Einweisung in das Spiel erhalten, solltest du noch weitere Fragen haben (z.B. wie du andere Burgen oder Dörfer erobern kannst), wende dich bitte an unsere <a href='".$config['help']."help.php' target='_blank'>Hilfeseite</a>, oder frage deine Mitspieler. Hilfer erhältst du auch über unser <a href='game.php?village=".$hid."&page=mail&show=new&at=Admin&betreff=Supportanfrage&message=Bitte beschreibe dein Problem möglichst genau!'>Supportformular</a><br><br><a href='".$server["path"]."&tut=stop'>Tutorial beenden</a>";
    }
  return $return;
  }

  //Hier wird die Nachricht auf der Seite angezeigt
  function box($tut) {
  global $server;
    if($tut >= 6) {
      $return = "";
    } else {
      $return = '<div class="tutorial" id="tutorial">
        <b>Tutorial - Stufe '.$tut.'</b><br>
        '.$this->message($tut).'
        <br>';
        if($tut = 0) {
          $tutz = $tut+1;
          $return .= '<a href="'.$server["path"].'&tut='.$tut.'">weiter</a>';
        }
      //$return .= '<hr><a href="'.$server["path"].'&tut=stop">vorzeitig beenden</a>';
      $return .= '</div>';
    }
  return $return;

  }

  //Checkt ob die Vorraussetzung für die nächste Sutfe gegeben sind!
  function check($step) {
  global $user, $db, $hid;
    $return = "";
    $village = $db->assoc("SELECT * FROM `village` WHERE userid = '".$user['id']."' LIMIT 1");
    if($step < 6 || $step < $user['tut']) {
      if($step == 1) {
        $return = "no_error";
        $db->no_query("UPDATE `user` SET `tut` = '$step' WHERE id = '".$user['id']."'");
      }
      elseif($step == 2 and $village['wood'] >= 3 and $village['clay'] >= 3 and $village['iron'] >= 3) {
        $db->no_query("UPDATE `user` SET `tut` = '$step' WHERE id = '".$user['id']."'");
        $return = "no_error";
        $db->no_query("UPDATE  `village` SET `wood_r` = `wood_r`+250, `clay_r` = `clay_r`+200, `iron_r` = `iron_r`+150 WHERE id = '$id'");
      } elseif($step == 3 and $village['main'] >= 5 and $village['barracks'] >= 1) {
        $db->no_query("UPDATE `user` SET `tut` = '$step' WHERE id = '".$user['id']."'");
        $return = "no_error";
        $db->no_query("UPDATE  `village` SET `wood_r` = `wood_r`+25, `clay_r` = `clay_r`+20, `iron_r` = `iron_r`+15 WHERE id = '$id'");
      } elseif($step == 4 and $village['farm'] >= 4 and $village['storage'] >= 5) {
        $db->no_query("UPDATE `user` SET `tut` = '$step' WHERE id = '".$user['id']."'");
        $return = "no_error";
        $db->no_query("UPDATE  `village` SET `wood_r` = `wood_r`+50, `clay_r` = `clay_r`+50, `iron_r` = `iron_r`+40 WHERE id = '$id'");
      } elseif($step == 5 and $village['stable'] >= 1 and $village['smith'] >= 3 and $village['light'] >= 10) {
        $db->no_query("UPDATE `user` SET `tut` = '$step' WHERE id = '".$user['id']."'");
        $return = "no_error";
        $db->no_query("UPDATE  `village` SET `wood_r` = `wood_r`+50, `clay_r` = `clay_r`+50, `iron_r` = `iron_r`+40 WHERE id = '$id'");
      }
    }
    if($step == "stop") {
      if($user['tut'] <= 5) {
        $db->no_query("UPDATE  `village` SET `wood_r` = `wood_r`+50, `clay_r` = `clay_r`+50, `iron_r` = `iron_r`+40 WHERE id = '$id'");
      }
      if($user['tut'] <= 4) {
        $db->no_query("UPDATE  `village` SET `wood_r` = `wood_r`+50, `clay_r` = `clay_r`+50, `iron_r` = `iron_r`+40 WHERE id = '$id'");
      }
      if($user['tut'] <= 3) {
        $db->no_query("UPDATE  `village` SET `wood_r` = `wood_r`+25, `clay_r` = `clay_r`+20, `iron_r` = `iron_r`+15 WHERE id = '$id'");
      }
      if($user['tut'] <= 2) {
        $db->no_query("UPDATE  `village` SET `wood_r` = `wood_r`+250, `clay_r` = `clay_r`+200, `iron_r` = `iron_r`+150 WHERE id = '$id'");
      }
      if($user['tut'] <= 1) {
        $db->no_query("UPDATE  `village` SET `wood_r` = `wood_r`+250, `clay_r` = `clay_r`+200, `iron_r` = `iron_r`+150 WHERE id = '$id'");
      }
      $return = "no_error";
      $db->no_query("UPDATE `user` SET `tut` = '6' WHERE id = '".$user['id']."'");
    }
  return $return;
  }
}
$tutorial = new tutorial;
?>
