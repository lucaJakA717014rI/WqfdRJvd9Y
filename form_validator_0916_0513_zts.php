<?php
// 代码生成时间: 2025-09-16 05:13:07
// 使用Slim框架的表单数据验证器
// 遵循PHP最佳实践，确保代码的可维护性和可扩展性

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\AllOfException;
use Respect\Validation\Exceptions\NestedValidationException;

require __DIR__ . '/../vendor/autoload.php'; // 引入自动加载文件

// 定义表单验证规则
$formRules = [
    'name' => v::notEmpty()->alpha(' ')->length(2, 50),
    'email' => v::email(),
    'age' => v::numeric()->positive(),
    'terms' => v::eq('on')
];

// 错误处理函数
function handleValidationErrors($e) {
    $errors = [];

    if ($e instanceof NestedValidationException) {
        foreach ($e->findMessages(array_keys($formRules)) as $input => $message) {
            $errors[$input] = $message;
        }
    } elseif ($e instanceof AllOfException) {
        $errors[$e->getMainPropertyName()] = $e->getMainMessage();
    }

    return json_encode(['errors' => $errors]);
}

// 创建Slim应用
$app = AppFactory::create();

// 添加表单验证路由
$app->post('/validate-form', function (Request $request, Response $response, $args) {
    $data = $request->getParsedBody();

    try {
        // 验证表单数据
        v::with('App\Validator\Rules', __DIR__ . '/src/Validator/Rules/')
            ->assert($data, $formRules);

        // 如果验证通过，返回成功响应
        return $response->withJson(['success' => 'Form validated successfully'], 200);
    } catch (NestedValidationException $e) {
        // 如果存在嵌套验证异常，处理错误
        return $response->withStatus(422)->withJson(handleValidationErrors($e));
    } catch (AllOfException $e) {
        // 如果存在通用验证异常，处理错误
        return $response->withStatus(422)->withJson(handleValidationErrors($e));
    } catch (Exception $e) {
        // 其他异常处理
        return $response->withStatus(500)->withJson(['error' => 'Internal server error']);
    }
});

// 运行应用
$app->run();