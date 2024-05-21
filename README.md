

## 生产环境安全配置

### 目录权限
```base
chown www:www /var/www/yoursite -R
chmod 555 /var/www/yoursite -R
chmod u+w /var/www/yoursite/runtime -R
chmod u+w /var/www/yoursite/public/uploads -R
```
可以直接执行脚本 `./settingfilep.sh`

### nginx 禁止访问php文件
```nginx
location ~ ^/(uploads|assets)/.*\.(php|php5|jsp)$ {
    deny all;
} 
```


```Apache

RewriteEngine on RewriteCond % !^$
RewriteRule uploads/(.*).(php)$ – [F]

```



## 使用队列

### 定义队列任务处理类
* 在 app\job\ku\ 中定义任务类 (首字母大写) 集成 
* 需要继承 app\job\CreateKu
* 实现 app\job\ku\Base 接口
* 例如
```php

<?php

namespace app\job\ku;

use think\queue\Job;
use app\job\CreateKu;

class Test extends CreateKu implements Base
{
	public $tries = 1;
	static function  checkNeedGo(array $data=[]):bool{
		return false;
	}
	/**
	 * 执行任务
	 * @param $data
	 * @return bool
	 */
	function do(array $data = []): bool
	{
		dump($data);
		return TRUE;
	}
}

```

### 发布任务
```php
* @param $name    string app\job\ku\类名 [首字母大写]
* @param $data    array 队列中的参数
* @param $back_id boolean 是否返回队列id

\app\job\CreateKu::create('Test', [], true);

```





