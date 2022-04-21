<?php namespace Depcore\Services\Components;

use Event;
use Depcore\Services\Models\Service;
use Cms\Classes\ComponentBase;

class ServiceContent extends ComponentBase
{

    public $item;

    public function componentDetails()
    {
        return [
            'name'        => 'depcore.services::lang.components.servicecontent.name',
            'description' => 'depcore.services::lang.components.servicecontent.description'
        ];
    }

    /**
     * prepare variables
     *
     * @return void
     * @author Adam
     **/
    public function onRun()
    {
        $slug = $this->param('slug');
        $this->item = Service::transWhere ( 'slug',$slug )->first (  );
        $this->page['service'] = $this->item;
    }

    public function defineProperties()
    {
        return [];
    }

}