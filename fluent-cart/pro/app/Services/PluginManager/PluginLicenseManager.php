<?php

namespace FluentCartPro\App\Services\PluginManager;

use FluentCart\Framework\Support\Arr;

class PluginLicenseManager
{
    private $settings;

    private $pluginBaseName = '';

    public function __construct()
    {
        $this->pluginBaseName = 'fluent-cart-pro/fluent-cart-pro.php';
        $urlBase = admin_url('admin.php?page=fluent-cart#/settings/licensing'); // your plugin dashboard

        $this->settings = [
            'item_id'        => 584,                   // FluentCart product ID
            'license_server' => 'https://cart-ddwe.wp1.site/', // Your store URL where the fluent software licensing plugin installed
            'item_id'        => 124373,                   // FluentCart product ID
            'license_server' => 'https://cart.test/', // Your store URL where the fluent software licensing plugin installed
            'plugin_file'    => FLUENTCART_PRO_PLUGIN_FILE_PATH,         // File path of your plugin
            'version'        => FLUENTCART_PRO_PLUGIN_VERSION,      // Current version of your plugin
            'store_url'      => 'https://fluentcart.com',
            'purchase_url'   => 'https://fluentcart.com',
            'settings_key'   => '__fct_pro_plugin_license',
            'activate_url'   => $urlBase . 'settings/license',
            'plugin_title'   => 'FluentCart Pro',
            'author'         => 'your plugin author name',
        ];
    }

    private function getLocalLicenseData($licenseKey = ''): array
    {
        $defaults = [
            'license_key' => $licenseKey ?: 'fluentcart-pro-local',
            'price_id'    => '',
            'expires'     => 'lifetime',
            'status'      => 'valid',
        ];

        $licenseStatus = get_option($this->getVar('settings_key'));
        if ($licenseStatus && is_array($licenseStatus)) {
            $defaults = wp_parse_args($licenseStatus, $defaults);
        }

        return $defaults;
    }

    public function pluginRowMeta($links, $file)
    {
        if ($this->pluginBaseName !== $file) {
            return $links;
        }

        $checkUpdateUrl = esc_url(admin_url('plugins.php?fluent_software_licensing_pro_check_update=' . time()));

        $row_meta = array(
            'docs'         => '<a href="' . esc_url(apply_filters('fluent_boards/docs_url', 'https://fluentboards.com/docs/')) . '" aria-label="' . esc_attr__('View FluentCRM documentation', 'fluent-cart-pro') . '">' . esc_html__('Docs', 'fluent-cart-pro') . '</a>',
            'support'      => '<a href="' . esc_url(apply_filters('fluent_boards/community_support_url', 'https://wpmanageninja.com/support-tickets/#/')) . '" aria-label="' . esc_attr__('Visit Support', 'fluent-cart-pro') . '">' . esc_html__('Help & Support', 'fluent-cart-pro') . '</a>',
            'check_update' => '<a  style="color: #583fad;font-weight: 600;" href="' . $checkUpdateUrl . '" aria-label="' . esc_attr__('Check Update', 'fluent-cart-pro') . '">' . esc_html__('Check Update', 'fluent-cart-pro') . '</a>',
        );

        return array_merge($links, $row_meta);
    }

    public function getVar($var)
    {
        if (isset($this->settings[$var])) {
            return $this->settings[$var];
        }
        return false;
    }

    public function licenseVar($var)
    {
        $details = $this->getLicenseDataFromMeta();
        if (isset($details[$var])) {
            return $details[$var];
        }
        return false;
    }

    public function getLicenseDataFromMeta(): array
    {
        $defaults = [
            'license_key' => '',
            'price_id'    => '',
            'expires'     => '',
            'status'      => 'unregistered', // this is the status mainly
        ];
        $licenseStatus = get_option($this->getVar('settings_key'));
        if (!$licenseStatus || !is_array($licenseStatus)) {
            return $defaults;
        }

        return wp_parse_args($licenseStatus, $defaults);
    }

