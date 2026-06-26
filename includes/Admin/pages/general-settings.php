<?php
if (!defined('ABSPATH'))
    exit;

$option_name = 'gent_general';

if (isset($_POST['gent_general_nonce']) && wp_verify_nonce($_POST['gent_general_nonce'], 'gent_save_general')) {
    $saved = get_option($option_name, []);

    // Sync site name & tagline directly into WordPress core options
    $site_name = sanitize_text_field($_POST['company_name'] ?? '');
    $tagline = sanitize_text_field($_POST['tagline'] ?? '');
    update_option('blogname', $site_name);
    update_option('blogdescription', $tagline);

    $fields = [
        'company_name', 'tagline', 'company_email', 'phone_number', 'whatsapp_number',
        'address', 'facebook_url', 'instagram_url', 'tiktok_url', 'youtube_url',
        'footer_description', 'copyright_text', 'copyright_tagline', 'moto',
    ];
    $data = [];
    foreach ($fields as $field) {
        $data[$field] = sanitize_text_field($_POST[$field] ?? '');
    }

    // Handle logo & favicon uploads — also sync logo with WP custom logo
    foreach (['logo', 'favicon', 'footer_logo'] as $upload) {
        $id = absint($_POST[$upload . '_id'] ?? 0);
        if ($id) {
            $data[$upload] = $id;
            if ($upload === 'logo')    set_theme_mod('custom_logo', $id);
            if ($upload === 'favicon') update_option('site_icon', $id);
        } else {
            $data[$upload] = $saved[$upload] ?? '';
        }
    }

    update_option($option_name, $data);
    $saved_notice = true;
}

// Pre-fill from WordPress core options if gent option is empty
$opts = get_option($option_name, []);
if (empty($opts['company_name']))
    $opts['company_name'] = get_option('blogname', '');
if (empty($opts['tagline']))
    $opts['tagline'] = get_option('blogdescription', '');
if (empty($opts['logo']))
    $opts['logo'] = get_theme_mod('custom_logo', '');
if (empty($opts['favicon']))
    $opts['favicon'] = get_option('site_icon', '');

$v = fn(string $key) => esc_attr($opts[$key] ?? '');

