<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getHeader($body,$startget,$endget){
		if (strpos($body,$startget)) {
			$body = strstr($body,$startget);
			if(!empty($endget))
			$body = strstr($body,$endget,true);
			$body = trim($body,$startget);
			return $body;
			
		}
		return $body;
	}
}
