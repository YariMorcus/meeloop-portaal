<?php 

// Include the Rooster model
include IVS_MEELOOP_PORTAAL_PLUGIN_INCLUDES_MODEL_DIR . '/Rooster.php';

// Include the Meeloopdag model
include IVS_MEELOOP_PORTAAL_PLUGIN_INCLUDES_MODEL_DIR . '/Meeloopdag.php';

// Declare class variable for Rooster
$rooster = new Rooster();

// Declare class variable for Meeloopdag
$meeloopdag = new Meeloopdag();

// Set base url to current page and add page specific vars
$base_url = get_admin_url() . 'admin.php';
$params = array( 'page' => basename( __FILE__, '.php') );

$base_url = add_query_arg( $params, $base_url );

// Get the POST data in filtered array
$post_array = $rooster->getPostValues();

// echo __FILE__ . __LINE__ . "<br>";
// echo '<pre>';
// var_dump($post_array);
// echo '</pre>';

// Collect errors
$error = FALSE;

if( !empty( $post_array ) ) {

    // Check the add form
    $add = FALSE;
    
    if( isset( $post_array['toevoegen-taak'] ) ) {

        // Save the rooster (in English: schedule)
        $result = $rooster->save( $post_array );

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
            <h2 class="subheading-2-admin">Toevoegen taak (rooster)</h2>
            <form action="<?php echo $base_url; ?>" method="post" id="formulier-toevoegen-rooster">

            <?php
            // Check if user has registered any meeloopdagen.
            // If NOT, don't show form, but a message instead
            if( $meeloopdag->getNrOfRegisteredMeeloopdagen() < 1 ) {
                
                $params = array( 'page' => 'toevoegen-meeloopdag');
                $toevoegen_meeloopdag_url = add_query_arg( $params,  $base_url );
                
                ?>
                <span><strong>Let op:</strong></span>
    
                <p class="paragraph-main">Omdat u nog <strong>geen</strong> meeloopdag heeft geregistreerd, kunt u geen rooster/taak aanmaken.
                Wanneer u een meeloopdag heeft geregistreerd, zal hier het formulier verschijnen waarmee u een rooster/taak kunt maken.
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
            </form> <!-- #formulier-toevoegen-rooster -->
        </div>
        <div>
            <h2 class="subheading-2-admin">Overzicht roosters</h2>
            <?php 
            if( $rooster->getNrOfRegisteredRoosters() < 1 ) {
                ?>
                <p class="paragraph-main">Er is op dit moment nog geen rooster/taak aangemaakt.
                    Maak een rooster/taak met behulp van het formulier aan uw linkerkant.
                </p>
                <?php
            } else {
                /**
                 TODO:  Recheck instructions below FIRST before moving on
                 
                    DONE 1. Retrieve all unique fk_meeloopdag_id from table ivs_mp_rooster
                    DONE 2. Store those in an array
                    DONE 3. Loop over the object
                    4. Execute a database query where taaknaam, starttijd and eindtijd are being returned, based on the given fk_meeloopdag_id
                    5. Store those results in an object
                */

                $meeloopdagen_list = $meeloopdag->getMeeloopdagenList();
                $meeloopagen_ids_list = array();

                
                foreach( $meeloopdagen_list as $idx => $meeloopdag ) {
                    $meeloopagen_ids_list[] = $meeloopdag->id; // Equivalent to array_push( $ids, $meeloopdag->id ) 
                }
                
                echo __FILE__ . __LINE__ . '<br>';
                foreach( $meeloopagen_ids_list as $meeloopdag_id ) {
                    
                    echo '<h3>' . $meeloopdag_id . '</h3>';
                    $rooster_meeloopdag = $rooster->getRoosterOfMeeloopdag( $meeloopdag_id );

                    echo '<pre>';
                    var_dump($rooster_meeloopdag); 
                    echo'</pre>';

                    ?>
                    


                    <?php
                }
                ?>
                <div id="accordion">
                    <h3 class="accordion-title">12 oktober 2021</h3>
                    <div>
                       <table class="ivs-table">
                           <thead>
                               <tr class="ivs-table-row">
                               <th class="ivs-table-th">Taak</th>
                               <th class="ivs-table-th">Starttijd</th>
                               <th class="ivs-table-th">Eindtijd</th>
                           </tr>
                           </thead>
                           <tbody>
                               <tr class="ivs-table-row">
                               <td class="ivs-table-td">Kennismaking IVS</td>
                               <td class="ivs-table-td">8:40</td>
                               <td class="ivs-table-td">9:30</td>
                           </tr>
                           <tr class="ivs-table-row">
                               <td class="ivs-table-td">Software Developer opdracht 1</td>
                               <td class="ivs-table-td">9:30</td>
                               <td class="ivs-table-td">10:10</td>
                           </tr>
                           <tr class="ivs-table-row">
                               <td class="ivs-table-td">Korte pauze</td>
                               <td class="ivs-table-td">10:10</td>
                               <td class="ivs-table-td">10:25</td>
                           </tr>
                           <tr class="ivs-table-row">
                               <td class="ivs-table-td">Software Developer opdracht 2</td>
                               <td class="ivs-table-td">10:25</td>
                               <td class="ivs-table-td">12:05</td>
                           </tr>
                           </tbody>
                       </table> <!-- .ivs-table -->
                    </div>
                    <h3 class="accordion-title">23 december 2021</h3>
                    <div>
                        <p>Mauris mauris ante, blandit et, ultrices a, suscipit eget, quam. Integer ut neque. Vivamus nisi metus, molestie vel,gravida in, condimentum sit amet, nunc. Nam a nibh. Donec suscipit eros. Nam mi. Proin viverra leo ut odio. Curabiturmalesuada. Vestibulum a velit eu ante scelerisque vulputate.</p>
                    </div>
                <?php
            }
            ?>
            </div> <!-- #accordion -->
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