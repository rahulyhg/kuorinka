<?php
/**
 * Kuorinka functions and definitions
 *
 * @package Kuorinka
 */

/**
 * The current version of the theme.
 */
define( 'KUORINKA_VERSION', '1.5.0' );

/**
 * The suffix to use for scripts.
 */
if ( ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ) {
	define( 'KUORINKA_SUFFIX', '' );
} else {
	define( 'KUORINKA_SUFFIX', '.min' );
}

if ( ! function_exists( 'kuorinka_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function kuorinka_setup() {

	/**
	* Set the content width based on the theme's design and stylesheet.
	*/
	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 880; /* pixels */
	}

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Kuorinka, use a find and replace
	 * to change 'kuorinka' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'kuorinka', get_template_directory() . '/languages' );

	/* Add default posts and comments RSS feed links to head. */
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/* This theme uses wp_nav_menu() in three locations. */
	register_nav_menu( 'primary', __( 'Primary Menu', 'kuorinka' ) );
	register_nav_menu( 'social', __( 'Social Menu', 'kuorinka' ) );
	
	/* Show portfolio menu only if Custom Content Portfolio Plugin is active. */
	if ( post_type_exists( 'portfolio_item' ) || class_exists( 'Custom_Content_Portfolio' ) ) {
		register_nav_menu( 'portfolio', __( 'Portfolio Menu', 'kuorinka' ) );
	}
	
	/* Show team member menu only if Team Member Plugin is active. For some reason post_type_exists check doesn't work. */
	if ( post_type_exists( 'team-member' ) || class_exists( 'Woothemes_Our_Team' ) ) {
		register_nav_menu( 'team-member', __( 'Team Member Menu', 'kuorinka' ) );
	}
	
	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'audio', 'aside', 'image', 'video', 'quote', 'link', 'status', 'gallery'
	) );
	
	/* Add support for WP title. */
	add_theme_support( 'title-tag' );
	
	/* Add custom image sizes. */
	add_image_size( 'kuorinka-large', 720, 405, true );
	add_image_size( 'kuorinka-thumbnail', 250, 250, true );
	
	/* Add theme support for refresh widgets. */
	add_theme_support( 'customize-selective-refresh-widgets' );
	
	/* Add theme support for site logo. */
	add_theme_support( 'custom-logo', apply_filters( 'kuorinka_custom_logo_arguments', array(
		'height'      => 50,
		'width'       => 50,
		'flex-width'  => true,
		'flex-height' => true,
	) ) );
	
	/* Enable theme layouts. */
	add_theme_support( 'theme-layouts', array( 'default' => is_rtl() ? '2c-r' :'2c-l' ) );
	
	/* Add theme layouts support to core and custom post types. */
	add_post_type_support( 'post',              'theme-layouts' );
	add_post_type_support( 'page',              'theme-layouts' );
	add_post_type_support( 'attachment',        'theme-layouts' );
	add_post_type_support( 'forum',             'theme-layouts' );
	add_post_type_support( 'literature',        'theme-layouts' );
	add_post_type_support( 'portfolio_item',    'theme-layouts' );
	add_post_type_support( 'portfolio_project', 'theme-layouts' );
	add_post_type_support( 'product',           'theme-layouts' );
	add_post_type_support( 'restaurant_item',   'theme-layouts' );
	
	/* Add theme support for breadcrumb trail. */
	add_theme_support( 'breadcrumb-trail' );
	
	/* Add excerpt support for team member. */
	add_post_type_support( 'team-member', 'excerpt' );
	
	/* Add custom-header support for portfolio. */
	add_post_type_support( 'portfolio_item', 'custom-header' );
	
	/* Add editor styles. */
	add_editor_style( kuorinka_get_editor_styles() );
	
	/* Add FluidVid JS when oEmbeds are around. */
	add_filter( 'wp_video_shortcode', 'kuorinka_fluidvids' );
	add_filter( 'embed_oembed_html', 'kuorinka_fluidvids' );
	add_filter( 'video_embed_html', 'kuorinka_fluidvids' );
	
}
endif; // kuorinka_setup
add_action( 'after_setup_theme', 'kuorinka_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function kuorinka_widgets_init() {

	$sidebar_primary_args = array(
		'id'            => 'primary',
		'name'          => _x( 'Primary', 'sidebar', 'kuorinka' ),
		'description'   => __( 'The main sidebar. It is displayed on right side of the page.', 'kuorinka' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	);
	
	$sidebar_header_args = array(
		'id'            => 'header',
		'name'          => _x( 'Header', 'sidebar', 'kuorinka' ),
		'description'   => __( 'Header sidebar. It is displayed on top of the page.', 'kuorinka' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	);
	
	$sidebar_subsidiary_args = array(
		'id'            => 'subsidiary',
		'name'          => _x( 'Subsidiary', 'sidebar', 'kuorinka' ),
		'description'   => __( 'A sidebar located in the footer of the site.', 'kuorinka' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	);
	
	$sidebar_front_page_args = apply_filters( 'kuorinka_sidebar_front_page_args', array(
		'id'            => 'front-page',
		'name'          => _x( 'Front Page', 'sidebar', 'kuorinka' ),
		'description'   => __( 'A sidebar located in the Front Page Template.', 'kuorinka' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	) );
	
	/* Register sidebars. */
	register_sidebar( $sidebar_primary_args );
	register_sidebar( $sidebar_header_args );
	register_sidebar( $sidebar_subsidiary_args );
	register_sidebar( $sidebar_front_page_args );
	
}
add_action( 'widgets_init', 'kuorinka_widgets_init' );

/**
 * Return the Google font stylesheet URL
 */
function kuorinka_fonts_url() {

	$fonts_url = '';

	/* Translators: If there are characters in your language that are not
	 * supported by Source Sans Pro, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$source_sans_pro = _x( 'on', 'Source Sans Pro font: on or off', 'kuorinka' );

	/* Translators: If there are characters in your language that are not
	 * supported by Roboto Condensed, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$roboto_condensed = _x( 'on', 'Roboto Condensed font: on or off', 'kuorinka' );

	if ( 'off' !== $source_sans_pro || 'off' !== $roboto_condensed ) {
		$font_families = array();

		if ( 'off' !== $source_sans_pro )
			$font_families[] = 'Source Sans Pro:400,600,700,400italic,600italic,700italic';

		if ( 'off' !== $roboto_condensed )
			$font_families[] = 'Roboto Condensed:300,400,700,300italic,400italic,700italic';

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);
		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return esc_url_raw( $fonts_url );
}

/**
 * Enqueue scripts and styles.
 */
function kuorinka_scripts() {

	/* Enqueue fonts, used in the main stylesheet. */
	wp_enqueue_style( 'kuorinka-fonts', kuorinka_fonts_url(), array(), null );
	
	/* Add Genericons font, used in the main stylesheet. */
	wp_enqueue_style( 'genericons', trailingslashit( get_template_directory_uri() ) . 'fonts/genericons/genericons' . KUORINKA_SUFFIX . '.css', array(), '3.4' );
	
	/* Enqueue parent theme styles if using child theme. */
	if ( is_child_theme() ) {
		wp_enqueue_style( 'kuorinka-parent-style', trailingslashit( get_template_directory_uri() ) . 'style' . KUORINKA_SUFFIX . '.css', array(), KUORINKA_VERSION );
	}
	
	/* Enqueue active theme styles. */
	wp_enqueue_style( 'kuorinka-style', get_stylesheet_uri(), array(), kuorinka_theme_version() );
	
	
	/* Register Fluidvids. */
	wp_register_script( 'kuorinka-fluidvids', trailingslashit( get_template_directory_uri() ) . 'js/fluidvids/fluidvids' . KUORINKA_SUFFIX . '.js', array(), KUORINKA_VERSION, true );
	
	/* Register Fluidvids settings. */
	wp_register_script( 'kuorinka-fluidvids-settings', trailingslashit( get_template_directory_uri() ) . 'js/fluidvids/settings' . KUORINKA_SUFFIX . '.js', array( 'kuorinka-fluidvids' ), KUORINKA_VERSION, true );
	
	/* Enqueue responsive navigation if primary menu is in use. */
	if ( has_nav_menu( 'primary' ) ) {
		
		/* Load multi-level script if it's enabled. */
		if ( get_theme_mod( 'enable_dropdown' ) ) {
			wp_enqueue_script( 'kuorinka-navigation', get_template_directory_uri() . '/js/multilevel-responsive-nav' . KUORINKA_SUFFIX . '.js', array(), KUORINKA_VERSION, true );
		} else {
			wp_enqueue_script( 'kuorinka-navigation', get_template_directory_uri() . '/js/responsive-nav' . KUORINKA_SUFFIX . '.js', array(), KUORINKA_VERSION, true );
		}
		
		/* Enqueue responsive navigation settings. */
		wp_enqueue_script( 'kuorinka-settings', trailingslashit( get_template_directory_uri() ) . 'js/settings' . KUORINKA_SUFFIX . '.js', array( 'kuorinka-navigation' ), KUORINKA_VERSION, true );
		
		/* Load settings for multilevel dropdown if we use it. We need to load this anyway so we can check to settings from navSettings. */
		wp_localize_script( 'kuorinka-settings', 'navSettings', array(
			'expand'   => '<span class="screen-reader-text">' . __( 'Expand child menu', 'kuorinka' ) . '</span>',
			'collapse' => '<span class="screen-reader-text">' . __( 'Collapse child menu', 'kuorinka' ) . '</span>',
			'dropdown' => get_theme_mod( 'enable_dropdown' ) ? true : false,
		) );
		
	}
	
	/* Enqueue functions. */
	wp_enqueue_script( 'kuorinka-script', get_template_directory_uri() . '/js/functions' . KUORINKA_SUFFIX . '.js', array(), KUORINKA_VERSION, true );
	
	/* Enqueue comment reply. */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'kuorinka_scripts' );

/**
 * Get theme version number, works also for child themes.
 *
 * @since  1.5.0
 * @return string $theme_version
 */
function kuorinka_theme_version() {
	$theme = is_child_theme() ? wp_get_theme( get_stylesheet() ) : wp_get_theme( get_template() );
	return $theme_version = $theme->get( 'Version' );
}

/**
 * Function for deciding which pages should have a one-column layout.
 *
 * @since  1.0.0
 */
function kuorinka_one_column() {

	if ( is_page_template( 'pages/front-page.php' ) ) {
		add_filter( 'theme_mod_theme_layout', 'kuorinka_theme_layout_one_column' );
	}
	elseif ( is_attachment() && wp_attachment_is_image() ) {
		add_filter( 'theme_mod_theme_layout', 'kuorinka_theme_layout_one_column' );
	}
	
}
add_action( 'template_redirect', 'kuorinka_one_column' );

/**
 * Filters 'get_theme_layout' by returning 'layout-1c'.
 *
 * @since  1.0.0
 * @param  string $layout The layout of the current page.
 * @return string
 */
function kuorinka_theme_layout_one_column( $layout ) {
	return '1c';
}

/**
 * Change [...] to ... Read more.
 *
 * @since 1.0.0
 */
function kuorinka_excerpt_more() {

	/* Translators: The %s is the post title shown to screen readers. */
	$text = sprintf( __( 'Read more %s', 'kuorinka' ), '<span class="screen-reader-text">' . get_the_title() . '</span>' );
	$more = sprintf( '&hellip; <span class="kuorinka-read-more"><a href="%s" class="more-link">%s</a></span>', esc_url( get_permalink() ), $text );

	return $more;

}
add_filter( 'excerpt_more', 'kuorinka_excerpt_more' );

/**
 * Counts widgets number in subsidiary sidebar and ads css class (.sidebar-subsidiary-$number) to body_class.
 * Used to increase / decrease widget size according to number of widgets.
 * Example: if there's one widget in subsidiary sidebar - widget width is 100%, if two widgets, 50% each...
 * @author    Sinisa Nikolic
 * @copyright Copyright (c) 2012
 * @link      http://themehybrid.com/themes/sukelius-magazine
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @since     1.0.0
 */
function kuorinka_subsidiary_classes( $classes ) {
    
	if ( is_active_sidebar( 'subsidiary' ) ) {
		
		$the_sidebars = wp_get_sidebars_widgets();
		$num = count( $the_sidebars['subsidiary'] );
		$classes[] = 'sidebar-subsidiary-' . $num;
		
    }
    
    return $classes;
	
}
add_filter( 'body_class', 'kuorinka_subsidiary_classes' );

/**
 * Counts widgets number in front page sidebar and ads css class (.sidebar-subsidiary-$number) to body_class.
 * Used to increase / decrease widget size according to number of widgets.
 * Example: if there's one widget in subsidiary sidebar - widget width is 100%, if two widgets, 50% each...
 * @author    Sinisa Nikolic
 * @copyright Copyright (c) 2012
 * @link      http://themehybrid.com/themes/sukelius-magazine
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @since     1.0.0
 */
function kuorinka_front_page_classes( $classes ) {
    
	if ( is_active_sidebar( 'front-page' ) && ( is_page_template( 'pages/front-page.php' ) ) ) {
		
		$the_sidebars = wp_get_sidebars_widgets();
		$num = count( $the_sidebars['front-page'] );
		$classes[] = 'sidebar-front-page-' . $num;
		
    }
    
    return $classes;
	
}
add_filter( 'body_class', 'kuorinka_front_page_classes' );

/**
 * Add sticky class to Front page template when using post_class.
 *
 * @since     1.0.0
 */
function kuorinka_front_page_sticky( $classes ) {
    
	/* Add sticky class also in front page template. */
	if ( is_page_template( 'pages/front-page.php' ) && is_sticky() ) {
		$classes[] = 'sticky';
    }
    
    return $classes;
	
}
add_filter( 'post_class', 'kuorinka_front_page_sticky' );

/**
 * Add header image and primary menu class.
 *
 * @since     1.0.0
 */
function kuorinka_extra_layout_classes( $classes ) {
	
	/* Add the '.custom-header-image' class if the user is using a custom header image. */
	if ( get_header_image() ) {
		$classes[] = 'custom-header-image';
	}
	
	/* Add the '.primary-menu-active' class if the user is using a primary menu. */
	if ( has_nav_menu( 'primary' ) ) {
		$classes[] = 'primary-menu-active';
	}
	
	/* Theme layouts. */
	if ( current_theme_supports( 'theme-layouts' ) ) {
		$classes[] = sanitize_html_class( 'layout-' . hybrid_get_theme_layout() );
	}
    
    return $classes;
	
}
add_filter( 'body_class', 'kuorinka_extra_layout_classes' );

/**
 * Add infinity sign after aside post format.
 *
 * @since  1.0.0
 * @return array
 */
function kuorinka_infinity_after_aside( $content ) {

	if ( has_post_format( 'aside' ) && !is_singular() ) {
		$content .= ' <a href="' . get_permalink() . '">&#8734;</a>';
	}
	
	return $content;
}
add_filter( 'the_content', 'kuorinka_infinity_after_aside', 9 ); // run before wpautop

/**
 * Callback function for adding editor styles. Use along with the add_editor_style() function.
 *
 * @author  Justin Tadlock, justintadlock.com
 * @link    http://themehybrid.com/themes/stargazer
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @since   1.0.0
 * @return  array
 */
function kuorinka_get_editor_styles() {

	/* Set up an array for the styles. */
	$editor_styles = array();

	/* Add the theme's editor styles. This also checks child theme's css/editor-style.css file. */
	$editor_styles[] = 'css/editor-style.css';
	
	/* Add genericons styles. */
	$editor_styles[] = 'fonts/genericons/genericons/genericons.css';
	
	/* Add theme fonts. */
	$editor_styles[] = kuorinka_fonts_url();

	/* Add the locale stylesheet. */
	$editor_styles[] = get_locale_stylesheet_uri();

	/* Return the styles. */
	return $editor_styles;
}

/**
 * Add JS when oEmbeds are around to make them responsive.
 *
 * @since  1.0.6
 * @access public
 * @return void
 */
function kuorinka_fluidvids( $html ) {
		
	/* Return if empty. */
	if ( empty( $html ) || ! is_string( $html ) ) {
		return $html;
	}
		
	/* Enqueue the JS file if Fluidvids plugin isn't doing it. has_action function doesn't seem to work in this case. */
	if ( ! wp_script_is( 'fluidvids', 'enqueued' ) ) {
		wp_enqueue_script( 'kuorinka-fluidvids' );
		wp_enqueue_script( 'kuorinka-fluidvids-settings' );
	}
	
	/* Return html. */
	return $html;	

}

/**
 * Flush out the transients used in front page WP Queries.
 *
 * @since   1.0.0
 */
function kuorinka_transient_flusher() {
	delete_transient( 'kuorinka_sticky_query' );
	delete_transient( 'kuorinka_post_query' );
}
add_action( 'save_post', 'kuorinka_transient_flusher' );

/**
 * Returns a link to the porfolio item URL if it has been set.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function kuorinka_get_portfolio_item_link() {

	$url = get_post_meta( get_the_ID(), 'portfolio_item_url', true );

	if ( !empty( $url ) ) {
		return '<a class="button portfolio-item-link" href="' . esc_url( $url ) . '">' . __( 'Visit Project', 'kuorinka' ) . '</a>';
	}

}

/**
 * Return the post URL.
 *
 * @uses get_url_in_content() to get the URL in the post meta (if it exists) or
 * the first link found in the post content.
 *
 * Falls back to the post permalink if no URL is found in the post.
 *
 * @copyright Twenty Thirteen Theme
 * @since 1.0.0
 *
 * @return string The Link format URL.
 */
function kuorinka_get_link_url() {
	$content = get_the_content();
	$has_url = get_url_in_content( $content );

	return ( $has_url ) ? $has_url : apply_filters( 'the_permalink', get_permalink() );
}

/**
 * Register layouts. This is the new way to do it in Hybrid Core version 3.0.0.
 *
 * @since 1.4.0
 */
function mina_olen_register_layouts() {
	
	hybrid_register_layout( '1c',   array( 'label' => esc_html__( '1 Column',                     'kuorinka' ), 'image' => '%s/images/layouts/1c.png'   ) );
	hybrid_register_layout( '2c-l', array( 'label' => esc_html__( '2 Columns: Content / Sidebar', 'kuorinka' ), 'image' => '%s/images/layouts/2c-l.png' ) );
	hybrid_register_layout( '2c-r', array( 'label' => esc_html__( '2 Columns: Sidebar / Content', 'kuorinka' ), 'image' => '%s/images/layouts/2c-r.png' ) );

}
add_action( 'hybrid_register_layouts', 'mina_olen_register_layouts' );

/**
 * Use a template for individual comment output.
 *
 * @param object $comment Comment to display.
 * @param int    $depth   Depth of comment.
 * @param array  $args    An array of arguments.
 *
 * @since 1.0.0
 */
function kuorinka_comment_callback( $comment, $args, $depth ) {
	include( locate_template( 'comment.php') );
}

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Implement the Custom Background feature.
 */
require get_template_directory() . '/inc/custom-background.php';

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

/**
 * Load theme layouts.
 */
require_once( get_template_directory() . '/inc/layouts.php' );

/**
 * Load media grabber.
 */
require_once( get_template_directory() . '/inc/media-grabber.php' );

/**
 * Load breadcrumb trail. Check that there is no Plugin version around.
 */
if( ! function_exists( 'breadcrumb_trail' ) ) {
	require_once( get_template_directory() . '/inc/breadcrumb-trail.php' );
}

/**
 * Load Schema.org file.
 */
require_once( get_template_directory() . '/inc/schema.php' );

/**
 * Load archive filters file.
 */
require_once( get_template_directory() . '/inc/archive-filters.php' );
