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

/**
 * Include class related to pages
 * @author Yari Morcus
 * @version 0.1
*/
require_once( IVS_MEELOOP_PORTAAL_PLUGIN_INCLUDES_VIEWS_DIR . '/PageView.php' );

// Tell WordPress what to do when plugin has been activated
register_activation_hook( __FILE__, array( 'IVSMeeloopPortaal', 'on_activation' ) );

// Tell WordPress what to do when plugin has been deactivated
register_deactivation_hook( __FILE__, array( 'IVSMeeloopPortaal', 'on_deactivation' ) );

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
        add_action( 'init', array( $this, 'init' ), 1 );
        
    }

    // Show custom post label in pages list for pages that have been created by the plugin
    public function showPostState( $states, $post ) {

        // Register all the templates
        $template_list = array(
            IVS_MEELOOP_PORTAAL_PLUGIN_TEMPLATES_FOLDER_FRONTEND . '/template-mijn-rooster.php',
            IVS_MEELOOP_PORTAAL_PLUGIN_TEMPLATES_FOLDER_FRONTEND . '/template-dashboard.php',
            IVS_MEELOOP_PORTAAL_PLUGIN_TEMPLATES_FOLDER_FRONTEND . '/template-indienen-opdrachten.php',
            IVS_MEELOOP_PORTAAL_PLUGIN_TEMPLATES_FOLDER_FRONTEND . '/template-inzien-opdrachten.php'
        );

        foreach( $template_list as $template ) {

            // If page in list (wp-admin) contains one of these templates, assign the label to it
            if ( $template == get_post_meta( $post->ID, '_wp_page_template', true ) ) {

                $states[] = __( 'IVS MP' );
                
                // Markup for better notice
                echo "
                <script type=\"text/javascript\">
                setTimeout(function() { 
                    
                    document.querySelector(\"#post-$post->ID .post-state\").style=\"margin-right: 2rem; padding: 0 2rem; color: #000; font-weight: bold; background: #e6bc28\"; 

                    var pageName = document.querySelector('#post-$post->ID .row-title');
                    var postState = document.querySelector('#post-$post->ID .post-state');
                    var parentNode = pageName.parentNode;

                    postState.parentNode.insertBefore(pageName, postState)
                    pageName.parentNode.insertBefore(postState, pageName)

                    postState.parentNode.removeChild(postState.previousSibling)

                
                }, 200)
                </script>'
                ";
            }

        }

        return $states;

    }

    // Show info message for more information about the labels (see above function)
    public function infoMessage() {

        $current_screen = get_current_screen();

        if ( $current_screen->id != 'edit-page' ) return;

        ?>
            <div class="notice notice-info">
                <p><strong>Ter informatie:</strong> pagina's waarvoor het label <span style="padding: 0 2rem; color: #000; font-weight: bold; background: rgb(230, 188, 40);">IVS MP</span> staat, zijn een onderdeel van het meeloop portaal. Deze <strong>niet</strong> wijzigen.</p>
            </div>
        <?php 
    }

    public function init() {

        // Instantiate the class
        $page_view = new PageView();

        add_filter( 'display_post_states', array( $this, 'showPostState'), 10, 2 );

        add_action( 'admin_notices', array( $this, 'infoMessage' ) );

        // Load the frontend css
        $page_view->loadFrontendCSS();
        
    }

    /**
     * on_activation
     * 
     * if { ! current_user_can( 'activate_plugins' ) }, if user cannot activate plugin's, abort function 
     * (prevent user from activating plugin)
    */
    public static function on_activation() {

        add_action( 'admin_notices', 'tester' );

        if ( ! current_user_can( 'activate_plugins' ) ) return;

        //$plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';

        //check_admin_referer('deactivate-plugin_${plugin}' );

        //exit( var_dump( $_GET ) );

        $page_view = new PageView();

        // Create page templates
        $page_view->createTemplates();

        // Create the corresponding pages for the dashboard
        $page_view->createPages();

    }

    /**
     * on_deactivation
     * 
     * if { ! current_user_can( 'activate_plugins' ) }, if user cannot activate plugin's, abort function 
     * (prevent user from deactivating plugin)
    */
    public static function on_deactivation() {

        if ( ! current_user_can( 'activate_plugins' ) ) return;

        $page_view = new PageView();

        // Remove templates directory
        $page_view->removeDirectory();

        // Remove WordPress pages
        $page_view->removePages();

        //$plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';

        //exit( var_dump( $_GET ) );
    }

}

// Instantiate the class
$ivs_meeloop_portaal = new IVSMeeloopPortaal();
?>
