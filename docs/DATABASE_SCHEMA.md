# Comprehensive Webstore Platform - Database Schema

## Overview
This document outlines the complete database architecture for a multi-tenant webstore platform supporting 6 distinct user roles with enterprise-grade features.

## Core Architecture Principles

### 1. Role-Based Access Control (RBAC)
- **Granular Permissions**: Each action is controlled by specific permissions
- **Multiple Roles**: Users can have multiple roles simultaneously
- **Hierarchical Structure**: Roles can inherit permissions from other roles
- **Audit Trail**: All role assignments and permission changes are logged

### 2. Multi-Tenant Architecture
- **Organizations**: Top-level tenant isolation
- **Stores**: Sub-tenant level for product owners/vendors
- **Data Isolation**: Strict separation of tenant data

### 3. Financial Integrity
- **Immutable Transactions**: Financial records cannot be modified once locked
- **Approval Workflows**: Multi-level approval for financial operations
- **Audit Trail**: Complete transaction history with hash verification

### 4. Real-Time Capabilities
- **Live Location Tracking**: GPS coordinates with PostGIS support
- **Real-Time Notifications**: Multi-channel notification system
- **System Monitoring**: Live health checks and performance metrics

## Database Tables Structure

### Core RBAC System

#### `roles`
Defines system roles with hierarchical permissions.

```sql
CREATE TABLE roles (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) UNIQUE NOT NULL,
    display_name VARCHAR(255) NOT NULL,
    description TEXT,
    permissions JSON,
    is_system_role BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_roles_name_system ON roles(name, is_system_role);
```

**System Roles:**
- `super_admin`: God mode access to everything
- `it_admin`: System health, logs, deployments
- `hr_manager`: Employee management, payroll
- `driver_supervisor`: Fleet management, logistics
- `finance_manager`: Financial operations, payouts
- `product_owner`: Store management, inventory

#### `permissions`
Granular permission definitions.

```sql
CREATE TABLE permissions (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) UNIQUE NOT NULL,
    display_name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_permissions_category_name ON permissions(category, name);
```

**Permission Categories:**
- `users`: User management operations
- `orders`: Order processing and management
- `finance`: Financial transactions and reports
- `system`: System administration
- `hr`: Human resources operations
- `logistics`: Delivery and fleet management

#### `user_roles`
Many-to-many relationship between users and roles.

```sql
CREATE TABLE user_roles (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT REFERENCES users(id) ON DELETE CASCADE,
    role_id BIGINT REFERENCES roles(id) ON DELETE CASCADE,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    assigned_by BIGINT REFERENCES users(id),
    expires_at TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    UNIQUE(user_id, role_id)
);

-- Indexes
CREATE INDEX idx_user_roles_user_active ON user_roles(user_id, is_active);
```

### Audit & Activity Logging

#### `audit_logs`
Complete audit trail for all system activities.

```sql
CREATE TABLE audit_logs (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT REFERENCES users(id) ON DELETE SET NULL,
    action VARCHAR(100) NOT NULL,
    model_type VARCHAR(100),
    model_id BIGINT,
    old_values JSON,
    new_values JSON,
    ip_address INET,
    user_agent TEXT,
    session_id VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_audit_logs_user_created ON audit_logs(user_id, created_at);
CREATE INDEX idx_audit_logs_model ON audit_logs(model_type, model_id);
CREATE INDEX idx_audit_logs_action_created ON audit_logs(action, created_at);
```

### Organizations & Stores

#### `organizations`
Top-level tenant isolation.

```sql
CREATE TABLE organizations (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    logo VARCHAR(500),
    settings JSON,
    status VARCHAR(20) DEFAULT 'active' CHECK (status IN ('active', 'suspended', 'inactive')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_organizations_status_created ON organizations(status, created_at);
```

#### `stores`
Sub-tenant level for product owners.

