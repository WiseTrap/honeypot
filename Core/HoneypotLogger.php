<?php

namespace WiseTrap\Core;

use PDO;

class HoneypotLogger
{
    /**
     * --------------------------------------------------------------------------
     * SECURITY NOTICE
     * --------------------------------------------------------------------------
     *
     * This honeypot logging layer is intentionally minimal in v1.
     *
     * Future versions should implement:
     *
     * - Input filtering and sanitization
     * - Request payload validation
     * - User-Agent normalization
     * - Safe logging limits
     * - Binary/content filtering
     * - Exception-safe logging
     * - Rate limiting protections
     * - Log storage hardening
     * - Malicious payload isolation
     *
     * Current implementation is intended for the first stable release only.
     * Additional security hardening will be introduced in future versions.
     * --------------------------------------------------------------------------
     */
    public static function log(): void
    {
        $user = Application::$app->user;
        if (!$user) {
            return;
        }
        $pdo    = Application::$app->database->pdo;
        $stmt   = $pdo->prepare("SELECT ug.trap_endpoint_id, te.endpoint_name FROM Users_Groups ug LEFT JOIN TrapEndpoints te ON te.endpoint_id = ug.trap_endpoint_id WHERE ug.GroupId = ? LIMIT 1");
        $stmt->execute([$user->GroupId]);
        $group  = $stmt->fetch(PDO::FETCH_ASSOC);
        if (empty($group['trap_endpoint_id'])) {
            return;
        }
        $endpointId = (int) $group['trap_endpoint_id'];
        $ip         = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $userAgent  = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        $method     = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        /*
         * Find attacker
         */
        $stmt       = $pdo->prepare("SELECT attacker_id FROM Attackers WHERE ip_address = ? AND user_agent = ? LIMIT 1");
        $stmt->execute([$ip, $userAgent]);
        $attacker   = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$attacker) {
            $stmt       = $pdo->prepare("INSERT INTO Attackers (ip_address, user_agent, first_seen, last_seen) VALUES (?, ?, NOW(), NOW())");
            $stmt->execute([$ip, $userAgent]);
            $attackerId = (int) $pdo->lastInsertId();
        } else {
            $attackerId = (int) $attacker['attacker_id'];
            $stmt       = $pdo->prepare("UPDATE Attackers SET last_seen = NOW() WHERE attacker_id = ?");
            $stmt->execute([$attackerId]);
        }
        /*
         * Insert attack log
         */
        $stmt = $pdo->prepare("INSERT INTO AttackLogs (attacker_id, endpoint_id, requested_url, timestamp, http_method, status_code, request_data, response_data) VALUES (?, ?, ?, NOW(), ?, ?, ?, ?)");
        $stmt->execute([$attackerId, $endpointId, $requestUri, $method, http_response_code(), json_encode(['GET'  => $_GET, 'POST' => $_POST], JSON_UNESCAPED_UNICODE), null]);
    }
}