<?php
/**
 * sabretooth.ini.php
 * 
 * Defines initialization settings for sabretooth.
 * DO NOT edit this file, to override these settings use sabretooth.local.ini.php instead.  Any
 * changes in the local ini file will override the settings found here.
 * 
 * @author Patrick Emond <emondpd@mcmaster.ca>
 * @package sabretooth
 */

namespace sabretooth;
global $SETTINGS;

// always leave as false when running as production server
$SETTINGS[ 'general' ][ 'development_mode' ] = false;

// the location of sabretooth internal paths
$SETTINGS[ 'paths' ][ 'SABRETOOTH_PATH' ] = '/usr/local/lib/sabretooth';
$SETTINGS[ 'paths' ][ 'API_PATH' ] = $SETTINGS[ 'paths' ][ 'SABRETOOTH_PATH' ].'/api';
$SETTINGS[ 'paths' ][ 'DOC_PATH' ] = $SETTINGS[ 'paths' ][ 'SABRETOOTH_PATH' ].'/doc';
$SETTINGS[ 'paths' ][ 'SQL_PATH' ] = $SETTINGS[ 'paths' ][ 'SABRETOOTH_PATH' ].'/sql';
$SETTINGS[ 'paths' ][ 'TPL_PATH' ] = $SETTINGS[ 'paths' ][ 'SABRETOOTH_PATH' ].'/tpl';

// the location of libraries
$SETTINGS[ 'paths' ][ 'ADODB_PATH' ] = '/usr/local/lib/adodb';
$SETTINGS[ 'paths' ][ 'PHPAGI_PATH' ] = '/usr/local/lib/phpagi';
$SETTINGS[ 'paths' ][ 'PHPEXCEL_PATH' ] = '/usr/local/lib/phpexcel';
$SETTINGS[ 'paths' ][ 'TWIG_PATH' ] = '/usr/local/lib/twig';
$SETTINGS[ 'paths' ][ 'JS_PATH' ] = 'js';
$SETTINGS[ 'paths' ][ 'CSS_PATH' ] = 'css';

// javascript libraries
$SETTINGS[ 'paths' ][ 'JQUERY_JS_FILE' ] = $SETTINGS[ 'paths' ][ 'JS_PATH' ].'/jquery.min.js';
$SETTINGS[ 'paths' ][ 'JQUERY_UI_JS_FILE' ] = $SETTINGS[ 'paths' ][ 'JS_PATH' ].'/jquery-ui.min.js';
$SETTINGS[ 'paths' ][ 'JQUERY_LAYOUT_JS_FILE' ] = $SETTINGS[ 'paths' ][ 'JS_PATH' ].'/jquery.layout.min.js';
$SETTINGS[ 'paths' ][ 'JQUERY_COOKIE_JS_FILE' ] = $SETTINGS[ 'paths' ][ 'JS_PATH' ].'/jquery.cookie.js';
$SETTINGS[ 'paths' ][ 'JQUERY_HOVERINTENT_JS_FILE' ] = $SETTINGS[ 'paths' ][ 'JS_PATH' ].'/jquery.hoverIntent.min.js';
$SETTINGS[ 'paths' ][ 'JQUERY_METADATA_JS_FILE' ] = $SETTINGS[ 'paths' ][ 'JS_PATH' ].'/jquery.metadata.js';
$SETTINGS[ 'paths' ][ 'JQUERY_FLIPTEXT_JS_FILE' ] = $SETTINGS[ 'paths' ][ 'JS_PATH' ].'/jquery-mb.flipText.js';
$SETTINGS[ 'paths' ][ 'JQUERY_EXTRUDER_JS_FILE' ] = $SETTINGS[ 'paths' ][ 'JS_PATH' ].'/jquery-mb.extruder.js';
$SETTINGS[ 'paths' ][ 'JQUERY_LOADING_JS_FILE' ] = $SETTINGS[ 'paths' ][ 'JS_PATH' ].'/jquery.loading.min.js';
$SETTINGS[ 'paths' ][ 'JQUERY_LOADING_OVERFLOW_JS_FILE' ] = $SETTINGS[ 'paths' ][ 'JS_PATH' ].'/jquery.loading.overflow.min.js';

// css files
$SETTINGS[ 'paths' ][ 'JQUERY_UI_CSS_FILE' ] = $SETTINGS[ 'paths' ][ 'CSS_PATH' ].'/ui/jquery-ui.css';

// the location of log files
$SETTINGS[ 'paths' ][ 'LOG_FILE' ] = '/var/local/sabretooth/log';

// database settings
$SETTINGS[ 'db' ][ 'driver' ] = 'mysql';
$SETTINGS[ 'db' ][ 'server' ] = 'localhost';
$SETTINGS[ 'db' ][ 'username' ] = 'sabretooth';
$SETTINGS[ 'db' ][ 'password' ] = '';
$SETTINGS[ 'db' ][ 'database' ] = 'sabretooth';
?>
