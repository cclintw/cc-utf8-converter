=== CC UTF8 Converter ===
Contributors: chance
Tags: utf8, big5, encoding, text converter, file tools
Requires at least: 5.8
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Convert uploaded plain text files such as Big5, SJIS, and GB2312 to UTF-8 and download them directly.

== Description ==

CC UTF8 Converter is a lightweight WordPress plugin that helps users convert uploaded plain text files into UTF-8 encoding.

It is especially useful for researchers, archivists, digital humanities users, and anyone dealing with older encoded text files.

Features:

* Convert text files to UTF-8 directly in WordPress
* Auto-detect UTF-8, BIG5, SJIS, and GB2312
* Replace unsupported characters with ■
* Support both admin page and shortcode output
* Automatically delete temporary files after download
* Translation ready with `cc-utf8-converter` text domain

Shortcode:

`[cc_utf8_converter]`

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin through the WordPress admin
3. Go to `Tools > UTF-8 Converter`
4. Or insert the shortcode `[cc_utf8_converter]` into a page or post

== Frequently Asked Questions ==

= What file types are supported? =

Supported formats include:

* .txt
* .csv
* .html
* .htm
* .xhtml
* .xml
* .md

= Does the plugin store uploaded files permanently? =

No. Temporary files are deleted after download.

== Changelog ==

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.0 =
Initial release.