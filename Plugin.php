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
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'depcore.services.create_services' => [
                'tab' => 'depcore.services::lang.plugin.name',
                'label' => 'depcore.services::lang.permissions.create_services'
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
                'permissions' => ['depcore.services.*'],
                'order'       => 500,
            ],
        ];
    }

}
