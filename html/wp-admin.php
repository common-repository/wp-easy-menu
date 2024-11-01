<?php
/*
  Plugin: WP-Easy Menu
  Author: Jordi Salord Font <jordi@graficlab.com>
  License: GPL version v3.0
 */

if (!constant('WEMENU_URL'))
      die("You can't access this file directly");

if (!current_user_can('manage_options')) {
      wp_die(__('You do not have sufficient permissions to access this page.'));
}
?>
<div class="wrap">
      <div id="icon-options-general" class="icon32"><br /></div>
      <h2><?php echo WEMENU_TITLE; ?></h2>
      <div class="metabox-holder has-right-sidebar">
            <div class="inner-sidebar">
                  <?php
                  $sidebar_plugin = WEMENU_NAME;
                  $sidebar_plugin_url = WEMENU_URL;
                  $sidebar_init = true;
                  require 'wp-admin-sidebar.php';
                  ?>
            </div>
            <div class="has-sidebar sm-padded">
                  <div id="post-body-content" class="has-sidebar-content">
                        <div class="meta-box-sortabless">
                              <div class="postbox instructions">
                                    <h3 class="hndle"><?php _e('Instructions', 'wp-easy-menu'); ?></h3>
                                    <div class="inside">
                                          <?php _e('Follow this steps:'); ?>
                                          <ol>
                                                <li><?php echo _e("Replace a <strong>wp_nav_menu()</strong> function for <strong>EasyMenu::getMenu()</strong>. Primary menu call function is usually placed on <strong>header.php</strong> theme's file"); ?></li>
                                                <li><?php _e("Choose your settings below this instructions"); ?></il>
                                          </ol>
                                    </div>
                              </div>
                              <?php
                              require 'wp-admin-form.php';
                              ?>
                        </div>
                  </div>
            </div>
            <br style="clear:both" />
      </div>
</div>