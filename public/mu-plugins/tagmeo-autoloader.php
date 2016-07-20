<?php
/**
 * Plugin Name: Tagmeo Autoloader
 * Plugin URI:  https://github.com/tagmeo/tagmeo
 * Description: An autoloader that enables standard plugins to be required just like must-use plugins. The autoloaded plugins are included during mu-plugin loading. An asterisk (*) next to the name of the plugin designates the plugins that have been autoloaded.
 * Version:     1.0.0
 * Author:      Kyle Anderson
 * Author URI:  https://divspace.com/
 * License:     MIT License
 * License URI: http://opensource.org/licenses/MIT
 */

namespace Tagmeo;

if (!is_blog_installed()) {
    return;
}

class Autoloader
{
    private static $cache;
    private static $autoPlugins;
    private static $muPlugins;
    private static $count;
    private static $activated;
    private static $relativePath;
    private static $singleton;

    public function __construct()
    {
        if (isset(self::$singleton)) {
            return;
        }

        self::$singleton = $this;
        self::$relativePath = '/../'.basename(__DIR__);

        if (is_admin()) {
            add_filter('show_advanced_plugins', function ($bool, $type) {
                $screen = get_current_screen();
                $current = is_multisite() ? 'plugins-network' : 'plugins';

                if ($screen->{'base'} != $current || $type != 'mustuse' || !current_user_can('activate_plugins')) {
                    return $bool;
                }

                $this->updateCache();

                self::$autoPlugins = array_map(function ($autoPlugin) {
                    $autoPlugin['Name'] .= ' *';
                    return $autoPlugin;
                }, self::$autoPlugins);

                $GLOBALS['plugins']['mustuse'] = array_unique(array_merge(self::$autoPlugins, self::$muPlugins), SORT_REGULAR);

                return false;
            }, 0, 2);
        }

        $this->loadPlugins();
    }

    public function loadPlugins()
    {
        $this->checkCache();
        $this->validatePlugins();
        $this->countPlugins();

        foreach (self::$cache['plugins'] as $plugin_file => $plugin_info) {
            include_once WPMU_PLUGIN_DIR.'/'.$plugin_file;
        }

        $this->pluginHooks();
    }

    private function checkCache()
    {
        $cache = get_site_option('tagmeoAutoloader');

        if ($cache === false) {
            return $this->updateCache();
        }

        self::$cache = $cache;
    }

    private function updateCache()
    {
        require_once(ABSPATH.'wp-admin/includes/plugin.php');

        self::$autoPlugins = get_plugins(self::$relativePath);
        self::$muPlugins = get_mu_plugins();

        $plugins = array_diff_key(self::$autoPlugins, self::$muPlugins);
        $rebuild = !is_array(self::$cache['plugins']);

        self::$activated = ($rebuild) ? $plugins : array_diff_key($plugins, self::$cache['plugins']);

        self::$cache = [
            'plugins' => $plugins,
            'count' => $this->countPlugins()
        ];

        update_site_option('tagmeoAutoloader', self::$cache);
    }

    private function pluginHooks()
    {
        if (!is_array(self::$activated)) {
            return;
        }

        foreach (self::$activated as $plugin_file => $plugin_info) {
            do_action('activate_'.$plugin_file);
        }
    }

    private function validatePlugins()
    {
        foreach (self::$cache['plugins'] as $plugin_file => $plugin_info) {
            if (!file_exists(WPMU_PLUGIN_DIR.'/'.$plugin_file)) {
                $this->updateCache();
                break;
            }
        }
    }

    private function countPlugins()
    {
        if (isset(self::$count)) {
            return self::$count;
        }

        $count = count(glob(WPMU_PLUGIN_DIR.'/*/', GLOB_ONLYDIR | GLOB_NOSORT));

        if (!isset(self::$cache['count']) || $count != self::$cache['count']) {
            self::$count = $count;
            $this->updateCache();
        }

        return self::$count;
    }
}

new Autoloader;
