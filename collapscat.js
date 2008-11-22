/*
Collapsing Categories version: 0.6.6
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


function autoExpandCat() {
  var cookies = document.cookie.split(';');
  for (var cookieIndex=0; cookieIndex<cookies.length; cookieIndex++) {
    var cookieparts= cookies[cookieIndex].split('=');
    var cookiename=cookieparts[0].trim();
    var cookievalue=cookieparts[1].trim();
    if (cookiename.match(/collapsCat-[0-9]+/)) {
      var expand= document.getElementById(cookiename);
      var thisli = expand.parentNode;
      for (var childI=0; childI< thisli.childNodes.length; childI++) {
        if (thisli.childNodes[childI].nodeName.toLowerCase() == 'span') {
          theSpan=thisli.childNodes[childI];
          if (theSpan.className.match(/^collapsCat/)) {
            if ((theSpan.className == 'collapsCat show' && cookievalue ==1) ||
                (theSpan.className == 'collapsCat hide' && cookievalue ==0)) {
              var theOnclick=theSpan.onclick+"";
              var matches=theOnclick.match(/.*\(event, ?([0-9]).*\)/);
              var expand=matches[1];
              expandCat(theSpan,expand,0);
            }
          }
        } 
      }
    }
  }
}

addLoadEvent(function() {
  autoExpandCat();
});
