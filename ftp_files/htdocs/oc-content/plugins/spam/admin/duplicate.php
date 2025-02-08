<?php
  // Create menu
  $title = __('Duplicated Content', 'spam');
  ans_menu($title);


  // GET & UPDATE PARAMETERS
  // $variable = mb_param_update( 'param_name', 'form_name', 'input_type', 'plugin_var_name' );
  // input_type: check or value

  $allow_check = mb_param_update('allow_check', 'plugin_action', 'check', 'plugin-spam');
  $duplicate_percent = mb_param_update('duplicate_percent', 'plugin_action', 'value', 'plugin-spam');
  $per_page = mb_param_update('per_page', 'plugin_action', 'value', 'plugin-spam');
  $action_check = mb_param_update('action_check', 'plugin_action', 'value', 'plugin-spam');
  $duplicate_length = mb_param_update('duplicate_length', 'plugin_action', 'value', 'plugin-spam');

  $duplicate_length = ($duplicate_length < 0 ? 200 : $duplicate_length);

  if(Params::getParam('plugin_action') == 'done') {
    message_ok(__('Settings were successfully saved', 'spam'));
  }



  if(Params::getParam('what')) {
    if(Params::getParam('what') == 'ban_email_ip' and Params::getParam('email') <> '' and Params::getParam('ip') <> '') {
      ModelANS::newInstance()->insertBan( 'Duplicate', Params::getParam('email'), Params::getParam('ip') );
      message_ok(__('Email & IP was successfully inserted into Ban List table', 'spam'));
    }

    if(Params::getParam('what') == 'ban_email' and Params::getParam('email') <> '') {
      ModelANS::newInstance()->insertEmailBan( Params::getParam('email') );
      message_ok(__('Email was successfully inserted into Ban List table', 'spam'));
    }

    if(Params::getParam('what') == 'ban_ip' and Params::getParam('ip') <> '') {
      ModelANS::newInstance()->insertIpBan( Params::getParam('ip') );
      message_ok(__('IP address was successfully inserted into Ban List table', 'spam'));
    }

    if(Params::getParam('what') == 'mark_spam' and Params::getParam('email') <> '') {
      ModelANS::newInstance()->updateItemSpam( Params::getParam('email') );
      message_ok(__('Listings were successfully marked as spam', 'spam'));
    }

    if(Params::getParam('what') == 'mark_deactivate' and Params::getParam('deactivate_email') <> '') {
      ModelANS::newInstance()->updateItemActivate( Params::getParam('deactivate_email') );
      message_ok(__('Listings were successfully deactivated', 'spam'));
    }

    if(Params::getParam('what') == 'mark_block' and Params::getParam('block_email') <> '') {
      ModelANS::newInstance()->updateItemBlock( Params::getParam('block_email') );
      message_ok(__('Listings were successfully blocked', 'spam'));
    }

    if(Params::getParam('what') == 'item_delete' and Params::getParam('item_id') <> '') {
      Item::newInstance()->deleteByPrimaryKey(Params::getParam('item_id'));
      message_ok(__('Listing was successfully deleted', 'spam'));
    }

    if(Params::getParam('what') == 'delete_all' and Params::getParam('delete_email') <> '') {
      foreach(ModelANS::newInstance()->getItemsByEmail(Params::getParam('delete_email')) as $item_check) {  
        Item::newInstance()->deleteByPrimaryKey($item_check['pk_i_id']);
      }
      message_ok(__('Listings from email', 'spam') . ' <strong>' . Params::getParam('delete_email') . '</strong> ' . __('were successfully deleted', 'spam'));
    }
  }


  $already_used = array();
  $last_email = '';

  $items_total = ModelANS::newInstance()->countItems();
  $num_spam = $items_total['total_count'];
  $num_list = $per_page;

  if($num_list == '') {
    $num_list = 50;
  }

  //Start position for pagination
  if (Params::getParam('start')) {
    $start = intval(Params::getParam('start'));
    if ($start > 0) {
      $start--;
    }
  } else {
    $start = 0;
  }

  // SCROLL TO DIV
  if(Params::getParam('plugin_action') == 'refresh' || Params::getParam('what') <> '' || Params::getParam('start') > 0) {
    ans_js_scroll('.mb-ban-words-check');
  }
?>


