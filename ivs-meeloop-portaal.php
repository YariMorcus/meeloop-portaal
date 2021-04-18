<?php 
// Check if WordPress core has loaded
defined( 'ABSPATH' ) OR exit;

/**
 * Plugin Name: Innovision Solutions - meeloop portaal
 * Plugin URI: http://stichtingivs.nl
 * Description: This WordPress plugin adds a 'meeloop portaal' as functionality, where students can see their date, planning, tasks and informational resources concerning the open day
 * Version: 0.1
 * Requires at least: 4.0
 * Author: Yari Morcus
*/

// Define the plugin name
define( 'IVS_MEELOOP_PORTAAL', __FILE__ );

// Include the general definition file
require_once( plugin_dir_path( __FILE__ ) . 'includes/defs.php' );

// Tell WordPress what to do when plugin has been activated

// HAS BEEN TEMPORARILY DISABLED DUE TO ERROR MESSAGE 18-4-2021
//register_activation_hook( __FILE__, array( 'IVSMeeloopPortaal', 'on_activation' ) );

// Tell WordPress what to do when plugin has been deactivated
// HAS BEEN TEMPORARILY DISABLED DUE TO ERROR MESSAGE 18-4-2021
// register_deactivation_hook( __FILE__, array( 'IVSMeeloopPortaal', 'on_deactivation' ) );

/**
 * Class to setup the plugin
 * @author Yari Morcus ymorcus@student.scalda.nl
 * @version 0.1
 * 
*/
class IVSMeeloopPortaal {

    /**
     * __construct
     * 
     * Execute function when new instance has been made of class IVSMeeloopPortaal
    */
    public function __construct() {

        // Fire a hook before the class is setup
        do_action( 'ivs_meeloop_portaal_pre_init' );

        // Load the plugin
        // add_action ( 'init', array( $this, 'init' ), 1 );
    }

    /**
     * on_activation
     * 
     * Indicate what will happen when user activates the plugin
    */
    public static function on_activation() {

        // If user cannot activate plugins, do nothing
        if ( ! current_user_can( 'activate_plugins' ) ) return;

        //$plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';

        check_admin_referer('deactivate-plugin_${plugin}' );

        //exit( var_dump( $_GET ) );

    }

    public static function on_deactivation() {

        if ( ! current_user_can( 'activate_plugins' ) ) return;

        $plugin = isset( $_REQUEST['plugin'] ) ? $_RQUEST['plugin'] : '';

        check_admin_referer( 'deactivate-plugin_${plugin}' );

        //exit( var_dump( $_GET ) );
    }

}

// Instantiate the class
$ivs_meeloop_portaal = new IVSMeeloopPortaal();
?>