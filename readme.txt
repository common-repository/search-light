=== Search Light ===
Contributors: Daniel Kowalski, Sai Liu
Tags: search, light, ajax, find, dropdown
Requires at least: 2.8
Tested up to: 3.0.4
Stable tag: 1.4.2

Find-As-You-Type dropdown extension for the standard WordPress search

== Description ==

Search Light adds a nice Find-As-You-Type Ajax dropdown-interface for the standard WordPress search.
Just install the plugin and start typing into the default WordPress search input field.
You will get instant results ordered by articles and pages in an elegant and easy to use way.

The following settings can be changed in the settings dialog:

* Dropdown-theme
* Number of search results shown within the dropdown
* Display of Post-Thumbnail Images
* Fine-tune the position of the dropdown
* Change headline titles shown in the search result dropdown
* Change search-form ID-tag for easy integration into custom themes

== Installation ==

For installation, just move the plugin-folder into wp-content/plugins and activate the plugin within WordPress.
Select a theme that fits your page-design.
Fine-tune the position of the dropdown for your theme within the settings dialog.

== Frequently Asked Questions ==

= I installed the plugin, but the dropdown is not showing up when typing into the search field =

Your theme might not use the default WordPress searchform or input-field ID.
To get the correct ID's for your theme, just copy them from searchform.php within your themes folder.

= How do I build my own theme? =

Just copy one of the original theme-folders that come with Search Light, then change the graphics and corresponding styles in this new folder until the design fits your site.

Don't forget to change the name of your new theme in the style sheet header, since this is the name you later select in the settings dialog.

== Screenshots ==

1. Several themes to choose from
2. The settings-dialog
3. Tag posts and pages for Search Light to move them up or hide them in the result list

== Changelog ==

= 1.4.2 =
* Minor bugfix: Fixed minor problem where translated dropdown content briefly showed up in german language

= 1.4.1 =
* Added option to hide specific posts or pages from Search Light result-dropdown

= 1.4 =
* Better control over search-result listing

= 1.3 =
* Support for WordPress 2.9 Post-Thumbnail Images
* New CSS3 theme "Lime" by Linus Metzler
* Fixed a problem displaying certain html special characters in dropdown titles
* Minor CSS fixes and enhancements

= 1.2 =
* Choose between 3 included themes
* Built your own themes and activate them within the settings-dialog
* Fixed a bug when running WordPress not within the root of a domain
* Some minor css changes

= 1.1.1 =
* Fixed file-path reference within plugin

= 1.1 =
* simplified plugin installation

== Upgrade Notice ==

== Arbitrary section ==

1.4 Update:

To improve the relevance of search results, Search Light now moves posts that are tagged as "sticky" to the top of the result list.
Since there's no sticky-attribue for pages, we added a small panel in the write / edit page dialog where you can tag important posts and pages
for Search Light to move them up in the result list.

The sorting now works like this:

For posts:

1. Show all posts with the option "Tagged for Search Light" AND "sticky" (sorted by date)
2. Show all posts with the option "Tagged for Search Light" (sorted by date)
3. Show all posts with the option "sticky" (sorted by date)
4. Show all remaining posts matching the search criteria (sorted by date)

For pages:

1. Show all pages with the option "Tagged for Search Light" (sorted by date)
2. Show all remaining pages matching the search criteria (sorted by date)

1.3 Update:

Please note that in order to use the new Post-Thumbnail Images feature, you have to use at least WordPress 2.9 or later.
You also have to activate this feature for your Theme.

For information about how to activate and use Post Thumbnail Images in WordPress, take a look [here](http://markjaquith.wordpress.com/2009/12/23/new-in-wordpress-2-9-post-thumbnail-images/ "New in WordPress 2.9: Post Thumbnail Images").