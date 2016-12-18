<?php

namespace App\Core\Controller\Admin;

use Kerisy\Http\Request;
use Kerisy\Http\Response;
use Kerisy\Log\Logger;
// use Lib\Support\Upload\UploadFile;
use Lib\Support\Paginate;
use Lib\Controller\BaseController;

use App\Core\Services\IndexService;

class IndexController extends BaseController
{
	private $logService;
	private $indexService;

    public function __construct(
		Logger $logger,
		IndexService $indexService	
	)
    {
        parent::__construct();
		$this->logService = $logger;
		$this->indexService = $indexService;
		//$this->middleware = ['Lib\Middleware\Controller\Auth'];
    }

    public function index(Request $request, Response $response)
	{	
		$parameters = array();
		// $parameters['type'] = 'DASHBOARD';
		return $response->view('core/index', $parameters);
	}
	
	public function signinPage(Request $request, Response $response)
	{
		return $response->view('public/signin');
	}
	
	public function signin(Request $request, Response $response)
	{
		$httpStatusCode = 200;

		$parameters = array();
        $parameters['username'] = $request->input('username');
		$parameters['password'] = $request->input('password');
		$returnData = $this->indexService->signin($parameters);

		if ( $returnData['isSuccess'] == false )
		{
			$httpStatusCode = 403;
		}

		return $response->json([
                'httpStatusCode' => $httpStatusCode,
                'data'           => $returnData
        ]);
	}

    public function logout(Request $request, Response $response)
    {
        $logout =  $this->indexService->loginout();
		
        if($logout === true)
        {
           return $response->redirect('/signin');
        }
    }
	
}
