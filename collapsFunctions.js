/*  Collapse Functions, version 2.0
 *
 *--------------------------------------------------------------------------*/
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
    //jQuery(this).parent().find('ul').html('<li>foo</li>').slideDown();
function addExpandCollapse(id, expandSym, collapseSym, accordion) {
  jQuery('.expand').live('click', function() {
    if (accordion==1) {
      var theDiv = jQuery('#' + id + ' .collapse').parent().find('div');
      jQuery(theDiv).slideUp('normal');
      jQuery('#' + id + ' .collapse').removeClass('collapse').addClass('expand');
    }
    expandList(this, expandSym, collapseSym);
  });
  jQuery('.collapse').live('click', function() {
    collapseList(this, expandSym, collapseSym);
  });
}

function expandList(symbol, expandSym, collapseSym) {
    var theDiv = jQuery(symbol).parent().find('div');
    jQuery(theDiv).html(collapsItems[jQuery(theDiv).attr('id')]).slideDown('normal');
    jQuery(symbol).removeClass('expand').addClass('collapse').html('<span class="sym">'+collapseSym+'</span>');
  createCookie(theDiv.attr('id'), 1, 7);
}
function collapseList(symbol, expandSym, collapseSym) {
    var theDiv = jQuery(symbol).parent().find('div');
    //var theDiv = jQuery('#' + id + ' .collapse').parent().find('div');
    jQuery(theDiv).slideUp('normal');
    jQuery(symbol).removeClass('collapse').addClass('expand').html('<span class="sym">'+expandSym+'</span>');
  createCookie(theDiv.attr('id'), 0, 7);
}
