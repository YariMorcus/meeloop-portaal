<?php 
/**
 * This class contains everything that is related to the pages
 * of the dashboard
 * @author Yari Morcus
 * @version 0.1
 * 
*/

require_once( wp_normalize_path( IVS_MEELOOP_PORTAAL_PLUGIN_INCLUDES_VIEWS_DIR . '/DatabaseInterface.php' ) );

class PageView {
    /**
     * showErrorMesssage
     * 
     * Load in an error message page when user is not logged in, or doesn't have the right permissions
     * 
    */
    public function showErrorMessage() {

        // The file name
        $file_name = '/not-logged-in-message.php';

        // Include the file
        include_once(wp_normalize_path(IVS_MEELOOP_PORTAAL_PLUGIN_INCLUDES_VIEWS_DIR . $file_name));

        // Prevent the rest of the content from being loaded
        exit;

    }

    /**
     * loadFrontendCSS
     * 
     * Register and load the css for the frontend pages
    */
    public function loadFrontendCSS() {

        $path = '/wp-content/plugins/ivs-meeloop-portaal/includes/views/assets/style.css';
        wp_register_style( 'ivs-meeloop-dashboard', $path , null );
        wp_enqueue_style('ivs-meeloop-dashboard');
    }
    
    /**
    * createTemplates
    *
    * Function acts as an intermediate function that creates all the necessary templates
    *
    */
    public function createTemplates() {

        // Create template-dashboard.php in current theme
        $this->createTemplateDashboard();

        // Create template-mijn-rooster.php in current theme
        $this->createTemplateMijnRooster();

        // Create template-inzien-opdrachten.php in current theme
        $this->createTemplateInzienOpdrachten();

        // Create template-indienen-opdrachten.php in current theme
        $this->createTemplateIndienenOpdrachten();
    }

    /**
     * createPages
     * 
     * Function acts as an intermediate function that creates all the necessary pages
     * URL for help: https://adaptiveweb.com.au/creating-pages-automatically-on-plugin-activation-in-wordpress/
    */
    public function createPages() {

        // Instantiate the DatabaseInterface
        $database_interface = new DatabaseInterface();

        // Create dashboard page
        $database_interface->createPageDashboard();

        // Create mijn rooster page
        $database_interface->createPageMijnRooster();

        // Create inzien opdrachten page
        $database_interface->createPageInzienOpdrachten();

        // Create indienen opdrachten page
        $database_interface->createPageIndienenOpdrachten();

    }

    /**
    * createTemplateBlueprint
    * 
    * @param string template_slug
    * @param string template_name
    *
    */
    private function createTemplateBlueprint( $template_slug, $template_name ) {

        $current_theme_path = wp_normalize_path( get_template_directory() ); 

        if ( ! file_exists($current_theme_path . '/' . IVS_MEELOOP_PORTAAL_PLUGIN_TEMPLATES_FOLDER_FRONTEND) ) {

            $new_directory = $current_theme_path . '/' . IVS_MEELOOP_PORTAAL_PLUGIN_TEMPLATES_FOLDER_FRONTEND; 
            mkdir( $new_directory, 0777, false );
        }

        // Create the file
        $file = fopen( $current_theme_path . "/" . IVS_MEELOOP_PORTAAL_PLUGIN_TEMPLATES_FOLDER_FRONTEND . "/template-{$template_slug}.php", 'w' ) or die ('Could not open file.');

        // Store Template Name comment, and write (fwrite) it to the file
        $template_name_header = "<?php /* Template Name: {$template_name} */ ?>\n\n";
        fwrite( $file, $template_name_header );

        // Redirect the created template in themes folder to template in plugins folder
        $redirect_to_template_file = "<?php require_once( IVS_MEELOOP_PORTAAL_PLUGIN_INCLUDES_VIEWS_DIR . '/template-{$template_slug}.php' ) ?>";
        fwrite( $file, $redirect_to_template_file );

        // Close the open file
        fclose( $file );

    }

    /**
     * createTemplateDashboard
     * 
     * Function is used to create the template for the 'meeloop dashboard' page
     * 
    */
    private function createTemplateDashboard() {

        $this->createTemplateBlueprint('dashboard', 'Meeloop dashboard');

    }
    
    /**
     * createTemplateMijnRooster
     * 
     * Function is used to create the template for the 'mijn rooster' page
     * 
    */
    private function createTemplateMijnRooster() {

        $this->createTemplateBlueprint('mijn-rooster', 'Meeloop rooster');

    }

    /**
     * createTemplateInzienOpdrachten
     * 
     * Function is used to create the template for the 'inzien opdrachten' page
     * 
    */
    private function createTemplateInzienOpdrachten() {

        $this->createTemplateBlueprint('inzien-opdrachten', 'Meeloop inzien opdrachten');

    }

    /**
     * createTemplateIndienenOpdrachten
     * 
     * Function is used to create the template for the 'indienen opdrachten' page
     * 
    */
    private function createTemplateIndienenOpdrachten() {

        $this->createTemplateBlueprint('indienen-opdrachten', 'Meeloop indienen opdrachten');

    }

    /**
    * removeDirectory
    *
    * Function removes both the files inside the directory, as the directory itself
    * (Keep in mind that rmdir only works on empty directory's)
    *
    * if { ! is_dir( $path_to_ivs_meeloop_templates_folder ) }, if path isn't a directory, throw an error
    * if { last_character_of_ivs_meeloop_templates_folder != '/' }, if path doesn't end with a forward slash, append it to it
    */
    public function removeDirectory() {

        // Path to the ivs-meeloop-templates folder in current theme
        $path_to_ivs_meeloop_templates_folder = wp_normalize_path( get_template_directory() ) . '/' . IVS_MEELOOP_PORTAAL_PLUGIN_TEMPLATES_FOLDER_FRONTEND;

       if ( ! is_dir( $path_to_ivs_meeloop_templates_folder ) ) {

           throw new InvalidArgumentException( "$path_to_ivs_meeloop_templates_folder must be a directory" );

       }

       // Get the last character of the path to the ivs-meeloop-templates folder
       $last_character_of_ivs_meeloop_templates_folder = substr( $path_to_ivs_meeloop_templates_folder, strlen( $path_to_ivs_meeloop_templates_folder ) - 1, 1);

       if ( $last_character_of_ivs_meeloop_templates_folder != '/' ) {
           $path_to_ivs_meeloop_templates_folder .= '/';
       }

       // Retrieve the pathnames of the files within the ivs-meeloop-templates folder
       $template_files = glob( $path_to_ivs_meeloop_templates_folder . '*', GLOB_MARK );

       // Iterate over all the pathnames
       foreach( $template_files as $template_file ) {

            // Delete the file
            unlink( $template_file );

       }

       // When all files are deleted, remove the directory
       rmdir( $path_to_ivs_meeloop_templates_folder );

    }

    /**
     * removePages
     * 
     * Function acts as an intermediate function that removes all the necessary pages
     * 
    */
    public function removePages() {

        // Instantiate the DatabaseInterface
        $database_interface = new DatabaseInterface();

        // Remove dashboard page
        $database_interface->removePageDashboard();

        // Remove mijn rooster page
        $database_interface->removePageMijnRooster();

        // Remove inzien opdrachten page
        $database_interface->removePageInzienOpdrachten();

        // Remove indienen opdracht page
        $database_interface->removePageIndienenOpdrachten();

    }

}
?>