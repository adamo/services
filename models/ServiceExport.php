<?php
namespace Depcore\Services\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class ServiceExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $services = Service::all();
        $services->each(function($subscriber) use ($columns) {
            $subscriber->addVisible($columns);
        });
        return $services->toArray();
    }
}