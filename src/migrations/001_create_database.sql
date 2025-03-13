-- Users table
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    role TEXT NOT NULL DEFAULT 'user', -- 'admin', 'nutritionist', 'restaurant', 'user'
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- User profiles table
CREATE TABLE user_profiles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    birth_date DATE,
    gender TEXT,
    height REAL, -- in cm
    current_weight REAL, -- in kg
    goal_weight REAL, -- in kg
    activity_level TEXT, -- 'sedentary', 'light', 'moderate', 'active', 'very_active'
    health_conditions TEXT,
    dietary_restrictions TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Nutritionist profiles table
CREATE TABLE nutritionist_profiles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    professional_id TEXT NOT NULL, -- CRN (Conselho Regional de Nutrição) number
    specialties TEXT,
    bio TEXT,
    education TEXT,
    experience TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Restaurant profiles table
CREATE TABLE restaurant_profiles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    cnpj TEXT NOT NULL, -- Brazilian company ID
    address TEXT NOT NULL,
    phone TEXT NOT NULL,
    description TEXT,
    delivery_areas TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Ingredients table
CREATE TABLE ingredients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    calories REAL NOT NULL, -- per 100g
    protein REAL NOT NULL, -- in g per 100g
    carbs REAL NOT NULL, -- in g per 100g
    fat REAL NOT NULL, -- in g per 100g
    fiber REAL, -- in g per 100g
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Meals table
CREATE TABLE meals (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    restaurant_id INTEGER NOT NULL,
    name TEXT NOT NULL,
    description TEXT,
    price REAL NOT NULL,
    available BOOLEAN DEFAULT 1,
    meal_type TEXT, -- 'breakfast', 'lunch', 'dinner', 'snack'
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES restaurant_profiles(id) ON DELETE CASCADE
);

-- Meal ingredients table (many-to-many)
CREATE TABLE meal_ingredients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    meal_id INTEGER NOT NULL,
    ingredient_id INTEGER NOT NULL,
    amount REAL NOT NULL, -- in grams
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (meal_id) REFERENCES meals(id) ON DELETE CASCADE,
    FOREIGN KEY (ingredient_id) REFERENCES ingredients(id) ON DELETE RESTRICT
);

-- Diet plans table
CREATE TABLE diet_plans (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nutritionist_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    name TEXT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    daily_calories REAL,
    daily_protein REAL, -- in g
    daily_carbs REAL, -- in g
    daily_fat REAL, -- in g
    notes TEXT,
    status TEXT DEFAULT 'active', -- 'active', 'completed', 'canceled'
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (nutritionist_id) REFERENCES nutritionist_profiles(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Diet meals table
CREATE TABLE diet_meals (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    diet_plan_id INTEGER NOT NULL,
    meal_type TEXT NOT NULL, -- 'breakfast', 'lunch', 'dinner', 'snack'
    day_of_week INTEGER NOT NULL, -- 0-6, 0 is Sunday
    time_of_day TIME NOT NULL,
    calories_target REAL,
    protein_target REAL, -- in g
    carbs_target REAL, -- in g
    fat_target REAL, -- in g
    notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (diet_plan_id) REFERENCES diet_plans(id) ON DELETE CASCADE
);

-- User meal selections table
CREATE TABLE user_meal_selections (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    diet_meal_id INTEGER NOT NULL,
    meal_id INTEGER NOT NULL,
    date DATE NOT NULL,
    status TEXT DEFAULT 'selected', -- 'selected', 'consumed', 'skipped'
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (diet_meal_id) REFERENCES diet_meals(id) ON DELETE CASCADE,
    FOREIGN KEY (meal_id) REFERENCES meals(id) ON DELETE CASCADE
);

-- User measurements table
CREATE TABLE user_measurements (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    date DATE NOT NULL,
    weight REAL, -- in kg
    body_fat_percentage REAL,
    waist REAL, -- in cm
    chest REAL, -- in cm
    arms REAL, -- in cm
    legs REAL, -- in cm
    notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Chats table
CREATE TABLE chats (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    nutritionist_id INTEGER NOT NULL,
    status TEXT DEFAULT 'active', -- 'active', 'closed'
    share_progress BOOLEAN DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (nutritionist_id) REFERENCES nutritionist_profiles(id) ON DELETE CASCADE
);

-- Chat messages table
CREATE TABLE chat_messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    chat_id INTEGER NOT NULL,
    sender_id INTEGER NOT NULL,
    message TEXT NOT NULL,
    read BOOLEAN DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (chat_id) REFERENCES chats(id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE
);