<?php
/**
 * GimmeSubs functions and definitions
 *
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Translated 1.0
 */

/**
 * Basic Functions
 *
 */


	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );


/**
 * Post Types
 *
 */

add_action( 'init', 'gimme_post_types' );

function gimme_post_types() {
    $labels = array(
        'name'                  => _x( 'Shows', 'Post type general name' ),
        'singular_name'         => _x( 'Show', 'Post type singular name' ),
        'menu_name'             => _x( 'Shows', 'Admin Menu text' ),
        'name_admin_bar'        => _x( 'Show', 'Add New on Toolbar' ),
        'add_new'               => __( 'Add New' ),
        'add_new_item'          => __( 'Add New Show' ),
        'new_item'              => __( 'New Show' ),
        'edit_item'             => __( 'Edit Show' ),
        'view_item'             => __( 'View Show' ),
        'all_items'             => __( 'All Shows' ),
        'search_items'          => __( 'Search Shows' ),
        'parent_item_colon'     => __( 'Parent Shows:' ),
        'not_found'             => __( 'No shows found.' ),
        'not_found_in_trash'    => __( 'No shows found in Trash.' ),
        'featured_image'        => _x( 'Show Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3' ),
        'set_featured_image'    => _x( 'Set show image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3' ),
        'remove_featured_image' => _x( 'Remove show image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3' ),
        'use_featured_image'    => _x( 'Use as show image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3' ),
        'archives'              => _x( 'Show archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4' ),

    );


        $sublabs = array(
        'name'                  => _x( 'Subtitles', 'Post type general name' ),
        'singular_name'         => _x( 'Subtitles', 'Post type singular name' ),
        'menu_name'             => _x( 'Subtitles', 'Admin Menu text' ),
        'name_admin_bar'        => _x( 'Show', 'Add New on Toolbar' ),
        'add_new'               => __( 'Add New' ),
        'add_new_item'          => __( 'Add Subtitles For An Episode' ),
        'new_item'              => __( 'New Episode Subtitles' ),
        'edit_item'             => __( 'Edit Episode Subtitles' ),
        'view_item'             => __( 'View Subtitles' ),
        'all_items'             => __( 'All Subtitles' ),
        'search_items'          => __( 'Search Subtitles' ),
        'parent_item_colon'     => __( 'Parent Subtitles:' ),
        'not_found'             => __( 'No subtitles found.' ),
        'not_found_in_trash'    => __( 'No subtitles found in Trash.' ),
        'featured_image'        => _x( 'Episode Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3' ),
        'set_featured_image'    => _x( 'Set Episode Image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3' ),
        'remove_featured_image' => _x( 'Remove episode image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3' ),
        'use_featured_image'    => _x( 'Use as episode image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3' ),
        'archives'              => _x( 'Subtitle archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4' ),
    );
 
    $shows = array(
        'labels'             => $labels,
        'public'             => true,
        'rewrite'            => array( 'slug' => 'show' ),
        'supports'           => array( 'title', 'thumbnail' ),
        'menu_icon' => 'dashicons-media-interactive',
        'show_in_rest'       => true,
    );

    $subtitles = array(
        'labels'             => $sublabs,
        'public'             => true,
        'supports'           => array( 'title', 'editor', 'thumbnail', 'author' ),
        'menu_icon' => 'dashicons-editor-aligncenter',
        'show_in_rest'       => true,
    );
 
    register_post_type( 'shows', $shows );
    register_post_type( 'subtitles', $subtitles );
}


function wpb_change_title_text( $title ){
     $screen = get_current_screen();
 
     if  ( 'subtitles' == $screen->post_type ) {
          $title = 'Enter Episode Title';
     }
 
     return $title;
}
 
add_filter( 'enter_title_here', 'wpb_change_title_text' );

add_filter( 'manage_edit-subtitles_columns', 'my_edit_movie_columns' ) ;

function my_edit_movie_columns( $columns ) {

    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => __( 'Title' ),
        'show' => __( 'Show' ),
        'date' => __( 'Date' )
    );

    return $columns;
}



add_action( 'manage_subtitles_posts_custom_column' , 'custom_columns', 10, 2 );

function custom_columns( $column, $post_id ) {
    global $post;
    switch ( $column ) {
        
        case 'show':
            $name = get_post_meta( $post_id, 'subtitle_for_id', true ); 

        $categories = get_the_terms($name, 'country');
 
         if ( ! empty( $categories ) ) {
         
                     $flag = get_term_meta( $categories[0]->term_id, 'flag', true );

             if ( ! empty( $flag ) ) {

            
            $out = '<span class="flag '.$flag.'"></span>';
        }
            }
            $out .= get_post_meta( $post_id, 'subtitle_for', true ); 
            echo $out;
            break;
    }
}


