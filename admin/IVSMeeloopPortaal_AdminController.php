<?php 
/**
 * This Admin controller file provides all the functionality for the Admin section
 * of the IVS Meeloop Portaal plugin
 * 
 * @author Yari Morcus
 * @version 0.1
*/

class IVSMeeloopPortaal_AdminController {

    /**
     * prepare
     * 
     * Prepare all Admin functionality for the plugin
     * 
    */
    static function prepare() {

        // Only execute when current request is from an administrative interface page
        if ( is_admin() ) {

            // Setup the admin menus
            add_action( 'admin_menu' , array( 'IVSMeeloopPortaal_AdminController', 'addMenus' ) );

            // Load admin css
            add_action( 'admin_enqueue_scripts', array('IVSMeeloopPortaal_AdminController', 'loadAdminCSS' ) );
        }

    }

    /**
     * addMenus
     * 
     * Add the Menu structure to the Admin sidebar
    */
    static function addMenus() {

        add_menu_page(

            // string $page_title - The text to be displayed in the title tag of the page
            // when menu is selected
            __( 'Admin overzicht - IVS Meeloop Portaal', 'ivs-meeloop-portaal' ),
            // string $menu_title - The text to be used for the menu
            __( 'Meeloop portaal', 'ivs-meeloop-portaal' ),
            // string $capability - The capability required for this menu to be displayed
            // to the user
            'manage_options',
            // string $menu_slug - The slug name to refer to this menu by
            // Should only include: lowercase alphanumeric, dashes and underscores
            // Must be unique
            'admin-overzicht-ivs-meeloop-portaal',
            // callable $function - The function to be called to output the content for this page
            array( 'IVSMeeloopPortaal_AdminController', 'adminMenuPage' ),
            // string $icon_url - The URL to the icon to be used for this menu
            // -- optional
            'dashicons-admin-home'
            // int $position - The position in the menu order this item should appear
            // -- optional

        );

        add_submenu_page(

            // string $parent_slug - The slug name for the parent menu
            'admin-overzicht-ivs-meeloop-portaal',
            // string $page_title - The text to be displayed in the title tag of the page
            // when menu is selected
            __( 'Toevoegen meeloopdag - IVS Meeloop Portaal', 'ivs-meeloop-portaal' ),
            // string $menu_title - The text to be used for the menu
            __( 'Toevoegen meeloopdag', 'ivs-meeloop-portaal' ),
            // string $capability - The capability required for this menu to be displayed
            // to the user
            'manage_options',
            // string $menu_slug - The slug name to refer to this menu by
            // Should only include: lowercase alphanumeric, dashes and underscores
            // Must be unique
            'toevoegen-meeloopdag',
            // callable $function - The function to be called to output the content for this page
            array( 'IVSMeeloopPortaal_AdminController', 'adminSubMenuToevoegenMeeloopdag' )

        );

        add_submenu_page(

            // string $parent_slug - The slug name for the parent menu
            'admin-overzicht-ivs-meeloop-portaal',
            // string $page_title - The text to be displayed in the title tag of the page
            // when menu is selected
            __( 'Mailinglist - IVS Meeloop Portaal', 'ivs-meeloop-portaal' ),
            // string $menu_title - The text to be used for the menu
            __( 'Mailinglist', 'ivs-meeloop-portaal' ),
            // string $capability - The capability required for this menu to be displayed
            // to the user
            'manage_options',
            // string $menu_slug - The slug name to refer to this menu by
            // Should only include lowercase alphanumeric, dashes and underscores
            // Must be unique
            'mailinglist-meeloopdag',
            // callable $function - The function to be called to output the content for this page
            array( 'IVSMeeloopPortaal_AdminController', 'adminSubMenuMailinglistMeeloopdag' )

        );

        add_submenu_page(

            // string $parent_slug - The slug name for the parent menu
            'admin-overzicht-ivs-meeloop-portaal',
            // string $page_title - The text to be displayed in the title tag of the page
            // when menu is selected
            __( 'Opleidingen - IVS Meeloop Portaal', 'ivs-meeloop-portaal' ),
            // string $menu_title - The text to be used for the menu
            __( 'Opleidingen', 'ivs-meeloop-portaal' ),
            // string $capability - The capability required for this menu to be displayed
            // to the user
            'manage_options',
            // string $menu_slug - The slug name to refer to this menu by
            // Should only include lowercase alphanumeric, dashes and underscores
            // Must be unique
            'opleidingen-meeloopdag',
            // callable $function - The function to be called to output the content for this page
            array( 'IVSMeeloopPortaal_AdminController', 'adminSubMenuOpleidingenMeeloopdag' )

        );

    }

    /**
     * adminMenuPage
     * 
     * The admin overview page of the IVS meeloop portaal plugin
    */
    static function adminMenuPage() {

        // Include the view for this menu page
        include IVS_MEELOOP_PORTAAL_PLUGIN_ADMIN_VIEWS_DIR . '/admin_main.php';
    }

    /**
     * adminSubMenuToevoegenMeeloopdag
     * 
     * The admin toevoegen meeloopdag page of the IVS meeloop portaal plugin
    */
    static function adminSubMenuToevoegenMeeloopdag() {

        // Include the view for this menu page
        include IVS_MEELOOP_PORTAAL_PLUGIN_ADMIN_VIEWS_DIR . '/toevoegen-meeloopdag.php';
    }

    /**
     * adminSubMenuMailinglistMeeloopdag
     * 
     * The admin mailinglist page of the IVS meeloop portaal plugin
    */
    static function adminSubMenuMailinglistMeeloopdag() {

        // Include the view for this menu page
        include IVS_MEELOOP_PORTAAL_PLUGIN_ADMIN_VIEWS_DIR . '/mailinglist-meeloopdag.php';

    }

    /**
     * adminSubMenuOpleidingenMeeloopdag
     * 
     * The admin opleidingen page of the IVS meeloop portaal plugin
     * 
    */
    static function adminSubMenuOpleidingenMeeloopdag() {

        // Include the view for this menu page
        include IVS_MEELOOP_PORTAAL_PLUGIN_ADMIN_VIEWS_DIR . '/opleidingen-meeloopdag.php';
    } 

    /**
     * loadAdminCSS
     * 
     * Load all the Admin CSS to style the menu item
    */
    static function loadAdminCSS() {

        wp_enqueue_style( 'my_custom_script', plugin_dir_url( __FILE__ ) .  'assets/ivs-styles.css', array(), null );

    }

}

?>