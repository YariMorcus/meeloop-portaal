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
                    
                    document.querySelector(\"#post-$post->ID .post-state\").style=\"margin-right: 2rem; padding: 0 2rem; color: #000; font-weight: bold; background: #fdd02a\"; 

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
                <p><strong>Ter informatie:</strong> pagina's waarvoor het label <span style="padding: 0 2rem; color: #000; font-weight: bold; background: #fdd02a;">IVS MP</span> staat, zijn een onderdeel van het meeloop portaal. Deze <strong>niet</strong> wijzigen.</p>
            </div>
        <?php 
    }

    function my_admin_queue( $hook_suffix ) {

        if ( $hook_suffix == 'meeloop-portaal_page_roosters-meeloopdag' ) {
            
            wp_enqueue_script( 'jquery-accordion-functionality', 'https://code.jquery.com/jquery-3.6.0.js' );
            wp_enqueue_script( 'jquery-ui', 'https://code.jquery.com/ui/1.13.0/jquery-ui.js', array( 'jquery-accordion-functionality' ) );
            wp_enqueue_style( 'jquery-ui', 'http://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css' );
            ?> 

            <script type="text/javascript">

            // Only execute jQuery when HTML body is fully loaded.
            // Will throw error otherwise
            document.addEventListener('DOMContentLoaded', function() {

                ( function( $ ) {
                    
                    $( '.collapse-header' ).click(function() {

                        $header = $(this);

                        // Get the next element
                        $content = $header.next();

                        // Show the content when user clicks on header, or close it if user has already opened it
                        $content.slideToggle( '500' );

                    });

                })( jQuery );
            });

            </script>

            <?php
        }
    }

    /**
     * init
     * 
     * Loads the plugin into WordPress
    */
    public function init() {

        // Load Admin only components
        if ( is_admin() ) {

            // Load all Admin specific includes
            $this->requireAdmin();

            // Setup admin page
            $this->createAdmin();

        }

        // Instantiate the class
        $page_view = new PageView();

        add_filter( 'display_post_states', array( $this, 'showPostState'), 10, 2 );

        add_action( 'admin_notices', array( $this, 'infoMessage' ) );

        add_action( 'admin_enqueue_scripts', array( $this, 'my_admin_queue' ) );

        // Load the frontend css
        $page_view->loadFrontendCSS();
        
    }

    /**
     * requireAdmin
     * 
     * Loads all Admin related files into scope
    */
    public function requireAdmin() {

        // Admin controller file
        require_once IVS_MEELOOP_PORTAAL_PLUGIN_ADMIN_DIR . '/IVSMeeloopPortaal_AdminController.php';
    }

    /**
     * createAdmin
     * 
     * Admin controller functionality
    */
    public function createAdmin() {

        // Execute the prepare function
        IVSMeeloopPortaal_AdminController::prepare();

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

        // Include the CreateDatabaseTables class
        require_once( IVS_MEELOOP_PORTAAL_PLUGIN_INCLUDES_MODEL_DIR . '/IVS_DatabaseSetup.php');

        // Insert the tables
        IVS_DatabaseSetup::createDBTables();

        // Insert the data
        IVS_DatabaseSetup::insertDBData();

        // Add the plugin capabilities
        IVSMeeloopPortaal::add_plugin_caps();

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
        
        // Include the CreateDatabaseTables class
        require_once( IVS_MEELOOP_PORTAAL_PLUGIN_INCLUDES_MODEL_DIR . '/IVS_DatabaseSetup.php');

        // Remove the database tables
        IVS_DatabaseSetup::removeDBTables();

        // Remove the plugin specific capabilities
        IVSMeeloopPortaal::remove_plugin_caps();

        $page_view = new PageView();

        // Remove templates directory
        $page_view->removeDirectory();

        // Remove WordPress pages
        $page_view->removePages();

    }

    /**
     * get_plugin_roles_and_caps
     * 
     * Define the array with plugin specific capabilities per role
    */
    public static function get_plugin_roles_and_caps() {

        // Define the desired roles for this plugin
        return array(
            /* Is always available - Should be on the first line */
            array( 
                'administrator',
                'Admin',
                array(
                    'ivs_mp_read',
                    'ivs_mp_create',
                    'ivs_mp_update',
                    'ivs_mp_delete'
                ) ),
            
            array(
                'docent',
                'Docent',
                array(
                    'ivs_mp_read',
                    'ivs_mp_create',
                    'ivs_mp_update',
                    'ivs_mp_delete'
                ) ),

            array(
                'meeloper',
                'Meelooper',
                array(
                    'ivs_mp_read'
                ) )
        );

    }

    /**
     * add_plugin_caps
     * 
     * Add plugin specific capabilities 
     * Check first for the specific roles
     * If they not exist, add specific roles
     * Add plugin specific caps per role
    */
    public static function add_plugin_caps() {

        // Include the roles and capabilities definition file
        require_once plugin_dir_path( __FILE__ ) . 'includes/roles_and_caps_defs.php';

        $role_array = IVSMeeloopPortaal::get_plugin_roles_and_caps();

        // Check for the roles
        foreach( $role_array as $key => $role_name ) {
         
            // Check specific role
            if ( !( $GLOBALS['wp_roles']->is_role( $role_name[IVS_MP_ROLE_NAME] ) ) ) {

                $role = add_role( 
                    $role_name[IVS_MP_ROLE_NAME],
                    $role_name[IVS_MP_ROLE_ALIAS],
                    array( 'read' => true, 'level_0' => true )
                 );

            }
        }

        // Add the capabilities per role
        foreach( $role_array as $key => $role_name ) {

            // Create the capabilities for this role
            foreach( $role_name[IVS_MP_ROLE_CAP_ARRAY] as $cap_key => $cap_name ) {

                // Gets the author role
                $role = get_role( $role_name[IVS_MP_ROLE_NAME] );

                // This only works, because it accesses the class instance.
                // Would allow the author to edit others' posts for current theme only
                $role->add_cap( $cap_name );

            }
        }
    }

    /**
     * remove_plugin_caps
     * 
     * Remove all the specific capabilities for this plugin
    */
    public static function remove_plugin_caps() {

        // Include the roles and capabilities definition file
        require_once plugin_dir_path( __FILE__ ) . 'includes/roles_and_caps_defs.php';

        // Get the plugin specific capabilities per role
        $role_array = IVSMeeloopPortaal::get_plugin_roles_and_caps();

        // Add the capabilities per role
        foreach( $role_array as $key => $role_name ) {

            // Create the capabilities for this role
            foreach( $role_name[IVS_MP_ROLE_CAP_ARRAY] as $cap_key => $cap_name ) {

                // Get the specific role
                $role = get_role( $role_name[IVS_MP_ROLE_NAME] );

                // This only works, because it accesses the class instance.
                $role->remove_cap( $cap_name );

            }

        }

    }
}

// Instantiate the class
$ivs_meeloop_portaal = new IVSMeeloopPortaal();
?>
