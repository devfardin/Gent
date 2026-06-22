<?php
if (!defined('ABSPATH')) exit;

$option_name = 'gent_general';

if (isset($_POST['gent_general_nonce']) && wp_verify_nonce($_POST['gent_general_nonce'], 'gent_save_general')) {
    $saved = get_option($option_name, []);

    // Sync site name & tagline directly into WordPress core options
    $site_name = sanitize_text_field($_POST['company_name'] ?? '');
    $tagline   = sanitize_text_field($_POST['tagline'] ?? '');
    update_option('blogname', $site_name);
    update_option('blogdescription', $tagline);

    $fields = ['company_name', 'tagline', 'company_email', 'phone_number', 'whatsapp_number',
               'address', 'facebook_url', 'instagram_url', 'tiktok_url', 'youtube_url'];
    $data = [];
    foreach ($fields as $field) {
        $data[$field] = sanitize_text_field($_POST[$field] ?? '');
    }

    // Handle logo & favicon uploads — also sync logo with WP custom logo
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    foreach (['logo', 'favicon'] as $upload) {
        if (!empty($_FILES[$upload]['name'])) {
            $id = media_handle_upload($upload, 0);
            if (!is_wp_error($id)) {
                $data[$upload] = $id;
                // Sync logo with WordPress custom logo theme mod
                if ($upload === 'logo') {
                    set_theme_mod('custom_logo', $id);
                }
                // Sync favicon with WordPress site icon
                if ($upload === 'favicon') {
                    update_option('site_icon', $id);
                }
            } else {
                $data[$upload] = $saved[$upload] ?? '';
            }
        } else {
            $data[$upload] = $saved[$upload] ?? '';
        }
    }

    update_option($option_name, $data);
    $saved_notice = true;
}

// Pre-fill from WordPress core options if gent option is empty
$opts = get_option($option_name, []);
if (empty($opts['company_name'])) $opts['company_name'] = get_option('blogname', '');
if (empty($opts['tagline']))      $opts['tagline']      = get_option('blogdescription', '');
if (empty($opts['logo']))         $opts['logo']         = get_theme_mod('custom_logo', '');
if (empty($opts['favicon']))      $opts['favicon']      = get_option('site_icon', '');

$v = fn(string $key) => esc_attr($opts[$key] ?? '');
?>
<div class="gent-settings-wrap">
    <h1><?php _e('General Settings', 'gent'); ?></h1>

    <?php if (!empty($saved_notice)) : ?>
        <div class="gent-notice success"><?php _e('✓ Settings saved successfully.', 'gent'); ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <?php wp_nonce_field('gent_save_general', 'gent_general_nonce'); ?>

        <!-- Site Identity -->
        <div class="gent-card">
            <p class="gent-card-title"><?php _e('Site Identity', 'gent'); ?></p>
            <div class="gent-form-grid">
                <div class="gent-field">
                    <label><?php _e('Site Name', 'gent'); ?></label>
                    <input type="text" name="company_name" value="<?= $v('company_name') ?>" placeholder="<?php _e('Your site name', 'gent'); ?>">
                    <span class="gent-hint"><?php _e('Site Title.', 'gent'); ?></span>
                </div>
                <div class="gent-field">
                    <label><?php _e('Tagline', 'gent'); ?></label>
                    <input type="text" name="tagline" value="<?= $v('tagline') ?>" placeholder="<?php _e('Just another WordPress site', 'gent'); ?>">
                    <span class="gent-hint"><?php _e('Tagline.', 'gent'); ?></span>
                </div>
            </div>
        </div>

        <!-- Company Info -->
        <div class="gent-card">
            <p class="gent-card-title"><?php _e('Company Information', 'gent'); ?></p>
            <div class="gent-form-grid">
                <div class="gent-field">
                    <label><?php _e('Company Email', 'gent'); ?></label>
                    <input type="email" name="company_email" value="<?= $v('company_email') ?>" placeholder="info@example.com">
                </div>
                <div class="gent-field">
                    <label><?php _e('Phone Number', 'gent'); ?></label>
                    <input type="text" name="phone_number" value="<?= $v('phone_number') ?>" placeholder="+1 000 000 0000">
                </div>
                <div class="gent-field" style="grid-column:1/-1">
                    <label><?php _e('Address', 'gent'); ?></label>
                    <textarea name="address" rows="3" placeholder="123 Main St, City, Country"><?= esc_textarea($opts['address'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Social Media -->
        <div class="gent-card">
            <p class="gent-card-title"><?php _e('Social Media', 'gent'); ?></p>
            <div class="gent-form-grid">
                <div class="gent-field">
                    <label><?php _e('Facebook URL', 'gent'); ?></label>
                    <input type="url" name="facebook_url" value="<?= $v('facebook_url') ?>" placeholder="https://facebook.com/...">
                </div>
                <div class="gent-field">
                    <label><?php _e('Instagram URL', 'gent'); ?></label>
                    <input type="url" name="instagram_url" value="<?= $v('instagram_url') ?>" placeholder="https://instagram.com/...">
                </div>
                <div class="gent-field">
                    <label><?php _e('TikTok URL', 'gent'); ?></label>
                    <input type="url" name="tiktok_url" value="<?= $v('tiktok_url') ?>" placeholder="https://tiktok.com/...">
                </div>
                <div class="gent-field">
                    <label><?php _e('YouTube URL', 'gent'); ?></label>
                    <input type="url" name="youtube_url" value="<?= $v('youtube_url') ?>" placeholder="https://youtube.com/...">
                </div>
            </div>
        </div>

        <!-- Branding -->
        <div class="gent-card">
            <p class="gent-card-title"><?php _e('Branding', 'gent'); ?></p>
            <div class="gent-form-grid">
                <div class="gent-field">
                    <label><?php _e('Logo', 'gent'); ?></label>
                    <div class="gent-upload-preview">
                        <?php if (!empty($opts['logo'])) : ?>
                            <img src="<?= esc_url(wp_get_attachment_url($opts['logo'])) ?>" style="max-height:52px;max-width:120px">
                        <?php endif; ?>
                        <input type="file" name="logo" accept="image/*">
                    </div>
                    <span class="gent-hint"><?php _e('Syncs with WordPress custom logo & Customizer.', 'gent'); ?></span>
                </div>
                <div class="gent-field">
                    <label><?php _e('Favicon', 'gent'); ?></label>
                    <div class="gent-upload-preview">
                        <?php if (!empty($opts['favicon'])) : ?>
                            <img src="<?= esc_url(wp_get_attachment_url($opts['favicon'])) ?>" style="max-height:32px;max-width:32px">
                        <?php endif; ?>
                        <input type="file" name="favicon" accept="image/x-icon,image/png,image/*">
                    </div>
                    <span class="gent-hint"><?php _e('Syncs with WordPress › Appearance › Customize › Site Icon.', 'gent'); ?></span>
                </div>
            </div>
        </div>

        <div class="gent-submit-row">
            <?php submit_button(__('Save Settings', 'gent'), 'primary', 'submit', false); ?>
        </div>

    </form>
</div>
