<?php 
/**
 * Class contains all the functionality that is associated with an education
 * 
 * @author Yari Morcus
*/
class Opleidingen {

    /**
     * getPostValues
     * Filter input and retrieve POST input params
     * 
     * @return array containing known POST input fields
    */
    public function getPostValues() {

        // Define the check for params
        $post_check_array = array(

            // Submit button
            'toevoegen-opleidingsnaam' => array( 'filter', FILTER_SANITIZE_STRING ),
            // Opleidingsnaam
            'input-opleidingsnaam' => array( 'filter', FILTER_SANITIZE_STRING )
        );

        // Get filtered input
        $inputs = filter_input_array( INPUT_POST, $post_check_array );

        return $inputs;

    }

    /**
     * 
     * @global type $wpdb The WordPress Database Interface
     * @param type $input_array containing insert data
     * @return boolean TRUE on success OR FALSE
    */
    public function save($input_array) {

        try {
            
            if ( !isset( $input_array['input-opleidingsnaam'] ) ) {
                // Mandatory fields are missing
                throw new Exception( __( "Missing mandatory fields." ) );
            }

            if ( strlen($input_array['input-opleidingsnaam']) < 1 ) {
                // Empty mandatory fields
                throw new Exception( __( "Empty mandatory fields." ) );
            }

            global $wpdb;

            // Insert query
            $wpdb->query( $wpdb->prepare( "INSERT INTO `ivs_mp_opleiding` (naam_opleiding) VALUES('%s');", $input_array['input-opleidingsnaam']) );

            // Error? It's in there
            if ( !empty( $wpdb->last_error ) ) {
                $this->last_error = $wpdb->last_error;
                return FALSE;
            }

        } catch(Exception $exc) {
            // @todo: add error handling
            echo '<pre>' . $exc->getTraceAsString() . '</pre>';
        }

        return TRUE;

    }

    /**
     * 
     * @global type $wpdb The WordPress Database Interface
     * @return int Integer of registered education stored in DB
    */
    public function getNrOfRegisteredEducation() {

        global $wpdb;

        // Setup the count query
        $count_query = "SELECT COUNT(*) AS nr FROM `" . "ivs_mp_opleiding`";

        // Retrieve the integer of the amount of rows
        $result = $wpdb->get_results( $count_query, ARRAY_A );

        // Return the integer
        return $result[0]['nr'];
    }

    /**
     * 
     * @global type $wpdb - The WordPress Database Interface
     * @return type $return_array - Array of objects, containing the data
    */
    public function getEducationList() {

        global $wpdb;

        // Array to store newly created objects
        $return_array = array();

        // Retrieve the results from the database
        $result_array = $wpdb->get_results( "SELECT * FROM ivs_mp_opleiding ORDER BY opleiding_id", ARRAY_A );

        // echo '<pre>'; 
        // echo __FILE__ . __LINE__ . '<br><br>';
        // var_dump($result_array);
        // echo '</pre>';

        // For all database results:
        foreach( $result_array as $idx => $array) {

            // Declare new object
            $opleiding = new Opleidingen();

            // Set all the info
            $opleiding->setID( $array['opleiding_id'] );
            $opleiding->setNaam( $array['naam_opleiding'] );

            // Add new object to array
            $return_array[] = $opleiding;

        }

        return $return_array;

    }

    /**
     * @param type Int - ID of the education name
    */
    public function setID($opleiding_ID) {
    
        if ( is_int( $opleiding_ID ) ) {
            $this->opleiding_ID = $opleiding_ID;
        }

    }

    /**
     * @param type String - Education name
    */
    public function setNaam($naam_opleiding) {

        if ( is_string( $naam_opleiding ) ) {
            $this->naam_opleiding = trim($naam_opleiding);
        }

    }

    /**
     * @return type Int - The ID of the education name
    */
    public function getID() {
        return $this->opleiding_ID;
    }

    /**
     * @return type String - The education name
    */
    public function getNaam() {
        return $this->naam_opleiding;
    }

}
?>