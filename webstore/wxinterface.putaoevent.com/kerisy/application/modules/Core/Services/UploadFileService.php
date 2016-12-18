<?php
namespace App\Core\Services;

use \Kerisy\Database\DB;

use Kerisy\Support\CloudUpload;
use Kerisy\Log\Logger;

class UploadFileService
{
	
	private $cloudUploadService;
	private $logService;
	
    public function __construct(CloudUpload $cloudUpload, Logger $logger)
    {
        $this->cloudUploadService = $cloudUpload;
		$this->logService = $logger;
    }
	
	public function uploadImage($request)
	{
		$action = $request->input('dir');

		// $returnData = array(
			// 'state' => 'FAILURE',	//上传状态，上传成功时必须返回"SUCCESS"
			// 'url' => '',			//返回的地址
			// 'title' => '',			//新文件名
			// 'original' => '',		//原始文件名
			// 'type' => '',			//文件类型
			// 'size' => ''			//文件大小
		// );
		
		$returnData = array(
			'url' => '',
			'error' => 0,
			'message' => ''
		);
		
        switch ($action)
        {
            case 'config':
                $result = json_encode(
                	array(
                		'serverUrl' => '/upload/image',
                		'imageActionName' => 'image',
                		'imageFieldName' => 'upfile',
                		'imageAllowFiles' => [".png", ".jpg", ".jpeg", ".gif", ".bmp"],
                		'imageUrlPrefix' => ''
					)
				);

				$this->logService->info('config result: ' . $result);
                break;
            /* 上传图片 */
            case 'image':

                $result = $this->cloudUploadService->dealUpload($request->files['upfile']);
				
				// $returnData['state'] = strtoupper($result['status']);
				$returnData['url'] = getFilePath($result['msg']['file_name']);
				
				if ( strtoupper($result['status']) !== 'SUCCESS' )
				{
					$returnData['error'] = 1;
					$returnData['message'] = "上传失败,请稍后再试.";
				}

				// $returnData['title'] = $result['msg']['file_name'];
				// $returnData['original'] = pathinfo($result['msg']['file_name'])['filename'];
				// $returnData['type'] = pathinfo($result['msg']['file_name'])['extension'];
				// $returnData['size'] = '';
				
				
				$this->logService->info('uploadimage result: ' . json_encode($result));
                break;
            /* 上传涂鸦 */
            case 'uploadscrawl':
				$this->logService->info('uploadscrawl: ');
				break;
            /* 上传视频 */
            case 'uploadvideo':
			$this->logService->info('uploadvideo: ');
				break;
            /* 上传文件 */
            case 'uploadfile':
				$this->logService->info('uploadfile: ');
				break;
        }

        return $returnData;
	}
	

}

