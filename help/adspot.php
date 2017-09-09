<h4>Werbung für <?= $config['name'] ?>!</h4>
<p>Mach Werbung für Middle-Age und verbreite das Spiel!<br /><br>
<b>Mit einem Textlink:</b> <a href="<?= $config['url'] ?>"><?= $config['name'] ?></a><br />
HTML:
<textarea cols="50" rows="2"><a href="<?= $config['url'] ?>"><?= $config['name'] ?></a>
</textarea><br />
BB-CODE:
<textarea cols="50" rows="2">[URL="<?= $config['url'] ?>"]<?= $config['name'] ?>[/URL]
</textarea>
<br><b>Mit einem Banner:</b> <a href="<?= $config['url'] ?>"><img src="<?= $config['cdn'] ?>img/banner.png"></a><br />
HTML:
<textarea cols="50" rows="2"><a href="<?= $config['url'] ?>"><img src="<?= $config['cdn'] ?>img/banner.png"></a>
</textarea><br />
BB-CODE:
<textarea cols="50" rows="2">[URL="<?= $config['url'] ?>"][IMG]<?= $config['cdn'] ?>img/banner.png[/IMG][/URL]
</textarea>
