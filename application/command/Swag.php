<?php

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class Swag extends Command
{
    protected function configure()
    {
        $this->setName("swag");
    }

    function execute(Input $input, Output $output)
    {

        $openapi = \OpenApi\Generator::scan([
            "./application/api/controller"
        ]);
        file_put_contents(ROOT_PATH.'public/docs/openapi.json', $openapi->toJson());
    }
}