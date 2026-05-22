<?php

namespace WiseTrap\App\Models;

use PDO;
use WiseTrap\Core\Application;

class AttackersModel extends DbModel
{
    public static function tableName(): string
    {
        return 'Attackers';
    }
    public static function primaryKey(): string
    {
        return 'attacker_id';
    }
    public function attributes(): array
    {
        return [];
    }
    public function getAllAttackers(): array
    {
        $pdo = Application::$app->database->pdo;
        $sql = "SELECT a.attacker_id, a.ip_address, a.user_agent, a.first_seen, a.last_seen,
            (SELECT te.endpoint_name FROM AttackLogs al LEFT JOIN TrapEndpoints te ON te.endpoint_id = al.endpoint_id
                WHERE al.attacker_id = a.attacker_id ORDER BY al.timestamp DESC LIMIT 1
            ) AS endpoint_name FROM Attackers a ORDER BY a.last_seen DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function rules(): array
    {
        // TODO: Implement rules() method.
    }
}