<?php
$guest = isset($_GET['id']) ? (new Amit_demo_plugin_Guest_Directory())->get_guest($_GET['id']) : null;
?>
<div class="wrap">
    <h1><?php echo $guest ? 'Edit Guest' : 'Add New Guest'; ?></h1>
    <form method="post" action="<?php echo admin_url('admin.php?page=event-guest-directory&action=save'); ?>" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $guest ? esc_attr($guest->id) : ''; ?>">
        <table class="form-table">
            <tr>
                <th><label for="name">Name</label></th>
                <td><input type="text" name="name" id="name" value="<?php echo $guest ? esc_attr($guest->name) : ''; ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="bio">Bio</label></th>
                <td><textarea name="bio" id="bio" class="regular-text"><?php echo $guest ? esc_textarea($guest->bio) : ''; ?></textarea></td>
            </tr>
            <tr>
                <th><label for="age">Age</label></th>
                <td><input type="number" name="age" id="age" value="<?php echo $guest ? esc_attr($guest->age) : ''; ?>" class="small-text"></td>
            </tr>
            <tr>
                <th><label for="type">Type</label></th>
                <td><input type="text" name="type" id="type" value="<?php echo $guest ? esc_attr($guest->type) : ''; ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="status">Status</label></th>
                <td>
                    <select name="status" id="status">
                        <option value="1" <?php selected($guest ? $guest->status : '', 1); ?>>Active</option>
                        <option value="0" <?php selected($guest ? $guest->status : '', 0); ?>>Inactive</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="avatar">Avatar</label></th>
                <td>
                    <input type="file" name="avatar" id="avatar">
                    <?php if ($guest && $guest->avatar): ?>
                        <p>Current Avatar: <img src="<?php echo esc_url($guest->avatar); ?>" alt="Avatar" style="max-width: 100px;"></p>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th><label for="zone">Zone</label></th>
                <td><input type="text" name="zone" id="zone" value="<?php echo $guest ? esc_attr($guest->zone) : ''; ?>" class="regular-text"></td>
            </tr>
        </table>
        <?php submit_button($guest ? 'Update Guest' : 'Add Guest'); ?>
    </form>
</div>