```sql
CREATE TABLE stores (
    id BIGSERIAL PRIMARY KEY,
    organization_id BIGINT REFERENCES organizations(id) ON DELETE CASCADE,
    owner_id BIGINT REFERENCES users(id) ON DELETE CASCADE,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    logo VARCHAR(500),
    business_info JSON,
    contact_info JSON,
    settings JSON,
    status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('active', 'pending', 'suspended', 'closed')),
    commission_rate DECIMAL(5,4) DEFAULT 0.0500,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_stores_owner_status ON stores(owner_id, status);
CREATE INDEX idx_stores_org_status ON stores(organization_id, status);
```

### Product Management

#### `categories`
Hierarchical product categorization.

```sql
CREATE TABLE categories (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    image VARCHAR(500),
    parent_id BIGINT REFERENCES categories(id) ON DELETE SET NULL,
    sort_order INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    metadata JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_categories_parent_active_sort ON categories(parent_id, is_active, sort_order);
```

#### `products`
Complete product information with inventory tracking.

```sql
CREATE TABLE products (
    id BIGSERIAL PRIMARY KEY,
    store_id BIGINT REFERENCES stores(id) ON DELETE CASCADE,
    category_id BIGINT REFERENCES categories(id) ON DELETE SET NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    short_description TEXT,
    sku VARCHAR(100) UNIQUE NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    cost_price DECIMAL(10,2),
    discount_price DECIMAL(10,2),
    stock_quantity INTEGER DEFAULT 0,
    low_stock_threshold INTEGER DEFAULT 10,
    images JSON,
    attributes JSON,
    seo_data JSON,
    is_active BOOLEAN DEFAULT TRUE,
    is_featured BOOLEAN DEFAULT FALSE,
    track_inventory BOOLEAN DEFAULT TRUE,
    status VARCHAR(20) DEFAULT 'draft' CHECK (status IN ('draft', 'active', 'inactive', 'out_of_stock')),
    weight DECIMAL(8,2),
    dimensions JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_products_store_status ON products(store_id, status);
CREATE INDEX idx_products_category_active ON products(category_id, is_active);
CREATE INDEX idx_products_sku_active ON products(sku, is_active);
CREATE INDEX idx_products_stock_threshold ON products(stock_quantity, low_stock_threshold);
```

### Order Management System

#### `orders`
Complete order lifecycle management.

```sql
CREATE TABLE orders (
    id BIGSERIAL PRIMARY KEY,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    customer_id BIGINT REFERENCES users(id) ON DELETE CASCADE,
    store_id BIGINT REFERENCES stores(id) ON DELETE SET NULL,
    
    -- Order Status Flow
    status VARCHAR(20) DEFAULT 'pending' CHECK (status IN (
        'pending', 'confirmed', 'processing', 'shipped', 
        'delivered', 'cancelled', 'refunded', 'returned'
    )),
    
    -- Payment Information
    payment_status VARCHAR(20) DEFAULT 'pending' CHECK (payment_status IN (
        'pending', 'paid', 'failed', 'refunded', 'partial'
    )),
    payment_method VARCHAR(50),
    payment_reference VARCHAR(255),
    
    -- Financial Breakdown
    subtotal DECIMAL(10,2) NOT NULL,
    tax_amount DECIMAL(10,2) DEFAULT 0,
    shipping_cost DECIMAL(10,2) DEFAULT 0,
    discount_amount DECIMAL(10,2) DEFAULT 0,
    total_amount DECIMAL(10,2) NOT NULL,
    commission_amount DECIMAL(10,2) DEFAULT 0,
    
    -- Shipping Information
    shipping_address JSON NOT NULL,
    billing_address JSON,
    estimated_delivery TIMESTAMP,
    shipped_at TIMESTAMP,
    delivered_at TIMESTAMP,
    
    -- Tracking & Notes
    tracking_number VARCHAR(100),
    customer_notes TEXT,
    admin_notes TEXT,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_orders_customer_status ON orders(customer_id, status);
CREATE INDEX idx_orders_store_status ON orders(store_id, status);
CREATE INDEX idx_orders_status_created ON orders(status, created_at);
CREATE INDEX idx_orders_payment_status_created ON orders(payment_status, created_at);
```

