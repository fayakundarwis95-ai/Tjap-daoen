<?php
function maps_resolve_short_link($url)
{
    $parts = parse_url($url);
    $host = strtolower($parts['host'] ?? '');
    if (!in_array($host, ['maps.app.goo.gl', 'goo.gl'], true)) {
        return $url;
    }

    if (function_exists('curl_init')) {
        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 8,
            CURLOPT_USERAGENT => 'Mozilla/5.0',
            CURLOPT_PROTOCOLS => CURLPROTO_HTTPS,
            CURLOPT_REDIR_PROTOCOLS => CURLPROTO_HTTPS,
        ]);
        curl_exec($curl);
        $resolved_url = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
        $success = curl_errno($curl) === 0;
        curl_close($curl);
    } elseif (function_exists('proc_open')) {
        $windows_dir = getenv('WINDIR') ?: 'C:\\Windows';
        $curl_binary = rtrim($windows_dir, '\\/') . '\\System32\\curl.exe';
        if (!is_file($curl_binary)) {
            return null;
        }

        $process = proc_open([
            $curl_binary,
            '--silent',
            '--location',
            '--max-redirs', '5',
            '--proto', '=https',
            '--proto-redir', '=https',
            '--output', 'NUL',
            '--write-out', '%{url_effective}',
            $url,
        ], [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        if (!is_resource($process)) {
            return null;
        }

        $resolved_url = stream_get_contents($pipes[1]);
        stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $success = proc_close($process) === 0;
    } else {
        return null;
    }

    if (!$success) {
        return null;
    }

    $resolved_host = strtolower(parse_url($resolved_url, PHP_URL_HOST) ?? '');
    return in_array($resolved_host, ['google.com', 'www.google.com', 'maps.google.com'], true)
        ? $resolved_url
        : null;
}

function maps_normalize_embed_url($url, $address = '')
{
    $url = trim((string) $url);
    if ($url === '') {
        return null;
    }

    $resolved_url = maps_resolve_short_link($url);
    if ($resolved_url === null) {
        return null;
    }
    $url = $resolved_url;

    $parts = parse_url($url);
    $host = strtolower($parts['host'] ?? '');
    $is_google_maps = in_array($host, ['google.com', 'www.google.com', 'maps.google.com'], true);
    if (($parts['scheme'] ?? '') !== 'https' || !$is_google_maps) {
        return null;
    }

    $path = $parts['path'] ?? '';
    if (strpos($path, '/maps/embed') === 0) {
        return $url;
    }

    parse_str($parts['query'] ?? '', $query);
    if (strpos($path, '/maps') === 0 && ($query['output'] ?? '') === 'embed') {
        return $url;
    }

    $target = $query['q'] ?? $query['query'] ?? '';
    if ($target === '' && preg_match('~!3d(-?[\d.]+)!4d(-?[\d.]+)~', $url, $coordinates)) {
        $target = $coordinates[1] . ',' . $coordinates[2];
    }
    if ($target === '' && preg_match('~/@(-?[\d.]+),(-?[\d.]+)~', $path, $coordinates)) {
        $target = $coordinates[1] . ',' . $coordinates[2];
    }
    if ($target === '' && preg_match('~/maps/place/([^/]+)~', $path, $place)) {
        $target = urldecode(str_replace('+', ' ', $place[1]));
    }
    if ($target === '') {
        $target = trim($address);
    }
    if ($target === '') {
        return null;
    }

    return 'https://www.google.com/maps?q=' . rawurlencode($target) . '&output=embed';
}

function maps_embed_url($url, $address = '')
{
    $embed_url = maps_normalize_embed_url($url, $address);
    if ($embed_url !== null) {
        return $embed_url;
    }

    $fallback_query = trim($address) !== '' ? trim($address) : 'Bondowoso, Jawa Timur';
    return 'https://www.google.com/maps?output=embed&q=' . rawurlencode($fallback_query);
}