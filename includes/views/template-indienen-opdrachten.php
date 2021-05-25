<?php 
// Insert the WordPress head (otherwise styles won't load!)
wp_head();

// Retrieve details from logged in user
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
                <img src="../wp-content/plugins/ivs-meeloop-portaal/includes/views/assets/images/ivs-logo-black.png" alt=""
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

            </div> <!-- .main__content -->
        </main> <!-- .dashboard__main -->
    </div> <!-- .dashboard -->
</div> <!-- .body__area -->