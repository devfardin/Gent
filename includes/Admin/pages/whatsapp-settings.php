<?php
if (!defined('ABSPATH')) exit;

$option_name = 'gent_whatsapp';

if (isset($_POST['gent_whatsapp_nonce']) && wp_verify_nonce($_POST['gent_whatsapp_nonce'], 'gent_save_whatsapp')) {
    $data = [
        'whatsapp_number'      => sanitize_text_field($_POST['whatsapp_number'] ?? ''),
        'predefined_message'   => sanitize_textarea_field($_POST['predefined_message'] ?? ''),
        'button_text'          => sanitize_text_field($_POST['button_text'] ?? ''),
        'button_position'      => sanitize_text_field($_POST['button_position'] ?? 'right'),
        'button_color'         => sanitize_hex_color($_POST['button_color'] ?? '#25d366'),
        'show_on_mobile'       => isset($_POST['show_on_mobile']) ? '1' : '0',
        'show_on_desktop'      => isset($_POST['show_on_desktop']) ? '1' : '0',
        'open_in_new_tab'      => isset($_POST['open_in_new_tab']) ? '1' : '0',
        'availability_start'   => sanitize_text_field($_POST['availability_start'] ?? ''),
        'availability_end'     => sanitize_text_field($_POST['availability_end'] ?? ''),
        'offline_message'      => sanitize_textarea_field($_POST['offline_message'] ?? ''),
    ];
    update_option($option_name, $data);
    $saved_notice = true;
}

$opts = get_option($option_name, []);
$v    = fn(string $key) => esc_attr($opts[$key] ?? '');
$chk  = fn(string $key, string $default = '1') => ($opts[$key] ?? $default) === '1' ? 'checked' : '';
?>
<div class="gent-settings-wrap">
    <h1>
        <svg width="20" height="20" viewBox="0 0 24 24" fill="#25d366" style="vertical-align:middle;margin-right:8px;margin-top:-3px">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
        <?php _e('WhatsApp Settings', 'gent'); ?>
    </h1>

    <?php if (!empty($saved_notice)) : ?>
        <div class="gent-notice success"><?php _e('✓ Settings saved successfully.', 'gent'); ?></div>
    <?php endif; ?>

    <form method="post">
        <?php wp_nonce_field('gent_save_whatsapp', 'gent_whatsapp_nonce'); ?>

        <!-- Connection -->
        <div class="gent-card">
            <p class="gent-card-title"><?php _e('Connection', 'gent'); ?></p>
            <div class="gent-form-grid">
                <div class="gent-field">
                    <label><?php _e('WhatsApp Number', 'gent'); ?></label>
                    <input type="text" name="whatsapp_number" value="<?= $v('whatsapp_number') ?>" placeholder="8801XXXXXXXXX">
                    <span class="gent-hint"><?php _e('Country code without + or spaces.', 'gent'); ?></span>
                </div>
                <div class="gent-field">
                    <label><?php _e('Button Text', 'gent'); ?></label>
                    <input type="text" name="button_text" value="<?= $v('button_text') ?>" placeholder="Chat with us">
                </div>
                <div class="gent-field" style="grid-column:1/-1">
                    <label><?php _e('Predefined Message', 'gent'); ?></label>
                    <textarea name="predefined_message" rows="3" placeholder="Hello! I'm interested in..."><?= esc_textarea($opts['predefined_message'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Button Appearance -->
        <div class="gent-card">
            <p class="gent-card-title"><?php _e('Button Appearance', 'gent'); ?></p>
            <div class="gent-form-grid">
                <div class="gent-field">
                    <label><?php _e('Button Color', 'gent'); ?></label>
                    <div class="gent-color-field">
                        <input type="color" name="button_color" value="<?= $v('button_color') ?: '#25d366' ?>">
                        <span><?= $v('button_color') ?: '#25d366' ?></span>
                    </div>
                </div>
                <div class="gent-field">
                    <label><?php _e('Button Position', 'gent'); ?></label>
                    <select name="button_position">
                        <option value="right" <?= selected($opts['button_position'] ?? 'right', 'right', false) ?>><?php _e('Bottom Right', 'gent'); ?></option>
                        <option value="left"  <?= selected($opts['button_position'] ?? 'right', 'left', false) ?>><?php _e('Bottom Left', 'gent'); ?></option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Visibility -->
        <div class="gent-card">
            <p class="gent-card-title"><?php _e('Visibility', 'gent'); ?></p>
            <div class="gent-form-grid">
                <div class="gent-field">
                    <label class="gent-toggle-label">
                        <input type="checkbox" name="show_on_mobile" value="1" <?= $chk('show_on_mobile') ?>>
                        <?php _e('Show on Mobile', 'gent'); ?>
                    </label>
                </div>
                <div class="gent-field">
                    <label class="gent-toggle-label">
                        <input type="checkbox" name="show_on_desktop" value="1" <?= $chk('show_on_desktop') ?>>
                        <?php _e('Show on Desktop', 'gent'); ?>
                    </label>
                </div>
                <div class="gent-field">
                    <label class="gent-toggle-label">
                        <input type="checkbox" name="open_in_new_tab" value="1" <?= $chk('open_in_new_tab') ?>>
                        <?php _e('Open in New Tab', 'gent'); ?>
                    </label>
                </div>
            </div>
        </div>

        <!-- Availability -->
        <div class="gent-card">
            <p class="gent-card-title"><?php _e('Availability Hours', 'gent'); ?></p>
            <div class="gent-form-grid">
                <div class="gent-field">
                    <label><?php _e('Available From', 'gent'); ?></label>
                    <input type="time" name="availability_start" value="<?= $v('availability_start') ?>">
                </div>
                <div class="gent-field">
                    <label><?php _e('Available Until', 'gent'); ?></label>
                    <input type="time" name="availability_end" value="<?= $v('availability_end') ?>">
                </div>
                <div class="gent-field" style="grid-column:1/-1">
                    <label><?php _e('Offline Message', 'gent'); ?></label>
                    <textarea name="offline_message" rows="2" placeholder="We're currently offline. Leave a message..."><?= esc_textarea($opts['offline_message'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <div class="gent-submit-row">
            <?php submit_button(__('Save Settings', 'gent'), 'primary', 'submit', false); ?>
        </div>

    </form>
</div>
