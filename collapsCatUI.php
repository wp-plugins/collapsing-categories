<?php
/*

Collapsing Categories version: 0.4.4
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

check_admin_referer();

if( isset($_POST['infoUpdate']) ) {
  if( isset($_POST['showPostCount']) ) {
    update_option( 'collapsCatShowPostCount', 'yes' );
  }
  else {
    update_option( 'collapsCatShowPostCount', 'no' );
  }
  if( isset($_POST['showPages']) ) {
    update_option( 'collapsCatShowPages', 'yes' );
  }
  else {
    update_option( 'collapsCatShowPages', 'no' );
  }
  if($_POST['archives'] == 'root') {
    update_option( 'collapsCatLinkToArchives', 'root' );
  } elseif ($_POST['archives'] == 'archives') {
    update_option( 'collapsCatLinkToArchives', 'archives' );
  } elseif ($_POST['archives'] == 'index') {
    update_option( 'collapsCatLinkToArchives', 'index' );
  }
  if($_POST['sortOrder'] == 'ASC') {
    update_option( 'collapsCatSortOrder', 'ASC' );
  } elseif ($_POST['sortOrder'] == 'DESC') {
    update_option( 'collapsCatSortOrder', 'DESC' );
  }
  if($_POST['sort'] == 'catName') {
    update_option( 'collapsCatSort', 'catName' );
  } elseif ($_POST['sort'] == 'catId') {
    update_option( 'collapsCatSort', 'catId' );
  } elseif ($_POST['sort'] == '') {
    update_option( 'collapsCatSort', '' );
    update_option( 'collapsCatSortOrder', '' );
  }
}
?>
<div class=wrap>
 <form method="post">
  <h2>Collapsing Categories Options</h2>
  <fieldset name="Collapsing Categories Options">
   <legend><?php _e('Display Options:'); ?></legend>
   <ul style="list-style-type: none;">
    <li>
     <input type="checkbox" name="showPostCount" <?php if(get_option('collapsCatShowPostCount')=='yes') echo 'checked'; ?> id="showPostCount"></input> <label for="showPostCount">Show Post Count in Category Links</label>
    </li>
    <li>
     <input type="checkbox" name="showPages" <?php if(get_option('collapsCatShowPages')=='yes') echo 'checked'; ?> id="showPages"></input> <label for="showPages">Show Pages as well as posts</label>
    </li>
    <li>
     <input type="radio" name="archives" <?php if(get_option('collapsCatLinkToArchives')=='root') echo 'checked'; ?> id="archivesRoot" value='root'></input> <label for="archivesRoot">Links based on site root (default)</label>
     <input type="radio" name="archives" <?php if(get_option('collapsCatLinkToArchives')=='index') echo 'checked'; ?> id="archivesIndex" value='index'></input> <label for="archivesIndex">Links based on index.php </label>
     <input type="radio" name="archives" <?php if(get_option('collapsCatLinkToArchives')=='archives') echo 'checked'; ?> id="archivesArchives" value='archives'></input> <label for="archivesArchives">Links based on archives.php</label>
    </li>
    <li>
     <input type="radio" name="sort" <?php if(get_option('collapsCatSort')=='catName') echo 'checked'; ?> id="sortCatName" value='catName'></input> <label for="sortCatName">Sort by category name</label>
     <input type="radio" name="sort" <?php if(get_option('collapsCatSort')=='catId') echo 'checked'; ?> id="sortCatId" value='catId'></input> <label for="sortCatId">Sort by category id</label>
    </li>
    <li>
     <input type="radio" name="sortOrder" <?php if(get_option('collapsCatSortOrder')=='ASC') echo 'checked'; ?> id="sortASC" value='ASC'></input> <label for="sortASC">Sort in ascending order</label>
     <input type="radio" name="sortOrder" <?php if(get_option('collapsCatSortOrder')=='DESC') echo 'checked'; ?> id="sortDESC" value='DESC'></input> <label for="sortDESC">Sort in descending order</label>
    </li>
   </ul>
  </fieldset>
  <div class="submit">
   <input type="submit" name="infoUpdate" value="<?php _e('Update options', 'Collapsing Categories'); ?> &raquo;" />
  </div>
 </form>
</div>
