<!--
/*
v1.1
ein script von Julian Hibbeln
http://www.fun-fox.de/bbcode_smileys.php
*/

/* falls eine bestimmte Funktion nicht genutzt werden soll, kann sie hier deaktiviert werden. */
var smileys = "ja";
var text_formation = "ja";
var url = "ja";

var a = "";

function showSmileys(id)
{
  var div_id = id+"-bbcus";

  /* Link zum anzeigen der Smileys. Im <div>-Tag werden die Smileys angezeigt. */
  document.write("<div id=\"" + div_id + "\" style=\"overflow: height:0px; border:0px;\"></div>");

  if(smileys == "ja" && typeof(hide_smileys) == "undefined")
  {
    /*
    Hier können neue Smileys hinzugefügt werden.
    Der 1. Parameter der addSmiley-Funktion und das src-Attribut am Schluss müssen angepasst werden!
    addSmiley('PARAMETER1 ','" + id + "')
    */

    a += "<img class=\"bbcuss_smiley\" onclick=\"javascript:addSmiley(':angry: ','" + id + "')\" alt=\"angry\" src='" + cdn_url + "smyle/angry.gif' />";
    a += "<img class=\"bbcuss_smiley\" onclick=\"javascript:addSmiley(':cheesy: ','" + id + "')\" alt=\"cheesy\" src='" + cdn_url + "smyle/cheesy.gif' />";
    a += "<img class=\"bbcuss_smiley\" onclick=\"javascript:addSmiley(':cool: ','" + id + "')\" alt=\"cool\" src='" + cdn_url + "smyle/cool.gif' />";
    a += "<img class=\"bbcuss_smiley\" onclick=\"javascript:addSmiley(':cry: ','" + id + "')\" alt=\"cry\" src='" + cdn_url + "smyle/cry.gif' />";
    a += "<img class=\"bbcuss_smiley\" onclick=\"javascript:addSmiley(':embarassed: ','" + id + "')\" alt=\"embarassed\" src='" + cdn_url + "smyle/embarassed.gif' />";
    a += "<img class=\"bbcuss_smiley\" onclick=\"javascript:addSmiley(':grin: ','" + id + "')\" alt=\"grin\" src='" + cdn_url + "smyle/grin.gif' />";
    a += "<img class=\"bbcuss_smiley\" onclick=\"javascript:addSmiley(':huh: ','" + id + "')\" alt=\"huh\" src='" + cdn_url + "smyle/huh.gif' />";
    a += "<img class=\"bbcuss_smiley\" onclick=\"javascript:addSmiley(':kiss: ','" + id + "')\" alt=\"kiss\" src='" + cdn_url + "smyle/kiss.gif' />";
    a += "<img class=\"bbcuss_smiley\" onclick=\"javascript:addSmiley(':laugh: ','" + id + "')\" alt=\"laugh\" src='" + cdn_url + "smyle/laugh.gif' />";
    a += "<img class=\"bbcuss_smiley\" onclick=\"javascript:addSmiley(':lipsrsealed: ','" + id + "')\" alt=\"lipsrsealed\" src='" + cdn_url + "smyle/lipsrsealed.gif' />";
    a += "<img class=\"bbcuss_smiley\" onclick=\"javascript:addSmiley(':rolleyes: ','" + id + "')\" alt=\"rolleyes\" src='" + cdn_url + "smyle/rolleyes.gif' />";
    a += "<img class=\"bbcuss_smiley\" onclick=\"javascript:addSmiley(':sad: ','" + id + "')\" alt=\"sad\" src='" + cdn_url + "smyle/sad.gif' />";
    a += "<img class=\"bbcuss_smiley\" onclick=\"javascript:addSmiley(':shocked: ','" + id + "')\" alt=\"shocked\" src='" + cdn_url + "smyle/shocked.gif' />";
    a += "<img class=\"bbcuss_smiley\" onclick=\"javascript:addSmiley(':smiley: ','" + id + "')\" alt=\"smiley\" src='" + cdn_url + "smyle/smiley.gif' />";
    a += "<img class=\"bbcuss_smiley\" onclick=\"javascript:addSmiley(':tongue: ','" + id + "')\" alt=\"tongue\" src='" + cdn_url + "smyle/tongue.gif' />";
    a += "<img class=\"bbcuss_smiley\" onclick=\"javascript:addSmiley(':undecided: ','" + id + "')\" alt=\"undecided\" src='" + cdn_url + "smyle/undecided.gif' />";
    a += "<img class=\"bbcuss_smiley\" onclick=\"javascript:addSmiley(':wink: ','" + id + "')\" alt=\"wink\" src='" + cdn_url + "smyle/wink.gif' />";
  }

  if(text_formation == "ja" && typeof(hide_text_formation) == "undefined")
  {
    a += "<br />";
    a += "<span onclick=\"bbCode('b','" + id + "');\" class=\"bbcuss_text\">";
    a += "<strong>F</strong>&nbsp;";
    a += "</span>";

    a += "<span onclick=\"bbCode('i','" + id + "');\" class=\"bbcuss_text\">"
    a += "<i>K</i>&nbsp;";
    a += "</span>";

    a += "<span onclick=\"bbCode('u','" + id + "');\" class=\"bbcuss_text\">";
    a += "<u>U</u>&nbsp;";
    a += "</span>";

    a += "<span class=\"bbcuss_text\" onclick=\"addQoute('"+ id +"')\">";
    a += "[Zitieren]";
    a += "</span>";

    a += "<span onclick=\"bbCode('user','" + id + "');\" class=\"bbcuss_text\">";
    a += "<img src='" + cdn_url + "img/stuff/user.png'>&nbsp;";
    a += "</span>";

  }

  if(url == "ja" && typeof(hide_url) == "undefined")
  {
    a += "<span class=\"bbcuss_text\" onclick=\"addLink('"+ id +"')\">";
    a += "<img src='" + cdn_url + "img/stuff/link.png'>";
    a += "</span>";
  }

  document.getElementById(div_id).innerHTML = a;
}




