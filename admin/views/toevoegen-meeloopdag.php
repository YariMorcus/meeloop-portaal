<?php 
// Include the model for Meeloopdag
include IVS_MEELOOP_PORTAAL_PLUGIN_INCLUDES_MODEL_DIR . '/Meeloopdag.php';

// Include the model for Opleiding
include IVS_MEELOOP_PORTAAL_PLUGIN_INCLUDES_MODEL_DIR . '/Opleiding.php';

// Declare class variable for Meeloopdag
$meeloopdag = new Meeloopdag();

// Declare class variable for Opleiding
$opleiding = new Opleiding();

// Set base url to current file and add page specific vars
$base_url = get_admin_url() . 'admin.php';
$params = array( 'page' => basename( __FILE__, ".php" ) );

// Add params to base url
$base_url = add_query_arg( $params, $base_url );

/**
 *      NOTICE:
 *      $base_url isn't being used in action attribute of form.
 *      This because it causes an error which prevents the form from being submitted
 * 
 *      @author: Yari Morcus
 *      @date: 25-05-2021
*/

// Get the POST data in filtered array
$post_array = $meeloopdag->getPostValues();

if ( !empty( $post_array ) ) {

    // Check the add form:
        $add = FALSE;

        if ( isset( $post_array['registreer-meeloopdag'] ) ) {

            // Save the meeloopdag
            $result = $meeloopdag->registerMeeloopdag( $post_array );

            if ($result) {
                // Save was succesfull
                $add = TRUE;
            } else {
                // Indicate error
                $error = TRUE;
            }

        }
}
?>

<div class="wrap">
    <h1 class="meeloop-portaal-h1">Toevoegen meeloopdag</h1>
    <?php 
    echo ( ( isset( $add ) && $add ) ? "<p>Meeloopdag toegevoegd.</p>" : "")
    ?>
    
    <form action="#" method="post" id="formulier-toevoegen-meeloopdag">

        <label for="selecteer-opleiding" id="label-opleiding">Selecteer hier de opleiding</label>
        <select name="opleiding" id="selecteer-opleiding">

        <?php 
        // Get all registered educations
        $education_list = $opleiding->getEducationList();

        // Loop over all the registered educations as an individual item
        // and print the id and name of the education
        foreach( $education_list as $idx => $array ) {
            ?>
            <option value="<?php echo $array->getID(); ?>"><?php echo $array->getNaam(); ?></option>
            <?php
        }
        ?>d
        </select>
        <label for="selecteer-datum" id="label-meeloopdag-datum">Selecteer hier de meeloopdag datum</label>
        <input type="date" id="selecteer-datum" name="meeloopdag-datum" min="2021-7-5">
        <input type="text" id="input-naam-docent"name="naam-docent" value="<?php echo wp_get_current_user()->display_name; ?>" readonly>
        <input type="submit" name="registreer-meeloopdag" id="registreer-meeloopdag" class="ivs-button" value="Stel meeloopdag in">

    </form>

    <span class="info-formulier"><strong>Let op: </strong>de naam van de docent wordt opgeslagen op basis van de schermnaam binnen WordPress.</span><br>
    <span class="info-formulier">U kunt dit instellen in 'Gebruikers' --> Profiel --> Schermnaam (vereist).</span>
</div>
<script>
// Prevent user from picking a date earlier than today
(function() {

    function returnNumberLength(n) {
        return String(Math.abs(n)).length;
    }

    function appendZeroToBeginning(n) {
        return n = '0' + n;
    }

    const DATE = new Date();
    let current_day = DATE.getDate();
    let current_month = DATE.getMonth() + 1;
    const CURRENT_YEAR = DATE.getFullYear();

    if (returnNumberLength(current_month) != 2) { current_month = appendZeroToBeginning(current_month) };
    if (returnNumberLength(current_day) != 2) { current_day = appendZeroToBeginning(current_day) };

    const MIN_VALUE = `${CURRENT_YEAR}-${current_month}-${current_day}`;

    document.getElementById('selecteer-datum').setAttribute('min', MIN_VALUE);
})();
</script>