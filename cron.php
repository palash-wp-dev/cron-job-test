<?php
/*
Plugin Name: Cron
Description: Displays an admin notice every minute using WP Cron.
Version: 1.0Author:
*/


// Hook into WordPress initialization to schedule the event if not already scheduled
add_action('init', 'wp_schedule_cron_event');
function wp_schedule_cron_event() {
    if (!wp_next_scheduled('wp_cron_event')) {
        wp_schedule_event(time(), 'wp_every_minute', 'wp_cron_event');
    }
}

// Add custom cron schedule for every minute
add_filter('cron_schedules', 'wp_add_every_minute_schedule');
function wp_add_every_minute_schedule($schedules) {
    $schedules['wp_every_minute'] = array(
        'interval' => 60,
        'display' => __('Every Minute')
    );
    return $schedules;
}

// Hook the custom cron event to a function
add_action('wp_cron_event', 'wp_display_admin_notice');
function wp_display_admin_notice() {
    $current_time = date("Y-m-d H:i:s");

   error_log('cron fired at:'. $current_time);
}

// Hook into plugin deactivation to clear the scheduled event
register_deactivation_hook(__FILE__, 'wp_deactivate_plugin');
function wp_deactivate_plugin() {
    $timestamp = wp_next_scheduled('wp_cron_event');
    wp_unschedule_event($timestamp, 'wp_cron_event');
}