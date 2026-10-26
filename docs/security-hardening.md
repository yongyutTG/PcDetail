# Security hardening

- Auto Routing is disabled. Declare future endpoints explicitly with filters.
- Admin authorization checks a fresh active database row and PCDETAIL_ADMIN_USERS (comma-separated usernames). There is no default administrator: configure PCDETAIL_ADMIN_USERS in .env on each server. Client GROUP_ID does not grant admin access.
- User responses exclude password hashes. User edits cannot modify passwords, SUP_ADMIN or client-supplied audit metadata.
- Scan accepts validated IPv4 ranges, at most 30 hosts per request (1-254), with shell argument escaping.
- Both access authentication and refresh reject inactive accounts. Refresh tokens cannot be used as access tokens.
- HTML rendering escapes API data; mutating browser requests retain CSRF protection.
- Set email.SMTPPass on every deployment. The local credential was moved to ignored .env without printing it. Rotate it at the mail provider; source removal does not erase Git history.
- Apache denies .env via .htaccess. Nginx ignores .htaccess: deny dotfiles including .env and .git in its server configuration before deployment.
- Refresh-token rotation and per-device revocation are outside this patch. Tokens remain subject to expiration and the active-account check.

Validation without production database writes:

    php vendor/bin/phpunit --no-coverage --no-logging --do-not-cache-result tests/unit/SecurityTest.php
    node tests/security/rendering.test.js
    php spark routes

Live login, SMTP delivery and database-backed admin workflows still require a staging smoke test.
