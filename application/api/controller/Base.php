<?php

namespace app\api\controller;

use app\api\model\UserModel;
use OpenApi\Annotations as OA;
use ReflectionMethod;
use think\Debug;
use app\api\library\F;
use think\Db;
use think\Request;
use think\Log;
use think\response\Json;
use utils\Token;
use function GuzzleHttp\Psr7\uri_for;

/**
 * @OA\OpenApi(
 *  @OA\Info(
 *      title="api接口",
 *      version="1.0.0",
 *     @OA\Contact(email="zwk480314826@163.com"),
 *  ),
 *     @OA\Server(url="http://localhost:1882/api", description="本地测试"),
 * )
 */
class Base extends \think\Controller
{
    use F;

    /**
     * 请求参数
     *  request->param();
     */
    public $p;

    public $request;

    public $token;

    /**
     * 请求方法
     * @var string
     */
    protected string $m;

    protected UserModel $user;

    protected string $ulang;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);

        $this->p = $this->request->param();

        $this->request = Request::instance();
        $this->request->filter('trim,strip_tags,htmlspecialchars');
        $this->token = \request()->header()['authorization'] ?? "";
        $this->m = strtolower($this->request->method());
        $this->ulang = strtolower($request->header('u-lang'));
        $this->auth();
    }

    private function auth(): void
    {
        $controller = $this->request->controller();
        $controller = str_replace(".", '\\', $controller);

        $action = $this->request->action();
        try {
            $reflection = new ReflectionMethod('app\api\controller\\' . $controller, $action);
            $docComment = $reflection->getDocComment();
            $needAuth = !strpos(strtoupper($docComment), '@NOAUTH');
            if ($needAuth) {
                $user_id = Token::get($this->token);
                $user = UserModel::get($user_id);
                if (!$user || $user->is_cancel <> 'n') {
                    header("Content-type:application/json");
                    echo json_encode([
                        "code" => 401,
                        "msg" => "请登录",
                        "data" => []
                    ]);
                    exit;
                }
                $this->user = $user;
            }
        } catch (\ReflectionException $e) {
            back("映射失败 (方法未定义)", 0, [
                "controller" => $controller,
                "action" => $action
            ]);
            return;
        }
    }

    /**
     * 接口返回数据
     *
     * @param string $msg
     * @param int $code
     * @param null $data
     * @param array $headers
     *
     * @return Json
     */
    public static function resp(string $msg, int $code = 0, $data = null, array $headers = []): Json
    {
        $backdata = [
            'msg' => $msg,
            'code' => $code,
            'data' => $data,
            't' => date('Y-m-dTH:i:s', time()),
        ];
        return json($backdata, 200, $headers);
    }

    public static function RespError($e, $msg = '意外错误'): Json
    {
        return self::resp($msg, 0, ['e' => $e->getMessage(), 'l' => $e->getLine(), 'f' => $e->getFile()]);
    }

    /**
     * 获取配置
     * @param $search_data id|name 配置id或者name
     * @return float|mixed|string
     */
    protected static function C($search_data)
    {
        $field = "name";
        if (is_numeric($search_data)) {
            $field = "id";
        }
        return Db::name('config')->where($field, $search_data)->value('value');
    }


}
