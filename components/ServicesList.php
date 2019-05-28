<?php namespace Depcore\Services\Components;

use Cms\Classes\ComponentBase;
use Depcore\Services\Models\Service;

class ServicesList extends ComponentBase
{

    public $services;

    public function componentDetails()
    {
        return [
            'name'        => 'depcore.services::lang.components.serviceslist.name',
            'description' => 'depcore.services::lang.components.serviceslist.description'
        ];
    }

    public function defineProperties()
    {
        return [
            'url' => [
                'title'             => 'depcore.services::lang.components.serviceslist.url',
                'description'       => 'depcore.services::lang.components.serviceslist.url_description',
                'default'           => 'services/case',
                'type'              => 'dropdown',
                'required'          => true,
                'validationMessage' => 'depcore.services::lang.components.serviceslist.url_required',
            ],
            'slug' => [
                'title'             => 'depcore.services::lang.components.serviceslist.slug',
                'description'       => 'depcore.services::lang.components.serviceslist.slug_description',
                'default'           => ':slug',
                'type'              => 'string',
                'required'          => true,
                'validationMessage' => 'depcore.services::lang.components.serviceslist.slug_required',
            ],
            'containerClass' => [
                'title'             => 'depcore.services::lang.components.serviceslist.container_class',
                'description'       => 'depcore.services::lang.components.serviceslist.container_class_description',
                'default'           => 'grid__services',
                'type'              => 'string',
            ],
        ];
    }

    /**
     * get all the published services from list
     *
     * @return void
     * @author Adam
     **/
    public function onRun()
    {
        $this->services = Service::published(  )->get(  );
    }


    public function getUrlOptions()
    {
        return \Cms\Classes\Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }


}