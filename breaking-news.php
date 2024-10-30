<?php
/*
Plugin Name: Breaking News WP
Plugin URI: https://wp-love.it/
Description: Show in every place your Free and Custom Breaking News Bar
Author: WP Love
Version: 1.3
Author URI: https://www.wp-love.it
Text Domain: breaking-news-wp
Domain Path: /languages
*/



// this is an include only WP file
if (!defined('ABSPATH')) {
  die;
}

function brnwp_load_textdomain()
{
	$plugin_rel_path = basename( dirname( __FILE__ ) ) . '/languages';
	load_plugin_textdomain( 'brnwp', false, $plugin_rel_path );
}
add_action('plugins_loaded', 'brnwp_load_textdomain');

function brnwp_load_custom_script() {
  wp_enqueue_script('brnwp_marquee_scroll', plugins_url( 'breaking-news-wp/js/marquee-scroll.js', dirname(__FILE__) ), array('jquery'));
  wp_enqueue_script('brnwp_marquee_scroll_min', plugins_url( 'breaking-news-wp/js/marquee-scroll-min.js', dirname(__FILE__) ), array('jquery'));
  wp_enqueue_script('brnwp_marquee_min', plugins_url( 'breaking-news-wp/js/jquery.marquee.min.js', dirname(__FILE__) ), array('jquery'));

}
add_action( 'wp_enqueue_scripts', 'brnwp_load_custom_script' );

function brnwp_admin_script()
{
  wp_enqueue_script('brnwp_bn_opt_reset', plugins_url( 'breaking-news-wp/js/bn-opt-res.js', dirname(__FILE__) ), array('jquery'));
}
add_action( 'admin_enqueue_scripts', 'brnwp_admin_script' );

function brnwp_show_breaking_news_wp($content = null)
{
  if(get_option('brnwp_testo_pers') == 'on')
  {
    $text = true;
    $tmp_text = get_option('brnwp_text');
    $txt_rep = intval(150/strlen(strip_tags($tmp_text)));
    $bar_text = $tmp_text;
    for($i = 0; $i <= $txt_rep; $i++)
    {
      $bar_text .= str_repeat('&nbsp;', 16) . $tmp_text;
    }
  }
  else
  {
    $cats = get_option( 'brnwp_fil_cat' );
  	$c    = "";
  	if ( $cats != '' )
  	{
  		foreach ( $cats as $cat )
  		{
  			$c .= $cat . ",";
  		}
  	}
  	$args = array(
  		'numberposts'      => get_option( 'brnwp_num_not' ),
  		'offset'           => 0,
  		'category'         => $c,
  		'orderby'          => 'post_date',
  		'order'            => 'DESC',
  		'include'          => '',
  		'exclude'          => '',
  		'meta_key'         => '',
  		'meta_value'       => '',
  		'post_type'        => 'post',
  		'post_status'      => 'publish',
  		'suppress_filters' => true
  	);

  	$recent_posts = wp_get_recent_posts( $args, OBJECT );
  }


  ob_start();
	include( 'templates/' . get_option( 'brnwp_theme' ) );
  $brnwp_bar = ob_get_clean();

  return $brnwp_bar;

}
add_shortcode('breaking-news-wp', 'brnwp_show_breaking_news_wp');

function brnwp_add_option_page()
{
	add_menu_page( 'Breaking News WP', 'Breaking News WP', 'manage_options', 'breaking-news-wp-main-menu', 'brnwp_config');
}
add_action('admin_menu', 'brnwp_add_option_page');


