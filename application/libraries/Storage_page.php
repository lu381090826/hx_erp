
<?php    
/*   
 * PHP分页类   
 * @package Page   
 * @Created 2013-03-27   
 * @Modify  2013-03-27   
 * @link http://www.60ie.net   
 * Example:   
       $myPage=new Pager(1300,intval($CurrentPage));   
       $pageStr= $myPage->GetPagerContent();   
       echo $pageStr;   
 */   
class Storage_page {    
    private $pageSize = 10;    
    private $pageIndex;    
    private $totalNum;    

    private $totalPagesCount;    

    private $pageUrl;    
    private static $_instance;    

    public function __construct($params, $p_pageSize = 10,$p_initNum=3,$p_initMaxNum=5) {    
        if (! isset ( $params['count'] ) || !isset($params['page'])) {    
            die ( "pager initial error" );    
        }    
       
        $this->totalNum = $params['count'];    
        $this->pageIndex = $params['page'];    
        $this->pageSize = $p_pageSize;    
        $this->initNum=$p_initNum;    
        $this->initMaxNum=$p_initMaxNum;    
        $this->totalPagesCount= ceil($params['count'] / $p_pageSize);    
        
         $this->_initPagerLegal();    
    }    

 
  /*   
   *设置页面参数合法性   
   *@return void   
  */   
  private function _initPagerLegal()    
  {    
      if((!is_numeric($this->pageIndex)) ||  $this->pageIndex<1)    
      {    
          $this->pageIndex=1;    
      }elseif($this->pageIndex > $this->totalPagesCount)    
      {    
          $this->pageIndex=$this->totalPagesCount;    
      }    

          

  }    
//$this->pageUrl}={$i}    
//{$this->CurrentUrl}={$this->TotalPages}    
    public function GetPagerContent() {    
        $str = "<div class=\"Pagination\" align='center'>";    
        //首页 上一页    
        if($this->pageIndex==1)    
        {    
            $str .="<a href='javascript:void(0)' class='tips' title='首页'>首页</a> "."\n";    
            $str .="<a href='javascript:void(0)' class='tips' title='上一页'>上一页</a> "."\n"."\n";    
        }else   
        {    
            $str .="<a href='javascript:void(0)' class='tips' onclick='jump_page(1)' title='首页'>首页</a> "."\n";    
                    $str .="<a href='javascript:void(0)' class='tips' onclick='jump_page({$this->pageIndex}-1)' title='上一页'>上一页</a> "."\n"."\n";    
        }    

            

        /*   

        除首末后 页面分页逻辑   

        */   
         //10页（含）以下    
         $currnt="";    
         if($this->totalPagesCount<=10)    
         {    

            for($i=1;$i<=$this->totalPagesCount;$i++)    

            {    
                       if($i==$this->pageIndex)    
                       {    $currnt=" class='current'";}    
                       else   
                       {    $currnt="";    }    
                        $str .="<a href='javascript:void(0)' {$currnt} onclick='jump_page($i)'>$i</a>"."\n" ;    
            }    
         }else                                //10页以上    
         {   if($this->pageIndex<3)  //当前页小于3    
             {    
                     for($i=1;$i<=3;$i++)    
                     {    
                         if($i==$this->pageIndex)    
                           {    $currnt=" class='current'";}    
                         else   
                         {    $currnt="";    }    
                        $str .="<a href='javascript:void(0) ' {$currnt} onclick='jump_page($i)'>$i</a>"."\n" ;    
                     }    

                     $str.="<span class=\"dot\">……</span>"."\n";    

                 for($i=$this->totalPagesCount-3+1;$i<=$this->totalPagesCount;$i++)//功能1    
                 {    
                      $str .="<a href='javascript:void(0)' onclick='jump_page($i)'>$i</a>"."\n" ;    

                 }    
             }elseif($this->pageIndex<=5)   //   5 >= 当前页 >= 3    
             {    
                 for($i=1;$i<=($this->pageIndex+1);$i++)    
                 {    
                      if($i==$this->pageIndex)    
                       {    $currnt=" class='current'";}    
                       else   
                       {    $currnt="";    }    
                        $str .="<a href='javascript:void(0) ' {$currnt} onclick='jump_page($i)'>$i</a>"."\n" ;    

                 }    
                 $str.="<span class=\"dot\">……</span>"."\n";    

                 for($i=$this->totalPagesCount-3+1;$i<=$this->totalPagesCount;$i++)//功能1    
                 {    
                      $str .="<a href='javascript:void(0)' onclick='jump_page($i)'>$i</a>"."\n" ;    

                 }    

             }elseif(5<$this->pageIndex  &&  $this->pageIndex<=$this->totalPagesCount-5 )             //当前页大于5，同时小于总页数-5    

             {    

                 for($i=1;$i<=3;$i++)    
                 {    
                     $str .="<a href='{$this->pageUrl}={$i}' >$i</a>"."\n" ;    
                 }    
                  $str.="<span class=\"dot\">……</span>";                 
                 for($i=$this->pageIndex-1 ;$i<=$this->pageIndex+1 && $i<=$this->totalPagesCount-5+1;$i++)    
                 {    
                       if($i==$this->pageIndex)    
                       {    $currnt=" class='current'";}    
                       else   
                       {    $currnt="";    }    
                        $str .="<a href='javascript:void(0) ' {$currnt} onclick='jump_page($i)'>$i</a>"."\n" ;    
                 }    
                 $str.="<span class=\"dot\">……</span>";    

                 for($i=$this->totalPagesCount-3+1;$i<=$this->totalPagesCount;$i++)    
                 {    
                      $str .="<a href='javascript:void(0)' onclick='jump_page($i)'>$i</a>"."\n" ;    

                 }    
             }else   
             {    

                  for($i=1;$i<=3;$i++)    
                 {    
                     $str .="<a href='javascript:void(0)' onclick='jump_page($i)'>$i</a>"."\n" ;    
                 }    
                  $str.="<span class=\"dot\">……</span>"."\n";    

                  for($i=$this->totalPagesCount-5;$i<=$this->totalPagesCount;$i++)//功能1    
                 {    
                       if($i==$this->pageIndex)    
                       {    $currnt=" class='current'";}    
                       else   
                       {    $currnt="";    }    
                        $str .="<a href='javascript:void(0) ' {$currnt} onclick='jump_page($i)'>$i</a>"."\n" ;    

                 }    
            }           

         }    

             

             
        /*   

        除首末后 页面分页逻辑结束   

        */   

        //下一页 末页    
        if($this->pageIndex==$this->totalPagesCount)    
        {       
            $str .="\n"."<a href='javascript:void(0)'  class='tips' title='下一页'>下一页</a>"."\n" ;    
            $str .="<a href='javascript:void(0)' class='tips' title='末页'>末页</a>"."\n";    

                
        }else   
        {    
            $str .="\n"."<a href='javascript:void(0)' onclick='jump_page({$this->pageIndex}+1)' class='tips' title='下一页'>下一页</a> "."\n";    
            $str .="<a href='javascript:void(0)' onclick='jump_page({$this->totalPagesCount})' class='tips' title='末页'>末页</a> "."\n" ;    
        }           

        $str .= "</div>";    
        return $str;    
    }    

   

   
/**   
 * 获得实例   
 * @return     
 */   
//  static public function getInstance() {    
//      if (is_null ( self::$_instance )) {    
//          self::$_instance = new pager ();    
//      }    
//      return self::$_instance;    
//  }    

   
}    
?>   