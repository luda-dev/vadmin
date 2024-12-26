<?php
namespace vadmin\container;

use app\Request;
use think\App;

// +----------------------------------------------------------------------
// | Casbin 
// +----------------------------------------------------------------------

class Casbin
{
    public function __construct()
    { 
       
    }

    public static function __make(App $app )
    {
        // 初始化 Casbin


        return new static($path);
    }

}
