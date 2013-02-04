/**
 * Alertify
 * An unobtrusive customizable JavaScript notification system
 *
 * @author Fabien Doiron <fabien.doiron@gmail.com>
 * @copyright Fabien Doiron 2012
 * @license MIT <http://opensource.org/licenses/mit-license.php>
 * @link http://www.github.com/fabien-d
 * @module Alertify
 * @version 0.2.5
 */
(function(e,t){"use strict";var n=e.document,r;r=function(){var r,i,s,o,u,a,f,l,c,h,p,d,v,m,g,y,b,w,E,S,x,T;return g={buttons:{holder:'<nav class="alertify-buttons">{{buttons}}</nav>',submit:'<button type="submit" class="alertify-button alertify-button-ok" id="aOK" />{{ok}}</button>',ok:'<a href="#" class="alertify-button alertify-button-ok" id="aOK">{{ok}}</a>',cancel:'<a href="#" class="alertify-button alertify-button-cancel" id="aCancel">{{cancel}}</a>'},input:'<input type="text" class="alertify-text" id="aText">',message:'<p class="alertify-message">{{message}}</p>',log:'<article class="alertify-log{{class}}">{{message}}</article>'},y=5e3,E={ENTER:13,ESC:27,SPACE:32},S={ok:"Valider",cancel:"Annuler"},T=[],w=!1,r=function(e){return n.getElementById(e)},i=function(i){var o=r("aResetFocus"),u=r("aOK")||t,f=r("aCancel")||t,l=r("aText")||t,c=r("aForm")||t,p=typeof u!="undefined",d=typeof f!="undefined",v=typeof l!="undefined",m="",g,y,b,w,S;g=function(e){b(e),typeof l!="undefined"&&(m=l.value),typeof i=="function"&&i(!0,m),typeof e.preventDefault!="undefined"&&e.preventDefault()},y=function(e){b(e),typeof i=="function"&&i(!1),typeof e.preventDefault!="undefined"&&e.preventDefault()},b=function(e){a(),h(n.body,"keyup",w),h(o,"focus",S),v&&h(c,"submit",g),p&&h(u,"click",g),d&&h(f,"click",y)},w=function(e){var t=e.keyCode;t===E.SPACE&&!v&&g(e),t===E.ESC&&d&&y(e)},S=function(e){v?l.focus():d?f.focus():u.focus()},s(o,"focus",S),p&&s(u,"click",g),d&&s(f,"click",y),s(n.body,"keyup",w),v&&s(c,"submit",g),e.setTimeout(function(){l?l.focus():u.focus()},50)},s=function(e,t,n){typeof e.addEventListener=="function"?e.addEventListener(t,n,!1):e.attachEvent&&e.attachEvent("on"+t,n)},o=function(e){var t="",n=e.type,r=e.message;t+='<div class="alertify-dialog">',n==="prompt"&&(t+='<form id="aForm">'),t+='<article class="alertify-inner">',t+=g.message.replace("{{message}}",r),n==="prompt"&&(t+=g.input),t+=g.buttons.holder,t+="</article>",n==="prompt"&&(t+="</form>"),t+='<a id="aResetFocus" class="alertify-resetFocus" href="#">Reset Focus</a>',t+="</div>";switch(n){case"confirm":t=t.replace("{{buttons}}",g.buttons.cancel+g.buttons.ok),t=t.replace("{{ok}}",S.ok).replace("{{cancel}}",S.cancel);break;case"prompt":t=t.replace("{{buttons}}",g.buttons.cancel+g.buttons.submit),t=t.replace("{{ok}}",S.ok).replace("{{cancel}}",S.cancel);break;case"alert":t=t.replace("{{buttons}}",g.buttons.ok),t=t.replace("{{ok}}",S.ok);break;default:}return b.className="alertify alertify-show alertify-"+n,m.className="alertify-cover",t},u=function(e,t){var n=t&&!isNaN(t)?+t:y;s(e,"click",function(){x.removeChild(e)}),setTimeout(function(){typeof e!="undefined"&&e.parentNode===x&&x.removeChild(e)},n)},a=function(){T.splice(0,1),T.length>0?c():(w=!1,b.className="alertify alertify-hide alertify-hidden",m.className="alertify-cover alertify-hidden")},f=function(){n.createElement("nav"),n.createElement("article"),n.createElement("section"),m=n.createElement("div"),m.setAttribute("id","alertifycover"),m.className="alertify-cover alertify-hidden",n.body.appendChild(m),b=n.createElement("section"),b.setAttribute("id","alertify"),b.className="alertify alertify-hidden",n.body.appendChild(b),x=n.createElement("section"),x.setAttribute("id","alertifylogs"),x.className="alertify-logs",n.body.appendChild(x),delete this.init},l=function(e,t,r){var i=n.createElement("article");i.className="alertify-log"+(typeof t=="string"&&t!==""?" alertify-log-"+t:""),i.innerHTML=e,x.insertBefore(i,x.firstChild),setTimeout(function(){i.className=i.className+" alertify-log-show"},50),u(i,r)},c=function(){var e=T[0];w=!0,b.innerHTML=o(e),i(e.callback)},h=function(e,t,n){typeof e.removeEventListener=="function"?e.removeEventListener(t,n,!1):e.detachEvent&&e.detachEvent("on"+t,n)},p=function(e,t,n){var r=function(){if(b&&b.scrollTop!==null)return;r()};if(typeof e!="string")throw new Error("message must be a string");if(typeof t!="string")throw new Error("type must be a string");if(typeof n!="undefined"&&typeof n!="function")throw new Error("fn must be a function");return typeof this.init=="function"&&(this.init(),r()),T.push({type:t,message:e,callback:n}),w||c(),this},d=function(e){return function(t,n){v.call(this,t,e,n)}},v=function(e,t,n){var r=function(){if(x&&x.scrollTop!==null)return;r()};return typeof this.init=="function"&&(this.init(),r()),l(e,t,n),this},{alert:function(e,t){return p.call(this,e,"alert",t),this},confirm:function(e,t){return p.call(this,e,"confirm",t),this},extend:d,init:f,log:function(e,t,n){return v.call(this,e,t,n),this},prompt:function(e,t){return p.call(this,e,"prompt",t),this},success:function(e,t){return v.call(this,e,"success",t),this},error:function(e,t){return v.call(this,e,"error",t),this},delay:y,labels:S}},typeof define=="function"?define([],function(){return new r}):typeof e.alertify=="undefined"&&(e.alertify=new r)})(this);

