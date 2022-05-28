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
        $result = $schedule->saveSchedule( $post_array );

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
                 <input type="time" name="input-eindtijd" id="input-eindtijd" min="00:00" max="23:59" pattern="[0-9]{2}:[0-9]{2}" required>
                 <span class="validity">Vul een tijd in later dan <span id="min-time"></span></span>
                 <input type="submit" name="toevoegen-taak" class="ivs-button" value="Toevoegen taak">
                <?php
            }
            ?>
            </form> <!-- #formulier-toevoegen-schedule -->
        </div>
        <div>
            <h2 class="subheading-2-admin">Overzicht roosters</h2>
            <?php 
            if( $schedule->getNrOfRegisteredschedules() < 1 ) {
                ?>
                <p class="paragraph-main">Er is op dit moment nog geen schedule/taak aangemaakt.
                    Maak een schedule/taak met behulp van het formulier aan uw linkerkant.
                </p> <!-- .paragraph-main -->
                <?php
            } else {
                
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

    // When user leaves input starttijd field after filling in
    INPUT_START_TIJD.addEventListener('blur', () => {

        function betweenNumber(num, min, max) {
            return num >= min && num <= max;
        }

        // Set the value from the input start tijd as a minimum for the eind tijd
        // In other words: prevents user from giving up a time BEFORE the start time
        
        // Get value of given start time
        var res = INPUT_START_TIJD.value;

        // Get hour and minute
        let hour = res.split(":")[0];
        let minute = parseInt(res.split(":")[1]);

        // Add 1 minute
        minute = minute + 1;

        // If minute is between 0 and 9, convert number to string, and append
        // a zero (0) to it. Otherwise, only convert number to string.
        // Otherwise format for min attr. isn't valid (valid format: HH:MM)
        if (betweenNumber(minute, 0, 9)) {

            // Convert back to string
            minute = minute.toString();

            // Append a zero (0) to it
            minute = minute.padStart(2, 0);

        } else {

            // Convert back to string
            minute = minute.toString();

        }

        // Combine both hour and minute
        const MINIMUM_TIME = hour + ":" + minute;

        // Set the modified time as a minimum
        INPUT_EIND_TIJD.setAttribute('min', MINIMUM_TIME);

    });

    INPUT_EIND_TIJD.addEventListener('invalid', () => {

        // Show red outline as error indicator
        INPUT_EIND_TIJD.classList.add('invalid-outline');

        // Show error message
        INPUT_EIND_TIJD.nextElementSibling.classList.add('validity-show');

        // Indicate user which time needs to be given as a minimum
        document.getElementById('min-time').innerText = INPUT_START_TIJD.value;

        INPUT_EIND_TIJD.addEventListener('input', () => {
            //alert('verandere!');
        });

    });
</script>