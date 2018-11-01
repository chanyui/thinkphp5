<?php
/**
 * Created by PhpStorm.
 * User: yc
 * Date: 2018/11/1
 * Time: 10:48
 */

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class TestCommand extends Command
{
    protected function configure()
    {
        $this->setName('TestCommand')->setDescription('Here is the TestCommand remark');
    }

    protected function execute(Input $input, Output $output)
    {
        $output->writeln('This is the output of TestCommand!');
    }
}