<?php
/*
Collapsing Categories version: 1.0.1
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
global $advanced;
if (file_exists(dirname(__FILE__) . "/advanced-config.php")) {
  include('advanced-config.php');
} else {
  include('advanced-config-sample.php');
}
extract($advanced);

function getCollapsCatLink($cat,$catlink,$self) {
  if (empty($catlink)) {
    if ($cat->taxonomy=='post_tag') {
      $link = "<a $self href='".get_tag_link($cat->term_id)."' ";
    } else {
      $link = "<a $self href='".get_category_link($cat->term_id)."' ";
    }
  } else {
    if ($cat->taxonomy=='post_tag') {
      $link = "<a $self href='".get_tag_link($cat)."' ";
    } else {
      $link = "<a $self href='".get_category_link($cat)."' ";
    }
  }
  return($link);
}
function miscPosts($cat,$catlink,$subCatPostCount2, $posttext) {
  /* this function will group posts into a miscellaneous sub-category */
  global $options, $expandSym,$expandSymJS;
  extract($options);
  $miscPosts="      <li class='collapsCat'>".
      "<span class='collapsCat show' ".
      "onclick='expandCollapse(event, \"$expandSymJS\", \"$collapseSymJS\", $animate, " .
      "\"collapsCat\"); return false'>".
      "<span class='sym'>$expandSym</span>";
  if ($linkToCat=='yes') {
    $thisLink=getCollapsCatLink($cat,$catlink,$self);
    $miscPosts.="</span>$thisLink>$addMiscTitle</a>";
  } else {
    $miscPosts.="$addMiscTitle</span>";
  }
  if( $showPostCount=='yes') {
    $miscPosts.=' (' . $subCatPostCount2.')';
  }
  $miscPosts.= "\n     <ul id='collapsCat-" . $cat->term_id .
      "-misc' style=\"display:$expanded\">\n" ;
  $miscPosts.=$posttext;
  $miscPosts.="    </ul></li>\n";
  return($miscPosts);
}

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
  global $postsToExclude, $options;
  extract($options);
  $posttext2='';
  if ($excludeAll==0 && !$showPosts) {
    $subCatPostCount2=$cat2->count;
  } else { 
    $subCatPostCount2=0;
    if (count($posts)==0) {
      return array(0,'');
    }
    foreach ($posts as $post2) {
      if ($post2->term_id == $cat2->term_id) {
        if (!in_array($post2->ID, $postsToExclude)) {
          array_push($subCatPosts, $post2->ID);
          $subCatPostCount2++;
          if (!$showPosts) {
            continue;
          }
          $date=preg_replace("/-/", '/', $post2->date);
          $name=$post2->post_name;
          $title_text = htmlspecialchars(strip_tags(__($post2->post_title),
          'collapsing-categories'), 
              ENT_QUOTES);
          $tmp_text = '';
          if ($postTitleLength> 0 && strlen($title_text) > $postTitleLength ) {
            $tmp_text = substr($title_text, 0, $postTitleLength );
              $tmp_text .= ' &hellip;';
          }
          $linktext = $tmp_text == '' ? $title_text : $tmp_text;
          if ($showPostDate) {
            $theDate = mysql2date($postDateFormat, $post2->post_date );
            if ($postDateAppend=='before') {
              $linktext = "$theDate $linktext";
            } else {
              $linktext = "$linktext $theDate";
            }
          }
          $posttext2.= "<li class='collapsCatPost'><a $self
              href='".get_permalink($post2).
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
  $subCatCount,$subCatPostCount,$expanded, $depth) {
  global $options,$expandSym, $collapseSym, $expandSymJS, $collapseSymJS,
      $autoExpand, $postsToExclude, $subCatPostCounts, $catlink, $postsInCat,
      $advanced;
  $subCatLinks='';
  extract($options);
  extract($advanced);
  $subCatPosts=array();
  $link2='';
  if ($depth==0) {
    $subCatPostCounts=array();
  }
  $depth++;
  if (in_array($cat->term_id, $parents)) {
    foreach ($categories as $cat2) {
      $subCatLink2=''; // clear info from subCatLink2
      if ($cat->term_id==$cat2->parent) {
        list($subCatPostCount2, $posttext2) = 
            getSubPosts($postsInCat[$cat2->term_id],$cat2, $subCatPosts, $showPosts);
        $subCatPostCount+=$subCatPostCount2;
        $subCatPostCounts[$depth]=$subCatPostCount2;
        $expanded='none';
        $theID='collapsCat-' . $cat2->term_id . "-$number";
        if (in_array($cat2->name, $autoExpand) ||
            in_array($cat2->slug, $autoExpand) ||
            ($useCookies && $_COOKIE[$theID]==1)) {
          $expanded='block';
        }
        if (!in_array($cat2->term_id, $parents)) {
					// check to see if there are more subcategories under this one
          $subCatCount=0;
          if ($subCatPostCount2<1) {
            continue;
          }
          if ($showPosts) {
            if ($expanded=='block') {
              $showHide='collapse';
              $symbol=$collapseSym;
            } else {
              $showHide='expand';
              $symbol=$expandSym;
            }
            $subCatLinks.=( "<li class='collapsCat'>".
                "<span class='collapsCat $showHide' ".
                "onclick='expandCollapse(event, \"$expandSymJS\",".
                "\"$collapseSymJS\", $animate, \"collapsCat\"); return false'>" . 
                "<span class='sym'>$symbol</span>" );
          } else {
            $subCatLinks.=( "<li class='collapsCatPost'>" );
          }
          $link2= getCollapsCatLink($cat2,$catlink,$self);
          if ( empty($cat2->description) ) {
            $link2 .= 'title="'. 
                sprintf(__("View all posts filed under %s",
                'collapsing-categories'), 
                wp_specialchars(apply_filters('single_cat_title',$cat2->name))) . '"';
          } else {
            $link2 .= 'title="' . 
                wp_specialchars(apply_filters('description', 
                $cat2->description,$cat2)) . '"';
          }
          $link2 .= '>';
          if ($linkToCat=='yes') {
            if ($showPosts) {
              $subCatLinks.='</span>';
            }
            $link2 .= apply_filters('single_cat_title', $cat2->name).
                '</a>';
          } else {
            $link2 .= apply_filters('single_cat_title', $cat2->name).  '</a>';
            if ($showPosts) {
              $link2 = apply_filters('single_cat_title', $cat2->name).
                  "</span>";
            }
          }
        } else {
          list ($subCatLink2, $subCatCount,$subCatPostCount,$subCatPosts)= 
              get_sub_cat($cat2, $categories, $parents, $posts, $subCatCount,
              $subCatPostCount,$expanded, $depth);
          $subCatCount=1;
          list($subCatPostCount2, $posttext2) = 
              getSubPosts($postsInCat[$cat2->term_id],$cat2, $subCatPosts, $showPosts);
          if ($subCatPostCount2<1) {
            continue;
          }
          if ($expanded=='block') {
            $showHide='collapse';
            $symbol=$collapseSym;
          } else {
            $showHide='expand';
            $symbol=$expandSym;
          }
          $subCatLinks.=( "<li class='collapsCat'>".
              "<span class='collapsCat $showHide' ".
              "onclick='expandCollapse(event, \"$expandSymJS\",".
              "\"$collapseSymJS\", $animate, \"collapsCat\"); return false'>" . 
              "<span class='sym'>$symbol</span>" );
          $link2=getCollapsCatLink($cat,$catlink,$self);
          if ( empty($cat2->description) ) {
            $link2 .= 'title="'. 
                sprintf(__("View all posts filed under %s"), 
                wp_specialchars(apply_filters('single_cat_title',$cat2->name))) . '"';
          } else {
            $link2 .= 'title="' . 
                wp_specialchars(apply_filters('description', 
                $cat2->description,$cat2)) . '"';
          }
          $link2 .= '>';
          if ($linkToCat=='yes') {
            $subCatLinks.='</span>';
            $link2 .= apply_filters('single_cat_title', $cat2->name).'</a>';
          } else {
            if ($showPosts || $subCatPostCount2>0) {
              $link2 = apply_filters('single_cat_title',$cat2->name) . '</span>';
            } else {
              // don't include the triangles if posts are not shown and there
              // are no more subcategories
                $link2 .= apply_filters('single_cat_title',$cat2->name).'</a>';
                $subCatLinks = "      <li class='collapsCatPost'>";
            }
          }
        }
        if( $showPostCount=='yes') {
          $theCount=$subCatPostCount2 + array_sum(array_slice($subCatPostCounts, $depth));
          $link2 .= ' ('.$theCount.')';
        }
        $subCatLinks.= $link2 ;
        $rssLink=addFeedLink($catfeed,$cat2);
        $subCatLinks.=$rssLink;
        if (($subCatCount>0) || ($showPosts)) {
          $subCatLinks.="\n<ul id='$theID' style=\"display:$expanded\">\n";
          if ($subCatCount>0 && $posttext2!='' && $addMisc) {
            $subCatLinks.=miscPosts($cat2,$catlink,$subCatPostCount2,$posttext2);
          } else {
            $subCatLinks.=$posttext2;
          }
        }
        // add in additional subcategory information
        $subCatLinks.="$subCatLink2";
        // close <ul> and <li> before starting a new category
        if (($subCatCount>0) || ($showPosts)) {
          $subCatLinks.= "          </ul>\n";
        }
        $subCatLinks.= "         </li> <!-- ending subcategory -->\n";
      }
    }
  }
  return array($subCatLinks,$subCatCount,$subCatPostCount,$subCatPosts);
}

function list_categories($args='') {
  global $expandSym,$collapseSym,$expandSymJS, $collapseSymJS, 
      $wpdb,$options,$post, $autoExpand, $postsToExclude, 
      $thisCat, $thisPost, $wp_rewrite, $catlink, $postsInCat, $advanced;
  include('defaults.php');
  $options=wp_parse_args($args, $defaults);
  extract($options);
  extract($advanced);
  $catlink = $wp_rewrite->get_category_permastruct();
  if (is_single() || is_category() || is_tag()) {
    $cur_category = get_the_category();
    $thisCat = $cur_category[0]->term_id;
    $thisPost = $post->ID;
  }
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
  } elseif ($expand==4) {
    $expandSym=$customExpand;
    $collapseSym=$customCollapse;
  } else {
    $expandSym='►';
    $collapseSym='▼';
  }
  if ($expand==3) {
    $expandSymJS='expandImg';
    $collapseSymJS='collapseImg';
  } else {
    $expandSymJS=$expandSym;
    $collapseSymJS=$collapseSym;
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
  if (!$showPages) {
    $isPage="AND p.post_type='post'";
  }
  if ($catSort!='') {
    if ($catSort=='catName') {
      $catSortColumn="ORDER BY t.name";
    } elseif ($catSort=='catId') {
      $catSortColumn="ORDER BY t.term_id";
    } elseif ($catSort=='catSlug') {
      $catSortColumn="ORDER BY t.slug";
    } elseif ($catSort=='catOrder') {
      $catSortColumn="ORDER BY t.term_order";
    } elseif ($catSort=='catCount') {
      $catSortColumn="ORDER BY tt.count";
    }
  } 
  if ($postSort!='') {
    if ($postSort=='postDate') {
      $postSortColumn="ORDER BY p.post_date";
    } elseif ($postSort=='postId') {
      $postSortColumn="ORDER BY p.id";
    } elseif ($postSort=='postTitle') {
      $postSortColumn="ORDER BY p.post_title";
    } elseif ($postSort=='postComment') {
      $postSortColumn="ORDER BY p.comment_count";
    }
  } 
	if ($defaultExpand!='') {
		$autoExpand = preg_split('/,\s*/',$defaultExpand);
  } else {
	  $autoExpand = array();
  }

	if ($catTag == 'tag') {
	  $catTagQuery= "'post_tag'";
	} elseif ($catTag == 'both') {
	  $catTagQuery= "'category','post_tag'";
	} else {
	  $catTagQuery= "'category'";
	}
	if ($olderThan > 0) {
		$now = date('U');
		$olderThanQuery= "AND  date(post_date) > '" . 
			date('Y-m-d', $now-date('U',$olderThan*60*60*24)) . "'";
	}

  echo "\n    <ul class='collapsCatList'>\n";

$catquery = "SELECT t.*, tt.* FROM $wpdb->terms AS t INNER JOIN
$wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy IN
($catTagQuery) $inExcludeQuery AND t.slug!='blogroll' $catSortColumn $catSortOrder ";
//$catquery = "SELECT t.*, tt.* FROM wp_terms AS t INNER JOIN wp_term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy IN ('category')  ORDER BY t.name ASC  ";
  if ($showPosts) {
    $postsInCat=array();
    $postquery= "select ID, slug, date(post_date) as date, post_status,
         post_date, post_title, post_name, name, object_id,
         t.term_id from $wpdb->term_relationships AS tr, $wpdb->posts AS p,
         $wpdb->terms AS t, $wpdb->term_taxonomy AS tt
         WHERE tt.term_id = t.term_id 
         AND object_id=ID 
         $olderThanQuery
         AND post_status='publish'
         AND tr.term_taxonomy_id = tt.term_taxonomy_id 
         AND tt.taxonomy IN ($catTagQuery) $isPage $postSortColumn $postSortOrder";
    $posts= $wpdb->get_results($postquery); 
    foreach ($posts as $post) {
      if (!$postsInCat[$post->term_id]) {
        $postsInCat[$post->term_id]=array();
      }
      array_push($postsInCat[$post->term_id], $post);
    }
  }
  $categories = $wpdb->get_results($catquery);
  $totalPostCount=count($posts);
  if ($totalPostCount>5000) {
    $options['showPosts']=false;
    $showPosts=false;
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
    print_r($options);
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
      $subCatPostCounts=array();
      list ($subCatLinks, $subCatCount,$subCatPostCount, $subCatPosts)=
          get_sub_cat($cat, $categories, $parents, $posts, 
          $subCatCount,$subCatPostCount,$expanded,0);
        list($subCatPostCount2, $posttext2) = 
            getSubPosts($postsInCat[$cat->term_id],$cat, $subCatPosts, $showPosts);
        
      $theCount=$subCatPostCount2+$subCatPostCount;
      if ($theCount>0) {
        $expanded='none';
        $theID='collapsCat-' . $cat->term_id . "-$number";
        if (in_array($cat->name, $autoExpand) ||
            in_array($cat->slug, $autoExpand) ||
            ($useCookies && $_COOKIE[$theID]==1)) {
          $expanded='block';
        }
        if ($showPosts || $subCatPostCount>0) {
          if ($expanded=='block') {
            $showHide='collapse';
            $symbol=$collapseSym;
          } else {
            $showHide='expand';
            $symbol=$expandSym;
          }
          $span= "      <li class='collapsCat'>".
              "<span class='collapsCat $showHide' ".
              "onclick='expandCollapse(event, \"$expandSymJS\"," .
              "\"$collapseSymJS\", $animate, \"collapsCat\"); return false'>".
              "<span class='sym'>$symbol</span>";
        } else {
          $span = "      <li class='collapsCatPost'>";
        }
        $link=getCollapsCatLink($cat,$catlink,$self);
        if ( empty($cat->description) ) {
          $link .= 'title="'. 
              sprintf(__("View all posts filed under %s",
              'collapsing-categories'),
              wp_specialchars(apply_filters('single_cat_title',$cat->name))) . '"';
        } else {
          $link .= 'title="' . wp_specialchars(apply_filters('description',$cat->description,$cat)) . '"';
        }
        $link .= '>';
        if ($linkToCat=='yes') {
          $link .= apply_filters('single_cat_title', $cat->name).'</a>';
          if ($showPosts || $subCatPostCount>0) {
            $span.='</span>';
          }
        } else {
          if ($showPosts || $subCatPostCount>0) {
            $link = apply_filters('single_cat_title',$cat->name) . '</span>';
          } else {
            // don't include the triangles if posts are not shown and there
            // are no more subcategories
              $link .= apply_filters('single_cat_title',$cat->name).'</a>';
              $span = "      <li class='collapsCatPost'>";
          }
        }
        // Now print out the post info
				$posttext='';
        if( ! empty($postsInCat[$cat->term_id]) ) {
            foreach ($postsInCat[$cat->term_id] as $post) {
              if ($post->term_id == $cat->term_id 
                  && (!in_array($post->ID, $subCatPosts))) {
								if (!in_array($post->ID, $postsToExclude)) {
								  $subCatPostCount++;
                  if (!$showPosts) {
                    continue;
                  }
                  if (is_single() && $post->ID == $thisPost)
                    $self="class='self'";
                  else
                    $self="";
									$date=preg_replace("/-/", '/', $post->date);
									$name=$post->post_name;
									$title_text = htmlspecialchars(strip_tags(
									    __($post->post_title), 'collapsing-categories'), ENT_QUOTES);
									$tmp_text = '';
									if ($postTitleLength> 0 && 
									    strlen($title_text) > $postTitleLength ) {
										$tmp_text = substr($title_text, 0, $postTitleLength );
											$tmp_text .= ' &hellip;';
									}
									$linktext = $tmp_text == '' ? $title_text : $tmp_text;
                  if ($showPostDate) {
                    $theDate = mysql2date($postDateFormat, $post->post_date );
                    if ($postDateAppend=='before') {
                      $linktext = "$theDate $linktext";
                    } else {
                      $linktext = "$linktext $theDate";
                    }
                  }
									$posttext.= "<li class='collapsCatPost'><a $self
										href='".get_permalink($post).
										"' title='$title_text'>$linktext</a></li>\n";
								} 
              }
            // close <ul> and <li> before starting a new category
          } 
        }
        if( $showPostCount=='yes') {
          $link .= ' (' . $theCount.')';
        }
        $link.=$rssLink;
				if ($theCount<1) {
					$link='';
					$span='';
				}
          print($span . $link );
        if (($subCatPostCount>0) || ($showPosts)) {
          print( "\n     <ul id='$theID' style=\"display:$expanded\">\n" );
        }
        echo $subCatLinks;
				if ($showPosts) {
          if ($subCatPostCount>0 && $subCatLinks!='' && $addMisc) {
            print(miscPosts($cat,$catlink,$subCatPostCount2,$posttext));
          } else {
            print($posttext);
          }
				}
				if ($subCatPostCount>0 || $showPosts) {
					echo "        </ul>\n";
				}
				echo "      </li> <!-- ending category -->\n";
      } // end if theCount>0
    }
  }
  echo "    </ul> <!-- ending collapsCat -->\n";
}
$url = get_settings('siteurl');
echo "<script type=\"text/javascript\">\n";
echo "// <![CDATA[\n";
echo '/* These variables are part of the Collapsing Categories Plugin 
      *  Version: 1.0.1
      *  $Id: collapscat.php 107679 2009-04-04 14:51:22Z robfelty $
      * Copyright 2007 Robert Felty (robfelty.com)
      */' . "\n";
$expandSym="<img src='". $url .
     "/wp-content/plugins/collapsing-categories/" . 
     "img/expand.gif' alt='expand' />";
$collapseSym="<img src='". $url .
     "/wp-content/plugins/collapsing-categories/" . 
     "img/collapse.gif' alt='collapse' />";
echo "var expandSym=\"$expandSym\";\n";
echo "var collapseSym=\"$collapseSym\";\n";
if ($useCookies) {
  echo"
  collapsAddLoadEvent(function() {
    autoExpandCollapse('collapsCat');
  });
  ";
}
echo "// ]]>\n</script>\n";
?>
