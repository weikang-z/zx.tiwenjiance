<?php

namespace app;

use think\console\Input;
use think\console\Output;

class Test extends \think\console\Command
{


    function configure()
    {
        $this->setName('test');
    }

    function execute(Input $input, Output $output)
    {
        $output->info('run ..');
    }
}
