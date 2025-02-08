<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title><?php echo meta_title(); ?></title>
<meta name="title" content="<?php echo osc_esc_html(meta_title()); ?>" />
<?php if(meta_description() != '') { ?><meta name="description" content="<?php echo osc_esc_html(meta_description()); ?>" /><?php } ?>
<?php if(osc_get_canonical() != '') { ?><link rel="canonical" href="<?php echo osc_get_canonical(); ?>"/><?php } ?>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Expires" content="Mon, 01 Jul 1970 00:00:00 GMT" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
<?php 
  if(zet_param('generate_favicons') == 1) {
    osc_current_web_theme_path('head-favicon.php');
  }
  
  $current_locale = osc_get_current_user_locale();
  $dimNormal = explode('x', osc_get_preference('dimNormal', 'osclass')); 
  $home_cat_rows = (zet_param('home_cat_rows') > 1 ? (int)zet_param('home_cat_rows') : 1);
  
  if(!defined('JQUERY_VERSION') || JQUERY_VERSION == '1') {
    $jquery_version = '1';
  } else {
    $jquery_version = JQUERY_VERSION;
  }
  
  // Prepare fonts
  $font_name = '-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Droid Sans,Helvetica Neue,sans-serif';
  $font_url = '';
  $font_size = '15px';
  $line_height = '20px';
  
  if(zet_param('enable_custom_font') == 1 && zet_param('font_name') != '' && zet_param('font_url') != '') {
    $font_name = trim(zet_param('font_name'));
    $font_url = trim(zet_param('font_url'));
  }
  
  $font_urls = array_filter(array_map('trim', explode(',', $font_url)));


  // Prepare colors
  $color_primary = '#ff2636';
  $color_primary_hover = '#cc1e2b';
  $color_secondary = '#008f79';
  $color_secondary_hover = '#026958';
  
  if(zet_param('enable_custom_color') == 1 || zet_is_demo()) {
    $color_primary = (zet_get_theme_color_primary() != '' ? zet_get_theme_color_primary() : $color_primary);
    $color_primary_hover = (zet_get_theme_color_primary() != '' ? zet_hex_brightness(zet_get_theme_color_primary(),-20) : $color_primary_hover);
    $color_secondary = (zet_get_theme_color_secondary() != '' ? zet_get_theme_color_secondary() : $color_secondary);
    $color_secondary_hover = (zet_get_theme_color_secondary() != '' ? zet_hex_brightness(zet_get_theme_color_secondary(),-20) : $color_secondary_hover);
  }
?>
<style>
:root {
  --mb-color0:#0c0c0c;--mb-color1:#1c1c1c;--mb-color2:#2c2c2c;--mb-color3:#3c3c3c;--mb-color4:#4c4c4c;--mb-color5:#5c5c5c;
  --mb-color6:#6c6c6c;--mb-color7:#7c7c7c;--mb-color8:#8c8c8c;--mb-color9:#9c9c9c;--mb-color10:#acacac;--mb-color11:#bcbcbc;
  --mb-color12:#dcdcdc;--mb-color13:#ececec;--mb-color14:#fcfcfc;--mb-color15:#fefefe;
  --mb-color-primary:<?php echo $color_primary; ?>;--mb-color-primary-hover:<?php echo $color_primary_hover; ?>;
  --mb-color-primary-light:<?php echo zet_hex_to_rgb($color_primary, 0.2); ?>;--mb-color-primary-lighter:<?php echo zet_hex_to_rgb($color_primary, 0.12); ?>;
  --mb-color-secondary:<?php echo $color_secondary; ?>;--mb-color-secondary-hover:<?php echo $color_secondary_hover; ?>;
  --mb-color-secondary-light:<?php echo zet_hex_to_rgb($color_secondary, 0.2); ?>;--mb-color-secondary-lighter:<?php echo zet_hex_to_rgb($color_secondary, 0.12); ?>;
  --mb-font-family:<?php echo $font_name; ?>;--mb-font-size:<?php echo $font_size; ?>;--mb-line-height:<?php echo $line_height; ?>;
  --mb-home-cat-rows:<?php echo $home_cat_rows; ?>;--set-where: head.php;}
</style>
<?php 
  if(is_array($font_urls) && count($font_urls) > 0) { 
    foreach($font_urls as $url) { 
    ?><link rel="preload" href="<?php echo $url; ?>" as="style" onload="this.onload=null;this.rel='stylesheet'"><?php echo PHP_EOL; ?><?php
    }
  }
