<?php

namespace App\Core\Controller\Admin;

use Kerisy\Http\Controller;
use Kerisy\Http\Request;
use Kerisy\Http\Response;
use App\Core\Services\UploadFileService;
use Kerisy\Log\Logger;

class UploadFileController extends Controller
{
    private $uploadFileService;
	private $logService;
	
    public function __construct(UploadFileService $uploadFileService, Logger $logger)
    {
        $this->uploadFileService = $uploadFileService;
		$this->logService = $logger;
    }
	
	public function uploadImage(Request $request, Response $response)
	{
		
		$returnData = $this->uploadFileService->uploadImage($request);		

        return $response->json($returnData);
	}
	
}	
