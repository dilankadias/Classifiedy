<div id="fi_list_items" class="fi_user_menu">
  <h2 class="r3"><a href="<?php echo osc_route_url('fi-favorite-items'); ?>"><?php _e('Items in your list', 'favorite_items'); ?></a></h2>

  <?php if( osc_count_items() == 0) { ?>
    <div class="fi_empty"><?php _e('You do not have any favorite listings', 'favorite_items'); ?></div>
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

        <div class="fi_tool">
          <span class="fi_list_remove" title="<?php echo osc_esc_html(__('Remove from list', 'favorite_items')); ?>" rel="<?php echo osc_item_id(); ?>"></span>
        </div>
      </div>
    <?php } ?>

  <?php } ?>
</div>

<div class="clear"></div>

<?php if($totalPages > 1) { ?>
  <div class="paginate"><ul>
    <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
      <li><a class="<?php if(Params::getParam('iPage') == $i) { ?>searchPaginationSelected<?php } else { ?>searchPaginationNonSelected<?php } ?>" href="<?php echo osc_route_url('favorite-lists', array('list-id' => Params::getParam('list-id'), 'iPage' => $i)); ?>"><?php echo $i; ?></a></li>
    <?php } ?>
  </ul></div>
<?php } ?>