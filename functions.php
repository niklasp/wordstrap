<?php
/**
 * wordstrap functions and definitions
 *
 * @package wordstrap
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

if ( ! function_exists( 'wordstrap_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function wordstrap_setup() {

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on wordstrap, use a find and replace
	 * to change 'wordstrap' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'wordstrap', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails on posts and pages
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'wordstrap' ),
	) );

	/**
	 * Enable support for Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

	/**
	 * Setup the WordPress core custom background feature.
	 */
	add_theme_support( 'custom-background', apply_filters( 'wordstrap_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // wordstrap_setup
add_action( 'after_setup_theme', 'wordstrap_setup' );



/**
 * Register widgetized area and update sidebar with default widgets
 */
function wordstrap_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar (id=sidebar-1)', 'wordstrap' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	));
	register_sidebar( array(
		'name'          => __( 'Sidebar2 (id=sidebar-2)', 'wordstrap' ),
		'id'            => 'sidebar-2',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	));
    register_sidebar(array(
    'name' => __('Footer', 'wordstrap'),
    'id' => 'sidebar-footer',
    'before_widget' => '<section class="widget %1$s %2$s col-md-4"><div class="widget-inner">',
    'after_widget' => '</div></section>',
    'before_title' => '<h4>',
    'after_title' => '</h4>',
  ));
}
add_action( 'widgets_init', 'wordstrap_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function wordstrap_scripts() {
	wp_enqueue_style( 'bootstrap', get_stylesheet_directory_uri() . '/bootstrap/css/bootstrap.min.css');
	wp_enqueue_style( 'customcss', get_stylesheet_directory_uri() . '/css/wordstrap.css');
	wp_enqueue_script( 'bootstrapjs', get_template_directory_uri() . '/bootstrap/js/bootstrap.min.js', array('jquery'), false, true );
	wp_enqueue_script( 'classie', get_template_directory_uri() . '/js/classie.js', array('jquery'), false, true );
	wp_enqueue_script( 'kunschtjs', get_template_directory_uri() . '/js/wordstrap.js', array('jquery'), false, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'wordstrap-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
}
add_action( 'wp_enqueue_scripts', 'wordstrap_scripts' );

}

// REPLACE "current_page_" WITH CLASS "active" 
function current_to_active($text){ $replace = array( 
	// List of classes to replace with "active" 
	'current_page_item' => 'active', 'current_page_parent' => 'active', 'current_page_ancestor' => 'active', ); 
	$text = str_replace(array_keys($replace), $replace, $text); return $text; 
} 
add_filter ('wp_nav_menu','current_to_active');

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
