<?php
/**
 * Register the "Events" custom post type.
 *
 * @since      1.0.0
 * @package    Amit_demo_plugin
 * @subpackage Amit_demo_plugin/admin
 */

class Amit_demo_plugin_Post_Type_And_Taxonomies_Events
{

    /**
     * Register the "Events" custom post type.
     *
     * @since 1.0.0
     */
    public function register_events_cpt()
    {
        $labels = [
            'name' => __('Events', 'amit_demo_plugin'),
            'singular_name' => __('Event', 'amit_demo_plugin'),
            'menu_name' => __('Events', 'amit_demo_plugin'),
            'name_admin_bar' => __('Event', 'amit_demo_plugin'),
            'add_new' => __('Add New', 'amit_demo_plugin'),
            'add_new_item' => __('Add New Event', 'amit_demo_plugin'),
            'new_item' => __('New Event', 'amit_demo_plugin'),
            'edit_item' => __('Edit Event', 'amit_demo_plugin'),
            'view_item' => __('View Event', 'amit_demo_plugin'),
            'all_items' => __('All Events', 'amit_demo_plugin'),
            'search_items' => __('Search Events', 'amit_demo_plugin'),
            'parent_item_colon' => __('Parent Events:', 'amit_demo_plugin'),
            'not_found' => __('No events found.', 'amit_demo_plugin'),
            'not_found_in_trash' => __('No events found in Trash.', 'amit_demo_plugin'),
        ];

        $args = [
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'events'],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 20,
            'show_in_rest' => true, // Required for the block editor
            'menu_icon' => 'dashicons-calendar',
            'supports' => ['title', 'editor', 'thumbnail'],
        ];

