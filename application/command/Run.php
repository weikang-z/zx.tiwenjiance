<?php

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class Run extends Command
{

    function configure()
    {
        $this->setName('run')->setDescription('Run the server');
    }

    function execute(Input $input, Output $output)
    {
        $command = sprintf("php -S localhost:%s -t %spublic",
            APP_DEV_PORT,
            ROOT_PATH
        );

        system($command);
    }

}