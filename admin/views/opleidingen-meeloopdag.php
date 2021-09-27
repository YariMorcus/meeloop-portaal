<?php 
// Include the model
require_once IVS_MEELOOP_PORTAAL_PLUGIN_INCLUDES_MODEL_DIR . '/Opleidingen.php';

// Declare class variable
$opleidingen = new Opleidingen();

// Set base URL to current file, and add page specific vars
$base_url = get_admin_url() . 'admin.php';
$params = array( 'page' => basename( __FILE__, '.php' ));

// Add params to base URL (and escape it for )
$base_url = esc_url( add_query_arg( $params, $base_url ) );

// Get the POST data in filtered array
$post_array = $opleidingen->getPostValues();

//*
// echo '<pre>';
// echo __FILE__ . __LINE__;
// var_dump($post_array);
// echo '</pre>';
//*/

// Collect the errors
$errors = FALSE;

// Check the POST data
if ( !empty( $post_array ) ) {

    // Check the add form
    $add = FALSE;

    if ( isset( $post_array['toevoegen-opleidingsnaam'] ) ) {

        // Save the education name in the database
        $result = $opleidingen->save( $post_array );

        if ( $result ) {

            // Show success message
            $add = TRUE;
            
        } else {

            // Indicate error
            $error = TRUE;
        }


    }
}
?>
<main class="wrap">
    <h1 class="main-heading-admin">Opleidingen</h1>
    <p class="paragraph-main">Op deze pagina is een overzicht van alle aangemaakte opleidingen te vinden.

    Deze pagina biedt de docent ook de mogelijkheid om een opleiding aan te maken, aan te passen en te verwijderen.
    De aangemaakte opleidingen kunnen worden geselecteerd, tijdens het aanmaken van een meeloopdag.
    </p> <!-- .paragraph-main -->
    <div class="two-column-layout">
        <section>
            <h2 class="subheading-2-admin">Overzicht opleidingen</h2>
            <table class="admin-meeloop-table">
                <tr>
                    <th class="table-header">Opleidingsnaam</th>
                    <th></th>
                </tr>
                <?php 
                    if( $opleidingen->getNrOfRegisteredEducation() < 1 ) { 
                ?>
                <tr>
                    <td>Er zijn nog geen opleidingen geregistreerd.</td>
                </tr>
                <?php 
                    } else { 
                        $opleidingen_list = $opleidingen->getEducationList();

                        // Loop over the array containing the objects that contain the data
                        foreach( $opleidingen_list as $opleiding) {
                        ?>
                        <tr>
                            <td><?php echo $opleiding->getNaam(); ?></td>
                            <td><a href="#" id="anchor-edit-opleiding"><img src="<?php echo plugin_dir_url( __DIR__ ) . 'assets/icons/pencil-icon.svg'; ?>" alt="" width="25" height="25"></a></td>
                        </tr>
                        <?php
                        }
                    } 
                ?>
            </table> <!-- admin-meeloop-table -->
        </section>
        <section>
            <h2 class="subheading-2-admin">Toevoegen opleiding</h2>
            <form action="<?php echo $base_url; ?>" method="POST">
                <label for="input-toevoegen-opleiding" id="label-toevoegen-opleiding">Opleidingsnaam</label>
                <input type="text" name="input-opleidingsnaam" id="input-toevoegen-opleiding" maxlength="50">
                <input type="submit" name="toevoegen-opleidingsnaam" id="toevoegen-meeloopdag" value="Toevoegen opleiding">
                <?php echo isset( $add ) ? "<span class=\"toegevoegd-message\">Opleiding toegevoegd</span>" : ''; ?>
            </form>
        </section>
    </div> <!-- .two-column-layout -->
</main> <!-- .wrap -->