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
                <tr>
                    <td>Mediavormgeving</td>
                    <td><a href="#" id="anchor-edit-opleiding"><img src="<?php echo plugin_dir_url( __DIR__ ) . 'assets/icons/pencil-icon.svg'; ?>" alt="" width="25" height="25"></a></td>
                </tr>
                <tr>
                    <td>Mediavormgeving</td>
                    <td><a href="#" id="anchor-edit-opleiding"><img src="<?php echo plugin_dir_url( __DIR__ ) . 'assets/icons/pencil-icon.svg'; ?>" alt="" width="25" height="25"></a></td>                </tr>
            </table> <!-- admin-meeloop-table -->
        </section>
        <section>
            <h2 class="subheading-2-admin">Toevoegen opleiding</h2>
            <form action="" method="POST">
                <label for="toevoegen-opleiding" id="label-toevoegen-opleiding">Opleidingsnaam</label>
                <input type="text" name="input-opleidingsnaam" id="input-toevoegen-opleiding" maxlength="50">
                <input type="submit" name="registreer-meeloopdag" id="toevoegen-meeloopdag" value="Toevoegen opleiding">
            </form>
        </section>
    </div> <!-- .two-column-layout -->
</main> <!-- .wrap -->