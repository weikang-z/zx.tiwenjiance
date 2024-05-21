<?php

namespace app\command;

use think\Db;
use think\console\Input;
use think\console\Output;
use think\console\Command;
use think\console\input\Argument;

class Createapimodel extends Command
{

	protected function configure()
	{
		$this->setName("cam")
			->setDescription("快速创建api数据表的模型")
			->addArgument("table", Argument::REQUIRED, "database table name")
			->addArgument("rewrite", Argument::OPTIONAL, "是否强制覆盖");
	}

	protected function execute(Input $input, Output $output)
	{

		$table = $input->getArgument("table");
		$rewrite = $input->getArgument("rewrite");

		$host = DATABASE_CONFIG["hostname"];
		$username = DATABASE_CONFIG["username"];
		$password = DATABASE_CONFIG["password"];
		$port = DATABASE_CONFIG["hostport"];
		$charset = "utf8mb4";
		$database = DATABASE_CONFIG['database'];

		if (empty($host) || empty($username) || empty($port) || empty($database)) {
			$output->error('请先配置const.php -> DATABASE ');
			return;
		}


		$dsn = 'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $database;
		$pdo = new \PDO($dsn, $username, $password);
		$sql = sprintf(
			"SELECT * FROM information_schema.tables  WHERE `table_name` = '%s' and `table_schema` = '%s';",
			$table,
			$database,
		);
		$result = $pdo->query($sql);

		$tableInfo = $result->fetch(\PDO::FETCH_ASSOC);
		$stmt = $pdo->query("SHOW FULL COLUMNS FROM {$table}");
		$columns = $stmt->fetchAll(\PDO::FETCH_ASSOC);

		$propertys = [];
		$jsonfield = [];
		foreach ($columns as $column) {
			$propertys[] = sprintf("* @property array|bool|float|int|mixed|object|stdClass|null %s", $column['Field']);

			if (strpos($column['Type'], 'json') !== false) {
				$jsonfield[] = sprintf("\"%s\"", $column['Field']);
			}
		}


		$tpls = [
			'tablecomment' => $tableInfo['TABLE_COMMENT'],
			'ENGINE' => $tableInfo['ENGINE'],
			'TABLE_NAME' => $tableInfo['TABLE_NAME'],
			'CREATE_TIME' => $tableInfo['CREATE_TIME'],
			'TABLE_COLLATION' => $tableInfo['TABLE_COLLATION'],
			'table_name' => $table,
			'jsonfield' => implode(", ", $jsonfield),
			'property' => implode("\n", $propertys),
			'modelname' => ucfirst($table) . "Model",
		];

		$content = $this->getStub();
		foreach ($tpls as $key => $value) {
			$content = str_replace("{%" . $key . "%}", $value, $content);
		}

		$savePath = APP_PATH . "api/model";
		if (!file_exists($savePath)) {
			mkdir($savePath, 0777, true);
		}
		$filename = ucfirst($table) . "Model.php";

		file_put_contents($savePath . "/" . $filename, $content);
	}

	protected function getStub()
	{
		return file_get_contents(ROOT_PATH . 'application/command/stub/api_model.stub');
	}
}
