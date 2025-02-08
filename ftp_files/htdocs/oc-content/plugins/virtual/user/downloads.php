<?php
  $downloads_paid = ModelVRT::newInstance()->getDownloadsByUserId(osc_logged_user_id());
  $downloads_free = ModelVRT::newInstance()->getFreeDownloads();

  //$downloads = array_merge($downloads_paid, $downloads_free);
?>

<div class="vrt-body vrt-downloads user-downloads">
  <div id="vrt-tab-menu">
    <a href="#" class="<?php if(Params::getParam('tab') == '' || Params::getParam('tab') == 'premium') { ?>vrt-active<?php } ?>" data-tab="premium"><?php _e('Premium downloads', 'virtual'); ?></a>
    <a href="#" class="<?php if(Params::getParam('tab') == 'free') { ?>vrt-active<?php } ?>" data-tab="free"><?php _e('Free downloads', 'virtual'); ?></a>
  </div>


  <div class="vrt-table vrt-table-orders vrt-downloads vrt-tab <?php if(Params::getParam('tab') == '' || Params::getParam('tab') == 'premium') { ?>vrt-active<?php } ?>" data-tab="premium">
    <div class="vrt-head-row">
      <div class="vrt-col product"><?php _e('Product', 'virtual'); ?></div>
      <div class="vrt-col version"><?php _e('Version', 'virtual'); ?></div>
      <div class="vrt-col date"><?php _e('Updated', 'virtual'); ?></div>
      <div class="vrt-col download">&nbsp;</div>
    </div>

    <?php if(count($downloads_paid) > 0) { ?>
      <div class="vrt-table-wrap">
        <?php foreach($downloads_paid as $d) { ?>
          <?php $item = Item::newInstance()->findByPrimaryKey($d['fk_i_item_id']); ?>

          <div class="vrt-row">
            <?php if($item !== false && isset($item['pk_i_id'])) { ?>
              <div class="vrt-col product"><a href="<?php echo osc_item_url_ns($item['pk_i_id']); ?>"><?php echo $item['s_title']; ?></a></div>
            <?php } else { ?>
              <div class="vrt-col product"><em><?php _e('Listing does not exists anymore', 'virtual'); ?></div>
            <?php } ?>
            
            <?php if(isset($d['pk_i_id']) && $d['pk_i_id'] > 0) { ?>
              <div class="vrt-col version">v<?php echo $d['i_version']; ?></div>
              <div class="vrt-col date"><?php echo vrt_smart_date($d['dt_date']); ?></div>
            <?php } else { ?>
              <div class="vrt-col version"><em><?php _e('- no file available -', 'virtual'); ?></em></div>
              <div class="vrt-col date"><em>-</em></div>
            <?php } ?>

            <div class="vrt-col download">
              <?php if(isset($d['s_file']) && $d['s_file'] <> '' && file_exists(osc_content_path() . 'plugins/virtual/files/' . $d['s_file']) && $item !== false && isset($item['pk_i_id'])) { ?>
                <a href="<?php echo osc_route_url('vrt-download', array('itemId' => $item['pk_i_id'])); ?>"><?php _e('Download', 'virtual'); ?></a>
              <?php } else { ?>
                <a href="#" class="disabled vrt-has-tooltip-right" title="<?php echo osc_esc_html(__('File not available, try again later', 'virtual')); ?>" onclick="return false;"><?php _e('Download', 'virtual'); ?></a>
              <?php } ?>
            </div>
          </div>
        <?php } ?>
      </div>
    <?php } else { ?>
      <div class="vrt-row vrt-row-empty">
        <i class="fa fa-warning"></i><span><?php _e('You have no available premium downloads', 'virtual'); ?></span>
      </div>
    <?php } ?>
  </div>


  <div class="vrt-table vrt-table-orders vrt-downloads vrt-tab <?php if(Params::getParam('tab') == 'free') { ?>vrt-active<?php } ?>" data-tab="free" style="display:none;">
    <div class="vrt-head-row">
      <div class="vrt-col product"><?php _e('Product', 'virtual'); ?></div>
      <div class="vrt-col version"><?php _e('Version', 'virtual'); ?></div>
      <div class="vrt-col date"><?php _e('Updated', 'virtual'); ?></div>
      <div class="vrt-col download">&nbsp;</div>
    </div>

    <?php if(count($downloads_free) > 0) { ?>
      <div class="vrt-table-wrap">
        <?php foreach($downloads_free as $d) { ?>
          <?php $item = Item::newInstance()->findByPrimaryKey($d['fk_i_item_id']); ?>

          <div class="vrt-row">
            <div class="vrt-col product"><a href="<?php echo osc_item_url_ns($item['pk_i_id']); ?>"><?php echo $item['s_title']; ?></a></div>

            <?php if(isset($d['pk_i_id']) && $d['pk_i_id'] > 0) { ?>
              <div class="vrt-col version">v<?php echo $d['i_version']; ?></div>
              <div class="vrt-col date"><?php echo vrt_smart_date($d['dt_date']); ?></div>
            <?php } else { ?>
              <div class="vrt-col version"><em><?php _e('- no file available -', 'virtual'); ?></em></div>
              <div class="vrt-col date"><em>-</em></div>
            <?php } ?>

            <div class="vrt-col download">
              <?php if(isset($d['s_file']) && $d['s_file'] != '' && file_exists(osc_content_path() . 'plugins/virtual/files/' . $d['s_file']) && $d['s_file'] <> '') { ?>
                <a href="<?php echo osc_route_url('vrt-download', array('itemId' => $item['pk_i_id'])); ?>"><?php _e('Download', 'virtual'); ?></a>
              <?php } else { ?>
                <a href="#" class="disabled vrt-has-tooltip-right" title="<?php echo osc_esc_html(__('File not available, try again later', 'virtual')); ?>" onclick="return false;"><?php _e('Download', 'virtual'); ?></a>
              <?php } ?>
            </div>
          </div>
        <?php } ?>
      </div>
    <?php } else { ?>
      <div class="vrt-row vrt-row-empty">
        <i class="fa fa-warning"></i><span><?php _e('You have no available free downloads', 'virtual'); ?></span>
      </div>
    <?php } ?>
  </div>
</div>
