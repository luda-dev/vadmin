<?php
// +----------------------------------------------------------------------
// | VADMIN APP应用服务类
// +----------------------------------------------------------------------

declare(strict_types=1);

namespace vadmin\service;

use think\Service;
use vadmin\container\VConfig;

class VAdminAppService extends Service
{

    public $bind = [
        'vconfig' => VConfig::class,
    ];

    public function boot()
    {
        // 加载App配置信息
        $this->app->vconfig->loadAppConfig();
    }
}
