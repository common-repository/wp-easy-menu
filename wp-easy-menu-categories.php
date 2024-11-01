<?php
/*
  Plugin: WP-Easy Menu
  Author: Jordi Salord Font <jordi@graficlab.com>
  License: GPL version v3.0
 */

class EasyMenuCategory {

      //Pseudo-abstract methods
      public static function getForm() {
            echo '<form id="wem_add_cat_form" action="' . WEMENU_ADMIN_URL . '" class="wem_adder" method="post">';
            echo '<div class="type">' . __('Adding Category', WEMENU_NAME) . '</div>';
            echo '<input type="hidden" value="wem_add_cat_form" name="form" />';
            echo self::dropDown();
            echo '<div><label>' . __('Levels', WEMENU_NAME) . '</label> ' . self::dropDownLevels($item['levels']) . '</div>';

            echo '<div><label>' . __('Name (optional)', WEMENU_NAME) . '</label><input type="text" name="name" /></div>';
            echo '<div><label>' . __('Top Order by', WEMENU_NAME) . '</label>' . self::dropDownOrderBy(false, 'orderbycat') . '</div>';
            echo '<div><label>' . __('Top Order', WEMENU_NAME) . '</label>' . self::dropDownOrder(false, 'ordercat') . '</div>';
            echo '<div><label>' . __('Name (optional)', WEMENU_NAME) . '</label><input type="text" name="name" /></div>';
            echo '<div><label>' . __('Hide empty categories', WEMENU_NAME) . '</label><input type="checkbox" checked name="hideempty" /></div>';
            echo '<div><label>' . __('Include posts', WEMENU_NAME) . '</label><input type="checkbox" name="incposts" />
<blockquote>
<div><label>' . __('Include posts from sub-categories', WEMENU_NAME) . '</label><input type="checkbox" name="incsubposts" /></div>
<div><label>' . __('Order by', WEMENU_NAME) . '</label>' . self::dropDownOrderBy() . '</div>
<div><label>' . __('Order', WEMENU_NAME) . '</label>' . self::dropDownOrder() . '</div>
</blockquote>
</div>';
            //echo '<div><label>' . __('Auto add new items', WEMENU_NAME) . '</label><input type="checkbox" name="autoadd" /></div>';
            echo '<div><input type="submit" value="' . __('Add category') . '" class="button-primary" /></div>';
            echo '</form>';
      }

      public static function addItem() {
            $menu = get_option('wem_menu');

            $name = $_POST['name'];
            $menu[] = array(
                'type' => 'add_cat_form',
                'id' => $_POST['id'],
                'name' => $name,
                'hideempty' => $_POST['hideempty'],
                'orderbycat' => $_POST['orderbycat'],
                'ordercat' => $_POST['ordercat'],
                'autoadd' => $_POST['autoadd'],
                'incposts' => $_POST['incposts'],
                'incsubposts' => $_POST['incsubposts'],
                'orderbyposts' => $_POST['orderbyposts'],
                'orderposts' => $_POST['orderposts'],
                'levels' => $_POST['levels']
            );

            update_option('wem_menu', $menu);
      }

      public static function getSubMenu($id, $orderby = false, $order = false, $count = false) {
            return self::getItem(array('id' => $id, 'orderby' => $orderby, 'order' => $order, 'incsub' => true, 'nohead' => true, 'nowrap' => true, 'count' => $count));
      }

