# Backend Structure Document for Sistem-Vinnci

This document describes the backend architecture, database setup, APIs, hosting, infrastructure, security, and maintenance plans for the Sistem-Vinnci project. It is written in everyday language so anyone can understand how the system is put together.

## 1. Backend Architecture

**Overall Design**
- We’ll build a single backend service (a monolith) using **Node.js** and **Express**. This keeps things simple at first while still letting us break parts into modules.
- We follow the **MVC (Model-View-Controller)** pattern:
  - **Models** hold data definitions and database interactions.
  - **Controllers** handle incoming requests, apply business logic, and send back responses.
  - **Routes** (the “view” in our case) map URLs to controllers, directing traffic.

**Scalability, Maintainability, Performance**
- **Modular Code**: We separate features into folders (e.g., `auth`, `warranty`, `profile`) so teams can work independently.
- **Containerization**: Using Docker lets us run multiple copies of the backend behind a load balancer, easily scaling up when needed.
- **Connection Pooling**: Our database driver reuses connections, which reduces overhead under heavy load.
- **Caching Layer**: Briefly stores frequent reads in Redis (like warranty lookups) to speed up response times.

## 2. Database Management

**Technology Choice**
- Relational database: **PostgreSQL** — it’s reliable, open-source, and handles complex queries well.
- Caching store: **Redis** — used for short-term data like session information or frequently accessed warranty status.

**Data Handling Practices**
- **Structured Data**: All core entities (users, products, warranties, claims) live in tables with clear relationships.
- **Migrations**: We use a migration tool (like Knex or Sequelize CLI) to evolve the database schema in a controlled way.
- **Backups**: Automated nightly backups to S3. We keep daily snapshots for seven days, weekly archives for four weeks.
- **Connection Pool**: Limits and reuses database connections to avoid overload.
- **Indexes**: Placed on frequently queried columns (e.g., product serial number, user email) to speed up lookups.

## 3. Database Schema

**Human-Readable Overview**
- **Users**: People who log in (customers or admins). Stores email, password hash, role.
- **Roles**: Defines access levels (admin, customer).
- **Company_Profile**: Holds text, images, and links for the public company page.
- **Products**: Records each product sold, including name and serial number.
- **Warranties**: Tracks warranty start and end dates for each registered product.
- **Warranty_Claims**: Holds customer claims, attachments, status (pending, approved, rejected).

**SQL Schema (PostgreSQL)**
```sql
CREATE TABLE roles (
  id SERIAL PRIMARY KEY,
  name VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  email VARCHAR(255) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role_id INTEGER REFERENCES roles(id),
  created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE company_profile (
  id SERIAL PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  logo_url VARCHAR(500),
  contact_email VARCHAR(255),
  updated_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE products (
  id SERIAL PRIMARY KEY,
  serial_number VARCHAR(100) UNIQUE NOT NULL,
  name VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE warranties (
  id SERIAL PRIMARY KEY,
  user_id INTEGER REFERENCES users(id),
  product_id INTEGER REFERENCES products(id),
  purchase_date DATE NOT NULL,
  expiry_date DATE NOT NULL,
  registered_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE warranty_claims (
  id SERIAL PRIMARY KEY,
  warranty_id INTEGER REFERENCES warranties(id),
  claim_date DATE DEFAULT CURRENT_DATE,
  description TEXT,
  status VARCHAR(50) DEFAULT 'pending',
  attachment_url VARCHAR(500),
  processed_at TIMESTAMP
);
```  

## 4. API Design and Endpoints

We expose a **RESTful API**. Below are key endpoints:

**Authentication**
- `POST /api/auth/register` — Create a new user account.
- `POST /api/auth/login` — Log in and receive a JWT token.

**User & Role**
- `GET /api/users/me` — Get current user profile.
- `GET /api/roles` — (Admin only) List all roles.

**Company Profile**
- `GET /api/company` — Public info about the company.
- `PUT /api/company` — (Admin only) Update company details.

**Product & Warranty**
- `POST /api/products/register` — Register a product under warranty.
- `GET /api/warranties` — List warranties for logged-in user.
- `GET /api/warranties/:id` — Get warranty details (check expiry, status).

**Claims**
- `POST /api/claims` — Submit a new warranty claim (with attachments).
- `GET /api/claims` — (Admin only) List all claims.
- `PUT /api/claims/:id` — (Admin only) Update claim status.

All endpoints require a valid JWT in the `Authorization` header (`Bearer <token>`) except public ones like `GET /api/company`.

## 5. Hosting Solutions

**Cloud Provider**: AWS (Amazon Web Services)
- **Compute**: EC2 instances (or ECS Fargate for containers).
- **Database**: Amazon RDS for PostgreSQL.
- **File Storage**: S3 for uploaded images and backups.
- **Domain & SSL**: Amazon Route 53 for DNS and AWS Certificate Manager for SSL certificates.

**Benefits**
- **Reliability**: Managed services take care of hardware failures and automatic failover.
- **Scalability**: Auto-scaling groups spin up new instances under load.
- **Cost Control**: Pay for what we use; we can size instances to our current needs and grow later.

## 6. Infrastructure Components

- **Load Balancer**: AWS Application Load Balancer distributes incoming traffic across multiple backend instances.
- **Containerization**: Docker images for the backend ensure consistent environments from development to production.
- **Caching**: Redis (hosted via AWS ElastiCache) speeds up read-heavy operations.
- **CDN**: CloudFront delivers static assets (images, JS, CSS) to users from the nearest edge location.
- **Networking**: VPC with public and private subnets keeps the database in a private network for security.

## 7. Security Measures

- **HTTPS Everywhere**: All traffic runs over TLS.
- **JWT Authentication**: Tokens carry user identity and roles, signed and time-limited.
- **Role-Based Access Control (RBAC)**: Users and admins only see what they’re allowed to.
- **Input Validation**: Sanitizing all incoming data to prevent SQL injection and cross-site scripting (XSS).
- **Encryption at Rest**: RDS and S3 encrypt data on disk.
- **Secrets Management**: Use AWS Secrets Manager or parameter store for database credentials and API keys.
- **Audit Logging**: Record critical operations (e.g., claim approvals) for compliance.

## 8. Monitoring and Maintenance

- **Logging**: Centralized logs go to AWS CloudWatch Logs or a service like Datadog.
- **Metrics & Alerts**: Track CPU, memory, error rates, and set alerts for thresholds (e.g., high 5xx errors).
- **Health Checks**: The load balancer pings a `/health` endpoint; unhealthy instances are replaced automatically.
- **Regular Updates**: Schedule monthly dependency reviews and patches for security updates.
- **Backups & Recovery**: Automated nightly RDS backups with point-in-time recovery enabled.

## 9. Conclusion and Overall Backend Summary

In summary, the Sistem-Vinnci backend is a modular, Node.js-based service with a PostgreSQL database. It uses common design patterns (MVC), modern infrastructure (Docker, AWS), and strong security measures (TLS, JWT, RBAC). Key components like a load balancer, Redis cache, and CDN ensure fast, reliable performance. Automated monitoring and backups keep the system healthy and secure. This setup aligns with our goal of providing a user-friendly company profile and robust warranty management platform, while remaining easy to maintain and scale as the project grows.