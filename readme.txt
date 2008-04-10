=== Collapsing Categories ===
Contributors: robfelty
Donate link: http://blog.robfelty.com/plugins
Plugin URI: http://blog.robfelty.com/plugins
Tags: categories, sidebar, widget
Requires at least: 2.3
Tested up to: 2.5
Stable tag: 0.5.1

This plugin uses Javascript to dynamically expand or collapsable the set of
posts for each category.

== Description ==

This is a very simple plugin that uses Javascript to form a collapsable set of
links in the sidebar for the categories. Every post corresponding to a given
category will be expanded.

It is largely based off of the Fancy Categories Plugin by Andrew Rader

== Installation ==

MANUAL INSTALLATION
(the only option for pre 2.3 wordpress, unless you have the widget plugin installed)

Unpackage contents to wp-content/plugins/ so that the files are in
a collapsCat directory. Now enable the plugin. To use the plugin,
change the following where appropriate	(most likely sidebar.php):

	<ul>
	 `<?php wp_list_cats(...); ?>`
	</ul>

To something of the following:
`
	<?php
	  if( function_exists('collapsCat') ) {
	  collapsCat();
	} else {
	  echo "<ul>\n";
	  wp_list_cats(...);
	  echo "</ul>\n";
	}
	?>
`

The above will fall back to the WP function for categories if you disable
the plugin.

WIDGET INSTALLATION

For those who have widget capabilities, (default in Wordpress 2.3+), installation is easier. 

Unpackage contents to wp-content/plugins/ so that the files are in a collapsCat
directory. There should be 2 new plugins in your Wordpress Admin interface --
Collapsing Categories, and Collapsing Categories Widget. You must enable both
of them, in that order. Then simply go the Presentation > Widgets section and
drag over the Collapsing Categories Widget.

== Frequently Asked Questions ==

None yet.

== Screenshots ==

1. a few expanded categories with default theme, showing nested categories
2. available options 

== Demo ==

I use this plugin in my blog at http://blog.robfelty.com


== OPTIONS AND CONFIGURATIONS ==

  * Show post counts in Category links
  * Show pages as well as posts
  * Links point to root, index.php or archives.php
  * Sort by category name or category id
  * Sort in ascending or descending order

== CAVEAT ==

Currently this plugin relies on Javascript to expand and collapse the links.
If a user's browser doesn't support javascript they won't see the links to the
posts, but the links to the categories will still work (which is the default
behavior in wordpress anyways)

== HISTORY ==

* 0.4.4
    * using unicode number codes in css stylesheet
    * fixed bug with duplicate entries in subcategories

* 0.4.3
    * nicer list indenting
    * re-fixed permalink bug introduced sometime after version 0.3.5

* 0.4.2
    * fixed bug with extraneous <ul>

* 0.4.1
		* fixed bug with get_sub_cat definition problem in WP 2.5. Looks like it
		  had something to do with nested functions maybe

* 0.4
    * Verified to work with wordpress 2.5
    * Now has custom styling option through the collapsCat.css stylesheet
    * updated screenshots
    * moved javascript into collapsArch.php and got rid of separate file

* 0.3.7
    * strips html tags from post titles now

* 0.3.6
    * Fixed bug introduced in version 0.3.5 where all links in a category
      pointed to the same post

* 0.3.5
    * Now links should work with all sorts of permalink structures. Thanks to
      Krysthora http://krysthora.free.fr/ for finding this bug

* 0.3.4
    * Added option to sort categories by id or name

* 0.3.3
    * fixed bug in headers when collapsArch is not loaded
    * fixed a few minor markup issues to make it valid xhtml

* 0.3.2
    * posts now have the class "collapsCatPost" and can be styled with CSS.
      Some styling has been added in collapsCat.php
    * removed list icons in front of triangles

* 0.3.1
    * Added option to make post links to index.php, root, or archive.php, like
      collapsing-archives
    * Fixed link to category listings

* 0.3
    * Now uses only 2 database queries instead of 1 + 2*(count(categories))
    * Now supports infinite levels of subcategories

* 0.2.2: 
    * Added option to show pages in list or not

* 0.2.1: 
    * Added collapsing class to <li>s with triangles for CSS styling
    * Added style information to make triangles bigger and give a pointer
      cursor over them
    * Added title tags to triangles to indicate functionality
		* Checking whether some of the same functionality from collapsing-archives
		  has already been loaded (for example the javascript file) in order to
		  avoid redundancy

* 0.2: 
    * Changed name from Fancy Archives to Collapsing Archives
    * Changed author from Andrew Rader to Robert Felty
    * Added triangles which mark the collapsing and expanding features
      That is, clicking on the triangle collapses or expands, while clicking
      on a month or year links to the archives for the said category.
      This uses html entities (dings) instead of images, for a variety of 
      reasons
    * Lists the titles of posts, instead of just listing subcategories
    * Removed the rel='hide' and rel='show' tags, because they are not xhtml
      1.0 compliant. Now uses the CSS classes instead
		* MOST IMPORTANTLY -- is compatible with both the pre 2.3 database which
		  uses categories, and the 2.3+ database structure which uses the tag
		  taxonomy

---------------------------------------------------------------------------

Fancy Categories Changelog

0.1:
	Initial Release
