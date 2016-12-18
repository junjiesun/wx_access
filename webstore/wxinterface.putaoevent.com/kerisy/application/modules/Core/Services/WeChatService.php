<?php
/**
 * Created by PhpStorm.
 * User: SJJ
 * Date: 2016/6/15
 * Time: 17:45
 */

namespace App\Core\Services;

use \Kerisy\Database\DB;
use \Kerisy\Log\Logger;

class WeChatService
{

    public function __construct(Logger $logger)
    {
        $this->logService = $logger;
    }


    public function weChatPublicNumberList( $parameters )
    {
        $sql = "select * from wx_account";
        $list = DB::select($sql);

        return $list;
    }

    public function wxChatPost( $parameters )
    {
        $isSuccess = false;
        if( empty($parameters['name']) || empty($parameters['appid']) || empty($parameters['appSecret']))
        {
            return $isSuccess;
        }

        $time = time();
        try
        {
            DB::beginTransaction();

            if( $parameters['post'] == 'add' )
            {
                $hash = sha1($parameters['appid'].$parameters['appSecret']);
                $data = array(
                    'name' => $parameters['name'],
                    'appId' => $parameters['appid'],
                    'appSecret' => $parameters['appSecret'],
                    'type' => $parameters['type'],
                    'hash' => $hash,
                    'authorization' => $parameters['authorization'],
                    'is_deleted' => false,
                    'is_close' => false,
                    'create_time' => $time,
                    'modified_time' => $time,
                );
                $isSuccess = DB::table('wx_account')->insert($data);
            }elseif ($parameters['post']  == 'edit'){

                $sql = 'update wx_account set 
                            name = ?,
                            appId = ?,
                            appSecret = ?,
                            type = ?,
                            authorization = ?,
                            modified_time = ?
                        where wx_account_id = ?
                            ';

                $isSuccess = DB::update($sql,[$parameters['name'], $parameters['appid'],
                    $parameters['appSecret'],$parameters['type'],$parameters['authorization'],
                    time(),$parameters['wx_account_id']
                ]);
            }

            $returnData['isSuccess'] = (bool)$isSuccess;
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception("Error ".$e);
        }
        return $returnData;
    }

}






