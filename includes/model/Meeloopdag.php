<?php 

/**
 * Class diagram indicates:
 * attributes: opleiding (string), datum (date) en naam_docent (string)
 * operations: registerMeeloopdag()
*/
class Meeloopdag {
    
    /**
     * getPostValues
     * 
     * Filter input and retrieve POST input params
    */
    public function getPostValues() {

        // Define the check for params
        $post_check_array = array(
            // Submit action
            'registreer-meeloopdag' => array( 'filter', FILTER_SANITIZE_STRING ),

            // Education name
            'opleiding' => array( 'filter' => FILTER_SANITIZE_STRING ),

            // Meeloopdag date
            'meeloopdag-datum' => array( 'filter' => FILTER_SANITIZE_STRING ),

            // Teacher name
            'naam-docent' => array( 'filter' => FILTER_SANITIZE_STRING )

        );

        // Get filtered input
        $inputs = filter_input_array( INPUT_POST, $post_check_array );

        // Return inputs
        return $inputs;

    }

    /**
     * getNrOfRegisteredMeeloopdagen
     * 
     * Get the number of registered meeloopdagen
     * 
     * @return int - integer of the registered meeloopdagen stored in database
    */
    public function getNrOfRegisteredMeeloopdagen() {

        global $wpdb;

        // Setup count query
        $count_query = "SELECT COUNT(*) AS nr FROM `ivs_mp_meeloopdag`";

        // Retrieve the integer of the amount of registered meeloopdagen
        $result = $wpdb->get_results( $count_query, OBJECT );

        // Return the number of registered meeloop dagen
        return $result[0]->nr;

    }

    /**
     * getMeeloopdagenList
     * 
     * Retrieve all the registered meeloopdagen from the database
     * @return array - Array containing all the registered meeloopdagen
    */
    public function getMeeloopdagenList() {

        global $wpdb;

        try {

            $return_array = array();

            $result_array = $wpdb->get_results( "SELECT `meeloopdag_id` AS id, `meeloopdag_datum` AS datum FROM ivs_mp_meeloopdag", OBJECT );

            // echo '<pre>'; 
            // echo __FILE__ . '   ' . __LINE__;
            // var_dump($result_array);
            // echo '</pre>';

            // For all database results
            foreach ($result_array as $idx => $meeloopdag_object) {

                // Declare new class variable
                $meeloopdag = new Meeloopdag();

                // Set all the info
                $meeloopdag->setId( $meeloopdag_object->id );
                $meeloopdag->setMeeloopdagDate( $meeloopdag_object->datum );

                // Add new object to array
                $return_array[] = $meeloopdag;

            }

            return $return_array;

            
        } catch(Exception $exc) {

        }

    }

    /**
     * setId
     * 
     * Set the ID of the opleiding
     * @param int - The id of the opleiding
    */
    public function setId( $id ) {

        if ( is_int( intval( $id ) ) ) {

            $this->id = $id;

        }

    }

    /**
     * setMeeloopdagDate
     * 
     * Set the date of the meeloopdag
     * @param string - The date of the meeloopdag
    */
    public function setMeeloopdagDate( $date ) {

        if( is_string( $date ) ) {
            $this->date = trim( $date );
        }

    }

    /**
     * getId
     * 
     * Get the ID of the opleiding
     * @return {int} - The Id of the opleiding
    */
    public function getId() {

        return $this->id;
    }

    /**
     * getMeeloopdagDate
     * 
     * Get the name of the opleiding
     * @return {string} - The name of the opleiding
    */
    public function getMeeloopdagDate() {

        return $this->date;
    }

    /**
     * registerMeeloopdag
     * 
     * This function registers the meeloopdag in the database
     * 
     * @global type $wpdb - The WordPress database class
     * @param {array} $input_array - The array containing the input date 
     * @return boolean TRUE on success OR FALSE
    */
    public function registerMeeloopdag($input_array) {

        try {
            
            if ( !isset( $input_array['opleiding']) OR !isset( $input_array['meeloopdag-datum'] ) ) {

                // Mandatory fields are missing
                throw new Exception( __("Missing mandatory fields") );

            }

            if ( (strlen( $input_array['opleiding'] ) < 1) OR (strlen( $input_array['meeloopdag-datum'] ) < 1 ) ) {
                
                // Mandatory fields are empty
                throw new Exception( __("Empty mandatory fields") );

            }

            global $wpdb;

            // Insert query
            $wpdb->query( $wpdb->prepare( "INSERT INTO ivs_mp_meeloopdag (fk_opleiding_id, meeloopdag_datum, naam_docent) VALUES (%d, %s, %s);",
            $input_array['opleiding'], $input_array['meeloopdag-datum'], $input_array['naam-docent']) );

            // Error? It's in there:
            if ( !empty( $wpdb->last_error ) ) {

                $this->last_error = $wpdb->last_error;
                
                return FALSE;

            }

        } catch( Exception $exc ) {

            // @todo: Add error handling
            echo '<pre>' . $exc->getTraceAsString() . '</pre>';

        }

        return TRUE;

    }

}
?>