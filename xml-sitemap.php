<?php

/**
 * XML Sitemap PHP Script
 * For more info, see: http://yoast.com/xml-sitemap-php-script/
 * Copyright (C), 2011 - 2012 - Joost de Valk, joost@yoast.com
 */

require './config.php';

// Get the keys so we can check quickly
$replace_files = array_keys( $replace );

// Sent the correct header so browsers display properly, with or without XSL.
header( 'Content-Type: application/xml' );

echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";

$ignore = array_merge( $ignore, array( '.', '..', 'config.php', 'xml-sitemap.php' ) );

if ( isset( $xsl ) && !empty( $xsl ) )
	echo '<?xml-stylesheet type="text/xsl" href="' . SITEMAP_DIR_URL . $xsl . '"?>' . "\n";

function parse_dir( $dir, $url ) {
	global $ignore, $filetypes, $replace, $chfreq, $prio;

	$handle = opendir( $dir );
	while ( false !== ( $file = readdir( $handle ) ) ) {

		// Check if this file needs to be ignored, if so, skip it.
		if ( in_array( $file, $ignore ) )
			continue;

		if ( is_dir( $file ) ) {
			if ( defined( 'RECURSIVE' ) && RECURSIVE )
				parse_dir( $file, $url . $file . '/' );
		}

		// Check whether the file has on of the extensions allowed for this XML sitemap
		$fileinfo = pathinfo( $dir . $file );
		if ( in_array( $fileinfo['extension'], $filetypes ) ) {

			// Create a W3C valid date for use in the XML sitemap based on the file modification time
			$mod = date( 'c', filemtime( $dir . $file ) );

			// Replace the file with it's replacement from the settings, if needed.
			if ( in_array( $file, $replace ) )
				$file = $replace[$file];

			// Start creating the output
	?>

    <url>
        <loc><?php echo $url . $file ?></loc>
        <lastmod><?php echo $mod; ?></lastmod>
        <changefreq><?php echo $chfreq; ?></changefreq>
        <priority><?php echo $prio; ?></priority>
    </url><?php
		}
	}
	closedir( $handle );
}

?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><?php
	parse_dir( SITEMAP_DIR, SITEMAP_DIR_URL );
?>

</urlset>