/**
 * Custom Taxonomy
 *
 */

add_action( 'init', 'create_countries', 0 );


function create_countries() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(

		'name'              => _x( 'Country', 'taxonomy general name'),
		'singular_name'     => _x( 'Countries', 'taxonomy singular name'),
		'search_items'      => __( 'Search Countries'),
		'all_items'         => __( 'All Countries'),
		'parent_item'       => __( 'Parent Country'),
		'parent_item_colon' => __( 'Parent Country:'),
		'edit_item'         => __( 'Edit Country'),
		'update_item'       => __( 'Update Country'),
		'add_new_item'      => __( 'Add New Country'),
		'new_item_name'     => __( 'New Country Name'),
		'menu_name'         => __( 'Countries'),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'country' ),
	);

	register_taxonomy( 'country', array( 'shows' ), $args );
}


add_action( 'init', 'country_flag_meta' );

function country_flag_meta() {

	$args1 = array(
    'type'      => 'string', // Validate and sanitize the meta value as a string.
        // Default: 'string'.  
        // In 4.7 one of 'string', 'boolean', 'integer', 'number' must be used as 'type'. 
    'description'    => 'A meta key associated with a string meta value.', // Shown in the schema for the meta key.
    'single'        => true, // Return a single value of the type. Default: false.
    'show_in_rest'    => true, // Show in the WP REST API response. Default: false.
);
 

    register_meta( 'country', 'flag', $args1 );
}


add_action( 'country_add_form_fields', 'country_add_flag' );

function country_add_flag() {

    wp_nonce_field( basename( __FILE__ ), 'jt_term_color_nonce' ); ?>

    <div class="form-field jt-term-color-wrap">
        <label for="jt-term-color"><?php _e( 'Flag', 'jt' ); ?></label>
        <input type="text" name="jt_term_color" id="jt-term-color" value="" class="jt-color-field" />
    </div>
<?php }


add_action( 'country_edit_form_fields', 'country_edit_flag' );

function country_edit_flag( $term ) {

   
    $color  = get_term_meta( $term->term_id, 'flag', true );

   ?>

    <tr class="form-field jt-term-color-wrap">
        <th scope="row"><label for="jt-term-color"><?php _e( 'Flag', 'jt' ); ?></label></th>
        <td>
            <?php wp_nonce_field( basename( __FILE__ ), 'jt_term_color_nonce' ); ?>
            <input type="text" name="jt_term_color" value="<?php echo $color; ?>" />
        </td>
    </tr>
<?php }

add_action( 'edit_country',   'jt_save_term_color' );
add_action( 'create_country', 'jt_save_term_color' );

function jt_save_term_color( $term_id ) {

    if ( ! isset( $_POST['jt_term_color_nonce'] ) || ! wp_verify_nonce( $_POST['jt_term_color_nonce'], basename( __FILE__ ) ) ) 
        return;

    $old_flag = get_term_meta( $term_id, 'flag', true );
    $new_flag = isset( $_POST['jt_term_color'] ) ? $_POST['jt_term_color'] : '';

    if ( $old_flag && '' === $new_flag )
        delete_term_meta( $term_id, 'flag' );

    else if ( $old_flag !== $new_flag )
        update_term_meta( $term_id, 'flag', $new_flag );


}


add_filter( 'manage_edit-country_columns', 'jt_edit_term_columns' );

function jt_edit_term_columns( $columns ) {

    $columns['flagged'] = __( 'Flag', 'jt' );

    return $columns;
}


add_filter( 'manage_country_custom_column', 'jt_manage_term_custom_column', 10, 3 );

function jt_manage_term_custom_column( $out, $column, $term_id ) {

    if ( 'flagged' === $column ) {

    	$flag = get_term_meta( $term_id, 'flag', true );

        if (! $flag)
        	$out = '';
        else {
        $out = '<span class="flag ';
        $out .=$flag;
        $out .= '" ></span>';}
    }

    return $out;
}


/**
 * Enqueue styles & scripts in Admin
 *
 */

