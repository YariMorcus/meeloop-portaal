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

            // Update button
            'update-opleidingsnaam' => array( 'filter', FILTER_SANITIZE_STRING ),

            // Id opleidingsnaam (updaten)
            'id-opleidingsnaam' => array( 'filter', FILTER_SANITIZE_STRING ),

            // Update opleidingsnaam
            'update-input-opleidingsnaam' => array( 'filter', FILTER_SANITIZE_STRING ),

            // Toevoegen opleidingsnaam
            'input-opleidingsnaam' => array( 'filter', FILTER_SANITIZE_STRING )
        );

        // Get filtered input
        $inputs = filter_input_array( INPUT_POST, $post_check_array );

        return $inputs;

    }

    /**
     * getGetValues
     * Filter input and retrieve GET input params
     * 
     * @return array containing known GET input fields
    */
    public function getGetValues() {

        $get_check_array = array(

            // Update action 
            'action' => array( 'filter', FILTER_SANITIZE_STRING ),

            // ID of the education name
            'id' => array( 'filter', FILTER_VALIDATE_INT )

        );

        // Get filtered input
        $filtered_input = filter_input_array( INPUT_GET, $get_check_array );

        return $filtered_input;

    }

    /**
     * Check the action, and perform action on:
     * -update
     * @param type Array - All get vars and values
    */
    public function handleGetAction($get_array) {

        $action = '';

        $action_value = $get_array['action'];

        switch( $action_value ) {
            case 'update':
                if ( !is_null( $get_array['id'] ) ) {
                    $action = $action_value;
                }
                break;
            default:
                // Oops 
                break;
        }

        return $action;

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
     * @global wpdb - The WordPress Database Interface
     * @param type $input_array - post_array
     * @return boolean TRUE on success, otherwise FALSE
    */
    public function update($input_array) {

        try {

            $array_fields = array( 'id-opleidingsnaam', 'update-input-opleidingsnaam' );
            $table_fields = array( 'opleiding_id', 'naam_opleiding' );
            $data_array = array();

            // Check if the fields are empty
            foreach( $array_fields as $field ) {
                if ( empty( $input_array[$field] ) ) {
                    throw new Exception( __( 'Fields are mandatory' ) );
                }

                // Add data_array
                $data_array[] = $input_array[$field];
            }

            global $wpdb;

            // Update query
            // $wpdb->query( $wpdb->prepare( "UPDATE " . $this->getTableName() . " SET `naam_opleiding` = '%s' WHERE `ivs_mp_opleiding`.`opleiding_id` = %d;", $input_array['update-input-opleidingsnaam'], $input_array['id-opleidingsnaam'] ) );

            $wpdb->update(
                $this->getTableName(), 
                $this->getTableDataArray( $data_array ),
                array( 'opleiding_id' => $input_array['id-opleidingsnaam'] ), // WHERE
                array( '%s' ),                                          // Data format
                array( '%d' )
            );

        } catch(Exception $exc) {
            // @todo: add error handling
            echo '<pre>' . $exc->getTraceAsString() . '</pre>';
            $this->last_error = $exc->getMessage();

            return FALSE;
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
    
        if ( is_int( intval( $opleiding_ID ) ) ) {
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

    /**
     * 
     * @return type String - Table name
    */
    private function getTableName() {
        
        return $table = 'ivs_mp_opleiding';
    }

    /**
     * The function takes the input data array and changes the indexes
     * to the column names
     * In case of update or insert action
     * 
     * @param type $input_data_array - data array (id-opleidingsnaam, update-input-opleidingsnaam)
     * @param type $action = update | insert
     * 
     * @return type array with column index and values OR FALSE
    */
    private function getTableDataArray($input_data_array, $action = '') {

        $keys = $this->getTableColumnNames( $this->getTableName() );

        // Get data array with table colum names
        // NULL if columns and data does not match in count
        //
        // NOTE: Order of the fields shall be the same for both!
        $table_data = array_combine( $keys, $input_data_array );

        switch( $action ) {
            case 'update': // Intended fall through
            case 'insert':

                // Remove the index -> is primary key and can therefore NOT be changed
                if ( !empty( $table_data ) ) {
                    echo 'zie je dit?';

                    unset( $table_data[ 'opleiding_id' ] );

                }

                break;
            default:
                // Oops
        }

        echo '<pre>';
        var_dump($table_data);
        echo '</pre>';
        return $table_data;

    }

    /**
     * Get the colum names of the specified table
     * @global wpdb - The WordPress Database Interface
     * @return type $table
    */
    private function getTableColumnNames($table) {

        global $wpdb;

        try {

            $result_array = $wpdb->get_results( "SELECT `COLUMN_NAME` "."FROM INFORMATION_SCHEMA.COLUMNS"." WHERE `TABLE_SCHEMA` = '" . DB_NAME . "' AND TABLE_NAME = '" . $this->getTableName() . "'", ARRAY_A );

            $keys = array();

            foreach( $result_array as $idx => $row ) {
                $keys[$idx] = $row['COLUMN_NAME'];
            }

            return $keys;

        } catch(Exception $exc) {

            // @Todo: add error handling
            echo '<pre>' . $exc->getTraceAsString() . '</pre>';
            $this->last_error = $exc->getMessage();
            return FALSE;

        }

    }

}
?>