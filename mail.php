<?php
$nodb = true;
include "config.php";
?>
<div style="background-image: url('<?= $config['cdn'] ?>img/back.jpg'); font-family: Verdana, Arial; width:100%; font-size:12px; overflow-x:hidden; top:      0px; left:     0px; margin:   0px; padding:  0px;">
  <div style="border-radius: 3px; margin:11px auto auto; background-color: #EFE3CB; border-style: solid; border-color: #C9B89B; width: 90%">
    <h2>Willkommen bei <?= $config['name'] ?></h2>
    <hr />
    <p>Um deinen Account permanent ohne nutzen zu k&ouml;nnen musst du deinen Account noch aktivieren. Bitte vergewissere dich, dass alle Daten korrekt sind.</p>
    <table border="1">
      <tr><th colspan="2">Pers&ouml;nliche Daten</th></tr>
      <tr><td>Benutzername</td><td><?= $_GET['user'] ?></td></tr>
      <tr><td>Passwort (zensiert)</td><td><?= $_GET['pw'] ?></td></tr>
      <tr><td>Email</td><td><?= $_GET['mail'] ?></td></tr>
    </table>
    Sollten diese Daten korrekt sein, klicke bitte auf diesen Link:<br />
    <a style="color:#E82E2E; font-weight:bold; text-decoration:none"
       href="<?= $config['url'] ?>?page=activate&user=<?= $_GET['user'] ?>&code=<?= $_GET['activate'] ?>" target="_blank"><?= $config['url'] ?>?page=activate&user=<?= $_GET['user'] ?>&code=<?= $_GET['activate'] ?></a><br /><br />
    Alternativ kannst du auch am unteren Rand der Startseite auf "Account aktivieren" klicken und folgenden Best&auml;tigungscode eingeben:<br />
    <b><?= $_GET['activate'] ?></b>

<br /><br /><br />
Diese Email wurde automatisch zugesendet.
  </div>
</div>
