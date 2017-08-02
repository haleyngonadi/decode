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
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
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
        wp_enqueue_script( 'admin_js', get_template_directory_uri() . '/js/admin.js', false, '1.1.0' );


}
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );



/**
 * Meta Boxes
 *
 */
 


function gimme_register_meta_boxes() {
	global $post;
	 $selected = get_post_meta($post->ID, 'seasons', true);


    add_meta_box( 'show-seasons', __( 'Seasons', 'gimmesubs' ), 'season_count_callback', 'shows', 'side', 'high' );
    add_meta_box( 'show-subtitles', __( 'Subtitle Details ', 'gimmesubs' ), 'subtitle_callback', 'subtitles','side', 'high' );


    	for( $i= 1 ; $i <= $selected ; $i++ ){
    		$seasonID = 'show-seasons'.$i;
    add_meta_box( $seasonID, __( 'Season '.$i, 'gimmesubs' ), 'wpdocs_my_display_callback', 'shows', 'normal'  );
           
        }


}
add_action( 'add_meta_boxes', 'gimme_register_meta_boxes' );


function wpdocs_my_display_callback( $post ) {

    $args = array(
        'post_type' => 'subtitles',
        'orderby'   => 'meta_value_num',
        'meta_key'  => 'episode_number',
        'order'   => 'ASC',
        'meta_query' => array(
        array(
            'key'     => 'itsseason',
            'value'   => 1,
            'compare' => '=',
        ),
    ),

        );
    $lastposts = get_posts( $args );
    //var_dump($lastposts);

foreach ( $lastposts as $sub ) {
 ?>

<p style="display: table; width: 100%; margin-bottom: 5px;">
  <input type="text" name="" value="<?php echo get_post_meta($sub->ID, 'episode_number', true)?>" style="width: 5%; float: left;" disabled>
  <input type="text" name="" value="<?php echo $sub->post_name?>" style="width: 94%; float: left">

  </p>

  <?php } ?>
<?}


 
function subtitle_callback( $post ) {
	 wp_nonce_field( 'my_subtitle_nonce', 'subtitle_nonce' );

	$selected = get_post_meta($post->ID, 'subtitle_for', true);
    $selectedid = get_post_meta($post->ID, 'subtitle_for_id', true);

    $epinumber = get_post_meta($post->ID, 'episode_number', true);
    $seanumber = get_post_meta($post->ID, 'itsseason', true);

 ?>

   <p>

<label for="season-number">Season:</label>
  <input type="number" name="season-number" style="width: 50px;" value="<?php if($seanumber) : ?><?php echo $seanumber; ?><?php else : echo 1?><?php endif; ?>">

<label for="episode-number">Episode:</label>
  <input type="number" name="episode-number" style="width: 50px;" value="<?php echo $epinumber;?>">


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
 wp_nonce_field( 'my_seasons_nonce', 'seasons_nonce' );

 ?>

  <select name="seasons">

<?php 
$results_array = array(1, 2, 3, 4, 5,6,7,8,9,'10+');
foreach($results_array as $key => $value){ ?>
                <option value="<?php echo $value;?>" <?php selected( $selected, $value ); ?>><?php echo $value;     ?></option> 
<?php } ?>
  </select>
  
<?}


add_action( 'save_post', 'save_seasons_count' );
add_action( 'save_post', 'save_subtitles' );

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
}



function save_subtitles( $post_id ) {
 // Bail if we're doing an auto save
 if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
 // if our nonce isn't there, or we can't verify it, bail
 if( !isset( $_POST['subtitle_nonce'] ) || !wp_verify_nonce( $_POST['subtitle_nonce'], 'my_subtitle_nonce' ) ) return;
 // if our current user can't edit this post, bail
 if( !current_user_can( 'edit_post' ) ) return;

// Probably a good idea to make sure your data is set
 if( isset( $_POST['subshow'] ) )
  update_post_meta( $post_id, 'subtitle_for', esc_attr( $_POST['subshow'] ) );

 if( isset( $_POST['subshowid'] ) )
  update_post_meta( $post_id, 'subtitle_for_id', esc_attr( $_POST['subshowid'] ) );

if( isset( $_POST['episode-number'] ) )
  update_post_meta( $post_id, 'episode_number', esc_attr( $_POST['episode-number'] ));

    if( isset( $_POST['season-number'] ) )
  update_post_meta( $post_id, 'itsseason', esc_attr( $_POST['season-number'] ) );
}

 