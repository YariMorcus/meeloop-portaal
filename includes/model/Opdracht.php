<?php 
/**
 * Class contains all the functionality that is associated with an education
 * 
 * @author Yari Morcus
 * 
*/
class Opdracht {

    // Define table name to prevent future spelling mistakes
    private const TABLE_NAME = 'ivs_mp_opleiding';


    /**
     * registerTaskPostType
     * 
     * Register the task post type, so the user can create tasks for his meeloop studenten
     * 
    */
    public static function registerTaskPostType() {

        // Define name for post type
        $post_type = 'opdrachten';

        // Define labels for post type
        $labels = array(
                'name'                  => _x( 'Opdrachten', 'Post type general name', 'textdomain' ),
                'singular_name'         => _x( 'Opdracht', 'Post type singular name', 'textdomain' ),
                'menu_name'             => _x( 'Opdrachten', 'Admin Menu text', 'textdomain' ),
                'name_admin_bar'        => _x( 'Opdracht', 'Add New on Toolbar', 'textdomain' ),
                'add_new'               => __( 'Maak opdracht', 'textdomain' ),
                'add_new_item'          => __( 'Maak opdracht', 'textdomain' ),
                'new_item'              => __( 'Maak opdracht', 'textdomain' ),
                'edit_item'             => __( 'Bewerk opdrachten', 'textdomain' ),
                'view_item'             => __( 'Bekijk opdracht', 'textdomain' ),
                'all_items'             => __( 'Opdrachten', 'textdomain' ),
                'search_items'          => __( 'Zoek een opdracht', 'textdomain' ),
                'parent_item_colon'     => __( 'Hoofd opdrachten', 'textdomain' ),
                'not_found'             => __( 'Geen opdrachten gevonden', 'textdomain' ),
                'not_found_in_trash'    => __( 'Geen opdrachten gevonden in prullenbak', 'textdomain' ),
        );

        // Define arguments for post type
        $args = array(
            'labels'                    => $labels,
            'public'                    => true,
            'hierarchical'              => false,
            'exclude_from_search'       => false,
            'publicly_queryable'        => false,
            'show_ui'                   => true,
            'show_in_menu'              => 'admin-overzicht-ivs-meeloop-portaal',
            'show_in_nav_menus'         => false,
            'show_in_admin_bar'         => false,
            'show_in_rest'              => false, // Set to true for Gutenberg Block editor (currently disabled because of routing problem)
            'menu_position'             => null,
            'capability_type'           => 'post',
            'capabilities'              => array(),
            'has_archive'               => false,
            'can_export'                => true,
            'taxonomies'                => array( 'category' ),
        );

        // Register the post type based on the given arguments
        register_post_type( $post_type, $args );

    }

}
 ?>