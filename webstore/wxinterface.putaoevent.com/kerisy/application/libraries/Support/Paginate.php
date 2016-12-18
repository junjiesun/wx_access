<?php

namespace Lib\Support;


class Paginate
{
    private $everypage;           //一页显示的多少个分页块
    private $nowpage;             //当前页
    private $allpage;           //总页数
    private $sindex;              //起头页数
    private $eindex;             //结尾页数
    private $linkurl;            //获取当前的url
    /*
    * $show_pages
    * 使用方法：
    *  $pagesService=new PagesService('list?page={page}','1',$totalpage,3);
    *  $displayView= $pagesService->showPages();
    *  $parameters['view']=$displayView;
    * //===========================================
    *  传入链接，当前页，总页数，按钮个数
    *  页面中取出view，即为分页跳转按钮
    */
    private $show_pages;

    public function __construct($linkurl='', $nowpage=1, $allpage=1, $everypage=16)
    {
        $this->nowpage   = $this->numeric($nowpage);
        $this->allpage   = $this->numeric($allpage);
        $this->everypage = $this->numeric($everypage);
        $this->linkurl   = $linkurl;

        $startend        = $this->getStartEnd( $this->everypage,$this->nowpage,$this->allpage);

        $this->sindex    = $startend[0];
        $this->eindex    = $startend[1];
    }

    private function getStartEnd($everypage,$nowpage,$allpage)
    {
        $midpage = $everypage/2;
        $sindex  = 1;
        $eindex  = $everypage;

        if($allpage>$everypage)
        {
            if(($nowpage-$midpage)>0 && ($nowpage+$midpage < $allpage))
            {
               $sindex = $nowpage-$midpage;
               $eindex = $nowpage+$midpage-1;
            }
            else if(($nowpage-$midpage)>0 && ($nowpage+$midpage >=$allpage ))
            {
               $sindex = $allpage-$everypage+1;
               $eindex = $allpage;
            }
        }else
        {
           $eindex = $allpage;
        }
        return array($sindex,$eindex);
    }

    //检测是否为数字
    private function numeric($num) 
    {
        if (strlen($num)) 
        {
            if (!preg_match("/^[0-9]+$/", $num)) {
                $num = 1;
            } else {
                $num = substr($num, 0, 11);
            }
        } else {
            $num = 1;
        }
        return $num;
    }

    //地址替换
    private function page_replace($page) 
    {
        return str_replace("{page}", $page, $this->linkurl);
    }

    //上一页
    private function pageLast() 
    {
        $page = $this->nowpage == 1 ? 1 : ($this->nowpage-1);
        return "<li ><a href='" . $this->page_replace($page) . "' aria-label='Previous'  title='上一页'><span aria-hidden='true'>&laquo;</span></a></li>";

    }
    //下一页
    private function pageNext()
    {
        $page = $this->nowpage==$this->allpage ? $this->allpage : ($this->nowpage+1) ;
        return "<li ><a href='" . $this->page_replace($page) . "' aria-label='Next'  title='下一页'><span aria-hidden='true'>&raquo;</span></a></li>";
    }

    //输出
    public function showPages() 
    {
        $str = '<nav style="text-align:center"><ul class="pagination" ><input type="hidden" name="page" id="page">';
        $str.= $this->pageLast();
        for ($i = $this->sindex; $i <= $this->eindex; $i++)
        {
            $css = '';
            if ($i == $this->nowpage)
            {
                $css = 'style="font-size:15px; font-weight:bold;background-color:#00acd6;color:#FFFFFF;"' ;
            }
            $str .= '<li><a '.$css.' href="'.$this->page_replace($i).'" > '.$i.'</a></li>';
        }

        $str .= $this->pageNext();
        $str .= "</ul></nav>";
        return $str;
    }
} 