<?php
/*
  Plugin: WP-Easy Menu
  Author: Jordi Salord Font <jordi@graficlab.com>
  License: GPL version v3.0
 */

class EasyMenuCustom {

      //Pseudo-abstract methods
      public static function getForm() {
            echo '<form id="wem_add_custom_form" action="' . WEMENU_ADMIN_URL . '" class="wem_adder" method="post">';
            echo '<div class="type">' . __('Adding custom link', WEMENU_NAME) . '</div>';
            echo '<input type="hidden" value="wem_add_custom_form" name="form" />';
            echo '<div><label>' . __('Name (optional)', WEMENU_NAME) . '</label><input type="text" name="name" /></div>';
            echo '<div><label>' . __('URL', WEMENU_NAME) . '</label><input type="text" name="url" /></div>';
            echo '<div><label>' . __('Target', WEMENU_NAME) . '</label><input type="text" name="target" /></div>';
            echo '<div><input type="submit" value="' . __('Add custom link') . '" class="button-primary" /></div>';
            echo '</form>';
      }

      public static function addItem() {
            $menu = get_option('wem_menu');

            $name = $_POST['name'];
            $menu[] = array(
                'type' => 'add_custom_form',
                'id' => $_POST['id'],
                'name' => $name,
                'url' => $_POST['url'],
                'target' => $_POST['target']
            );
            update_option('wem_menu', $menu);
      }

      public static function getItem($item) {
            $class = 'menu-item primary link-item';

            $target = '';
            if ($item['target'])
                  $target = ' target="' . $item['target'] . '"';
            $o .= '<li class="' . $class . '"><a href="' . $item['url'] . '" class="' . $class . '"' . $target . '>' . $item['name'] . '</a>';

            $o .= '</li>';

            return $o;
      }

      public static function getPreviewRow($item, $n, $production) {
            if ($production) {
                  return self::getItem($item);
            }

            echo '<li class="item">';
            echo '<div class="toolbar">';
            echo '<a class="done" href="#">' . __('Done', WEMENU_NAME) . '</a>';
            echo '<a class="edit" href="#">' . __('Edit', WEMENU_NAME) . '</a>';
            echo '<a class="delete" href="' . WEMENU_ADMIN_URL . '&action=delete&position=' . $n . '">' . __('Delete', WEMENU_NAME) . '</a>';
            echo '<a class="up" href="#">' . __('Up', WEMENU_NAME) . '</a>';
            echo '<a class="down" href="#">' . __('Down', WEMENU_NAME) . '</a>';
            echo '</div>';
            echo '<div class="edit">';
            echo '<div class="type">' . __('Editing page', WEMENU_NAME) . ' <input type="hidden" name="post[custom' . $n . '][type]" value="' . $item['type'] . '" /></div>';
            echo '<div class="name">' . __('Name (optional)', WEMENU_NAME) . ' <input type="text" name="post[custom' . $n . '][name]" value="' . $item['name'] . '" /></div>';
            echo '<div class="url">' . __('URL', WEMENU_NAME) . ' <input type="text" name="post[custom' . $n . '][url]" value="' . $item['name'] . '" /></div>';
            echo '<div class="target">' . __('Target', WEMENU_NAME) . ' <input type="text" name="post[custom' . $n . '][target]" value="' . $item['name'] . '" /></div>';

            echo '</div>';
            echo '<div class="view"><ul class="menu">';
            echo self::getItem($item);
            echo '</ul></div>';
            echo '</li>';
      }

}