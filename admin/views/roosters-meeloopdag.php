<?php 

// Include the schedule model
include IVS_MEELOOP_PORTAAL_PLUGIN_INCLUDES_MODEL_DIR . '/Rooster.php';

// Include the Meeloopdag model
include IVS_MEELOOP_PORTAAL_PLUGIN_INCLUDES_MODEL_DIR . '/Meeloopdag.php';

// Declare class variable for schedule
$schedule = new Rooster();

// Declare class variable for Meeloopdag
$meeloopdag = new Meeloopdag();

// Set base url to current page and add page specific vars
$base_url = get_admin_url() . 'admin.php';
$params = array( 'page' => basename( __FILE__, '.php') );

$base_url = add_query_arg( $params, $base_url );

// Get the POST data in filtered array
$post_array = $schedule->getPostValues();

// Collect errors
$error = FALSE;

if( !empty( $post_array ) ) {

    // Check the add form
    $add = FALSE;
    
    if( isset( $post_array['toevoegen-taak'] ) ) {

        // Save the schedule (in English: schedule)
        $result = $schedule->save( $post_array );

        if( $result )  {
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
    <h1 class="main-heading-admin">Roosters meeloopdag(en)</h1>
    <p class="paragraph-main">Op deze pagina is een overzicht van alle aangemaakte roosters te vinden voor de geregistreerde meeloopdagen.

    Deze pagina biedt de docent ook de mogelijkheid om een rooster (taak) aan te maken.
    </p> <!-- .paragraph-main -->
    <div class="grid-container">
        <div>
            <h2 class="subheading-2-admin">Toevoegen taak (schedule)</h2>
            <form action="<?php echo $base_url; ?>" method="post" id="formulier-toevoegen-rooster">

            <?php
            // Check if user has registered any meeloopdagen.
            // If NOT, don't show form, but a message instead
            if( $meeloopdag->getNrOfRegisteredMeeloopdagen() < 1 ) {
                
                $params = array( 'page' => 'toevoegen-meeloopdag');
                $toevoegen_meeloopdag_url = add_query_arg( $params,  $base_url );
                
                ?>
                <span><strong>Let op:</strong></span>
    
                <p class="paragraph-main">Omdat u nog <strong>geen</strong> meeloopdag heeft geregistreerd, kunt u geen schedule/taak aanmaken.
                Wanneer u een meeloopdag heeft geregistreerd, zal hier het formulier verschijnen waarmee u een schedule/taak kunt maken.
                U kunt een meeloopdag registeren op de <a href="<?php echo $toevoegen_meeloopdag_url; ?>">toevoegen meeloopdag</a> pagina.
                </p>
                <?php
            } else {
                ?>
                <label for="selecteer-meeloopdag" id="label-selecteer-meeloopdag">Selecteer meeloopdag *</label>
                 <select name="select-meeloopdag" id="selecteer-meeloopdag">
                 <?php 
    
                     // Get all registered meeloopdagen
                     $meeloopdagen_list = $meeloopdag->getMeeloopdagenList();
    
                     setlocale( LC_TIME, 'nld_nld' );
    
                     // Loop over all the registered meeloopdagen as an individual meeloopdag
                     // and fill in the <option> element with the data
                     foreach( $meeloopdagen_list as $idx => $meeloopdag ) {
    
                         // Convert the numerical date to a more friendly date, and convert it to Dutch
                         // Example: 2024-01-03 ==> 3 january 2024 😊
                         $meeloopdag_date = strftime( '%e %B %Y', strtotime( $meeloopdag->date ) );
                         
                         ?><option value="<?php echo $meeloopdag->id; ?>"><?php echo $meeloopdag_date; ?></option><?php
                     }
                 ?>
                 </select>
                 <label for="input-taaknaam" id="label-input-taaknaam">Vul taak in *</label>
                 <input type="text" name="input-taaknaam" id="input-taaknaam" required>
                 <label for="input-starttijd"" id="label-input-starttijd">Selecteer starttijd *</label>
                 <input type="time" name="input-starttijd" id="input-starttijd" pattern="[0-9]{2}:[0-9]{2}" required>
                 <label for="input-eindtijd" id="label-input-eindtijd">Selecteer eindtijd *</label>
                 <input type="time" name="input-eindtijd" id="input-eindtijd" pattern="[0-9]{2}:[0-9]{2}" required>
                 <span class="validity"></span>
                 <input type="submit" name="toevoegen-taak" class="ivs-button" value="Toevoegen taak">
                <?php
            }
            ?>
            </form> <!-- #formulier-toevoegen-schedule -->
        </div>
        <div>
            <h2 class="subheading-2-admin">Overzicht schedules</h2>
            <?php 
            if( $schedule->getNrOfRegisteredschedules() < 1 ) {
                ?>
                <p class="paragraph-main">Er is op dit moment nog geen schedule/taak aangemaakt.
                    Maak een schedule/taak met behulp van het formulier aan uw linkerkant.
                </p> <!-- .paragraph-main -->
                <?php
            } else {
                /**
                 TODO:  Recheck instructions below FIRST before moving on
                 
                    DONE 1. Retrieve all unique fk_meeloopdag_id from table ivs_mp_schedule
                    DONE 2. Store those in an array
                    DONE 3. Loop over the object
                    4. Execute a database query where taaknaam, starttijd and eindtijd are being returned, based on the given fk_meeloopdag_id
                    5. Store those results in an object
                */

                // Get a list of all registered meeloopdagen
                $meeloopdagen_list = $meeloopdag->getMeeloopdagenList();

                // Create array to store meeloopdag id's
                $meeloopagen_ids_list = array();

                // Loop over all registered meeloopdagen, and store the ID's in the array above

                // Set the locale for time to Dutch
                setlocale( LC_TIME, 'nld_nld' );

                foreach( $meeloopdagen_list as $idx => $meeloopdag ) {

                    $meeloopagen_ids_list[] = $meeloopdag->id; // Equivalent to array_push( $ids, $meeloopdag->id ) 

                }
                
                foreach( $meeloopagen_ids_list as $idx => $meeloopdag_id ) {
                    
                    // Get the schedule of a specific meeloopdag
                    $schedule_meeloopdag = $schedule->getScheduleOfMeeloopdag( $meeloopdag_id );

                    // Convert the numerical date to a more friendly date, and convert it to Dutch
                    // Example: 2024-01-03 ==> 3 january 2024 😊
                    $meeloopdag_date = strftime( '%e %B %Y', strtotime( $meeloopdagen_list[$idx]->date ) );
                    
                    ?>
                    <div class="collapse-container">
                        <header class="collapse-header"><?php echo $meeloopdag_date; ?></header>
                        <div class="collapse-content">
                            <table class="ivs-table">
                                <thead>
                                    <tr class="ivs-table-row">
                                        <th class="ivs-table-th">Taak</th>
                                        <th class="ivs-table-th">Starttijd</th>
                                        <th class="ivs-table-th">Eindtijd</th>
                                    </tr> <!-- .ivs-table-row -->
                                </thead>
                                <tbody>
                    <?php

                    // Loop over the individual task of the schedule
                    foreach( $schedule_meeloopdag as $item ) {
                        // Underneath this comment: fill the row with the corresponding data
                        ?>
                                    <tr class="ivs-table-row">
                                        <td class="ivs-table-td"><?php echo $item->taaknaam; ?></td>
                                        <td class="ivs-table-td"><time datetime="<?php echo $meeloopdagen_list[$idx]->date?>T<?php echo $item->starttijd; ?>"><?php echo $item->starttijd; ?></time></td>
                                        <td class="ivs-table-td"><time datetime="<?php echo $meeloopdagen_list[$idx]->date?>T<?php echo $item->eindtijd; ?>"><?php echo $item->eindtijd; ?></time></td> 
                                    </tr> <!-- .ivs-table-row -->
                                    <?php
                    }
                    ?>
                                </tbody>
                            </table> <!-- .ivs-table -->
                        </div> <!-- .collapse-content -->
                    </div> <!-- .collapse-container -->
                <?php
                }
            }
            ?>
        </div>
    </div> <!-- .grid-container -->
</div> <!-- .wrap -->
<script>
    const INPUT_START_TIJD = document.querySelector('#input-starttijd');
    const INPUT_EIND_TIJD = document.querySelector('#input-eindtijd');

    // When user leaves input starttijd field after filling in,
    // retrieve the value
    INPUT_START_TIJD.addEventListener('blur', () => {

        // Set the value from the input start tijd as a minimum for the eind tijd
        // In other words: prevents user from giving up a time BEFORE the start time
        INPUT_EIND_TIJD.setAttribute('min', INPUT_START_TIJD.value);

    });

    INPUT_EIND_TIJD.addEventListener('blur', () => {
        
        /**
        1. Check if input eindtijd is bigger than input eindtijd
        2. If not, insert a span below #input-eindtijd, and show an error message
        */
    });
</script>