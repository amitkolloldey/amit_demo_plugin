<?php
/**
 * Add the settings page
 *
 * @since      1.0.0
 * @package    Amit_demo_plugin
 * @subpackage Amit_demo_plugin/admin
 */

 class Amit_demo_plugin_Settings_Page
 { 
     /**
      * Add settings page to the admin menu
      */
     public function add_settings_page()
     {
         add_menu_page(
             esc_html__('Amit Demo Plugin Settings', 'amit_demo_plugin'),
             esc_html__('Plugin Settings', 'amit_demo_plugin'),
             'manage_options',
             'amit_demo_plugin_settings',
             [$this, 'render_settings_page'],
             'dashicons-admin-generic'
         );
     }
 
     /**
      * Register plugin settings
      */
     public function register_settings()
     {
         // Register the main settings group
         register_setting('amit_demo_plugin_options_group', 'amit_demo_plugin_options', [
             'sanitize_callback' => [$this, 'sanitize_options'],
         ]);
 
         // Add sections and fields for the settings page
         add_settings_section('amit_demo_plugin_general_settings', esc_html__('General Settings', 'amit_demo_plugin'), [$this, 'general_settings_section_callback'], 'amit_demo_plugin_settings');
         
         add_settings_field('amit_demo_plugin_text_field', esc_html__('Text Field Setting', 'amit_demo_plugin'), [$this, 'render_text_field'], 'amit_demo_plugin_settings', 'amit_demo_plugin_general_settings');
         add_settings_field('amit_demo_plugin_checkbox_field', esc_html__('Enable Feature', 'amit_demo_plugin'), [$this, 'render_checkbox_field'], 'amit_demo_plugin_settings', 'amit_demo_plugin_general_settings');
         add_settings_field('amit_demo_plugin_file_upload', esc_html__('Upload File', 'amit_demo_plugin'), [$this, 'render_file_upload_field'], 'amit_demo_plugin_settings', 'amit_demo_plugin_general_settings');
         add_settings_field('amit_demo_plugin_textarea_field', esc_html__('Textarea Field', 'amit_demo_plugin'), [$this, 'render_textarea_field'], 'amit_demo_plugin_settings', 'amit_demo_plugin_general_settings');
         add_settings_field('amit_demo_plugin_select_field', esc_html__('Select Field', 'amit_demo_plugin'), [$this, 'render_select_field'], 'amit_demo_plugin_settings', 'amit_demo_plugin_general_settings');
         add_settings_field('amit_demo_plugin_radio_field', esc_html__('Radio Field', 'amit_demo_plugin'), [$this, 'render_radio_field'], 'amit_demo_plugin_settings', 'amit_demo_plugin_general_settings');
         add_settings_field('amit_demo_plugin_multi_checkbox_field', esc_html__('Select Multiple Options', 'amit_demo_plugin'), [$this, 'render_multi_checkbox_field'], 'amit_demo_plugin_settings', 'amit_demo_plugin_general_settings');
     }
 
     /**
      * Render settings page
      */
     public function render_settings_page()
     {
         ?>
         <div class="wrap">
             <h1><?php esc_html_e('Amit Demo Plugin Settings', 'amit_demo_plugin'); ?></h1>
             <form method="post" action="options.php" enctype="multipart/form-data">
                 <?php
                 settings_fields('amit_demo_plugin_options_group');
                 do_settings_sections('amit_demo_plugin_settings');
                 submit_button();
                 ?>
             </form>
         </div>
         <?php
     }

     /**
      * General section callback
      */
     public function general_settings_section_callback() {
        $options = get_option('amit_demo_plugin_options'); 
        // Get the individual option values
        $text_field = isset($options['text_field']) ? esc_html($options['text_field']) : '';
        $checkbox_field = isset($options['checkbox_field']) ? $options['checkbox_field'] : 0;
        $file_upload = isset($options['file_upload']) ? esc_html($options['file_upload']) : '';
        $textarea_field = isset($options['textarea_field']) ? esc_html($options['textarea_field']) : '';
        $select_field = isset($options['select_field']) ? esc_html($options['select_field']) : '';
        $radio_field = isset($options['radio_field']) ? esc_html($options['radio_field']) : '';
        $multi_checkbox_field = isset($options['multi_checkbox_field']) ? $options['multi_checkbox_field'] : [];
    
        // Display all the values in the section callback
        echo '<p>' . esc_html__('Configure general settings for the plugin.', 'amit_demo_plugin') . '</p>';
    
        // Text field value
        echo '<p><strong>' . esc_html__('Text Field:', 'amit_demo_plugin') . '</strong> ' . $text_field . '</p>';
    
        // Checkbox field value
        echo '<p><strong>' . esc_html__('Checkbox Field:', 'amit_demo_plugin') . '</strong> ' . ($checkbox_field ? esc_html__('Enabled', 'amit_demo_plugin') : esc_html__('Disabled', 'amit_demo_plugin')) . '</p>';
    
        // File upload value
        echo '<p><strong>' . esc_html__('File Upload:', 'amit_demo_plugin') . '</strong> ' . ($file_upload ? $file_upload : esc_html__('No file uploaded', 'amit_demo_plugin')) . '</p>';
    
        // Textarea value
        echo '<p><strong>' . esc_html__('Textarea Field:', 'amit_demo_plugin') . '</strong> ' . $textarea_field . '</p>';
    
        // Select field value
        echo '<p><strong>' . esc_html__('Select Field:', 'amit_demo_plugin') . '</strong> ' . ($select_field ? $select_field : esc_html__('No option selected', 'amit_demo_plugin')) . '</p>';
    
        // Radio field value
        echo '<p><strong>' . esc_html__('Radio Field:', 'amit_demo_plugin') . '</strong> ' . ($radio_field ? $radio_field : esc_html__('No option selected', 'amit_demo_plugin')) . '</p>';
    
        // Multi-checkbox field value
        if (!empty($multi_checkbox_field)) {
            echo '<p><strong>' . esc_html__('Multi Checkbox Field:', 'amit_demo_plugin') . '</strong> ' . implode(', ', $multi_checkbox_field) . '</p>';
        } else {
            echo '<p><strong>' . esc_html__('Multi Checkbox Field:', 'amit_demo_plugin') . '</strong> ' . esc_html__('No options selected', 'amit_demo_plugin') . '</p>';
        }
    }
    
     /**
      * Render text field for settings
      */
     public function render_text_field()
     {
         $options = get_option('amit_demo_plugin_options');
         ?>
         <input type="text" name="amit_demo_plugin_options[text_field]" value="<?php echo esc_attr($options['text_field'] ?? ''); ?>" />
         <?php
     }
 
     /**
      * Render checkbox field for settings
      */
     public function render_checkbox_field()
     {
         $options = get_option('amit_demo_plugin_options');
         ?>
         <input type="checkbox" name="amit_demo_plugin_options[checkbox_field]" value="1" <?php checked(1, $options['checkbox_field'] ?? 0); ?> />
         <?php
     }
 
     /**
      * Render file upload field for settings
      */
      public function render_file_upload_field()
      {
          $options = get_option('amit_demo_plugin_options');
          ?>
          <input type="file" name="amit_demo_plugin_options[file_upload]" />
          
          <?php if (!empty($options['file_upload'])): ?>
              <!-- Hidden field to store the existing file URL -->
              <input type="hidden" name="amit_demo_plugin_options[existing_file]" value="<?php echo esc_url($options['file_upload']); ?>" />
              <p>Current file: <a href="<?php echo esc_url($options['file_upload']); ?>" target="_blank"><?php echo basename($options['file_upload']); ?></a></p>
          <?php endif; ?>
          <?php
      }

     /**
      * Render textarea field for settings
      */
     public function render_textarea_field()
     {
         $options = get_option('amit_demo_plugin_options');
         ?>
         <textarea name="amit_demo_plugin_options[textarea_field]" rows="5" cols="50"><?php echo esc_textarea($options['textarea_field'] ?? ''); ?></textarea>
         <?php
     }

     /**
      * Render select field for settings
      */
     public function render_select_field()
     {
         $options = get_option('amit_demo_plugin_options');
         ?>
         <select name="amit_demo_plugin_options[select_field]">
             <option value="option1" <?php selected('option1', $options['select_field'] ?? ''); ?>><?php esc_html_e('Option 1', 'amit_demo_plugin'); ?></option>
             <option value="option2" <?php selected('option2', $options['select_field'] ?? ''); ?>><?php esc_html_e('Option 2', 'amit_demo_plugin'); ?></option>
             <option value="option3" <?php selected('option3', $options['select_field'] ?? ''); ?>><?php esc_html_e('Option 3', 'amit_demo_plugin'); ?></option>
         </select>
         <?php
     }

     /**
      * Render radio field for settings
      */
     public function render_radio_field()
     {
         $options = get_option('amit_demo_plugin_options');
         ?>
         <input type="radio" name="amit_demo_plugin_options[radio_field]" value="option1" <?php checked('option1', $options['radio_field'] ?? ''); ?> /> <?php esc_html_e('Option 1', 'amit_demo_plugin'); ?><br>
         <input type="radio" name="amit_demo_plugin_options[radio_field]" value="option2" <?php checked('option2', $options['radio_field'] ?? ''); ?> /> <?php esc_html_e('Option 2', 'amit_demo_plugin'); ?><br>
         <input type="radio" name="amit_demo_plugin_options[radio_field]" value="option3" <?php checked('option3', $options['radio_field'] ?? ''); ?> /> <?php esc_html_e('Option 3', 'amit_demo_plugin'); ?><br>
         <?php
     }

     /**
      * Render multi-checkbox field for settings
      */
     public function render_multi_checkbox_field()
     {
         $options = get_option('amit_demo_plugin_options');
         $selected_options = $options['multi_checkbox_field'] ?? [];
         ?>
         <input type="checkbox" name="amit_demo_plugin_options[multi_checkbox_field][]" value="option1" <?php checked(in_array('option1', $selected_options)); ?> /> <?php esc_html_e('Option 1', 'amit_demo_plugin'); ?><br>
         <input type="checkbox" name="amit_demo_plugin_options[multi_checkbox_field][]" value="option2" <?php checked(in_array('option2', $selected_options)); ?> /> <?php esc_html_e('Option 2', 'amit_demo_plugin'); ?><br>
         <input type="checkbox" name="amit_demo_plugin_options[multi_checkbox_field][]" value="option3" <?php checked(in_array('option3', $selected_options)); ?> /> <?php esc_html_e('Option 3', 'amit_demo_plugin'); ?><br>
         <?php
     }

     /**
      * Sanitize the options before saving
      */
     public function sanitize_options($options)
     {
         // Example sanitization logic
         if (isset($options['text_field'])) {
             $options['text_field'] = sanitize_text_field($options['text_field']);
         }
         if (isset($options['checkbox_field'])) {
             $options['checkbox_field'] = (int) $options['checkbox_field'];
         }
         
         if (isset($_FILES['amit_demo_plugin_options']) && !empty($_FILES['amit_demo_plugin_options']['name']['file_upload'])) {
            // Process the new file upload
            $file = array(
                'name'     => $_FILES['amit_demo_plugin_options']['name']['file_upload'],
                'type'     => $_FILES['amit_demo_plugin_options']['type']['file_upload'],
                'tmp_name' => $_FILES['amit_demo_plugin_options']['tmp_name']['file_upload'],
                'error'    => $_FILES['amit_demo_plugin_options']['error']['file_upload'],
                'size'     => $_FILES['amit_demo_plugin_options']['size']['file_upload']
            );
            
            if ($file['name']) {
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                
                $upload_overrides = array(
                    'test_form' => false
                );
    
                // Upload the new file
                $movefile = wp_handle_upload($file, $upload_overrides);
    
                if ($movefile && !isset($movefile['error'])) {
                    // If a file is uploaded successfully, save the new file URL
                    $options['file_upload'] = $movefile['url'];
                }
            }
        } else {
            // If no new file is uploaded, retain the old file if available
            if (isset($options['existing_file']) && !empty($options['existing_file'])) {
                $options['file_upload'] = $options['existing_file'];
            } else {
                // If no file is uploaded and there's no existing file, reset the file upload value
                $options['file_upload'] = '';
            }
        }
    
         if (isset($options['textarea_field'])) {
             $options['textarea_field'] = sanitize_textarea_field($options['textarea_field']);
         }
         if (isset($options['select_field'])) {
             $options['select_field'] = sanitize_text_field($options['select_field']);
         }
         if (isset($options['radio_field'])) {
             $options['radio_field'] = sanitize_text_field($options['radio_field']);
         }
         if (isset($options['multi_checkbox_field'])) {
             $options['multi_checkbox_field'] = array_map('sanitize_text_field', $options['multi_checkbox_field']);
         }
         
         return $options;
     }
 }
