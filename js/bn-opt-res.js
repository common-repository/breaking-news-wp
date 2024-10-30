jQuery(document).ready(function($)
  {
    jQuery('#reset').click(function()
      {
        jQuery('#brnwp_theme_one').attr('checked', true);
        jQuery('#brnwp_dim_barra').val(100);
        jQuery('#brnwp_testo_pers').attr('checked', false);
        jQuery('#brnwp_text').val('');
        jQuery('#brnwp_custom_text').hide();
        jQuery('#brnwp_col_tit').val('#FFFFFF');
        jQuery('#brnwp_col_bar_tit').val('#FF0000');
        jQuery('#brnwp_col_not').val('#FFFFFF');
        jQuery('#brnwp_col_bar').val('#000000');
        jQuery('#brnwp_col_link').val('#FF0000');
        jQuery('#brnwp_fil_cat').val('');
        jQuery('#brnwp_num_not').val(10);
        jQuery('#brnwp_title_content').val('Breaking News');
        jQuery('#brnwp_style').val('');
      });

    });
