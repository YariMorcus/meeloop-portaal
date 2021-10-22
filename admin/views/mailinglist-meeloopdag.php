<?php 
// Include the model
include IVS_MEELOOP_PORTAAL_PLUGIN_INCLUDES_MODEL_DIR . '/Mailinglist.php';

// Declare class variable
$mailinglist = new Mailinglist();

// Set base url to current page and add specific vars
$base_url = get_admin_url() . 'admin.php';
$params = array( 'page' => basename( __FILE__, '.php') );

// Add params to $base_url
$base_url = add_query_arg( $params, $base_url );

// Get the POST data in filtered array
$post_array = $mailinglist->getPostValues();

// Get the GET data in filtered array
$get_array = $mailinglist->getGetValues();

// Collect Errors
$error = FALSE;

// Check the POST data
if( !empty( $post_array ) ) {

    // Check the add form
    $add = FALSE;

    // If user submitted the form, save the meeloopstudent to mailinglist database
    if ( isset( $post_array['registreer-meeloop-student'] ) ) {

        $result = $mailinglist->addMeeloopstudentToMailinglist( $post_array );

        if ( $result ) {

            // Save was succesfull
            $add = TRUE;

        } else {

            // Indicate error
            $error = TRUE;

        }

    }

    if ( isset( $post_array['bevestig-actie'] ) AND !empty( $post_array['actie'] ) ) {

        $action = $mailinglist->handlePostAction( $post_array );

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

        $action = $mailinglist->handleGetAction( $get_array );

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
    <h1 class="meeloop-portaal-h1">Mailinglist meeloop studenten</h1>
    <h2 class="meeloop-portaal-h2">Voeg nieuwe meeloop student toe</h2>
    <form action="#" method="post" id="formulier-toevoegen-mailinglist">
        <label for="input-meeloopdag-meeloop-student" id="label-meeloopdag-meeloop-student">Selecteer meeloopdag</label>
        <select name="meeloopdag-meeloop-student" id="input-meeloopdag-meeloop-student" form="formulier-toevoegen-mailinglist" required>
            <option value="2">23 oktober 2021</option>
        </select>
        <label for="input-naam-meeloop-student" id="label-naam-meeloop-student">Vul hier de naam in van de meeloop student</label>
        <input type="text" id="input-naam-meeloop-student" name="naam-meeloop-student" required>
        <label for="input-email-meeloop-student" id="label-email-meeloop-student">Vul hier het e-mailadres in van de meeloop student</label>
        <input type="email" id="input-email-meeloop-student" name="email-meeloop-student" required>
        <input type="submit" name="registreer-meeloop-student" id="registreer-meeloop-student" value="Voeg meeloop student toe aan mailinglist">
        <?php 

        if ( isset( $add ) AND $add ) {
            echo "<span class=\"toegevoegd-message\">Meeloop student toegevoegd aan mailinglist</span>";
        }

        if ( isset( $verwijderen ) AND $verwijderen ) {
            echo "<span class=\"verwijderd-message\">Meeloopstudent(en) succesvol verwijderd!</span>";
        }

        if ( isset( $uitnodiging ) AND $uitnodiging ) {
            echo "<span class=\"verzonden-message\">Uitnodiging(en) succesvol verzonden</span>";
        }

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
    </form> <!-- #formulier-toevoegen-mailinglist -->

    <h2 class="meeloop-portaal-h2">Mailinglist</h2>
    <form action="<?php echo $base_url; ?>" method="post" id="acties-form">
        <div class="acties-formulier">
            <label for="actie" id="label-actie">Geselecteerde meeloop studenten</label>
            <select name="actie" id="selecteer-actie" class="selecteer-actie-1">
                <option selected="selected" id="value_1"></option>
                <option value="versturen-uitnodiging" id="value_2">versturen uitnodiging</option>
                <option value="verwijderen-meeloop-student" id="value_3">verwijderen</option>
            </select> <!-- #selecteer-actie -->
            <input type="submit" name="bevestig-actie" id="bevestig-actie" value="Bevestig actie">     
        </div> <!-- .acties-formulier -->
        
        <table id="mailinglist-tabel">
            <tr id="mailinglist-tabel-header">
                <th id="mailinglist-tabel-data">
                    Selecteer<br>alles
                    <input type="checkbox" name="checkbox-selecteer-allen" id="checkbox-selecteer-allen">
                </th>
                <th id="mailinglist-tabel-data">Meeloopdag</th>
                <th id="mailinglist-tabel-data">Naam</th>
                <th id="mailinglist-tabel-data">E-mail</th>
                <th id="mailinglist-tabel-data">Status</th>
                <th id="mailinglist-tabel-data">Verwijderen</th>
            </tr> <!-- #mailinglist-tabel-header-->
            <?php 
            $mailinglist_records = $mailinglist->getMeeloopstudentenList();
            foreach( $mailinglist_records as $meeloop_student ) {

                // Create delete link
                $params = array( 'actie' => 'verwijderen', 'id' => $meeloop_student->getID() );

                // Add params to base url delete link
                $del_link = add_query_arg( $params, $base_url );

                $email_status_label = $meeloop_student->getEmailStatusLabel( $meeloop_student->getEmailStatusID() );

            ?>
            <tr id="mailinglist-tabel-rij">
                <td id="mailinglist-tabel-data">
                    <input type="checkbox" name="checkbox-selecteer-individu[]" class="checkbox-individu" value="<?php echo $meeloop_student->getID(); ?>">
                </td>
                <td id="mailinglist-tabel-data">23 oktober 2021</td>
                <td id="mailinglist-tabel-data"><?php echo $meeloop_student->getName(); ?></td>
                <td id="mailinglist-tabel-data"><?php echo $meeloop_student->getEmail(); ?></td>
                <td id="mailinglist-tabel-data"><?php echo $email_status_label; ?></td>
                <td id="mailinglist-tabel-data"><a href="<?php echo $del_link; ?>" class="verwijder-button">X</a></td>
            </tr> <!-- #mailinglist-tabel-rij -->
            <?php 
            }
            ?>
        </table> <!-- #mailinglist-tabel -->

        <div class="acties-formulier">
            <label for="actie" id="label-actie">Geselecteerde meeloop studenten</label>
            <select name="actie" id="selecteer-actie" class="selecteer-actie-2">
                <option selected="selected" id="value_4"></option>
                <option value="versturen-uitnodiging" id="value_5">versturen uitnodiging</option>
                <option value="verwijderen-meeloop-student" id="value_6">verwijderen</option>
            </select> <!-- #selecteer-actie -->
            <input type="submit" name="bevestig-actie" id="bevestig-actie" value="Bevestig actie">     
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