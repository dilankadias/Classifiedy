<div class="clear"></div>

<div id="fi_list_items" class="fi_user_menu fi_most_favorited">
  <h2><a href="<?php echo osc_route_url('fi-favorite-items'); ?>"><?php _e('Most favorited listings', 'favorite_items'); ?></a></h2>

  <?php if( osc_count_items() == 0) { ?>
    <div class="fi_empty"><?php _e('There are any favorite listings', 'favorite_items'); ?></div>
  <?php } else { ?>

    <?php while(osc_has_items()) { ?>
      <div class="fi_item fi_item_<?php echo osc_item_id(); ?>">
        <div class="fi_left">
          <?php if(osc_count_item_resources()) { ?>
            <a class="fi_img-link" href="<?php echo osc_item_url(); ?>">
              <img src="<?php echo osc_resource_thumbnail_url(); ?>" title="<?php echo osc_esc_html(osc_item_title()); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>" />
            </a>
          <?php } else { ?>
            <a class="fi_img-link" href="<?php echo osc_item_url(); ?>">
              <img src="<?php echo osc_base_url() . 'oc-content/plugins/favorite_items/img/no-image.png'; ?>" title="<?php echo osc_esc_html(osc_item_title()); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>" />
            </a>
          <?php } ?>
        </div>

        <div class="fi_right">
          <div class="fi_top">
            <a href="<?php echo osc_item_url(); ?>">
              <?php echo osc_item_title(); ?>
            </a>
          </div>

          <div class="fi_bottom">
            <?php if( osc_price_enabled_at_items() ) { ?>
              <?php echo osc_item_formated_price(); ?>
            <?php } ?>
          </div>
        </div>
      </div>
    <?php } ?>

  <?php } ?>
</div>