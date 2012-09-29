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

if ( isset( $xsl ) && !empty( $xsl ) )
	echo '<?xml-stylesheet type="text/xsl" href="'. SITEMAP_DIR_URL . $xsl . '"?>' . "\n";

?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<?php

	// Open the dir that was asked for.
	if ( $handle = opendir( SITEMAP_DIR ) ) {
		while ( false !== ( $file = readdir( $handle ) ) ) {
			// Check if this file needs to be ignored, if so, skip it.
			if ( in_array( $file, $ignore ) )
				continue;

			// Check whether the file has on of the extensions allowed for this XML sitemap
			$fileinfo = pathinfo( SITEMAP_DIR . $file );
			if ( in_array( $fileinfo['extension'], $filetypes ) ) {

				// Create a W3C valid date for use in the XML sitemap based on the file modification time
				$mod = date( 'c', filemtime( SITEMAP_DIR . $file ) );

				// Replace the file with it's replacement from the settings, if needed.
				if ( in_array( $file, $replace_files ) )
					$file = $replace[$file];

				// Start creating the output
				?>

                <url>
                    <loc><?php echo SITEMAP_DIR_URL . $file ?></loc>
                    <lastmod><?php echo $mod; ?></lastmod>
                    <changefreq><?php echo $chfreq; ?></changefreq>
                    <priority><?php echo $prio; ?></priority>
                </url>
				<?php
			}
		} // End of the while loop

		// Close the dir
		closedir( $handle );
	}

	?>
</urlset>