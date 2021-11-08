<?php 
/**
 * IVS_DatabaseSetup
 * 
 * @author Yari Morcus <ymorcus@student.scalda.nl>
 * @package IVS_Meeloop_Portaal_Plugin
 * @subpackage CreateDatabaseTables
 * @since 0.1
*/
class IVS_DatabaseSetup { 

    /**
     * createDBTables
     * 
     * Create the database tables for this plugin
    */
    public static function createDBTables() {

        require_once IVS_MEELOOP_PORTAAL_PLUGIN_INCLUDES_MODEL_DIR . '/IVS_Databases.php';

        // Retrieve the SQL query for table creations
        $sql = IVS_Databases::setupTableQueries();

        // Include dbDelta
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        
        // Execute the query (create the tables)
        dbDelta( $sql );

    }
    /**
     * insertDBData
     * 
     * Insert the standard data for this plugin into the tables
    */
    public static function insertDBData() {

        global $wpdb;

        // Include the IVS_Databases class
        require_once IVS_MEELOOP_PORTAAL_PLUGIN_INCLUDES_MODEL_DIR . '/IVS_Databases.php';

        // Retrieve the table names
        $table_names = IVS_Databases::retrieveTables();

        // Insert first row in table 'ivs_mp_email_status'
        $wpdb->insert(
            $table_names[0],
            array(
                'status_id' => 1,
                'status' => 'Niet gereageerd'
            )
        );

        // Insert second row in table 'ivs_mp_email_status'
        $wpdb->insert(
            $table_names[0],
            array(
                'status_id' => 2,
                'status' => 'Geregistreerd'
            )
        );
        
    }

    /**
     * removeDBTables
     * 
     * Remove the automatically created tables when plugin is deactivated
    */
    public static function removeDBTables() {

        global $wpdb;

        // Include the IVS_Databases class
        require_once IVS_MEELOOP_PORTAAL_PLUGIN_INCLUDES_MODEL_DIR . '/IVS_Databases.php';

        // Retrieve the table names
        $table_names = IVS_Databases::retrieveTables();

        // Loop over each $table
        foreach( $table_names as $table ) {

            // Delete query
            $query = "DROP TABLE IF EXISTS $table";

            // Bypass queries in order for all tables to be deleted
            // If not supplied, table 'ivs_mp_email_status' won't be deleted because of foreign key referencing this table
            $disable_foreign_key_check = "SET foreign_key_checks = 0;";
            $enable_foreign_key_check = "SET foreign_key_checks = 1;";

            // Disable the foreign key check
            $wpdb->query ( $disable_foreign_key_check );

            // Delete tables
            $wpdb->query( $query );

            // Enable foreign key check to maintain integrity of database
            $wpdb->query( $enable_foreign_key_check );

        }
        
    }

}

?>