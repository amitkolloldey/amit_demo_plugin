<?php

class Amit_demo_plugin_Guest_Directory_Shortcode
{

    public static function render_guest_directory()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'event_guest_directory';
        $guests = $wpdb->get_results("SELECT * FROM $table_name");

        ob_start();
        ?>
        <div class="guest-directory">
            <?php foreach ($guests as $guest): ?>
                <div class="guest">
                    <img src="<?php echo esc_url($guest->avatar); ?>"
                        alt="<?php echo esc_attr($guest->name); ?>">
                    <h3><?php echo esc_html($guest->name); ?></h3>
                    <p><?php echo esc_html($guest->bio); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}
 
