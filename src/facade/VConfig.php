<?php

namespace vadmin\facade;

use think\Facade;

class VConfig extends Facade
{
    protected static function getFacadeClass()
    {
        return \vadmin\container\VConfig::class;
    }
}
