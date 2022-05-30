<?php 
// Insert the WordPress head (otherwise styles won't load!)
wp_head();

// Retrieve details from logged in user for further usage on the page
$logged_in_user = wp_get_current_user();

/**
 * Include class related to pages
 * @author Yari Morcus
*/
require_once IVS_MEELOOP_PORTAAL_PLUGIN_INCLUDES_VIEWS_DIR . '/PageView.php';

// Check if user is not logged in
if ( ! is_user_logged_in() ) {

    // Instantiate the PageView class
    $page_view = new PageView();

    // If user is not logged in, display an additional error message
    $page_view->showErrorMessage();
}

?>
<div class="body__area">
    <div class="dashboard">
        <header class="dashboard__header">
            <a href="<?php echo get_site_url() . '/dashboard'?>">
                <img src="../wp-content/plugins/ivs-meeloop-portaal/includes/views/assets/images/#" alt=""
                class="dashboard__logo" width="250">
            </a>
        </header> <!-- .dashboard__header -->
        <nav class="dashboard__nav">
            <div class="dashboard__profile">
                <img src="<?php echo esc_url( get_avatar_url( $logged_in_user->ID, ['size' => '70'] ) ) ;?>" alt=""
                    class="profile__avatar">
                <label class="profile__message">Welkom, <?php echo $logged_in_user->display_name; ?></label>
            </div> <!-- .dashboard__profile -->
            <div class="dashboard__logout">
                <a href="<?php echo wp_logout_url(); ?>" class="logout__link">Uitloggen</a>
            </div> <!-- .dashboard__logout -->
        </nav> <!-- .dashboard__nav -->
        <main class="dashboard__main">
            <div class="main__content">
                <h1 class="heading-1"><?php echo get_the_title( $post->ID ); ?></h1>
                <div class="snelle__navigatie">
                    <h2 class="heading-2">Snelle navigatie</h2>
                    <ol class="snelle__navigatie--ol">
                        <li class="snelle_navigatie--li"><a href="#">Datum meeloopdag</a></li>
                        <li class="snelle_navigatie--li"><a href="#">Mijn rooster</a></li>
                        <li class="snelle_navigatie--li"><a href="#">Opdrachten</a></li>
                        <li class="snelle_navigatie--li"><a href="#">Informatiebronnen</a></li>
                    </ol>
                </div> <!-- .snelle__navigatie -->
                <div class="row-1">
                    <div class="dashboard__item item__datum--container">
                        <h2 class="heading-2">Datum meeloopdag</h2>
                        <div class="item__datum">21 december 2021</div>
                    </div> <!-- . item__datum--container -->
                    <div class="dashboard__item item__rooster--container">
                        <h2 class="heading-2">Mijn rooster</h2>
                        <a href="<?php echo get_permalink( get_page_by_title( 'Bekijk rooster' )->ID ); ?>" class="item__link">Bekijk rooster</a>
                    </div> <!-- .item__rooster--container -->
                </div> <!-- .row-1 -->
                <div class="row-2">
                    <div class="dashboard__item item__opdrachten--container">
                        <h2 class="heading-2">Opdrachten</h2>
                        <a href="<?php echo get_permalink( get_page_by_title( 'Inzien opdrachten' )->ID ); ?>" class="item__link">Inzien opdrachten</a>
                    </div> <!-- . item__opdrachten--container -->
                    <div class="dashboard__item item__opdrachten--container">
                        <a href="<?php echo get_permalink( get_page_by_title( 'Indienen opdrachten' )->ID ); ?>" class="item__link">Indienen opdrachten</a>
                    </div> <!-- .item__opdrachten--container -->
                </div> <!-- .row-2 -->
                <div class="row-3">
                    <div class="dashboard__item item__informatie--container">
                        <h2 class="heading-2">Informatiebronnen</h2>
                        <a href="#" class="item__link">Informatie opleiding</a>
                    </div> <!-- . item__opdrachten--container -->
                    <div class="dashboard__item item__informatie--container">
                        <a href="#" class="item__link">Informatie leerbedrijf</a>
                    </div> <!-- .item__informatie--container -->
                    <div class="dashboard__item item__informatie--container">
                        <a href="#" class="item__link">Informatie opdrachten</a>
                    </div> <!-- .item__informatie--container -->
                </div> <!-- .row-3 -->
            </div> <!-- .main__content -->
        </main> <!-- .dashboard__main -->
    </div> <!-- .dashboard -->
</div> <!-- .body__area -->