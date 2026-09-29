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

CREATE TABLE IF NOT EXISTS controllers (
    station_no INTEGER PRIMARY KEY,
    model TEXT NOT NULL,
    ip_enabled INTEGER NOT NULL DEFAULT 1,
    selected INTEGER NOT NULL DEFAULT 0,
    ip_address TEXT NOT NULL DEFAULT '',
    port INTEGER NOT NULL DEFAULT 0,
    lan_base TEXT NOT NULL DEFAULT 'AR-7xx/8xxE',
    display_group INTEGER NOT NULL DEFAULT 0,
    last_status TEXT NOT NULL DEFAULT 'unknown',
    last_checked_at TEXT
);

CREATE INDEX IF NOT EXISTS idx_controllers_ip ON controllers(ip_address, port);
