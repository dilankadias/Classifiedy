<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo zet_language_dir(); ?>" mode="<?php echo zet_light_dark_mode(); ?>" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php'); ?>
  <meta name="robots" content="index, follow" />
  <meta name="googlebot" content="index, follow" />
</head>

<?php
  $path = __get('file'); 
  $path = explode('/', $path);

  $plugin = @$path[0];
  $file = str_replace('.php', '', end($path));
?>

<body id="user-custom" class="body-ua plugin-<?php echo $plugin; ?> file-<?php echo $file; ?> <?php osc_run_hook('body_class'); ?>">
  <?php osc_current_web_theme_path('header.php'); ?>

  <div class="container primary">
    <div id="user-menu"><?php zet_user_menu(); ?></div>

    <div id="user-main">
      <?php echo zet_banner('user_account_top'); ?>
      
      <div class="user-custom-box">
        <?php osc_render_file(); ?>
      </div>
    </div>
  </div>

  <?php osc_current_web_theme_path('footer.php'); ?>
</body>
</html>