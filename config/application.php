<?php

Env::init();

$dotenv = new Dotenv\Dotenv($app::environmentPath());

if (file_exists($app::environmentFile())) {
    $dotenv->load();

    $dotenv->required([
        'DB_NAME',
        'DB_USER',
        'DB_PASS',
        'WP_HOME',
        'WP_SITEURL'
    ]);
}

define('WP_ENV', env('WP_ENV') ?: 'development');

$envConfig = $app::configPath('environments/'.WP_ENV.'.php');

if (file_exists($envConfig)) {
    require $envConfig;
}

/**
 * General
 */
define('DISABLE_WP_CRON', env('DISABLE_CRON') ?: false);
define('DISALLOW_FILE_EDIT', env('DISABLE_FILE_EDIT') ?: false);
define('AUTOMATIC_UPDATER_DISABLED', env('DISABLE_UPDATER') ?: false);

/**
 * URLs & Paths
 */
if (!defined('ABSPATH')) {
    define('ABSPATH', $app::publicPath('cms'));
}

define('BASEPATH', $app::basePath());

define('WP_HOME', env('WP_HOME'));
define('WP_SITEURL', env('WP_SITEURL'));

define('WP_CONTENT_DIR', $app::publicPath());
define('WP_CONTENT_URL', WP_HOME);

define('WP_PLUGIN_DIR', $app::pluginPath());
define('WP_PLUGIN_URL', WP_HOME.'/'.$app::$pluginDir);

define('WPMU_PLUGIN_DIR', $app::mustUsePluginPath());
define('WPMU_PLUGIN_URL', WP_HOME.'/'.$app::$mustUsePluginDir);

/**
 * Theme
 */
define('WP_DEFAULT_THEME', 'tagmeo');

/**
 * Cookies
 */
define('COOKIEHASH', hash('sha1', WP_HOME));
define('USER_COOKIE', 'tagmeo_user_'.COOKIEHASH);
define('PASS_COOKIE', 'tagmeo_pass_'.COOKIEHASH);
define('AUTH_COOKIE', 'tagmeo_auth_'.COOKIEHASH);
define('TEST_COOKIE', 'tagmeo_test_'.COOKIEHASH);
define('LOGGED_IN_COOKIE', 'tagmeo_logged_in_'.COOKIEHASH);
define('SECURE_AUTH_COOKIE', 'tagmeo_secure_auth_'.COOKIEHASH);

/**
 * Database
 */
define('DB_NAME', env('DB_NAME'));
define('DB_USER', env('DB_USER'));
define('DB_PASSWORD', env('DB_PASS'));
define('DB_HOST', env('DB_HOST') ?: 'localhost');
define('DB_CHARSET', env('DB_CHARSET') ?: 'utf8');
define('DB_COLLATE', env('DB_COLLATE') ?: 'utf8_unicode_ci');

$table_prefix = env('DB_PREFIX') ?: 'wp_';

/**
 * Keys & Salts
 */
define('AUTH_KEY', env('AUTH_KEY'));
define('AUTH_SALT', env('AUTH_SALT'));
define('NONCE_KEY', env('NONCE_KEY'));
define('NONCE_SALT', env('NONCE_SALT'));
define('LOGGED_IN_KEY', env('LOGGED_IN_KEY'));
define('SECURE_AUTH_KEY', env('SECURE_AUTH_KEY'));
define('LOGGED_IN_SALT', env('LOGGED_IN_SALT'));
define('SECURE_AUTH_SALT', env('SECURE_AUTH_SALT'));
