<?php

namespace app\command;


use think\Config;
use think\Exception;
use think\console\Input;
use think\console\Output;
use think\console\Command;
use think\Db;


/**
 * Author: ekr123
 * Date: 2021/11/9
 * Time: 2:37 下午
 * Mail: zwk480314826@163.com
 */

class Installsql extends Command
{


    protected function configure()
    {
        $this
            ->setName('installsql')
            ->setDescription('运行sql文件');
    }


    protected function execute(Input $input, Output $output)
    {

        $host = DATABASE_CONFIG["hostname"];
        $username = DATABASE_CONFIG["username"];
        $password = DATABASE_CONFIG["password"];
        $port = DATABASE_CONFIG["hostport"];
        $charset = "utf8mb4";
        $database = DATABASE_CONFIG['database'];
        $sql_file = ROOT_PATH . 'base/install/init.sql';
        if (!file_exists($sql_file)) {
            $output->error("sql文件不存在");
            return;
        }

        if (empty($host) || empty($username) || empty($port) || empty($database)) {
            $output->error("请先配置const.php -> DATABASE ");
            return;
        }

        $dsn = 'mysql:host=' . $host . ';port=' . $port;
        $pdo = new \PDO($dsn, $username, $password);
        $sql = sprintf(
            'CREATE DATABASE IF NOT EXISTS `%s` DEFAULT CHARACTER SET %s COLLATE utf8mb4_unicode_ci;',
            $database,
            empty($charset) ? 'utf8mb4' : $charset,
        );
        $pdo->exec($sql);
        $pdo->exec('use `' . $database . '`;');
        try {

            // 判断是否存在 install_succes 表
            $sql = sprintf(
                "SELECT * FROM information_schema.tables  WHERE `table_name` = 'admin' and `table_schema` = '%s';",
                $database,
            );
            $result = $pdo->query($sql);
            $result = $result->fetchAll(\PDO::FETCH_ASSOC);
            if ($result) {
                $output->error("admin 表已存在");
                return;
            }

            $sql = file_get_contents($sql_file);
            $pdo->exec($sql);
            $output->info("安装成功");
        } catch (\Exception $e) {
            $output->error("pdo error" . $e->getMessage() . '. line:' . $e->getLine());
        }
    }
}
