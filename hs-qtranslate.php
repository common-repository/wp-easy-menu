<?php
if (!function_exists('hs_qtranslate_post')) {

      function hs_qtranslate_post($key) {
            global $q_config;
            if (!isset($q_config))
                  return $_POST[$key];
            else
                  return qtrans_join($_POST[$key]);
      }

}

if (!function_exists('hs_qtranslate_lang')) {

      function hs_qtranslate_lang() {
            global $q_config;
            if (!isset($q_config))
                  return 'en';

            return $q_config['language'];
      }

}

if (!function_exists('hs_qtranslate_flag')) {

      function hs_qtranslate_flag($language) {
            global $q_config;
            return get_bloginfo('url') . '/wp-content/' . $q_config['flag_location'] . $q_config['flag'][$language];
      }

}

if (!function_exists('hs_qtranslate')) {

      function hs_qtranslate($input,$filter = false) {
            global $q_config;
            if (!isset($q_config))
                  $output = $input;
            elseif (is_array($input))
                  $output = $input[$q_config['language']];
            else {
                  $values = qtrans_split($input);
                  $output = $values[$q_config['language']];
            }
            
            if ($filter) {
                  $output = apply_filters('the_content', $output);
                  $output = str_replace(']]>', ']]&gt;', $output);
            }
            
            return $output;
      }

}

if (!function_exists('hs_qtranslate_changer')) {

      function hs_qtranslate_changer() {
            global $q_config;
            if (!isset($q_config))
                  return;
            
            echo '<div class="hs_qtranslate_changer html-active" style="z-index: 98;position: absolute;right: 162px;">';
            foreach ($q_config['enabled_languages'] as $language) {
                  //      echo '<li class="hs_qtranslate_button_' . $language . '" style="display: inline-block;padding-right: 5px;"><a href="#" title="' . $language . '">' . $q_config['language_name'][$language] . '</a></li>';
                  echo '<a href="#" class="wp-switch-editor switch-tmce switch-html hs_qtranslate_button_' . $language . '" title="' . $language . '" style="text-decoration:none;">' . $q_config['language_name'][$language] . '</a>';
            }
            echo '</div>';
      }

}

if (!function_exists('hs_qtranslate_javascript')) {

      function hs_qtranslate_javascript() {
            global $q_config;
            if (!isset($q_config))
                  return;
            
            ?>
            <script type="text/javascript">
                  var hs_qtranslate_langs = <?php echo json_encode($q_config['enabled_languages']); ?>;
                  var hs_qtranslate_current = '<?php echo $q_config['language']; ?>';
                  jQuery(document).ready(function(){
                        setTimeout(function(){hs_qtranslate_set(hs_qtranslate_current)},1000);
                        jQuery('div.hs_qtranslate_changer a').click(function(){
                              hs_qtranslate_set(jQuery(this).attr('title'));
                              return false;
                        });
                        hs_qtranslate_binder();
                  })
                  function hs_qtranslate_set(lang) {
                        var scrt = jQuery(window).scrollTop();
                        for (i in hs_qtranslate_langs) {
                              if (hs_qtranslate_langs[i] != lang) {
                                    hs_qtranslate_hide(hs_qtranslate_langs[i]);
                                    jQuery('a.hs_qtranslate_button_'+hs_qtranslate_langs[i]).removeClass('switch-html');
                              } else {
                                    hs_qtranslate_show(hs_qtranslate_langs[i]);
                                    jQuery('a.hs_qtranslate_button_'+hs_qtranslate_langs[i]).addClass('switch-html');
                              }
                        }
                        jQuery(window).scrollTop(scrt);
                  }
                  function hs_qtranslate_hide(lang) {
                        jQuery('textarea.hs_qtranslate_'+lang).each(function(){
                              var heditorwrap = jQuery(this).parents('div.wp-editor-wrap');
                              if (heditorwrap.length > 0)
                                    heditorwrap.hide();
                              else
                                    jQuery(this).hide();
                        });
                  }
                  function hs_qtranslate_show(lang) {
                        jQuery('.hs_qtranslate_'+lang).each(function(){
                              var heditorwrap = jQuery(this).parents('div.wp-editor-wrap');
                              if (heditorwrap.length > 0)
                                    heditorwrap.show();
                              else
                                    jQuery(this).show();
                        });
                  }
                  var hsqformimage = null;
                  function hs_qtranslate_binder() {
                        jQuery('input.hs_qtranslate_image_button').unbind('click').click(function() {
                              hsqformimage = jQuery(this).parent().find('.hs_qtranslate_image');
                              tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
                              return false;
                        });

                        window.send_to_editor = function(html) {
                              imgurl = jQuery('img',html).attr('src');
                              hsqformimage.val(imgurl);
                              hsqformimage.parent().find('img').attr("src",imgurl);
                              tb_remove();
                        }
                  }
            </script>
            <?php
      }

}

if (!function_exists('hs_qtranslate_input')) {

      function hs_qtranslate_input($id, $value, $skiplang = false) {
            global $q_config;

            if ((!isset($q_config)) || ($skiplang)) {
                  echo '<input type="text" name="' . $id . '" id="' . $id . '" value="' . $value . '" />';
                  return;
            }

            if (is_array($value))
                  $values = & $value;
            else
                  $values = qtrans_split($value);

            foreach ($q_config['enabled_languages'] as $language) {
                  $flag = hs_qtranslate_flag($language);
                  echo '<input type="text" name="' . $id . '[' . $language . ']" id="' . $id . '_' . $language . '" value="' . $values[$language] . '" class="hs_qtranslate hs_qtranslate_' . $language . '" style="background:url(' . $flag . ') no-repeat 5px 5px transparent;padding-left:25px;" />';
            }
            return $content;
      }

}

