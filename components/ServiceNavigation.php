<?php namespace Depcore\Services\Components;

use Cms\Classes\ComponentBase;
use Depcore\Services\Models\Service;

class ServiceNavigation extends ComponentBase
{
    public $service;

    public function componentDetails()
    {
        return [
            'name'        => 'depcore.services::lang.components.servicenavigation.name',
            'description' => 'depcore.services::lang.components.servicenavigation.description'
        ];
    }

    public function defineProperties()
    {
        return [

        ];
    }

    /**
     * get current item and apply links
     *
     * @return object
     * @author Adam
     **/
    public function onRun()
    {
        $slug = $this->param('slug');
        $this->service = Service::where('slug', $slug)->first();
    }
}