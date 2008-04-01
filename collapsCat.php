<?php
/*
Plugin Name: Collapsing Categories
Plugin URI: http://blog.robfelty.com/plugins
Description: Uses javascript to expand and collapse categories to show the posts that belong to the category 
Author: Robert Felty
Version: 0.4
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
add_action('admin_menu', array('collapsCat','setup'));

class collapsCat {

	function init() {
		if( function_exists('add_option') ) {
			add_option( 'collapsCatShowPostCount', 'yes' );
			add_option( 'collapsCatShowPages', 'no' );
			add_option( 'collapsCatLinkToArchives', 'root' );
			add_option( 'collapsCatSort', 'catName' );
			add_option( 'collapsCatSortOrder', 'ASC' );
		}
	}

	function setup() {
		if( function_exists('add_options_page') ) {
			add_options_page(__('Collapsing Categories'),__('Collapsing Categories'),1,basename(__FILE__),array('collapsCat','ui'));
		}
	}

	function ui() {
		include_once( 'collapsCatUI.php' );
	}

	function list_categories() {
		global $wpdb;

		include( 'collapsCatList.php' );

		return;
	}

	function get_head() {
		$url = get_settings('siteurl');
    echo "<style type='text/css'>
		@import '$url/wp-content/plugins/collapsing-categories/collapsCat.css';
    </style>\n";
		echo "<script type=\"text/javascript\">\n";
		echo "// <![CDATA[\n";
		echo "// These variables are part of the Collapsing Categories Plugin version: 0.4\n// Copyright 2007 Robert Felty (robfelty.com)\n";
    echo "function expandCat( e ) {
    if( e.target ) {
      src = e.target;
    }
    else {
      src = window.event.srcElement;
    }

    srcList = src.parentNode;
    childList = null;

    for( i = 0; i < srcList.childNodes.length; i++ ) {
      if( srcList.childNodes[i].nodeName.toLowerCase() == 'ul' ) {
        childList = srcList.childNodes[i];
      }
    }

    if( src.getAttribute( 'class' ) == 'collapsArch hide' ) {
      childList.style.display = 'none';
      src.setAttribute('class','collapsArch show');
      src.setAttribute('title','click to expand');
      src.innerHTML='&#9658&nbsp;';
    }
    else {
      childList.style.display = '';
      src.setAttribute('class','collapsArch hide');
      src.setAttribute('title','click to collapse');
      src.innerHTML='&#9660&nbsp;';
    }

    if( e.preventDefault ) {
      e.preventDefault();
    }

    return false;
  }\n";

		echo "// ]]>\n</script>\n";
	}
	function getCollapsSubCat($cat, $categories, $parents, $posts, $taxonomy,$subCatCount) {
		if (in_array($cat->term_id, $parents)) {
			foreach ($categories as $cat2) {
				if ($cat->term_id==$cat2->parent) {
					// print out category name 
					$subCatLinks.=( "<li class='collapsCat'><span class='collapsCat show' onclick='expandCat(event); return false'>&#9658;&nbsp;</span>" );
					if ($taxonomy==true) {
						$link2 = "<a href='".get_category_link($cat2->term_id)."' ";
						//$link2 = "<a href='$url/category/".$cat2->slug."' ";
					} else {
						$link2 = "<a href='".get_category_link($cat2->cat_ID)."' ";
						//$link2 = "<a href=$url/'".get_category_link($cat2->cat_ID)."' ";
					}
					if ( empty($cat2->category_description) ) {
						$link2 .= 'title="'. sprintf(__("View all posts filed under %s"), wp_specialchars($cat2->name)) . '"';
					} else {
						$link2 .= 'title="' . wp_specialchars(apply_filters('category_description',$cat2->category_description,$cat2)) . '"';
					}
					$link2 .= '>';
					if ($taxonomy==true) {
						$link2 .= apply_filters('list_cats', $cat2->name, $cat2).'</a>';
					} else {
						$link2 .= apply_filters('list_cats', $cat2->cat_name, $cat2).'</a>';
					}

					if( get_option('collapsCatShowPostCount')=='yes') {
						if ($taxonomy==true) {
							$link2 .= ' ('.intval($cat2->count).')';
						} else {
							$link2 .= ' ('.intval($cat2->category_count).')';
						}
					}
					$subCatLinks.= $link2 ;
					$subCatLinks.="\n<ul style=\"display:none;\">\n";
					if (!in_array($cat2->term_id, $parents)) {
						$subCatCount=$subCatCount+$cat2->count;
					//if( !empty($posts)) {
						foreach ($posts as $post2) {
							if ($post2->term_id == $cat2->term_id) {
								$date=preg_replace("/-/", '/', $post2->date);
								$name=$post2->post_name;
								$subCatLinks.= "<li class='collapsCatPost'><a href='$url/$archives$date/$name'>" .  strip_tags($post2->post_title) . "</a></li>\n";
								//$subCatLinks.= "<li class='collapsCatPost'><a href='$url/$archives$date/$name'>" .  $post2->post_title . "</a></li>\n";
							}
						}
					} else {
						list ($subCatLink2, $subCatCount)= getCollapsSubCat($cat2, $categories, $parents, $posts,$taxonomy,$subCatCount);
						$subCatLinks.="$subCatLink2";
					}
					// close <ul> and <li> before starting a new category
					$subCatLinks.= "</ul>  </li> <!-- ending subcategory -->\n";
				}
			}
		}
		return array($subCatLinks,$subCatCount);
	}
}

function collapsCat() {
	collapsCat::list_categories();
}
?>
