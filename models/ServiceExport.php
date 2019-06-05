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
            // $subscriber->addVisible($columns);
            foreach ($columns as $column) {
                if (is_array($subscriber->$column)) {
                    $subscriber->$column = json_encode($subscriber->$column);
                }
                $subscriber->addVisible($column);
            }
        });

        return $services->toArray();
    }
}