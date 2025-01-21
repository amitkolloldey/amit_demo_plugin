<?php
// In the list page: guest-directory-list.php
$search = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';
$page = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
$guests_per_page = 10;
$guests = (new Amit_demo_plugin_Guest_Directory())->get_guests($search, $page, $guests_per_page);
$total_guests = (new Amit_demo_plugin_Guest_Directory())->get_total_guests($search);
$total_pages = ceil($total_guests / $guests_per_page);

// Handle bulk delete action
if (isset($_POST['bulk_action']) && $_POST['bulk_action'] === 'delete' && isset($_POST['guest_ids'])) {
    $guest_ids = $_POST['guest_ids'];
    (new Amit_demo_plugin_Guest_Directory())->bulk_delete_guests($guest_ids);
    echo '<script>window.location.href = "' . esc_url(admin_url('admin.php?page=event-guest-directory&deleted=true')) . '";</script>';
    exit;
}
?>
<div class="wrap">
    <h1>Event Guest Directory <a href="?page=event-guest-directory&action=edit"
            class="page-title-action">Add New</a></h1>

    <!-- Display Total Number of Guests -->
    <p><strong>Total Guests:</strong> <?php echo esc_html($total_guests); ?></p>

    <!-- Search Form -->
    <form method="get"
        action="">
        <input type="hidden"
            name="page"
            value="event-guest-directory">
        <input type="text"
            name="search"
            value="<?php echo esc_attr($search); ?>"
            placeholder="Search by Name"
            class="regular-text">
        <input type="submit"
            value="Search"
            class="button">
    </form>

    <!-- Bulk Action Form -->
    <form method="post"
        action="">
        <select name="bulk_action">
            <option value="">Bulk Actions</option>
            <option value="delete">Delete</option>
        </select>
        <input type="submit"
            value="Apply"
            class="button action">
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><input type="checkbox"
                            id="select_all"></th>
                    <th>Name</th>
                    <th>Avatar</th>
                    <th>Age</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Created At</th> <!-- Added this column -->
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($guests)): ?>
                    <tr>
                        <td colspan="8"
                            style="text-align: center;">No results found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($guests as $guest): ?>
                        <tr>
                            <td><input type="checkbox"
                                    name="guest_ids[]"
                                    value="<?php echo $guest->id; ?>"></td>
                            <td><?php echo esc_html($guest->name); ?></td>
                            <td><img src="<?php echo esc_url($guest->avatar); ?>"
                                    alt="Avatar"
                                    width="50"></td>
                            <td><?php echo esc_html($guest->age); ?></td>
                            <td><?php echo esc_html($guest->type); ?></td>
                            <td><?php echo $guest->status ? 'Active' : 'Inactive'; ?></td>
                            <td><?php echo esc_html(date('Y-m-d H:i', strtotime($guest->created_at))); ?></td>
                            <td>
                                <a href="?page=event-guest-directory&action=edit&id=<?php echo $guest->id; ?>">Edit</a> |
                                <a href="?page=event-guest-directory&action=delete&id=<?php echo $guest->id; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>

        </table>
    </form>

    <!-- Pagination -->
    <div class="tablenav">
        <div class="alignleft actions bulkactions">
            <?php if ($total_pages > 1): ?>
                <span class="pagination-links">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=event-guest-directory&paged=<?php echo $i; ?>&search=<?php echo esc_attr($search); ?>"
                            class="<?php echo $i === $page ? 'current' : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Select all checkboxes functionality
    document.getElementById('select_all').addEventListener('click', function () {
        var checkboxes = document.querySelectorAll('input[name="guest_ids[]"]');
        checkboxes.forEach(function (checkbox) {
            checkbox.checked = document.getElementById('select_all').checked; // Use the select all checkbox state
        });
    });
</script>