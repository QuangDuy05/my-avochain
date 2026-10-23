<?php
// Temporary router used only for the share tunnel to the original PHP app.
// Keep development tools and configuration examples off the public origin.
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$relativePath = rawurldecode(ltrim($requestPath, '/'));
$normalizedPath = str_replace('\\', '/', $relativePath);
$segments = array_map('strtolower', explode('/', $normalizedPath));
$extension = strtolower(pathinfo($normalizedPath, PATHINFO_EXTENSION));
$blockedNames = ['config.php', 'gen_hash.php', 'update_hash.php', 'original_app_router.php'];

if (str_contains($normalizedPath, "\0")
    || in_array('..', $segments, true)
    || in_array('tools', $segments, true)
    || in_array(strtolower(basename($normalizedPath)), $blockedNames, true)
    || in_array($extension, ['md', 'log', 'sql', 'env', 'ini'], true)
    || array_filter($segments, static fn($segment) => str_starts_with($segment, '.'))) {
    http_response_code(404);
    exit('Not found');
}

return false;
