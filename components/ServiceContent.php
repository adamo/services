<?php namespace Depcore\Services\Components;

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
        $this->item = Service::where ( 'slug',$slug )->first (  );

        if ($this->item->meta_title != '') $this->page->title = $this->item->meta_title;
        else $this->page->title = $this->item->name;

        if ($this->item->meta != '') $this->page->meta_description = $this->item->meta;
    }

    public function defineProperties()
    {
        return [];
    }

}