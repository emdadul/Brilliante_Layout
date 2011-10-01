<?php
// ----------------- Menus w/fallback for older WP versions --------------------
//
register_nav_menu( 'primary', __( 'Primary Menu', 'brilliante_layout' ) );
// Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
function brilliante_layout_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'brilliante_layout_page_menu_args' );
?>
<?php
// ----------------- Widget-Ready Sidebar ---------------------------------------
//
if ( function_exists('register_sidebar') )
    register_sidebar(array(
				'name' => __( 'Sidebar', 'brilliante_layout' ),
				'id' => 'sidebar',
        'before_widget' => '<li class="sidebar-widget"><div class="sidebar-widget" id="%1$s">',
        'after_widget' => '</div></li>',
        'before_title' => '<h2 class="sidebar-widget"><span>',
        'after_title' => '</span></h2>',
    ));
?>
<?php
// ----------------- Widget-Ready Footer ----------------------------------------
//
if ( function_exists('register_sidebar') )
    register_sidebar(array(
				'name' => __( 'Footer First', 'brilliante_layout' ),
				'id' => 'footer-first',
        'before_widget' => '<div class="footer-widget">',
        'after_widget' => '</div>',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
    ));
	if ( function_exists('register_sidebar') )
	    register_sidebar(array(
					'name' => __( 'Footer Second', 'brilliante_layout' ),
					'id' => 'footer-second',
	        'before_widget' => '<div class="footer-widget">',
	        'after_widget' => '</div>',
	        'before_title' => '<h2>',
	        'after_title' => '</h2>',
	    ));
		if ( function_exists('register_sidebar') )
		    register_sidebar(array(
						'name' => __( 'Footer Third', 'brilliante_layout' ),
						'id' => 'footer-third',
		        'before_widget' => '<div class="footer-widget">',
		        'after_widget' => '</div>',
		        'before_title' => '<h2>',
		        'after_title' => '</h2>',
		    ));
?>
<?php
// ----------------- Post Featured Images support -------------------------------
// Watch out for the array( 'post','slides' ) in here...
if ( function_exists( 'add_theme_support' ) ) { // Added in 2.9
	add_theme_support( 'post-thumbnails', array( 'post','slides' ) ); // Add featured images to posts
	set_post_thumbnail_size( 140, 140, true ); // Normal post thumbnails
	add_image_size( 'single-post-thumbnail', 542,220, true ); // Single Post thumbnail size
}
?>
<?php
// ----------------- Ugly Admin Bar ---------------------------------------------
//
function mytheme_admin_bar_render() {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('comments');
        $wp_admin_bar->add_menu( array(
        'parent' => 'new-content',
        'id' => 'new_media',
        'title' => __('Media'),
        'href' => admin_url( 'media-new.php')
    ) );
}
add_action( 'wp_before_admin_bar_render', 'mytheme_admin_bar_render' );
?>
<?php
// ----------------- Comments System ---------------------------------------------
//
function theme_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
     <div id="comment-<?php comment_ID(); ?>">
      <div class="comment-author vcard">
         <?php echo get_avatar($comment,$size='48',$default='<path_to_url>' ); ?>

         <?php printf(__('<cite class="fn">%s</cite> <span class="says">said on</span>'), get_comment_author_link()) ?>

				<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'  ','') ?>
				
      </div>
      <?php if ($comment->comment_approved == '0') : ?>
         <em><?php _e('Your comment is awaiting moderation.') ?></em>
         <br />
      <?php endif; ?>

      
      <?php comment_text() ?>

      <div class="reply">
         <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
      </div>
     </div>
<?php
        }
?>
<?php
include 'lib/twitter-widget-pro.php';
include 'lib/html-bios.php';
include 'lib/author-bio-widget.php';
include 'lib/flickr-thumbs-widget.php';
?>
<?php
/*******************************
  THEME OPTIONS PAGE
********************************/

add_action('admin_menu', 'brilliante_layout_theme_page');
function brilliante_layout_theme_page ()
{
	if ( count($_POST) > 0 && isset($_POST['brilliante_layout_settings']) )
	{
		$options = array ('twitter_user','flickr_user');

		foreach ( $options as $opt )
		{
			delete_option ( 'brilliante_layout_'.$opt, $_POST[$opt] );
			add_option ( 'brilliante_layout_'.$opt, $_POST[$opt] );	
		}			

	}
	add_menu_page(__('Brilliante Options'), __('Brilliante Options'), 'edit_themes', basename(__FILE__), 'brilliante_layout_settings');
	add_submenu_page(__('Brilliante Options'), __('Brilliante Options'), 'edit_themes', basename(__FILE__), 'brilliante_layout_settings');
}
function brilliante_layout_settings()
{?>
<div class="wrap">
	<h2>Brilliante Layout Options Panel</h2>

<form method="post" action="">

	<fieldset style="border:1px solid #ddd; padding-bottom:20px; margin-top:20px;">
	<legend style="margin-left:5px; padding:0 5px; color:#2481C6;text-transform:uppercase;"><strong>Social Links</strong></legend>
		<table class="form-table">
		<tr valign="top">
			<th scope="row"><label for="twitter_user">Twitter Username (without @)</label></th>
			<td>
				<input name="twitter_user" type="text" id="twitter_user" value="<?php echo get_option('brilliante_layout_twitter_user'); ?>" class="regular-text" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="flickr_user">Flickr Username</label></th>
			<td>
				<input name="flickr_user" type="text" id="flickr_user" value="<?php echo get_option('brilliante_layout_flickr_user'); ?>" class="regular-text" />
			</td>
		</tr>
    </table>
    </fieldset>
		<p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="Save Changes" />
		<input type="hidden" name="brilliante_layout_settings" value="save" style="display:none;" />
		</p>
</form>
</div>
<?php }
?>