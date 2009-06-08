<?php
      $title=$new_instance['title'];
      if ($new_instance['linkToCat']=='yes') {
        $linkToCat=true;
      } else {
        $linkToCat=false;
      }
      if (isset($new_instance['showPostCount']) ) {
        $showPostCount= true ;
      } else {  
        $showPostCount= false ;
      }
      if (isset($new_instance['showPostDate']) ) {
        $showPostDate= true ;
      } else {  
        $showPostDate= false ;
      }
      $catSortOrder= 'ASC' ;
      if ($new_instance['catSortOrder'] == 'DESC') {
        $catSortOrder= 'DESC' ;
      }
      if ($new_instance['catSort'] == 'catName') {
        $catSort= 'catName' ;
      } elseif ($new_instance['catSort'] == 'catId') {
        $catSort= 'catId' ;
      } elseif ($new_instance['catSort'] == 'catSlug') {
        $catSort= 'catSlug' ;
      } elseif ($new_instance['catSort'] == 'catOrder') {
        $catSort= 'catOrder' ;
      } elseif ($new_instance['catSort'] == 'catCount') {
        $catSort= 'catCount' ;
      } elseif ($new_instance['catSort'] == '') {
        $catSort= '' ;
        $catSortOrder= '' ;
      }
      $postSortOrder= 'ASC' ;
      if ($new_instance['postSortOrder'] == 'DESC') {
        $postSortOrder= 'DESC' ;
      }
      if ($new_instance['postSort'] == 'postName') {
        $postSort= 'postName' ;
      } elseif ($new_instance['postSort'] == 'postId') {
        $postSort= 'postId' ;
      } elseif ($new_instance['postSort'] == 'postRating') {
        $postSort= 'postRating' ;
      } elseif ($new_instance['postSort'] == 'postUrl') {
        $postSort= 'postUrl' ;
      } elseif ($new_instance['postSort'] == '') {
        $postSort= '' ;
        $postSortOrder= '' ;
      }
      $expand= $new_instance['expand'];
      $customExpand= $new_instance['customExpand'];
      $customCollapse= $new_instance['customCollapse'];
      $catTag= $new_instance['catTag'];
      $inExclude= 'include' ;
      if($new_instance['inExclude'] == 'exclude') {
        $inExclude= 'exclude' ;
      }
      $postDateAppend= 'after' ;
      if($new_instance['postDateAppend'] == 'before') {
        $postDateAppend= 'before' ;
      }
      if( isset($new_instance['animate'])) {
        $animate= 1 ;
      } else {
        $animate=0;
      }
      $debug=false;
      if (isset($new_instance['debug'])) {
        $debug= true ;
      }
      $inExcludeCats=addslashes($new_instance['inExcludeCats']);
      $postDateFormat=addslashes($new_instance['postDateFormat']);
      $defaultExpand=addslashes($new_instance['defaultExpand']);
      if ($new_instance['showPosts']=='yes') {
        $showPosts= true ;
      } else {
        $showPosts=false;
      }
      $instance = compact(
          'title','showPostCount','catSort','catSortOrder','defaultExpand',
          'expand','inExclude','inExcludeCats','postSort','postSortOrder',
          'animate', 'debug', 'showPosts', 'customExpand', 'customCollapse',
          'catTag', 'linkToCat', 'showPostDate', 'postDateFormat',
          'postDateAppend');

?>
