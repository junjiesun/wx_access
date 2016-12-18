<?php
/**
 * Kerisy Framework
 *
 * PHP Version 7
 *
 * @author             Jiaqing Zou <zoujiaqing@gmail.com>
 * @copyright      (c) 2015 putao.com, Inc.
 * @package            kerisy/framework
 * @subpackage         Http
 * @since              2015/11/11
 * @version            2.0.0
 */

namespace Kerisy\Http;

use Kerisy\Core\MiddlewareTrait;
use Kerisy\Core\Object;
use Kerisy\Core\ShouldBeRefreshed;
use Kerisy\Support\Json;
use Kerisy\Core\InvalidParamException;

/**
 * Class Response
 *
 * @package Kerisy\Http
 */
class Response extends Object implements ShouldBeRefreshed
{

    use MiddlewareTrait;

    public $data;

    /**
     * @var HeaderBag
     */
    public $headers;

    public $version = '1.0';

    public $statusCode = 200;
    public $statusText;

    public $prefix;
    public $view;

    public $attach = NULL;

    public static $httpStatuses = [
        100 => 'Continue' ,
        101 => 'Switching Protocols' ,
        102 => 'Processing' ,
        118 => 'Connection timed out' ,
        200 => 'OK' ,
        201 => 'Created' ,
        202 => 'Accepted' ,
        203 => 'Non-Authoritative' ,
        204 => 'No Content' ,
        205 => 'Reset Content' ,
        206 => 'Partial Content' ,
        207 => 'Multi-Status' ,
        208 => 'Already Reported' ,
        210 => 'Content Different' ,
        226 => 'IM Used' ,
        300 => 'Multiple Choices' ,
        301 => 'Moved Permanently' ,
        302 => 'Found' ,
        303 => 'See Other' ,
        304 => 'Not Modified' ,
        305 => 'Use Proxy' ,
        306 => 'Reserved' ,
        307 => 'Temporary Redirect' ,
        308 => 'Permanent Redirect' ,
        310 => 'Too many Redirect' ,
        400 => 'Bad Request' ,
        401 => 'Unauthorized' ,
        402 => 'Payment Required' ,
        403 => 'Forbidden' ,
        404 => 'Not Found' ,
        405 => 'Method Not Allowed' ,
        406 => 'Not Acceptable' ,
        407 => 'Proxy Authentication Required' ,
        408 => 'Request Time-out' ,
        409 => 'Conflict' ,
        410 => 'Gone' ,
        411 => 'Length Required' ,
        412 => 'Precondition Failed' ,
        413 => 'Request Entity Too Large' ,
        414 => 'Request-URI Too Long' ,
        415 => 'Unsupported Media Type' ,
        416 => 'Requested range unsatisfiable' ,
        417 => 'Expectation failed' ,
        418 => 'I\'m a teapot' ,
        422 => 'Unprocessable entity' ,
        423 => 'Locked' ,
        424 => 'Method failure' ,
        425 => 'Unordered Collection' ,
        426 => 'Upgrade Required' ,
        428 => 'Precondition Required' ,
        429 => 'Too Many Requests' ,
        431 => 'Request Header Fields Too Large' ,
        449 => 'Retry With' ,
        450 => 'Blocked by Windows Parental Controls' ,
        500 => 'Internal Server Error' ,
        501 => 'Not Implemented' ,
        502 => 'Bad Gateway or Proxy Error' ,
        503 => 'Service Unavailable' ,
        504 => 'Gateway Time-out' ,
        505 => 'HTTP Version not supported' ,
        507 => 'Insufficient storage' ,
        508 => 'Loop Detected' ,
        509 => 'Bandwidth Limit Exceeded' ,
        510 => 'Not Extended' ,
        511 => 'Network Authentication Required' ,
    ];

    protected $content;
    protected $prepared = FALSE;

    protected $_cookies = NULL;

    public function init()
    {
        $this->headers = new HeaderBag();
    }

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    public function initView()
    {
        if ( !$this->view ) {
            $this->view = new \Kerisy\Http\View($this->prefix);
        }
    }

    public function assign($key , $value)
    {
        $this->attach[ $key ] = $value;
    }

    public function view($template , $data = [ ])
    {
        $this->initView();
        if ( !is_null($this->attach) && is_array($this->attach) ) {
            $data += $this->attach;
        }
        $this->view->replace($data);
        $this->data = $this->view->render($template);

        $this->headers->set('Content-Type' , 'text/html; charset=UTF-8');

        return $this;
    }


    public function json($data = [ ])
    {
        $this->data = json_encode($data , JSON_UNESCAPED_UNICODE);

        $this->headers->set('Content-Type' , 'application/json; charset=UTF-8');

        return $this;
    }

    /**
     * @describe 跳转
     * @auth     haoyanfei<haoyf@putao.com>
     *
     * @param        $url
     * @param int    $code
     * @param string $text
     *
     * @return $this
     */
    public function redirect($url , $code = 302 , $text = '')
    {
        if ( empty( $url ) ) {
            throw new \InvalidArgumentException('Cannot redirect to an empty URL.');
        }
        $this->data =
            sprintf('<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="refresh" content="0;url=%1$s" />
        <title>Redirecting to %1$s</title>
    </head>
    <body>
        Redirecting to <a href="%1$s">%1$s</a>.
    </body>
</html>' , htmlspecialchars($url , ENT_QUOTES , 'UTF-8'));
        $this->headers->set('Location' , $url);
        $this->status($code , $text);

        return $this;
    }

    public function download($filepath , $name , $headers)
    {
        // TODO
        return $this;
    }


    public function status($code , $text = NULL)
    {
        if ( !isset( self::$httpStatuses[ $code ] ) ) {
            throw new InvalidParamException("The HTTP status code is invalid: $code");
        }

        $this->statusCode = $code;

        if ( $text === NULL ) {
            $this->statusText = isset( static::$httpStatuses[ $this->statusCode ] ) ? static::$httpStatuses[ $this->statusCode ] : '';
        } else {
            $this->statusText = $text;
        }
    }

    public function with($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Prepare the response to ready to send to client.
     */
    public function prepare()
    {
        if ( !$this->prepared ) {
            $this->content = is_string($this->data) ? $this->data : Json::encode($this->data);
            if ( !is_string($this->data) ) {
                $this->headers->set('Content-Type' , 'application/json; charset=UTF-8');
            }
            $this->prepared = TRUE;
        }
    }

    /**
     * Gets the raw response content.
     *
     * @return string
     */
    public function content()
    {
        if ( !$this->prepared ) {
            $this->prepare();
        }

        return $this->content;
    }

    public function getCookies()
    {
        return $this->_cookies;
    }

    public function setCookie( $key, $value, $ttl = 0, $path = '/', $domain = '.putao.com', $secure = false, $httponly = false )
    {
        $this->_cookies[] = [$key, $value, $ttl, $path, $domain, $secure, $httponly];
        return $this;
    }

}
