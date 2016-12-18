<?php
    namespace App\Core\Controller\Admin;

    use Kerisy\Http\Request;
    use Kerisy\Http\Response;
    use Kerisy\Log\Logger;
    use Lib\Support\Paginate;
    use Lib\Controller\BaseController;
    use App\Core\Services\WeChatService;

class WeChatController extends BaseController
{
    private $logService;
    private $weChatService;

    public function __construct(Logger $logger,WeChatService $weChatService){
        $this->logService = $logger;
        $this->weChatService = $weChatService;
    }

    public function weChatPublicNumberList( Request $request,Response $response )
    {

        $parameters = array();
        $parameters['type'] = 'wechatlist';

//        $page = $request->input('page',1);
//        $parameters['limit']         = 20;
//        $parameters['page']          = $page;
//        list( $totalpage, $List ) = $this->weChatService->weChatPublicNumberList($parameters);
        $list = $this->weChatService->weChatPublicNumberList($parameters);
//        $paginate = new Paginate("/wechat/userlist?page={page}", $page, $totalpage, $parameters['limit']);
//        $paginateView  = $paginate->showPages();
        $parameters['list'] = $list;
//        $parameters['paginateView'] = $paginateView;
        return $response->view('core/wechat/wechatlist',$parameters);
    }

    public function wechatAdd( Request $request,Response $response )
    {
        $httpStatusCode = 200;

        $parameters = $request->all();
        $parameters['post'] = 'add';
        $returnData = $this->weChatService->wxChatPost($parameters);

        if ( $returnData['isSuccess'] == false )
        {
            $httpStatusCode = 403;
        }

        return $response->json([
            'httpStatusCode' => $httpStatusCode,
            'data'           => $returnData
        ]);
    }

    public function wechatEdit( Request $request,Response $response )
    {
        $httpStatusCode = 200;

        $parameters = $request->all();
        $parameters['post'] = 'edit';
        $returnData = $this->weChatService->wxChatPost($parameters);

        if ( $returnData['isSuccess'] == false )
        {
            $httpStatusCode = 403;
        }

        return $response->json([
            'httpStatusCode' => $httpStatusCode,
            'data'           => $returnData
        ]);
    }
}
?>