function load_custom_wp_admin_style() {
        wp_register_style( 'flags_css', get_template_directory_uri() . '/css/flags.min.css', false, '1.2.0' );
        wp_enqueue_style( 'flags_css' );
        wp_enqueue_style( 'admin_css', get_template_directory_uri() . '/css/admin.css', false, '1.2.0' );
        wp_enqueue_style( 'bootstrap_admin', get_template_directory_uri() . '/css/bootstrap-grid.css', false, '1.1.0' );

        wp_enqueue_script( 'admin_js', get_template_directory_uri() . '/js/admin.js', false, '1.2.0' );


      $screen = get_current_screen();

      if ('shows' == $screen->post_type){

       // wp_enqueue_script( 'tiny_jquery', get_template_directory_uri() . '/js/jquery.tinymce.min.js', false, '1.0.0' );
        wp_enqueue_script( 'tiny_js', '//cloud.tinymce.com/stable/tinymce.min.js?apiKey=ucvbqtrax0meq720f0i577b98551fn2vvrryyv8t11sjwqs1', false, '1.0.0' );

      }



}
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );



/**
 * Meta Boxes
 *
 */
 


function gimme_register_meta_boxes() {
	global $post;
	 $selected = get_post_meta($post->ID, 'seasons', true);


    add_meta_box( 'show-seasons', __( 'Show Information', 'gimmesubs' ), 'season_count_callback', 'shows', 'side', 'default' );
    add_meta_box( 'show-subtitles', __( 'Subtitle Details ', 'gimmesubs' ), 'subtitle_callback', 'subtitles','side', 'high' );



    	for( $i= 1 ; $i <= $selected ; $i++ ){
    		$seasonID = 'show-seasons'.$i;
    add_meta_box( $seasonID, __( 'Season '.$i, 'gimmesubs' ), 'wpdocs_my_display_callback', 'shows', 'normal', 'low', array( 'season' => $i )  );
           
        }

            add_meta_box( 'watch-the-episode', __( 'Watch', 'gimmesubs' ), 'watch_callback', 'subtitles','normal', 'high' );



}
add_action( 'add_meta_boxes', 'gimme_register_meta_boxes' );





function watch_callback( $post ) { 
     wp_nonce_field( 'my_watch_nonce', 'watch_nonce' );

     $selected = get_post_meta($post->ID, 'watch_episode', true);

    ?>
<p style="display: table; width: 100%; margin-bottom: 5px;">
  <input type="url" name="watch_episode" value="<?php echo get_post_meta($post->ID, 'watch_episode', true)?>" style="width: 100%;" placeholder="Enter the URL here...">

  </p>

<?}

