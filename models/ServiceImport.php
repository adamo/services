<?php
namespace Depcore\Services\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;


/**
 * Import services class
 */
class ServiceImport extends \Backend\Models\ImportModel
{

	public $rules = [];

	function importData($results, $sessionKey = null)
	{
		foreach ($results as $row => $data) {
			try {
				$service = new \Depcore\Services\Models\Service;
				$service->fill( $data );
				$service->save(  );

				$this->logCreated(  );

			} catch (\Exception $e) {
				$this->logError( $row, $e->getMessage(  ) );
			}
		}
	}

}