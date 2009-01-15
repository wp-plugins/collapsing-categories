<?php
/*
Collapsing Categories version: 0.8.3
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

/* TODO 
* add depth option
* add option to display number of comments
*/
function addFeedLink($feed,$cat) {
  if ($feed=='text') {
    $rssLink= '<a href="' . get_category_feed_link($cat->term_id) .
        '">&nbsp;(RSS)</a>';
  } elseif ($feed=='image') {
    $rssLink= '<a href="' . get_category_feed_link($cat->term_id) .
        '">&nbsp;<img src="' .get_settings(siteurl) .
        '/wp-includes/images/rss.png" /></a>';
  } else {
    $rssLink='';
  }
  return $rssLink;
}

function get_sub_cat($cat, $categories, $parents, $posts,
  $subCatCount,$subCatPostCount,$number,$expanded) {
  global $options,$expandSym, $collapseSym, $autoExpand;
  print_r($options[number]);
  extract($options[$number]);
  $subCatPosts=array();
  $link2='';
  if (in_array($cat->term_id, $parents)) {
    foreach ($categories as $cat2) {
      $subCatLink2=''; // clear info from subCatLink2
      if ($cat->term_id==$cat2->parent) {
        // check to see if there are more subcategories under this one
        $subCatPostCount=$subCatPostCount+$cat2->count;
        $expanded='none';
        $theID='collapsCat' . $cat2->term_id;
        //echo "theID=$theID";
        if (in_array($cat2->name, $autoExpand) ||
            in_array($cat2->slug, $autoExpand) ||
            in_array($theID, array_keys($_COOKIE))) {
          $expanded='block';
        }
        if (!in_array($cat2->term_id, $parents)) {
          $subCatCount=0;
          if ($linkToCat=='yes') {
            $link2 = "<a href='".get_category_link($cat2->term_id)."' ";
            if ( empty($cat2->description) ) {
              $link2 .= 'title="'. 
                  sprintf(__("View all posts filed under %s"), 
                  wp_specialchars($cat2->name)) . '"';
            } else {
              $link2 .= 'title="' . 
                  wp_specialchars(apply_filters('description', 
                  $cat2->description,$cat2)) . '"';
            }
            $link2 .= '>';
            $link2 .= apply_filters('list_cats', $cat2->name, $cat2).
                '</a>';
            if ($showPosts=='yes') {
              if ($expanded=='block') {
                $subCatLinks.=( "<li class='collapsCat'>".
                    "<span class='collapsCat hide' ".
                    "onclick='expandCollapse(event, $expand, $animate, \"collapsCat\"); return false'>" . 
                    "<span class='sym'>$collapseSym</span></span>" );
              } else {
                $subCatLinks.=( "<li class='collapsCat'>".
                    "<span class='collapsCat show' ".
                    "onclick='expandCollapse(event, $expand, $animate, \"collapsCat\"); return false'>" . 
                    "<span class='sym'>$expandSym</span></span>" );
              }
            } else {
              $subCatLinks.=( "<li class='collapsCatPost'>" );
            }
          } else {
            $link2 = "<a href='".get_category_link($cat2->term_id)."' ";
            if ( empty($cat2->description) ) {
              $link2 .= 'title="'. 
                  sprintf(__("View all posts filed under %s"), 
                  wp_specialchars($cat2->name)) . '"';
            } else {
              $link2 .= 'title="' . 
                  wp_specialchars(apply_filters('description', 
                  $cat2->description,$cat2)) . '"';
            }
            $link2 .= '>';
            $link2 .= apply_filters('list_cats', $cat2->name, $cat2).
                '</a>';
            //$link2 = apply_filters('list_cats', $cat2->name, $cat2).
                '</span>';
            if ($showPosts=='yes') {
              $link2 = apply_filters('list_cats', $cat2->name, $cat2).
                  "</span>";
              $subCatLinks.= "<li class='collapsCat'>".
                  "<span class='collapsCat show' ".
                  "onclick='expandCollapse(event, $expand, $animate, \"collapsCat\");".
                  "return false'>".
                  "<span class='sym'>$expandSym</span>";
            } else {
              $subCatLinks.=( "<li class='collapsCatPost'>" );
            }
          }
        } else {
          list ($subCatLink2, $subCatCount,$subCatPostCount,$subCatPosts)= 
              get_sub_cat($cat2, $categories, $parents, $posts, $subCatCount,
              $subCatPostCount, $number,$expanded);
          $subCatCount=1;
          if ($linkToCat=='yes') {
            if ($expanded=='block') {
              $subCatLinks.=( "<li class='collapsCat'>".
                  "<span class='collapsCat hide' ".
                  "onclick='expandCollapse(event, $expand, $animate, \"collapsCat\"); return false'>" . 
                  "<span class='sym'>$collapseSym</span></span>" );
            } else {
              $subCatLinks.=( "<li class='collapsCat'>".
                  "<span class='collapsCat show' ".
                  "onclick='expandCollapse(event, $expand, $animate, \"collapsCat\"); return false'>" . 
                  "<span class='sym'>$expandSym</span></span>" );
            }

                $link2 = "<a href='".get_category_link($cat2->term_id)."' ";
                if ( empty($cat2->description) ) {
                  $link2 .= 'title="'. 
                      sprintf(__("View all posts filed under %s"), 
                      wp_specialchars($cat2->name)) . '"';
                } else {
                  $link2 .= 'title="' . 
                      wp_specialchars(apply_filters('description', 
                      $cat2->description,$cat2)) . '"';
                }
                $link2 .= '>';
                $link2 .= apply_filters('list_cats', $cat2->name, $cat2).'</a>';
          } else {
            if ($showPosts=='yes') {
            
              $link2 = apply_filters('list_cats', $cat2->name,
                  $cat2).'</span>';
              $subCatLinks.="<li class='collapsCat'>".
                  "<span class='collapsCat show' ".
                  "onclick='expandCollapse(event, $expand, $animate,
									\"collapsCat\"); return false'>".
                  "<span class='sym'>$expandSym</span>";
            } else {
              $subCatLinks.="<li class='collapsCat'>".
                  "<span class='collapsCat show' ".
                  "onclick='expandCollapse(event, $expand, $animate, \"collapsCat\"); return false'>".
                  "<span class='sym'>$expandSym</span>";
              $link2 = apply_filters('list_cats', $cat2->name,
                  $cat2).'</span>';
            }
          }
        }

        if( $showPostCount=='yes') {
          list ($subCatLink3, $subCatCount2,$subCatPostCount2,$subCatPosts2)=
              get_sub_cat($cat2, $categories, $parents, $posts,0,0,
              $number,$expanded);
          $theCount=$subCatPostCount2 + $cat2->count;
          $link2 .= ' ('.$theCount.')';
        }
        $subCatLinks.= $link2 ;
        $rssLink=addFeedLink($catfeed,$cat2);
        $subCatLinks.=$rssLink;
        if (($subCatCount>0) || ($showPosts=='yes')) {
          $subCatLinks.="\n<ul id='collapsCat-" . $cat2->term_id . 
              "' style=\"display:$expanded\">\n";
        }
          if ($showPosts=='yes') {
            foreach ($posts as $post2) {
              if ($post2->term_id == $cat2->term_id) {
                array_push($subCatPosts, $post2->ID);
                $date=preg_replace("/-/", '/', $post2->date);
                $name=$post2->post_name;
                $subCatLinks.= "<li class='collapsCatPost'><a
								href='".get_permalink($post2->ID)."'>" .  strip_tags($post2->post_title) . "</a></li>\n";

              }
            }
          }
        // add in additional subcategory information
        $subCatLinks.="$subCatLink2";
        // close <ul> and <li> before starting a new category
        if (($subCatCount>0) || ($showPosts=='yes')) {
          $subCatLinks.= "          </ul>\n";
        }
        $subCatLinks.= "         </li> <!-- ending subcategory -->\n";
      }
    }
  }
  return array($subCatLinks,$subCatCount,$subCatPostCount,$subCatPosts);
}

