<?php 

/**
 * XML Sitemap PHP Script
 * For more info, see: http://yoast.com/xml-sitemap-php-script/
 * Copyright (C), 2011 - Joost de Valk, joost@yoast.com
 */

// The directory to check, this script doesn't work recursively
define( 'SITEMAP_DIR', './');

// The file types, you can just add them on, so 'pdf', 'php' would work
$filetypes 	= array( 'php', 'html', 'pdf' );

// The replace array, this works as file => replacement, so 'index.php' => '', would make the index.php be listed as just /
$replace	= array( 'index.php' => '' );

// The XSL file used for styling the sitemap output, make sure this path is relative to the root of the site.
$xsl		= '/xml-sitemap.xsl';

// The Change Frequency for files, should probably not be 'never', unless you know for sure you'll never change them again.
$chfreq		= 'never';

// The Priority Frequency for files. There's no way to differentiate so it might just as well be 1. 
$prio		= 1;

// Ignore array, all files in this array will be: ignored!
$ignore		= array( 'config.php' );

/**
 * STOP EDITING HERE. (UNLESS YOU KNOW WHAT YOU'RE DOING)
 */ 

// Get the keys so we can check quickly
$replace_files	= array_keys( $replace );

// Sent the correct header so browsers display properly, with or without XSL.
header('Content-Type: application/xml');

echo '<?xml version="1.0" encoding="utf-8"?>'."\n"; 

if ( isset( $xsl ) && $xsl != '' )
	echo '<?xml-stylesheet type="text/xsl" href="http://'.$_SERVER['SERVER_NAME'].$xsl.'"?>'."\n";

?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"  xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><?php

		// Open the dir that was asked for.
		if ( $handle = opendir( SITEMAP_DIR ) ) {
			while ( false !== ( $file = readdir($handle) ) ) {
				// Check if this file needs to be ignored, if so, skip it.
				if ( in_array( $file, $ignore ) )
					continue;
					
				// Check whether the file has on of the extensions allowed for this XML sitemap
				$fileinfo = pathinfo( SITEMAP_DIR.$file );
				if ( in_array( $fileinfo['extension'], $filetypes ) ) {

					// Create a W3C valid date for use in the XML sitemap based on the file modification time
					$mod = date( 'c', filemtime( SITEMAP_DIR.$file ) );

					// Replace the file with it's replacement from the settings, if needed.
					if ( in_array( $file, $replace_files ) )
						$file = $replace[$file];
	
	// Start creating the output
	?>

	<url> 
		<loc>http://<?php echo $_SERVER['SERVER_NAME'].'/'.$file ?></loc> 
		<lastmod><?php echo $mod; ?></lastmod> 
		<changefreq><?php echo $chfreq; ?></changefreq> 
		<priority><?php echo $prio; ?></priority> 
	</url><?php
				}
		    } // End of the while loop
		
			// Close the dir
			closedir($handle);
		}
		
	?>	
</urlset>