function gent_media_picker(string $name, $attachment_id, string $hint = ''): void {
    $url = $attachment_id ? wp_get_attachment_image_url($attachment_id, 'medium') : '';
    ?>
    <div class="gent-media-wrap">
        <input type="hidden" name="<?= esc_attr($name) ?>_id" class="gent-media-id" value="<?= esc_attr($attachment_id) ?>">
        <img class="gent-media-preview" src="<?= esc_url($url) ?>" <?= $url ? '' : 'style="display:none"' ?>>
        <div class="gent-media-actions">
            <button type="button" class="button gent-media-btn"><?= $url ? __('Change Image', 'gent') : __('Select Image', 'gent') ?></button>
            <button type="button" class="gent-media-remove" <?= $url ? '' : 'style="display:none"' ?>>✕ <?php _e('Remove', 'gent'); ?></button>
        </div>
        <?php if ($hint) : ?><span class="gent-hint"><?= esc_html($hint) ?></span><?php endif; ?>
    </div>
    <?php
}
?>
<div class="gent-settings-wrap">
    <h1><?php _e('General Settings', 'gent'); ?></h1>

    <?php if (!empty($saved_notice)): ?>
        <div class="gent-notice success notice is-dismissible"><?php _e('', 'gent'); ?>
            ✓ Settings saved successfully.
        </div>
    <?php endif; ?>

    <form method="post">
        <?php wp_nonce_field('gent_save_general', 'gent_general_nonce'); ?>

        <!-- Site Identity -->
        <div class="gent-card">
            <p class="gent-card-title"><?php _e('Site Identity', 'gent'); ?></p>
            <div class="gent-form-grid">
                <div class="gent-field">
                    <label><?php _e('Site Name', 'gent'); ?></label>
                    <input type="text" name="company_name" value="<?= $v('company_name') ?>"
                        placeholder="<?php _e('Your site name', 'gent'); ?>">
                    <span class="gent-hint"><?php _e('Site Title.', 'gent'); ?></span>
                </div>
                <div class="gent-field">
                    <label><?php _e('Tagline', 'gent'); ?></label>
                    <input type="text" name="tagline" value="<?= $v('tagline') ?>"
                        placeholder="<?php _e('Just another WordPress site', 'gent'); ?>">
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
                    <input type="email" name="company_email" value="<?= $v('company_email') ?>"
                        placeholder="info@example.com">
                </div>
                <div class="gent-field">
                    <label><?php _e('Phone Number', 'gent'); ?></label>
                    <input type="text" name="phone_number" value="<?= $v('phone_number') ?>"
                        placeholder="+1 000 000 0000">
                </div>
                <div class="gent-field" style="grid-column:1/-1">
                    <label><?php _e('Address', 'gent'); ?></label>
                    <textarea name="address" rows="3"
                        placeholder="123 Main St, City, Country"><?= esc_textarea($opts['address'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Social Media -->
        <div class="gent-card">
            <p class="gent-card-title"><?php _e('Social Media', 'gent'); ?></p>
            <div class="gent-form-grid">
                <div class="gent-field">
                    <label><?php _e('Facebook URL', 'gent'); ?></label>
                    <input type="url" name="facebook_url" value="<?= $v('facebook_url') ?>"
                        placeholder="https://facebook.com/...">
                </div>
                <div class="gent-field">
                    <label><?php _e('Instagram URL', 'gent'); ?></label>
                    <input type="url" name="instagram_url" value="<?= $v('instagram_url') ?>"
                        placeholder="https://instagram.com/...">
                </div>
                <div class="gent-field">
                    <label><?php _e('TikTok URL', 'gent'); ?></label>
                    <input type="url" name="tiktok_url" value="<?= $v('tiktok_url') ?>"
                        placeholder="https://tiktok.com/...">
                </div>
                <div class="gent-field">
                    <label><?php _e('YouTube URL', 'gent'); ?></label>
                    <input type="url" name="youtube_url" value="<?= $v('youtube_url') ?>"
                        placeholder="https://youtube.com/...">
                </div>
            </div>
        </div>

        <!-- Branding -->
        <div class="gent-card">
            <p class="gent-card-title"><?php _e('Branding', 'gent'); ?></p>
            <div class="gent-form-grid">
                <div class="gent-field">
                    <label><?php _e('Logo', 'gent'); ?></label>
                    <?php gent_media_picker('logo', $opts['logo'] ?? '', __('Syncs with WordPress custom logo & Customizer.', 'gent')); ?>
                </div>
                <div class="gent-field">
                    <label><?php _e('Favicon', 'gent'); ?></label>
                    <?php gent_media_picker('favicon', $opts['favicon'] ?? '', __('Syncs with WordPress › Appearance › Customize › Site Icon.', 'gent')); ?>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="gent-card">
            <p class="gent-card-title"><?php _e('Footer', 'gent'); ?></p>
            <div class="gent-form-grid">
                <div class="gent-field" style="grid-column:1/-1">
                    <label><?php _e('Footer Logo', 'gent'); ?></label>
                    <?php gent_media_picker('footer_logo', $opts['footer_logo'] ?? '', __('Separate logo displayed in the footer area.', 'gent')); ?>
                </div>
                <div class="gent-field" style="grid-column:1/-1">
                    <label><?php _e('Footer Description', 'gent'); ?></label>
                    <textarea name="footer_description" rows="3" placeholder="<?php _e('A short description shown below the footer logo.', 'gent'); ?>"><?= esc_textarea($opts['footer_description'] ?? '') ?></textarea>
                </div>
                <div class="gent-field">
                    <label><?php _e('Copyright Text', 'gent'); ?></label>
                    <input type="text" name="copyright_text" value="<?= $v('copyright_text') ?>" placeholder="<?php _e('e.g. © 2025 Gent. All rights reserved.', 'gent'); ?>">
                </div>
                <div class="gent-field">
                    <label><?php _e('Copyright Tagline', 'gent'); ?></label>
                    <input type="text" name="copyright_tagline" value="<?= $v('copyright_tagline') ?>" placeholder="<?php _e('e.g. Designed with ♥ in Bangladesh.', 'gent'); ?>">
                </div>
            </div>
        </div>

        <div class="gent-submit-row">
            <?php submit_button(__('Save Settings', 'gent'), 'primary', 'submit', false); ?>
        </div>

    </form>
</div>