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
                <label for="" id="label-input-taaknaam">Vul taak in *</label>
                <input type="text" name="input-taaknaam" id="input-taaknaam">
                <label for="" id="label-input-starttijd">Selecteer starttijd *</label>
                <input type="text" id="input-starttijd">
                <label for="" id="label-input-eindtijd">Selecteer eindtijd *</label>
                <input type="text" id="input-eindtijd">
                <input type="submit" name="toevoegen-taak" class="ivs-button" value="Toevoegen taak">
            </form>
        </div>
        <div class="grid-3-5">
            <h2 class="subheading-2-admin">Overzicht roosters</h2>
            <p>accordion here</p>
        </div>
    </div> <!-- .grid-container -->
</div> <!-- .wrap -->