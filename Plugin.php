<?php namespace Depcore\Services;

use Backend;
use Event;
use System\Classes\PluginBase;
use Depcore\Services\Models\Service;

/**
 * services Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'depcore.services::lang.plugin.name',
            'description' => 'depcore.services::lang.plugin.description',
            'author'      => 'depcore',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

        Event::listen('translate.localePicker.translateParams', function($page, $params, $oldLocale, $newLocale) {
            if ($page->baseFileName == 'usluga') {
                return Service::translateParams($params, $oldLocale, $newLocale);
            }
        });

        Event::listen('pages.menuitem.listTypes', function () {
            return [
                'single-service' => 'depcore.services::lang.service.label' ,
                'all-services' => 'depcore.services::lang.services.menu_label',
            ];
        });

        Event::listen('pages.menuitem.getTypeInfo', function ($type) {
            if ($type === 'single-service' or $type == 'all-services') {
                return Service::getMenuTypeInfo($type);
            }
        });

        Event::listen('pages.menuitem.resolveItem', function ($type, $item, $url, $theme) {
            if ($type === 'single-service' or $type == 'all-services') {
                return Service::resolveMenuItem($item, $url, $theme);
            }
        });
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {

    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {

        return [
            'Depcore\Services\Components\ServiceContent' => 'ServiceContent',
            'Depcore\Services\Components\ServicesList' => 'ServicesList',
            'Depcore\Services\Components\ServiceNavigation' => 'serviceNavigation',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'depcore.services.manage_services' => [
                'tab' => 'depcore.services::lang.plugin.name',
                'label' => 'depcore.services::lang.permissions.manage_services'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {

        return [
            'services' => [
                'label'       => 'depcore.services::lang.plugin.name',
                'url'         => Backend::url('depcore/services/services'),
                'icon'        => 'icon-cubes',
                'permissions' => ['depcore.services.manage_services'],
                'order'       => 500,
                'sideMenu' => [
                    'services' => [
                        'label'       => 'depcore.services::lang.services.all',
                        'icon'        => 'icon-cube',
                        'url'         => Backend::url('depcore/services/services'),
                        'counter'     => ['\Depcore\Services\Controllers\Services', 'getServicesCount'],
                        'counterLabel'=> 'depcore.services::lang.services.counter_label',
                        'permissions' => ['depcore.services.manage_services'],
                    ],
                    'add' => [
                        'label'       => 'depcore.services::lang.service.create_title',
                        'icon'        => 'icon-plus',
                        'url'         => Backend::url('depcore/services/services/create'),
                        'permissions' => ['depcore.services.manage_services'],
                    ],

                    // 'settings' => [
                    //     'label'       => 'depcore.services::lang.menu.secondary.settings',
                    //     'icon'        => 'icon-cog',
                    //     'url'         => Backend::url('system/settings/update/depcore/services/form'),
                    //     'permissions' => ['depcore.services.access_settings']
                    // ],
                ], // side menu ends
            ],
        ];
    }

        public function registerMarkupTags()
    {
        return [
            'filters' => [
                'getClassIdAndStyle' => [$this, 'extractStyle'],
                'url' => [$this, 'getBaseUrl'],
            ],

        ];
    }

    /**
     * Return combined attributes for the section or content objects
     *
     * @return string
     * @author Adam
     * @todo Invoke media manager for background images
     **/
    public function extractStyle($div)
    {
        if (is_array($div)) {
            $style = '';
            if ( array_key_exists('class', $div) and $div['class'] != '') $style .= "class='{$div['class']}' ";
            if ( array_key_exists('id', $div) and $div['id'] != '' ) $style .= "id='{$div['id']}' ";
            if ( array_key_exists('backgroundImage', $div) and $div['backgroundImage'] != ''  ) {
                $image = \Config::get('cms.storage.media.path').$div['backgroundImage'];
                $div['style'] .= "background-image: url($image)";
            }
            if (array_key_exists('style', $div) and $div['style'] != '' ) $style .= "style='{$div['style']}'";
            return $style;
        }
    }

    /**
     * Get the base url from a long one
     *
     * @return string
     * @author Adam
     **/
    public function getbaseUrl($url)
    {
        $url = parse_url($url);
        if (is_array($url) and array_key_exists('host', $url)) {
            return $url['host'];
        }
    }

    public function registerStormedModels()
    {
        return [
            '\Depcore\Services\Models\Service' => [
                'placement' => 'tabs',
            ],
        ];
    }

}
