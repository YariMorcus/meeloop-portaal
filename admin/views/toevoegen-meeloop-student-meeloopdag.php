<?php 
// Include the Meeloopstudent model
include IVS_MEELOOP_PORTAAL_PLUGIN_INCLUDES_MODEL_DIR . '/Meeloopstudent.php';

// Include the Meeloopdag model
include IVS_MEELOOP_PORTAAL_PLUGIN_INCLUDES_MODEL_DIR . '/Meeloopdag.php';

// Declare class variable for Meeloopstudent
$meeloop_student = new Meeloopstudent();

// Declare class variable for Meeloopdag
$meeloopdag = new Meeloopdag();

// Set base url to current page and add specific vars
$base_url = get_admin_url() . 'admin.php';
$params = array( 'page' => basename( __FILE__, '.php') );

// Add params to $base_url
$base_url = add_query_arg( $params, $base_url );

// Get the POST data in filtered array
$post_array = $meeloop_student->getPostValues();

// Get the GET data in filtered array
$get_array = $meeloop_student->getGetValues();

// Collect Errors
$error = FALSE;

// Check the POST data
if( !empty( $post_array ) ) {

    // Check the add form
    $add = FALSE;

    // If user submitted the form, save the meeloopstudent to meeloop studenten database
    if ( isset( $post_array['registreer-meeloop-student'] ) ) {

        $result = $meeloop_student->addMeeloopstudent( $post_array );

        if ( $result ) {

            // Save was succesfull
            $add = TRUE;

        } else {

            // Indicate error
            $error = TRUE;

        }

    }

    if ( isset( $post_array['bevestig-actie'] ) AND !empty( $post_array['actie'] ) ) {

        $action = $meeloop_student->handlePostAction( $post_array );

        if ( $action === 'verwijderen-meeloop-student' ) {
            $verwijderen = TRUE;
        }

        // If invitations have been send succesfully
        if ( $action[0] === 'versturen-uitnodiging' AND $action[1] === TRUE) {
            $uitnodiging = TRUE;
        }

        // If ERROR happened during sending invitations
        if ( $action[0] === 'versturen-uitnodiging' AND $action[1] === FALSE) {
            $uitnodiging = FALSE;
        }

    }
}

