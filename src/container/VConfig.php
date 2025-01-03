<?php

namespace vadmin\container;

use app\Request;
use think\App;

// +----------------------------------------------------------------------
// | VADMIN 配置
// +----------------------------------------------------------------------

class VConfig
{
    protected $config = [];

    protected $app;

    protected $path;

    public function Input(string $title, string $name, string $value = '', array $options = [])
    {

        if (isset($options)) {
            $options['rule'] = '';
            $options['tip'] = '';
        }

        $config = [];
        $config['title'] = $title;
        $config['name'] = $name;
        $config['type'] = 'input';
        $config['value'] = $value;
        $config['options'] = $options;
        return $config;
    }

    public function Select(string $title, string $name, string $value = '', array $options = [])
    {
        if (isset($options)) {
            $options['rule'] = '';
            $options['tip'] = '';
        }

        $config = [];
        $config['title'] = $title;
        $config['name'] = $name;
        $config['type'] = 'select';
        $config['value'] = $value;
        $config['options'] = $options;
        return $config;
    }

    public function __construct(App $app , $path)
    {
        $this->app = $app;
        $this->path = $path;
    }

    public static function __make(App $app)
    {
        $path = $app->getBasePath();
        return new static($app, $path);
    }

    public function loadAppConfig()
    {
        $appPath = $this->path;
        $files = glob($appPath . '*/config.php');
        foreach ($files as $file) {
            $name = basename(dirname($file));
            $this->load($file, $name);
            $this->app->config->set(
                $this->getModuleConfigs($name),
                $name
            );
        }
    }

    /**
     * 获取模块配置
     * @param string $name 配置名称
     * @return array 模块配置数组
     */
    protected function getModuleConfigs($name)
    {
        $configs = $this->config[$name] ?? [];
        $result = [];
        foreach (array_keys($configs) as $module) {
            $config = $this->get("$name.$module") ?? [];
            $result[$module] = array_column($config, 'value', 'name');
        }
        return $result;
    }


    /**
     * 加载配置文件
     * @access public
     * @param  string $name 一级配置名
     * @return array
     */
    public function load(string $file, string $name = ''): array
    {
        if (is_file($file)) {
            $filename = $file;
        } elseif (is_file($this->path . $file)) {
            $filename = $this->path . $file;
        }

        if (isset($filename)) {
            return $this->parse($filename, $name);
        }
        return $this->config;
    }
    /**
     * 解析配置文件
     * @access public
     * @param  string $file 配置文件名
     * @param  string $name 一级配置名
     * @return array
     */
    protected function parse(string $file, string $name): array
    {
        $config = [];
        $config = include $file;
        return is_array($config) ? $this->set($config, strtolower($name)) : [];
    }

    /**
     * 检测配置是否存在
     * @access public
     * @param  string $name 配置参数名（支持多级配置 .号分割）
     * @return bool
     */
    public function has(string $name): bool
    {
        if (!str_contains($name, '.') && !isset($this->config[strtolower($name)])) {
            return false;
        }

        return !is_null($this->get($name));
    }

    /**
     * 获取一级配置
     * @access protected
     * @param  string $name 一级配置名
     * @return array
     */
    protected function pull(string $name): array
    {
        $name = strtolower($name);

        return $this->config[$name] ?? [];
    }

    /**
     * 获取配置参数 为空则获取所有配置
     * @access public
     * @param  string $name    配置参数名（支持多级配置 .号分割）
     * @param  mixed  $default 默认值
     * @return mixed
     */
    public function get(string $name = null, $default = null)
    {
        // 无参数时获取所有
        if (empty($name)) {
            return $this->config;
        }

        if (!str_contains($name, '.')) {
            return $this->pull($name);
        }

        $name    = explode('.', $name);
        $name[0] = strtolower($name[0]);
        $config  = $this->config;

        // 按.拆分成多维数组进行判断
        foreach ($name as $val) {
            if (isset($config[$val])) {
                $config = $config[$val];
            } else {
                return $default;
            }
        }

        return $config;
    }

    /**
     * 设置配置参数 name为数组则为批量设置
     * @access public
     * @param  array  $config 配置参数
     * @param  string $name 配置名
     * @return array
     */
    public function set(array $config, string $name = null): array
    {
        if (empty($name)) {
            $this->config = array_merge($this->config, array_change_key_case($config));
            return $this->config;
        }

        if (isset($this->config[$name])) {
            $result = array_merge($this->config[$name], $config);
        } else {
            $result = $config;
        }

        $this->config[$name] = $result;

        return $result;
    }
}
