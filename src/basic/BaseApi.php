<?php
// +----------------------------------------------------------------------
// | 控制器基础类
// +----------------------------------------------------------------------

declare(strict_types=1);

namespace vadmin\basic;

use think\App;
use think\exception\ValidateException;
use think\Validate;


abstract class BaseApi
{
    // 数据返回
    use \vadmin\traits\Result;

    protected string $responseType = 'json';
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 控制器路径
     * @var string
     */
    protected $controllerPath;

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = $app->request;
        $this->controllerPath = str_replace('.',DIRECTORY_SEPARATOR, $this->request->controller(true));
        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize() {
        $langset = $this->app->lang->defaultLangSet();
        $path = $this->app->getAppPath() . 'lang' . DIRECTORY_SEPARATOR . $langset .DIRECTORY_SEPARATOR.(str_replace('/', DIRECTORY_SEPARATOR, $this->controllerPath))  .'.php';
        $this->app->lang->load($path);
    }

    /**
     * 验证数据
     * @access protected
     * @param  array        $data     数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array        $message  提示信息
     * @param  bool         $batch    是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, string|array $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }
}
