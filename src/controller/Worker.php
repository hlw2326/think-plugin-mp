<?php

declare(strict_types=1);

namespace plugin\mp\controller;

use think\admin\Controller;
use think\admin\service\AdminService;
use think\admin\service\ProcessService;
use think\exception\HttpResponseException;

/**
 * Worker 服务管理
 * @class Worker
 */
class Worker extends Controller
{
    /**
     * 重载 Worker 服务
     * @login true
     */
    public function reload()
    {
        if (AdminService::isSuper()) {
            try {
                $command = ProcessService::think('xadmin:worker reload');
                ProcessService::create($command);
                $this->success('Worker 服务重载指令已发送！');
            } catch (HttpResponseException $exception) {
                throw $exception;
            } catch (\Exception $exception) {
                trace_file($exception);
                $this->error($exception->getMessage());
            }
        } else {
            $this->error('请使用超管账号操作！');
        }
    }
}