        register_post_type('events', $args);
    }

    /**
     * Register custom taxonomies for the "Events" CPT.
     */
    public function register_event_taxonomies()
    {
        // Register Event Organizers taxonomy
        $organizers_labels = [
            'name' => __('Event Organizers', 'amit_demo_plugin'),
            'singular_name' => __('Event Organizer', 'amit_demo_plugin'),
            'search_items' => __('Search Organizers', 'amit_demo_plugin'),
            'all_items' => __('All Organizers', 'amit_demo_plugin'),
            'edit_item' => __('Edit Organizer', 'amit_demo_plugin'),
            'update_item' => __('Update Organizer', 'amit_demo_plugin'),
            'add_new_item' => __('Add New Organizer', 'amit_demo_plugin'),
            'new_item_name' => __('New Organizer Name', 'amit_demo_plugin'),
            'menu_name' => __('Organizers', 'amit_demo_plugin'),
        ];

        $organizers_args = [
            'hierarchical' => true, // Set to false for a non-hierarchical taxonomy
            'labels' => $organizers_labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'event-organizers'],
            'show_in_rest' => true, // Required for the block editor
        ];

        register_taxonomy('event_organizers', ['events'], $organizers_args);

        // Register Country taxonomy
        $country_labels = [
            'name' => __('Countries', 'amit_demo_plugin'),
            'singular_name' => __('Country', 'amit_demo_plugin'),
            'search_items' => __('Search Countries', 'amit_demo_plugin'),
            'all_items' => __('All Countries', 'amit_demo_plugin'),
            'edit_item' => __('Edit Country', 'amit_demo_plugin'),
            'update_item' => __('Update Country', 'amit_demo_plugin'),
            'add_new_item' => __('Add New Country', 'amit_demo_plugin'),
            'new_item_name' => __('New Country Name', 'amit_demo_plugin'),
            'menu_name' => __('Countries', 'amit_demo_plugin'),
        ];

        $country_args = [
            'hierarchical' => true, // Set to true for a hierarchical taxonomy
            'labels' => $country_labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'countries'],
            'show_in_rest' => true, // Required for the block editor
        ];

        register_taxonomy('countries', ['events'], $country_args);
    }

    /**
     * Add meta boxes for the "Events" CPT.
     */
    public function add_event_meta_boxes()
    {
        add_meta_box(
            'event_details_meta_box', // Meta box ID
            __('Event Details', 'amit_demo_plugin'), // Title
            [$this, 'render_event_meta_box'], // Callback function
            'events', // Post type
            'normal', // Context
            'high' // Priority
        );
    }

    /**
     * Render the event meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_event_meta_box($post)
    {
        // Add a nonce field for security
        wp_nonce_field('save_event_meta_fields', 'event_meta_nonce');

        // Retrieve existing values
        $start_date = get_post_meta($post->ID, '_start_date', true);
        $end_date = get_post_meta($post->ID, '_end_date', true);
        $schedule_pdf = get_post_meta($post->ID, '_schedule_pdf', true);
        $additional_details = get_post_meta($post->ID, '_additional_details', true);
        $is_featured = get_post_meta($post->ID, '_is_featured', true);
        $is_upcoming = get_post_meta($post->ID, '_is_upcoming', true);
        $is_past = get_post_meta($post->ID, '_is_past', true);
        $status = get_post_meta($post->ID, '_status', true);

        // Meta box form
        echo '<p><label for="start_date">' . __('Start Date:', 'amit_demo_plugin') . '</label>';
        echo '<input type="date" id="start_date" name="start_date" value="' . esc_attr($start_date) . '" /></p>';

        echo '<p><label for="end_date">' . __('End Date:', 'amit_demo_plugin') . '</label>';
        echo '<input type="date" id="end_date" name="end_date" value="' . esc_attr($end_date) . '" /></p>';

        echo '<p><label for="schedule_pdf">' . __('Schedule PDF:', 'amit_demo_plugin') . '</label>';
        echo '<input type="file" id="schedule_pdf" name="schedule_pdf" /></p>';
        if ($schedule_pdf) {
            echo '<p>' . __('Uploaded File:', 'amit_demo_plugin') . ' <a href="' . esc_url($schedule_pdf) . '" target="_blank">' . esc_html(basename($schedule_pdf)) . '</a></p>';
        }

        echo '<p><label for="additional_details">' . __('Additional Details:', 'amit_demo_plugin') . '</label>';
        echo '<textarea id="additional_details" name="additional_details" rows="5" style="width: 100%;">' . esc_textarea($additional_details) . '</textarea></p>';

        echo '<p><label><input type="checkbox" name="is_featured" value="1"' . checked($is_featured, '1', false) . '/> ' . __('Featured', 'amit_demo_plugin') . '</label></p>';
        echo '<p><label><input type="checkbox" name="is_upcoming" value="1"' . checked($is_upcoming, '1', false) . '/> ' . __('Upcoming', 'amit_demo_plugin') . '</label></p>';
        echo '<p><label><input type="checkbox" name="is_past" value="1"' . checked($is_past, '1', false) . '/> ' . __('Past', 'amit_demo_plugin') . '</label></p>';

        echo '<p><label><input type="radio" name="status" value="active"' . checked($status, 'active', false) . '/> ' . __('Active', 'amit_demo_plugin') . '</label>';
        echo '<label><input type="radio" name="status" value="inactive"' . checked($status, 'inactive', false) . '/> ' . __('Inactive', 'amit_demo_plugin') . '</label></p>';
    }

    /**
     * Save the meta fields when the post is saved.
     *
     * @param int $post_id The ID of the current post.
     */
    public function save_event_meta_fields($post_id)
    {
        // Verify nonce
        if (!isset($_POST['event_meta_nonce']) || !wp_verify_nonce($_POST['event_meta_nonce'], 'save_event_meta_fields')) {
            return;
        }

        // Check for autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save start date
        if (isset($_POST['start_date'])) {
            update_post_meta($post_id, '_start_date', sanitize_text_field($_POST['start_date']));
        }

        // Save end date
        if (isset($_POST['end_date'])) {
            update_post_meta($post_id, '_end_date', sanitize_text_field($_POST['end_date']));
        }

        // Save schedule PDF
        if (!empty($_FILES['schedule_pdf']['name'])) {
            $uploaded = media_handle_upload('schedule_pdf', $post_id);
            if (!is_wp_error($uploaded)) {
                update_post_meta($post_id, '_schedule_pdf', wp_get_attachment_url($uploaded));
            }
        }

        // Save additional details
        if (isset($_POST['additional_details'])) {
            update_post_meta($post_id, '_additional_details', sanitize_textarea_field($_POST['additional_details']));
        }

        // Save checkboxes
        update_post_meta($post_id, '_is_featured', isset($_POST['is_featured']) ? '1' : '0');
        update_post_meta($post_id, '_is_upcoming', isset($_POST['is_upcoming']) ? '1' : '0');
        update_post_meta($post_id, '_is_past', isset($_POST['is_past']) ? '1' : '0');

        // Save radio button
        if (isset($_POST['status'])) {
            update_post_meta($post_id, '_status', sanitize_text_field($_POST['status']));
        }
    }
}


