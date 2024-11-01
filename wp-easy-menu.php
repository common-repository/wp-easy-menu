<?php
/*
  Plugin Name: WP-Easy Menu
  Plugin URI: http://wordpress.menorcadev.com/wp-easy-menu/
  Description: Automatically generates menu from taxonomies, custom post types, categories, pages, posts and custom links.
  Version: 0.41
  Author: Jordi Salord Font <jordi@graficlab.com>
  Author URI: http://www.jordisalord.com
  Text Domain: wp-easy-menu
  License: Licensed under GPL version v3.0 - http://www.gnu.org/licenses/old-licenses/gpl-3.0.html
 */
/*
  Copyright 2012  Jordi Salord Font  (email : jordi@graficlab.com)
 */
define('WEMENU_NAME', 'wp-easy-menu');
define('WEMENU_TITLE', 'Easy Menu');
define('WEMENU_TOOLBAR', 'Easy Menu Toolbar');
define('WEMENU_URL', WP_PLUGIN_URL . '/' . WEMENU_NAME . '/');
define('WEMENU_PATH', dirname(__FILE__));
define('WEMENU_ADMIN_URL', get_admin_url() . 'admin.php?page=' . WEMENU_NAME);

//remove

require 'wp-easy-menu-home.php';
require 'wp-easy-menu-categories.php';
require 'wp-easy-menu-pages.php';
require 'wp-easy-menu-custom.php';
require_once 'hs-qtranslate.php';

class EasyMenu {

      //construct
      function EasyMenu() {
            if (is_admin()) {
                  add_action('init', array(&$this, 'adminInit'));
                  add_action('admin_menu', array(&$this, 'adminMenu'));
                  add_action('plugin_action_links', array(&$this, 'pluginActionLinks'), 10, 4);
            }
            load_plugin_textdomain(WEMENU_NAME, false, WEMENU_PATH . '/lang');
      }

      /* backend */

      function adminInit() {
            if (isset($_POST['action']) && isset($_POST['wes-form'])) {
                  
            }
            $url = WEMENU_URL . 'admin.css';
            wp_register_style('wemstyle', $url);
            self::procesPost();
      }

      function adminMenu() { //sidebar settings menu
            add_menu_page(WEMENU_TITLE, WEMENU_TITLE, 'edit_posts', WEMENU_NAME, array(&$this, 'options'), '', 4);
      }

      function options() { //easy slider administration page
            wp_enqueue_style('wemstyle');
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-sortable');
            require 'html/wp-admin.php';
      }

      function pluginActionLinks($links, $file) { //put a settings link on plugins list page
            if ($file == 'wp-easy-menu/' . basename(__FILE__)) {
                  $settings_link = '<a href="' . WEMENU_ADMIN_URL . '" title="' . __('Settings') . '">' . __('Settings') . '</a>';
                  array_unshift($links, $settings_link);
            }
            return $links;
      }

      function getChangelog($n = 1) { //get last changelog
            $data = file_get_contents(WEMENU_PATH . '/readme.txt');

            $str = '== Changelog ==';
            $start = strpos($data, $str);

            $tail = substr($data, $start + strlen($str));
            unset($data);

            $end = strpos($tail, '==');

            $out = substr($tail, 0, $end);
            unset($tail);
            $lines = explode("\n", $out);
            unset($out);

            echo '<div class="postbox">';
            $c = 0;
            foreach ($lines as $k => $v) {
                  if ($v) {
                        if ($v[0] == '=') {
                              if ($c < $n) {
                                    echo '<h3 class="hndle">Changelog ' . trim(str_replace('=', '', $v)) . '</h3><div class="inside"><ol>';
                                    $c++;
                              }
                              else
                                    break;
                        } elseif ($v[0] == '*') {
                              echo '<li>' . trim(substr($v, 1)) . '</li>';
                        }
                  }
            }
            unset($lines);
            echo '</ol></div></div>';
      }

      public static function uninstall() {//remove all saved data
            delete_option('wem_menu');
            delete_option('wem_htmlcache');
            self::olduninstall();
      }

