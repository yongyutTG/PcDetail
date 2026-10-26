<?php
namespace App\Libraries;

final class ScanRange
{
    public static function addresses($base, $start, $end): array
    {
        if (!is_string($base) || !preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}$/D', $base)
            || filter_var($base . '.1', FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false
            || !is_scalar($start) || !is_scalar($end)
            || filter_var($start, FILTER_VALIDATE_INT) === false
            || filter_var($end, FILTER_VALIDATE_INT) === false) {
            throw new \InvalidArgumentException('Invalid IPv4 range');
        }
        $start = (int) $start;
        $end = (int) $end;
        if ($start < 1 || $end > 254 || $start > $end || $end - $start + 1 > 30) {
            throw new \InvalidArgumentException('Scan 1-30 addresses between 1 and 254 per request');
        }
        return array_map(static function ($host) use ($base) {
            return $base . '.' . $host;
        }, range($start, $end));
    }
}
