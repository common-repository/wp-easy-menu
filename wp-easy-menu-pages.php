<?php
/*
  Plugin: WP-Easy Menu
  Author: Jordi Salord Font <jordi@graficlab.com>
  License: GPL version v3.0
 */

class EasyMenuPage {

      //Pseudo-abstract methods
      public static function getForm() {
            echo '<form id="wem_add_page_form" action="' . WEMENU_ADMIN_URL . '" class="wem_adder" method="post">';
            echo '<div class="type">' . __('Adding page', WEMENU_NAME) . '</div>';
            echo '<input type="hidden" value="wem_add_page_form" name="form" />';
            echo self::dropDown();

            echo '<div><label>' . __('Name (optional)', WEMENU_NAME) . '</label><input type="text" name="name" /></div>';
            echo '<div><label>' . __('Include sub-pages', WEMENU_NAME) . '</label><input type="checkbox" name="incsub" />';
            echo '<blockquote><div><label>' . __('Levels', WEMENU_NAME) . '</label> ' . self::dropDownLevels($item['levels']) . '</div>';
            echo '<div><label>' . __('Order by', WEMENU_NAME) . '</label>' . self::dropDownOrderBy() . '</div>';
            echo '<div><label>' . __('Order', WEMENU_NAME) . '</label>' . self::dropDownOrder() . '</div></blockquote></div>';
            echo '<div><input type="submit" value="' . __('Add page') . '" class="button-primary" /></div>';
            echo '</form>';
      }

      public static function addItem() {
            $menu = get_option('wem_menu');

            $name = $_POST['name'];
            $menu[] = array(
                'type' => 'add_page_form',
                'id' => $_POST['id'],
                'name' => $name,
                'levels' => $_POST['levels'],
                'incsub' => $_POST['incsub'],
                'orderby' => $_POST['orderby'],
                'order' => $_POST['order']
            );
            update_option('wem_menu', $menu);
      }

      public static function getSubMenu($id, $orderby = false, $order = false) {
            return self::getItem(array('id' => $id, 'orderby' => $orderby, 'order' => $order, 'incsub' => true, 'nohead' => true, 'nowrap' => true));
      }

      public static function getItem($item, $n = 1, $c = 0, & $refitem = null) {
            global $post;
            if ($c == 0) {
                  $page = get_page($item['id']);

                  $porder = self::setLevels($item['id'], $item['orderby'], $item['order']);

                  $class = 'menu-item primary page-item page-item-' . $page->ID;

                  if ($post->ID == $page->ID)
                        $class .= ' active';

                  if ($item['name'])
                        $name = $item['name'];
                  else
                        $name = EasyMenu::translate($page->post_title);
                  if (!@$item['nowrap'])
                        $o .= '<li class="' . $class . '">';
                  if (!@$item['nohead']) {
                        if ($page->post_status == 'publish')
                              $o .= '<a href="' . get_page_link($page->ID) . '" class="' . $class . '">' . $name . '</a>';
                        else {
                              $o .= '<a class="' . $class . '">' . $name . '</a>';
                        }
                  }

                  if ($item['incsub']) {
                        $showsub = count($porder[$page->post_type][$item['id']]['childs']) > 0;
                        if ($showsub) {
                              $o .= '<ul class="sub-menu">';
                              if ($showsub) {
                                    $r = self::getItem($porder[$page->post_type][$item['id']], $n, ($c + 1), $item);
                                    $o .= $r;
                              }
                              $o .= '</ul>';
                        }
                  }

                  if (!@$item['nowrap'])
                        $o .= '</li>';
            } elseif ($n >= $c) {
                  foreach ($item['childs'] as $k => $page) {
                        $class = 'menu-item page-item page-item-' . $page['page']['id'];

                        if (($post->ID == $page['page']['id']) && (!is_home()))
                              $class .= ' active';

                        $o .= '<li class="' . $class . '"><a href="' . get_page_link($page['page']['id']) . '">' . $page['page']['name'] . '</a>';

                        $o .= '</li>';
                  }
            }

            return $o;
      }

