<?php
declare(strict_types=1);

namespace plugin\mp\service;

use plugin\mp\model\PluginMiniCardScore;
use plugin\mp\model\PluginMiniCardVip;
use plugin\mp\model\PluginMiniCardLog;
use plugin\mp\model\PluginMiniUser;
use plugin\mp\model\PluginMiniUserScoreLog;
use think\admin\Service;

/**
 * 卡密使用服务
 * @class CardUseService
 * @package plugin\mp\service
 */
class CardUseService extends Service
{
    /**
     * 使用积分卡密
     */
    public static function useScoreCard(string $cardCode, int $userId): array
    {
        $canUse = PluginMiniCardLog::canUseCard(PluginMiniCardLog::TYPE_SCORE, $cardCode, $userId);
        if (!$canUse['can']) {
            return ['success' => false, 'message' => $canUse['reason']];
        }

        $card = PluginMiniCardScore::mk()->where(['card_code' => $cardCode])->findOrEmpty();
        if (!$card->isExists()) {
            return ['success' => false, 'message' => '卡密不存在！'];
        }

        try {
            \think\facade\Db::startTrans();

            $logResult = PluginMiniCardLog::recordUse(
                PluginMiniCardLog::TYPE_SCORE,
                $card->id,
                $cardCode,
                $userId,
                ['score' => $card->score]
            );

            if (!$logResult) {
                throw new \Exception('记录使用日志失败');
            }

            $user = PluginMiniUser::mk()->where(['id' => $userId])->findOrEmpty();
            if (!$user->isExists()) {
                throw new \Exception('用户不存在');
            }
            $newBalance = intval($user->score) + intval($card->score);
            PluginMiniUser::mk()->where(['id' => $userId])->update(['score' => $newBalance]);

            PluginMiniUserScoreLog::record(
                $userId,
                intval($card->score),
                $newBalance,
                PluginMiniUserScoreLog::SOURCE_CARD_SCORE,
                intval($card->id),
                '积分卡密充值：' . $cardCode
            );

            \think\facade\Db::commit();

            $stats = PluginMiniCardLog::getCardUseStats($cardCode, PluginMiniCardLog::TYPE_SCORE);

            return [
                'success' => true,
                'message' => '使用成功',
                'data'    => [
                    'score'        => $card->score,
                    'use_count'    => $stats['total_uses'],
                    'unique_users' => $stats['unique_users'],
                ],
            ];
        } catch (\Exception $e) {
            \think\facade\Db::rollback();
            return ['success' => false, 'message' => '使用失败：' . $e->getMessage()];
        }
    }

    /**
     * 使用会员卡密
     */
    public static function useVipCard(string $cardCode, int $userId): array
    {
        $canUse = PluginMiniCardLog::canUseCard(PluginMiniCardLog::TYPE_VIP, $cardCode, $userId);
        if (!$canUse['can']) {
            return ['success' => false, 'message' => $canUse['reason']];
        }

        $card = PluginMiniCardVip::mk()->where(['card_code' => $cardCode])->findOrEmpty();
        if (!$card->isExists()) {
            return ['success' => false, 'message' => '卡密不存在！'];
        }

        try {
            \think\facade\Db::startTrans();

            $logResult = PluginMiniCardLog::recordUse(
                PluginMiniCardLog::TYPE_VIP,
                $card->id,
                $cardCode,
                $userId,
                ['vip_days' => $card->vip_days]
            );

            if (!$logResult) {
                throw new \Exception('记录使用日志失败');
            }

            \think\facade\Db::commit();

            $stats = PluginMiniCardLog::getCardUseStats($cardCode, PluginMiniCardLog::TYPE_VIP);

            return [
                'success' => true,
                'message' => '使用成功',
                'data'    => [
                    'vip_days'     => $card->vip_days,
                    'use_count'    => $stats['total_uses'],
                    'unique_users' => $stats['unique_users'],
                ],
            ];
        } catch (\Exception $e) {
            \think\facade\Db::rollback();
            return ['success' => false, 'message' => '使用失败：' . $e->getMessage()];
        }
    }

    /**
     * 获取用户卡密使用记录
     */
    public static function getUserUseHistory(int $userId, int $cardType = 0, int $page = 1, int $limit = 20): array
    {
        $query = PluginMiniCardLog::mk()->where(['user_id' => $userId]);
        if ($cardType > 0) {
            $query->where(['card_type' => $cardType]);
        }
        $total = $query->count();
        $logs  = $query->order('id', 'desc')->page($page, $limit)->select()->toArray();
        return ['total' => $total, 'page' => $page, 'limit' => $limit, 'logs' => $logs];
    }

    /**
     * 获取卡密使用统计
     */
    public static function getCardStatistics(string $cardCode, int $cardType): array
    {
        $stats      = PluginMiniCardLog::getCardUseStats($cardCode, $cardType);
        $recentUses = PluginMiniCardLog::mk()
            ->where(['card_code' => $cardCode, 'card_type' => $cardType])
            ->order('id', 'desc')
            ->limit(10)
            ->select()
            ->toArray();
        return [
            'total_uses'   => $stats['total_uses'],
            'unique_users' => $stats['unique_users'],
            'recent_uses'  => $recentUses,
        ];
    }
}
