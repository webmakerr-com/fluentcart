<?php

namespace FluentCartPro\App\Services\PluginManager;

class FluentLicensing
{
    private static $instance;

    private $config = [];

    public $settingsKey = '';

    public function register($config = [])
    {
        if (self::$instance) {
            return self::$instance; // Return existing instance if already set.
        }

        if (empty($config['basename']) || empty($config['version']) || empty($config['api_url'])) {
            throw new \Exception('Invalid configuration provided for FluentLicensing. Please provide basename, version, and api_url.');
        }

        $this->config = $config;
        $baseName = isset($config['basename']) ? $config['basename'] : plugin_basename(__FILE__);

        $slug = isset($config['slug']) ? $config['slug'] : explode('/', $baseName)[0];
        $this->config['slug'] = (string) $slug;

        $this->settingsKey = isset($config['settings_key']) ? $config['settings_key'] : '__' . $this->config['slug'] . '_sl_info';

        $config = $this->config;

        if (empty($config['license_key']) && empty($config['license_key_callback'])) {
            $config['license_key_callback'] = function () {
                return $this->getCurrentLicenseKey();
            };
        }

        if (!class_exists('\\' . __NAMESPACE__ . '\PluginUpdater')) {
            require_once __DIR__ . '/PluginUpdater.php';
        }

        // Initialize the updater with the provided configuration.
        new PluginUpdater($config);

        self::$instance = $this; // Set the instance for future use.

        return self::$instance;
    }

    private function getLocalLicenseData($overrides = [])
    {
        $defaults = [
            'license_key'     => 'fluentcart-pro-local',
            'status'          => 'valid',
            'variation_id'    => '',
            'variation_title' => 'Local Activation',
            'expires'         => 'lifetime',
            'activation_hash' => 'local',
            'renew_url'       => '',
            'is_expired'      => false,
        ];

        $saved = get_option($this->settingsKey, []);
        if ($saved && is_array($saved)) {
            $defaults = wp_parse_args($saved, $defaults);
        }

        return wp_parse_args($overrides, $defaults);
    }

    public function getConfig($key)
    {
        if (isset($this->config[$key])) {
            return $this->config[$key]; // Return the requested configuration value.
        }

        throw new \Exception("Configuration key '{$key}' does not exist.");
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            throw new \Exception('Licensing is not registered. Please call register() method first.');
        }

        return self::$instance; // Return the singleton instance.
    }

    public function activate($licenseKey = '')
    {
        if (!$licenseKey) {
            $licenseKey = 'fluentcart-pro-local';
        }

        $saveData = $this->getLocalLicenseData([
            'license_key' => $licenseKey,
        ]);

        update_option($this->settingsKey, $saveData, false);

        return $saveData; // Return the saved data.
    }

    public function deactivate()
    {
        $savedData = $this->getLocalLicenseData([
            'license_key' => 'fluentcart-pro-local',
            'status'      => 'valid',
        ]);

        update_option($this->settingsKey, $savedData, false);

        return $savedData;
    }

    public function getStatus($remoteFetch = false)
    {
        $currentLicense = $this->getLocalLicenseData();

        update_option($this->settingsKey, $currentLicense, false);

        return $currentLicense;
    }

    public function getCurrentLicenseKey()
    {
        $status = $this->getStatus();

        return isset($status['license_key']) ? $status['license_key'] : ''; // Return the current license key.
    }

    private function apiRequest($action, $data = [])
    {
        return $this->getLocalLicenseData($data);
    }

    public function getLicenseNotice()
    {
        return false;
    }

    public function getExpireMessage($licenseData, $scope = 'global')
    {
        if ($scope == 'global') {
            $renewUrl = $this->getConfig('activate_url');
        } else {
            $renewUrl = $this->getConfig('api_url');
        }

        return '<p>Your ' . $this->getConfig('plugin_title') . ' ' . __('license has been', 'fluent-cart-pro') . ' <b>' . __('expired at', 'fluent-cart-pro') . ' ' . gmdate('d M Y', strtotime($licenseData['expires'])) . '</b>, Please ' .
            '<a href="' . $renewUrl . '"><b>' . __('Click Here to Renew Your License', 'fluent-cart-pro') . '</b></a>' . '</p>';
    }
}
