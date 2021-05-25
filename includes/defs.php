<?php 
/**
 * Definitions needed in the plugin
 * 
 * @author Yari Morcus
 * @version 0.1
 * 
 * Version history
 * 0.1      Initial version 
*/

define( 'IVS_MEELOOP_PORTAAL__VERSION', '0.1' );

// Minimum required WordPress version for this plugin
define( "IVS_MEELOOP_PORTAAL_REQUIRED_WP_VERSION", '4.0' );

define( 'IVS_MEELOOP_PORTAAL_PLUGIN_BASENAME', plugin_basename( IVS_MEELOOP_PORTAAL ) );

define( 'IVS_MEELOOP_PORTAAL_PLUGIN_NAME', trim( dirname( IVS_MEELOOP_PORTAAL_PLUGIN_BASENAME ), '/' ) );

// Define the plugin directory
define( 'IVS_MEELOOP_PORTAAL_PLUGIN_DIR', untrailingslashit( dirname( IVS_MEELOOP_PORTAAL ) ));

// Define the includes directory
define( 'IVS_MEELOOP_PORTAAL_PLUGIN_INCLUDES_DIR',  IVS_MEELOOP_PORTAAL_PLUGIN_DIR . '/includes');

// Define the includes/views directory
define( 'IVS_MEELOOP_PORTAAL_PLUGIN_INCLUDES_VIEWS_DIR', IVS_MEELOOP_PORTAAL_PLUGIN_INCLUDES_DIR . '/views' );

// Define the includes/model directory
define( 'IVS_MEELOOP_PORTAAL_PLUGIN_INCLUDES_MODEL_DIR', IVS_MEELOOP_PORTAAL_PLUGIN_INCLUDES_DIR . '/model' );

// Define the admin directory
define( 'IVS_MEELOOP_PORTAAL_PLUGIN_ADMIN_DIR', IVS_MEELOOP_PORTAAL_PLUGIN_DIR . '/admin' );

// Define the admin/views directory
define( 'IVS_MEELOOP_PORTAAL_PLUGIN_ADMIN_VIEWS_DIR', IVS_MEELOOP_PORTAAL_PLUGIN_ADMIN_DIR . '/views');

// Define the name of the ivs-meeloop-templates folder
define( 'IVS_MEELOOP_PORTAAL_PLUGIN_TEMPLATES_FOLDER_FRONTEND', 'ivs-meeloop-templates' );
?>