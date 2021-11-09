<?php 
/**
 * Class contains the entire Database SQL query for database setup,
 * when plugin is activated by an Administrator within WordPress
 * 
 * @package IVS_Databases
 * @author Yari Morcus <ymorcus@student.scalda.nl>
 * @version 0.1
 * @since 7.4.16
*/
class IVS_Databases {

    /**
     * retrieveTables
     * 
     * Retrieve all table names for creation
     * @return array - Array containing the table names
    */
    public static function retrieveTables() {

        return array(

            $table_name_1 = 'ivs_mp_email_status', 
            $table_name_2 = 'ivs_mp_meeloopstudent',
            $table_name_3 = 'ivs_mp_meeloopdag',
            $table_name_4 = 'ivs_mp_opleiding',
            $table_name_5 = 'ivs_mp_rooster'

        );

    }

    /**
     * setupTableQueries
     * 
     * Setup the SQL query set, used for the creation of the tables
     * 
     * @return string - The string containing the entire SQL query set
    */
    public static function setupTableQueries() {
    
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        // Retrieve the table names
        $table_names = IVS_Databases::retrieveTables();

        // Setup the SQL queries for table creations
        $table_queries = "CREATE TABLE $table_names[3] (
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
    
                CREATE TABLE $table_names[4] (
                    rooster_id int(10) NOT NULL AUTO_INCREMENT,
                    fk_meeloopdag_id int(10) NOT NULL,
                    taaknaam varchar(68) NOT NULL,
                    starttijd varchar(255) NOT NULL,
                    eindtijd varchar(255) NOT NULL,
                    PRIMARY KEY  (rooster_id),
                    FOREIGN KEY  (fk_meeloopdag_id) REFERENCES $table_names[2] (meeloopdag_id)
                ) $charset_collate;          
                ";

                return $table_queries;
    
    }
    
}



?>