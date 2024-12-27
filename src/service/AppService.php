<?php
// +----------------------------------------------------------------------
// | VADMIN APP应用服务类
// +----------------------------------------------------------------------

declare(strict_types=1);

namespace vadmin\service;

use think\Service;
use vadmin\container\VConfig;
use vadmin\container\Casbin;
class AppService extends Service
{

    public $bind = [
        'vconfig' => VConfig::class,
        // casbin 容器
        'casbin' => Casbin::class,
    ];

    public function boot()
    {

        // 大量的前置操作
        // dump();

        // 加载App配置信息
        $this->app->vconfig->loadAppConfig();
    }
}
