<?php 

?>

<div class="wrap">
    <h1 class="main-heading-admin">Roosters meeloopdag(en)</h1>
    <p class="paragraph-main">Op deze pagina is een overzicht van alle aangemaakte roosters te vinden voor de geregistreerde meeloopdagen.

    Deze pagina biedt de docent ook de mogelijkheid om een rooster (taak) aan te maken.
    </p> <!-- .paragraph-main -->
    <div class="grid-container">
        <div class="grid-2-5">
            <h2 class="subheading-2-admin">Toevoegen taak (rooster)</h2>
            <form action="#" method="post" id="formulier-toevoegen-rooster">
                <label for="selecteer-meeloopdag" id="label-selecteer-meeloopdag">Selecteer meeloopdag *</label>
                <select name="meeloopdag" id="selecteer-meeloopdag">
                    <option value="hier-id-van-meeloopdag">23 oktober 2021</option>
                </select>
                <label for="input-taaknaam" id="label-input-taaknaam">Vul taak in *</label>
                <input type="text" name="input-taaknaam" id="input-taaknaam">
                <label for="input-starttijd"" id="label-input-starttijd">Selecteer starttijd *</label>
                <input type="time" id="input-starttijd">
                <label for="input-eindtijd" id="label-input-eindtijd">Selecteer eindtijd *</label>
                <input type="time" id="input-eindtijd">
                <input type="submit" name="toevoegen-taak" class="ivs-button" value="Toevoegen taak">
            </form>
        </div>
        <div class="grid-3-5">
            <h2 class="subheading-2-admin">Overzicht roosters</h2>
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
                   </table>
                </div>
                <h3 class="accordion-title">23 december 2021</h3>
                <div>
                    <p>Mauris mauris ante, blandit et, ultrices a, suscipit eget, quam. Integer ut neque. Vivamus nisi metus, molestie vel,gravida in, condimentum sit amet, nunc. Nam a nibh. Donec suscipit eros. Nam mi. Proin viverra leo ut odio. Curabiturmalesuada. Vestibulum a velit eu ante scelerisque vulputate.</p>
                </div>
            </div>
        </div>
    </div> <!-- .grid-container -->
</div> <!-- .wrap -->