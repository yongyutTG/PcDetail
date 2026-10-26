<?php
namespace App\Filters;

use App\Models\Pc\UserModel;
use CodeIgniter\HTTP\RequestInterface;

/** Server-side authorization for the existing administrator account. */
class AdminAuth extends SessionTimeoutFilter
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $response = parent::before($request, $arguments);
        if ($response !== null) {
            return $response;
        }
        $user = model(UserModel::class)->find(session()->get('USER_ID'));
        if (!$user || (string) ($user['IS_ACTIVE'] ?? '') !== '1'
            || !\App\Libraries\AdminAccess::allows($user['USER_NAME'] ?? '')) {
            return service('response')->setStatusCode(403)
                ->setJSON(['status' => 'error', 'message' => 'Administrator permission required']);
        }
    }
}