function wpdocs_my_display_callback( $post, $metabox ) {
     wp_nonce_field( 'my_add_subs_nonce', 'add_subs_nonce' );
   
    ?>
    <div id="meta_inner" >


    <?php
        $season = convert_number_to_words($metabox['args']['season']);

    $episodes = get_post_meta($post->ID,'subtitles_'.$season,false);


    $c = 0;
    if ( count( $episodes ) > 0 ) {
      foreach($episodes as $row => $innerArray){
      foreach($innerArray as $innerRow => $track){
       
         if ( isset( $track['episode'] )  ) 

          {?>



        <div class="row subadded newsub">


<div class="col-md-4 col-xs-12"><input type="text" name="subtitles<?php echo $season?>[<?php echo $c?>][episode]" placeholder="Select An Episode" value="<?php echo $track['episode']?>"></div>
<div class="col-md-1 col-xs-12 langcol"><input type="text" name="subtitles<?php echo $season?>[<?php echo $c?>][lang]" placeholder="Subtitle language?" value="<?php echo $track['lang']?>"></div>
<div class="col-md-5 col-xs-12" style="padding-right: 0;"><input type="url" name="subtitles<?php echo $season?>[<?php echo $c?>][watch]" placeholder="Where can this episode be watched?" value="<?php echo $track['watch']?>"></div>
           <div class="col-md-2">
           <div class="editit"><span class="dashicons dashicons-edit"></span></div>
           <div class="remove"><span class="dashicons dashicons-trash"></span></div>
           </div>

<div class="col-md-12">

<div class="textsubs">
<textarea class="subtext" name="subtitles<?php echo $season?>[<?php echo $c?>][text]"><?php echo $track['text']?></textarea></div></div>

</div>

<?php $c = $c +1;?>


               <?}
      }
      }

    }


 ?>

 <div id="here_<?php echo $season;?>"></div>


<p style="display: table; width: 100%; margin-bottom: 5px; text-align: center;">
  <a class="button addsubs" data-season="<?php echo $season?>">Add Subtitles</a>

  </p>

  </div>

<?}


 
function subtitle_callback( $post ) {
	 wp_nonce_field( 'my_subtitle_nonce', 'subtitle_nonce' );

	$selected = get_post_meta($post->ID, 'subtitle_for', true);
    $selectedid = get_post_meta($post->ID, 'subtitle_for_id', true);

    $epinumber = get_post_meta($post->ID, 'episode_number', true);
    $seanumber = get_post_meta($post->ID, 'itsseason', true);

     $lang = get_post_meta($post->ID, 'lang', true);


 ?>

   <p>

<label for="season-number">Season:</label>
  <input type="number" name="season-number" style="width: 50px;" value="<?php if($seanumber) : ?><?php echo $seanumber; ?><?php else : echo 1?><?php endif; ?>">

<label for="episode-number">Episode:</label>
  <input type="number" name="episode-number" style="width: 50px;" value="<?php echo $epinumber;?>">

   </p>

   <p>
       

<label for="sub-lang"><?php echo __('Translating To', 'gimmesubs')?>:</label><br>
  <select name="sub_lang">

  <option value="English" <?php selected( $lang, 'English' ); ?>>English</option>
<?php 
$results_array = array('Español', 'Français', '한국어', 'Português');
foreach($results_array as $key => $value){ ?>
                <option value="<?php echo $value;?>" <?php selected( $lang, $value ); ?>><?php echo $value;     ?></option> 
<?php } ?>
  </select>

   </p>

 <p> What show is this for?</p>
<dl id="country-select" class="dropdown">
<input type="hidden" name="subshow" value="<?php echo $selected;?>" class="title">
<input type="hidden" name="subshowid" value="<?php echo $selectedid;?>" class="id">

  <?php if (!empty($selected)): ?>
  <dt><a><span><?php echo $selected ?></span></a></dt> 
<?php else: ?>
  <dt><a></span><span>Select One</span></a></dt> 

<?php endif; ?>
  <dd>
    <ul style="display: none;">

 <?php
 $query = new WP_Query( array( 'post_type' => 'shows' ) );
$posts = $query->posts;

foreach($posts as $post) {

	$terms = get_the_terms( $post->ID, 'country' );

 

	$color  = get_term_meta( $terms[0]->term_id, 'flag', true );
	?>
	<li><a><span class="flag <?php echo $color;?>"></span><span><?php echo $post->post_title; ?></span><span class="show-id" style="display: none"><?php echo $post->ID; ?></span></a></li>

  
<?}

 ?>

 </ul>


  
<?}





function season_count_callback( $post ) {
 $selected = get_post_meta($post->ID, 'seasons', true);
  $desc = get_post_meta($post->ID, 'show_description', true);

 wp_nonce_field( 'my_seasons_nonce', 'seasons_nonce' );

 ?>

<h4 style="margin-bottom: 4px;">Seasons</h4>
  <select name="seasons">

<?php 
$results_array = array(1, 2, 3, 4, 5,6,7,8,9,'10+');
foreach($results_array as $key => $value){ ?>
                <option value="<?php echo $value;?>" <?php selected( $selected, $value ); ?>><?php echo $value;?></option> 
<?php } ?>
  </select>

  <p style="display: table; width: 100%; margin-bottom: 5px;">
<label for='show_description'><h4 style="margin-bottom: 4px; margin-top: 4px;"> Description</h4></label>
  <textarea name="show_description" rows="3" cols="30"><?php echo $desc;?></textarea> 

  </p>
  
<?}


add_action( 'save_post', 'save_seasons_count' );
add_action( 'save_post', 'save_subtitles' );
add_action( 'save_post', 'save_watch' );


function save_seasons_count( $post_id ) {
 // Bail if we're doing an auto save
 if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
 // if our nonce isn't there, or we can't verify it, bail
 if( !isset( $_POST['seasons_nonce'] ) || !wp_verify_nonce( $_POST['seasons_nonce'], 'my_seasons_nonce' ) ) return;
 // if our current user can't edit this post, bail
 if( !current_user_can( 'edit_post' ) ) return;

// Probably a good idea to make sure your data is set
 if( isset( $_POST['seasons'] ) )
  update_post_meta( $post_id, 'seasons', esc_attr( $_POST['seasons'] ) );

if( isset( $_POST['show_description'] ) )
  update_post_meta( $post_id, 'show_description', esc_attr( $_POST['show_description'] ) );

}