      public static function getPreviewRow($item, $n, $production) {
            if ($production) {
                  return self::getItem($item, $item['levels']);
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
            echo '<div class="type">' . __('Editing page', WEMENU_NAME) . ' <input type="hidden" name="post[page' . $n . '][type]" value="' . $item['type'] . '" /></div>';
            echo self::dropDown($item['id'], false, false, 'post[page' . $n . '][id]');
            echo '<div class="name">' . __('Name (optional)', WEMENU_NAME) . ' <input type="text" name="post[page' . $n . '][name]" value="' . $item['name'] . '" /></div>';

            $checked = "";
            if ($item['incsub'] == 'on')
                  $checked = " checked";
            echo '<div><label>' . __('Include sub-pages', WEMENU_NAME) . '</label><input type="checkbox" name="post[page' . $n . '][incsub]"' . $checked . ' />';
            echo '<blockquote><div>' . __('Levels') . ' ' . self::dropDownLevels($item['levels'], 'post[page' . $n . '][levels]') . '</div>';
            echo '<div><label>' . __('Order by', WEMENU_NAME) . '</label>' . self::dropDownOrderBy($item['orderby'], 'post[page' . $n . '][orderby]') . '</div>';
            echo '<div><label>' . __('Order', WEMENU_NAME) . '</label>' . self::dropDownOrder($item['order'], 'post[page' . $n . '][order]') . '</div></blockquote>';
            echo '</div>';
            echo '</div>';
            echo '<div class="view"><ul class="menu">';
            echo self::getItem($item, $item['levels']);
            echo '</ul></div>';
            echo '</li>';
      }

      // Own methods
      public static function dropDown($id = false, $sub = false, $n = 0, $name = "id") {
            $first = false;
            if (!$sub) {
                  $levels = self::setLevels();

                  $o = '<label>' . __('Top level page', WEMENU_NAME) . '</label><select name="' . $name . '">';
                  $o .= '<option selected value="0">' . __('Select page', EASYCPT_NAME) . '</option>';

                  if (@constant('EASYCPT_INIT') == true) {
                        $families = get_option('easycpt_families');
                  }
                  $families['page']['plural'] = __('Pages');

                  foreach ($levels as $cpt => $level) {
                        $sub = $level[0];
                        if (is_array($sub['childs'])) {
                              $o .= '<optgroup label="' . $families[$cpt]['plural'] . '">';
                              if ($id == 'ALL')
                                    $o .= '<option selected value="ALL">' . __('ALL', EASYCPT_NAME) . '</option>';
                              else
                                    $o .= '<option value="ALL">' . __('ALL', EASYCPT_NAME) . '</option>';
                              foreach ($sub['childs'] as $k => $v) {
                                    if ($id == $v['page']['id'])
                                          $o .= '<option selected value="' . $v['page']['id'] . '">' . $v['page']['name'] . '</option>';
                                    else
                                          $o .= '<option value="' . $v['page']['id'] . '">' . $v['page']['name'] . '</option>';
                                    if ($v['childs']) {
                                          $o .= self::dropDown(false, $v, ($n + 1));
                                    }
                              }
                              $o .= '</optgroup>';
                        }
                  }

                  $o .= '</select>';
            } elseif (is_array($sub['childs'])) {
                  foreach ($sub['childs'] as $k => $v) {
                        if ($id == $v['page']['id'])
                              $o .= '<option selected value="' . $v['page']['id'] . '">' . EasyMenu::getIndent($n) . $v['page']['name'] . '</option>';
                        else
                              $o .= '<option value="' . $v['page']['id'] . '">' . EasyMenu::getIndent($n) . $v['page']['name'] . '</option>';

                        if ($v['childs'])
                              $o .= self::dropDown(false, $v, ($n + 1));
                  }
            }

            return $o;
      }

      public static function dropDownLevels($id, $name = 'levels') {
            $o = '<select name="' . $name . '">';
            for ($i = 1; $i < 10; $i++) {
                  if ($i == $id)
                        $o .= '<option value="' . $i . '" selected>' . $i . '</option>';
                  else
                        $o .= '<option value="' . $i . '">' . $i . '</option>';
            }
            $o .= '</select>';

            return $o;
      }

      public static function dropDownOrderBy($id = false, $name = 'orderby') {
            $items = array(
                'post_title' => __('Title', WEMENU_NAME),
                'post_date' => __('Date', WEMENU_NAME)
            );

            return EasyMenu::dropDownGeneric($items, $id, $name);
      }

      public static function dropDownOrder($id = false, $name = 'order') {
            $items = array(
                'ASC' => __('Ascendent', WEMENU_NAME),
                'DESC' => __('Descendent', WEMENU_NAME)
            );

            return EasyMenu::dropDownGeneric($items, $id, $name);
      }

      public static function getPages($childof = 0, $sort_column = 'post_title', $sort_order = 'ASC', $post_type = 'page') { //get pages array
            $inchild = $childof;
            if ($childof == 'ALL') {
                  $childof = -1;
                  $status = 'publish';
            } elseif ($childof == 0) {
                  $status = array('publish', 'draft');
            }
            else
                  $status = 'publish';

            if ($inchild == 'ALL') {
                  $args = array(
                  'numberposts' => 0,
                  'offset' => 0,
                  'category' => false,
                  'orderby' => $sort_column,
                  'order' => $sort_order,
                  'include' =>false,
                  'exclude' =>false,
                  'meta_key' =>false,
                  'meta_value' =>false,
                  'post_type' => $post_type,
                  'post_mime_type' =>false,
                  'post_parent' =>false,
                  'post_status' => $status );

                  $o = get_posts($args);
            } else {
                  $args = array(
                      'sort_order' => $sort_order,
                      'sort_column' => $sort_column,
                      'hierarchical' => 1,
                      'exclude' => false,
                      'include' => false,
                      'meta_key' => false,
                      'meta_value' => false,
                      'authors' => false,
                      'child_of' => $childof,
                      'exclude_tree' => false,
                      'number' => false,
                      'offset' => 0,
                      'post_type' => $post_type,
                      'post_status' => $status
                  );

                  $o = get_pages($args);
            }

            return $o;
      }

      public static function setLevels($childof = 0, $sort_column = 'post_title', $sort_order = 'ASC') { //reorganize pages array by levels
            $levels = array();

            $list['page'] = self::getPages($childof, $sort_column, $sort_order);
            if (@constant('EASYCPT_INIT') == true) {
                  $families = get_option('easycpt_families');

                  foreach ($families as $kf => $family) {
                        if ($family['hierarchical']) {
                              $list[$kf] = self::getPages($childof, $sort_column, $sort_order, $kf);
                        }
                  }
            }

            if (is_array($list)) {
                  foreach ($list as $cpt => $stuff) {
                        if (is_array($stuff)) {
                              foreach ($stuff as $k => $v) {
                                    $levels[$cpt][$v->ID] = array(
                                        'page' => array(
                                            'id' => $v->ID,
                                            'name' => EasyMenu::translate($v->post_title)
                                        )
                                    );

                                    $levels[$cpt][(int) $v->post_parent]['childs'][] = & $levels[$cpt][$v->ID];
                              }
                        }
                  }
                  unset($list);
            }

            return $levels;
      }

}