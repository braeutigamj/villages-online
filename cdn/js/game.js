/*
Das Kopieren der Scripte ist verboten und wird strafrechtlich verfolgt!
*/
function getElementsById(ids) {
    var idList = ids.split(" ");
    var results = [], item;
    for (var i = 0; i < idList.length; i++) {
        item = document.getElementById(idList[i]);
        if (item) {
            results.push(item);
        }
    }
    return(results);
}

function mapinfo(id) {
	$( "#mapinfo" ).css( "display", "block");
	$( "#mapinfo" ).load('back.php?map&id=' + id);
}

function closemap() {
	$( "#mapinfo" ).css( "display", "none");
}

function counter(element, i = 'nono') {
	if(i == 'nono') {
		i = element-time();
	}
	var seconds = i;
	var date = new Date(seconds * 1000);
	var hh = date.getHours();
	var mm = date.getMinutes();
	var ss = date.getSeconds();
	if (hh > 12) {hh = hh - 12;}
	var hh = hh - 1;
	if (hh < 10) {hh = "0"+hh;}
		if (mm < 10) {mm = "0"+mm;}
	if (ss < 10) {ss = "0"+ss;}
	var t = hh+":"+mm+":"+ss;
	document.getElementById(element).innerHTML = t;
	if(i > 1) {
		i--;
		var timeout = window.setTimeout("counter('"+ element + "', " + i + ")", 1000);
	} else {
		if(get_url_param('page') == "main") {
			url = "game.php?page=main";
			window.location = url;
		} else {
			location.reload();
		}
	}
	
}

function time () {
  // http://kevin.vanzonneveld.net
  return Math.floor(new Date().getTime() / 1000);
}

function get_url_param( name )
{
	//netlobo.com
	name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	var regexS = "[\\?&]"+name+"=([^&#]*)";
	var regex = new RegExp( regexS );
	var results = regex.exec( window.location.href );
	if ( results == null )
		return "";
	else
		return results[1];
}
