/*
Collapsing Categories version: 0.6.5
Copyright 2007 Robert Felty

This work is largely based on the Fancy Categories plugin by Andrew Rader
(http://voidsplat.org), which was also distributed under the GPLv2. See the
CHANGELOG file for more information.

This file is part of Collapsing Categories

		Collapsing Categories is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License as published by 
    the Free Software Foundation; either version 2 of the License, or (at your
    option) any later version.

    Collapsing Categories is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Collapsing Categories; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

String.prototype.trim = function() {
  return this.replace(/^\s+|\s+$/g,"");
}

function createCookie(name,value,days) {
  if (days) {
    var date = new Date();
    date.setTime(date.getTime()+(days*24*60*60*1000));
    var expires = "; expires="+date.toGMTString();
  } else {
    var expires = "";
  }
  document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') {
      c = c.substring(1,c.length);
    }
    if (c.indexOf(nameEQ) == 0) {
      return c.substring(nameEQ.length,c.length);
    }
  }
  return null;
}

function eraseCookie(name) {
  createCookie(name,"",-1);
}

function autoExpand() {
  var cookies = document.cookie.split(';');
  for (i=0; i<cookies.length; i++) {
    var cookieparts= cookies[i].split('=');
    var cookiename=cookieparts[0].trim();
    if (cookiename.match(/collapsCat-[0-9]+/)) {
      var expand= document.getElementById(cookiename);
      var thisli = expand.parentNode;
      for (j=0; j< thisli.childNodes.length; j++) {
        if (thisli.childNodes[j].nodeName.toLowerCase() == 'span') {
          theSpan=thisli.childNodes[j];
            //alert(theSpan.getAttribute('style'));
            // Can't seem to get getAttribute to work in IE
          if (theSpan.getAttribute('class') =='collapsCat show') {
            var theOnclick=theSpan.getAttribute('onclick');
            var expand=theOnclick.replace(/.*event,([0-9]),[0-9].*/, '$1');
            expandCat(theSpan,expand,0);
          }
        } 
      }
    }
  }
}
function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      if (oldonload) {
        oldonload();
      }
      func();
    }
  }
}

addLoadEvent(function() {
  autoExpand();
});
