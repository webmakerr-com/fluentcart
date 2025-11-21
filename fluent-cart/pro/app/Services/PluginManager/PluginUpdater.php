<?php

namespace FluentCartPro\App\Services\PluginManager;

class PluginUpdater
{
    /**
     * The caching key for version info.
     *
     * @var string
     */
    private $cache_key;

    private $config = [];

    /**
     * Initialize the class.
     * @param array $config Configuration for the updater.
     */
    public function __construct($config = [])
    {
        $defaults = [
            'type'                 => 'plugin', // Default type is 'plugin'.
            'slug'                 => '', // Slug for the plugin.
            'item_id'              => '', // Item ID for the plugin
            'basename'             => '', // Basename for the plugin
            'version'              => '', // Current Version of the plugin
            'api_url'              => '', // API URL for the updater.
            'license_key'          => '', // License key for the plugin. Optional
            'license_key_callback' => '', // Optional callback for license key
        ];

        $config = wp_parse_args($config, $defaults);

        $this->config = $config;
        $this->cache_key = 'fsl_' . md5($config['basename'] . '_' . $config['item_id']) . '_version_info';

        if ($config['type'] === 'plugin') {
            $this->initPluginUpdaterHooks(); // Initialize the plugin updater hooks.
        }
    }

    /**
     * Run plugin updater hooks.
     *
     * @return void
     */
    private function initPluginUpdaterHooks()
    {
        add_filter('pre_set_site_transient_update_plugins', array($this, 'checkPluginUpdate'));
        add_filter('plugins_api', array($this, 'pluginsApiFilter'), 10, 3);
    }

    /**
     * Check for Update for this specific plugin.
     *
     * @param Object $transient_data Transient data for update.
     */
    public function checkPluginUpdate($transient_data)
    {

        global $pagenow;

        if (!is_object($transient_data)) {
            $transient_data = new \stdClass();
        }

        if ('plugins.php' === $pagenow && is_multisite()) {
            return $transient_data; // If on plugins page in a multisite, skip update check.
        }

        if (!empty($transient_data->response) && !empty($transient_data->response[$this->config['basename']])) {
            return $transient_data;
        }

        $version_info = $this->getVersionInfo();

        if (false !== $version_info && is_object($version_info) && isset($version_info->new_version)) {
            unset($version_info->sections);
            // If new version available then set to `response`.
            if (version_compare($this->config['version'], $version_info->new_version, '<')) {
                $transient_data->response[$this->config['basename']] = $version_info;
            } else {
                // If new version is not available then set to `no_update`.
                $transient_data->no_update[$this->config['basename']] = $version_info;
            }

            $transient_data->last_checked = time();
            $transient_data->checked[$this->config['basename']] = $this->config['version'];
        }


        return $transient_data;
    }

    /**
     * Filter the plugins API response for this specific plugin.
     *
     * @param mixed $data Plugin data.
     * @param string $action The action type.
     * @param object $args Arguments.
     *
     * @return mixed
     */
    public function pluginsApiFilter($data, $action = '', $args = null)
    {
        // must be requesting plugin info.
        if ('plugin_information' !== $action || !$args) {
            return $data;
        }

        $slug = $this->config['slug'];

        // check f this our plugin or not
        if (!isset($args->slug) || ($args->slug !== $slug)) {
            return $data;
        }

        // get the version info.
        $data = $this->getVersionInfo();

        if (is_wp_error($data)) {
            return $data;
        }

        if (!$data) {
            return new \WP_Error('no_data', 'No data found for this plugin');
        }

        return $data;
    }

    /**
     * Get version info from database
     *
     * @return mixed
     */
    private function getCachedVersionInfo()
    {
        global $pagenow;

        // If updater page then force fetch.
        if ('update-core.php' === $pagenow || ($pagenow === 'plugin-install.php' && !empty($_GET['plugin']))) {
            return false;
        }

        return get_transient($this->cache_key);
    }

    /**
     * Set version info to transient
     *
     * @param Object $value Version info to store in the transient.
     * @return void
     */
    private function setCachedVersionInfo($value)
    {
        if (!$value) {
            return;
        }

        set_transient($this->cache_key, $value, 3 * HOUR_IN_SECONDS); // cache for 3 hours.
    }

    /**
     * Get Plugin Version Info
     */
    private function getVersionInfo()
    {
        if (empty($this->config['api_url'])) {
            // No remote endpoint is defined; keep plugin functional without external calls.
            return false;
        }

        $versionInfo = $this->getCachedVersionInfo();

        if (false === $versionInfo) {
            $versionInfo = $this->getRemoteVersionInfo();
            $this->setCachedVersionInfo($versionInfo);
        }

        return $versionInfo;
    }

    private function getRemoteVersionInfo()
    {
        // Remote checks are disabled to keep the plugin fully open-access.
        return false;
    }
}