function brnwp_config()
{
	if ( ! current_user_can( 'edit_posts' ) )
	{
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<h1> Breaking News WP </h1></br>';
	_e( 'Here you customize your Breaking News WP bar settings:', 'brnwp' );
	echo '<br>';

	brnwp_update_options();
}


function brnwp_activate_set_default_options()
{
  add_option('brnwp_theme','one.php');
  add_option('brnwp_dim_barra', '100');
  add_option('brnwp_testo_pers', '');
  add_option('brnwp_text', '');
  add_option('brnwp_col_tit', '#FFFFFF');
  add_option('brnwp_col_bar_tit', '#FFFF00');
  add_option('brnwp_col_not', '#FFFFFF');
  add_option('brnwp_col_bar', '#000000');
  add_option('brnwp_col_link', '#FF0000');
  add_option('brnwp_fil_cat', '');
  add_option('brnwp_num_not', 10);
  add_option('brnwp_title_content', 'Breaking News');
  add_option('brnwp_style', '');
}

register_activation_hook(__FILE__, 'brnwp_activate_set_default_options');


function brnwp_register_options_group()
{
  register_setting('brnwp_options_group', 'brnwp_theme');
  register_setting('brnwp_options_group', 'brnwp_dim_barra');
  register_setting('brnwp_options_group', 'brnwp_testo_pers');
  register_setting('brnwp_options_group', 'brnwp_text');
  register_setting('brnwp_options_group', 'brnwp_col_tit');
  register_setting('brnwp_options_group', 'brnwp_col_bar_tit');
  register_setting('brnwp_options_group', 'brnwp_col_not');
  register_setting('brnwp_options_group', 'brnwp_col_bar');
  register_setting('brnwp_options_group', 'brnwp_col_link');
  register_setting('brnwp_options_group', 'brnwp_fil_cat');
  register_setting('brnwp_options_group', 'brnwp_num_not');
  register_setting('brnwp_options_group', 'brnwp_title_content');
  register_setting('brnwp_options_group', 'brnwp_style');
}

add_action('admin_init', 'brnwp_register_options_group');


function brnwp_update_options()
{
  ?>

  <script>
  function brnwp_ajax_sd()
  {
    jQuery.ajax(
    {
      url: "<?php echo admin_url('admin-ajax.php'); ?>",
      type: "POST",
      data: ({
        action: 'brnwp_ajax_form',
        'brnwp_theme': 'one.php',
        'brnwp_dim_barra': 100,
        'brnwp_testo_pers': '',
        'brnwp_text': '',
        'brnwp_col_tit': '#FFFFFF',
        'brnwp_col_bar_tit': '#FF0000',
        'brnwp_col_not': '#FFFFFF',
        'brnwp_col_bar': '#000000',
        'brnwp_col_link': '#FF0000',
        'brnwp_fil_cat': '',
        'brnwp_num_not': 10,
        'brnwp_title_content': 'Breaking News',
        'brnwp_style': ''
      }),
      success: function(){alert("<?php _e('Settings restored successfully', 'brnwp')?>");},
      error: function(jqXHR, textStatus, errorThrown){alert(errorThrown);}

    });
  }

  function brnwp_custom_text_check() {
    if(jQuery('#brnwp_testo_pers').attr('checked') === 'checked') {
      jQuery('#brnwp_custom_text').show();
    } else {
      jQuery('#brnwp_text').val('');
      jQuery('#brnwp_custom_text').hide();
    }
  };

  jQuery(document).ready(function () {
    jQuery('#brnwp_testo_pers').ready(function () {
      brnwp_custom_text_check();
    });
    jQuery('#brnwp_testo_pers').click(function () {
      brnwp_custom_text_check();
    });
  });
  </script>

  <div class="wrap">
    <h2><?php _e('Breaking News WP settings', 'brnwp') ?></h2>
    <form method="post" action="options.php">
    <?php settings_fields('brnwp_options_group'); ?>
    <table class="form-table">
      <tbody>
        <tr valign="top">
          <th scope="row">
            <label for="brnwp_theme"><?php _e('Choose Theme', 'brnwp') ?></label>
          </th>
          <td>
            <fieldset>
              <?php _e('Simple', 'brnwp') ?><input type="radio" id="brnwp_theme_one" name="brnwp_theme" value="one.php" <?php echo (get_option('brnwp_theme') == 'one.php' ? 'checked' : '') ?>>
	            <?php _e('Detailed', 'brnwp') ?><input type="radio" id="brnwp_theme_two" name="brnwp_theme" value="two.php" <?php echo (get_option('brnwp_theme') == 'two.php' ? 'checked' : '') ?>>
            </fieldset>
            <span class="description"></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">
            <label for="brnwp_dim_barra"><?php _e('Bar Width', 'brnwp') ?></label>
          </th>
          <td>
            <input type="number" id="brnwp_dim_barra" name="brnwp_dim_barra" min=20 max=100 value=<?php echo get_option('brnwp_dim_barra'); ?>>%
            <span class="description"></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">
            <label for="brnwp_testo_pers"><?php _e('Custom Text', 'brnwp') ?></label>
          </th>
          <td>
            <?php _e('Do you want to use a custom text?', 'brnwp') ?>&nbsp;&nbsp;&nbsp;<input type="checkbox" id="brnwp_testo_pers" name="brnwp_testo_pers" <?php echo (get_option('brnwp_testo_pers') == 'on' ? 'checked' : '') ?>>
          </td>
        </tr>
        <tr id="brnwp_custom_text" valign="top" style="display:none;">
          <th>
          </th>
          <td>
            <?php wp_editor(get_option('brnwp_text'), 'brnwp_text', array('media_buttons' => false, 'teeny' => true)); ?>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">
            <label for="brnwp_col_tit"><?php _e('Title Color', 'brnwp') ?></label>
          </th>
          <td>
            <input type="color" id="brnwp_col_tit" name="brnwp_col_tit" value="<?php echo get_option('brnwp_col_tit'); ?>">
            <span class="description"></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">
            <label for="brnwp_col_bar_tit"><?php _e('Bar Title Color', 'brnwp') ?></label>
          </th>
          <td>
            <input type="color" id="brnwp_col_bar_tit" name="brnwp_col_bar_tit" value="<?php echo get_option('brnwp_col_bar_tit'); ?>">
            <span class="description"></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">
            <label for="brnwp_col_not"><?php _e('News Color', 'brnwp') ?></label>
          </th>
          <td>
            <input type="color" id="brnwp_col_not" name="brnwp_col_not" value="<?php echo get_option('brnwp_col_not'); ?>">
            <span class="description"></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">
            <label for="brnwp_col_bar"><?php _e('News Bar Color', 'brnwp') ?></label>
          </th>
          <td>
            <input type="color" id="brnwp_col_bar" name="brnwp_col_bar" value="<?php echo get_option('brnwp_col_bar'); ?>">
            <span class="description"></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">
            <label for="brnwp_col_link"><?php _e('News Link Color', 'brnwp') ?></label>
          </th>
          <td>
            <input type="color" id="brnwp_col_link" name="brnwp_col_link" value="<?php echo get_option('brnwp_col_link'); ?>">
            <span class="description"></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">
            <label for="brnwp_fil_cat"><?php _e('News Filter', 'brnwp') ?></label>
          </th>
          <td>
            <?php $cats = get_categories( array( 'orderby' => 'name', 'order' => 'ASC' ) ); ?>
            <select id="brnwp_fil_cat" name="brnwp_fil_cat[]" size="3" multiple >
              <?php
                foreach ($cats as $cat)
                  {
                    echo '<option value="' . $cat->term_id . '">' . $cat->name . '</option>';
                  }
              ?>
            </select>
            <span class="description"></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">
            <label for="brnwp_num_not"><?php _e('News Max Number', 'brnwp') ?></label>
          </th>
          <td>
            <input type="number" id="brnwp_num_not" name="brnwp_num_not" min=1 max=50 value=<?php echo get_option('brnwp_num_not'); ?>>
            <span class="description"></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">
            <label for="brnwp_title_content"><?php _e('News Bar Title', 'brnwp') ?></label>
          </th>
          <td>
            <input type="text" id="brnwp_title_content" name="brnwp_title_content" value="<?php echo get_option('brnwp_title_content'); ?>">
            <span class="description"></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">
            <label for="brnwp_style"><?php _e('Custom Style', 'brnwp') ?></label>
          </th>
          <td>
            <textarea rows="10" cols="100" id="brnwp_style" name="brnwp_style" ><?php echo get_option('brnwp_style'); ?></textarea>
            <span class="description"></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"></th>
            <td>
              <p>
                <input type="submit" class="button-primary" id="submit" name="submit" value="<?php _e('Save Changes' ,'brnwp') ?>" />
              </p>
            </td>
        </tr>
        <tr valign="top">
          <th scope="row"></th>
            <td>
              <p>
                <input type="button" class="button-primary" id="reset" name="reset" value="<?php _e('Reset') ?>" onclick="brnwp_ajax_sd()" />
              </p>
            </td>
        </tr>
      </tbody>
    </table>
    </form>

    <div style="margin-top: 4%; font-style: italic; color: #555d66; font-size: 15px">
      <?php _e('For support or specific requests contact:', 'brnwp'); ?> <a href="mailto:assistenza@wp-love.it">assistenza@wp-love.it</a>
    </div>
  </div>
  <?php
}

add_action( 'wp_ajax_brnwp_ajax_form', 'brnwp_ajax_form' );

function brnwp_ajax_form() {
  global $wpdb;

  $options = $wpdb->prefix . 'options';

  $wpdb->update( $options ,
          array( 'option_value' => sanitize_text_field($_POST['brnwp_theme']) ),
          array( 'option_name'  => 'brnwp_theme'         ));

  $wpdb->update( $options ,
          array( 'option_value' => sanitize_text_field($_POST['brnwp_dim_barra']) ),
          array( 'option_name'  => 'brnwp_dim_barra'         ));

  $wpdb->update( $option ,
          array( 'option_value' => sanitize_text_field($_POST['brnwp_testo_pers']) ),
          array( 'option_name'  => 'brnwp_testo_pers'        ));

  $wpdb->update( $option ,
          array( 'option_value' => sanitize_text_field($_POST['brnwp_text']) ),
          array( 'option_name'  => 'brnwp_text'              ));

  $wpdb->update( $options ,
          array( 'option_value' => sanitize_text_field($_POST['brnwp_col_tit']) ),
          array( 'option_name'  => 'brnwp_col_tit'         ));

  $wpdb->update( $options ,
          array( 'option_value' => sanitize_text_field($_POST['brnwp_col_bar_tit']) ),
          array( 'option_name'  => 'brnwp_col_bar_tit'         ));

  $wpdb->update( $options ,
          array( 'option_value' => sanitize_text_field($_POST['brnwp_col_not']) ),
          array( 'option_name'  => 'brnwp_col_not'         ));

  $wpdb->update( $options ,
          array( 'option_value' => sanitize_text_field($_POST['brnwp_col_bar']) ),
          array( 'option_name'  => 'brnwp_col_bar'         ));

  $wpdb->update( $options ,
          array( 'option_value' => sanitize_text_field($_POST['brnwp_col_link']) ),
          array( 'option_name'  => 'brnwp_col_link'         ));

  $wpdb->update( $options ,
          array( 'option_value' => sanitize_text_field($_POST['brnwp_fil_cat']) ),
          array( 'option_name'  => 'brnwp_fil_cat'         ));

  $wpdb->update( $options ,
          array( 'option_value' => sanitize_text_field($_POST['brnwp_num_not']) ),
          array( 'option_name'  => 'brnwp_num_not'         ));

  $wpdb->update( $options ,
          array( 'option_value' => sanitize_text_field($_POST['brnwp_title_content']) ),
          array( 'option_name'  => 'brnwp_title_content'         ));

  $wpdb->update( $options ,
          array( 'option_value' => sanitize_textarea_field($_POST['brnwp_style']) ),
          array( 'option_name'  => 'brnwp_style'         ));

  wp_die();
}

?>
