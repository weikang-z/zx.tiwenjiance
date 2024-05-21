<?php

namespace app\command;

use think\console\Command;
use think\console\input\Argument;
use think\console\Input;
use think\console\Output;
use EasyTask\Task;


class Timer extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('timer')
            ->addArgument("status", Argument::REQUIRED, "start|stop|status",)
            ->setDescription('启动系统计划任务');
    }

    protected function execute(Input $input, Output $output)
    {
        // 指令输出
        $run_status = $input->getArgument('status');
        $task = new Task();
        $task->setDaemon(true)
            ->setTimeZone('Asia/Shanghai')
            ->setAutoRecover(true)
            ->setPrefix(APP_NANE . "_timer");
        $logPath = ROOT_PATH . "runtime/timer";
        if (!file_exists($logPath)) {
            mkdir($logPath, 0777, true);
        }
        $task->setRunTimePath($logPath);

        $timers = include APP_PATH . "extra/timers.php";

        if (empty($timers)) {
            $output->writeln("没有计划任务");
            return;
        }

        foreach ($timers as $timer) {
            if ($timer['status']) {
                $task->addClass($timer['class'], $timer['method'], $timer['name'], $timer['time'], $timer['uses']);
            }
        }

        if ($run_status == "start") {
            $task->start();
        } else if ($run_status == "stop") {
            $task->stop(true);
        } else if ($run_status == "status") {
            $task->status();
        }
    }
}
