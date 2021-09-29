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

// Get the GET data in filtered array
$get_array = $opleidingen->getGetValues();

//*
echo '<pre>';
echo __FILE__ . __LINE__;
// var_dump($post_array);
echo '</pre>';
//*/

// Collect the errors
$errors = FALSE;

$action = FALSE;

// Check the POST data
if ( !empty( $post_array ) ) {

    // Check the add form
    $add = FALSE;
    
    $edit = FALSE;

    if ( !empty( $post_array['toevoegen-opleidingsnaam'] ) ) {

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

    if ( !empty( $post_array['update-opleidingsnaam'] ) ) {


        // Save new (edited) education name
        $opleidingen->update( $post_array );
        
        $edit = TRUE;

    }
}

// Check the GET data
if ( !empty( $get_array ) ) {

    // Check action
    if ( isset( $get_array['action'] ) ) {
            $action = $opleidingen->handleGetAction( $get_array );
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
            <?php 
            // If action = update, change table to form, so user can edit and submit the education
            echo (( $action === 'update' ) ? '<form action="' . $base_url . '" method="post">' : ''); 
            ?>
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

                            // Add params for update url
                            $params = array( 'action' => 'update', 'id' => $opleiding->getID() );

                            // Generate update URL with given parameters
                            $update_url = add_query_arg( $params, $base_url )

                        ?>
                        <tr>
                            <?php 
                                if ( ( $action === 'update') && ( $opleiding->getID() === $get_array['id'] ) ) {
                                    ?>
                                    <td style="display: none;"><input type="hidden" name="id-opleidingsnaam" value="<?php echo $opleiding->getID(); ?>"></td>
                                    <td><input type="text" name="update-input-opleidingsnaam" maxlength="50" class="input-edit-education" value="<?php echo $opleiding->getNaam(); ?>" required></td>
                                    <td><input type="submit" name="update-opleidingsnaam" value="Aanpassen" id="bewerken-opleidingsnaam"></td>
                                    <?php
                                } else {
                                    ?>
                                    <td class="td-no-edit-input"><?php echo $opleiding->getNaam(); ?></td>
                                    <td><a href="<?php echo $update_url; ?>" id="anchor-edit-opleiding">
                                    <img src="<?php echo plugin_dir_url( __DIR__ ) . 'assets/icons/pencil-icon.svg'; ?>" alt="" width="25" height="25"></a></td>
                                    <?php
                                }
                            ?>
                            
                        </tr>
                        <?php
                        }
                    } 
                ?>
            </table> <!-- admin-meeloop-table -->
            <?php 
            // If action = update, close the form
            echo (( $action === 'update' ) ? '</form>' : '' );
            ?>
        </section>
        <section>
            <h2 class="subheading-2-admin">Toevoegen opleiding</h2>
            <form action="<?php echo $base_url; ?>" method="POST">
                <label for="input-toevoegen-opleiding" id="label-toevoegen-opleiding">Opleidingsnaam</label>
                <input type="text" name="input-opleidingsnaam" id="input-toevoegen-opleiding" maxlength="50" required>
                <input type="submit" name="toevoegen-opleidingsnaam" id="toevoegen-meeloopdag" value="Toevoegen opleiding">
                <?php echo !empty( $add ) ? "<span class=\"toegevoegd-message\">Opleiding toegevoegd</span>" : ''; ?>
                <?php echo !empty( $edit ) ? "<span class=\"bewerkt-message\">Opleiding aangepast</span>" : ''; ?>
            </form>
        </section>
    </div> <!-- .two-column-layout -->
</main> <!-- .wrap -->