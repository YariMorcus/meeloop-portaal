<?php 
// Include the model
include IVS_MEELOOP_PORTAAL_PLUGIN_INCLUDES_MODEL_DIR . '/Meeloopdag.php';

// Declare class variable
$meeloopdag = new Meeloopdag();

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

// echo '<pre>';
// echo __FILE__.__LINE__.'<br />';
//  var_dump($post_array);
// echo '</pre>';

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
        $education_list = $meeloopdag->getOpleidingenList();
        foreach( $education_list as $idx => $array ) {
            ?>
            <option value="<?php echo $array->id    ; ?>"><?php echo $array->education_name; ?></option>
            <?php
        }
        ?>d
        </select>
        <label for="selecteer-datum" id="label-meeloopdag-datum">Selecteer hier de meeloopdag datum</label>
        <input type="date" id="selecteer-datum" name="meeloopdag-datum" min="2021-7-5">
        <input type="text" id="input-naam-docent"name="naam-docent" value="<?php echo wp_get_current_user()->display_name; ?>" readonly>
        <input type="submit" name="registreer-meeloopdag" id="registreer-meeloopdag" value="Stel meeloopdag in">

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
    let CURRENT_DAY = DATE.getDate();
    let CURRENT_MONTH = DATE.getMonth() + 1;
    const CURRENT_YEAR = DATE.getFullYear();

    if (returnNumberLength(CURRENT_MONTH) != 2) { CURRENT_MONTH = appendZeroToBeginning(CURRENT_MONTH) };
    if (returnNumberLength(CURRENT_DAY) != 2) { CURRENT_DAY = appendZeroToBeginning(CURRENT_DAY) };

    const MIN_VALUE = `${CURRENT_YEAR}-${CURRENT_MONTH}-${CURRENT_DAY}`;

    document.getElementById('selecteer-datum').setAttribute('min', MIN_VALUE);
})();
</script>