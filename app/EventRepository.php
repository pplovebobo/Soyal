<?php

declare(strict_types=1);

final class EventRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function search(array $filters = [], int $limit = 100, int $offset = 0): array
    {
        $where = [];
        $params = [];

        if (!empty($filters['date'])) {
            $where[] = 'date(occurred_at) = :date';
            $params[':date'] = $filters['date'];
        }

        if (!empty($filters['station'])) {
            $where[] = 'station_no = :station';
            $params[':station'] = (int) $filters['station'];
        }

        if (!empty($filters['keyword'])) {
            $where[] = '(card_no LIKE :keyword OR person_name LIKE :keyword OR station_name LIKE :keyword)';
            $params[':keyword'] = '%' . $filters['keyword'] . '%';
        }

        $sql = 'SELECT * FROM events';
        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $sql .= ' ORDER BY occurred_at ASC, id ASC LIMIT :limit OFFSET :offset';

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function count(array $filters = []): int
    {
        $where = [];
        $params = [];

        if (!empty($filters['date'])) {
            $where[] = 'date(occurred_at) = :date';
            $params[':date'] = $filters['date'];
        }

        if (!empty($filters['station'])) {
            $where[] = 'station_no = :station';
            $params[':station'] = (int) $filters['station'];
        }

        if (!empty($filters['keyword'])) {
            $where[] = '(card_no LIKE :keyword OR person_name LIKE :keyword OR station_name LIKE :keyword)';
            $params[':keyword'] = '%' . $filters['keyword'] . '%';
        }

        $sql = 'SELECT COUNT(*) FROM events';
        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function seedDemoData(): void
    {
        if ($this->count() > 0) {
            return;
        }

        $rows = [
            ['08:05:57', 7, '007-17-三廠上班考勤', '0494', '張欣凰', '位元', '人資課'],
            ['08:07:00', 1, '001-17-廠大廳上班', '0462', '洪西茹', '位元', '人資課'],
            ['08:08:32', 7, '007-17-三廠上班考勤', '0143', '陳曉蓉', '位元', '人資課'],
            ['08:11:50', 7, '007-17-三廠上班考勤', '0214', '徐美鈴', '位元', '人資課'],
            ['08:12:21', 7, '007-17-三廠上班考勤', '0075', '柯芳妏', '位元', '人資課'],
            ['08:13:25', 1, '001-17-廠大廳上班', '0421', '劉郁雯', '位元', '人資課'],
            ['08:15:42', 7, '007-17-三廠上班考勤', '0252', '林鴻全', '位元', '人資課'],
            ['08:16:04', 7, '007-17-三廠上班考勤', '0441', '游振寰', '位元', '人資課'],
            ['08:18:27', 7, '007-17-三廠上班考勤', '0014', '徐國凱', '位元', '人資課'],
            ['08:18:30', 7, '007-17-三廠上班考勤', '0137', '陳冠章', '位元', '人資課'],
            ['08:18:34', 7, '007-17-三廠上班考勤', '0173', '張裕祥', '位元', '人資課'],
            ['08:18:37', 7, '007-17-三廠上班考勤', '0173', '張裕祥', '位元', '人資課'],
        ];

        $stmt = $this->pdo->prepare(
            'INSERT INTO events (occurred_at, station_no, station_name, card_no, person_name, dept1, dept2, event_type, door_name)
             VALUES (:occurred_at, :station_no, :station_name, :card_no, :person_name, :dept1, :dept2, :event_type, :door_name)'
        );

        $date = date('Y-m-d');
        foreach ($rows as $row) {
            $stmt->execute([
                ':occurred_at' => $date . ' ' . $row[0],
                ':station_no' => $row[1],
                ':station_name' => $row[2],
                ':card_no' => $row[3],
                ':person_name' => $row[4],
                ':dept1' => $row[5],
                ':dept2' => $row[6],
                ':event_type' => '刷卡',
                ':door_name' => '',
            ]);
        }
    }
}
