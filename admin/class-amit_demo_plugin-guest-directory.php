<?php
class Amit_demo_plugin_Guest_Directory
{
    private $table_name;

    public function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'event_guest_directory';
    }

    public function add_admin_menu()
    {
        add_menu_page(
            'Guest Directory',
            'Guest Directory',
            'manage_options',
            'event-guest-directory',
            [$this, 'render_admin_page'],
            'dashicons-groups',
            20
        );
    }

    public function render_admin_page()
    {
        if (isset($_GET['action']) && $_GET['action'] === 'edit') {
            include plugin_dir_path(__FILE__) . '/partials/guest-directory-edit.php';
        } elseif (isset($_GET['action']) && $_GET['action'] === 'save') {
            $this->handle_form_submission();
        } elseif (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
            $this->handle_delete();
        } else {
            include plugin_dir_path(__FILE__) . '/partials/guest-directory-list.php';
        }
    }

    private function handle_form_submission()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $id = isset($_POST['id']) ? intval($_POST['id']) : null;
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $bio = isset($_POST['bio']) ? sanitize_textarea_field($_POST['bio']) : '';
        $age = isset($_POST['age']) ? intval($_POST['age']) : 0;
        $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';
        $status = isset($_POST['status']) ? intval($_POST['status']) : 0;
        $zone = isset($_POST['zone']) ? sanitize_text_field($_POST['zone']) : '';

        // Handle avatar upload
        $avatar = null;
        if (!empty($_FILES['avatar']['name'])) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            $uploaded_file = wp_handle_upload($_FILES['avatar'], ['test_form' => false]);

            if (isset($uploaded_file['url'])) {
                $avatar = esc_url_raw($uploaded_file['url']);
            }
        }

        // Prepare data for insert/update
        $data = [
            'name' => $name,
            'bio' => $bio,
            'age' => $age,
            'type' => $type,
            'status' => $status,
            'zone' => $zone,
        ];

        if ($avatar) {
            $data['avatar'] = $avatar;
        }

        if ($id) {
            $this->update_guest($id, $data);
            $redirect_url = admin_url('admin.php?page=event-guest-directory&updated=true');
        } else {
            $this->create_guest($data);
            $redirect_url = admin_url('admin.php?page=event-guest-directory&created=true');
        }

        // Output JavaScript redirect
        echo '<script>window.location.href = "' . esc_js($redirect_url) . '";</script>';
        exit;
    }

    private function handle_delete()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($id) {
            $this->delete_guest($id);
            $redirect_url = admin_url('admin.php?page=event-guest-directory&deleted=true');
        } else {
            $redirect_url = admin_url('admin.php?page=event-guest-directory&error=true');
        }

        // Output JavaScript redirect after delete
        echo '<script>window.location.href = "' . esc_js($redirect_url) . '";</script>';
        exit;
    }

    public function create_guest($data)
    {
        global $wpdb;

        // Add created_at field with the current timestamp
        $data['created_at'] = current_time('mysql');

        $wpdb->insert($this->table_name, $data);
    }

    public function update_guest($id, $data)
    {
        global $wpdb;
        $wpdb->update($this->table_name, $data, ['id' => $id]);
    }

    public function delete_guest($id)
    {
        global $wpdb;
        $wpdb->delete($this->table_name, ['id' => $id]);
    }

    public function bulk_delete_guests($guest_ids)
    {
        global $wpdb;
        if (is_array($guest_ids)) {
            $placeholders = implode(',', array_fill(0, count($guest_ids), '%d'));
            $query = $wpdb->prepare("DELETE FROM {$this->table_name} WHERE id IN ($placeholders)", ...$guest_ids);
            $wpdb->query($query);
        }
    }

    public function get_guests($search = '', $page = 1, $per_page = 10)
    {
        global $wpdb;

        // Build the query for filtering
        $query = "SELECT * FROM {$this->table_name}";

        if (!empty($search)) {
            $query .= $wpdb->prepare(" WHERE name LIKE %s", '%' . $wpdb->esc_like($search) . '%');
        }

        // Add ORDER BY to sort by created_at in descending order
        $query .= " ORDER BY created_at DESC";

        // Pagination logic
        $offset = ($page - 1) * $per_page;
        $query .= " LIMIT {$offset}, {$per_page}";

        return $wpdb->get_results($query);
    }

    public function get_guest($id)
    {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->table_name} WHERE id = %d", $id));
    }

    public function get_total_guests($search = '')
    {
        global $wpdb;

        $query = "SELECT COUNT(*) FROM {$this->table_name}";
        if (!empty($search)) {
            $query .= $wpdb->prepare(" WHERE name LIKE %s", '%' . $wpdb->esc_like($search) . '%');
        }

        return $wpdb->get_var($query);
    }
}
?>