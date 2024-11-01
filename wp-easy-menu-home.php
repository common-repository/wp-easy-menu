<?php
/*
  Plugin: WP-Easy Menu
  Author: Jordi Salord Font <jordi@graficlab.com>
  License: GPL version v3.0
 */

class EasyMenuHome {

      //Pseudo-abstract methods
      public static function getForm() {
            echo '<form id="wem_add_home_link_form" action="' . WEMENU_ADMIN_URL . '" class="wem_adder" method="post">';
            echo '<input type="hidden" value="wem_add_home_link_form" name="form" />';
            echo '<div class="type">' . __('Home link', WEMENU_NAME) . '</div>';
            echo '<div><label>' . __('Name (optional)', WEMENU_NAME) . '</label><input type="text" name="name" /></div>';
            echo '<div><input type="submit" value="' . __('Add home link') . '" class="button-primary" /></div>';
            echo '</form>';
      }

      public static function addItem() {
            $menu = get_option('wem_menu');

            $name = $_POST['name'];
            if (!$name)
                  $name = __('Home', WEMENU_NAME);

            $menu[] = array(
                'type' => 'add_home_link_form',
                'name' => $name
            );

            update_option('wem_menu', $menu);
      }

      public static function getItem($item) {
            $class = 'menu-item primary home';

            if (is_home())
                  $class .= ' active';

            $o .= '<li class="' . $class . '"><a href="' . get_home_url() . '" class="' . $class . '">' . $item['name'] . '</a></li>';
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
            echo '<div class="type">' . __('Home link', WEMENU_NAME) . ' <input type="hidden" name="post[home' . $n . '][type]" value="' . $item['type'] . '" /></div>';
            echo '<div class="name">' . __('Name (optional)', WEMENU_NAME) . ' <input type="text" name="post[home' . $n . '][name]" value="' . $item['name'] . '" /></div>';
            echo '</div><div class="view"><ul class="menu">';
            echo self::getItem($item, $item['levels']);
            echo '</ul></div>';
            echo '</li>';
      }

}

?>