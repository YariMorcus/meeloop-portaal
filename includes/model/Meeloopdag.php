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
     * getOpleidingen
     * 
     * Retrieve all the opleidingen from the database
     * @return $return_array - Array containing all the registered educations
    */
    public function getOpleidingenList() {

        global $wpdb;

        $return_array = array();

        $result_array = $wpdb->get_results( "SELECT * FROM ivs_mp_opleiding ORDER BY opleiding_id", ARRAY_A );

        
        // echo '<pre>';

        // echo __FILE__.__LINE__.'<br />';
        // var_dump($result_array);
        // echo '</pre>';

        // For all database results
        foreach( $result_array as $idx => $array ) {
            
            // Create new object
            $meeloopdag = new Meeloopdag();

            // Set all info
            $meeloopdag->setId( $array['opleiding_id'] );
            $meeloopdag->setEducationName( $array['naam_opleiding'] );

            // Add new object to return_array
            $return_array[] = $meeloopdag;

        }

        return $return_array;

    }

    /**
     * setId
     * 
     * Set the ID of the opleiding
     * @param {int} - The id of the opleiding
    */
    public function setId( $id ) {

        if ( is_int( intval( $id ) ) ) {

            $this->id = $id;

        }

    }

    /**
     * setEducationName
     * 
     * Set the name of the opleiding
     * @param {int} - The id of the opleiding
    */
    public function setEducationName( $name ) {

        if ( is_string( $name ) ) {

            $this->education_name = trim( $name );

        }

    }

    /**
     * getId
     * 
     * Get the ID of the opleiding
     * @return {int} - The Id of the opleiding
    */
    public function getId() {

        return $this-id;
    }

    /**
     * getEducationName
     * 
     * Get the name of the opleiding
     * @return {string} - The name of the opleiding
    */
    public function getEducationName() {

        return $this-education_name;
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