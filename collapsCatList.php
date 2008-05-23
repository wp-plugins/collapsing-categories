<?php
/*
Collapsing Categories version: 0.5.6
Copyright 2007 Robert Felty

This work is largely based on the Collapsing Categories plugin by Andrew Rader
(http://voidsplat.org), which was also distributed under the GPLv2. I have tried
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

// Helper functions
function get_sub_cat($cat, $categories, $parents, $posts, $taxonomy,$subCatCount,$subCatPostCount) {
  global $expand, $collapse;
  $subCatPosts=array();
  if (in_array($cat->term_id, $parents)) {
    foreach ($categories as $cat2) {
      //$subCatLinks.= "cat2 =". $cat2->term_id;
        $subCatLink2=''; // clear info from subCatLink2
      if ($cat->term_id==$cat2->parent) {
          // check to see if there are more subcategories under this one
          $subCatPostCount=$subCatPostCount+$cat2->count;
        if (!in_array($cat2->term_id, $parents)) {
          $subCatCount=0;
          if (get_option('collapsCatShowPosts')=='yes') {
            $subCatLinks.=( "<li class='collapsCat'><span class='collapsCat show' onclick='expandCat(event); return false'>$expand&nbsp;</span>" );
          } else {
            $subCatLinks.=( "<li class='collapsCat'>&nbsp;&nbsp;" );
          }
        } else {
          //echo "subCatCount = $subCatCount ... before calling get_sub_cat\n";
          list ($subCatLink2, $subCatCount,$subCatPostCount,$subCatPosts)= get_sub_cat($cat2, $categories, $parents, $posts,$taxonomy,$subCatCount,$subCatPostCount);
          $subCatCount=1;
          //echo "subCatCount = $subCatCount ... after calling get_sub_cat\n";
          if (get_option('collapsCatShowPosts')=='yes') {
            $subCatLinks.=( "<li class='collapsCat'><span class='collapsCat show' onclick='expandCat(event); return false'>$expand&nbsp;</span>" );
          } else {
            $subCatLinks.=( "<li class='collapsCat'><span class='collapsCat show' onclick='expandCat(event); return false'>$expand&nbsp;</span>" );
          }
        }
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
          list ($subCatLink3, $subCatCount2,$subCatPostCount2,$subCatPosts2)= get_sub_cat($cat2, $categories, $parents, $posts,$taxonomy,0,0);
          $theCount=$subCatPostCount2 + $cat2->count;
          if ($taxonomy==true) {
            $link2 .= ' ('.$theCount.')';
          } else {
            $link2 .= ' ('.intval($cat2->category_count).')';
          }
        }
        $subCatLinks.= $link2 ;
        if (($subCatCount>0) || (get_option('collapsCatShowPosts')=='yes')) {
          $subCatLinks.="\n<ul style=\"display:none;count=$subCatCount\">\n";
        }
          if (get_option('collapsCatShowPosts')=='yes') {
            foreach ($posts as $post2) {
              if ($post2->term_id == $cat2->term_id) {
                array_push($subCatPosts, $post2->id);
                $date=preg_replace("/-/", '/', $post2->date);
                $name=$post2->post_name;
                //$subCatLinks.= "<li class='collapsCatPost'><a href='$url/$archives$date/$name'>" .  strip_tags($post2->post_title) . "</a></li>\n";
                $subCatLinks.= "<li class='collapsCatPost'><a href='".get_permalink($post2->id)."'>" .  strip_tags($post2->post_title) . "</a></li>\n";

                //$subCatLinks.= "<li class='collapsCatPost'><a href='$url/$archives$date/$name'>" .  $post2->post_title . "</a></li>\n";
              }
            }
          }
        // add in additional subcategory information
        $subCatLinks.="$subCatLink2";
        // close <ul> and <li> before starting a new category
        if (($subCatCount>0) || (get_option('collapsCatShowPosts')=='yes')) {
          $subCatLinks.= "          </ul>\n";
        }
        $subCatLinks.= "         </li> <!-- ending subcategory -->\n";
      }
    }
  }
  return array($subCatLinks,$subCatCount,$subCatPostCount,$subCatPosts);
}

/* the category and tagging database structures changed drastically between wordpress 2.1 and 2.3. We will use different queries for category based vs. term_taxonomy based database structures */
//$taxonomy=false;
function list_categories() {
  global $wpdb, $expand, $collapse;
  $expand='&#9658;';
  $collapse='&#9660;';

  if (get_option('collapsCatExpand')==1) {
    $expand='+';
    $collapse='&mdash;';
  }
  if (get_option('collapsCatLinkToArchives')=='archives') {
    $archives='archives.php/';
  } elseif (get_option('collapsCatLinkToArchives')=='index') {
    $archives='index.php/';
  } elseif (get_option('collapsCatLinkToArchives')=='root') {
    $archives='';
  }
  $exclude=get_option('collapsCatExclude');
	$exclusions = '';
	if ( !empty($exclude) ) {
		$exterms = preg_split('/[,]+/',$exclude);
		if ( count($exterms) ) {
			foreach ( $exterms as $exterm ) {
				if (empty($exclusions))
					$exclusions = " AND ( $wpdb->terms.slug <> '" . sanitize_title($exterm) . "' ";
				else
					$exclusions .= " AND $wpdb->terms.slug <> '" . sanitize_title($exterm) . "' ";
			}
		}
	}
	if ( !empty($exclusions) ) {
		$exclusions .= ')';
  }

  $taxonomy=true;
  $tables = $wpdb->query("show tables like '$wpdb->term_relationships'"); 
  if ($tables==0) {
    $taxonomy=false;
  }
  $isPage='';
  if (get_option('collapsCatIncludePages'=='no')) {
    $isPage="AND $wpdb->posts.post_type='post'";
  }
  if (get_option('collapsCatSort')!='') {
    if (get_option('collapsCatSort')=='catName') {
      $sortColumn="ORDER BY $wpdb->terms.name";
    } elseif (get_option('collapsCatSort')=='catId') {
      $sortColumn="ORDER BY $wpdb->terms.term_id";
    } elseif (get_option('collapsCatSort')=='catSlug') {
      $sortColumn="ORDER BY $wpdb->terms.slug";
    }
    $sortOrder = get_option('collapsCatSortOrder');
  } 

  echo "\n    <ul id='collapsCatList'>\n";

  if ($taxonomy==true) {
      $catquery = "SELECT $wpdb->term_taxonomy.count as 'count', $wpdb->terms.term_id, $wpdb->terms.name, $wpdb->terms.slug, $wpdb->term_taxonomy.parent FROM $wpdb->terms, $wpdb->term_taxonomy WHERE $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id AND $wpdb->terms.name != 'Blogroll' AND $wpdb->term_taxonomy.taxonomy = 'category' $exclusions $sortColumn $sortOrder";
      $postquery = "SELECT $wpdb->terms.term_id, $wpdb->terms.name, $wpdb->terms.slug, $wpdb->term_taxonomy.count, $wpdb->posts.id, $wpdb->posts.post_title, $wpdb->posts.post_name, date($wpdb->posts.post_date) as 'date' FROM $wpdb->posts, $wpdb->terms, $wpdb->term_taxonomy, $wpdb->term_relationships  WHERE $wpdb->posts.id = $wpdb->term_relationships.object_id AND $wpdb->posts.post_status='publish' AND $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id AND $wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id AND $wpdb->term_taxonomy.taxonomy = 'category' $isPage";
  } else {
    $catquery = "SELECT cat_ID, cat_name, category_nicename, category_description, category_parent, category_count FROM $wpdb->categories WHERE cat_ID > 0 AND category_parent = 0 AND category_count > 0";
    $postquery = "SELECT $wpdb->posts.post_title, $wpdb->posts.post_name, DATE($wpdb->posts.post_date) AS 'date' FROM $wpdb->posts, $wpdb->post2cat, $wpdb->categories where $wpdb->post2cat.category_id = $cat->cat_ID and $wpdb->posts.ID = $wpdb->post2cat.post_id and $wpdb->categories.cat_ID = $wpdb->post2cat.category_id and $wpdb->posts.post_status = 'publish' and $wpdb->categories.category_count>0 $isPage";
  }
    /* changing to use only one query 
     * don't forget to exclude pages if so desired
     */
  $categories = $wpdb->get_results($catquery);
  $posts= $wpdb->get_results($postquery); 
  $parents=array();
  foreach ($categories as $cat) {
    if ($cat->parent!=0) {
      array_push($parents, $cat->parent);
    }
  }
  /*echo "<pre>";
  print_r($parents);
  echo "</pre>";
  */
  foreach( $categories as $cat ) {
    //echo $cat->name;
    if ($cat->parent==0) {
      $url = get_settings('siteurl');
      $home=$url;
      $lastCat= $cat->term_id;
      // print out category name 
      if ($taxonomy==true) {
        //$link = "<a href='$url/category/".$cat->slug."' ";
        $link = "<a href='".get_category_link($cat->term_id)."' ";
      } else {
        $link = "<a href='".get_category_link($cat->cat_ID)."' ";
      }
      if ( empty($cat->category_description) ) {
        if( get_option('collapsCatShowPostCount')=='yes') {
          $link .= 'title="'. sprintf(__("View all posts filed under %s"), wp_specialchars($cat->name)) . '"';
        } else {
          $link .= "title='View all subcategories'";
        }
      } else {
        $link .= 'title="' . wp_specialchars(apply_filters('category_description',$cat->category_description,$cat)) . '"';
      }
      $link .= '>';
      if ($taxonomy==true) {
        $link .= apply_filters('list_cats', $cat->name, $cat).'</a>';
      } else {
        $link .= apply_filters('list_cats', $cat->cat_name, $cat).'</a>';
      }

      $subCatPostCount=0;
      $subCatCount=0;
      list ($subCatLinks, $subCatCount,$subCatPostCount, $subCatPosts)=get_sub_cat($cat, $categories, $parents, $posts,$taxonomy,$subCatCount,$subCatPostCount);
        
      $theCount=$cat->count+$subCatPostCount;
      if ($theCount>0) {
        if (get_option('collapsCatShowPosts')=='yes') {
          if( empty( $posts ) && empty($categories)) {
            print( "      <li class='collapsCat'><span class='collapsCat hide' onclick='expandCat(event); return false'>$collapse&nbsp;</span>" );
          } else {
            print( "      <li class='collapsCat'><span class='collapsCat show' onclick='expandCat(event); return false'>$expand&nbsp;</span>" );
          }
        } else {
          // don't include the triangles if posts are not shown and there are no
          // more subcategories
          if ($subCatPostCount>0) {
            print( "      <li class='collapsCat'><span class='collapsCat show' onclick='expandCat(event); return false'>$expand&nbsp;</span>" );
          } else {
            print( "      <li class='collapsCat'>&nbsp;&nbsp;" );
          } 
        }
        if( get_option('collapsCatShowPostCount')=='yes') {
            //$link .= "$taxonomy (".intval($cat->count) + $subCatPostCount.')';
          if ($taxonomy==true) {
            $link .= '(' . $theCount.')';
          } else {
            $link .= ' ('.intval($cat->category_count).')';
          }
        }
          print( $link );
        if (($subCatPostCount>0) || (get_option('collapsCatShowPosts')=='yes')) {
          print( "\n     <ul style=\"display:none;count=$subCatCount\">\n" );
        }
        echo $subCatLinks;
        // Now print out the post info
        if( ! empty($posts) ) {
          if (get_option('collapsCatShowPosts')=='yes') {
            foreach ($posts as $post) {
              if (($post->term_id == $cat->term_id)  && (!in_array($post->id, $subCatPosts))) {
                $date=preg_replace("/-/", '/', $post->date);
                $name=$post->post_name;
                echo "          <li class='collapsCatPost'><a href='".  get_permalink($post->id)."'>" .  strip_tags($post->post_title) . "</a></li>\n";
                //echo "<li class='collapsCatPost'><a href='$url/$archives$date/$name'>" .  $post->post_title . "</a></li>\n";
              }
            }
            // close <ul> and <li> before starting a new category
          } 
        if ($subCatPostCount>0 || get_option('collapsCatShowPosts')=='yes') {
          echo "        </ul>\n";
        }
        echo "      </li> <!-- ending category -->\n";
        }
      } // end if theCount>0
    }
  }
  echo "    </ul> <!-- ending collapsCat -->\n";
}
?>