#### `order_items`
Individual items within orders with price snapshots.

```sql
CREATE TABLE order_items (
    id BIGSERIAL PRIMARY KEY,
    order_id BIGINT REFERENCES orders(id) ON DELETE CASCADE,
    product_id BIGINT REFERENCES products(id) ON DELETE CASCADE,
    product_name VARCHAR(255) NOT NULL,
    product_sku VARCHAR(100) NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    quantity INTEGER NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    product_snapshot JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_order_items_order_product ON order_items(order_id, product_id);
```

### Delivery & Logistics System

#### `drivers`
Driver profiles and vehicle information.

```sql
CREATE TABLE drivers (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT REFERENCES users(id) ON DELETE CASCADE,
    license_number VARCHAR(50) UNIQUE NOT NULL,
    license_expiry DATE NOT NULL,
    vehicle_type VARCHAR(50) NOT NULL,
    vehicle_plate VARCHAR(20) UNIQUE NOT NULL,
    vehicle_info JSON,
    status VARCHAR(20) DEFAULT 'active' CHECK (status IN ('active', 'inactive', 'suspended')),
    availability VARCHAR(20) DEFAULT 'offline' CHECK (availability IN ('available', 'busy', 'offline')),
    rating DECIMAL(3,2) DEFAULT 5.00,
    total_deliveries INTEGER DEFAULT 0,
    working_hours JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_drivers_status_availability ON drivers(status, availability);
CREATE INDEX idx_drivers_rating_deliveries ON drivers(rating, total_deliveries);
```

#### `driver_locations`
Real-time GPS tracking with PostGIS support.

```sql
CREATE TABLE driver_locations (
    id BIGSERIAL PRIMARY KEY,
    driver_id BIGINT REFERENCES drivers(id) ON DELETE CASCADE,
    latitude DECIMAL(10,8) NOT NULL,
    longitude DECIMAL(11,8) NOT NULL,
    accuracy DECIMAL(8,2),
    speed DECIMAL(8,2),
    heading DECIMAL(8,2),
    recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    
    -- PostGIS geometry column (if PostGIS is available)
    -- location GEOMETRY(POINT, 4326)
);

-- Indexes
CREATE INDEX idx_driver_locations_driver_recorded ON driver_locations(driver_id, recorded_at);
CREATE INDEX idx_driver_locations_coords ON driver_locations(latitude, longitude);

-- PostGIS spatial index (if available)
-- CREATE INDEX idx_driver_locations_geom ON driver_locations USING GIST(location);
```

#### `delivery_assignments`
Order-to-driver assignments with status tracking.

```sql
CREATE TABLE delivery_assignments (
    id BIGSERIAL PRIMARY KEY,
    order_id BIGINT REFERENCES orders(id) ON DELETE CASCADE,
    driver_id BIGINT REFERENCES drivers(id) ON DELETE CASCADE,
    assigned_by BIGINT REFERENCES users(id) ON DELETE CASCADE,
    
    status VARCHAR(20) DEFAULT 'assigned' CHECK (status IN (
        'assigned', 'accepted', 'rejected', 'picked_up', 
        'in_transit', 'delivered', 'failed', 'cancelled'
    )),
    
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    accepted_at TIMESTAMP,
    picked_up_at TIMESTAMP,
    delivered_at TIMESTAMP,
    
    driver_notes TEXT,
    delivery_proof JSON,
    delivery_fee DECIMAL(8,2),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_delivery_assignments_driver_status ON delivery_assignments(driver_id, status);
CREATE INDEX idx_delivery_assignments_order_status ON delivery_assignments(order_id, status);
```

### Financial System

#### `financial_transactions`
Immutable financial transaction records.

