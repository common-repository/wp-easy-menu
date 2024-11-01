<?php
/*
  Plugin: WP-Easy Menu
  Author: Jordi Salord Font <jordi@graficlab.com>
  License: GPL version v3.0
 */
if (!constant('WEMENU_URL'))
      die("You can't access this file directly");

if (isset($_GET['reset'])) {
      EasyMenu::uninstall();
      echo '<div class="updated settings-error"><p><strong>' . __('Settings have been reset.', WEMENU_NAME) . '</strong></p></div>';
}
?>
<!--<div class="postbox" id="plugin_settings">
      <h3 class="hndle"><?php _e('Settings', WEMENU_NAME); ?></h3>
      <a name="usecat"></a>
      <div class="inside">-->
<div id="wem_items" class="postbox">
      <h3 class="hndle"><?php _e('Available items'); ?></h3>
      <div class="inside"><a href="#" id="wem_add_home_link" class="button-secondary button-adder"><?php _e('Add home link', WEMENU_NAME); ?></a>

            <a href="#" id="wem_add_cat" class="button-secondary button-adder"><?php _e('Add category', WEMENU_NAME); ?></a>

            <a href="#" id="wem_add_page" class="button-secondary button-adder"><?php _e('Add page', WEMENU_NAME); ?></a>
            <a href="#" id="wem_add_custom" class="button-secondary button-adder"><?php _e('Add custom link', WEMENU_NAME); ?></a>

            <?php
            EasyMenuHome::getForm();
            EasyMenuCategory::getForm();
            EasyMenuPage::getForm();
            EasyMenuCustom::getForm();
            ?></div>
</div>
<div id="wem_preview" class="postbox">
      <h3 class="hndle"><?php _e('Result', WEMENU_NAME); ?></h3>
      <div class="inside">
            <form action="<?php echo WEMENU_ADMIN_URL; ?>" method="post">
                  <p class="submit">
                        <input type="submit" name="submit" value="<?php _e('Save Changes', WEMENU_NAME); ?>" class="button-primary" />
                        <a href="<?php echo WEMENU_ADMIN_URL; ?>&reset" class="button-primary"><?php _e('Reset', WEMENU_NAME); ?></a>
                  </p>
                  
                  <ul id="wem_menu">
                        <?php
                        EasyMenu::getPreview();
                        ?>
                  </ul>


                  <p class="submit">
                        <input type="submit" name="submit" value="<?php _e('Save Changes', WEMENU_NAME); ?>" class="button-primary" />
                        <a href="<?php echo WEMENU_ADMIN_URL; ?>&reset" class="button-primary"><?php _e('Reset', WEMENU_NAME); ?></a>
                  </p>
            </form>
      </div>
</div>
<!--
</div>
</div>-->
<script language="javascript">
      jQuery(document).ready(function($){
            $('a.button-adder').click(function(){
                  $('form.wem_adder').hide();
                  $('#'+$(this).attr("id")+'_form').show();
                  return false;
            });
            $('input[type="checkbox"]').click(function(){
                  if ($(this).attr("checked") == "checked")
                        $(this).parent().find('blockquote').show();
                  else
                        $(this).parent().find('blockquote').hide();
            });
            $('input[type="checkbox"]').each(function(){
                  if ($(this).attr("checked") == "checked")
                        $(this).parent().find('blockquote').show();
                  else
                        $(this).parent().find('blockquote').hide();
            
            });
            $('a.edit,a.done').click(function(){
                  $(this).parent().parent().find('div.edit').toggle();
                  $(this).parent().parent().find('div.view').toggle();
                  $(this).parent().parent().find('a.edit').toggle();
                  $(this).parent().parent().find('a.done').toggle();
                  return false;
            });
            $('a.up').click(function(){
                  var hp = $(this).parent().parent();
                  var hpprev = hp.prev();
                  hpprev.insertAfter(hp);
                  return false;
            });
            $('a.down').click(function(){
                  var hp = $(this).parent().parent();
                  var hpnext = hp.next();
                  hpnext.insertBefore(hp);
                  return false;
            });
            $('a.delete').click(function(){
                  return confirm('<?php echo addslashes(__('Are you sure?')); ?>');
            });
            $('#wem_menu > li.item').mouseover(function(){
                  $(this).find('div.toolbar').show();
            }).mouseleave(function(){
                  $(this).find('div.toolbar').hide();
            });
            $('#wem_menu').sortable();
      });
</script>