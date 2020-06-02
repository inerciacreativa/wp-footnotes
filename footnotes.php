<?php
/**
 * Plugin Name: ic Footnotes
 * Plugin URI:  https://github.com/inerciacreativa/wp-footnotes
 * Version:     1.0.1
 * Text Domain: ic-footnotes
 * Domain Path: /languages
 * Description: Crea notas a pie de página.
 * Author:      Jose Cuesta
 * Author URI:  https://inerciacreativa.com/
 * License:     MIT
 * License URI: https://opensource.org/licenses/MIT
 */

use ic\Framework\Framework;
use ic\Plugin\Footnotes\Footnotes;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists(Framework::class)) {
	throw new RuntimeException(sprintf('Could not find %s class.', Framework::class));
}

if (!class_exists(Footnotes::class)) {
	$autoload = __DIR__ . '/vendor/autoload.php';

	if (file_exists($autoload)) {
		/** @noinspection PhpIncludeInspection */
		include_once $autoload;
	} else {
		throw new RuntimeException(sprintf('Could not load %s class.', Footnotes::class));
	}
}

Footnotes::create(__FILE__);