// Check the GET array
if ( !empty( $get_array ) ) {

    // Check the action
    if ( isset( $get_array['actie'] ) And !empty( $get_array['actie'] ) ) {

        $action = $meeloop_student->handleGetAction( $get_array );

        if ($action == 'verwijderen') {
            $verwijderen = TRUE;
        }

    }

}
?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.12.4.js"></script>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<div class="wrap">
    <h1 class="meeloop-portaal-h1">Toevoegen meeloop student</h1>
    <h2 class="meeloop-portaal-h2">Voeg nieuwe meeloop student toe</h2>
        <?php 
        // Check if user has registered any meeloopdagen.
        // If NOT, don't show form, but a message instead
        if ( $meeloopdag->getNrOfRegisteredMeeloopdagen() < 1 ) {

            $params = array( 'page' => 'toevoegen-meeloopdag' );
            $toevoegen_meeloopdag_url = add_query_arg( $params, $base_url );

            ?>
            <p class="paragraph-main">Omdat u nog <strong>geen</strong> meeloopdag heeft geregistreerd, kunt u nog geen meeloop studenten toevoegen.
            Wanneer u een meeloopdag heeft geregistreerd, zal hier het formulier verschijnen waarmee u meeloop studenten kunt registreren.
            U kunt een meeloopdag registeren op de <a href="<?php echo $toevoegen_meeloopdag_url; ?>">toevoegen meeloopdag</a> pagina.
            </p>
            <?php

        // If meeloopdagen have been registered, show form
        } else {
            ?> 
            <form action="#" method="post" id="formulier-toevoegen">
                <label for="input-meeloopdag-meeloop-student" id="label-meeloopdag-meeloop-student">Selecteer meeloopdag</label>
                <select name="meeloopdag-meeloop-student" id="input-meeloopdag-meeloop-student" form="formulier-toevoegen" required>
                    <?php 
                    
                    // Get all registered meeloopdagen
                    $meeloopdagen_list = $meeloopdag->getMeeloopdagenList();

                    // Loop over all the registered meeloopdagen as an individual meeloopdag
                    setlocale( LC_TIME, 'nld_nld' );

                    // and fill in the <option> element with the data
                    foreach( $meeloopdagen_list as $idx => $meeloopdag ) {

                        // Convert the numerical date to a more friendly date, and convert it to Dutch
                        // Example: 2024-01-03 ==> 3 january 2024 ????
                        $date = strftime( '%e %B %Y', strtotime( $meeloopdag->date ) );

                        ?><option value="<?php echo $meeloopdag->id; ?>"><?php echo $date; ?></option><?php

                    }
                    ?>
                </select>
                <label for="input-naam-meeloop-student" id="label-naam-meeloop-student">Vul hier de naam in van de meeloop student</label>
                <input type="text" id="input-naam-meeloop-student" name="naam-meeloop-student" required>
                <label for="input-email-meeloop-student" id="label-email-meeloop-student">Vul hier het e-mailadres in van de meeloop student</label>
                <input type="email" id="input-email-meeloop-student" name="email-meeloop-student" required>
                <input type="submit" name="registreer-meeloop-student" id="registreer-meeloop-student" value="Voeg meeloop student toe">
            <?php
            // If user has added a new meeloop student, show success message
            if ( isset( $add ) AND $add ) {
                echo "<span class=\"toegevoegd-message\">Meeloop student toegevoegd</span>";
            }

            // If user has removed a meeloop student, show removed message
            if ( isset( $verwijderen ) AND $verwijderen ) {
                echo "<span class=\"verwijderd-message\">Meeloopstudent(en) succesvol verwijderd!</span>";
            }

            // If user has send an invitation e-mail to meeloop studenten, show message that
            // e-mails have been succesfully send
            if ( isset( $uitnodiging ) AND $uitnodiging ) {
                echo "<span class=\"verzonden-message\">Uitnodiging(en) succesvol verzonden</span>";
            }

            // If message couldn't be send somehow, show error message with tips on how to fix it
            if ( isset( $uitnodiging ) AND empty( $uitnodiging ) ) {
                $error_message = "
                Error: Uitnodiging(en) zijn niet verzonden.<br>
                Controleer de volgende punten:
                <ol>
                    <li>Of de server juist is ingesteld op het versturen van e-mails via PHP.</li>
                    <li>Of u de juiste authenticatie gebruikt.</li>
                    <li>Of u de WP Mail SMTP by WPForms plugin gebruikt.</li>
                </ol>
                ";

                echo "<span class=\"niet-verzonden-message\">" . $error_message .  "</span>";
            }
            ?> 
            </form> <!-- #formulier-toevoegen -->
            <?php
        }
        ?>
    <h2 class="meeloop-portaal-h2">Overzicht meeloop studenten</h2>
    <form action="<?php echo $base_url; ?>" method="post" id="acties-form">
        <div class="acties-formulier">
            <label for="actie" id="label-actie">Geselecteerde meeloop studenten</label>
            <select name="actie" id="selecteer-actie" class="selecteer-actie-1">
                <option selected="selected" id="value_1"></option>
                <option value="versturen-uitnodiging" id="value_2">versturen uitnodiging</option>
                <option value="verwijderen-meeloop-student" id="value_3">verwijderen</option>
            </select> <!-- #selecteer-actie -->
            <input type="submit" name="bevestig-actie" id="bevestig-actie" class="ivs-button ivs-button-inline button-full-width" value="Bevestig actie">     
        </div> <!-- .acties-formulier -->
        
        <table id="meeloopstudenten-tabel">
            <tr id="meeloopstudenten-tabel-header">
                <th id="meeloopstudenten-tabel-data">
                    Selecteer<br>alles
                    <input type="checkbox" name="checkbox-selecteer-allen" id="checkbox-selecteer-allen">
                </th>
                <th id="meeloopstudenten-tabel-data">Meeloopdag</th>
                <th id="meeloopstudenten-tabel-data">Naam</th>
                <th id="meeloopstudenten-tabel-data">E-mail</th>
                <th id="meeloopstudenten-tabel-data">Status</th>
                <th id="meeloopstudenten-tabel-data">Verwijderen</th>
            </tr> <!-- #meeloopstudenten-tabel-header-->
            <?php 

            // Get all registered meeloop studenten
            $meeloop_student_records = $meeloop_student->getMeeloopstudentenList();

            // Loop over registered meeloop studenten list as an individual
            foreach( $meeloop_student_records as $meeloop_student_individual ) {

                // Create delete link
                $params = array( 'actie' => 'verwijderen', 'id' => $meeloop_student_individual->getID() );

                // Add params to base url delete link
                $del_link = add_query_arg( $params, $base_url );

                // Get the meeloopdag date for the current meeloop student
                $meeloopdag_date = $meeloop_student_individual->getMeeloopdagDate( $meeloop_student_individual->getMeeloopdagID() );

                // Convert the numerical date to a more friendly date, and convert it to Dutch
                // Example: 2024-01-03 ==> 3 january 2024 ????
                $meeloopdag_date = strftime( '%e %B %Y', strtotime( $meeloopdag_date ) );

                // Get the e-mail status label for the current meeloop student
                $email_status_label = $meeloop_student_individual->getEmailStatusLabel( $meeloop_student_individual->getEmailStatusID() );

            ?>
            <tr id="meeloopstudenten-tabel-rij">
                <td id="meeloopstudenten-tabel-data">
                    <input type="checkbox" name="checkbox-selecteer-individu[]" class="checkbox-individu" value="<?php echo $meeloop_student_individual->getID(); ?>">
                </td>
                <td id="meeloopstudenten-tabel-data"><?php echo $meeloopdag_date; ?></td>
                <td id="meeloopstudenten-tabel-data"><?php echo $meeloop_student_individual->getName(); ?></td>
                <td id="meeloopstudenten-tabel-data"><?php echo $meeloop_student_individual->getEmail(); ?></td>
                <td id="meeloopstudenten-tabel-data"><?php echo $email_status_label; ?></td>
                <td id="meeloopstudenten-tabel-data"><a href="<?php echo $del_link; ?>" class="verwijder-button">X</a></td>
            </tr> <!-- #meeloopstudenten-tabel-rij -->
            <?php 
            }
            ?>
        </table> <!-- #meeloopstudenten-tabel -->

        <div class="acties-formulier">
            <label for="actie" id="label-actie">Geselecteerde meeloop studenten</label>
            <select name="actie" id="selecteer-actie" class="selecteer-actie-2">
                <option selected="selected" id="value_4"></option>
                <option value="versturen-uitnodiging" id="value_5">versturen uitnodiging</option>
                <option value="verwijderen-meeloop-student" id="value_6">verwijderen</option>
            </select> <!-- #selecteer-actie -->
            <input type="submit" name="bevestig-actie" id="bevestig-actie" class="ivs-button ivs-button-inline button-full-width" value="Bevestig actie">     
        </div> <!-- .acties-formulier -->      

    </form>
    <div id="dialog-confirm" title="Empty the recycle bin?">
        <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Weet u zeker dat u deze meeloop student(en) wilt verwijderen? U kunt dit niet ongedaan maken.</p>
    </div>
