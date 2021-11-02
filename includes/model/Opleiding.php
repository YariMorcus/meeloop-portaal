<?php 
/**
 * Class contains all the functionality that is associated with an education
 * 
 * @author Yari Morcus
*/
class Opleiding {

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
        $post_inputs = filter_input_array( INPUT_POST, $post_check_array );

        return $post_inputs;

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
        $get_inputs = filter_input_array( INPUT_GET, $get_check_array );

        return $get_inputs;

    }

    /**
     * handleGetAction
     * 
     * Check if user wants to update the education name.
     * If yes, and education name id is known, return action to show form update field to user
     * 
     * @param array - All get vars and values
     * if (( !is_null( $get_array['id'] )), education name id supplied? Store action 'update' in $action
    */
    public function handleGetAction($get_array) {

        // Placeholder to store current action of user
        $action = '';

        // Store action supplied in $get_array
        $action_value = $get_array['action'];

        switch( $action_value ) {
            case 'update':
                if ( !is_null( $get_array['id'] ) ) {
                    $action = $action_value;
                }
                break;
            default:
                break;
        }

        return $action;

    }

    /**
     * save
     * 
     * Save new education name in database
     * 
     * @global type $wpdb The WordPress Database Interface
     * @param type $input_array containing insert data
     * @return boolean TRUE on success OR FALSE
     * if ( !isset( $input_array['input-opleidingsnaam'] ) ) - If user forgot to fill in mandatory field, throw an error 
     * if ( strlen($input_array['input-opleidingsnaam']) < 1 ) - If user forgot to fill in mandatory field, throw an error (extra check)
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
     * update
     * 
     * Save new (updated) education name in database
     * 
     * @global wpdb - The WordPress Database Interface
     * @param type $input_array - post_array
     * @return boolean TRUE on success, otherwise FALSE
     * if (empty( $input_array[$field] )) - If user forgot to fill in mandatory field, throw an error 
     * (prevent insertion of empty education name)
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

            $wpdb->update(
                $this->getTableName(), 
                $this->getTableDataArray( $data_array ),
                array( 'opleiding_id' => $input_array['id-opleidingsnaam'] ), // WHERE
                array( '%s' ),                                          // Data format
                array( '%d' )
            );

        } catch(Exception $exc) {

            $this->last_error = $exc->getMessage();
            echo $exc->getMessage();

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
     * @return array - Array of objects, containing the data
    */
    public function getEducationList() {

        global $wpdb;

        // Array to store newly created objects
        $return_array = array();

        // Retrieve the results from the database
        $result_array = $wpdb->get_results( "SELECT * FROM ivs_mp_opleiding ORDER BY opleiding_id", ARRAY_A );

        // For all database results:
        foreach( $result_array as $idx => $array) {

            // Declare new object
            $opleiding = new Opleiding();

            // Set all the info
            $opleiding->setID( $array['opleiding_id'] );
            $opleiding->setNaam( $array['naam_opleiding'] );

            // Add new object to array
            $return_array[] = $opleiding;

        }

        return $return_array;

    }

    /**
     * setID
     * 
     * Store ID of education name in current object
     * 
     * @param int - ID of the education name
     * if (is_int( intval( $opleiding_ID ) )) - get integer value of variable, and check if it is an integer
    */
    public function setID($opleiding_ID) {
    
        if ( is_int( intval( $opleiding_ID ) ) ) {
            $this->opleiding_ID = $opleiding_ID;
        }

    }

    /**
     * setNaam
     * 
     * Store education name in current object
     * 
     * @param string - Education name
     * if( is_string( $naam_opleiding ) ) - check if supplied education name is string
    */
    public function setNaam($naam_opleiding) {

        if ( is_string( $naam_opleiding ) ) {
            $this->naam_opleiding = trim($naam_opleiding);
        }

    }

    /**
     * getID
     * 
     * Get ID of education name of current object
     * 
     * @return int - The ID of the education name
    */
    public function getID() {
        return $this->opleiding_ID;
    }

    /**
     * getNaam
     * 
     * Get education name of current object
     * 
     * @return string - The education name
    */
    public function getNaam() {
        return $this->naam_opleiding;
    }

    /**
     * getTableName
     * 
     * Get table name to prevent writing mistakes on several places
     * 
     * @return string - Table name
    */
    private function getTableName() {
        
        return $table = 'ivs_mp_opleiding';
    }

    /**
     * getTableDataArray
     * 
     * The function takes the input data array and changes the indexes
     * to the column names
     * In case of update or insert action
     * 
     * @param type $input_data_array - data array (id-opleidingsnaam, update-input-opleidingsnaam)
     * @param type $action = update | insert
     * 
     * @return array with column index and values OR FALSE
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

                    unset( $table_data[ 'opleiding_id' ] );

                }

                break;
            default:
                // Oops
        }
        
        return $table_data;

    }

    /**
     * getTableColumnNames
     * 
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

            $this->last_error = $exc->getMessage();
            echo $exc->getMessage();
            return FALSE;

        }

    }

}
?>