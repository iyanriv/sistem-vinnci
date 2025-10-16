# Vinnci Interior Company System - Technical Specification

## 1. Executive Summary

The Vinnci system is a comprehensive web-based platform designed to digitize and streamline the interior car seat replacement business. The system addresses the primary pain point of manual, physically-delivered warranties by implementing a fully digital warranty management system alongside customer booking, company profile management, and administrative controls.

## 2. System Overview

### 2.1 Core Objectives
- Replace manual warranty printing with digital warranty cards featuring expiration countdowns
- Enable online booking transactions with down payment processing
- Provide a browsable company profile for prospective customers
- Implement role-based access for customers and administrators
- Automate warranty claim processing and validation

### 2.2 Target Users
- **Guest Customers**: Browse products, services, and company information
- **Registered Customers**: Book services, view warranties, submit claims
- **Administrators**: Manage CMS content, products, materials, warranties, and system operations

## 3. System Architecture

### 3.1 High-Level Architecture
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Frontend      │    │   Backend API   │    │   Database      │
│   (Next.js)     │◄──►│   (Node.js)     │◄──►│   (PostgreSQL)  │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │                       │
         │              ┌─────────────────┐              │
         │              │   File Storage  │              │
         └──────────────►│   (AWS S3)      │◄─────────────┘
                        └─────────────────┘
