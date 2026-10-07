<?php
/**
 * Application Rate Limiter (File-based with flock)
 * PNMEC - Công Ty CP Cơ Khí & Xây Dựng
 */

/**
 * Check if the given action/client has exceeded the rate limit.
 *
 * @param string $action Action key (e.g. 'login', 'contact', 'quote', 'upload')
 * @param int $maxAttempts Maximum allowed attempts in the time window
 * @param int $windowSeconds Time window in seconds (e.g. 300 for 5 minutes)
 * @param string|null $identifier Client identifier (defaults to REMOTE_ADDR)
 * @return array ['allowed' => bool, 'remaining' => int, 'retry_after' => int]
 */
function check_rate_limit(string $action, int $maxAttempts = 10, int $windowSeconds = 300, ?string $identifier = null): array {
    if ($identifier === null) {
        $identifier = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }

    $storageDir = dirname(__DIR__) . '/storage/rate-limit';
    if (!is_dir($storageDir)) {
        @mkdir($storageDir, 0755, true);
    }

    $hash = hash('sha256', $action . '_' . $identifier);
    $filePath = $storageDir . '/' . $hash . '.json';
    $now = time();

    $fp = @fopen($filePath, 'c+');
    if (!$fp) {
        // Fail-open gracefully if file system is temporarily unwriteable
        return ['allowed' => true, 'remaining' => $maxAttempts, 'retry_after' => 0];
    }

    $data = [
        'attempts'   => 0,
        'reset_time' => $now + $windowSeconds,
    ];

    if (flock($fp, LOCK_EX)) {
        $size = filesize($filePath);
        if ($size > 0) {
            $raw = fread($fp, $size);
            $parsed = json_decode($raw, true);
            if (is_array($parsed) && isset($parsed['reset_time'])) {
                if ($now < $parsed['reset_time']) {
                    $data = $parsed;
                }
            }
        }

        $data['attempts']++;

        if ($data['attempts'] > $maxAttempts) {
            $retryAfter = max(1, $data['reset_time'] - $now);
            flock($fp, LOCK_UN);
            fclose($fp);
            return [
                'allowed'     => false,
                'remaining'   => 0,
                'retry_after' => $retryAfter,
            ];
        }

        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($data));
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);

        return [
            'allowed'     => true,
            'remaining'   => max(0, $maxAttempts - $data['attempts']),
            'retry_after' => 0,
        ];
    }

    fclose($fp);
    return ['allowed' => true, 'remaining' => $maxAttempts, 'retry_after' => 0];
}

/**
 * Enforce rate limit for API endpoints.
 * Automatically responds with HTTP 429 and exits if limit exceeded.
 */
function enforce_rate_limit_api(string $action, int $maxAttempts = 10, int $windowSeconds = 300, ?string $identifier = null): void {
    $result = check_rate_limit($action, $maxAttempts, $windowSeconds, $identifier);
    if (!$result['allowed']) {
        $retryAfter = $result['retry_after'] ?? 60;
        header('Retry-After: ' . $retryAfter);
        http_response_code(429);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode([
            'success'     => false,
            'message'     => 'Bạn đã gửi yêu cầu quá nhiều lần. Vui lòng thử lại sau ' . $retryAfter . ' giây.',
            'retry_after' => $retryAfter,
            'timestamp'   => date('Y-m-d H:i:s'),
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
}