      public static function getItem($item, $n = 1, $c = 0, & $refitem = null) {
            global $cat;
            if ($c == 0) {
                  $catch = get_category($item['id']);
                  $csorder = self::setLevels($item['id'], $item);
                  $csorder = $csorder[$catch->taxonomy];

                  $class = 'menu-item primary  menu-item-type-taxonomy menu-item-object-category cat-item cat-item-' . $catch->term_id;
                  if ($catch->term_id == $cat)
                        $class .= ' active';

                  $hideempty = EasyMenu::str2bool($item['hideempty']);
                  $orderbycat = ($item['orderbycat']);
                  $ordercat = ($item['ordercat']);
                  $incposts = EasyMenu::str2bool($item['incposts']);
                  $incsubposts = EasyMenu::str2bool($item['incsubposts']);
                  $orderbyposts = ($item['orderbyposts']);
                  $orderposts = ($item['orderposts']);
                  if ($item['name'])
                        $name = $item['name'];
                  else
                        $name = EasyMenu::translate($catch->name);

                  if ($item['count'])
                        $name .= ' (' . $catch->count . ')';
                  if (!@$item['nowrap'])
                        $o .= '<li id="menu-item-'.$item['id'].'" class="' . $class . ' menu-item-'.$item['id'].'">';
                  if (!@$item['nohead'])
                        $o .= '<a href="' . get_category_link($catch->term_id) . '">' . $name . '</a>';

                  $showsub = count($csorder[$item['id']]['childs']) > 0;
                  if (($incposts) || ($showsub)) {
                        $ao = '';
                        $r = '';
                              $posts = array();
                        if ($showsub) {
                              $r = self::getItem($csorder[$item['id']], $n, ($c + 1), $item);
                              $ao .= $r;
                        }
                        if ($incposts) {
                              $posts = self::getPosts($item);
                              if (count($posts) > 0) {
                                    if (($showsub) && ($r)) {
                                          //separator disabled
                                          //$o .= '<li class="sep"></li>';
                                    }

                                    $ao .= self::getItemPosts($posts);
                              }
                        }
                        if ($r ||  count($posts) > 0) {
                              $o .= '<ul class="sub-menu">';
                              $o .= $ao;
                              $o .= '</ul>';
                        }
                  }

                  if (!@$item['nowrap'])
                        $o .= '</li>';
            } elseif ($n >= $c) {
                  foreach ($item['childs'] as $k => $catch) {
                        $class = 'menu-item cat-item cat-item-' . $catch['cat']['id'];
                        if ($catch['cat']['id'] == $cat)
                              $class .= ' active';

                        $name = $catch['cat']['name'];
                        if ($refitem['count'])
                              $name .= ' (' . (int) $catch['cat']['count'] . ')';

                        if (!$catch['cat']['count']) {
                              if (!$refitem['hideempty'])
                                    $o .= '<li class="' . $class . ' empty"><a href="' . get_category_link($catch['cat']['id']) . '">' . $name . '</a>';
                        }
                        else
                              $o .= '<li class="' . $class . '"><a href="' . get_category_link($catch['cat']['id']) . '">' . $name . '</a>';



                        $showsub = count($catch['childs']) > 0;
                        if (($refitem['incsubposts']) || ($showsub)) {
                              $ao = '';
                              $r = '';
                              $posts = array();
                              if ($showsub) {
                                    $r = self::getItem($catch, $n, ($c + 1), $refitem);
                                    $ao .= $r;
                              }
                              if ($refitem['incsubposts']) {
                                    $args = array(
                                        'id' => $catch['cat']['id'],
                                        'orderbyposts' => $refitem['orderbyposts'],
                                        'orderposts' => $refitem['orderposts']
                                    );

                                    $posts = self::getPosts($args, '');
                                    if (count($posts) > 0) {
                                          if (($showsub) && ($r)) {
                                                //separator disabled
                                                //$o .= '<li class="sep"></li>';
                                          }

                                          $ao .= self::getItemPosts($posts);
                                    }
                              }
                              if ($r || count($posts) > 0) {
                                    $o .= '<ul class="sub-menu">';
                                    $o .= $ao;
                                    $o .= '</ul>';
                              }
                        }


                        $o .= '</li>';
                  }
            }

            return $o;
      }

