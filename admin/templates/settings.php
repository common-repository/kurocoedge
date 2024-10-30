<div class="wrap">
    <h1>Configure KurocoEdge!</h1>
    
    <?php $value = get_option('kurocoedge_general_enable'); ?>
    <?php if($value != '1'): ?>
        <div class='notice notice-warning'>
            <p><strong>This plugin is not enabled. Not sending anything to KurocoEdge Network.</strong></p>
        </div>
    <?php endif; ?>

    <!-- Sync now Functionality Alerts Start -->
    <div id='kurocoedge_settings_sync_now_processing_alert' class='notice notice-info is-dismissible hidden'>
        <p><strong>Syncing Settings now, please Wait.</strong></p>
        <button type='button' class='notice-dismiss'><span class='screen-reader-text'>Dismiss this notice.</span></button>
    </div>
    <div id='kurocoedge_settings_sync_now_success_alert' class='notice notice-success is-dismissible hidden'>
        <p><strong>Sync Settings Successful.</strong></p>
        <button type='button' class='notice-dismiss'><span class='screen-reader-text'>Dismiss this notice.</span></button>
    </div>
    <div id='kurocoedge_settings_sync_now_failed_alert' class='notice notice-error is-dismissible hidden'>
        <p><strong>Sync Settings failed.</strong></p>
        <button type='button' class='notice-dismiss'><span class='screen-reader-text'>Dismiss this notice.</span></button>
    </div>
    <!-- Sync now Functionality Alerts End -->

    <!-- Clear Cache now Functionality Alerts Start -->
    <div id='kurocoedge_cache_clear_processing_alert' class='notice notice-info is-dismissible hidden'>
        <p><strong>Clearing Cache, please Wait.</strong></p>
        <button type='button' class='notice-dismiss'><span class='screen-reader-text'>Dismiss this notice.</span></button>
    </div>
    <div id='kurocoedge_cache_clear_success_alert' class='notice notice-success is-dismissible hidden'>
        <p><strong>Clearing Cache Successful.</strong></p>
        <button type='button' class='notice-dismiss'><span class='screen-reader-text'>Dismiss this notice.</span></button>
    </div>
    <div id='kurocoedge_cache_clear_failed_alert' class='notice notice-error is-dismissible hidden'>
        <p><strong>Clearing Cache failed.</strong></p>
        <button type='button' class='notice-dismiss'><span class='screen-reader-text'>Dismiss this notice.</span></button>
    </div>
    <!-- Clear Cache now Functionality Alerts End -->

    
    <form action='options.php' method='post'>
        <?php settings_fields('kurocoedge_settings'); ?> 
        <?php do_settings_sections('kurocoedge_settings'); ?>
        <input name='submit' class='button button-primary' type='submit' value='<?php esc_attr_e('Save'); ?>' />
    </form>
</div>