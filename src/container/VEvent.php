<?php
/*
 * @Author: luda <admin@jianyun.chat>
 * @Date: 2025-02-14 16:15:35
 * @LastEditors: luda <admin@jianyun.chat>
 * @LastEditTime: 2025-02-14 16:16:12
 * @Copyright: Copyright (c) 2024~2025 http://jianyun.chat All rights reserved.
 * @License: Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * @Description: 
 */

// +----------------------------------------------------------------------
// | VADMIN 应用、模块、插件管理
// +----------------------------------------------------------------------

use think\App;

class VEvent
{
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }
}
