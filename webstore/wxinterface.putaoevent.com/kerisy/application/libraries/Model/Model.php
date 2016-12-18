<?php
/**
 * Created by PhpStorm.
 * User: haoyanfei<haoyf@putao.com>
 * Date: 2015/11/29
 * Time: 19:18
 */
namespace Lib\Model;

use Kerisy\Database\Model as baseModel;

class Model extends baseModel
{
    protected $connection = 'default';
    public $timestamps = false;
} 