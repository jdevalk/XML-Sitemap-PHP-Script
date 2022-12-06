<?php
/**
 * XML Sitemap PHP Script
 * For more info, see: https://joost.blog/xml-sitemap-php-script/
 * Copyright (C), 2011 - 2022 - Joost de Valk, joost@joost.blog
 */

class Joost_XML_Sitemap_PHP {
	/**
	 * Holds our configuration.
	 */
	private array $config = [];

	/**
	 * Holds the output.
	 */
	private string $output = '';

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->read_ini();
		$this->read_dir();
		$this->output();
	}

	/**
	 * Read our ini file.
	 *
	 * @return void
	 */
	private function read_ini() {
		if ( file_exists( './xml-sitemap-config.ini' ) ) {
			$this->config = parse_ini_file( './xml-sitemap-config.ini' );
		}
		else if ( file_exists( '../xml-sitemap-config.ini' ) ) {
			$this->config = parse_ini_file( '../xml-sitemap-config.ini' );
		}
		else {
			echo "Error: unable to load config.php, please copy config-sample.php to config.php and adjust." . PHP_EOL;
			die( 1 );
		}
	}

	/**
	 * Kick off the script reading the dir provided in config.
	 *
	 * @return void
	 */
	private function read_dir() {
		$this->parse_dir( $this->config['directory'], $this->config['directory_url'] );
	}

	/**
	 * Output our XML sitemap.
	 *
	 * @return void
	 */
	private function output() {
		// Sent the correct header so browsers display properly, with or without XSL.
		header( 'Content-Type: application/xml' );

		echo '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL;
		if ( isset( $this->config['xsl'] ) && ! empty( $this->config['xsl'] ) ) {
			echo '<?xml-stylesheet type="text/xsl" href="' . $this->config['directory_url'] . $this->config['xsl'] . '"?>' . PHP_EOL;
		}
		echo '<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
		echo $this->output;
		echo '</urlset>' . PHP_EOL;
	}

	/**
	 * Reads a directory and adds files found to the output.
	 *
	 * @param string $dir The directory to read.
	 * @param string $url The base URL.
	 *
	 * @return void
	 */
	private function parse_dir( string $dir, string $url ) {
		$ignore        = array_merge( $this->config['ignore'], array( '.', '..', 'xml-sitemap.php' ) );
		$filetypes     = $this->config['filetypes'];

		$handle = opendir( $dir );

		while ( false !== ( $file = readdir( $handle ) ) ) {
			// Check if this file needs to be ignored, if so, skip it.
			if ( in_array( utf8_encode( $file ), $ignore ) ) {
				continue;
			}

			if ( is_dir( $dir . $file ) ) {
				if ( $this->config['recursive'] ) {
					$this->parse_dir( $dir . $file . '/', $url . $file . '/' );
				}
			}

			// Check whether the file has on of the extensions allowed for this XML sitemap
			$file_info = pathinfo( $dir . $file );
			if ( ! empty( $file_info['extension'] ) && in_array( $file_info['extension'], $filetypes ) ) {

				// Create a W3C valid date for use in the XML sitemap based on the file modification time
				if ( ! filemtime( $dir . '/' . $file ) ) {
					$mod = date( 'c', filectime( $dir . $file ) );
				} else {
					$mod = date( 'c', filemtime( $dir . $file ) );
				}

				// Replace the file with its replacement from the settings, if needed.
				if ( array_key_exists( $file, $this->config['replacements'] ) ) {
					$file = $this->config['replacements'][ $file ];
				}

				// Start creating the output
				$output = '<url>'  . PHP_EOL;
				$output .= "\t" . '<loc>' . $url . rawurlencode( $file ) . '</loc>'  . PHP_EOL;
				$output .= "\t" . '<lastmod>' . $mod . '</lastmod>'  . PHP_EOL;
				if ( ! empty( $this->config['changefreq'] ) ) {
					$output .= "\t" . '<changefreq>' . $this->config['changefreq'] . '</changefreq>'  . PHP_EOL;
				}
				if ( ! empty( $this->config['priority'] ) ) {
					$output .= "\t" . '<priority>' . $this->config['priority'] . '</priority>'  . PHP_EOL;
				}
				$output .= '</url>' . PHP_EOL;

				$this->output .= $output;
			}
		}
		closedir( $handle );
	}
}

new Joost_XML_Sitemap_PHP();