      public static function getItemPosts(& $posts) {
            global $post;

            $o = '';
            foreach ($posts as $k => $p) {
                  $class = 'menu-item post-item post-item-' . $p->ID;
                  if (($post->ID == $p->ID) && is_singular())
                        $class .= ' active ' . $post->ID;

                  $o .= '<li class="' . $class . '"><a href="' . get_permalink($p->ID) . '">' . $p->post_title . '</a>';
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
            echo '<div class="type">' . __('Editing category', WEMENU_NAME) . ' <input type="hidden" name="post[cat' . $n . '][type]" value="' . $item['type'] . '" /></div>';
            echo self::dropDown($item['id'], false, false, 'post[cat' . $n . '][id]');
            echo '<div>' . __('Levels') . ' ' . self::dropDownLevels($item['levels'], 'post[cat' . $n . '][levels]') . '</div>';
            echo '<div class="name">' . __('Name (optional)', WEMENU_NAME) . ' <input type="text" name="post[cat' . $n . '][name]" value="' . $item['name'] . '" /></div>';

            echo '<div>' . __('Top Order by') . ' ' . self::dropDownOrderBy($item['orderbycat'], 'post[cat' . $n . '][orderbycat]') . '</div>';
            echo '<div>' . __('Top Order') . ' ' . self::dropDownOrder($item['ordercat'], 'post[cat' . $n . '][ordercat]') . '</div>';

            $checked = "";
            if ($item['hideempty'] == 'on')
                  $checked = " checked";
            echo '<div>' . __('Hide empty categories') . ' <input type="checkbox" name="post[cat' . $n . '][hideempty]"' . $checked . ' /></div>';

            $checked = "";
            if ($item['autoadd'] == 'on')
                  $checked = " checked";
            //echo '<div>' . __('Auto add new sub-categories') . ' <input type="checkbox" name="post[cat' . $n . '][autoadd]"' . $checked . ' /></div>';

            $checked = "";
            if ($item['incposts'] == 'on')
                  $checked = " checked";
            echo '<div>' . __('Include posts') . ' <input type="checkbox" name="post[cat' . $n . '][incposts]"' . $checked . ' />';

            $checked = "";
            if ($item['incsubposts'] == 'on')
                  $checked = " checked";
            echo '<blockquote>
<div><label>' . __('Include posts from sub-categories', WEMENU_NAME) . '</label><input type="checkbox" name="post[cat' . $n . '][incsubposts]"' . $checked . ' /></div>
<div><label>' . __('Order by', WEMENU_NAME) . '</label>' . self::dropDownOrderBy($item['orderbyposts'], 'post[cat' . $n . '][orderbyposts]') . '</div>
<div><label>' . __('Order', WEMENU_NAME) . '</label>' . self::dropDownOrder($item['orderposts'], 'post[cat' . $n . '][orderposts]') . '</div>
</blockquote></div>';
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

                  $o = '<label>' . __('Top level category', WEMENU_NAME) . '</label><select name="' . $name . '">';

                  if (@constant('EASYCPT_INIT') == true) {
                        $families = get_option('estore_families');
                  }
                  $families['category']['plural'] = __('Categories');

                  foreach ($levels as $taxonomy => $level) {
                        if ($taxonomy != 'category')
                              $taxonomy = substr($taxonomy, 0, -9); //removes the end of '_categories' to read from $families, not cool but useful

                        $sub = $level[0];
                        if (is_array($sub['childs'])) {
                              $o .= '<optgroup label="' . $families[$taxonomy]['plural'] . '">';
                              foreach ($sub['childs'] as $k => $v) {
                                    if ($id == $v['cat']['id'])
                                          $o .= '<option selected value="' . $v['cat']['id'] . '">' . $v['cat']['name'] . '</option>';
                                    else
                                          $o .= '<option value="' . $v['cat']['id'] . '">' . $v['cat']['name'] . '</option>';

                                    if ($v['childs'])
                                          $o .= self::dropDown($id, $v, ($n + 1));
                              }
                              $o .= '</optgroup>';
                        }
                  }

                  $o .= '</select>';
            }
            elseif (is_array($sub['childs'])) {
                  foreach ($sub['childs'] as $k => $v) {
                        if ($id == $v['cat']['id'])
                              $o .= '<option selected value="' . $v['cat']['id'] . '">' . EasyMenu::getIndent($n) . $v['cat']['name'] . '</option>';
                        else
                              $o .= '<option value="' . $v['cat']['id'] . '">' . EasyMenu::getIndent($n) . $v['cat']['name'] . '</option>';

                        if ($v['childs'])
                              $o .= self::dropDown(false, $v, ($n + 1));
                  }
            }

            return $o;
      }

