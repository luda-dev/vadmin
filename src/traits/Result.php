<?php
/*
 * @Author: luda <admin@jianyun.chat>
 * @Date: 2025-02-14 23:25:38
 * @LastEditors: luda <admin@jianyun.chat>
 * @LastEditTime: 2025-02-14 23:28:15
 * @Copyright: Copyright (c) 2024~2025 http://jianyun.chat All rights reserved.
 * @License: Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * @Description: 
 */
namespace vadmin\traits;
use think\Response;
use think\exception\HttpResponseException;


trait Result {

    /**
     * 操作成功
     * @param string      $msg     提示消息
     * @param mixed       $data    返回数据
     * @param int         $code    错误码
     * @param string|null $type    输出类型
     * @param array       $header  发送的 header 信息
     * @param array       $options Response 输出参数
     */
    protected function success(string $msg = '', mixed $data = null, int $code = 1, string $type = null, array $header = [], array $options = []): void
    {
        $this->result($msg, $data, $code, $type, $header, $options);
    }

    /**
     * 操作失败
     * @param string      $msg     提示消息
     * @param mixed       $data    返回数据
     * @param int         $code    错误码
     * @param string|null $type    输出类型
     * @param array       $header  发送的 header 信息
     * @param array       $options Response 输出参数
     */
    protected function error(string $msg = '', mixed $data = null, int $code = 0, string $type = null, array $header = [], array $options = []): void
    {
        $this->result($msg, $data, $code, $type, $header, $options);
    }

    /**
     * 返回 API 数据
     * @param string      $msg     提示消息
     * @param mixed       $data    返回数据
     * @param int         $code    错误码
     * @param string|null $type    输出类型
     * @param array       $header  发送的 header 信息
     * @param array       $options Response 输出参数
     */
    public function result(string $msg, mixed $data = null, int $code = 0, string $type = null, array $header = [], array $options = [])
    {
        $result = [
            'Code' => $code,
            'Message'  => $msg,
            'Time' => $this->request->server('REQUEST_TIME'),
            'Data' => $data,
        ];

        $type = $type ?: $this->responseType;
        $code = $header['StatusCode'] ?? 200;

        $response = Response::create($result, $type, $code)->header($header)->options($options);
        throw new HttpResponseException($response);
    }
}