      public static function olduninstall() {//old version uninstall
            $list = EasyMenuCategory::getCategories();

            foreach ($list as $k => $v) {
                  delete_option('wem_category_' . $v->cat_ID);
                  delete_option('wem_category_order_' . $v->cat_ID);
                  delete_option('wem_category_parent_' . $v->cat_ID);
                  delete_option('wem_category_name_' . $v->cat_ID);
            }
            delete_option('wem_usecat');
            delete_option('wem_hideempty');
      }

      public static function str2bool($str) { //html checkbox to bool
            if ($str == 'on')
                  return true;

            return false;
      }

      public static function getIndent($n=1) {
            $o = '';
            for ($i = 0; $i < $n; $i++)
                  $o .= '&nbsp;&gt;&nbsp;';
            return $o;
      }

      public static function dropDownGeneric($items, $id = false, $name = 'generic') {
            $o = '<select name="' . $name . '">';
            foreach ($items as $k => $v) {
                  $checked = '';
                  if ($id == $k)
                        $checked = " selected";
                  $o .= '<option value="' . $k . '"' . $checked . '>' . $v . '</option>';
            }
            $o .= '</select>';

            return $o;
      }

      /* frontend */

      private static function procesPost() {
            switch ($_GET['action']) {
                  case 'delete':
                        $pos = ((int) $_GET['position'] - 1);
                        $menu = get_option('wem_menu');
                        unset($menu[$pos]);
                        update_option('wem_menu', $menu);

                        return;
                        break;
            }

            if (isset($_POST['post'])) {
                  $newarray = array();
                  foreach ($_POST['post'] as $k => $field) {
                        $obj = array();
                        foreach ($field as $kf => $value) {
                              $obj[$kf] = $value;
                        }
                        $newarray[] = $obj;
                  }
                  update_option('wem_menu', $newarray);
            }

            switch ($_POST['form']) {
                  case 'wem_add_home_link_form':
                        EasyMenuHome::addItem();
                        break;
                  case 'wem_add_cat_form':
                        EasyMenuCategory::addItem();
                        break;
                  case 'wem_add_page_form':
                        EasyMenuPage::addItem();
                        break;
                  case 'wem_add_custom_form':
                        EasyMenuCustom::addItem();
                        break;
            }

            //update_option('wem_htmlcache',self::getPreview(true));
      }

      static function translate($input) {
            return $input;
            global $q_config;

            if (!isset($q_config))
                  return $input;

            if (is_array($input))
                  return $input[$q_config['language']];
            else {
                  $values = qtrans_split($input);
                  return $values[$q_config['language']];
            }
      }

      public static function getPreview($production = false) {
            $menu = get_option('wem_menu');

            $o = '';
            if (count($menu) > 0) {
                  if ($production)
                        $o .= '<div class="wp-easy-menu menu-header"><ul id="menu-main-menu" class="menu">';
                  else
                        $o .= '<ul id="wem_preview">';
                  $n = 1;

                  if (is_array($menu)) {
                        foreach ($menu as $k => $v) {
                              switch ($v['type']) {
                                    case 'add_home_link_form':
                                          $o .= EasyMenuHome::getPreviewRow($v, $n, $production);
                                          break;
                                    case 'add_cat_form':
                                          $o .= EasyMenuCategory::getPreviewRow($v, $n, $production);
                                          break;
                                    case 'add_page_form':
                                          $o .= EasyMenuPage::getPreviewRow($v, $n, $production);
                                          break;
                                    case 'add_custom_form':
                                          $o .= EasyMenuCustom::getPreviewRow($v, $n, $production);
                                          break;
                              }

                              $n++;
                        }
                  }

                  if ($production)
                        $o .= '</ul></div>';
                  else
                        $o .= '</ul>';
            } elseif (!$production) {
                  echo '<em class="empty">' . __('There is no item add to menu') . '</em>';
            }

            return $o;
      }

      public static function getMenu() {
            echo self::getPreview(true);
            //echo get_option('wem_htmlcache');
      }

}

new EasyMenu;