<?php 
/**
 * Class contains all the functionality that is associated a rooster (in English: schedule)
 * 
 * @author Yari Morcus
*/
class Rooster {

    // Declare and initialize class properties
    private $meeloopdag = '';
    private $taaknaam = '';
    private $starttijd = '';
    private $eindtijd = '';
    
    /**
     * getPostValues
     * 
     * Filter input and retrieve POST input params
     * @return array containing known POST input fields
    */
    public function getPostValues() {

        $post_check_array = array(

            // Submit button
            'toevoegen-taak' => array( 'filter' => FILTER_SANITIZE_STRING ),

            // Meeloopdag date
            'select-meeloopdag' => array( 'filter' => FILTER_SANITIZE_STRING ),

            // Task name
            'input-taaknaam' => array( 'filter' => FILTER_SANITIZE_STRING ),

            // Start time
            'input-starttijd' => array( 'filter' => FILTER_SANITIZE_STRING ),

            // End time
            'input-eindtijd' => array( 'filter' => FILTER_SANITIZE_STRING )
        );

        // Get filtered input
        $post_inputs = filter_input_array( INPUT_POST, $post_check_array );

        // Return filtered input
        return $post_inputs;

    }

    /**
     * getNrOfRegisteredSchedules
     * 
     * Get the number of registered roosters (schedules)
     * @return int - The number of registered roosters
    */
    public function getNrOfRegisteredSchedules() {

        global $wpdb;

        // Setup count query
        $count_query = "SELECT COUNT(*) AS nr FROM `ivs_mp_rooster`";

        // Get the object that holds the number of registered roosters (schedules)
        $result_obj = $wpdb->get_results( $count_query, OBJECT );

        // Return the number
        return $result_obj[0]->nr;
    }

    /**
     * getscheduleOfMeeloopdag
     * 
     * Get the rooster of a specific meeloopdag, based on the given meeloopdag id
     * @param string - The ID of the meeloopdag
     * @return object - Object containing all the data associated with a specific meeloopdag
    */
    public function getScheduleOfMeeloopdag($meeloopdag_id) {

        try {

            // Check if ID has been given and NOT null
            if ( !isset( $meeloopdag_id ) ) {

                // Throw new exception message for user
                throw new Exception( __( "ID of meeloopdag has not been given, or is equal to null." ) );

            }

            global $wpdb;

            // Setup query
            $select_query = "SELECT `taaknaam`, `starttijd`, `eindtijd` FROM `ivs_mp_rooster` WHERE `fk_meeloopdag_id`= %s";

            $rooster_meeloopdag = $wpdb->get_results( $wpdb->prepare( $select_query, $meeloopdag_id ), OBJECT );

            if ( isset( $rooster_meeloopdag ) && is_array( $rooster_meeloopdag ) ) {
                
                return $rooster_meeloopdag;

            }

            // Error? It's in there
            if ( !empty( $wpdb->last_error ) ) {

                $this->last_error = $wpdb->last_error;
                return FALSE;

            }

        } catch(Exception $exc) {

            $this->last_error = $wpdb->last_error;
            $exc->getMessage();

        }


    }

    /**
     * saveSchedule
     * 
     * @global type $wpdb - The WordPress database interface
     * 
     * 
     * @param array - The post array containing the values (insert data)
     * @return boolean - boolean TRUE on success, otherwise FALSE
    */
    public function saveSchedule($input_array) {

        try {

            // Declare all input fields
            $input_fields = array(
                'toevoegen-taak',
                'select-meeloopdag',
                'input-taaknaam',
                'input-starttijd',
                'input-eindtijd'
            );
    
            // Loop over all input fields, and check if user has forgotten to fill in a required field
            // If yes, return boolean FALSE
            foreach( $input_fields as $field_name ) {
    
                // Check if mandatory fields are missing
                if( !isset( $input_array[ $field_name ] ) ) {

                    throw new Exception( __( 'Mandatory fields are missing.' ) );

                }

                // Check if mandatory fields are empty
                if ( strlen( $input_array[ $field_name ] ) < 1 ) {

                    throw new Exception( __( 'Empty mandatory fields.' ) );

                }
            }
    
            global $wpdb;
    
            // Setup insert query
            $query = "INSERT INTO `ivs_mp_rooster` (`fk_meeloopdag_id`, `taaknaam`, `starttijd`, `eindtijd`) VALUES ('%s', '%s', '%s', '%s');";
    
            // Insert the data into the ivs_mp_rooster table
            // $wpdb->prepare is used to prepare SQL query for safe execution
            $wpdb->query( $wpdb->prepare( 
                $query,                           // Insert query (with placeholders)
                // Part underneath will append the data to the query
                $input_array[ $input_fields[1] ], // meeloopdag date
                $input_array[ $input_fields[2] ], // taaknaam
                $input_array[ $input_fields[3] ], // starttijd
                $input_array[ $input_fields[4] ]  // eindtijd
                ) 
            );  

            // Error? It's in there
            if( !empty( $wpdb->last_error ) ) {
                $this->last_error = $wpdb->last_error;
                return FALSE;
            }
    
            return TRUE;
            
        } catch(Exception $exc) {

            $this->last_error = $wpdb->last_error;
            $exc->getMessage();      

        }

    }

}
?>