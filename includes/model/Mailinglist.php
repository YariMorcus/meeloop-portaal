<?php 
/**
 * Class diagram indicates:
 * • naam_meeloop_student
 * • email_meeloop_student
 * • email_status
 * addMeeloopstudentToMailinglist
 * removeMeeloopstudentFromMailinglist
*/
class Mailinglist {

    // Declare and initialize class properties
    public $meeloop_student_id = 0;
    public $meeloopdag_id = 0;
    public $email_status_id = 0;
    public $naam_meeloop_student = '';
    public $email_meeloop_student = '';
    public $email_status = '';

    /**
     * getPostValues
     * 
     * Filter input and retrieve POST input params
     * 
     * @return {array} - containing known POST input fields
    */
    public function getPostValues() {

        // filters
        $post_check_array = array(

            // registreer-meeloop-student (submit action)
            'registreer-meeloop-student' => array( 'filter', FILTER_SANITIZE_STRING ),

            // meeloopdag-meeloop-student field
            'meeloopdag-meeloop-student' => array( 'filter', FILTER_SANITIZE_NUMBER_INT ),

            // naam-meeloop-student field
            'naam-meeloop-student' => array( 'filter', FILTER_SANITIZE_STRING ),

            // email-meeloop-student field
            'email-meeloop-student' => array( 'filter', FILTER_SANITIZE_EMAIL ),

            'bevestig-actie' => array( 'filter', FILTER_SANITIZE_STRING ),

            'actie' => array( 'filter', FILTER_SANITIZE_STRING )
            
        );

        // Get filtered input
        $inputs = filter_input_array( INPUT_POST, $post_check_array );

        // If user has selected some checkboxes, filter those as well
        if ( isset( $_POST['checkbox-selecteer-individu'] ) ) {

            // Filter the checkbox array individually
            $input_checkbox_array = filter_input( INPUT_POST, 'checkbox-selecteer-individu', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

            // Merge two filtered inputs
            $inputs = array_merge( $inputs, array( $input_checkbox_array ) );

        }

        // Return inputs
        return $inputs;

    }

    /**
     * getGetValues
     * 
     * Filter input and retrieve GET input params
     * @return {array} - containing known GET input fields
    */
    public function getGetValues() {

        // Define the check for params
        $get_check_array = array(

            // Actie parameter
            'actie' => array( 'filter', FILTER_SANITIZE_STRING ), 

            // ID of current meeloop student
            'id' => array( 'filter', FILTER_VALIDATE_INT ),

        );

        // Get filtered input
        $inputs = filter_input_array( INPUT_GET, $get_check_array );

        // If user has selected some checkboxes, filter those as well
        if ( isset( $_GET['checkbox-selecteer-individu'] ) ) {

            // Filter the checkbox array individually
            $input_checkbox_array = filter_input( INPUT_GET, 'checkbox-selecteer-individu', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

            // Merge two filtered inputs
            $inputs = array_merge( $inputs, array( $input_checkbox_array ) );

        }


        // Return the input
        return $inputs;

    }

    /**
     * handlePostAction
     * 
     * Handle the post action to act upon:
     * • deletion (multiple users)
     * • sending invitation (multiple users)
     * 
     * @param 
    */
    public function handlePostAction( $post_array ) {

        // echo '<pre>';
        // echo __FILE__ . __LINE__ . '<br>';
        // var_dump( $post_array );
        // echo '</pre>'; 

        $action = '';

        switch( $post_array['actie'] ) {
            case 'verwijderen-meeloop-student':

                // Loop over the array that holds the user ID's
                // User ID's are stored in named key 0
                foreach( $post_array[0] as $user_id ) {

                    // Pass each checkbox value in database query for deletion
                    $this->removeMeeloopstudentFromMailinglist( null, $user_id );

                }

                $action = 'verwijderen-meeloop-student';

                break;
            case 'versturen-uitnodiging':

                    // Send the selected emails an invitation
                    $result = $this->sendEmailToMeeloopstudent( $post_array );

                    /**
                       If e-mails have been send succesfully, inform the frontend the current action
                       array with TRUE or FALSE has been used for the following reason:
                       wp_mail() function returns TRUE on success or FALSE otherwise
                       In both situations, a different message needs to be shown to the user, BUT both messages
                       are related to 'versturen-uitnodiging'
                    */
                     
                    $action = $result ? array('versturen-uitnodiging', TRUE) : array('versturen-uitnodiging', FALSE);                    

                break;
            default:
                // Do something
        }

        // Return the action to provide the user with the right message
        return $action;
    }

    /**
     * handleGetAction
     * 
     * Handle the get action to act upon: 
     * - delete
     * 
     * @param {array} - Array of get vars and values
     * @return {string} - The action provided by the $_GET array
     * 
    */
    public function handleGetAction( $get_array ) {

        $action = '';

        switch( $get_array['actie'] ) {
            case 'verwijderen-meeloop-student':

                var_dump($get_array);
                // Loop over all $get_array items
                foreach($get_array as $item) {

                    // Loop over the checkbox items
                    if ( is_array( $item ) ) {
                        
                        // Loop over the checkbox values
                        foreach ($item as $checkbox_value) {

                            // Pass each checkbox value in database query for deletion
                            $this->removeMeeloopstudentFromMailinglist( null, $checkbox_value);

                        }
                    }
                        
                }

                    $action = "verwijderen-meeloop-student";
                break;

            case 'verwijderen':
                // Delete current meeloop student
                if ( !is_null( $get_array['id'] ) ) {

                    $this->removeMeeloopstudentFromMailinglist( $get_array, null );

                }

                $action = 'verwijderen';
                break;

            case 'versturen-uitnodiging':

                    // Send the selected emails an invitation
                    $result = $this->sendEmailToMeeloopstudent( $get_array );

                    // If e-mails have been send succesfully, inform the frontend the current action
                    $action = $result ? array('versturen-uitnodiging', TRUE) : array('versturen-uitnodiging', FALSE);
                break;

            default:
                break;
        }

        return $action;

    }

    /**
     * removeMeeloopstudentFromMailinglist
     * 
     * Delete the current meeloop student from mailinglist (database)
     * 
     * @global $wpdb - The WordPress database class
     * @param {array} - $input_array containing delete id
     * @param {string} - $checkbox_input_value containing delete id (checkboxes)
     * @return boolean TRUE on success or FALSE
    */
    public function removeMeeloopstudentFromMailinglist( $input_array, $checkbox_input_value ) {

        try {

            // Check input ID
            if ( !isset( $input_array['id']) && !isset( $checkbox_input_value ) ) {

                throw new Exception( __('Missing mandatory fields') );

            }

            $user_id = null;

            // If user selected one meeloop student for deletion
            if ( isset( $input_array['id'] ) ) {
                $user_id = $input_array['id'];
            }

            // If user selected multiple meeloop students for deletion
            if ( isset( $checkbox_input_value ) ) {
                $user_id = $checkbox_input_value;
            }

            global $wpdb;

            $wpdb->delete( 
                'ivs_mp_mailinglist', 
                array( 'meeloop_student_id' => $user_id ),
                array( '%d' )
            );

            // Error? It's in there
            if ( !empty( $wpdb->last_error ) ) {

                throw new Exception( $wpdb->last_error );

            }

        } catch(Exception $exc) {
            // @todo: Add error handling
            echo '<pre>';
            $this->last_error = $exc->getMessage();
            echo $exc->getTraceAsString();
            echo $exc->getMessage();
            echo '</pre>';

        }

        return TRUE;

    }

    /**
     * getMeeloopstudentenList
     * 
     * Retrieve all the registered meeloopstudenten from the database
     * 
     * @global $wpdb - The WordPress database class
    */
    public function getMeeloopstudentenList() {

        global $wpdb;

        $return_array = array();

        // Execute the SQL query, and store results in $result_array
        $result_array = $wpdb->get_results( "SELECT * FROM ivs_mp_mailinglist ORDER BY meeloop_student_id", ARRAY_A );

        // For all database results:

        foreach( $result_array as $idx => $array ) {

            // New object
            $meeloop_student = new Mailinglist();

            // Set all info
            $meeloop_student->setID( $array['meeloop_student_id'] );
            $meeloop_student->setMeeloopdagID( $array['fk_meeloopdag_id'] );
            $meeloop_student->setName( $array['meeloop_student_naam'] );
            $meeloop_student->setEmail( $array['meeloop_student_email'] );
            $meeloop_student->setEmailStatusID( $array['fk_mail_status_id'] );

            // Add new object to array
            $return_array[] = $meeloop_student;
            
        }

        return $return_array;

    }

    /**
     * setID
     * 
     * Set the ID of the meeloop student
     * @param int - The ID of the meeloop student
    */
    public function setID( $meeloop_student_ID ) {

        if ( is_int( intval( $meeloop_student_ID ) ) ) {

            $this->meeloop_student_id = $meeloop_student_ID;

        }

    }

    /**
     * setMeeloopdagID
     * 
     * Set the ID of the meeloopdag for the meeloop student
     * @param int - The ID of the meeloopdag
    */
    public function setMeeloopdagID( $id ) {

        if( is_int( intval( $id ) ) ) {

            $this->meeloopdag_id = $id;

        }

    }

    /**
     * setName
     * 
     * Set the name of the meeloop student
     * @param {string} - The name of the meeloop student
    */
    public function setName( $meeloop_student_naam ) {

        if ( is_string( $meeloop_student_naam ) ) {

            $this->naam_meeloop_student = trim( $meeloop_student_naam );

        }

    }

    /**
     * setEmail
     * 
     * Set the email of the meeloop student
     * @param {string} - The email of the meeloop student
    */
    public function setEmail( $meeloop_student_email ) {

        if ( is_string( $meeloop_student_email ) ) {

            $this->email_meeloop_student = trim( $meeloop_student_email );

        }

    }

    /**
     * setEmailStatus
     * 
     * Set the email status of the meeloop student
     * @param int - The email status id of the meeloop student
    */
    public function setEmailStatusID( $email_status_id ) {

        if ( is_int( intval( $email_status_id ) ) ) {

            $this->email_status_id = $email_status_id;

        }

    }

    /**
     * getID
     * 
     * Get the ID of the meeloop student
    */
    public function getID() {

        return $this->meeloop_student_id;

    }

    /**
     * getMeeloopdagID
     * 
     * Get the ID of the meeloopdag (to which the meeloop student has been registered)
    */
    public function getMeeloopdagID() {

        return $this->meeloopdag_id;

    }


    /**
     * getName
     * 
     * Get the name of the meeloop student
     * @return string - The name of the meeloop student
    */
    public function getName() {
        return $this->naam_meeloop_student;
    }

    /**
     * getEmail
     * 
     * Get the email of the meeloop student
     * @return string - The email of the meeloop student
    */
    public function getEmail() {
        return $this->email_meeloop_student;
    }

    /**
     * getEmailStatusID
     * 
     * Get the email status of the meeloop student
     * @return int - The status ID of the email
    */
    public function getEmailStatusID() {
        return $this->email_status_id;
    }

    /**
     * getMeeloopdagDate
     * 
     * Get the meeloopdag date from the database
     * (conversion from ID to date)
     *
     * @param int - ID of the meeloopdag
     * @return string - Meeloopdag date
    */
    public function getMeeloopdagDate( $meeloopdag_id ) {

        try {

            global $wpdb;

            // Setup the query
            $query = "SELECT meeloopdag_datum FROM `ivs_mp_meeloopdag` WHERE meeloopdag_id = %d";

            // Prepare and execute the query, and store it in $meeloopdag_date
            // $meeloopdag_date is an stdClass object
            $meeloopdag_date = $wpdb->get_row( $wpdb->prepare( $query, $meeloopdag_id ), OBJECT );

            // Return the meeloopdag date
            return $meeloopdag_date->meeloopdag_datum;

        } catch(Exception $exc) {
            
            $this->last_error = $exc->getMessage();
            echo $exc->getMessage();

        }

    }

    /**
     * getEmailStatusLabel
     * 
     * Get the email status label from the database 
     * (conversion from ID to label)
     * @param int - The status ID of the email
     * @return string - The email status label
    */
    public function getEmailStatusLabel( $email_status_id ) {

        try {

            global $wpdb;

            // Setup the query
            $query = "SELECT status FROM ivs_mp_email_status WHERE status_id = %s";

            // Prepare and execute the query, and store it in $result
            // Result is an associative array
            $result = $wpdb->get_row( $wpdb->prepare( $query, $email_status_id ), ARRAY_A );

            // Return the status of the mail
            return $result['status'];

        } catch(Exception $exc) {

            $this->last_error = $exc->getMessage();
            echo $exc->getMessage();

        }

        return $this->email_status_id;
    }

    /**
     * addMeeloopstudentToMailinglist
     * 
     * Save the meeloopstudent in database, so user can be shown in mailinglist
     * 
     * @global type $wpdb - The WordPress database class
     * @param {array} $input_array - Array containing the data
     * @return boolean TRUE on success or FALSE
     * if { !isset( $input_array['naam-meeloop-student'] ) OR !isset( $input_array['email-meeloop-student']) }, 
     * Check if variable has NOT been declared and or null
    */
    public function addMeeloopstudentToMailinglist($input_array) {

        try {

            if ( !isset( $input_array['meeloopdag-meeloop-student'] ) OR !isset( $input_array['naam-meeloop-student'] ) OR !isset( $input_array['email-meeloop-student']) ) {
                
                throw new Exception( __( 'Missing mandatory fields' ) );

            }

            if ( ( strlen( $input_array['meeloopdag-meeloop-student'] ) < 1 ) OR ( strlen( $input_array['naam-meeloop-student'] ) < 1 ) OR ( strlen( $input_array['email-meeloop-student'] ) < 1 ) ) {

                throw new Exception( __( "Empty mandatory fields" ) );

            }

            global $wpdb;

            // Insert query
            $wpdb->query( $wpdb->prepare( "INSERT INTO ivs_mp_mailinglist (fk_meeloopdag_id, meeloop_student_naam, meeloop_student_email) VALUES (%s, %s, %s);", $input_array['meeloopdag-meeloop-student'], $input_array['naam-meeloop-student'], $input_array['email-meeloop-student'] ) );


            // Error? It's in there
            if ( !empty( $wpdb->last_error ) ) {

                $this->last_error = $wpdb->last_error;

                return FALSE;

            }

        } catch(Exception $exc) {

            echo $exc->getMessage();
        }

        return TRUE;

    }

    /**
     * getEmailFromDatabase
     * 
     * Get the meeloop student emails from database
     * @global $wpdb - The WordPress database class
     * @param {array} - The input array
     * @return {array} - The e-mails of the meeloop students
    */
    public function getEmailFromDatabase( $user_id_list ) {

        // If user ID list has NOT been supplied, return error
        if ( !isset( $user_id_list ) ) {

            throw new Exception( __("An user id list has NOT been supplied") );

        }

        global $wpdb;

        // Setup query
        $query = "SELECT `meeloop_student_email` FROM `ivs_mp_mailinglist` WHERE meeloop_student_id = %s";

        // Setup array for e-mails list
        $emails_list = array();
        
        forEach( $user_id_list as $user_id ) {
            
            // Retrieve the raw data from the database 
            array_push( $emails_list, $wpdb->get_results( $wpdb->prepare( $query, $user_id ) ) );

        }

        // Foreach is used to retrieve and clean the $emails_list with ONLY the e-mails
        forEach( $emails_list as $key => $value ) {

            // Retrieve the e-mail of the meeloop student
           array_push( $emails_list, $value[0]->meeloop_student_email );

           // Remove the raw data from the database
           if ( is_array( $emails_list[$key] ) ) {
               unset( $emails_list[$key] );
           }

        }

        // Return e-mails list
        return $emails_list;

    }

    /**
     * sendEmailToMeeloopstudent
     * 
     * Send the selected meeloop student(en) an invitation e-mail
     * 
    */
    public function sendEmailToMeeloopstudent( $input_array ) {

        try {

            if ( !isset( $input_array[0] ) ) {

                throw new Exception( __("No user ID's have been supplied") );

            }

            // List of e-mails
            $emails_list = $this->getEmailFromDatabase( $input_array[0] );

            // Define the subject
            $subject = '[Hier onderwerp mailinglist]';

            // Define the message
            $message = "Dit is de <b>body</b> van de e-mail.
            Als 'body' dikgedrukt is, dan is de Content-Type juist ingesteld.
            
            <h3>Zie je deze e-mail?</h3>
            Dan mag je aankruisen binnen de testprocedure dat deze stap werkt.";

            // Define the headers
            $headers = array( 'Content-Type: text/html; charset=UTF-8' );

            $mail = wp_mail( $emails_list, $subject, $message, $headers );

            // If mail has been send succesfully
            if ($mail) {
                
                return TRUE;

            }



        } catch(Exception $exc) {

            $this->last_error = $wpdb->last_error;
            echo $exc->getMessage();
        }

    }

}
?>