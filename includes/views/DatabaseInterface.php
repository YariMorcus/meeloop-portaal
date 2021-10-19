<?php 
/**
 * Class contains everything that is related to the database, such as:
 * inserting, updating, deleting pages
 * @author Yari Morcus
 * @version 0.1
 * 
*/

class DatabaseInterface {

    /**
     * createPageTemplate
     * 
     * @param string the slug of the post
     * @param string the name of the template. Name will be prefixed with {template-}
     * @param string the title of the page
     * 
     * URL for help: https://adaptiveweb.com.au/creating-pages-automatically-on-plugin-activation-in-wordpress/
    */
        public function createPageTemplate($post_name_slug, $template_name, $page_title) {

            global $wpdb;

            // If page doesn't exist yet, create it
            if (null === $wpdb->get_row("SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = '{$post_name_slug}'", 'ARRAY_A') ) {

                // Get current user
                $current_user = wp_get_current_user();

                // Path to the template
                $path_to_template = IVS_MEELOOP_PORTAAL_PLUGIN_TEMPLATES_FOLDER_FRONTEND . "/template-{$template_name}.php";

                // Create the post object
                $page = array(
                    'post_title' => __( $page_title ),
                    'post_status' => 'publish',
                    'post_author' => $current_user->ID,
                    'post_type' => 'page',
                    'post_name' => $post_name_slug,
                    'page_template' => $path_to_template,

                );

                // Insert the post
                wp_insert_post( $page );
            }
        }

        /**
         * removePageTemplate
         * 
         * Removes the automatically created pages when user deactivates the plugin
         * @param string the post name
        */
        public function removePageTemplate($post_name) {

            /**
             * 1. Retrieve the post id of the given $post_name
             * 
             * wp_delete_post()
             * https://developer.wordpress.org/reference/functions/wp_delete_post/
             * 
             * https://stackoverflow.com/questions/12905763/get-post-by-post-name-instead-of-id
            */
            
            $page = get_page_by_path( $post_name, OBJECT, 'page' );

            wp_delete_post( $page->ID, true );

        }    

    /**
     * createPageDashboard
     * 
     * Function creates 'meeloop dashboard' page automatically, when plugin is activated
     * 
    */
    public function createPageDashboard() {
        $this->createPageTemplate( 'dashboard', 'dashboard', 'Dashboard meeloopdag' );
    }

    /**
     * createPageMijnRooster
     * 
     * Function creates 'mijn rooster' page automatically, when plugin is activated
     * 
    */
    public function createPageMijnRooster() {
        $this->createPageTemplate( 'mijn-rooster', 'mijn-rooster', 'Bekijk rooster' );
    }

    /**
     * createPageInzienOpdrachten
     * 
     * Function creates 'inzien opdrachten' page automatically, when plugin is activated
     * 
    */
    public function createPageInzienOpdrachten() {
        $this->createPageTemplate( 'inzien-opdrachten', 'inzien-opdrachten', 'Inzien opdrachten' );
    }

    /**
     * createPageIndienenOpdrachten
     * 
     * Function creates 'indienen opdrachten' page automatically, when plugin is activated
     * 
    */
    public function createPageIndienenOpdrachten() {
        $this->createPageTemplate( 'indienen-opdrachten', 'indienen-opdrachten', 'Indienen opdrachten' );
    }
   
    /**
     * removePageDashboard
     * 
     * Function removes 'meeloop dashboard' page automatically, when plugin is deactivated
     * 
    */
    public function removePageDashboard() {
        $this->removePageTemplate( 'dashboard' );
    }

    /**
     * removePageMijnRooster
     * 
     * Function removes 'mijn rooster' page automatically, when plugin is deactivated
     * 
    */
    public function removePageMijnRooster() {
        $this->removePageTemplate( 'mijn-rooster' );
    }

    /**
     * removePageInzienOpdrachten
     * 
     * Function removes 'inzien opdrachten' page automatically, when plugin is deactivated
     * 
    */
    public function removePageInzienOpdrachten() {
        $this->removePageTemplate( 'inzien-opdrachten' );
    }

    /**
     * removePageInzienOpdrachten
     * 
     * Function removes 'indienen opdrachten' page automatically, when plugin is deactivated
     * 
    */
    public function removePageIndienenOpdrachten() {
        $this->removePageTemplate( 'indienen-opdrachten' );
    }

}
?>