    public function getLicenseDetails()
    {
        $licenseData = $this->getLicenseDataFromMeta();
        $key = Arr::get($licenseData, 'license_key');

        return $this->getLocalLicenseData($key);

    }

    public function checkLicense($key)
    {
        return $this->getLocalLicenseData($key);

    }

    public function getLicenseMessages()
    {
        $licenseDetails = $this->getLicenseDetails();
        $status = $licenseDetails['status'];

        if ($status == 'expired') {
            return [
                'message'         => $this->getExpireMessage($licenseDetails),
                'type'            => 'in_app',
                'license_details' => $licenseDetails
            ];
        }

        if ($status != 'valid') {
            return [
                'message'         => sprintf('The %s license needs to be activated. %sActivate Now%s',
                    $this->getVar('plugin_title'), '<a href="' . $this->getVar('activate_url') . '">',
                    '</a>'),
                'type'            => 'global',
                'license_details' => $licenseDetails
            ];
        }

        return false;
    }


    public function activateLicense($licenseKey)
    {
        return $this->updateLicenseDetails($this->getLocalLicenseData($licenseKey));
    }

    public function deactivateLicense()
    {
        $licenseDetails = $this->getLicenseDataFromMeta();

        if (empty($licenseDetails['license_key'])) {
            return new \WP_Error(423, 'No license key found');
        }

        return $this->updateLicenseDetails($this->getLocalLicenseData());
    }

    public function isRequireVerify()
    {
        $lastCalled = get_option($this->getVar('settings_key') . '_lc');
        if (!$lastCalled) {
            return true;
        }

        return (time() - $lastCalled) > 604800; // 7 days
    }

    public function verifyRemoteLicense($isForced = false)
    {
        update_option($this->getVar('settings_key') . '_lc', time(), 'no');

        return $this->updateLicenseDetails($this->getLocalLicenseData());
    }

    public function getRemoteLicense()
    {
        return $this->getLocalLicenseData($this->getSavedLicenseKey());
    }

    private function processRemoteLicenseData($license_data, $licenseKey = false)
    {
        return $this->updateLicenseDetails($this->getLocalLicenseData($licenseKey));
    }

    private function updateLicenseDetails($data)
    {
        $licenseDetails = $this->getLicenseDataFromMeta();
        update_option($this->getVar('settings_key'), wp_parse_args($data, $licenseDetails));
        $data = get_option($this->getVar('settings_key'));

        if (Arr::get($data, 'status') == 'expired') {
            $data['renew_url'] = 'https://fluentcart.com/';
        }
        return $data;
    }

    private function getErrorMessage($licenseData, $licenseKey = false)
    {
        $errorMessage = 'There was an error activating the license, please verify your license is correct and try again or contact support.';
        $errorType = $licenseData['license'] ?? '';

        if (!$errorType && !empty($licenseData['license'])) {
            if ($licenseData['license'] == 'expired') {
                return sprintf('Your license has been expired at %s. Please <a target="_blank" href="%s">click here</a> to renew your license', $licenseData['expires'], $this->getRenewUrl());
            }
        }


        if ($errorType == 'expired') {
            $renewUrl = $this->getRenewUrl($licenseKey);
            $errorMessage = 'Your license has been expired at ' . $licenseData->expires . ' . Please <a target="_blank" href="' . $renewUrl . '">click here</a> to renew your license';
        } else if ($errorType == 'no_activations_left') {
            $errorMessage = 'No Activation Site left: You have activated all the sites that your license offer. Please go to wpmanageninja.com account and review your sites. You may deactivate your unused sites from wpmanageninja account or you can purchase another license. <a target="_blank" href="' . $this->getVar('purchase_url') . '">' . 'Click Here to purchase another license' . '</a>';
        } else if ($errorType == 'missing') {
            $errorMessage = sprintf('The given license key is not valid. Please verify that your license is correct. You may login to %s and get your valid license key for your purchase.', '<a rel="noopener" target="_blank" href="https://wpmanageninja.com/account/dashboard/#/">wpmanageninja.com account</a>');
        } else if ($errorType == 'invalid') {
            $errorMessage = 'The given license key is not valid. Please verify that your license is correct and try again or contact support.';
        }

        return $errorMessage;
    }