```

### 3.2 Technology Stack

#### Frontend
- **Framework**: Next.js 14 (React 18)
- **Styling**: Tailwind CSS
- **State Management**: React Context + Zustand
- **Form Handling**: React Hook Form + Zod validation
- **UI Components**: Headless UI + Custom components
- **Charts**: Chart.js / Recharts for analytics

#### Backend
- **Runtime**: Node.js 18+
- **Framework**: Express.js
- **Database**: PostgreSQL 14+
- **ORM**: Prisma
- **Authentication**: JWT + bcrypt
- **File Upload**: Multer + AWS S3
- **Email**: Nodemailer
- **Payment**: Stripe
- **Validation**: Joi / Zod

#### Infrastructure
- **Deployment**: Docker + AWS/Azure
- **CDN**: CloudFront (for static assets)
- **Monitoring**: Sentry + custom logging
- **CI/CD**: GitHub Actions

## 4. Database Schema

### 4.1 Core Entities

#### Users Table
```sql
- id: UUID (Primary Key)
- email: String (Unique)
- password: String (Hashed)
- name: String
- phone: String (Optional)
- role: Enum (CUSTOMER, ADMIN)
- isActive: Boolean
- emailVerified: Boolean
- createdAt: DateTime
- updatedAt: DateTime
```

#### Products Table
```sql
- id: UUID (Primary Key)
- name: String
- description: Text
- category: String (e.g., "car-seat-replacement", "interior-accessories")
- basePrice: Decimal
- images: JSON (Array of image URLs)
- specifications: JSON (Car model compatibility, material types, etc.)
- isActive: Boolean
- createdAt: DateTime
- updatedAt: DateTime
```

#### Materials Table
```sql
- id: UUID (Primary Key)
- name: String
- type: String (leather, fabric, synthetic, etc.)
- description: Text
- pricePerUnit: Decimal
- stockQuantity: Integer
- specifications: JSON (color, texture, durability, etc.)
- images: JSON (Array of material images)
- isActive: Boolean
- createdAt: DateTime
- updatedAt: DateTime
```

#### Services Table
```sql
- id: UUID (Primary Key)
- name: String
- description: Text
- basePrice: Decimal
- duration: Integer (in hours)
- materials: JSON (Required materials and quantities)
- category: String (installation, repair, custom-fabrication, etc.)
- isActive: Boolean
- createdAt: DateTime
- updatedAt: DateTime
```

#### Bookings Table
```sql
- id: UUID (Primary Key)
- customerId: UUID (Foreign Key → Users)
- serviceId: UUID (Foreign Key → Services)
- selectedProducts: JSON (Array of product IDs and quantities)
- selectedMaterials: JSON (Array of material IDs and quantities)
- totalAmount: Decimal
- downPayment: Decimal
- downPaymentStatus: Enum (PENDING, PAID, FAILED, REFUNDED)
- status: Enum (PENDING, CONFIRMED, IN_PROGRESS, COMPLETED, CANCELLED)
- scheduledDate: DateTime
- completedDate: DateTime (Nullable)
- notes: Text (Optional)
- createdAt: DateTime
- updatedAt: DateTime
```

#### Warranties Table
```sql
- id: UUID (Primary Key)
- customerId: UUID (Foreign Key → Users)
- bookingId: UUID (Foreign Key → Bookings)
- warrantyNumber: String (Unique)
- coverageDetails: JSON (What's covered, exclusions)
- durationMonths: Integer
- startDate: DateTime
- expirationDate: DateTime
- isActive: Boolean
- qrCodeUrl: String (URL to QR code image)
- pdfUrl: String (URL to warranty PDF)
- createdAt: DateTime
- updatedAt: DateTime
```

#### Warranty Claims Table
```sql
- id: UUID (Primary Key)
- warrantyId: UUID (Foreign Key → Warranties)
- claimNumber: String (Unique)
- description: Text
- evidenceFiles: JSON (Array of file URLs)
- status: Enum (SUBMITTED, UNDER_REVIEW, APPROVED, REJECTED, COMPLETED)
- adminNotes: Text
- submittedAt: DateTime
- processedAt: DateTime (Nullable)
- processedBy: UUID (Foreign Key → Users, Admin)
```

#### CMS Content Table
```sql
- id: UUID (Primary Key)
- page: String (home, about, contact, etc.)
- section: String (hero_banner, about_text, contact_info, etc.)
- contentType: Enum (TEXT, IMAGE, BANNER, TESTIMONIAL)
- content: JSON (Dynamic content structure)
- isActive: Boolean
- createdAt: DateTime
- updatedAt: DateTime
```

## 5. API Specification

### 5.1 Authentication Endpoints
```
POST /api/auth/register
POST /api/auth/login
POST /api/auth/logout
POST /api/auth/forgot-password
POST /api/auth/reset-password
GET  /api/auth/profile
PUT  /api/auth/profile
POST /api/auth/verify-email
```

### 5.2 Public Endpoints (Guest Access)
```
GET  /api/cms/homepage
GET  /api/cms/about
GET  /api/cms/contact
GET  /api/products
GET  /api/products/:id
GET  /api/services
GET  /api/services/:id
GET  /api/materials
```

### 5.3 Customer Endpoints
```
GET  /api/customer/bookings
POST /api/customer/bookings
GET  /api/customer/bookings/:id
PUT  /api/customer/bookings/:id/cancel
GET  /api/customer/warranties
GET  /api/customer/warranties/:id
POST /api/customer/warranties/:id/claims
GET  /api/customer/warranties/:id/claims
```

### 5.4 Admin Endpoints
```
# CMS Management
GET  /api/admin/cms/:page
PUT  /api/admin/cms/:page
POST /api/admin/cms/upload-image

# Product Management
GET    /api/admin/products
POST   /api/admin/products
GET    /api/admin/products/:id
PUT    /api/admin/products/:id
DELETE /api/admin/products/:id

# Material Management
GET    /api/admin/materials
POST   /api/admin/materials
GET    /api/admin/materials/:id
PUT    /api/admin/materials/:id
DELETE /api/admin/materials/:id

# Service Management
GET    /api/admin/services
POST   /api/admin/services
GET    /api/admin/services/:id
PUT    /api/admin/services/:id
DELETE /api/admin/services/:id

# Warranty Management
GET  /api/admin/warranties
POST /api/admin/warranties
GET  /api/admin/warranties/:id
PUT  /api/admin/warranties/:id
POST /api/admin/warranties/:id/validate-claim
PUT  /api/admin/warranty-claims/:claimId

# User Management
GET  /api/admin/users
GET  /api/admin/users/:id
PUT  /api/admin/users/:id
```

### 5.5 Payment Endpoints
```
POST /api/payments/create-intent
POST /api/payments/confirm
POST /api/payments/webhook
GET  /api/payments/status/:bookingId
```

## 6. Frontend Architecture

### 6.1 Page Structure

#### Public Pages
- **Homepage** (`/`): Hero banner, featured products, services overview
- **Products** (`/products`): Product catalog with filtering and search
- **Services** (`/services`): Service listings with descriptions and pricing
- **About Us** (`/about`): Company information, team, history
- **Contact** (`/contact`): Contact form and location information

#### Customer Portal (Authentication Required)
- **Dashboard** (`/dashboard`): Overview of bookings, warranties, profile
- **Bookings** (`/bookings`): Booking history and status tracking
- **Services** (`/services-book`): Service selection and booking
- **Materials** (`/materials`): Available materials for selection
- **Warranties** (`/warranties`): Active warranties with countdown timers
- **Profile** (`/profile`): Personal information and account settings

#### Admin Panel (Admin Role Required)
- **Admin Dashboard** (`/admin`): System overview, analytics, recent activities
- **CMS Management** (`/admin/cms`): Content management for public pages
- **Product Management** (`/admin/products`): Product catalog administration
- **Material Management** (`/admin/materials`): Material inventory management
- **Service Management** (`/admin/services`): Service configuration
- **Warranty Management** (`/admin/warranties`): Warranty creation and claims processing
- **User Management** (`/admin/users`): Customer account administration
- **Bookings** (`/admin/bookings`): All booking management and status updates

### 6.2 Component Architecture

#### Layout Components
- `Header`: Navigation with authentication state
- `Footer`: Site information and links
- `Sidebar`: Admin panel navigation
- `Breadcrumbs`: Page navigation hierarchy

#### Business Components
- `ProductCard`: Product display with quick actions
- `ServiceCard`: Service information display
- `BookingForm`: Multi-step booking process
- `WarrantyCard`: Warranty display with countdown
- `WarrantyClaimForm`: Claim submission interface
- `PaymentForm`: Secure payment processing
- `FileUpload`: Document/image upload component

#### UI Components
- `Modal`: Dialog overlays
- `Toast`: Notification system
- `DataTable`: Sortable, filterable data displays
- `Forms`: Validated form components
- `LoadingSpinner`: Loading state indicators
- `CountdownTimer`: Real-time countdown display

## 7. Feature Specifications

### 7.1 Digital Warranty System

#### Core Features
1. **Warranty Generation**: Automatic creation upon booking completion
2. **Expiration Countdown**: Real-time display showing days/hours/minutes remaining
3. **QR Code Integration**: Unique QR codes for warranty verification
4. **PDF Generation**: Downloadable warranty documents
5. **Claim Submission**: File upload for evidence and claim tracking
6. **Expiration Reminders**: Automated email notifications (7 days, 1 day before)

#### Technical Implementation
- Server-side expiration calculation with timezone handling
- Real-time countdown using WebSocket or periodic polling
- QR code generation using `qrcode` library
- PDF generation using `puppeteer` or similar
- Email templates for automated notifications

### 7.2 Booking System with Payment

#### Booking Workflow
1. **Service Selection**: Choose from available interior services
2. **Product/Material Selection**: Select specific products and materials
3. **Customization Options**: Color, material type, additional features
4. **Scheduling**: Select preferred date and time
5. **Down Payment**: Calculate and process initial payment
6. **Confirmation**: Email confirmation with booking details
7. **Warranty Generation**: Automatic creation upon service completion

#### Payment Integration
- Stripe integration for secure payment processing
- Support for multiple payment methods (cards, digital wallets)
- Payment status tracking and webhook handling
- Refund processing for cancelled bookings
- Transaction history and receipts

### 7.3 Admin Panel Features

#### CMS Management
- Dynamic homepage banner editing
- About us content management
- Contact information updates
- Image and media library management
- SEO metadata management

#### Inventory Management
- Product catalog with specifications
- Material inventory tracking
- Pricing management
- Stock level monitoring
- Supplier information

#### Warranty Administration
- Warranty creation and assignment
- Claim processing and validation
- Expiration monitoring
- QR code generation and verification
- Analytics and reporting

## 8. Security Considerations

### 8.1 Authentication & Authorization
- JWT-based authentication with refresh tokens
- Role-based access control (RBAC)
- Password hashing with bcrypt
- Rate limiting for authentication endpoints
- CSRF protection for form submissions

### 8.2 Data Protection
- Input validation and sanitization
- SQL injection prevention via ORM
- XSS protection with content security policies
- Secure file upload handling
- PII encryption for sensitive customer data

### 8.3 Payment Security
- PCI compliance for payment processing
- Secure transmission of payment data
- Webhook signature verification
- Transaction logging and audit trails

## 9. Performance Optimization

### 9.1 Database Optimization
- Proper indexing for frequently queried fields
- Query optimization with Prisma
- Connection pooling for database efficiency
- Database query caching strategies

### 9.2 Frontend Performance
- Code splitting and lazy loading
- Image optimization and CDN usage
- Component memoization
- Service worker for offline functionality

### 9.3 Caching Strategy
- Redis for session storage
- CDN for static assets
- Database query result caching
- API response caching where appropriate

## 10. Deployment & Infrastructure

### 10.1 Environment Setup
- Docker containerization for consistent deployments
- Environment-specific configuration management
- Database migration scripts
- Seed data for initial setup

### 10.2 Monitoring & Logging
- Application performance monitoring
- Error tracking and alerting
- Database performance monitoring
- User activity logging

### 10.3 Backup & Recovery
- Automated database backups
- File storage backup strategy
- Disaster recovery procedures
- Data retention policies

## 11. Testing Strategy

### 11.1 Unit Testing
- Backend API endpoint testing
- Frontend component testing
- Database model testing
- Utility function testing

### 11.2 Integration Testing
- API integration testing
- Payment gateway testing
- Email service testing
- File upload testing

### 11.3 End-to-End Testing
- Complete user journey testing
- Booking workflow testing
- Warranty claim process testing
- Admin panel functionality testing

## 12. Timeline & Phased Implementation

### Phase 1: Foundation (4-6 weeks)
- Database schema implementation
- Authentication system
- Basic API structure
- Admin panel scaffolding

### Phase 2: Core Features (6-8 weeks)
- Product and service management
- Booking system foundation
- Basic customer portal
- Payment integration

### Phase 3: Warranty System (4-6 weeks)
- Digital warranty generation
- Countdown functionality
- Claim submission system
- QR code implementation

### Phase 4: CMS & Polish (3-4 weeks)
- Company profile pages
- CMS content management
- UI/UX refinements
- Performance optimization

### Phase 5: Testing & Launch (2-3 weeks)
- Comprehensive testing
- Security audits
- Deployment preparation
- Production launch

## 13. Success Metrics

### 13.1 Business Metrics
- Reduction in manual warranty processing time
- Increase in online booking conversion rate
- Customer satisfaction improvement
- Administrative efficiency gains

### 13.2 Technical Metrics
- System uptime and availability
- Page load times
- API response times
- Error rates and resolution times

This technical specification provides a comprehensive roadmap for developing the Vinnci interior company system, addressing all core requirements while ensuring scalability, security, and maintainability.