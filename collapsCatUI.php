<?php
/*

Collapsing Categories version: 0.2.2
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
   </ul>
  </fieldset>
  <div class="submit">
   <input type="submit" name="infoUpdate" value="<?php _e('Update options', 'Collapsing Categories'); ?> &raquo;" />
  </div>
 </form>
</div>