    public function getExpireMessage($licenseData, $scope = 'global')
    {
        if ($scope == 'global') {
            $renewUrl = $this->getVar('activate_url');
        } else {
            $renewUrl = $this->getRenewUrl();
        }

        return '<p>Your ' . $this->getVar('plugin_title') . ' license has been <b>expired at ' . date('d M Y', strtotime($licenseData['expires'])) . '</b>, Please ' .
            '<a href="' . $renewUrl . '"><b>' . 'Click Here to Renew Your License' . '</b></a>' . '</p>';
    }

    private function urlGetContentFallBack($url)
    {
        $parts = parse_url($url);
        $host = $parts['host'];
        $result = false;
        if (!function_exists('curl_init')) {
            $ch = curl_init();
            $header = array('GET /1575051 HTTP/1.1',
                "Host: {$host}",
                'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language:en-US,en;q=0.8',
                'Cache-Control:max-age=0',
                'Connection:keep-alive',
                'Host:adfoc.us',
                'User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36',
            );
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_COOKIESESSION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $result = curl_exec($ch);
            curl_close($ch);
        }
        if (!$result && function_exists('fopen') && function_exists('stream_get_contents')) {
            $handle = fopen($url, "r");
            $result = stream_get_contents($handle);
        }
        return $result;
    }

    private function getSavedLicenseKey()
    {
        $details = $this->getLicenseDataFromMeta();
        return $details['license_key'];
    }

    public function getRenewUrl($licenseKey = false)
    {
        if (!$licenseKey) {
            $licenseKey = $this->getSavedLicenseKey();
        }
        if ($licenseKey) {
            $renewUrl = $this->getVar('store_url');
        } else {
            $renewUrl = $this->getVar('purchase_url');
        }
        return $renewUrl;
    }

    /*
     * Init Updater
     */
    public function initUpdater()
    {
        $licenseDetails = $this->getLicenseDataFromMeta();
        // set up the updater
        new Updater($this->getVar('license_server'), $this->getVar('plugin_file'), array(
            'version'   => $this->getVar('version'),
            'license'   => $licenseDetails['license_key'],
            'item_name' => $this->getVar('item_name'),
            'item_id'   => $this->getVar('item_id'),
            'author'    => $this->getVar('author')
        ),
            array(
                'license_status' => $licenseDetails['status'],
                'admin_page_url' => $this->getVar('activate_url'),
                'purchase_url'   => $this->getVar('purchase_url'),
                'plugin_title'   => $this->getVar('plugin_title')
            )
        );
    }

    private function getOtherInfo()
    {
        if (!$this->timeMatched()) {
            return false;
        }

        global $wp_version;
        $themeName = wp_get_theme()->get('Name');
        if (strlen($themeName) > 30) {
            $themeName = 'custom-theme';
        }

        return [
            'plugin_version' => $this->getVar('version'),
            'php_version'    => (defined('PHP_VERSION')) ? PHP_VERSION : phpversion(),
            'wp_version'     => $wp_version,
            'plugins'        => (array)get_option('active_plugins'),
            'site_lang'      => get_bloginfo('language'),
            'site_title'     => get_bloginfo('name'),
            'theme'          => $themeName
        ];
    }

    private function timeMatched()
    {
        $prevValue = get_option('_fluent_last_m_run');
        if (!$prevValue) {
            return true;
        }
        return (time() - $prevValue) > 518400; // 6 days match
    }

}
