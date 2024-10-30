<?php
// this is an include only WP file
if (!defined('ABSPATH')) {
  die;
}
wp_enqueue_style('brnwp_two', plugins_url( 'css/two.css', dirname(__FILE__) ));
?>
<style>

.br_marquee ul li a{
  color: <?php echo get_option('brnwp_col_not'); ?>;
}

.br_ul {
  color: <?php echo get_option('brnwp_col_not'); ?>;
}

.br_marquee a:visited {
  color: <?php echo get_option('brnwp_col_not'); ?>;
  text-decoration: none;
}

.br_marquee a:hover {
  color: <?php echo get_option('brnwp_col_link'); ?>!important;
  text-decoration: none;
}

.br_span{
  color: <?php echo get_option('brnwp_col_not'); ?>;
}

.br-sitewidth{
  background: <?php echo get_option('brnwp_col_bar'); ?>;
  border-left: 0px solid !important;

}

.br-title{
  border: solid 1px black!important;
  border-right: 0 solid!important;
  color: <?php echo get_option('brnwp_col_tit'); ?>;
  background-color: <?php echo get_option('brnwp_col_bar_tit'); ?>;
}

#br{
  width: <?php echo get_option('brnwp_dim_barra');?>% ;
}

<?php echo get_option('brnwp_style'); ?>

</style>

<script>

jQuery(function () {
    jQuery('.br_marquee').marquee({
        speed: 50,
        duplicated: true,
        pauseOnHover: true
    });
});

</script>

<?php
if(!$text)
{
  $more_posts = $recent_posts;
  if(count($recent_posts) < 10 )
  {
    $diff = intval(10/count($recent_posts));
    for($i = 0; $i < $diff; $i++)
    {
      $more_posts = array_merge($more_posts, $recent_posts);
    }
  }
}

?>

<div id="ll" class="container">
<section id="breaking-news-wp">
  <div id="br" class="container brnopadding">
    <div class="br-title"><?php echo get_option('brnwp_title_content');?></div>
    <div class="br-sitewidth">
      <div class = "br_marquee">
        <ul class = "br_ul">
          <?php if($text) : ?>
            <li><?php echo $bar_text;?></li>
          <?php else : ?>
            <?php foreach ($more_posts as $key => $value) : ?>
              <li class = "br_li"><a href=<?php echo get_post_permalink($value->ID) ?>><?php echo $value->post_title; ?><span class = "br_span"><?php echo get_the_date( 'd/m/Y', $value->ID )?></span></a></li>
            <?php endforeach; ?>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>
</section>
</div>
