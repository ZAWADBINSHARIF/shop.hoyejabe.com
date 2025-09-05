# Database Documentation

## Overview
This e-commerce application uses MariaDB/MySQL as the primary database system. The database schema is designed to support product management, order processing, user authentication, and content management for an online store.

## Database Tables

### 1. Authentication & Session Management

#### users
Stores registered user information for authentication (admin users).
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | User unique identifier |
| name | VARCHAR(255) | NOT NULL | User full name |
| email | VARCHAR(255) | UNIQUE, NOT NULL | User email address |
| email_verified_at | TIMESTAMP | NULLABLE | Email verification timestamp |
| password | VARCHAR(255) | NOT NULL | Hashed password |
| remember_token | VARCHAR(100) | NULLABLE | Remember me token |
| created_at | TIMESTAMP | | Record creation time |
| updated_at | TIMESTAMP | | Last update time |

#### customers
Stores customer information for e-commerce transactions.
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Customer unique identifier |
| full_name | VARCHAR(255) | NOT NULL | Customer full name |
| phone_number | VARCHAR(255) | NOT NULL, INDEX | Customer phone number |
| email | VARCHAR(255) | UNIQUE, NULLABLE, INDEX | Customer email address |
| city | VARCHAR(255) | NULLABLE | Customer city |
| upazila | VARCHAR(255) | NULLABLE | Sub-district |
| thana | VARCHAR(255) | NULLABLE | Police station area |
| post_code | VARCHAR(255) | NULLABLE | Postal code |
| delivery_address | TEXT | NULLABLE | Full delivery address |
| password | VARCHAR(255) | NOT NULL | Hashed password |
| remember_token | VARCHAR(100) | NULLABLE | Remember me token |
| created_at | TIMESTAMP | | Record creation time |
| updated_at | TIMESTAMP | | Last update time |

#### password_reset_tokens
Manages password reset functionality.
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| email | VARCHAR(255) | PRIMARY KEY | User email |
| token | VARCHAR(255) | NOT NULL | Reset token |
| created_at | TIMESTAMP | NULLABLE | Token creation time |

#### sessions
Manages user sessions for the application.
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | VARCHAR(255) | PRIMARY KEY | Session identifier |
| user_id | BIGINT | FOREIGN KEY (users), NULLABLE, INDEX | Associated user |
| ip_address | VARCHAR(45) | NULLABLE | Client IP address |
| user_agent | TEXT | NULLABLE | Browser user agent |
| payload | LONGTEXT | NOT NULL | Session data |
| last_activity | INTEGER | INDEX | Last activity timestamp |

### 2. Product Management

#### product_categories
Product categorization system.
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Category ID |
| name | VARCHAR(255) | UNIQUE, NOT NULL | Category name |
| slug | VARCHAR(255) | UNIQUE, NOT NULL | URL-friendly slug |
| created_at | TIMESTAMP | | Record creation time |
| updated_at | TIMESTAMP | | Last update time |

#### products
Main product information table.
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Product ID |
| name | VARCHAR(255) | NOT NULL | Product name |
| slug | VARCHAR(255) | UNIQUE, NOT NULL | URL-friendly slug |
| images | JSON | NOT NULL | Product images array |
| highlighted_description | TEXT | NOT NULL | Short description |
| details_description | TEXT | NULLABLE | Detailed description |
| product_category | BIGINT | FOREIGN KEY (product_categories), CASCADE UPDATE | Category ID |
| base_price | DECIMAL(10,2) | NOT NULL | Base selling price |
| extra_shipping_cost | DECIMAL(10,2) | DEFAULT 0 | Additional shipping charge |
| published | BOOLEAN | NOT NULL | Publication status |
| out_of_stock | BOOLEAN | NOT NULL | Stock availability |
| created_at | TIMESTAMP | | Record creation time |
| updated_at | TIMESTAMP | | Last update time |

#### product_colors
Product color variants with pricing.
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Color variant ID |
| product_id | BIGINT | FOREIGN KEY (products), CASCADE DELETE/UPDATE | Product ID |
| color_code | VARCHAR(255) | NOT NULL | Color code (hex or name) |
| extra_price | DECIMAL(10,2) | DEFAULT 0 | Additional price for color |
| created_at | TIMESTAMP | | Record creation time |
| updated_at | TIMESTAMP | | Last update time |

#### sizes
Size definitions for products.
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Size ID |
| value | VARCHAR(255) | NOT NULL | Size value (S, M, L, XL, etc.) |
| created_at | TIMESTAMP | | Record creation time |
| updated_at | TIMESTAMP | | Last update time |

