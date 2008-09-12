=== Collapsing Categories ===
Contributors: robfelty
Donate link: http://blog.robfelty.com/plugins
Plugin URI: http://blog.robfelty.com/plugins
Tags: categories, sidebar, widget
Requires at least: 2.6
Tested up to: 2.6.1
Stable tag: 0.6.2

This plugin uses Javascript to dynamically expand or collapsable the set of
posts for each category.

== Description ==

This is a very simple plugin that uses Javascript to form a collapsable set of
links in the sidebar for the categories. Every post corresponding to a given
category will be expanded.

It is largely based off of the Fancy Categories Plugin by Andrew Rader

== Installation ==

Unpackage contents to wp-content/plugins/ so that the files are in a
collapsing-categories directory. Activate the plugin, then simply go the
Presentation > Widgets section and drag over the Collapsing Categories Widget.

== Frequently Asked Questions ==

=  How do I change the style of the collapsing categories lists? =

  The collapsing categories plugin uses several ids and classes which can be
  styled with CSS. I have provided a few defaults in the collapsCat.css file.
  You can modify the collapsCat.css file to your liking. Alternatively, you
  can copy those rules into your themes style file. You may have to rename
  some of the id statements. For example, if your sidebar is called
  "myawesomesidebar", you would rewrite the line 

  #sidebar li.collapsCat {list-style-type:none}

  to

  #myawesomesidebar li.collapsCat {list-style-type:none}

= The stylesheet doesn't seem to be having any effect? =
 
  Check this url in your browser:
  http://yourblogaddress/wp-content/plugins/collapsing-categories/collapsCat.css
  If you don't see a plaintext file with css style rules, there may be
  something wrong with your .htaccess file (mod_rewrite). If you don't know
  how to fix this, you can copy the style rules there into your themes style
  file.
  
= I don't like the fixed width fonts for the category names =

I decided to use a fixed-width font for the expanding and collapsing
characters, because otherwise the spacing can change a bit (especially between
+/-). The problem is, if you enable the option to have the category name
trigger expanding and collapsing instead of linking to the category, then you
get ugly fixed-width text as well. If you don't mind the fact that that the
(+/-) have slightly different widths, then you can remove the line in the css
file that specifies the fixed with font

= How do I use different symbols for collapsing and expanding? =

If you want to use images, you can upload your own images to
http://yourblogaddress/wp-content/plugins/collapsing-categories/img/collapse.gif
and expand.gif

If you want to use text (recommended), you can change a few lines in
collapsCat.php and collapsCatList.php (search for expandSym), or suggest some
other characters, and I might include them as an option.

= I have selected a category to expand by default, but it doesn't seem to work =

If you select a sub-category to expand by default, but not the parent
category, you will not see the sub-category expanded until you expand the
parent category.  You probably want to add both the parent and the
sub-category into the expand by default list.

= I can't get including or excluding to work = 

Make sure you specify category names, not ids.

= How can I eliminate the line spacing between categories? =
Add a line to the collapscat.css file in the .sym class, like so:

.sym {font-family:monospace;
      font-size:1.5em;
      line-height:1.5em;
      padding-right:5px;}

= I upgraded from 0.5.? to 0.6.? and nothing works! =

Try disabling, then re-enabling the plugin

== Screenshots ==

1. a few expanded categories with default theme, showing nested categories
2. available options 

== Demo ==

I use this plugin in my blog at http://blog.robfelty.com


== OPTIONS AND CONFIGURATIONS ==

  * Show post counts in Category links
  * Show pages as well as posts
  * Links point to root, index.php or categories.php
  * Sort by category name or category id
  * Sort in ascending or descending order

== CAVEAT ==

Currently this plugin relies on Javascript to expand and collapse the links.
If a user's browser doesn't support javascript they won't see the links to the
posts, but the links to the categories will still work (which is the default
behavior in wordpress anyways)

== HISTORY ==

* 0.6.2 (2008/09/11)
    * Fixed display of expand and collapse symbols when using images
    * Improved font handling and styling of text symbols

* 0.6.1 (2008/09/01)
    * Improved styling so that collapsing and expanding symbols use a
      fixed-width font, but category names do not
    * When using the option to have category names trigger expansion, and not
      showing posts, categories with no subcategories now link to the category
    * Added option to use images instead of html for collapse/expand characters
    * +/- now uses UTF-8 encoding instead of html entities (may not work for
      pages not encoded in UTF-8
    
* 0.6 (2008/08/27)
    * Can have multiple instances of widgets, each with separate options
    * No longer works as non-widget
    * All options are stored in one database row
    * Added more sorting options
    * Added option to include or exclude certain categories
    * Added option to expand certain categories by default
    * Added option to have category names either link to category archive or to
      activate expanding and collapsing

* 0.5.10 (2008/08/20)
    * minor bug fix. Fixed option to optionally show pages

* 0.5.9 (2008/08/07)
    * minor bug fix - added space before category count
    * Added option to sort by category (term) order
    * Added option to sort by category (term) count (note that it sorts by the
      count of the parent category, so categories with many subcategories, but
      not many posts themselves will be out of order
    * Added option to sort posts within categories

* 0.5.8 (2008/06/15)
		* bug fix - category description now correctly appears in title attribute
		  if there is a description for a given category
    * implemented a few more changes to work towards internationalization

* 0.5.7 (2008/05/23)
    * fixed misnamed class in javascript (collapsArch -> collapsCat)
    * added font-family definition to css to make it monospace for +/- 
    * added another option with brackets around the +/-

* 0.5.6 (2008/05/23)
    * fixed bug such that subcategories would not display the expand and
      collapse icons
    * fixed bug that categories with subcategories that have posts, but do not
      have posts themselves will be displayed
    * Thanks to [Andy] (http://www.onkelandy.com/blog) for both of these bug
      notices

* 0.5.5 (2008/05/19)
    * fixed bug - html now validates when not displaying posts
    * new option - choose between arrows or +- for expanding and collapsing
    * tweaked exclude option to function better with collapsing categories

* 0.5.4
    * fixed bug - was using hard-coded wp_ prefix in one SQL query. 
      Now using $wpdb-> instead

* 0.5.3
    * count is now correct for all subcategories

* 0.5.2
    * Added option to exclude certain categories
    * Added option to sort categories by slug

* 0.5.1
    * options in widget seem to work now
    * removed duplicate entries due to tag + category

* 0.5
    * Added option to not show posts
    * Added option to change title in widget
    * Now is condensed into one plugin

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
    * moved javascript into collapsCat.php and got rid of separate file

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
    * fixed bug in headers when collapsCat is not loaded
    * fixed a few minor markup issues to make it valid xhtml

* 0.3.2
    * posts now have the class "collapsCatPost" and can be styled with CSS.
      Some styling has been added in collapsCat.php
    * removed list icons in front of triangles

* 0.3.1
    * Added option to make post links to index.php, root, or archive.php, like
      collapsing-categories
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
		* Checking whether some of the same functionality from collapsing-categories
		  has already been loaded (for example the javascript file) in order to
		  avoid redundancy

* 0.2: 
    * Changed name from Fancy categories to Collapsing categories
    * Changed author from Andrew Rader to Robert Felty
    * Added triangles which mark the collapsing and expanding features
      That is, clicking on the triangle collapses or expands, while clicking
      on a category links to the category list for the said category.
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