</div> <!-- .wrap -->
<script>

// Prevent user from opening deletion link
// Otherwise modal dialog for confirmation won't show
const x = document.getElementsByClassName('verwijder-button');
for (let i = 0; i < x.length; i++) {

    x[i].addEventListener('click', function(e) {
        e.preventDefault();
    });
}

// show confirm dialog when user deletes meeloop student
jQuery(document).ready(function($) {

    function modal(deletion_link) {

            // Open the dialog when anchor is pressed
            jQuery('#dialog-confirm').dialog({

                // Configuration settings
                title: "Meeloop student(en) verwijderen?",
                modal: true,
                draggable: false,
                resizable: false,
                width: 400,
                buttons: [
                    {
                        text: 'Annuleer verwijdering',
                        click: function() {

                            // Close dialog when button is pressed
                            $(this).dialog('close');
                            
                        }
                    },
                    {
                        text: 'Verwijderen',
                        click: function() {
                            
                            // Replace current URL with deletion URL, ONLY if deletion_link is specified
                            // NOTE: this is a different URL than the action 'verwijderen-meeloop-student' (batch)
                            if (deletion_link) {
                                window.location.replace(deletion_link);
                                return;
                            }

                            // Close modal dialog
                            $(this).dialog('close');

                            // Remove the preventDefault, otherwise form WON'T be submitted
                            $('#bevestig-actie').off('click');

                            // Submit form
                            $('#bevestig-actie').click();
                            
                        }
                    }
                ]
            });

    }
    
    // Attach eventListener to bevestig-actie button    
    $("#bevestig-actie").bind('click', function(e) {
        
        // If actie === 'verwijderen-meeloop-student', show modal dialog for confirmation
        if ($("#selecteer-actie").children('option:selected').val() === 'verwijderen-meeloop-student') {
            
            // Prevent the button from submitting the form
            e.preventDefault();

            // Show modal dialog
            modal(null);
            
        }
    });

    // Attach eventListener to verwijder-button    
    $('.verwijder-button').on('click', function() {

        // Retrieve the href value attr. of currently pressed anchor
        const HREF_VALUE = this.getAttribute('href');         

        modal(HREF_VALUE);

    })        

});

