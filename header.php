<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<?php 
    if (is_home() || is_front_page()) {
        $meta_description = esc_html( get_bloginfo( 'description' ) );
        $meta_title = esc_html( get_bloginfo( 'name' ) );
        $meta_url = esc_url( home_url( "/" ) );
        $meta_img = "";
    } else {
        $meta_description = strip_tags( get_the_excerpt() );
        $meta_title = strip_tags( get_the_title() );
        $meta_url = esc_url( get_permalink() );
        $meta_img = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
    }
?>
<head prefix="og:http://ogp.me/ns#">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="format-detection" content="telephone=no">
    <meta name="description" content="<?php echo $meta_description; ?>" >

    <meta property="og:title" content="<?php echo $meta_title; ?>">
    <meta property="og:description" content="<?php echo $meta_description; ?>">
    <meta property="og:url" content="<?php echo $meta_url; ?>">
    <meta property="og:image" content="<?php echo $meta_img;?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?php echo esc_html( get_bloginfo( 'name' ) ); ?>">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="<?php //@Twitterアカウント名 ;?>">

    <link rel="shortcut icon" href="">

	<title><?php echo esc_html( get_the_title() ); ?></title>
    <?php wp_head(); ?>
    
    <script>function navopen() {
        document.getElementById("hamburger").classList.toggle("js-open");
        document.getElementById("gnav-container").classList.toggle("js-open");
    }</script>


</head>
<body>
    <header>
        
    </header>