#### product_sizes
Junction table linking products to sizes with pricing.
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Record ID |
| product_id | BIGINT | FOREIGN KEY (products), CASCADE UPDATE | Product ID |
| size_id | BIGINT | FOREIGN KEY (sizes), CASCADE UPDATE | Size ID |
| extra_price | DECIMAL(10,2) | DEFAULT 0 | Additional price for size |
| created_at | TIMESTAMP | | Record creation time |
| updated_at | TIMESTAMP | | Last update time |

### 3. Order Management

#### orders
Customer order information.
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Order ID |
| order_tracking_id | VARCHAR(32) | UNIQUE, NOT NULL | Tracking number |
| customer_name | VARCHAR(255) | NOT NULL | Customer full name |
| customer_mobile | VARCHAR(255) | NOT NULL | Contact number |
| city | VARCHAR(255) | NOT NULL | Delivery city |
| address | VARCHAR(255) | NULLABLE | Street address |
| upazila | VARCHAR(255) | NULLABLE | Sub-district |
| thana | VARCHAR(255) | NULLABLE | Police station area |
| post_code | VARCHAR(255) | NULLABLE | Postal code |
| selected_shipping_area | BIGINT | FOREIGN KEY (shipping_costs), CASCADE UPDATE, NULL ON DELETE | Shipping zone |
| shipping_cost | DECIMAL(10,2) | NOT NULL | Shipping charge |
| extra_shipping_cost | DECIMAL(10,2) | DEFAULT 0 | Additional shipping |
| total_price | DECIMAL(10,2) | NOT NULL | Order total amount |
| order_status | VARCHAR(255) | NOT NULL | Order status |
| created_at | TIMESTAMP | | Order creation time |
| updated_at | TIMESTAMP | | Last update time |

#### ordered_products
Line items for each order.
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Line item ID |
| order_id | BIGINT | FOREIGN KEY (orders), CASCADE DELETE/UPDATE | Order ID |
| product_id | BIGINT | FOREIGN KEY (products), CASCADE UPDATE, NULL ON DELETE | Product ID |
| product_name | VARCHAR(255) | NOT NULL | Product name snapshot |
| quantity | INTEGER | NOT NULL | Quantity ordered |
| selected_color_code | VARCHAR(255) | NULLABLE | Selected color |
| color_extra_price | DECIMAL(10,2) | DEFAULT 0 | Color variant price |
| selected_size | VARCHAR(255) | NULLABLE | Selected size |
| size_extra_price | DECIMAL(10,2) | DEFAULT 0 | Size variant price |
| base_price | DECIMAL(10,2) | NOT NULL | Product base price |
| extra_shipping_cost | DECIMAL(10,2) | DEFAULT 0 | Product shipping cost |
| product_total_price | DECIMAL(10,2) | NOT NULL | Line total price |
| created_at | TIMESTAMP | | Record creation time |
| updated_at | TIMESTAMP | | Last update time |

### 4. Shipping & Logistics

#### shipping_costs
Shipping zone configuration.
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Zone ID |
| title | VARCHAR(255) | NOT NULL | Zone name |
| cost | DECIMAL(10,2) | NOT NULL | Shipping cost |
| created_at | TIMESTAMP | | Record creation time |
| updated_at | TIMESTAMP | | Last update time |

### 5. Content Management

#### carousel_images
Homepage carousel/slider images.
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Image ID |
| sort | INTEGER | DEFAULT 0 | Display order |
| title | VARCHAR(255) | NULLABLE | Image title |
| image | VARCHAR(255) | NOT NULL | Image path |
| product_url | VARCHAR(255) | NULLABLE | Link URL |
| created_at | TIMESTAMP | | Record creation time |
| updated_at | TIMESTAMP | | Last update time |

#### company_details
Company/store information.
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Record ID |
| name | VARCHAR(255) | NOT NULL | Company name |
| logo | VARCHAR(255) | NULLABLE | Logo image path |
| about | TEXT | NOT NULL | About text |
| width | DECIMAL(8,2) | NULLABLE | Logo width |
| height | DECIMAL(8,2) | NULLABLE | Logo height |
| created_at | TIMESTAMP | | Record creation time |
| updated_at | TIMESTAMP | | Last update time |

#### contacts
Store contact information.
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Record ID |
| mobile_number | VARCHAR(255) | NULLABLE | Contact phone |
| email | VARCHAR(255) | NULLABLE | Contact email |
| facebook | VARCHAR(255) | NULLABLE | Facebook URL |
| messanger | VARCHAR(255) | NULLABLE | Messenger link |
| instagram | VARCHAR(255) | NULLABLE | Instagram URL |
| whatsapp | VARCHAR(255) | NULLABLE | WhatsApp number |
| office_location | VARCHAR(255) | NULLABLE | Physical address |
| created_at | TIMESTAMP | | Record creation time |
| updated_at | TIMESTAMP | | Last update time |

