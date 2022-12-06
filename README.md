# XML Sitemap PHP Script

This simple PHP script is meant to help you easily create XML Sitemaps for static files, for instance PDFs.

## Configuration

1. Copy `xml-sitemap-config-sample.ini` to `xml-sitemap-config.ini`.
2. Configure the settings.
3. Check the output of the xml-sitemap script and if it's ok, add the script URL to Google Webmaster Tools.

## FAQ

### Why do the `changefreq` and `priority` default to empty?

Because Google doesn't use them, and at that point outputting them is more work than it's worth. If you want to set them 
though, you can.

### Why can't I host the XSL elsehwere?

You can put it wherever you want except that it _has_ to come from the same domain. I'd suggest just keeping it with the script.

### Could I run this in a WordPress `wp-content` folder?

Yes, absolutely. Set the `directory` to `uploads` and make sure `recursive` is set to `true` and it'd generate a nice XML
sitemap for all your PDFs.

### Can I report issues?

Absolutely, on this project's [GitHub](https://github.com/jdevalk/XML-Sitemap-PHP-Script).

## License

This script is licensed under the GPL v3.

## Changelog

* 2022-12-06:
    * Changed the whole script to be a single class to avoid namespace clashes and modernize the code.
    * Switched from `config.php` to a `xml-sitemap-config.ini` file that can also be stored one directory above the script.
    * Fixed a series of bugs with recursive directory listings etc.

* 2013-09-22:
    * Some small bugfixes to the script.
    * Added license to readme.

* 2012-09-29:
    * Move configuration to config.php.
    * Fix URL output.
    * Add option to work recursively.