?>
<script type="text/javascript">
  var currentLocaleCode = '<?php echo osc_esc_js($current_locale['pk_c_code']); ?>';
  var currentLocale = '<?php echo osc_esc_js($current_locale['s_name']); ?>';
  var fileDefaultText = '<?php echo osc_esc_js(__('No file selected', 'zeta')); ?>';
  var fileBtnText = '<?php echo osc_esc_js(__('Choose File', 'zeta')); ?>';
  var baseDir = '<?php echo osc_base_url(); ?>';
  var baseSearchUrl = '<?php echo osc_search_url(array('page' => 'search')); ?>';
  var baseAjaxUrl = '<?php echo zet_ajax_url(); ?>';
  var currentLocation = '<?php echo osc_get_osclass_location(); ?>';
  var currentSection = '<?php echo osc_get_osclass_section(); ?>';
  var userLogged = '<?php echo osc_is_web_user_logged_in() ? 1 : 0; ?>';
  var adminLogged = '<?php echo osc_is_admin_user_logged_in() ? 1 : 0; ?>';
  var zetLazy = '<?php echo (zet_is_lazy() ? 1 : 0); ?>';
  var darkMode = '<?php echo (zet_is_dark_mode() ? 1 : 0); ?>';
  var imgPreviewRatio = <?php echo round($dimNormal[0]/$dimNormal[1], 3); ?>;
  var searchRewrite = '/<?php echo osc_get_preference('rewrite_search_url', 'osclass'); ?>';
  var ajaxSearch = '<?php echo (zet_param('search_ajax') == 1 ? '1' : '0'); ?>';
  var ajaxForms = '<?php echo (zet_param('forms_ajax') == 1 ? '1' : '0'); ?>';
  var locationPick = '<?php echo (zet_param('location_pick') == 1 ? '0' : '0'); ?>';
  var delTitleNc = '<?php echo osc_esc_js(__('Parent category cannot be selected', 'zeta')); ?>';
  var jqueryVersion = '<?php echo $jquery_version; ?>';
  var isRtl = <?php echo (zet_is_rtl() ? 'true' : 'false'); ?>;
</script>
<?php

osc_enqueue_style('style', osc_current_web_theme_url('css/style.css' . zet_asset_version()));
osc_enqueue_style('font-awesome5', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

if($jquery_version == '1') {
  osc_enqueue_style('jquery-ui', osc_current_web_theme_url('css/jquery-ui.min.css'));
} else {
  osc_enqueue_style('jquery-ui', osc_assets_url('js/jquery3/jquery-ui/jquery-ui.min.css'));
}

if(osc_is_ad_page() || (osc_get_osclass_location() == 'item' && osc_get_osclass_section() == 'send_friend')) {
  osc_enqueue_style('swiper', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.1.0/swiper-bundle.min.css');
  osc_enqueue_style('lightgallery', 'https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.10.0/css/lightgallery.min.css');
}

if(zet_ajax_image_upload() && (osc_is_publish_page() || osc_is_edit_page())) {
  osc_enqueue_style('fine-uploader-css', osc_assets_url('js/fineuploader/fineuploader.css'));
}

osc_register_script('global', osc_current_web_theme_js_url('global.js' . zet_asset_version()), array('jquery'));

if($jquery_version == '1') {
  osc_register_script('validate', osc_current_web_theme_js_url('jquery.validate.min.js'), array('jquery'));
} else {
  osc_register_script('validate', osc_assets_url('js/jquery.validate.min.js'), array('jquery'));
}

osc_register_script('lazyload', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js');
osc_register_script('swiper', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.1.0/swiper-bundle.min.js');
osc_register_script('lightgallery', 'https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.10.0/js/lightgallery-all.min.js');
osc_register_script('date', osc_base_url() . 'oc-includes/osclass/assets/js/date.js');

osc_enqueue_script('jquery');

if(zet_param('lazy_load') == 1) {
  osc_enqueue_script('lazyload');
}

osc_remove_script('jquery-validate');

if(!osc_is_search_page() && !osc_is_home_page()) {
  osc_enqueue_script('validate');
}

if(osc_is_ad_page() || (osc_get_osclass_location() == 'item' && osc_get_osclass_section() == 'send_friend')) {
  osc_enqueue_script('swiper');
  osc_enqueue_script('lightgallery');
}

if(!osc_is_search_page() && !osc_is_home_page() && !osc_is_ad_page()) {
  osc_enqueue_script('tabber');
}

if(zet_ajax_image_upload() && (osc_is_publish_page() || osc_is_edit_page())) {
  osc_enqueue_script('jquery-fineuploader');
}

if(osc_is_publish_page() || osc_is_edit_page() || osc_is_search_page()) {
  osc_enqueue_script('date');
}

osc_enqueue_script('jquery-ui');
osc_enqueue_script('global');

osc_run_hook('header'); 
?>