      public static function dropDownLevels($id, $name = 'levels') {
            $o = '<select name="' . $name . '">';
            $o .= '<option value="0">' . __('0 (No sub-items)', WEMENU_NAME) . '</option>';
            for ($i = 1; $i < 10; $i++) {
                  if ($i == $id)
                        $o .= '<option value="' . $i . '" selected>' . $i . '</option>';
                  else
                        $o .= '<option value="' . $i . '">' . $i . '</option>';
            }
            $o .= '</select>';

            return $o;
      }

      public static function dropDownOrderBy($id = false, $name = 'orderbyposts') {
            $items = array(
                'name' => __('Name', WEMENU_NAME),
                'post_date' => __('Date', WEMENU_NAME)
            );

            return EasyMenu::dropDownGeneric($items, $id, $name);
      }

      public static function dropDownOrder($id = false, $name = 'orderposts') {
            $items = array(
                'ASC' => __('Ascendent', WEMENU_NAME),
                'DESC' => __('Descendent', WEMENU_NAME)
            );

            return EasyMenu::dropDownGeneric($items, $id, $name);
      }

      public static function getCategories($childof = 0, $taxonomy = 'category', $orderby = 'name', $order = 'ASC') { //get categories array
            $args = array(
                'type' => 'post',
                'child_of' => $childof,
                'parent' => '',
                'orderby' => $orderby,
                'order' => $order,
                'hide_empty' => 0,
                'hierarchical' => 1,
                'exclude' => '',
                'include' => '',
                'number' => '',
                'taxonomy' => $taxonomy,
                'pad_counts' => true);

            return get_categories($args);
      }

      public static function getPosts(& $refitem, $taxonomy = 'post') {
            $args = array(
                'numberposts' => 0,
                'offset' => 0,
                'category' => $refitem['id'],
                'orderby' => $refitem['orderbyposts'],
                'order' => $refitem['orderposts'],
                'include' => '',
                'exclude' => '',
                'meta_key' => '',
                'meta_value' => '',
                'post_type' => $taxonomy,
                'post_mime_type' => '',
                'post_parent' => '',
                'post_status' => 'publish');

            $o = get_posts($args);
            return $o;
      }

      public static $levels;

      public static function setLevels($childof = 0, $refitem = false) { //reorganize categories array by levels
            $levels = array();

            $list['category'] = self::getCategories($childof);

            if (@constant('EASYCPT_INIT') == true) {
                  $families = get_option('estore_families');
                  foreach ($families as $kf => $family) {
                        $taxonomy = $family['slug'] . '_category';
                        $list[$taxonomy] = self::getCategories($childof, $taxonomy, $refitem['orderbycat'], $refitem['ordercat']);
                  }
            }

            if (is_array($list)) {
                  foreach ($list as $taxonomy => $stuff) {
                        foreach ($stuff as $k => $v) {
                              $levels[$taxonomy][(int) $v->parent]['childs'][$v->cat_ID]['cat'] = array(
                                  'id' => $v->cat_ID,
                                  'name' => EasyMenu::translate($v->name),
                                  'count' => $v->count
                              );

                              $levels[$taxonomy][$v->cat_ID] = & $levels[$taxonomy][(int) $v->parent]['childs'][$v->cat_ID];
                        }
                  }
                  unset($list);
            }

            return $levels;
      }

}