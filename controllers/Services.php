<?php namespace Depcore\Services\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Flash;
use Lang;
use Depcore\Services\Models\Service;

/**
 * Services Back-end Controller
 */
class Services extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Depcore.Services', 'services', 'services');
    }

    /**
     * Deleted checked services.
     */
    public function index_onDelete()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {

            foreach ($checkedIds as $serviceId) {
                if (!$service = Service::find($serviceId)) continue;
                $service->delete();
            }

            Flash::success(Lang::get('depcore.services::lang.services.delete_selected_success'));
        }
        else {
            Flash::error(Lang::get('depcore.services::lang.services.delete_selected_empty'));
        }

        return $this->listRefresh();
    }
}
