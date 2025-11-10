<?php

if (!function_exists('url_to')) {
    function url_to(string $path = ''): string
    {
        $baseValue = defined('APP_BASE_PATH') ? APP_BASE_PATH : '';
        if ($baseValue === '/' || $baseValue === '.') {
            $baseValue = '';
        }

        $trimmedPath = ltrim($path, '/');
        $isAbsolute = is_string($baseValue) && ($baseValue !== '' && (str_contains($baseValue, '://') || str_starts_with($baseValue, '//')));

        if ($isAbsolute) {
            $normalizedBase = rtrim($baseValue, '/');
            if ($trimmedPath === '') {
                return $normalizedBase;
            }

            return $normalizedBase . '/' . $trimmedPath;
        }

        $base = $baseValue === '' ? '' : '/' . ltrim((string) $baseValue, '/');
        $base = rtrim($base, '/');

        if ($trimmedPath === '') {
            return $base === '' ? '/' : $base;
        }

        if ($base === '') {
            return '/' . $trimmedPath;
        }

        return $base . '/' . $trimmedPath;
    }
}

if (!function_exists('asset_url')) {
    function asset_url(string $path): string
    {
        return url_to($path);
    }
}
