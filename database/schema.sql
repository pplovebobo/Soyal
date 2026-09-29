CREATE TABLE IF NOT EXISTS events (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    occurred_at TEXT NOT NULL,
    station_no INTEGER NOT NULL,
    station_name TEXT NOT NULL DEFAULT '',
    card_no TEXT NOT NULL DEFAULT '',
    person_name TEXT NOT NULL DEFAULT '',
    dept1 TEXT NOT NULL DEFAULT '',
    dept2 TEXT NOT NULL DEFAULT '',
    event_type TEXT NOT NULL DEFAULT '',
    door_name TEXT NOT NULL DEFAULT '',
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_events_occurred_at ON events(occurred_at);
CREATE INDEX IF NOT EXISTS idx_events_station_no ON events(station_no);
CREATE INDEX IF NOT EXISTS idx_events_card_no ON events(card_no);
