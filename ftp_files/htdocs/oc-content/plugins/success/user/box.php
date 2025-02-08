<?php
  if(osc_is_web_user_logged_in()) {
    $where = '= ' . osc_logged_user_id();
  } else {
    $where = 'is null';
  }

  if(Params::getParam('itemId') > 0) {
    $item = Item::newInstance()->findByPrimaryKey(Params::getParam('itemId'));
  } else {
    $sql = sprintf('SELECT * FROM %st_item WHERE fk_i_user_id %s ORDER BY pk_i_id DESC LIMIT 1', DB_TABLE_PREFIX, $where);
    $result = Item::newInstance()->dao->query($sql);
  
    if(!$result) { 
      $item = array();
    } else {
      $item = $result->row();
      //$item = Item::newInstance()->extendDataSingle($item);  // add locales
    }
  }


  //View::newInstance()->_exportVariableToView('item', $item);

  if(@$item['b_active'] == 1) {
    $message = __('Your listing is active and visible to users', 'success');
  } else {
    $message = __('Your listing is not active yet and must be validated first. Check your inbox for validation email.', 'success');
  }
?>

<?php if(isset($item['pk_i_id']) && $item['pk_i_id'] > 0) { ?>
  <div id="scf-box">
    <div id="scf-close"><i class="fa fa-times"></i></div>

    <div class="inside">
      <div class="icon"><img src="<?php echo osc_base_url(); ?>oc-content/plugins/success/img/check.png"/></div>
      <div class="top"><?php _e('Your listing is published!', 'success'); ?></div>
      <div class="middle"><?php _e('Thanks for your submission.', 'success'); ?> <?php echo $message; ?></div>
      <div class="share">
        <?php if(function_exists('sr_buttons')) { ?>
          <?php echo sr_buttons($item['pk_i_id']); ?>
        <?php } else { ?>
          <?php $share_url = urlencode(osc_item_url_ns($item['pk_i_id'])); ?>

          <span class="whatsapp"><a target="_blank" href="whatsapp://send?text=<?php echo $share_url; ?>" data-action="share/whatsapp/share"><i class="fa fa-whatsapp"></i> <?php _e('WhatsApp', 'success'); ?></a></span>
          <span class="facebook"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" title="<?php echo osc_esc_html(__('Share us on Facebook', 'success')); ?>" target="_blank"><i class="fa fa-facebook"></i> <?php _e('Facebook', 'success'); ?></a></span>
          <span class="pinterest"><a target="_blank" href="https://pinterest.com/pin/create/button/?url=<?php echo $share_url; ?>&media=<?php echo osc_base_url(); ?>oc-content/themes/<?php echo osc_current_web_theme(); ?>/images/logo.jpg&description=" title="<?php echo osc_esc_html(__('Share us on Pinterest', 'success')); ?>" target="_blank"><i class="fa fa-pinterest"></i> <?php _e('Pinterest', 'success'); ?></a></span>
          <span class="twitter"><a target="_blank" href="https://twitter.com/home?status=<?php echo $share_url; ?>%20-%20<?php _e('your', 'success'); ?>%20<?php _e('classifieds', 'success'); ?>" title="<?php echo osc_esc_html(__('Tweet us', 'success')); ?>" target="_blank"><i class="fa fa-twitter"></i> <?php _e('Twitter', 'success'); ?></a></span>
          <span class="linkedin"><a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $share_url; ?>&title=<?php echo osc_esc_html(__('My', 'success')); ?>%20<?php echo osc_esc_html(__('classifieds', 'success')); ?>&summary=&source=" title="<?php echo osc_esc_html(__('Share us on LinkedIn', 'success')); ?>" target="_blank"><i class="fa fa-linkedin"></i> <?php _e('LinkedIn', 'success'); ?></a></span>
        <?php } ?>
      </div>
      <div class="actions">
        <a href="<?php echo osc_item_url_ns($item['pk_i_id']); ?>"><?php _e('View listing', 'success'); ?></a>

        <?php if(function_exists('osp_item_promote_manage')) { ?>
          <a href="<?php echo osc_route_url('osp-item-pay-publish', array('itemId' => $item['pk_i_id'], 'isPublish' => 0)); ?>"><?php _e('Promote listing', 'success'); ?></a>
        <?php } ?>

        <a href="<?php echo osc_item_post_url(); ?>"><?php _e('Add new', 'success'); ?></a>
      </div>
    </div>
  </div>

  <div id="scf-cover">&nbsp;</div>
<?php } ?>