<?php
use App\Filters\AdminAuth;
use App\Filters\JWTAuth;
use App\Libraries\ScanRange;
use App\Models\Pc\UserModel;
use App\Controllers\Pc\JwtController;
use CodeIgniter\Config\Factories;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Services;
use Firebase\JWT\JWT;

final class SecurityTest extends CIUnitTestCase
{
    private $savedEnv = [];

    protected function setUp(): void
    {
        parent::setUp();
        foreach (['JWT_SECRET_KEY' => 'unit-test-access-key-not-for-production',
            'JWT_REFRESH_SECRET_KEY' => 'unit-test-refresh-key-not-for-production',
            'PCDETAIL_ADMIN_USERS' => 'it0007', 'SESSION_IDLE_TIMEOUT' => '600'] as $name => $value) {
            $this->savedEnv[$name] = getenv($name);
            putenv($name . '=' . $value);
            $_ENV[$name] = $value;
        }
    }

    protected function tearDown(): void
    {
        foreach ($this->savedEnv as $name => $value) {
            putenv($value === false ? $name : $name . '=' . $value);
            unset($_ENV[$name]);
        }
        Factories::reset('models');
        parent::tearDown();
    }

    private function user(array $row): void
    {
        $model = $this->getMockBuilder(UserModel::class)->disableOriginalConstructor()
            ->onlyMethods(['find'])->getMock();
        $model->method('find')->willReturn($row);
        Factories::injectMock('models', UserModel::class, $model);
    }

    private function loggedIn(bool $loggedIn = true): void
    {
        $session = $this->getMockBuilder(\CodeIgniter\Session\Session::class)
            ->disableOriginalConstructor()->onlyMethods(['get', 'set', 'destroy'])->getMock();
        $values = ['logged_in' => $loggedIn, 'USER_ID' => 7, 'last_activity' => time()];
        $session->method('get')->willReturnCallback(static function ($key) use ($values) {
            return $values[$key] ?? null;
        });
        Services::injectMock('session', $session);
    }

    public function testScanRejectsShellInputAndOversizedRanges(): void
    {
        foreach ([['127.0.0;echo unsafe', '1', '1'], ['999.1.1', '1', '1'],
            ['127.0.0', '1', '254'], ['127.0.0', '2', '1'], ['127.0.0', [], '1'],
            ['127.0.0', '-1', '1'], ['127.0.0', '1.5', '2']] as $input) {
            try {
                ScanRange::addresses(...$input);
                $this->fail('Invalid scan input was accepted');
            } catch (\InvalidArgumentException $e) {
                $this->assertNotEmpty($e->getMessage());
            }
        }
        $this->assertSame(['192.168.1.1', '192.168.1.2'], ScanRange::addresses('192.168.1', '1', '2'));
    }

    public function testAdminRejectsGuest(): void
    {
        $this->loggedIn(false);
        $response = (new AdminAuth())->before(Services::request());
        $this->assertSame(302, $response->getStatusCode());
    }

    public function testAdminRejectsOrdinaryAndInactiveAccounts(): void
    {
        $this->loggedIn();
        foreach ([['USER_NAME' => 'ordinary', 'IS_ACTIVE' => '1'],
            ['USER_NAME' => 'it0007', 'IS_ACTIVE' => '0']] as $row) {
            $this->user($row);
            $this->assertSame(403, (new AdminAuth())->before(Services::request())->getStatusCode());
        }
    }

    public function testAdminAllowsConfiguredActiveAccount(): void
    {
        $this->loggedIn();
        $this->user(['USER_NAME' => 'it0007', 'IS_ACTIVE' => '1']);
        $this->assertNull((new AdminAuth())->before(Services::request()));
    }

    public function testRefreshRejectsInactiveAccount(): void
    {
        $this->user(['USER_ID' => 7, 'USER_NAME' => 'test', 'IS_ACTIVE' => '0']);
        $token = JWT::encode(['type' => 'refresh', 'exp' => time() + 60, 'data' => ['id' => 7]],
            getenv('JWT_REFRESH_SECRET_KEY'), 'HS256');
        $request = Services::request();
        $request->setBody(json_encode(['refresh_token' => $token]));
        $controller = new JwtController();
        $controller->initController($request, Services::response(), Services::logger());
        $this->assertSame(401, $controller->refresh()->getStatusCode());
    }

    public function testAccessTokenRejectsInactiveAccount(): void
    {
        $this->user(['USER_ID' => 7, 'IS_ACTIVE' => '0']);
        $token = JWT::encode(['type' => 'access', 'exp' => time() + 60, 'data' => ['id' => 7]],
            getenv('JWT_SECRET_KEY'), 'HS256');
        $request = Services::request();
        $request->setHeader('Authorization', 'Bearer ' . $token);
        $this->assertSame(401, (new JWTAuth())->before($request)->getStatusCode());
    }

    public function testRefreshTokenCannotBeUsedAsAccessToken(): void
    {
        $token = JWT::encode(['type' => 'refresh', 'exp' => time() + 60, 'data' => ['id' => 7]],
            getenv('JWT_SECRET_KEY'), 'HS256');
        $request = Services::request();
        $request->setHeader('Authorization', 'Bearer ' . $token);
        $this->assertSame(401, (new JWTAuth())->before($request)->getStatusCode());
    }

    public function testAccessTokenAllowsActiveAccount(): void
    {
        $this->user(['USER_ID' => 7, 'IS_ACTIVE' => '1']);
        $token = JWT::encode(['type' => 'access', 'exp' => time() + 60, 'data' => ['id' => 7]],
            getenv('JWT_SECRET_KEY'), 'HS256');
        $request = Services::request();
        $request->setHeader('Authorization', 'Bearer ' . $token);
        $this->assertNull((new JWTAuth())->before($request));
    }

    public function testActiveAccountCanRefreshAccessToken(): void
    {
        $this->user(['USER_ID' => 7, 'USER_NAME' => 'test', 'IS_ACTIVE' => '1']);
        $token = JWT::encode(['type' => 'refresh', 'exp' => time() + 60, 'data' => ['id' => 7]],
            getenv('JWT_REFRESH_SECRET_KEY'), 'HS256');
        $request = Services::request();
        $request->setBody(json_encode(['refresh_token' => $token]));
        $controller = new JwtController();
        $controller->initController($request, Services::response(), Services::logger());
        $response = $controller->refresh();
        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody(), true);
        $decoded = JWT::decode($body['access_token'], new \Firebase\JWT\Key(getenv('JWT_SECRET_KEY'), 'HS256'));
        $this->assertSame('access', $decoded->type);
        $this->assertSame(7, $decoded->data->id);
    }
}
