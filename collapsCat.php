<?php
/*
Plugin Name: Collapsing Categories
Plugin URI: http://blog.robfelty.com/plugins
Description: Uses javascript to expand and collapse categories to show the posts that belong to the category 
Author: Robert Felty
Version: 0.6
Author URI: http://robfelty.com
Tags: sidebar, widget, categories

Copyright 2007 Robert Felty

This work is largely based on the Fancy Categories plugin by Andrew Rader
(http://nymb.us), which was also distributed under the GPLv2. I have tried
contacting him, but his website has been down for quite some time now. See the
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

add_action( 'wp_head', array('collapsCat','get_head'));
add_action('activate_collapsing-categories/collapsCat.php', array('collapsCat','init'));

class collapsCat {

	function init() {
	}

	function setup() {
	}

	function get_head() {
		$url = get_settings('siteurl');
    echo "<style type='text/css'>
		@import '$url/wp-content/plugins/collapsing-categories/collapsCat.css';
    </style>\n";
		echo "<script type=\"text/javascript\">\n";
		echo "// <![CDATA[\n";
		echo "// These variables are part of the Collapsing Categories Plugin version: 0.6\n// Copyright 2007 Robert Felty (robfelty.com)\n";
    echo "function expandCat( e, expand ) {
    if (expand==1) {
      expand='+';
      collapse='—';
    } else if (expand==2) {
      expand='[+]';
      collapse='[—]';
    } else {
      expand='►';
      collapse='▼';
    }
    if( e.target ) {
      src = e.target;
    }
    else {
      src = window.event.srcElement;
    }

    srcList = src.parentNode;
    if (srcList.nodeName.toLowerCase() == 'span') {
      srcList= srcList.parentNode;
      src= src.parentNode;
    }
    childList = null;

    for( i = 0; i < srcList.childNodes.length; i++ ) {
      if( srcList.childNodes[i].nodeName.toLowerCase() == 'ul' ) {
        childList = srcList.childNodes[i];
      }
    }

    if( src.getAttribute( 'class' ) == 'collapsCat hide' ) {
      childList.style.display = 'none';
      src.setAttribute('class','collapsCat show');
      src.setAttribute('title','click to expand');
      //src.innerHTML=src.innerHTML.replace(/collapse/g,'expand');
      //src.innerHTML=src.innerHTML.replace(/^[^<]* /,expand+' ');
      var pattern = collapse;
      var replace = expand;
      //alert(src.innerHTML + 'pattern=' + pattern + 'replace=' +replace);
      src.innerHTML=src.innerHTML.replace(pattern,replace);
    }
    else {
      childList.style.display = 'block';
      src.setAttribute('class','collapsCat hide');
      src.setAttribute('title','click to collapse');
      var pattern = expand;
      var replace = collapse;
      //alert(src.innerHTML + 'pattern=' + pattern + 'replace=' +replace);
      src.innerHTML=src.innerHTML.replace(pattern,replace);
      //alert(src.innerHTML);
      //src.innerHTML=src.innerHTML.replace(/^[^<]* /,collapse+' ');
    }

    if( e.preventDefault ) {
      e.preventDefault();
    }

    return false;
  }\n";

		echo "// ]]>\n</script>\n";
	}
}


		include( 'collapsCatList.php' );
function collapsCat($number) {
	list_categories($number);
}
include('collapsCatWidget.php');
?>