```sql
CREATE TABLE financial_transactions (
    id BIGSERIAL PRIMARY KEY,
    transaction_id VARCHAR(100) UNIQUE NOT NULL,
    user_id BIGINT REFERENCES users(id) ON DELETE SET NULL,
    order_id BIGINT REFERENCES orders(id) ON DELETE SET NULL,
    store_id BIGINT REFERENCES stores(id) ON DELETE SET NULL,
    
    type VARCHAR(50) NOT NULL CHECK (type IN (
        'order_payment', 'commission', 'payout', 'refund', 
        'fee', 'adjustment', 'payroll', 'expense'
    )),
    
    status VARCHAR(20) DEFAULT 'pending' CHECK (status IN (
        'pending', 'processing', 'completed', 'failed', 'cancelled'
    )),
    
    amount DECIMAL(12,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'USD',
    description TEXT NOT NULL,
    metadata JSON,
    
    -- Immutability fields
    hash VARCHAR(64),
    is_locked BOOLEAN DEFAULT FALSE,
    locked_at TIMESTAMP,
    
    -- Approval workflow
    approval_status VARCHAR(20) DEFAULT 'pending' CHECK (approval_status IN (
        'pending', 'approved', 'rejected'
    )),
    approved_by BIGINT REFERENCES users(id),
    approved_at TIMESTAMP,
    approval_notes TEXT,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_financial_transactions_type_status ON financial_transactions(type, status);
CREATE INDEX idx_financial_transactions_user_created ON financial_transactions(user_id, created_at);
CREATE INDEX idx_financial_transactions_store_type ON financial_transactions(store_id, type);
CREATE INDEX idx_financial_transactions_approval_created ON financial_transactions(approval_status, created_at);
```

#### `payouts`
Store owner payout requests and processing.

```sql
CREATE TABLE payouts (
    id BIGSERIAL PRIMARY KEY,
    store_id BIGINT REFERENCES stores(id) ON DELETE CASCADE,
    requested_by BIGINT REFERENCES users(id) ON DELETE CASCADE,
    amount DECIMAL(12,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'USD',
    status VARCHAR(20) DEFAULT 'pending' CHECK (status IN (
        'pending', 'approved', 'processing', 'completed', 'rejected'
    )),
    bank_details JSON NOT NULL,
    notes TEXT,
    processed_by BIGINT REFERENCES users(id),
    processed_at TIMESTAMP,
    reference_number VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_payouts_store_status ON payouts(store_id, status);
CREATE INDEX idx_payouts_status_created ON payouts(status, created_at);
```

### HR Management System

#### `employees`
Employee profiles and employment details.

```sql
CREATE TABLE employees (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT REFERENCES users(id) ON DELETE CASCADE,
    employee_id VARCHAR(50) UNIQUE NOT NULL,
    department VARCHAR(100) NOT NULL,
    position VARCHAR(100) NOT NULL,
    hourly_rate DECIMAL(8,2),
    monthly_salary DECIMAL(10,2),
    hire_date DATE NOT NULL,
    termination_date DATE,
    employment_type VARCHAR(20) NOT NULL CHECK (employment_type IN (
        'full_time', 'part_time', 'contract', 'intern'
    )),
    status VARCHAR(20) DEFAULT 'active' CHECK (status IN (
        'active', 'inactive', 'terminated', 'on_leave'
    )),
    emergency_contact JSON,
    documents JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_employees_department_status ON employees(department, status);
CREATE INDEX idx_employees_status_hire_date ON employees(status, hire_date);
```

#### `shifts`
Work shift scheduling and time tracking.

```sql
CREATE TABLE shifts (
    id BIGSERIAL PRIMARY KEY,
    employee_id BIGINT REFERENCES employees(id) ON DELETE CASCADE,
    shift_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    actual_start_time TIME,
    actual_end_time TIME,
    break_duration DECIMAL(4,2) DEFAULT 0,
    hours_worked DECIMAL(4,2),
    overtime_hours DECIMAL(4,2) DEFAULT 0,
    status VARCHAR(20) DEFAULT 'scheduled' CHECK (status IN (
        'scheduled', 'in_progress', 'completed', 'missed', 'cancelled'
    )),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_shifts_employee_date ON shifts(employee_id, shift_date);
CREATE INDEX idx_shifts_date_status ON shifts(shift_date, status);
```

