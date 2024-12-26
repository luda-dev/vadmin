<?php

// +----------------------------------------------------------------------
// | VADMIN 应用、模块、插件管理
// +----------------------------------------------------------------------

use think\App;

class VModule
{
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }
}
