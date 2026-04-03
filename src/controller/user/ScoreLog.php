<?php
declare(strict_types=1);

namespace plugin\mp\controller\user;

use plugin\mp\model\PluginMiniUserScoreLog;
use think\admin\Controller;
use think\admin\helper\QueryHelper;

/**
 * 积分流水
 * @class ScoreLog
 * @package plugin\mp\controller\user
 */
class ScoreLog extends Controller
{
    /**
     * 积分流水
     * @auth true
     * @menu true
     */
    public function index(): void
    {
        PluginMiniUserScoreLog::mQuery()->layTable(function () {
            $this->title   = '积分流水';
            $this->sources = PluginMiniUserScoreLog::getSources();
        }, function (QueryHelper $query) {
            $query->equal('user_id,source');
            $query->dateBetween('create_at');
        });
    }
}