function list_categories($number) {
  global $expandSym,$collapseSym,$wpdb,$options, $autoExpand;
  $options=get_option('collapsCatOptions');
  extract($options[$number]);
  if ($expand==1) {
    $expandSym='+';
    $collapseSym='—';
  } elseif ($expand==2) {
    $expandSym='[+]';
    $collapseSym='[—]';
  } elseif ($expand==3) {
    $expandSym="<img src='". get_settings('siteurl') .
         "/wp-content/plugins/collapsing-categories/" . 
         "img/expand.gif' alt='expand' />";
    $collapseSym="<img src='". get_settings('siteurl') .
         "/wp-content/plugins/collapsing-categories/" . 
         "img/collapse.gif' alt='collapse' />";
  } else {
    $expandSym='►';
    $collapseSym='▼';
  }
	$inExclusions = array();
	if ( !empty($inExclude) && !empty($inExcludeCats) ) {
		$exterms = preg_split('/[,]+/',$inExcludeCats);
    if ($inExclude=='include') {
      $in='IN';
    } else {
      $in='NOT IN';
    }
		if ( count($exterms) ) {
			foreach ( $exterms as $exterm ) {
				if (empty($inExclusions))
					$inExclusions = "'" . sanitize_title($exterm) . "'";
				else
					$inExclusions .= ", '" . sanitize_title($exterm) . "' ";
			}
		}
	}
	if ( empty($inExclusions) ) {
		$inExcludeQuery = "";
  } else {
    $inExcludeQuery ="AND $wpdb->terms.slug $in ($inExclusions)";
  }

  $isPage='';
  if ($showPages=='no') {
    $isPage="AND $wpdb->posts.post_type='post'";
  }
  if ($catSort!='') {
    if ($catSort=='catName') {
      $catSortColumn="ORDER BY $wpdb->terms.name";
    } elseif ($catSort=='catId') {
      $catSortColumn="ORDER BY $wpdb->terms.term_id";
    } elseif ($catSort=='catSlug') {
      $catSortColumn="ORDER BY $wpdb->terms.slug";
    } elseif ($catSort=='catOrder') {
      $catSortColumn="ORDER BY $wpdb->term_relationships.term_order";
    } elseif ($catSort=='catCount') {
      $catSortColumn="ORDER BY $wpdb->term_taxonomy.count";
    }
  } 
  if ($postSort!='') {
    if ($postSort=='postDate') {
      $postSortColumn="ORDER BY $wpdb->posts.post_date";
    } elseif ($postSort=='postId') {
      $postSortColumn="ORDER BY $wpdb->posts.id";
    } elseif ($postSort=='postTitle') {
      $postSortColumn="ORDER BY $wpdb->posts.post_title";
    } elseif ($postSort=='postComment') {
      $postSortColumn="ORDER BY $wpdb->posts.comment_count";
    }
  } 
	if ($defaultExpand!='') {
		$autoExpand = preg_split('/,\s*/',$defaultExpand);
  } else {
	  $autoExpand = array();
  }

	if ($catTag == 'tag') {
	  $catTagQuery= "AND $wpdb->term_taxonomy.taxonomy = 'post_tag'";
	} elseif ($catTag == 'both') {
	  $catTagQuery= "AND $wpdb->term_taxonomy.taxonomy IN
				('category','post_tag')";
	} else {
	  $catTagQuery= "AND $wpdb->term_taxonomy.taxonomy = 'category'";
	}

  echo "\n    <ul id='collapsCatList'>\n";

  $catquery = "SELECT $wpdb->term_taxonomy.count as 'count',
			$wpdb->terms.term_id, $wpdb->terms.name, $wpdb->terms.slug,
			$wpdb->term_taxonomy.parent, $wpdb->term_taxonomy.description 
			FROM $wpdb->terms, $wpdb->term_taxonomy, $wpdb->term_relationships
			WHERE $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id 
      AND $wpdb->term_taxonomy.term_taxonomy_id = $wpdb->term_relationships.term_taxonomy_id 
			$catTagQuery $inExcludeQuery 
      GROUP BY $wpdb->terms.term_id $catSortColumn
			$catSortOrder";
  $postquery= "select distinct ID, date(post_date) as date, post_status,
       post_title, post_name, name, object_id,
       $wpdb->terms.term_id from $wpdb->term_relationships, $wpdb->posts,
       $wpdb->terms, $wpdb->term_taxonomy 
       WHERE $wpdb->term_taxonomy.term_id = $wpdb->terms.term_id 
       AND object_id=ID 
       AND post_status='publish'
       AND $wpdb->term_relationships.term_taxonomy_id =
           $wpdb->term_taxonomy.term_taxonomy_id 
       $catTagQuery $isPage $postSortColumn $postSortOrder";
  $categories = $wpdb->get_results($catquery);
  $posts= $wpdb->get_results($postquery); 
  $parents=array();
  foreach ($categories as $cat) {
    if ($cat->parent!=0) {
      array_push($parents, $cat->parent);
    }
  }
  if ($debug==1) {
    echo "<pre style='display:none' >";
    printf ("MySQL server version: %s\n", mysql_get_server_info());
    echo "CATEGORY QUERY: \n $catquery\n";
    echo "\nCATEGORY QUERY RESULTS\n";
    print_r($categories);
    echo "POST QUERY:\n $postquery\n";
    echo "\nPOST QUERY RESULTS\n";
    print_r($posts);
    echo "\ncollapsCat options:\n";
    print_r($options[$number]);
    echo "</pre>";
  }

  
  foreach( $categories as $cat ) {
    if ($cat->parent==0) {
      //$lastCat= $cat->term_id;

      $rssLink=addFeedLink($catfeed,$cat);
      $subCatPostCount=0;
      $subCatCount=0;
      list ($subCatLinks, $subCatCount,$subCatPostCount, $subCatPosts)=
          get_sub_cat($cat, $categories, $parents, $posts, 
          $subCatCount,$subCatPostCount,$number,$expanded);
        
      $theCount=$cat->count+$subCatPostCount;
      if ($theCount>0) {
        $expanded='none';
        $theID='collapsCat' . $cat->term_id;
        if (in_array($cat->name, $autoExpand) ||
            in_array($cat->slug, $autoExpand) ||
            in_array($theID, array_keys($_COOKIE))) {
          $expanded='block';
        }
        if ($linkToCat=='yes') {
          $link = "<a href='".get_category_link($cat->term_id)."' ";
          if ( empty($cat->description) ) {
            $link .= 'title="'. 
                sprintf(__("View all posts filed under %s"),
                wp_specialchars($cat->name)) . '"';
          } else {
            $link .= 'title="' . wp_specialchars(apply_filters('description',$cat->description,$cat)) . '"';
          }
          $link .= '>';
          $link .= apply_filters('list_cats', $cat->name, $cat).'</a>';
          if ($showPosts=='yes' || $subCatPostCount>0) {
            if ($expanded=='block') {
              print( "      <li class='collapsCat'>".
                  "<span class='collapsCat hide' ".
                  "onclick='expandCollapse(event, $expand, $animate, \"collapsCat\"); return false'>".
                  "<span class='sym'>$collapseSym</span></span>" );
            } else {
              print( "      <li class='collapsCat'>".
                  "<span class='collapsCat show' ".
                  "onclick='expandCollapse(event, $expand, $animate, \"collapsCat\"); return false'>".
                  "<span class='sym'>$expandSym</span></span>" );
            }
          } else {
            print( "      <li class='collapsCatPost'>" );
          }
        } else {
          if ($showPosts=='yes') {
            $link = apply_filters('list_cats', $cat->name, $cat) . '</span>';
            if ($expanded=='block') {
              print( "      <li class='collapsCat'>".
                  "<span class='collapsCat hide' ".
                  "onclick='expandCollapse(event, $expand, $animate, \"collapsCat\"); return false'>".
                  "<span class='sym'>$collapseSym</span>");
            } else {
              print( "      <li class='collapsCat'>".
                  "<span class='collapsCat show' ".
                  "onclick='expandCollapse(event, $expand, $animate, \"collapsCat\"); return false'>".
                  "<span class='sym'>$expandSym</span>");
            }
          } else {
            // don't include the triangles if posts are not shown and there
            // are no more subcategories
            if ($subCatPostCount>0) {
              $link = apply_filters('list_cats', $cat->name, $cat).'</span>';
              if ($expanded=='block') {
                print( "      <li class='collapsCat'>".
                    "<span class='collapsCat hide' ".
                    "onclick='expandCollapse(event, $expand, $animate, \"collapsCat\"); return false'>".
                    "<span class='sym'>$collapseSym</span>");
              } else {
                print( "      <li class='collapsCat'>".
                    "<span class='collapsCat show' ".
                    "onclick='expandCollapse(event, $expand, $animate, \"collapsCat\"); return false'>".
                    "<span class='sym'>$expandSym</span>");
              }
            } else {
              $link = "<a href='".get_category_link($cat->term_id)."' ";
              if ( empty($cat->description) ) {
                $link .= 'title="'. 
                    sprintf(__("View all posts filed under %s"),
                    wp_specialchars($cat->name)) . '"';
              } else {
                $link .= 'title="' . wp_specialchars(apply_filters(
                    'description',$cat->description,$cat)) . '"';
              }
              $link .= '>';
              $link .= apply_filters('list_cats', $cat->name, $cat).'</a>';
              print( "      <li class='collapsCatPost'>" );
            } 
          }
        }
        if( $showPostCount=='yes') {
          $link .= ' (' . $theCount.')';
        }
        $link.=$rssLink;
          print( $link );
        if (($subCatPostCount>0) || ($showPosts=='yes')) {
          print( "\n     <ul id='collapsCat-" . $cat->term_id .
              "' style=\"display:$expanded\">\n" );
        }
        echo $subCatLinks;
        // Now print out the post info
        if( ! empty($posts) ) {
          if ($showPosts=='yes') {
            foreach ($posts as $post) {
              if (($post->term_id == $cat->term_id)  
                  && (!in_array($post->ID, $subCatPosts))) {
                $date=preg_replace("/-/", '/', $post->date);
                $name=$post->post_name;
                echo "          <li class='collapsCatPost'><a href='".
                    get_permalink($post->ID)."'>" .  
                    strip_tags($post->post_title) . "</a></li>\n";
              }
            }
            // close <ul> and <li> before starting a new category
          } 
          if ($subCatPostCount>0 || $showPosts=='yes') {
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
