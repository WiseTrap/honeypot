<?php

namespace WiseTrap\App\Models;

use PDO;
use WiseTrap\Core\Application;

class DashboardModel
{
    public function stats(): array
    {
        $pdo = Application::$app->database->pdo;
        return [
            'attackers' => $this->count($pdo, 'Attackers'),
            'logs'      => $this->count($pdo, 'AttackLogs'),
            'traps'     => $this->count($pdo, 'TrapEndpoints'),
            'bots'      => $this->countBots($pdo),
        ];
    }
    private function count(PDO $pdo, string $table): int
    {
        $stmt = $pdo->query("SELECT COUNT(*) FROM {$table}");
        return (int) $stmt->fetchColumn();
    }
    private function countBots(PDO $pdo): int
    {
        $stmt = $pdo->query("SELECT COUNT(*) FROM Attackers WHERE is_bot = 1");
        return (int) $stmt->fetchColumn();
    }
}