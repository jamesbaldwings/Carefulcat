-- Cat Rescue Database Schema for MySQL
-- Compatible with Hostinger MySQL/MariaDB

CREATE DATABASE IF NOT EXISTS cat_rescue CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cat_rescue;

-- Cats table
CREATE TABLE IF NOT EXISTS cats (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    name VARCHAR(255) NOT NULL,
    species VARCHAR(100) NOT NULL,
    status VARCHAR(50) NOT NULL, -- 'adoptable', 'hold', 'pending', 'adopted', 'resident'
    sex CHAR(1) NOT NULL, -- 'M' or 'F'
    age VARCHAR(50) NOT NULL, -- 'Kitten', 'Young Adult', 'Adult', 'Senior'
    fee INT NULL, -- adoption fee in dollars
    location VARCHAR(255) NOT NULL DEFAULT 'Murfreesboro, TN',
    bio TEXT NOT NULL,
    medical JSON, -- ["vaccinated", "microchipped", "neutered"]
    badges JSON, -- ["Good with adults", "Special needs"]
    readiness JSON, -- ["Vaccinated", "Microchipped", "Spay/Neuter complete"]
    hero_photo VARCHAR(500) NOT NULL,
    gallery JSON, -- array of image URLs
    videos JSON, -- array of video URLs
    intake_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_intake_date (intake_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Donations table
CREATE TABLE IF NOT EXISTS donations (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    amount INT NOT NULL, -- in cents
    type VARCHAR(50) NOT NULL, -- 'one-time' or 'monthly'
    sponsored_cat_id VARCHAR(36) NULL,
    stripe_payment_intent_id VARCHAR(255),
    status VARCHAR(50) NOT NULL DEFAULT 'pending', -- 'pending', 'completed', 'failed'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sponsored_cat_id) REFERENCES cats(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contacts table
CREATE TABLE IF NOT EXISTS contacts (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    subject VARCHAR(500) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bookings table
CREATE TABLE IF NOT EXISTS bookings (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    session_type VARCHAR(50) NOT NULL, -- '30min' or '60min'
    number_of_people INT NOT NULL DEFAULT 1,
    requested_date DATETIME,
    status VARCHAR(50) NOT NULL DEFAULT 'pending', -- 'pending', 'confirmed', 'completed', 'cancelled'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_requested_date (requested_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volunteers table
CREATE TABLE IF NOT EXISTS volunteers (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    address TEXT NOT NULL,
    experience TEXT NOT NULL,
    availability TEXT NOT NULL,
    interests TEXT NOT NULL,
    emergency_contact TEXT NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'pending', -- 'pending', 'approved', 'active', 'inactive'
    volunteer_id VARCHAR(20),
    background_check BOOLEAN DEFAULT FALSE,
    orientation_completed BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_volunteer_id (volunteer_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volunteer Events table
CREATE TABLE IF NOT EXISTS volunteer_events (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    title VARCHAR(500) NOT NULL,
    description TEXT,
    date DATETIME NOT NULL,
    duration_minutes INT NOT NULL,
    max_volunteers INT NOT NULL DEFAULT 5,
    volunteers_needed TEXT,
    status VARCHAR(50) NOT NULL DEFAULT 'open', -- 'open', 'full', 'completed', 'cancelled'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_date (date),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volunteer Event Signups table
CREATE TABLE IF NOT EXISTS volunteer_event_signups (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    volunteer_id VARCHAR(36) NOT NULL,
    event_id VARCHAR(36) NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'signed_up', -- 'signed_up', 'completed', 'no_show'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (volunteer_id) REFERENCES volunteers(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES volunteer_events(id) ON DELETE CASCADE,
    UNIQUE KEY unique_signup (volunteer_id, event_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Newsletter Subscriptions table
CREATE TABLE IF NOT EXISTS newsletter_subscriptions (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    email VARCHAR(255) NOT NULL UNIQUE,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    interests TEXT,
    status VARCHAR(50) NOT NULL DEFAULT 'active', -- 'active', 'unsubscribed'
    subscribe_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    unsubscribe_date TIMESTAMP NULL,
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Merchandise Products table
CREATE TABLE IF NOT EXISTS merch_products (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    name VARCHAR(500) NOT NULL,
    description TEXT,
    category VARCHAR(100) NOT NULL, -- 'apparel', 'accessories', 'home', 'calendars'
    price INT NOT NULL, -- in cents
    images JSON,
    sizes JSON,
    colors JSON,
    printful_id VARCHAR(255),
    status VARCHAR(50) NOT NULL DEFAULT 'active', -- 'active', 'inactive', 'out_of_stock'
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_status (status),
    INDEX idx_featured (featured)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Merchandise Orders table
CREATE TABLE IF NOT EXISTS merch_orders (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    customer_email VARCHAR(255) NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    shipping_address TEXT NOT NULL,
    products TEXT NOT NULL, -- JSON string
    subtotal INT NOT NULL,
    shipping INT NOT NULL,
    tax INT NOT NULL,
    total INT NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'pending', -- 'pending', 'processing', 'shipped', 'delivered', 'cancelled'
    stripe_payment_intent_id VARCHAR(255),
    tracking_number VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_customer_email (customer_email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin Users table
CREATE TABLE IF NOT EXISTS admin_users (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'admin', -- 'admin', 'super_admin'
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin Sessions table
CREATE TABLE IF NOT EXISTS admin_sessions (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    admin_id VARCHAR(36) NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES admin_users(id) ON DELETE CASCADE,
    INDEX idx_token (token),
    INDEX idx_expires_at (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sponsors table
CREATE TABLE IF NOT EXISTS sponsors (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    name VARCHAR(500) NOT NULL,
    logo_url VARCHAR(500) NOT NULL,
    website_url VARCHAR(500) NOT NULL,
    description TEXT,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_is_active (is_active),
    INDEX idx_display_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Posts table (Blog & Success Stories)
CREATE TABLE IF NOT EXISTS posts (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    slug VARCHAR(500) NOT NULL UNIQUE,
    title VARCHAR(500) NOT NULL,
    excerpt TEXT,
    content LONGTEXT NOT NULL,
    cover_image_url VARCHAR(500),
    tags JSON,
    category VARCHAR(50) NOT NULL, -- 'blog', 'success', 'vlog'
    status VARCHAR(50) NOT NULL DEFAULT 'draft', -- 'draft', 'published'
    seo_title VARCHAR(500),
    seo_description TEXT,
    og_image_url VARCHAR(500),
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_video BOOLEAN DEFAULT FALSE,
    video_id VARCHAR(255) UNIQUE,
    video_url VARCHAR(500),
    video_duration INT,
    video_thumbnail VARCHAR(500),
    INDEX idx_slug (slug),
    INDEX idx_category (category),
    INDEX idx_status (status),
    INDEX idx_published_at (published_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- System Settings table
CREATE TABLE IF NOT EXISTS system_settings (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    setting_key VARCHAR(255) NOT NULL UNIQUE,
    setting_value TEXT NOT NULL,
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_setting_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Fosters table
CREATE TABLE IF NOT EXISTS fosters (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    address TEXT NOT NULL,
    housing_type VARCHAR(50) NOT NULL,
    has_yard BOOLEAN DEFAULT FALSE,
    own_or_rent VARCHAR(50) NOT NULL,
    landlord_approval BOOLEAN DEFAULT FALSE,
    current_pets TEXT,
    experience TEXT NOT NULL,
    available_space TEXT NOT NULL,
    max_cats INT NOT NULL DEFAULT 1,
    can_foster_kittens BOOLEAN DEFAULT FALSE,
    can_foster_special_needs BOOLEAN DEFAULT FALSE,
    status VARCHAR(50) NOT NULL DEFAULT 'pending', -- 'pending', 'approved', 'active', 'inactive'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Foster Assignments table
CREATE TABLE IF NOT EXISTS foster_assignments (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    foster_id VARCHAR(36) NOT NULL,
    cat_id VARCHAR(36) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE,
    status VARCHAR(50) NOT NULL DEFAULT 'active', -- 'active', 'completed', 'cancelled'
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (foster_id) REFERENCES fosters(id) ON DELETE CASCADE,
    FOREIGN KEY (cat_id) REFERENCES cats(id) ON DELETE CASCADE,
    INDEX idx_status (status),
    INDEX idx_foster_id (foster_id),
    INDEX idx_cat_id (cat_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Waitlist table
CREATE TABLE IF NOT EXISTS waitlist (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    preferences TEXT NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'active', -- 'active', 'matched', 'inactive'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default system settings
INSERT INTO system_settings (setting_key, setting_value, description) VALUES
('volunteer_applications_enabled', 'true', 'Enable or disable volunteer applications'),
('page_adoptions_visible', 'true', 'Show or hide adoptions page'),
('page_blog_visible', 'true', 'Show or hide blog page'),
('page_shop_visible', 'true', 'Show or hide shop page'),
('page_residents_visible', 'true', 'Show or hide residents page'),
('page_book_visit_visible', 'true', 'Show or hide book visit page'),
('page_volunteer_visible', 'true', 'Show or hide volunteer page'),
('page_volunteer_events_visible', 'true', 'Show or hide volunteer events page'),
('site_name', 'Careful Cat Rescue', 'Website name'),
('site_email', 'carefulcatrescue@gmail.com', 'Contact email'),
('site_phone', '', 'Contact phone number'),
('site_address', 'Murfreesboro, TN', 'Physical address')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

-- Create default admin user (password: admin123 - CHANGE THIS!)
-- Password hash for 'admin123'
INSERT INTO admin_users (email, password, first_name, last_name, role, is_active) VALUES
('admin@carefulcatrescue.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'User', 'super_admin', TRUE)
ON DUPLICATE KEY UPDATE email = email;




-- Sponsors table
CREATE TABLE IF NOT EXISTS sponsors (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    name VARCHAR(255) NOT NULL,
    tier VARCHAR(50) NOT NULL, -- 'bronze', 'silver', 'gold'
    logo VARCHAR(500) NOT NULL, -- path to logo image
    website_url VARCHAR(500) NULL,
    description TEXT NULL,
    featured_on_homepage BOOLEAN DEFAULT FALSE,
    display_order INT DEFAULT 0,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_tier (tier),
    INDEX idx_featured (featured_on_homepage),
    INDEX idx_display_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample sponsors
INSERT INTO sponsors (name, tier, logo, website_url, description, featured_on_homepage, display_order) VALUES
('Paws & Claws Veterinary', 'gold', '/uploads/sponsors/sponsor1.png', 'https://example.com', 'Providing quality veterinary care since 2005', TRUE, 1),
('Murfreesboro Pet Supply', 'gold', '/uploads/sponsors/sponsor2.png', 'https://example.com', 'Your local pet supply store', TRUE, 2),
('The Smith Family', 'gold', '/uploads/sponsors/sponsor3.png', NULL, 'Dedicated cat lovers and advocates', TRUE, 3),
('Green Hills Animal Hospital', 'gold', '/uploads/sponsors/sponsor4.png', 'https://example.com', 'Compassionate care for all animals', TRUE, 4),
('TN Cat Lovers Association', 'gold', '/uploads/sponsors/sponsor5.png', 'https://example.com', 'Supporting feline welfare statewide', TRUE, 5),
('Johnson & Associates Law', 'gold', '/uploads/sponsors/sponsor6.png', 'https://example.com', 'Proud supporters of animal welfare', TRUE, 6);


-- ============================================
-- PHASE 2 FEATURES - Additional Tables
-- ============================================

-- Products/Merchandise Table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(100),
    price DECIMAL(10, 2) NOT NULL,
    sale_price DECIMAL(10, 2),
    stock_quantity INT DEFAULT 0,
    sku VARCHAR(100) UNIQUE,
    featured BOOLEAN DEFAULT FALSE,
    active BOOLEAN DEFAULT TRUE,
    external_url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Product Images Table
CREATE TABLE IF NOT EXISTS product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_path VARCHAR(500) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    display_order INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Success Stories Table
CREATE TABLE IF NOT EXISTS success_stories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cat_id INT,
    title VARCHAR(255) NOT NULL,
    story TEXT NOT NULL,
    adopter_name VARCHAR(255),
    adoption_date DATE,
    before_image VARCHAR(500),
    after_image VARCHAR(500),
    testimonial TEXT,
    featured BOOLEAN DEFAULT FALSE,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cat_id) REFERENCES cats(id) ON DELETE SET NULL
);

-- Events Table
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    event_type VARCHAR(100),
    event_date DATE NOT NULL,
    start_time TIME,
    end_time TIME,
    location VARCHAR(500),
    max_attendees INT,
    rsvp_required BOOLEAN DEFAULT FALSE,
    featured BOOLEAN DEFAULT FALSE,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Event RSVPs Table
CREATE TABLE IF NOT EXISTS event_rsvps (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    number_of_guests INT DEFAULT 1,
    message TEXT,
    status VARCHAR(50) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

-- Wishlist Items Table
CREATE TABLE IF NOT EXISTS wishlist_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(100),
    priority VARCHAR(50) DEFAULT 'medium',
    quantity_needed INT DEFAULT 1,
    quantity_fulfilled INT DEFAULT 0,
    amazon_url VARCHAR(500),
    estimated_cost DECIMAL(10, 2),
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Foster Applications Table
CREATE TABLE IF NOT EXISTS foster_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    address TEXT,
    housing_type VARCHAR(100),
    has_pets BOOLEAN DEFAULT FALSE,
    pet_details TEXT,
    experience TEXT,
    availability TEXT,
    special_needs_willing BOOLEAN DEFAULT FALSE,
    status VARCHAR(50) DEFAULT 'pending',
    notes TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reviewed_at TIMESTAMP NULL
);

-- Foster Families Table
CREATE TABLE IF NOT EXISTS foster_families (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    address TEXT,
    capacity INT DEFAULT 1,
    current_fosters INT DEFAULT 0,
    active BOOLEAN DEFAULT TRUE,
    notes TEXT,
    approved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (application_id) REFERENCES foster_applications(id) ON DELETE SET NULL
);

-- Medical Records Table
CREATE TABLE IF NOT EXISTS medical_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cat_id INT NOT NULL,
    record_type VARCHAR(100),
    record_date DATE NOT NULL,
    veterinarian VARCHAR(255),
    clinic VARCHAR(255),
    diagnosis TEXT,
    treatment TEXT,
    medications TEXT,
    cost DECIMAL(10, 2),
    follow_up_date DATE,
    notes TEXT,
    document_path VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cat_id) REFERENCES cats(id) ON DELETE CASCADE
);

-- Vaccinations Table
CREATE TABLE IF NOT EXISTS vaccinations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cat_id INT NOT NULL,
    vaccine_name VARCHAR(255) NOT NULL,
    vaccination_date DATE NOT NULL,
    due_date DATE,
    veterinarian VARCHAR(255),
    batch_number VARCHAR(100),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cat_id) REFERENCES cats(id) ON DELETE CASCADE
);

-- Sponsorships Table
CREATE TABLE IF NOT EXISTS sponsorships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cat_id INT NOT NULL,
    sponsor_name VARCHAR(255) NOT NULL,
    sponsor_email VARCHAR(255) NOT NULL,
    sponsor_phone VARCHAR(50),
    tier VARCHAR(50) DEFAULT 'bronze',
    monthly_amount DECIMAL(10, 2) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE,
    status VARCHAR(50) DEFAULT 'active',
    payment_method VARCHAR(100),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cat_id) REFERENCES cats(id) ON DELETE CASCADE
);

-- Sponsor Updates Table
CREATE TABLE IF NOT EXISTS sponsor_updates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sponsorship_id INT NOT NULL,
    update_date DATE NOT NULL,
    title VARCHAR(255),
    message TEXT,
    image_path VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sponsorship_id) REFERENCES sponsorships(id) ON DELETE CASCADE
);

-- Newsletter Subscribers Table (if not exists)
CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    name VARCHAR(255),
    status VARCHAR(50) DEFAULT 'active',
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    unsubscribed_at TIMESTAMP NULL
);

-- Newsletter Archive Table
CREATE TABLE IF NOT EXISTS newsletters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    sent_date DATE,
    status VARCHAR(50) DEFAULT 'draft',
    recipients_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