// OUVRIR UN LIEN DANS UN NOUVEL ONGLET
$('a[rel="external"]').click(function() {
	window.open($(this).attr('href'));
	return false;
});

// ACTION LORSQUE L'ON CLIQUE SUR UN BOUTON "SUPPRIMER"
$('.button-supprimer').live('click', function (e) {	
	var conteneur = $(this).parent();
	var tweet = $(this).prev('span').html();
	var tab_id = $(this).attr("class").split('_');
	var tweet_id = tab_id[1];
	alertify.confirm("Supprimer ce tweet : \""+tweet+"\"", function (e) {
    	if (e) {
        	// user clicked "ok"
			var dataString = "id="+tweet_id+"&content="+tweet;
			if (tweet_id !== "" && tweet !== "") {
				$.ajax({
      				type: "POST",
      				url: "supprimer_tweet.php",
      				data: dataString,
      				success: function(data) {
						if (data === "ok") {
							alertify.alert("Le tweet a bien \351t\351 supprim\351 !");
							conteneur.remove();
						}
						else {
							alertify.alert(data);
						}
						
					}
				});
			}
    	}
	});
});



// FONCTION POUR L'AFFICHAGE DES NOUVEAUX TWEETS
$('.actualiser_page').live('click', function(e) {
	for (i=0;i<listeTweets_id.length;i++) {
		$('#main').prepend('<article class="tweet tweet-'+listeTweets_id[i]+'"><div class="date">'+listeTweets_date[i]+'</div><span>'+listeTweets_texte[i]+'</span><button class="button-supprimer button id_'+listeTweets_id[i]+'" type="button" name="supprimer">Supprimer ce Tweet</button></article>');	
	}						   
	nombreNouveauwTweets = 0;
	$('.nb_nvx_tweets').html('');
	$('.texte_nouveaux_tweets').html('Pas de nouveaux tweets');
	$('.actualiser_page').remove();				   
										   
	listeTweets_id = [];
	listeTweets_date = [];
	listeTweets_texte = [];
});

var listeTweets_id = new Array();
var listeTweets_date = new Array();
var listeTweets_texte = new Array();

var tab = $(".tweet:first-child").attr('class').split('-');
var dernierID = tab[1];
var nombreNouveauwTweets = 0;

// FONCTION QUI RECUPERE LE NOMBRE DE NOUVEAUX TWEETS TOUTES LES 60 SECONDES ET QUI L'AFFICHE DANS LA PAGE
setInterval(function() {
	//var tab = $(".tweet:first-child").attr('class').split('-');
	//var id = tab[1];
	var dataString = "id="+dernierID;
	if (dernierID !== "") {
		$.ajax({
      		type: "POST",
      		url: "getNouveauxTweets.php",
      		data: dataString,
      		success: function(data) {
				var nb = parseInt(data.nb);
				nombreNouveauwTweets += nb; 
				if (nombreNouveauwTweets === 1) {
					$('.nb_nvx_tweets').html(nombreNouveauwTweets+' ');
					$('.texte_nouveaux_tweets').html('nouveau tweet');
					if ($(".actualiser_page").length == 0){
						$('.informations').append('<button class="actualiser_page" type="button" name="actualiser">Afficher le nouveau tweet</button>');
					}
					for (i=0;i<data.listeTweets_id.length;i++) {
						listeTweets_id.push(data.listeTweets_id[i]);
						listeTweets_date.push(data.listeTweets_date[i]);
						listeTweets_texte.push(data.listeTweets_texte[i]);
					}
					dernierID = listeTweets_id[listeTweets_id.length-1];
				}
				else if (nombreNouveauwTweets > 1) {
					$('.nb_nvx_tweets').html(nombreNouveauwTweets+' ');
					$('.texte_nouveaux_tweets').html('nouveaux tweets');
					if ($(".actualiser_page").length){
						$('.actualiser_page').html('Afficher les nouveaux tweets');
					}
					else {
						$('.informations').append('<button class="actualiser_page" type="button" name="actualiser">Afficher les nouveaux tweets</button>');
					}
					for (i=0;i<data.listeTweets_id.length;i++) {
						listeTweets_id.push(data.listeTweets_id[i]);
						listeTweets_date.push(data.listeTweets_date[i]);
						listeTweets_texte.push(data.listeTweets_texte[i]);
					}
					dernierID = listeTweets_id[listeTweets_id.length-1];
				}
			}
		});
	}
}, 60000);