### 6. System Tables

#### cache
Application cache storage.
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| key | VARCHAR(255) | PRIMARY KEY | Cache key |
| value | MEDIUMTEXT | NOT NULL | Cached value |
| expiration | INTEGER | NOT NULL | Expiration timestamp |

#### cache_locks
Cache lock management.
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| key | VARCHAR(255) | PRIMARY KEY | Lock key |
| owner | VARCHAR(255) | NOT NULL | Lock owner |
| expiration | INTEGER | NOT NULL | Lock expiration |

#### jobs
Background job queue.
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Job ID |
| queue | VARCHAR(255) | NOT NULL, INDEX | Queue name |
| payload | LONGTEXT | NOT NULL | Job data |
| attempts | TINYINT | NOT NULL | Attempt count |
| reserved_at | INTEGER | NULLABLE | Reservation timestamp |
| available_at | INTEGER | NOT NULL | Availability timestamp |
| created_at | INTEGER | NOT NULL | Creation timestamp |

#### job_batches
Batch job management.
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | VARCHAR(255) | PRIMARY KEY | Batch ID |
| name | VARCHAR(255) | NOT NULL | Batch name |
| total_jobs | INTEGER | NOT NULL | Total jobs in batch |
| pending_jobs | INTEGER | NOT NULL | Pending job count |
| failed_jobs | INTEGER | NOT NULL | Failed job count |
| failed_job_ids | LONGTEXT | NOT NULL | Failed job IDs |
| options | MEDIUMTEXT | NULLABLE | Batch options |
| cancelled_at | INTEGER | NULLABLE | Cancellation timestamp |
| created_at | INTEGER | NOT NULL | Creation timestamp |
| finished_at | INTEGER | NULLABLE | Completion timestamp |

#### failed_jobs
Failed job records.
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Record ID |
| uuid | VARCHAR(255) | UNIQUE | Job UUID |
| connection | TEXT | NOT NULL | Queue connection |
| queue | TEXT | NOT NULL | Queue name |
| payload | LONGTEXT | NOT NULL | Job payload |
| exception | LONGTEXT | NOT NULL | Exception details |
| failed_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Failure time |

## Database Relationships

### Primary Relationships

1. **Products → Product Categories** (Many-to-One)
   - `products.product_category` → `product_categories.id`

2. **Product Colors → Products** (Many-to-One)
   - `product_colors.product_id` → `products.id` (CASCADE DELETE/UPDATE)

3. **Product Sizes → Products** (Many-to-Many via junction table)
   - `product_sizes.product_id` → `products.id`
   - `product_sizes.size_id` → `sizes.id`

4. **Orders → Shipping Costs** (Many-to-One)
   - `orders.selected_shipping_area` → `shipping_costs.id`

5. **Ordered Products → Orders** (Many-to-One)
   - `ordered_products.order_id` → `orders.id` (CASCADE DELETE/UPDATE)

6. **Ordered Products → Products** (Many-to-One)
   - `ordered_products.product_id` → `products.id` (NULL ON DELETE)

7. **Sessions → Users** (Many-to-One)
   - `sessions.user_id` → `users.id`

8. **Orders → Customers** (Many-to-One) - Future relationship
   - When orders table is updated, `orders.customer_id` → `customers.id`

## Indexes

- `users.email` - UNIQUE
- `customers.email` - UNIQUE
- `customers.phone_number` - INDEX
- `products.slug` - UNIQUE
- `product_categories.slug` - UNIQUE
- `product_categories.name` - UNIQUE
- `orders.order_tracking_id` - UNIQUE
- `sessions.user_id` - INDEX
- `sessions.last_activity` - INDEX
- `jobs.queue` - INDEX
- `failed_jobs.uuid` - UNIQUE

## Important Business Rules

1. **Product Pricing**: Final price = base_price + color_extra_price + size_extra_price
2. **Order Total**: Calculated from ordered products + shipping costs
3. **Product Deletion**: When a product is deleted, its colors are cascade deleted, but ordered_products references become NULL
4. **Order Tracking**: Each order has a unique 32-character tracking ID
5. **Image Storage**: Product images are stored as JSON arrays, allowing multiple images per product
6. **Carousel Sorting**: Images are displayed based on the `sort` column value

## Migration History

The database has undergone several iterations with modifications:
1. Initial schema creation (users, products, categories, orders)
2. Added order tracking ID field
3. Added product total price calculation field
4. Added carousel image sorting capability
5. Added company logo dimensions support

## Database Configuration

Default connection settings (from .env.example):
- **Connection Type**: MariaDB/MySQL
- **Default Database**: shop_hoyejabe
- **Port**: 3306
- **Character Set**: UTF-8
- **Collation**: utf8mb4_unicode_ci (Laravel default)