function save_subtitles( $post_id ) {
 // Bail if we're doing an auto save
 if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
 // if our nonce isn't there, or we can't verify it, bail
 if( !isset( $_POST['add_subs_nonce'] ) || !wp_verify_nonce( $_POST['add_subs_nonce'], 'my_add_subs_nonce' ) ) return;
 // if our current user can't edit this post, bail
 if( !current_user_can( 'edit_post' ) ) return;


 $results_array = array(1, 2, 3, 4, 5,6,7,8,9,10);
foreach($results_array as $key => $value){ 

    $episodes = $_POST['subtitles'.convert_number_to_words($value)];
     if( isset( $_POST['subtitles'.convert_number_to_words($value)] ) ) {
    update_post_meta($post_id,'subtitles_'.convert_number_to_words($value),$episodes); }

    else {
      delete_post_meta($post_id,'subtitles_'.convert_number_to_words($value),$episodes);
    }


} 



    


}

function save_watch( $post_id ) {
 // Bail if we're doing an auto save
 if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
 // if our nonce isn't there, or we can't verify it, bail
 if( !isset( $_POST['watch_nonce'] ) || !wp_verify_nonce( $_POST['watch_nonce'], 'my_watch_nonce' ) ) return;
 // if our current user can't edit this post, bail
 if( !current_user_can( 'edit_post' ) ) return;

 if( isset( $_POST['watch_episode'] ) )
  update_post_meta( $post_id, 'watch_episode', esc_attr( $_POST['watch_episode'] ) );

}



/*** Custom Buttons ***/

function appthemes_add_quicktags() {
       $screen = get_current_screen();

    if (wp_script_is('quicktags') &&  'subtitles' == $screen->post_type){
?>
    <script type="text/javascript">
    QTags.addButton( 'sub_tag', 'time', css_callback );

    function css_callback(){
        var css_class = prompt( 'Class name:', '' );
        if ( css_class && css_class !== '' ) {
            QTags.insertContent('<div class="' + css_class +'"></div>');
        }
    }



    </script>
<?php
    }
}
add_action( 'admin_print_footer_scripts', 'appthemes_add_quicktags' );

function wpa_47010( $qtInit ) {
    $qtInit['buttons'] = 'strong,em,dfw';

    return $qtInit;
}
add_filter('quicktags_settings', 'wpa_47010');

/*** TinyMCE

function custom_mce_button() {
   $screen = get_current_screen();

   if( !current_user_can( 'edit_post' ) ) return;
  // Check if WYSIWYG is enabled
  if ( 'true' == get_user_option( 'rich_editing' ) &&  'subtitles' == $screen->post_type ) {
    add_filter( 'mce_external_plugins', 'custom_tinymce_plugin' );
    add_filter( 'mce_buttons', 'register_mce_button' );
  }
}


add_action('admin_head', 'custom_mce_button');

function custom_tinymce_plugin( $plugin_array ) {
  $plugin_array['custom_mce_button'] = get_template_directory_uri() .'/js/editor_plugin.js';
  return $plugin_array;
}

function register_mce_button( $buttons ) {
        $buttons = array( 'bold','italic', 'custom_mce_button','indent','outdent','link','unlink');

  //array_push( $buttons, 'custom_mce_button' );
  return $buttons;
}

 function cw_mce_buttons_2( $buttons ) {
    $buttons = array('fontselect','styleselect','fontsizeselect','charmap','blockquote','hr','pastetext','removeformat','spellchecker','fullscreen','undo','redo','wp_help');
    return false;
}
add_filter( 'mce_buttons_2', 'cw_mce_buttons_2' );
***/

add_filter('user_can_richedit', 'disable_wyswyg_for_custom_post_type');
function disable_wyswyg_for_custom_post_type( $default ){
  global $post;
  if( $post->post_type === 'subtitles') return false;
  return $default;
}

function RemoveAddMediaButtonsForSubtitles(){

       $screen = get_current_screen();


    if ( 'subtitles' == $screen->post_type ) {
        remove_action( 'media_buttons', 'media_buttons' );
    }
}
add_action('admin_head', 'RemoveAddMediaButtonsForSubtitles');



 /*** Shortcodes ***/


 function subtag_func( $atts, $content = "" ) {

    $out = '<span ';
    $out .= 'data-begin="'.$atts['data-begin'].'"';
    $out .= 'data-end="'.$atts['data-end'].'"';
    $out .= '>'. $content . '</span>';
    return $out;
}
add_shortcode( 'sub', 'subtag_func' );


function convert_number_to_words($number) {

    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}