function addSmiley(Smiley,id)
{
  var currentMessage = document.getElementById(id).value;
  var revisedMessage = currentMessage + Smiley;
  document.getElementById(id).value = revisedMessage;
  document.getElementById(id).focus();
  return;
}

function bbCode(bbcode,id)
{
  var bbCodeMessage = prompt("Gebe bitte den Text ein, den du mit ["+ bbcode +"][/"+ bbcode +"] formatieren mchtest:","");
  if(bbCodeMessage != "")
  {
    var currentMessage = document.getElementById(id).value;
    var revisedMessage = currentMessage + " [" + bbcode + "]" + bbCodeMessage + "[/" + bbcode + "]";
    document.getElementById(id).value = revisedMessage;
    document.getElementById(id).focus();
  }
}

function addLink(id)
{
  var linkUrl = prompt("Gebe bitte die URL ein:","http://");
  var linkText = prompt("Gebe bitte den Linktext ein:","");
  if(linkUrl != "http://" && linkUrl != "")
  {
    var currentMessage = document.getElementById(id).value;
    var revisedMessage = currentMessage + " [url=" + linkUrl + "]" + linkText + "[/url]";
    document.getElementById(id).value = revisedMessage;
    document.getElementById(id).focus();
  }
}
function addQoute(id)
{
  var linkUrl = prompt("Gebe bitte die zitierte Person ein","");
  var linkText = prompt("Gebe bitte den zitierten Text ein","");
  if(linkUrl != "Arschloch" && linkUrl != "")
  {
    var currentMessage = document.getElementById(id).value;
    var revisedMessage = currentMessage + " [qoute=" + linkUrl + "]" + linkText + "[/qoute]";
    document.getElementById(id).value = revisedMessage;
    document.getElementById(id).focus();
  }
}
//-->
