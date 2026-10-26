<?php
namespace App\Libraries;

final class AdminAccess
{
    public static function allows(string $username): bool
    {
        $admins = array_filter(array_map('trim', explode(',', (string) env('PCDETAIL_ADMIN_USERS', ''))));
        return in_array(strtolower($username), array_map('strtolower', $admins), true);
    }
}
