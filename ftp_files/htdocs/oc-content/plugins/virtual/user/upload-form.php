<?php
  $item_id = Params::getParam('itemId');
  $item = Item::newInstance()->findByPrimaryKey($item_id);
  $user_id = osc_logged_user_id();
  $file = ModelVRT::newInstance()->getLastFileByItemId($item_id, -1);

  if(Params::getParam('itemId') == '' && osc_get_osclass_location() == 'ajax' && osc_get_osclass_section() == 'runhook') {
    $file_required = vrt_param('file_required');
  } else if(Params::getParam('itemId') > 0 && osc_get_osclass_location() == 'ajax' && osc_get_osclass_section() == 'runhook') {
    $file_required = 0;
  } else {
    $file_required = 1;
  }


  if(isset($file['i_version']) && $file['i_version'] <> '') {
    $new_version = $file['i_version'] + 1;
  } else {
    $new_version = 1;
  }
?>

<div id="vrt-upload-form">
  <input type="hidden" name="vrtUpload" id="vrtUpload" value="done">

  <div class="inside user-upload file-upload">
    <h2>
      <?php if(isset($item['s_title']) && $item['s_title'] <> '') { ?>
        <span class="text"><?php _e('File upload for item', 'virtual'); ?> <a href="<?php echo osc_item_url_ns($item_id); ?>"><?php echo osc_highlight($item['s_title'], 30); ?></a></span>
      <?php } else { ?>
        <span class="text"><?php _e('File upload', 'virtual'); ?></span>
      <?php } ?>
      
      <?php if(vrt_param('file_required') == 1) { ?>
        <span class="rqrd">(<?php _e('required', 'virtual'); ?>)</div>
      <?php } else { ?>
        <span class="rqrd">(<?php _e('optional', 'virtual'); ?>)</div>
      <?php } ?>
    </h2>

    <div class="row">
      <div class="file-info">
        <div class="line"><?php echo sprintf(__('Please upload file associated with this product. File must have extension %s', 'virtual'), implode(', ', explode(',', vrt_param('allowed_extensions')))); ?>.</div>
        <?php if(vrt_param('require_validation') == 1) { ?>
          <div class="line"><?php _e('Each file will be validated by our team', 'virtual'); ?>.</div>
        <?php } ?>
      </div>
    </div>

    <div class="row">
      <label for="productVersion"><span><?php _e('Product version', 'virtual'); ?></span><?php if($file_required == 1) { ?><span class="req">*</span><?php } ?></label>
      <input id="productVersion" placeholder="1" type="number" name="productVersion" value="<?php echo $new_version; ?>" <?php if($file_required) { ?>required<?php } ?>>

      <?php if(isset($file['i_version']) && $file['i_version'] <> '') { ?>
        <span class="info"><?php echo sprintf(__('Last product version: %s', 'virtual'), $file['i_version']); ?></span>
      <?php } ?>
    </div>


    <div class="row">
      <label for="versionSummary"><span><?php _e('Version summary', 'virtual'); ?></span></label>
      <input id="versionSummary" type="text" placeholder="<?php echo osc_esc_html(__('Updated version...', 'virtual')); ?>" name="versionSummary" value="">
    </div>

    <div class="row">
      <label for="productFile"><span><?php _e('Click to upload', 'virtual'); ?></span><?php if($file_required == 1) { ?><span class="req">*</span><?php } ?></label>

      <div class="attachment">
        <div class="att-box">
          <label class="productFile">
            <span class="wrap"><i class="fa fa-file-archive-o"></i> <span data-original="<?php echo osc_esc_html(__('Upload file', 'virtual')); ?>"><?php _e('Upload file', 'virtual'); ?></span></span>
            <input type="file" name="attachment" <?php if($file_required) { ?>required<?php } ?>>
          </label>
        </div>

        <div class="text"><?php echo sprintf(__('Allowed extensions: %s', 'virtual'), vrt_param('allowed_extensions')); ?></div>
        <div class="text"><?php echo sprintf(__('Maximum size: %dMb', 'virtual'), vrt_param('max_file_size')); ?></div>
      </div>
    </div>
  </div>
</div>