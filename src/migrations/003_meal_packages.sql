-- Tabela de pacotes de refeições
CREATE TABLE IF NOT EXISTS meal_packages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    name TEXT NOT NULL,
    type TEXT NOT NULL, -- 'day', 'week', 'month'
    start_date TEXT NOT NULL,
    end_date TEXT NOT NULL,
    status TEXT NOT NULL DEFAULT 'pending', -- 'pending', 'active', 'completed', 'canceled'
    preferences TEXT, -- JSON com preferências (para pacotes semanais e mensais)
    meal_count INTEGER DEFAULT 0,
    total_price REAL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Tabela de refeições do pacote
CREATE TABLE IF NOT EXISTS package_meals (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    package_id INTEGER NOT NULL,
    meal_id INTEGER NOT NULL,
    delivery_date TEXT NOT NULL,
    meal_type TEXT NOT NULL, -- 'breakfast', 'lunch', 'dinner', 'snack'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (package_id) REFERENCES meal_packages(id),
    FOREIGN KEY (meal_id) REFERENCES meals(id)
);

-- Adicionar campo package_id à tabela meal_orders
ALTER TABLE meal_orders ADD COLUMN package_id INTEGER;