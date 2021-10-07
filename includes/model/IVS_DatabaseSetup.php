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

    private static function retrieveTables() {

        return array(

            $table_name_1 = 'ivs_mp_email_status', 
            $table_name_2 = 'ivs_mp_mailinglist',
            $table_name_3 = 'ivs_mp_meeloopdag',
            $table_name_4 = 'ivs_mp_opleiding'

        );

    }

    /**
     * createDBTables
     * 
     * Create the database tables for this plugin
    */
    public static function createDBTables() {

        global $wpdb;

        // Retrieve the table names
        $table_names = IVS_DatabaseSetup::retrieveTables();

        $charset_collate = $wpdb->get_charset_collate();

        // Setup the SQL query for table creations
        $sql = "CREATE TABLE $table_names[3] (
            opleiding_id int(10) NOT NULL AUTO_INCREMENT,
            naam_opleiding varchar(255) NOT NULL,
            PRIMARY KEY  (opleiding_id)
            ) $charset_collate;

            CREATE TABLE $table_names[0] (
            status_id int(1) NOT NULL AUTO_INCREMENT,
            status varchar(255) NOT NULL,
            PRIMARY KEY  (status_id)
            ) $charset_collate;

            CREATE TABLE $table_names[2] (
            meeloopdag_id int(10) NOT NULL AUTO_INCREMENT,
            fk_opleiding_id int(10) NOT NULL,
            meeloopdag_datum date NOT NULL,
            naam_docent varchar(255) NOT NULL,
            PRIMARY KEY  (meeloopdag_id),
            FOREIGN KEY  (fk_opleiding_id) REFERENCES $table_names[3] (opleiding_id)
            ) $charset_collate;

            CREATE TABLE $table_names[1] (
            meeloop_student_id int(10) NOT NULL AUTO_INCREMENT,
            fk_meeloopdag_id int(10) DEFAULT NULL,
            fk_mail_status_id int(1) NOT NULL DEFAULT 1,
            fk_opleiding_id int(10) DEFAULT NULL,
            meeloop_student_naam varchar(255) NOT NULL,
            meeloop_student_email varchar(255) NOT NULL,
            PRIMARY KEY  (meeloop_student_id),
            FOREIGN KEY  (fk_opleiding_id) REFERENCES $table_names[3] (opleiding_id),
            FOREIGN KEY  (fk_mail_status_id) REFERENCES $table_names[0] (status_id),
            FOREIGN KEY  (fk_meeloopdag_id) REFERENCES $table_names[2] (meeloopdag_id)
            ) $charset_collate;            
            ";


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

        // Retrieve the table names
        $table_names = IVS_DatabaseSetup::retrieveTables();

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

        // Retrieve the table names
        $table_names = IVS_DatabaseSetup::retrieveTables();

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