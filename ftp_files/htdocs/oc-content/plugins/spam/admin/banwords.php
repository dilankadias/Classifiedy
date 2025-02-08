<?php
  // Create menu
  $title = __('Ban words', 'spam');
  ans_menu($title);


  // GET & UPDATE PARAMETERS
  // $variable = mb_param_update( 'param_name', 'form_name', 'input_type', 'plugin_var_name' );
  // input_type: check or value

  $enable_banwords = mb_param_update('enable_banwords', 'plugin_action', 'check', 'plugin-spam');
  $list_banwords = mb_param_update('list_banwords', 'plugin_action', 'value', 'plugin-spam');


  if(Params::getParam('plugin_action') == 'done') {
    message_ok(__('Settings were successfully saved', 'spam'));
  }
  
  
  // Checker
  if(Params::getParam('what')) {
    if(Params::getParam('what') == 'item_delete' and Params::getParam('item_id') <> '' && !ans_is_demo()) {
      Item::newInstance()->deleteByPrimaryKey(Params::getParam('item_id'));
      message_ok(__('Listing was successfully deleted','spam'));
    }

    if(Params::getParam('what') == 'mark_spam' and Params::getParam('banword') <> '') {
      foreach(ModelANS::newInstance()->getItemsByBanword(Params::getParam('banword')) as $item_check) {  
        ModelANS::newInstance()->updateItemSpamByID( $item_check['pk_i_id'] );
      }
      message_ok(__('Listings containing banword','spam') . ' <strong>' . Params::getParam('banword') . '</strong> ' . __('were successfully marked as Spam','spam'));
    }

    if(Params::getParam('what') == 'mark_deactivate' and Params::getParam('banword') <> '') {
      foreach(ModelANS::newInstance()->getItemsByBanword(Params::getParam('banword')) as $item_check) {  
        ModelANS::newInstance()->updateItemActivateByID( $item_check['pk_i_id'] );
      }
      message_ok(__('Listings containing banword','spam') . ' <strong>' . Params::getParam('banword') . '</strong> ' . __('were successfully Deactivated','spam'));
    }

    if(Params::getParam('what') == 'mark_block' and Params::getParam('banword') <> '') {
      foreach(ModelANS::newInstance()->getItemsByBanword(Params::getParam('banword')) as $item_check) {  
        ModelANS::newInstance()->updateItemBlockByID( $item_check['pk_i_id'] );
      }
      message_ok(__('Listings containing banword','spam') . ' <strong>' . Params::getParam('banword') . '</strong> ' . __('were successfully Blocked','spam'));
    }

    if(Params::getParam('what') == 'delete_all' and Params::getParam('banword') <> '' && !ans_is_demo()) {
      foreach(ModelANS::newInstance()->getItemsByBanword(Params::getParam('banword')) as $item_check) {  
        Item::newInstance()->deleteByPrimaryKey($item_check['pk_i_id']);
      }
      message_ok(__('Listings containing banword','spam') . ' <strong>' . Params::getParam('banword') . '</strong> ' . __('were successfully Deleted','spam'));
    }
  }



  // SCROLL TO DIV
  if(Params::getParam('plugin_action') == 'refresh' || Params::getParam('what') <> '') {
    ans_js_scroll('.mb-ban-words-check');
  }
?>


