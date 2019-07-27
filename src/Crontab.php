<?php declare(strict_types=1);

namespace Swoft\Crontab;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Process\Process;
use Swoft\Process\UserProcess;
use Swoole\Coroutine;

/**
 * Class Crontab
 *
 * @since 2.0
 *
 * @Bean()
 */
class Crontab extends UserProcess
{
    /**
     * @param Process $process
     */
    public function run(Process $process): void
    {
        while (true) {
            $time = time();
            $task = CrontabRegister::getCronTasks($time);
            foreach ($task as $item) {
                sgo(function () use ($item) {
                    $obj = new $item[0]();
                    call_user_func(array($obj, $item[1]));
                });
            }
            Coroutine::sleep(1);
        }
    }
}
