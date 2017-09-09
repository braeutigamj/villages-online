function counter(time, place) {
	var seconds = time;
	var place = place;
	var date = new Date(seconds * 1000);
	var hh = date.getHours();
	var mm = date.getMinutes();
	var ss = date.getSeconds();
	// This line gives you 12-hour (not 24) time
	if (hh > 12) {hh = hh - 12;}
	var hh = hh - 1;
	// These lines ensure you have two-digits
	if (hh < 10) {hh = "0"+hh;}
	if (mm < 10) {mm = "0"+mm;}
	if (ss < 10) {ss = "0"+ss;}
	// This formats your string to HH:MM:SS
	var t = hh+":"+mm+":"+ss;

	document.getElementById(place).innerHTML = t;
	if (time > 0) {
		time--;
		var timeout = window.setTimeout("counter(" + time + ", '" + place + "')", 1000);
	} else {
		window.location.reload();
	}
}
function changer(value) {
	var Url = 'backg.php?page=index&cookie=info_world&active=' + value;
	var request = new XMLHttpRequest();
	request.open( 'GET', Url, true );
	request.send( null );
	window.setTimeout('window.location.reload()', 0500);

}
function zeiter() {
	d = new Date ();
			 		h = (d.getHours () < 10 ? '0' + d.getHours () : d.getHours ());
	m = (d.getMinutes () < 10 ? '0' + d.getMinutes () : d.getMinutes ());
	s = (d.getSeconds () < 10 ? '0' + d.getSeconds () : d.getSeconds ());
	document.getElementById("zeit").innerHTML = h + ':' + m + ':' + s;
	setTimeout("zeiter()",1000);
}