<div class="mb-body">
 
  <!-- CONFIGURE SECTION -->
  <div class="mb-box">
    <div class="mb-head"><i class="fa fa-wrench"></i> <?php _e('Ban words settings', 'spam'); ?></div>

    <div class="mb-inside mb-minify">
      <form name="promo_form" action="<?php echo osc_admin_base_url(true); ?>" method="POST" enctype="multipart/form-data" >
        <?php if(!ans_is_demo()) { ?>
        <input type="hidden" name="page" value="plugins" />
        <input type="hidden" name="action" value="renderplugin" />
        <input type="hidden" name="file" value="<?php echo osc_plugin_folder(__FILE__); ?>banwords.php" />
        <input type="hidden" name="plugin_action" value="done" />
        <?php } ?>

        <div class="mb-row">
          <label for="enable_banwords" class="h1"><span><?php _e('Enable Ban Words', 'spam'); ?></span></label> 
          <input name="enable_banwords" type="checkbox" class="element-slide" <?php echo ($enable_banwords == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('When enabled and visitor use one of banned words, vistor get banned.', 'spam'); ?></div>
        </div>
        
        
        <div class="mb-row">
          <label for="list_banwords" class="h2"><span><?php _e('Badwords List', 'spam'); ?></span></label> 
          <textarea name="list_banwords"><?php echo osc_esc_html($list_banwords); ?></textarea>

          <div class="mb-explain"><?php _e('Use comma as delimiter, no white spaces needed. Ban words are case insensitive and are not checking whole words. Example: word1,Word2,wORD3,WORD4 . Usage of any of these words will result in ban, as well as using them in other word. Having ban word abc1 and using word xyzabc123 will result in ban as well.', 'spam'); ?></div>
        </div>     
        

        <div class="mb-row">&nbsp;</div>

        <div class="mb-foot">
          <?php if(ans_is_demo()) { ?>
            <a class="mb-button mb-has-tooltip disabled" onclick="return false;" style="cursor:not-allowed;opacity:0.5;" title="<?php echo osc_esc_html(__('This is demo site', 'spam')); ?>"><?php _e('Save', 'spam');?></a>
          <?php } else { ?>
            <button type="submit" class="mb-button"><?php _e('Save', 'spam');?></button>
          <?php } ?>
        </div>
      </form>
    </div>
  </div>



  <!-- ITEMS LIST -->
  <div class="mb-box mb-ban-words-check">
    <div class="mb-head"><i class="fa fa-list"></i> <?php _e('Ban words checker', 'spam'); ?></div>

    <div class="mb-inside">
      <div class="mb-row mb-notes">
        <div class="mb-line"><?php _e('Before checking for ban words, make sure to save updated list in above section, if you made change there.', 'spam'); ?></div>
      </div>

      <a href="<?php echo osc_admin_base_url(true); ?>?page=plugins&action=renderplugin&file=spam/admin/banwords.php&plugin_action=refresh" class="mb-button-green mb-add-pack"><?php _e('Run ban words check on existing items', 'spam'); ?></a>

      <?php if(Params::getParam('plugin_action')=='refresh' or (Params::getParam('what') and Params::getParam('what') <> '')) { ?>
        <?php 
          $banwords_array = array_unique(array_filter(explode(',', trim(strtolower($list_banwords)))));
        ?>

        <div class="mb-table" style="margin-top:30px;">
          <?php if(count($banwords_array) <= 0) { ?>
            <div class="mb-table-row mb-row-empty">
              <i class="fa fa-warning"></i><span><?php _e('No ban words defined', 'spam'); ?></span>
            </div>
          <?php } else { ?>

            <?php foreach($banwords_array as $word) { ?>
              <?php $items = ModelANS::newInstance()->getItemsByBanword($word); ?>

              <?php if(count($items) > 0) { ?>
                <div class="mb-table-row mb-mass-action-row">
                  <div class="mb-col-24 mb-align-left">
                    <span><?php echo sprintf(__('Ban word: %s', 'spam'), '<strong>' . $word . '</strong>'); ?></span>
                    <a href="<?php echo osc_admin_base_url(true); ?>?page=plugins&action=renderplugin&file=spam/admin/banwords.php&what=mark_spam&banword=<?php echo $word; ?>" onclick="return confirm('<?php echo osc_esc_js(__('Are you sure you want to mark as spam all selected listings', 'spam')); ?>?')"><?php echo __('Mark all as Spam', 'spam'); ?></a>
                    <a href="<?php echo osc_admin_base_url(true); ?>?page=plugins&action=renderplugin&file=spam/admin/banwords.php&what=mark_block&banword=<?php echo $word; ?>" onclick="return confirm('<?php echo osc_esc_js(__('Are you sure you want to block all selected listings', 'spam')); ?>?')"><?php echo __('Block all', 'spam'); ?></a>
                    <a href="<?php echo osc_admin_base_url(true); ?>?page=plugins&action=renderplugin&file=spam/admin/banwords.php&what=mark_deactivate&banword=<?php echo $word; ?>" onclick="return confirm('<?php echo osc_esc_js(__('Are you sure you want to deactivate all selected listings', 'spam')); ?>?')"><?php echo __('Deactivate all', 'spam'); ?></a>
                    <a href="<?php echo osc_admin_base_url(true); ?>?page=plugins&action=renderplugin&file=spam/admin/banwords.php&what=delete_all&banword=<?php echo $word; ?>" onclick="return confirm('<?php echo osc_esc_js(__('Are you sure you want to delete all selected listings', 'spam')); ?>?')"><?php echo __('Delete all', 'spam'); ?></a>
                  </div>
                </div>

                <div class="mb-table-head">
                  <div class="mb-col-2"><?php _e('Status', 'spam'); ?></div>
                  <div class="mb-col-6 mb-align-left"><?php _e('Item Title', 'spam'); ?></div>
                  <div class="mb-col-12 mb-align-left"><?php _e('Description', 'spam'); ?></div>
                  <div class="mb-col-4 mb-align-right"><?php _e('Action', 'spam'); ?></div>
                </div>

     
                <?php foreach($items as $item) { ?>
                  <?php
                    $title = str_ireplace($word, '<strong class="mb-spam-bold">' . $word . '</strong>', $item['s_title']);
                    $desc = str_ireplace($word, '<strong class="mb-spam-bold">' . $word . '</strong>', $item['s_description']);

                    $id_class = '';
                    if($item['b_spam'] == 1) { $id_class='as_spam'; } elseif ($item['b_enabled'] == 0) { $id_class='as_blocked';} elseif ($item['b_active'] == 0) { $id_class='as_inactive';} else { $id_class='as_none';}
                  ?>

                  <div class="mb-table-row">
                    <div class="mb-col-2">
                      <?php if($item['b_spam'] == 1) { ?>
                        <span class="mb-spam"><?php _e('Spam', 'spam'); ?></span>
                      <?php } else if($item['b_enabled'] == 0) { ?>
                        <span class="mb-blocked"><?php _e('Blocked', 'spam'); ?></span>
                      <?php } else if($item['b_active'] == 0) { ?>
                        <span class="mb-inactive"><?php _e('Inactive', 'spam'); ?></span>
                      <?php } else { ?>
                        <span class="mb-visible"><?php _e('Active', 'spam'); ?></span>
                      <?php } ?>
                    </div>

                    <div class="mb-col-6 mb-align-left ans-has-tooltip-long" title="<?php echo osc_esc_html($title); ?>"><?php echo osc_highlight($title, 140); ?></div>
                    <div class="mb-col-12 mb-align-left ans-has-tooltip-long" title="<?php echo osc_esc_html($desc); ?>"><?php echo osc_highlight($desc, 300); ?></div>

                    <div class="mb-col-4 mb-align-right mb-spam-buttons">
                      <a target="_blank" class="mb-button-white mb-view" href="<?php echo osc_base_url(true); ?>?page=item&id=<?php echo $item['pk_i_id']; ?>"><i class="fa fa-external-link"></i></a>
                      <a target="_blank" class="mb-button-white mb-edit" href="<?php echo osc_admin_base_url(true); ?>?page=items&action=item_edit&id=<?php echo $item['pk_i_id']; ?>"><i class="fa fa-pencil"></i></a>
                    
                      <?php if(!ans_is_demo()) { ?>
                        <a target="_blank" class="mb-button-red mb-delete" href="<?php echo osc_admin_base_url(true); ?>?page=plugins&action=renderplugin&file=spam/admin/banwords.php&what=item_delete&item_id=<?php echo $item['pk_i_id']; ?>" onclick="return confirm('<?php echo osc_esc_js(__('Are you sure you want to delete this listing', 'spam')); ?>?')"><i class="fa fa-trash"></i></a>
                      <?php } ?>
                    </div>
                  </div>
                <?php } ?>
              <?php } ?>
            <?php } ?>
          <?php } ?>
        </div>
      <?php } ?>

    </div>
  </div>



  <!-- PLUGIN INTEGRATION -->
  <div class="mb-box">
    <div class="mb-head"><i class="fa fa-wrench"></i> <?php _e('Plugin Setup', 'spam'); ?></div>

    <div class="mb-inside">

      <div class="mb-row">
        <div class="mb-line"><?php _e('Ban words feature does not require any modifications in theme files.', 'spam'); ?></div>
      </div>
    </div>
  </div>
</div>

<?php echo ans_footer(); ?>