<?php
declare(strict_types=1);

namespace plugin\mp\service;

use plugin\mp\model\PluginMiniUser;
use plugin\mp\model\PluginMiniUserQuery;
use plugin\mp\model\PluginMiniUserScoreLog;
use think\admin\Service;

/**
 * 账号查询服务
 * @class QueryService
 * @package plugin\mp\service
 */
class QueryService extends Service
{
    /**
     * 执行一次账号查询
     *
     * 流程：
     * 1. 检查用户是否存在
     * 2. 判断 VIP 或积分是否足够
     * 3. 积分不足直接返回，不写记录
     * 4. 事务内：扣积分 + 写积分流水 + 写查询记录
     *
     * @param int    $userId   用户ID
     * @param string $platform 平台标识
     * @param string $inputUrl 用户输入的原始链接
     * @param array  $result   查询结果数组（空数组表示查询失败）
     * @param string $failReason 失败原因（result 为空时填写）
     * @return array ['success'=>bool, 'message'=>string, 'query_id'=>int]
     */
    public static function record(int $userId, string $platform, string $inputUrl, array $result = [], string $failReason = ''): array
    {
        $user = PluginMiniUser::mk()->where(['id' => $userId])->findOrEmpty();
        if (!$user->isExists()) {
            return ['success' => false, 'message' => '用户不存在！', 'query_id' => 0];
        }

        $isVip     = self::isVip($user);
        $costScore = 0;

        if (!$isVip) {
            $costScore = intval(sysconf('mini.query_deduct_points') ?: 0);
            if ($costScore > 0 && intval($user->score) < $costScore) {
                return ['success' => false, 'message' => '积分不足，无法查询！', 'query_id' => 0];
            }
        }

        $status  = !empty($result) ? PluginMiniUserQuery::STATUS_SUCCESS : PluginMiniUserQuery::STATUS_FAIL;
        $queryId = 0;

        try {
            \think\facade\Db::startTrans();

            if (!$isVip && $costScore > 0) {
                $newBalance = intval($user->score) - $costScore;
                PluginMiniUser::mk()->where(['id' => $userId])->update(['score' => $newBalance]);
                PluginMiniUserScoreLog::record(
                    $userId,
                    -$costScore,
                    $newBalance,
                    PluginMiniUserScoreLog::SOURCE_QUERY_COST,
                    0,
                    '查询扣除：' . $platform
                );
            }

            $query = PluginMiniUserQuery::mk();
            $query->save([
                'user_id'      => $userId,
                'platform'     => $platform,
                'input_url'    => $inputUrl,
                'query_result' => $status === PluginMiniUserQuery::STATUS_SUCCESS ? json_encode($result, JSON_UNESCAPED_UNICODE) : '',
                'cost_score'   => $costScore,
                'is_vip'       => $isVip ? 1 : 0,
                'status'       => $status,
                'fail_reason'  => $failReason,
                'refunded'     => 0,
            ]);
            $queryId = $query->id;

            \think\facade\Db::commit();

            return ['success' => true, 'message' => '查询成功', 'query_id' => $queryId];
        } catch (\Exception $e) {
            \think\facade\Db::rollback();
            return ['success' => false, 'message' => '系统异常：' . $e->getMessage(), 'query_id' => 0];
        }
    }

    /**
     * 判断用户是否为有效 VIP
     */
    private static function isVip(object $user): bool
    {
        return intval($user->vip_time) > 0 && intval($user->vip_time) > time();
    }
}