#### `payroll_records`
Payroll calculations and payment records.

```sql
CREATE TABLE payroll_records (
    id BIGSERIAL PRIMARY KEY,
    employee_id BIGINT REFERENCES employees(id) ON DELETE CASCADE,
    pay_period VARCHAR(20) NOT NULL,
    regular_hours DECIMAL(6,2) DEFAULT 0,
    overtime_hours DECIMAL(6,2) DEFAULT 0,
    regular_pay DECIMAL(10,2) DEFAULT 0,
    overtime_pay DECIMAL(10,2) DEFAULT 0,
    bonuses DECIMAL(10,2) DEFAULT 0,
    deductions DECIMAL(10,2) DEFAULT 0,
    gross_pay DECIMAL(10,2) NOT NULL,
    net_pay DECIMAL(10,2) NOT NULL,
    status VARCHAR(20) DEFAULT 'draft' CHECK (status IN ('draft', 'approved', 'paid')),
    approved_by BIGINT REFERENCES users(id),
    approved_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(employee_id, pay_period)
);

-- Indexes
CREATE INDEX idx_payroll_records_period_status ON payroll_records(pay_period, status);
```

### IT/DevOps Monitoring System

#### `system_services`
Service health monitoring and status tracking.

```sql
CREATE TABLE system_services (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL,
    host VARCHAR(255) NOT NULL,
    port INTEGER,
    status VARCHAR(20) DEFAULT 'offline' CHECK (status IN (
        'online', 'offline', 'degraded', 'maintenance'
    )),
    response_time DECIMAL(8,3),
    uptime_percentage INTEGER DEFAULT 0,
    last_check TIMESTAMP,
    health_data JSON,
    configuration JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_system_services_status_check ON system_services(status, last_check);
CREATE INDEX idx_system_services_type_status ON system_services(type, status);
```

#### `system_logs`
Centralized application and system logging.

```sql
CREATE TABLE system_logs (
    id BIGSERIAL PRIMARY KEY,
    level VARCHAR(20) NOT NULL CHECK (level IN (
        'emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'
    )),
    channel VARCHAR(50),
    message TEXT NOT NULL,
    context JSON,
    file VARCHAR(500),
    line INTEGER,
    user_id VARCHAR(50),
    session_id VARCHAR(255),
    ip_address INET,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_system_logs_level_created ON system_logs(level, created_at);
CREATE INDEX idx_system_logs_channel_created ON system_logs(channel, created_at);
CREATE INDEX idx_system_logs_user_created ON system_logs(user_id, created_at);
```

#### `deployment_logs`
Deployment tracking and version management.

```sql
CREATE TABLE deployment_logs (
    id BIGSERIAL PRIMARY KEY,
    version VARCHAR(50) NOT NULL,
    environment VARCHAR(20) NOT NULL,
    status VARCHAR(20) NOT NULL CHECK (status IN (
        'pending', 'in_progress', 'completed', 'failed', 'rolled_back'
    )),
    deployed_by BIGINT REFERENCES users(id) ON DELETE CASCADE,
    started_at TIMESTAMP NOT NULL,
    completed_at TIMESTAMP,
    changes JSON,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_deployment_logs_env_status ON deployment_logs(environment, status);
CREATE INDEX idx_deployment_logs_version_env ON deployment_logs(version, environment);
```

### Customer Support System

#### `support_tickets`
Customer support ticket management.

