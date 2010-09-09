<?php
/*
Collapsing Categories version: 2.0
Copyright 2007-2010 Robert Felty

    Collapsing Categories is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    Collapsing Categories is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Collapsing Categories; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

check_admin_referer();

$options=get_option('collapsCatOptions');
$widgetOn=0;
$number='%i%';
if (empty($options)) {
  $number = '-1';
} elseif (!isset($options['%i%']['title']) || 
    count($options) > 1) {
  $widgetOn=1; 
}

if( isset($_POST['resetOptions']) ) {
  if (isset($_POST['reset'])) {
    delete_option('collapsCatOptions');   
		$widgetOn=0;
    $number = '-1';
  }
} elseif (isset($_POST['infoUpdate'])) {
  $style=$_POST['collapsCatStyle'];
  $defaultStyles=get_option('collapsCatDefaultStyles');
  $selectedStyle=$_POST['collapsCatSelectedStyle'];
  $defaultStyles['selected']=$selectedStyle;
  $defaultStyles['custom']=$_POST['collapsCatStyle'];

  update_option('collapsCatInFooter', $_POST['collapsCatInFooter']);

  if ($widgetOn==0) {
    include('updateOptions.php');
  }
}
include('processOptions.php');
?>
<div class=wrap>
 <form method="post">
  <h2><? _e('Collapsing Categories Options', 'collapsing-categories'); ?></h2>
  <fieldset name="Collapsing Categories Options">
   <p>
   <input type="checkbox" name="collapsCatInFooter" id ="collapsCatInFooter"
   <?php if (get_option('collapsCatInFooter')) echo
   'checked'; ?> id="collapsCatInFooter"></input> 
<label
   for="collapsCatInFooter"><?php _e('Put javascript file in footer (speeds
   page load, but is not compatible with all themes', 'collapsing-categories'); ?></label>  
    </p>

<script type='text/javascript'>

function changeStyle(preview,template,select,selected,custom) {
  var preview = document.getElementById(preview);
  var pageStyles = document.getElementById(select);
  var selectedStyle;
  var hiddenStyle=document.getElementById(selected);
  var pageStyle = document.getElementById(template);
  if (custom==true) {
    selectedStyle=pageStyles.options[pageStyles.options.length-1];
    selectedStyle.value=pageStyle.value;
    selectedStyle.selected=true;
  } else {
    for(i=0; i<pageStyles.options.length; i++) {
      if (pageStyles.options[i].selected == true) {
        selectedStyle=pageStyles.options[i];
      }
    }
  }
  hiddenStyle.value=selectedStyle.innerHTML
  preview.src='<?php echo $url ?>/img/'+selectedStyle.innerHTML+'.png';
  var sidebarId=document.getElementById('collapsCatSidebarId').value;

  if (sidebarId!='') {
    var theStyle = selectedStyle.value.replace(/#[a-zA-Z]+\s/g, '#'+sidebarId + ' ');
  } else {
    var theStyle = selectedStyle.value.replace(/#[a-zA-Z]+\s/g, '');
  }
  pageStyle.value=theStyle
}

function restoreStyle() {
  var defaultStyle = document.getElementById('collapsCatCurrentStyle').value;
  var pageStyle = document.getElementById('collapsCatStyle');
  pageStyle.value=defaultStyle;
}
  changeStyle('collapsCatStylePreview','collapsCatStyle', 'collapsCatDefaultStyles', 'collapsCatSelectedStyle', false);

</script>
  </fieldset>
  <div class="submit">
   <input type="submit" name="infoUpdate" value="<?php _e('Update options', 'collapsing-categories'); ?> &raquo;" />
  </div>
 </form>
</div>