<div class="mb-body">
 
  <!-- CONFIGURE SECTION -->
  <div class="mb-box">
    <div class="mb-head"><i class="fa fa-wrench"></i> <?php _e('Duplicated content settings', 'spam'); ?></div>

    <div class="mb-inside mb-minify">
      <form name="promo_form" action="<?php echo osc_admin_base_url(true); ?>" method="POST" enctype="multipart/form-data" >
        <?php if(!ans_is_demo()) { ?>
        <input type="hidden" name="page" value="plugins" />
        <input type="hidden" name="action" value="renderplugin" />
        <input type="hidden" name="file" value="<?php echo osc_plugin_folder(__FILE__); ?>duplicate.php" />
        <input type="hidden" name="plugin_action" value="done" />
        <?php } ?>

        <div class="mb-row">
          <label for="allow_check" class="h1"><span><?php _e('Enable Duplicate Check', 'spam'); ?></span></label> 
          <input name="allow_check" type="checkbox" class="element-slide" <?php echo ($allow_check == 1 ? 'checked' : ''); ?> />

          <div class="mb-explain"><?php _e('When enabled, plugin will check each new listing added against all existing listings posted from same email address to check if this user did not already posted very similar listing.', 'spam'); ?></div>
        </div>


        <div class="mb-row">
          <label for="action_check" class="h2"><span><?php _e('Duplicate Item Action', 'spam'); ?></span></label> 
          <select name="action_check">
            <option <?php if($action_check == 'spam') { echo 'selected';} ?> value="spam"><?php _e('Mark listing as spam','spam'); ?></option>
            <option <?php if($action_check == 'delete') { echo 'selected';} ?> value="delete"><?php _e('Delete listing','spam'); ?></option>
          </select>

          <div class="mb-explain"><?php _e('When new listing is identified as duplicate, you can mark it as spam and disable for front-office or delete it from database to avoid overloading of database and reduce manual work with deleting duplicates.', 'spam'); ?></div>
        </div>     


        <div class="mb-row">
          <label for="duplicate_percent" class="h3"><span><?php _e('Maximum Similarity', 'spam'); ?></span></label> 
          <input type="number" size="10" name="duplicate_percent" value="<?php echo $duplicate_percent; ?>" style="text-align:right;"/>
          <div class="mb-input-desc">%</div>

          <div class="mb-explain"><?php _e('Maximum acceptable percentage of similarity means, when title/description of new listing is similar to any other title/description of listing posted by same user, it is detected as duplicate. Lower percentage means higer protection. Recommended is 80%.', 'spam'); ?></div>
        </div>     


        <div class="mb-row">
          <label for="duplicate_length" class="h4"><span><?php _e('Check Characters', 'spam'); ?></span></label> 
          <input type="number" size="10" name="duplicate_length" value="<?php echo $duplicate_length; ?>" style="text-align:right;"/>
          <div class="mb-input-desc"><?php _e('characters', 'spam'); ?></div>

          <div class="mb-explain"><?php _e('Set how many characters from title/description are used to detect duplicate listing. Note that more characters means more resources required, recommended is 200 characters. Example: If you set 200 characters to compare and description is 500 characters long, just first 200 characters is used.', 'spam'); ?></div>
        </div>     


        <div class="mb-row">
          <label for="per_page" class="h5"><span><?php _e('Items per Page', 'spam'); ?></span></label> 
          <input type="number" size="10" name="per_page" value="<?php echo $per_page; ?>" style="text-align:right;"/>
          <div class="mb-input-desc"><?php _e('items', 'spam'); ?></div>

          <div class="mb-explain"><?php _e('How many listings are compared on 1 page when you are looking for duplicates that already exists. More listings require more resources. Remember that when particular page is empty, it does not mean that also next page is empty. It just means, that on this page were not found any duplicates.', 'spam'); ?></div>
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
        <div class="mb-line"><?php _e('Before checking for duplicated listings, make sure to save updated list in above section, if you made change there.', 'spam'); ?></div>
        <div class="mb-line"><?php _e('There is no need to check for duplicates on listings posted after plugin installation.', 'spam'); ?></div>
      </div>

      <a href="<?php echo osc_admin_base_url(true); ?>?page=plugins&action=renderplugin&file=spam/admin/duplicate.php&plugin_action=refresh" class="mb-button-green"><?php _e('Run duplicate items control', 'spam'); ?></a>

      <?php if(Params::getParam('plugin_action')=='refresh' or (Params::getParam('what') and Params::getParam('what') <> '')) { ?>
        <?php 
          $items = ModelANS::newInstance()->getAllItems($start, $num_list, osc_current_user_locale());
        ?>

        <div class="mb-table mb-duplicates" style="margin-top:30px;">
          <?php if(count($items) <= 0) { ?>
            <div class="mb-table-row mb-row-empty">
              <i class="fa fa-warning"></i><span><?php _e('No items found', 'spam'); ?></span>
            </div>
          <?php } else { ?>
            <?php foreach($items as $item) { ?>
              <?php
                $already_used[] = $item['pk_i_id'];

                $origin_title = substr(trim(strtolower($item['s_title'])), 0, $duplicate_length);
                $origin_description = substr(trim(strtolower($item['s_description'])), 0, $duplicate_length);

                $title_percent = 0;
                $description_percent = 0;
  
                $items_check = ModelANS::newInstance()->getItemsByEmail($item['s_contact_email'], $item['fk_c_locale_code'], $item['pk_i_id']);
                $found = false;
              ?>



              <?php if(count($items_check) > 0) { ?>
                <?php foreach($items_check as $item_check) { ?>
                  <?php if(!in_array($item_check['pk_i_id'], $already_used) && $item['pk_i_id'] < $item_check['pk_i_id']) { ?>
                    <?php if($item['s_contact_email'] <> $last_email) { ?>
                      <div class="mb-table-row mb-mass-action-row">
                        <div class="mb-col-24 mb-align-left">
                          <span><?php echo sprintf(__('User: %s (%s)', 'spam'), '<strong>' . $item['s_contact_email'] . '</strong>', $item['s_ip']); ?></span>

                          <a href="<?php echo osc_admin_base_url(true); ?>?page=plugins&action=renderplugin&file=spam/admin/duplicate.php&what=ban_email_ip&email=<?php echo $item['s_contact_email']; ?>&ip=<?php echo $item['s_ip']; ?>" onclick="return confirm('<?php echo osc_esc_js(__('Are you sure you want to add this email and IP to ban list', 'spam')); ?>?')"><?php echo __('Add email & IP to ban list', 'spam'); ?></a>
                          <a href="<?php echo osc_admin_base_url(true); ?>?page=plugins&action=renderplugin&file=spam/admin/duplicate.php&what=ban_email&email=<?php echo $item['s_contact_email']; ?>" onclick="return confirm('<?php echo osc_esc_js(__('Are you sure you want to add this email to ban list', 'spam')); ?>?')"><?php echo __('Add email to ban list', 'spam'); ?></a>
                          <a href="<?php echo osc_admin_base_url(true); ?>?page=plugins&action=renderplugin&file=spam/admin/duplicate.php&what=ban_ip&ip=<?php echo $item['s_ip']; ?>" onclick="return confirm('<?php echo osc_esc_js(__('Are you sure you want to add this IP to ban list', 'spam')); ?>?')"><?php echo __('Add IP to ban list', 'spam'); ?></a>
                          <a href="<?php echo osc_admin_base_url(true); ?>?page=plugins&action=renderplugin&file=spam/admin/duplicate.php&what=mark_spam&email=<?php echo $item['s_contact_email']; ?>" onclick="return confirm('<?php echo osc_esc_js(__('Are you sure you want to mark as spam all selected listings', 'spam')); ?>?')"><?php echo __('Mark all as Spam', 'spam'); ?></a>
                          <a href="<?php echo osc_admin_base_url(true); ?>?page=plugins&action=renderplugin&file=spam/admin/duplicate.php&what=mark_block&block_email=<?php echo $item['s_contact_email']; ?>" onclick="return confirm('<?php echo osc_esc_js(__('Are you sure you want to block all selected listings', 'spam')); ?>?')"><?php echo __('Block all', 'spam'); ?></a>
                          <a href="<?php echo osc_admin_base_url(true); ?>?page=plugins&action=renderplugin&file=spam/admin/duplicate.php&what=mark_deactivate&deactivate_email=<?php echo $item['s_contact_email']; ?>" onclick="return confirm('<?php echo osc_esc_js(__('Are you sure you want to deactivate all selected listings', 'spam')); ?>?')"><?php echo __('Deactivate all', 'spam'); ?></a>
                          <a href="<?php echo osc_admin_base_url(true); ?>?page=plugins&action=renderplugin&file=spam/admin/duplicate.php&what=delete_all&delete_email=<?php echo $item['s_contact_email']; ?>" onclick="return confirm('<?php echo osc_esc_js(__('Are you sure you want to delete all selected listings', 'spam')); ?>?')"><?php echo __('Delete all', 'spam'); ?></a>

                        </div>
                      </div>

                      <div class="mb-table-head">
                        <div class="mb-col-1 mb-align-left"><?php _e('Type', 'spam'); ?></div>
                        <div class="mb-col-1"><?php _e('Id', 'spam'); ?></div>
                        <div class="mb-col-2"><?php _e('Status', 'spam'); ?></div>
                        <div class="mb-col-2"><?php _e('Similarity', 'spam'); ?></div>
                        <div class="mb-col-5 mb-align-left"><?php _e('Title', 'spam'); ?></div>
                        <div class="mb-col-2"><?php _e('Similarity', 'spam'); ?></div>
                        <div class="mb-col-8 mb-align-left"><?php _e('Description', 'spam'); ?></div>
                        <div class="mb-col-3 mb-align-right"><?php _e('Action', 'spam'); ?></div>
                      </div>
                    <?php } ?>

                    <?php 
                      $found = true;
                      $last_email = $item['s_contact_email']; 

                      $check_title = substr(trim(strtolower($item_check['s_title'])), 0, $duplicate_length);
                      $check_description = substr(trim(strtolower($item_check['s_description'])), 0, $duplicate_length);

                      // check similarity on title
                      similar_text($origin_title, $check_title, $title_percent);

                      // check similarity on description
                      similar_text($origin_description, $check_description, $description_percent);

                      if( $title_percent >= $duplicate_percent) { $s1 = 'redd'; } else { $s1 = 'greenn'; }
                      if( $description_percent >= $duplicate_percent) { $s2 = 'redd'; } else { $s2 = 'greenn'; }
                    ?>

                    <div class="mb-table-row">
                      <div class="mb-col-1 mb-align-left">
                        <div class="mb-line"><?php _e('Orig', 'spam'); ?></div>
                        <div class="mb-line"><?php _e('Check', 'spam'); ?></div>
                      </div>

                      <div class="mb-col-1">
                        <div class="mb-line"><?php echo $item['pk_i_id']; ?></div>
                        <div class="mb-line"><?php echo $item_check['pk_i_id']; ?></div>
                      </div>

                      <div class="mb-col-2">
                        <div class="mb-line">
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

                        <div class="mb-line">
                          <?php if($item_check['b_spam'] == 1) { ?>
                            <span class="mb-spam"><?php _e('Spam', 'spam'); ?></span>
                          <?php } else if($item_check['b_enabled'] == 0) { ?>
                            <span class="mb-blocked"><?php _e('Blocked', 'spam'); ?></span>
                          <?php } else if($item_check['b_active'] == 0) { ?>
                            <span class="mb-inactive"><?php _e('Inactive', 'spam'); ?></span>
                          <?php } else { ?>
                            <span class="mb-visible"><?php _e('Active', 'spam'); ?></span>
                          <?php } ?>
                        </div>
                      </div>

                      <div class="mb-col-2 mb-similarity">
                        <div class="mb-line mb-percent <?php echo $s1; ?>"><?php echo round($title_percent, 1); ?>%</div>
                      </div>

                      <div class="mb-col-5 mb-align-left">
                        <div class="mb-line ans-has-tooltip-long" title="<?php echo osc_esc_html($item['s_title']); ?>"><?php echo osc_highlight($item['s_title'], 120); ?></div>
                        <div class="mb-line ans-has-tooltip-long" title="<?php echo osc_esc_html($item_check['s_title']); ?>"><?php echo osc_highlight($item_check['s_title'], 120); ?></div>
                      </div>

                      <div class="mb-col-2 mb-similarity">
                        <div class="mb-line mb-percent <?php echo $s2; ?>"><?php echo round($description_percent, 1); ?>%</div>
                      </div>

                      <div class="mb-col-8 mb-align-left">
                        <div class="mb-line ans-has-tooltip-long" title="<?php echo osc_esc_html($item['s_description']); ?>"><?php echo osc_highlight($item['s_description'], 160); ?></div>
                        <div class="mb-line ans-has-tooltip-long" title="<?php echo osc_esc_html($item_check['s_description']); ?>"><?php echo osc_highlight($item_check['s_description'], 160); ?></div>
                      </div>

                      <div class="mb-col-3 mb-spam-buttons mb-align-right">
                        <div class="mb-line">
                          <a target="_blank" class="mb-button-white mb-view" href="<?php echo osc_base_url(true); ?>?page=item&id=<?php echo $item['pk_i_id']; ?>"><i class="fa fa-external-link"></i></a>
                          <a target="_blank" class="mb-button-white mb-edit" href="<?php echo osc_admin_base_url(true); ?>?page=items&action=item_edit&id=<?php echo $item['pk_i_id']; ?>"><i class="fa fa-pencil"></i></a>
                        
                          <?php if(!ans_is_demo()) { ?>
                            <a target="_blank" class="mb-button-red mb-delete" href="<?php echo osc_admin_base_url(true); ?>?page=plugins&action=renderplugin&file=spam/admin/banwords.php&what=item_delete&item_id=<?php echo $item['pk_i_id']; ?>" onclick="return confirm('<?php echo osc_esc_js(__('Are you sure you want to delete this listing', 'spam')); ?>?')"><i class="fa fa-trash"></i></a>
                          <?php } ?>
                        </div>

                        <div class="mb-line">
                          <a target="_blank" class="mb-button-white mb-view" href="<?php echo osc_base_url(true); ?>?page=item&id=<?php echo $item_check['pk_i_id']; ?>"><i class="fa fa-external-link"></i></a>
                          <a target="_blank" class="mb-button-white mb-edit" href="<?php echo osc_admin_base_url(true); ?>?page=items&action=item_edit&id=<?php echo $item_check['pk_i_id']; ?>"><i class="fa fa-pencil"></i></a>
                        
                          <?php if(!ans_is_demo()) { ?>
                            <a target="_blank" class="mb-button-red mb-delete" href="<?php echo osc_admin_base_url(true); ?>?page=plugins&action=renderplugin&file=spam/admin/banwords.php&what=item_delete&item_id=<?php echo $item_check['pk_i_id']; ?>" onclick="return confirm('<?php echo osc_esc_js(__('Are you sure you want to delete this listing', 'spam')); ?>?')"><i class="fa fa-trash"></i></a>
                          <?php } ?>
                        </div>
                      </div>
                    </div>

                    <?php if(!$found) { ?>
                      <?php
                        $start = Params::getParam('start');
                        $move = $start + $per_page;
                        $url = osc_admin_base_url(true) . '?page=plugins&action=renderplugin&plugin_action=refresh&file=spam/admin/duplicate.php&start=' . $move;
                      ?>

                      <?php if($num_spam < $start + $per_page) { ?>
                        <div class="mb-table-row mb-row-empty">
                          <i class="fa fa-warning"></i><span><?php echo __('No duplicates found on this page.', 'spam'); ?></span>
                        </div>
                      <?php } else { ?>
                        <div class="mb-table-row mb-row-empty">
                          <i class="fa fa-warning"></i><span><?php echo __('No duplicates found on this page. Please move to', 'spam'); ?> <a href="<?php echo $url; ?>"><?php echo __('next page', 'spam'); ?></a></span>
                        </div>
                      <?php } ?>
                    <?php } ?>
                  <?php } ?>
                <?php } ?>
              <?php } ?>
            <?php } ?>
          <?php } ?>
        </div>

        <?php if($per_page < $items_total) { ?>
          <div id="mb-pagination">
            <div class="mb-pagination-wrap">
              <?php echo ans_pagination($num_spam, $num_list, 'spam/admin/duplicate.php'); ?>
            </div>
          </div>
        <?php } ?>
      <?php } ?>

    </div>
  </div>



  <!-- PLUGIN INTEGRATION -->
  <div class="mb-box">
    <div class="mb-head"><i class="fa fa-wrench"></i> <?php _e('Plugin Setup', 'spam'); ?></div>

    <div class="mb-inside">

      <div class="mb-row">
        <div class="mb-line"><?php _e('Bad words feature does not require any modifications in theme files.', 'spam'); ?></div>
        <div class="mb-line"><?php _e('In some cases, you may want to replace function osc_item_title() with ans_clear_title() and osc_item_description() with ans_clear_description().', 'spam'); ?></div>
      </div>
    </div>
  </div>
</div>

<?php echo ans_footer(); ?>			