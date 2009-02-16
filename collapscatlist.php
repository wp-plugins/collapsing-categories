<?php
/*
Collapsing Categories version: 0.8.5
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
function checkCurrentCat($cat, $categories) {
 /* this function checks whether the post being displayed belongs to a given category, 
 * or if that category's page itself is displayed. 
 * If so, it adds all parent categories to the autoExpand array, so
 * that it is automatically expanded 
 */
  global $autoExpand;
	array_push($autoExpand, $cat->slug);
	if ($cat->parent!=0) {
		foreach ($categories as $cat2) {
		  if ($cat2->term_id == $cat->parent) {
			  checkCurrentCat($cat2,$categories);
		  }
		}
	}
}

/* TODO 
* add depth option
* add option to display number of comments
*/
function getSubPosts($posts, $cat2, $subCatPosts, $showPosts) {
  global $postsToExclude;
  //print_r($options[$number]);
  $posttext2='';
  if ($excludeAll==0 && $showPosts=='no') {
    $subCatPostCount2=$cat2->count;
  } else { 
    $subCatPostCount2=0;
    foreach ($posts as $post2) {
      if ($post2->term_id == $cat2->term_id) {
        if (!in_array($post2->ID, $postsToExclude)) {
          array_push($subCatPosts, $post2->ID);
          $subCatPostCount2++;
          if ($showPosts=='no') {
            continue;
          }
          $date=preg_replace("/-/", '/', $post2->date);
          $name=$post2->post_name;
          $title_text = htmlspecialchars(strip_tags(__($post2->post_title)), 
              ENT_QUOTES);
          $tmp_text = '';
          if ($postTitleLength> 0 && strlen($title_text) > $postTitleLength ) {
            $tmp_text = substr($title_text, 0, $postTitleLength );
              $tmp_text .= ' &hellip;';
          }
          $linktext = $tmp_text == '' ? $title_text : $tmp_text;
          $posttext2.= "<li class='collapsCatPost'><a
              href='".get_permalink($post2->ID).
              "' title='$title_text'>$linktext</a></li>\n";
        }
      }
    }
  }
  return array($subCatPostCount2, $posttext2);
}

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
  global $options,$expandSym, $collapseSym, $autoExpand, $postsToExclude;
  extract($options[$number]);
  $subCatPosts=array();
  $link2='';
  if (in_array($cat->term_id, $parents)) {
    foreach ($categories as $cat2) {
      $subCatLink2=''; // clear info from subCatLink2
      if ($cat->term_id==$cat2->parent) {
        $expanded='none';
        $theID='collapsCat' . $cat2->term_id;
        if (in_array($cat2->name, $autoExpand) ||
            in_array($cat2->slug, $autoExpand) ||
            in_array($theID, array_keys($_COOKIE))) {
          $expanded='block';
        }
        if (!in_array($cat2->term_id, $parents)) {
					// check to see if there are more subcategories under this one
          $subCatCount=0;
          list($subCatPostCount2, $posttext2) = 
              getSubPosts($posts,$cat2, $subCatPosts, $showPosts);
					$subCatPostCount+=$subCatPostCount2;
          if ($subCatPostCount2<1) {
            continue;
          }
          if ($linkToCat=='yes') {
            $link2 = "<a $self href='".get_category_link($cat2)."' ";
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
            $link2 = "<a $self href='".get_category_link($cat2)."' ";
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
          list($subCatPostCount2, $posttext2) = 
              getSubPosts($posts,$cat2, $subCatPosts, $showPosts);
					$subCatPostCount+=$subCatPostCount2;
        //  $subCatCount=1;
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

                $link2 = "<a $self href='".get_category_link($cat2)."' ";
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
          $link2 .= ' ('.$subCatPostCount2.')';
        }
        $subCatLinks.= $link2 ;
        $rssLink=addFeedLink($catfeed,$cat2);
        $subCatLinks.=$rssLink;
        if (($subCatCount>0) || ($showPosts=='yes')) {
          $subCatLinks.="\n<ul id='collapsCat-" . $cat2->term_id . 
              "' style=\"display:$expanded\">\n";
					$subCatLinks.=$posttext2;
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
  global $expandSym,$collapseSym,$wpdb,$options,$post, $autoExpand, $postsToExclude, $thisCat, $thisPost;
  if (is_single() || is_category() || is_tag()) {
    $cur_category = get_the_category();
    $thisCat = $cur_category[0]->term_id;
    $thisPost = $post->ID;
  }
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
	$inExclusionArray = array();
	if ( !empty($inExclude) && !empty($inExcludeCats) ) {
		$exterms = preg_split('/[,]+/',$inExcludeCats);
    if ($inExclude=='include') {
      $in='IN';
    } else {
      $in='NOT IN';
    }
		if ( count($exterms) ) {
			foreach ( $exterms as $exterm ) {
					$sanitizedTitle = sanitize_title($exterm);
			  array_push($inExclusionArray, $sanitizedTitle);
				if (empty($inExclusions))
					$inExclusions = "'$sanitizedTitle'";
				else
					$inExclusions .= ", '$sanitizedTitle'";
			}
		}
	}
	if ( empty($inExclusions) ) {
		$inExcludeQuery = "";
  } else {
    $inExcludeQuery ="AND t.slug $in ($inExclusions)";
  }

  $isPage='';
  if ($showPages=='no') {
    $isPage="AND $wpdb->posts.post_type='post'";
  }
  if ($catSort!='') {
    if ($catSort=='catName') {
      $catSortColumn="ORDER BY t.name";
    } elseif ($catSort=='catId') {
      $catSortColumn="ORDER BY t.term_id";
    } elseif ($catSort=='catSlug') {
      $catSortColumn="ORDER BY t.slug";
    } elseif ($catSort=='catOrder') {
      $catSortColumn="ORDER BY trelationships.term_order";
    } elseif ($catSort=='catCount') {
      $catSortColumn="ORDER BY tt.count";
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
	if ($olderThan > 0) {
		$now = date('U');
		$olderThanQuery= "AND  date(post_date) > '" . 
			date('Y-m-d', $now-date('U',$olderThan*60*60*24)) . "'";
	}

  echo "\n    <ul class='collapsCatList'>\n";

/*
  $catquery = "SELECT $wpdb->term_taxonomy.count as 'count',
			$wpdb->terms.term_id, $wpdb->terms.name, $wpdb->terms.slug,
			$wpdb->term_taxonomy.parent, $wpdb->term_taxonomy.description 
			FROM $wpdb->terms, $wpdb->term_taxonomy, $wpdb->term_relationships
			WHERE $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id 
    		$catTagQuery $inExcludeQuery AND $wpdb->terms.slug!='blogroll'
      GROUP BY $wpdb->terms.term_id $catSortColumn
			$catSortOrder";
*/
$catquery = "SELECT t.*, tt.* FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy IN ('category') $inExcludeQuery AND t.slug!='blogroll' $catSortColumn $catSortOrder ";
//$catquery = "SELECT t.*, tt.* FROM wp_terms AS t INNER JOIN wp_term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy IN ('category')  ORDER BY t.name ASC  ";
  if ($showPosts=='yes') {
    $postquery= "select ID, slug, date(post_date) as date, post_status,
         post_title, post_name, name, object_id,
         $wpdb->terms.term_id from $wpdb->term_relationships, $wpdb->posts,
         $wpdb->terms, $wpdb->term_taxonomy 
         WHERE $wpdb->term_taxonomy.term_id = $wpdb->terms.term_id 
         AND object_id=ID 
         $olderThanQuery
         AND post_status='publish'
         AND $wpdb->term_relationships.term_taxonomy_id =
             $wpdb->term_taxonomy.term_taxonomy_id 
         $catTagQuery $isPage $postSortColumn $postSortOrder";
    $posts= $wpdb->get_results($postquery); 
  }
  $categories = $wpdb->get_results($catquery);
  $totalPostCount=count($posts);
  if ($totalPostCount>1000) {
    $options[$number]['showPosts']='no';
    $showPosts='no';
  }
  $parents=array();
  foreach ($categories as $cat) {
    if ($cat->parent!=0) {
      array_push($parents, $cat->parent);
    }
    if (!empty($thisCat) && $cat->term_id == $thisCat) {
      checkCurrentCat($cat,$categories);
    }
  }
	$postsToExclude=array();
	if ($excludeAll==1) {
		foreach ($posts as $post) {
			if (in_array($post->slug, $inExclusionArray)) {
				array_push($postsToExclude, $post->ID);
			}
		}
	}
  if ($debug==1) {
    echo "<pre style='display:none' >";
    printf ("MySQL server version: %s\n", mysql_get_server_info());
    echo "\ncollapsCat options:\n";
    print_r($options[$number]);
    echo "\npostsToExclude:\n";
    print_r($postsToExclude);
    echo "CATEGORY QUERY: \n $catquery\n";
    echo "\nCATEGORY QUERY RESULTS\n";
    print_r($categories);
    echo "POST QUERY:\n $postquery\n";
    echo "\nPOST QUERY RESULTS\n";
    print_r($posts);
    echo "</pre>";
  }

  foreach( $categories as $cat ) {
    if ($cat->parent==0) {
      if ((is_category() || is_tag()) && $cat->term_id == $thisCat)
        $self="class='self'";
      else
        $self="";
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
          $link = "<a $self href='".get_category_link($cat)."' ";
          if ( empty($cat->description) ) {
            $link .= 'title="'. 
                sprintf(__("View all posts filed under %s"),
                wp_specialchars($cat->name)) . '"';
          } else {
            $link .= 'title="' . wp_specialchars(apply_filters('description',$cat->description,$cat)) . '"';
          }
          $link .= '>';
          $link .= __($cat->name).'</a>';
          if ($showPosts=='yes' || $subCatPostCount>0) {
            if ($expanded=='block') {
              $span= "      <li class='collapsCat'>".
                  "<span class='collapsCat hide' ".
                  "onclick='expandCollapse(event, $expand, $animate, \"collapsCat\"); return false'>".
                  "<span class='sym'>$collapseSym</span></span>";
            } else {
              $span = "      <li class='collapsCat'>".
                  "<span class='collapsCat show' ".
                  "onclick='expandCollapse(event, $expand, $animate, \"collapsCat\"); return false'>".
                  "<span class='sym'>$expandSym</span></span>";
            }
          } else {
            $span = "      <li class='collapsCatPost'>";
          }
        } else {
          if ($showPosts=='yes') {
            $link = apply_filters('list_cats', $cat->name, $cat) . '</span>';
            if ($expanded=='block') {
              $span ="      <li class='collapsCat'>".
                  "<span class='collapsCat hide' ".
                  "onclick='expandCollapse(event, $expand, $animate, \"collapsCat\"); return false'>".
                  "<span class='sym'>$collapseSym</span>";
            } else {
              $span = "      <li class='collapsCat'>".
                  "<span class='collapsCat show' ".
                  "onclick='expandCollapse(event, $expand, $animate, \"collapsCat\"); return false'>".
                  "<span class='sym'>$expandSym</span>";
            }
          } else {
            // don't include the triangles if posts are not shown and there
            // are no more subcategories
            if ($subCatPostCount>0) {
              $link = apply_filters('list_cats', $cat->name, $cat).'</span>';
              if ($expanded=='block') {
                $span =  "      <li class='collapsCat'>".
                    "<span class='collapsCat hide' ".
                    "onclick='expandCollapse(event, $expand, $animate, \"collapsCat\"); return false'>".
                    "<span class='sym'>$collapseSym</span>";
              } else {
                $span =  "      <li class='collapsCat'>".
                    "<span class='collapsCat show' ".
                    "onclick='expandCollapse(event, $expand, $animate, \"collapsCat\"); return false'>".
                    "<span class='sym'>$expandSym</span>";
              }
            } else {
              $link = "<a $self href='".get_category_link($cat)."' ";
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
              $span = "      <li class='collapsCatPost'>";
            } 
          }
        }
        // Now print out the post info
        if( ! empty($posts) ) {
					$posttext='';
            foreach ($posts as $post) {
              if ($post->term_id == $cat->term_id 
                  && (!in_array($post->ID, $subCatPosts))) {
								if (!in_array($post->ID, $postsToExclude)) {  
								  $subCatPostCount++;
                  if ($showPosts=='no') {
                    continue;
                  }
            if (is_single() && $post->ID == $thisPost)
              $self="class='self'";
            else
              $self="";
									$date=preg_replace("/-/", '/', $post->date);
									$name=$post->post_name;
									$title_text = htmlspecialchars(strip_tags(
									    __($post->post_title)), ENT_QUOTES);
									$tmp_text = '';
									if ($postTitleLength> 0 && 
									    strlen($title_text) > $postTitleLength ) {
										$tmp_text = substr($title_text, 0, $postTitleLength );
											$tmp_text .= ' &hellip;';
									}
									$linktext = $tmp_text == '' ? $title_text : $tmp_text;
									$posttext.= "<li class='collapsCatPost'><a
										href='".get_permalink($post->ID).
										"' title='$title_text'>$linktext</a></li>\n";
								} else {
								  //$subCatPostCount--;
								  //$theCount--;
								}
              }
            // close <ul> and <li> before starting a new category
          } 
        }
        if( $showPostCount=='yes') {
          $link .= ' (' . $subCatPostCount.')';
        }
        $link.=$rssLink;
				if ($subCatPostCount<1) {
					$link='';
					$span='';
				}
          print($span . $link );
        if (($subCatPostCount>0) || ($showPosts=='yes')) {
          print( "\n     <ul id='collapsCat-" . $cat->term_id .
              "' style=\"display:$expanded\">\n" );
        }
        echo $subCatLinks;
				if ($showPosts=='yes') {
					print($posttext);
				}
				if ($subCatPostCount>0 || $showPosts=='yes') {
					echo "        </ul>\n";
				}
				echo "      </li> <!-- ending category -->\n";
      } // end if theCount>0
    }
  }
  echo "    </ul> <!-- ending collapsCat -->\n";
}
?>
