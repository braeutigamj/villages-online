<?php
$result = $db->fetch("
    SELECT *
    FROM message
    WHERE userid='$id'
    ORDER BY id DESC");
 ?>
<form method="post" action="#">
  <table class="vis" width="100%">
    <tr>
      <th>löschen</th>
      <th>Betreff</th>
      <th>Von</th>
      <th>Zeit</th>
    </tr>
    <?php while ($zeile = $result->fetch_array()) {
      if(isset($_POST['delete']))
      {
        if(isset($_POST[$zeile['id']])) {
          $idd = $zeile['id'];
          $db->no_query("DELETE FROM message WHERE userid='$id' AND id = '$idd'");
          echo '<meta http-equiv="refresh" content="0; URL=game.php?village='.$hid.'&page=mail">';
        }
      } ?>
      <tr>
        <td>
          <input id="checkmail" type="checkbox" name="<?php echo $zeile['id']; ?>">
        </td>
        <td>
          <a href="game.php?village=<?= $hid ?>&page=mail&show=read&msid=<?php echo $zeile['id']; ?>&read=<?php echo $zeile['readed']; ?>">
            <?php
            echo $zeile['betreff'];
            if($zeile['readed'] == "0") {
              echo '<img src="'.$config["cdn"].'img/message.png">';
            } ?>
          </a>
        </td>
        <td>
          <a href="game.php?village=<?= $hid ?>&page=splayer&id=<?= $zeile['von_player'] ?>">
            <?php echo $zeile['von_player']; ?>
          </a>
        </td>
        <td><? echo format_date($zeile['time']); ?></td>
      </tr>
    <?php } ?>
    <tr>
      <td>
        <input type="submit" name="delete" value="löschen">
      </td>
      <td colspan="3"></td>
    </tr>
  </table>
</form>
