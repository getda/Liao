<?php
/**
 * Author 王小大 [m@wangxiaoda.com]
 * DateTime 2022/8/17 7:42
 * Description 定时任务类
 **/
namespace process;

use support\Log;
use support\Redis;
use Workerman\Crontab\Crontab;

class Task
{
    public function onWorkerStart()
    {

        // 每3天删除一次三天前的聊天记录
        new Crontab('0 0 0 */3 * *', function(){
            foreach ([1] as $group_id) {
                $key = "liao:group_{$group_id}";
                $start = strtotime(date('1970-01-01')) . "000";
                $end = strtotime("-3 day")."000";
                $members = Redis::zrangebyscore($key, $start, $end);
                $number = Redis::zrem($key, $members);
                Log::info("删除 [".date('Y-m-d H:i:s', $end/1000)."] 之前 [{$number}条] 聊天记录！");
            }
        });
    }
}