```sql
CREATE TABLE support_tickets (
    id BIGSERIAL PRIMARY KEY,
    ticket_number VARCHAR(20) UNIQUE NOT NULL,
    customer_id BIGINT REFERENCES users(id) ON DELETE CASCADE,
    assigned_to BIGINT REFERENCES users(id) ON DELETE SET NULL,
    subject VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    priority VARCHAR(20) DEFAULT 'medium' CHECK (priority IN ('low', 'medium', 'high', 'urgent')),
    status VARCHAR(20) DEFAULT 'open' CHECK (status IN (
        'open', 'in_progress', 'waiting_customer', 'resolved', 'closed'
    )),
    category VARCHAR(50),
    related_order_id BIGINT REFERENCES orders(id) ON DELETE SET NULL,
    first_response_at TIMESTAMP,
    resolved_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_support_tickets_status_priority ON support_tickets(status, priority);
CREATE INDEX idx_support_tickets_assigned_status ON support_tickets(assigned_to, status);
CREATE INDEX idx_support_tickets_customer_status ON support_tickets(customer_id, status);
```

#### `ticket_replies`
Support ticket conversation history.

```sql
CREATE TABLE ticket_replies (
    id BIGSERIAL PRIMARY KEY,
    ticket_id BIGINT REFERENCES support_tickets(id) ON DELETE CASCADE,
    user_id BIGINT REFERENCES users(id) ON DELETE CASCADE,
    message TEXT NOT NULL,
    attachments JSON,
    is_internal BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_ticket_replies_ticket_created ON ticket_replies(ticket_id, created_at);
```

### Notification System

#### `notifications`
Multi-channel notification management.

```sql
CREATE TABLE notifications (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT REFERENCES users(id) ON DELETE CASCADE,
    type VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    data JSON,
    channel VARCHAR(20) DEFAULT 'database' CHECK (channel IN ('database', 'email', 'sms', 'push')),
    is_read BOOLEAN DEFAULT FALSE,
    read_at TIMESTAMP,
    sent_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_notifications_user_read ON notifications(user_id, is_read);
CREATE INDEX idx_notifications_type_created ON notifications(type, created_at);
```

### Settings & Configuration

#### `system_settings`
Global system configuration management.

```sql
CREATE TABLE system_settings (
    id BIGSERIAL PRIMARY KEY,
    key VARCHAR(255) UNIQUE NOT NULL,
    value TEXT,
    type VARCHAR(20) DEFAULT 'string',
    description TEXT,
    is_public BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_system_settings_key_public ON system_settings(key, is_public);
```

## Performance Optimization

### Indexing Strategy
- **Primary Keys**: All tables use BIGSERIAL for scalability
- **Foreign Keys**: Indexed for join performance
- **Composite Indexes**: Multi-column indexes for common query patterns
- **Partial Indexes**: For filtered queries (e.g., active records only)

### Partitioning Recommendations
- **audit_logs**: Partition by month for better performance
- **system_logs**: Partition by level and date
- **driver_locations**: Partition by date for historical data
- **financial_transactions**: Partition by created_at for archival

### Caching Strategy
- **Redis**: Session data, frequently accessed settings
- **Application Cache**: User permissions, role definitions
- **Query Cache**: Dashboard statistics, reports

## Security Considerations

### Data Encryption
- **At Rest**: Encrypt sensitive fields (bank_details, personal_info)
- **In Transit**: TLS 1.3 for all communications
- **Application Level**: Encrypt PII before database storage

### Access Control
- **Row Level Security**: Implement RLS for multi-tenant isolation
- **Column Level**: Restrict access to sensitive financial data
- **API Level**: Rate limiting and authentication

### Audit Requirements
- **Financial Transactions**: Immutable once locked
- **User Actions**: Complete audit trail
- **System Changes**: Deployment and configuration tracking

## Backup & Recovery

### Backup Strategy
- **Full Backups**: Daily full database backups
- **Incremental**: Hourly transaction log backups
- **Point-in-Time**: Recovery capability for last 30 days

### Disaster Recovery
- **Hot Standby**: Real-time replication to secondary site
- **Geographic Distribution**: Multi-region deployment
- **Recovery Time**: RTO < 4 hours, RPO < 15 minutes