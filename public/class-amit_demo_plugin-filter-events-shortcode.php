<?php

/**
 * Handles the Events Filter Shortcode and AJAX functionality.
 *
 * @package    Amit_demo_plugin
 * @subpackage Amit_demo_plugin/public
 */
class Amit_demo_plugin_Filter_Events_Shortcode
{
    /**
     * Render the filter events form shortcode.
     *
     * @return string
     */
    public function render_filter_events_shortcode()
    {
        // Get terms for country and organizers.
        $countries = get_terms(['taxonomy' => 'countries', 'hide_empty' => false]);
        $organizers = get_terms(['taxonomy' => 'event_organizers', 'hide_empty' => false]);

        // Query to get all events by default.
        $args = [
            'post_type' => 'events',
            'posts_per_page' => -1, // Retrieve all events.
        ];
        $all_events = new WP_Query($args);

        ob_start(); ?>

        <!-- Filter form -->
        <form id="events-filter-form">
            <label for="filter-country"><?php esc_html_e('Country:', 'amit_demo_plugin'); ?></label>
            <select id="filter-country"
                name="country">
                <option value=""><?php esc_html_e('Select Country', 'amit_demo_plugin'); ?></option>
                <?php foreach ($countries as $country): ?>
                    <option value="<?php echo esc_attr($country->slug); ?>"><?php echo esc_html($country->name); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="filter-organizer"><?php esc_html_e('Organizer:', 'amit_demo_plugin'); ?></label>
            <select id="filter-organizer"
                name="organizer">
                <option value=""><?php esc_html_e('Select Organizer', 'amit_demo_plugin'); ?></option>
                <?php foreach ($organizers as $organizer): ?>
                    <option value="<?php echo esc_attr($organizer->slug); ?>"><?php echo esc_html($organizer->name); ?></option>
                <?php endforeach; ?>
            </select>

            <button type="button"
                id="filter-events-button"><?php esc_html_e('Filter Events', 'amit_demo_plugin'); ?></button>
        </form>

        <div id="events-filter-results">
            <?php
            if ($all_events->have_posts()) {
                while ($all_events->have_posts()) {
                    $all_events->the_post();

                    // Get meta values
                    $start_date = get_post_meta(get_the_ID(), '_start_date', true);
                    $end_date = get_post_meta(get_the_ID(), '_end_date', true);
                    $schedule_pdf = get_post_meta(get_the_ID(), '_schedule_pdf', true);
                    $additional_details = get_post_meta(get_the_ID(), '_additional_details', true);
                    $is_featured = get_post_meta(get_the_ID(), '_is_featured', true);
                    $is_upcoming = get_post_meta(get_the_ID(), '_is_upcoming', true);
                    $is_past = get_post_meta(get_the_ID(), '_is_past', true);
                    $status = get_post_meta(get_the_ID(), '_status', true);
                    ?>

                    <div class="event-item">
                        <h3><?php the_title(); ?></h3>
                        <p><?php the_excerpt(); ?></p>

                        <!-- Displaying the meta values -->
                        <ul class="event-meta">
                            <li><strong><?php esc_html_e('Start Date:', 'amit_demo_plugin'); ?></strong>
                                <?php echo esc_html($start_date); ?></li>
                            <li><strong><?php esc_html_e('End Date:', 'amit_demo_plugin'); ?></strong>
                                <?php echo esc_html($end_date); ?></li>
                            <li><strong><?php esc_html_e('Schedule PDF:', 'amit_demo_plugin'); ?></strong>
                                <?php echo esc_html($schedule_pdf); ?></li>
                            <li><strong><?php esc_html_e('Additional Details:', 'amit_demo_plugin'); ?></strong>
                                <?php echo esc_html($additional_details); ?></li>
                            <li><strong><?php esc_html_e('Featured:', 'amit_demo_plugin'); ?></strong>
                                <?php echo esc_html($is_featured ? 'Yes' : 'No'); ?></li>
                            <li><strong><?php esc_html_e('Upcoming:', 'amit_demo_plugin'); ?></strong>
                                <?php echo esc_html($is_upcoming ? 'Yes' : 'No'); ?></li>
                            <li><strong><?php esc_html_e('Past:', 'amit_demo_plugin'); ?></strong>
                                <?php echo esc_html($is_past ? 'Yes' : 'No'); ?></li>
                            <li><strong><?php esc_html_e('Status:', 'amit_demo_plugin'); ?></strong> <?php echo esc_html($status); ?>
                            </li>
                        </ul>
                    </div>

                    <?php
                }
            } else {
                echo '<p>' . esc_html__('No events found.', 'amit_demo_plugin') . '</p>';
            }
            ?>
        </div>

        <?php return ob_get_clean();
    }


    /**
     * Handle the AJAX request for filtering events.
     */
    public function filter_events_ajax()
    {
        $country = sanitize_text_field($_POST['country'] ?? '');
        $organizer = sanitize_text_field($_POST['organizer'] ?? '');

        // Build the query arguments.
        $args = [
            'post_type' => 'events',
            'posts_per_page' => -1,
            'tax_query' => [
                'relation' => 'AND',
            ],
        ];

        if ($country) {
            $args['tax_query'][] = [
                'taxonomy' => 'countries',
                'field' => 'slug',
                'terms' => $country,
            ];
        }

        if ($organizer) {
            $args['tax_query'][] = [
                'taxonomy' => 'event_organizers',
                'field' => 'slug',
                'terms' => $organizer,
            ];
        }

        // Query the events.
        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                echo '<div class="event-item">';
                echo '<h3>' . get_the_title() . '</h3>';
                echo '<p>' . get_the_excerpt() . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>' . esc_html__('No events found.', 'amit_demo_plugin') . '</p>';
        }

        wp_die(); // End AJAX request.
    }
}
