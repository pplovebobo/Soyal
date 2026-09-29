<?php

declare(strict_types=1);

final class ControllerRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function all(): array
    {
        return $this->pdo->query(
            'SELECT * FROM controllers ORDER BY station_no'
        )->fetchAll();
    }

    public function find(int $stationNo): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM controllers WHERE station_no = :station');
        $stmt->execute([':station' => $stationNo]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function updateStatus(int $stationNo, string $status): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE controllers SET last_status = :status, last_checked_at = :checked_at
             WHERE station_no = :station'
        );
        $stmt->execute([
            ':status' => $status,
            ':checked_at' => date('Y-m-d H:i:s'),
            ':station' => $stationNo,
        ]);
    }

    public function seedFromLegacyScreens(): void
    {
        if ((int) $this->pdo->query('SELECT COUNT(*) FROM controllers')->fetchColumn() > 0) {
            return;
        }

        $rows = [
            [0,  '721/757/737H V3', 0, 0, '',              0],
            [1,  '881E/82xEv5/725Ev2/727E', 1, 1, '192.168.31.47', 2500],
            [2,  '881E/82xEv5/725Ev2/727E', 1, 1, '192.168.31.47', 2500],
            [3,  '881E/82xEv5/725Ev2/727E', 0, 1, '',              2500],
            [4,  '881E/82xEv5/725Ev2/727E', 0, 1, '',              2500],
            [5,  '881E/82xEv5/725Ev2/727E', 0, 1, '',              2500],
            [6,  '881E/82xEv5/725Ev2/727E', 0, 1, '',              2500],
            [7,  '881E/82xEv5/725Ev2/727E', 1, 1, '192.168.3.46',  2500],

            [8,  '881E/82xEv5/725Ev2/727E', 1, 1, '192.168.3.46',  2500],
            [9,  '881E/82xEv5/725Ev2/727E', 1, 1, '192.168.31.26', 1623],
            [10, '725H/321H/327H 3K',        1, 1, '192.168.31.47', 2500],
            [11, '881E/82xEv5/725Ev2/727E', 1, 1, '192.168.200.10',1621],
            [12, '721/757/737H V3',          1, 1, '192.168.3.46',  2500],
            [13, '725H/321H/327H 3K',        1, 1, '192.168.31.47', 2500],
            [14, '725H/321H/327H 3K',        1, 1, '192.168.31.47', 2500],
            [15, '725H/321H/327H 3K',        1, 1, '192.168.31.26', 1621],

            [16, '721/757/737H V3',          1, 1, '192.168.31.26', 1621],
            [17, '881E/82xEv5/725Ev2/727E', 1, 1, '192.168.31.26', 1621],
            [18, '725H/321H/327H 3K',        1, 1, '192.168.31.26', 1623],
            [19, '721/757/737H V3',          1, 1, '192.168.31.26', 1621],
            [20, '721/757/737H V3',          1, 1, '192.168.31.26', 1623],
            [21, '721/757/737H V3',          1, 1, '192.168.31.26', 1623],
            [22, '725H/321H/327H 3K',        1, 0, '192.168.31.26', 1621],
            [23, '725H/321H/327H 3K',        1, 1, '192.168.31.26', 1621],

            [24, '881E/82xEv5/725Ev2/727E', 1, 1, '192.168.31.26', 1623],
            [25, '725H/321H/327H 3K',        1, 1, '192.168.3.46',  2500],
            [26, '721/757/737H V3',          1, 1, '192.168.3.46',  2500],
            [27, '721/757/737H V3',          1, 1, '192.168.3.46',  2500],
            [28, '721/757/737H V3',          1, 1, '192.168.3.46',  2500],
            [29, '721/757/737H V3',          1, 1, '192.168.3.46',  2500],
            [30, '725H/321H/327H 3K',        1, 1, '192.168.3.46',  2500],
            [31, '721/757/737H V3',          1, 1, '192.168.3.46',  2500],

            [32, '721/757/737H V3',          1, 1, '192.168.3.46',  2500],
            [33, '725H/321H/327H 3K',        1, 1, '192.168.3.47',  2500],
            [34, '721/757/737H V3',          1, 1, '192.168.3.47',  2500],
            [35, '721/757/737H V3',          1, 1, '192.168.3.47',  2500],
            [36, '881E/82xEv5/725Ev2/727E', 1, 0, '192.168.3.47',  2500],
            [37, '721/757/737H V3',          1, 0, '0.0.0.0',        2500],
            [38, '725H/321H/327H 3K',        1, 1, '192.168.3.46',  2500],
            [39, '725H/321H/327H 3K',        1, 0, '192.168.3.46',  2500],

            [40, '725H/321H/327H 3K',        1, 1, '192.168.31.47', 2500],
            [41, '721/757/737H V3',          1, 0, '192.168.200.10',1621],
            [42, '721/757/737H V3',          1, 0, '192.168.200.10',1621],
            [43, '721/757/737H V3',          1, 0, '192.168.200.10',1621],
            [44, '725H/321H/327H 3K',        1, 1, '192.168.31.26', 1623],
            [45, '725H/321H/327H 3K',        1, 0, '192.168.31.47', 2500],
            [46, '721/757/737H V3',          1, 0, '192.168.200.10',1621],
            [47, '721/757/737H V3',          1, 0, '192.168.200.10',1621],

            [48, '727/747H V3',              0, 0, '',              0],
            [49, '725H/321H/327H 3K',        1, 1, '192.168.3.47',  2500],
            [50, '725H/321H/327H 3K',        1, 1, '192.168.3.47',  2500],
            [51, '727/747H V3',              0, 0, '',              0],
            [52, '727/747H V3',              0, 0, '',              0],
            [53, '727/747H V3',              0, 0, '',              0],
            [54, '727/747H V3',              0, 0, '',              0],
            [55, '727/747H V3',              0, 0, '',              0],
        ];

        $stmt = $this->pdo->prepare(
            'INSERT INTO controllers
             (station_no, model, ip_enabled, selected, ip_address, port, lan_base, display_group)
             VALUES (:station, :model, :ip_enabled, :selected, :ip, :port, :lan_base, :group)'
        );

        foreach ($rows as [$station, $model, $ipEnabled, $selected, $ip, $port]) {
            $stmt->execute([
                ':station' => $station,
                ':model' => $model,
                ':ip_enabled' => $ipEnabled,
                ':selected' => $selected,
                ':ip' => $ip,
                ':port' => $port,
                ':lan_base' => 'AR-7xx/8xxE',
                ':group' => intdiv($station, 8),
            ]);
        }
    }
}