if (!function_exists('hs_qtranslate_textarea')) {

      function hs_qtranslate_textarea($id, $value, $skiplang = false) {
            global $q_config;
            if ((!isset($q_config)) || ($skiplang)) {
                  echo '<textarea name="' . $id . '" id="' . $id . '">' . $value . '</textarea>';
                  return;
            }

            if (is_array($value))
                  $values = & $value;
            else
                  $values = qtrans_split($value);

            hs_qtranslate_changer();
            foreach ($q_config['enabled_languages'] as $language) {
                  $flag = hs_qtranslate_flag($language);
                  echo '<textarea name="' . $id . '[' . $language . ']" id="' . $id . '_' . $language . '" class="hs_qtranslate hs_qtranslate_' . $language . '" style="background:url(' . $flag . ') no-repeat 5px 5px transparent;padding-left:25px;">' . $values[$language] . '</textarea>';
            }
            return $content;
      }

}

if (!function_exists('hs_qtranslate_editor')) {

      function hs_qtranslate_editor($id, $value, $skiplang = false) {
            global $q_config;
            if ((!isset($q_config)) || ($skiplang)) {
                  $settings = array('textarea_name' => $id);
                  wp_editor($value, $id, $settings);
                  return;
            }

            if (is_array($value))
                  $values = & $value;
            else
                  $values = qtrans_split($value);

            hs_qtranslate_changer();
            foreach ($q_config['enabled_languages'] as $language) {
                  $flag = hs_qtranslate_flag($language);
                  echo '<div style="position: relative;">';
                  //echo '<img src="' . $flag . '" style="position: absolute; right: 120px; top: 12px;"/>';
                  $settings = array('textarea_name' => $id . '[' . $language . ']', 'textarea_id' => $id . '[' . $language . ']', 'editor_class' => 'hs_qtranslate hs_qtranslate_' . $language, 'textarea_height' => 600);
                  wp_editor($values[$language], $id . '_' . $language, $settings);
                  echo '</div>';
            }
            return $content;
      }

}



if (!function_exists('hs_qtranslate_image')) {

      function hs_qtranslate_image($id, $value, $skiplang = false, $size = array(256,256), $skipimage = false) {
            global $q_config;

            if ((!isset($q_config)) || ($skiplang)) {
                  //echo '<input type="file" name="' . $id . '" id="' . $id . '" value="' . $value . '" />';
                  echo '<div>';
                  if ((!$skipimage) && ($value)) {
                        echo '<img src="'.$value.'" style="max-width:'.$size[0].'px;max-height:'.$size[1].'px;" /><br />';
                  }
                  echo '<input type="text" class="hs_qtranslate_image" name="' . $id . '" id="' . $id . '" value="' . $value . '" />';
                  echo '<input type="button" class="hs_qtranslate_image_button" value="' . __('Upload image') . '" /></div>';
                  return;
            }
            
            if (is_array($value))
                  $values = & $value;
            else
                  $values = qtrans_split($value);
            
            foreach ($q_config['enabled_languages'] as $language) {
                  $flag = hs_qtranslate_flag($language);
                  echo '<div style="background:url(' . $flag . ') no-repeat 5px 5px transparent;padding-left:25px;">';
                  if ((!$skipimage) && ($values[$language])) {
                        echo '<img src="'.$values[$language].'" style="max-width:'.$size[0].'px;max-height:'.$size[1].'px;" /><br />';
                  }
                  echo '<input type="text" class="hs_qtranslate_image" name="' . $id . '[' . $language . ']" id="' . $id . '_' . $language . '" value="' . $values[$language] . '" />';
                  echo '<input type="button" class="hs_qtranslate_image_button" value="' . __('Upload image') . '" /></div>';
            }
            return $content;
      }

}

if (!function_exists('hs_qtranslate_list')) {

      function hs_qtranslate_list($id, $value, $list, $multiple = false) {
            global $q_config;

            $multiplestr = '';
            if ($multiple)
                  $multiplestr = ' multiple';

            if (!isset($q_config)) {
                  if (!$multiple)
                        $value = array($value);

                  echo '<select name="' . $id . '" id="' . $id . '"' . $multiple . '>';
                  foreach ($list as $kl => $item) {
                        $selected = '';
                        if (in_array($kl, $value))
                              $selected = ' selected';
                        echo '<option value="' . $kl . '"' . $selected . '>' . $item . '</option>';
                  }
                  echo '</select>';
                  return;
            }

            if (is_array($value))
                  $values = & $value;
            else
                  $values = qtrans_split($value);

            foreach ($q_config['enabled_languages'] as $language) {
                  if (isset($list[$language]))
                        $olist = & $list[$language];
                  else
                        $olist = & $list;

                  echo '<select name="' . $id . '[' . $language . ']" id="' . $id . '_' . $language . '" class="hs_qtranslate hs_qtranslate_' . $language . '"' . $multiple . '>';
                  foreach ($olist as $kl => $item) {
                        $selected = '';
                        if ($kl == $values[$language])
                              $selected = ' selected';
                        echo '<option value="' . $kl . '"' . $selected . '>' . $item . '</option>';
                  }
                  echo '</select>';
            }
            return $content;
      }

}