document.addEventListener('DOMContentLoaded', function() {
    // Retrieve the input fields
    const INPUT_NAAM_MEELOOP_STUDENT = document.getElementById('input-naam-meeloop-student');
    const INPUT_EMAIL_MEELOOP_STUDENT = document.getElementById('input-email-meeloop-student');

    // Show custom message when name field is invalid
    INPUT_NAAM_MEELOOP_STUDENT.oninvalid = function(e) {
        e.target.setCustomValidity("");
        if (!e.target.validity.valid) {
            e.target.setCustomValidity("U bent de naam vergeten.");
        }
    }

    // Remove the error message when user types in
    INPUT_NAAM_MEELOOP_STUDENT.oninput = function(e) {
            e.target.setCustomValidity("");
    }

    // Show custom message when email field is invalid
    INPUT_EMAIL_MEELOOP_STUDENT.oninvalid = function(e) {
        e.target.setCustomValidity("");
        if (!e.target.valueMissing) {
            e.target.setCustomValidity("U bent de e-mail vergeten.");
        }
        if (e.target.validity.typeMismatch) {
            e.target.setCustomValidity("U mist een @ in de e-mail");
        }
    }

    // Remove the error message when user types in
    INPUT_EMAIL_MEELOOP_STUDENT.oninput = function(e) {
        e.target.setCustomValidity("");
    }
});

// Define the action forms
const SELECT_DROPDOWN_1 = document.querySelector('.selecteer-actie-1');
const SELECT_DROPDOWN_2 = document.querySelector('.selecteer-actie-2');

// Detect whether value changes for select dropdown 1
SELECT_DROPDOWN_1.addEventListener('change', function(e) {

    // If selected value of select dropdown 1 is NOT the same as select dropdown 2
    // then change the selected option in select dropdown 2
    if (SELECT_DROPDOWN_1.selectedIndex !== SELECT_DROPDOWN_2.selectedIndex) {
        SELECT_DROPDOWN_2.selectedIndex = SELECT_DROPDOWN_1.selectedIndex;
    }
});

// Detect whether value changes for select dropdown 2
SELECT_DROPDOWN_2.addEventListener('change', function(e) {

    // If selected value of select dropdown 2 is NOT the same as select dropdown 1
    // then change the selected option in select dropdown 1
    if (SELECT_DROPDOWN_2.selectedIndex !== SELECT_DROPDOWN_1.selectedIndex) {
        SELECT_DROPDOWN_1.selectedIndex = SELECT_DROPDOWN_2.selectedIndex;
    }
});

const BUTTON_SELECTEER_ALLES = document.querySelector('#checkbox-selecteer-allen');

BUTTON_SELECTEER_ALLES.addEventListener('click', function() {

    // Get all checkboxes
    const CHECKBOXES = document.querySelectorAll('.checkbox-individu');

    // If 'selecteer alles' has been checked, check all select boxes
    if (this.checked) {
        CHECKBOXES.forEach(checkbox => { checkbox.checked = true } );    
        
    // If 'selecter alles' has been unchecked, remove check from all select boxes
    } else {
        CHECKBOXES.forEach(checkbox => { checkbox.checked = false } );

    }

});
</script>