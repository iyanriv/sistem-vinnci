# Technical Specification Portal Pelanggan PT. Vinnci Nusantara Cemerlang

**Dokumen Version**: 1.0  
**Tanggal**: 16 Oktober 2025  
**Prepared by**: Sistem Analis  
**Project**: Portal Pelanggan dengan Sistem Booking dan Garansi

---

## 1. Lingkungan Pengembangan & Teknologi Inti

### 1.1 Stack Teknologi Utama

#### Backend Framework
- **Framework**: CodeIgniter 4 (CI4)
- **Version**: 4.4.4 atau terbaru
- **Alasan Pemilihan**: 
  - Lightweight dan fast performance
  - Built-in security features (CSRF, XSS protection)
  - Comprehensive documentation
  - Mature ecosystem dengan libraries lengkap
  - Support PHP 8.0+ untuk performa optimal

#### Database
- **Database**: MySQL 8.0+
- **Alasan Pemilihan**:
  - Open source dengan community support kuat
  - ACID compliance untuk data integrity
  - Performance optimal untuk query kompleks
  - Support foreign key constraints
  - Backup dan restore capabilities

#### Bahasa Pemrograman
- **Bahasa**: PHP 8.1+
- **Minimum Version**: PHP 8.0
- **Alasan Pemilihan**:
  - Native compatibility dengan CodeIgniter 4
  - Improved performance dengan JIT compiler
  - Modern syntax dan type safety
  - Security enhancements
  - Extended support hingga 2025

#### Frontend Technologies
- **HTML5**: Semantic markup untuk struktur konten
- **CSS3**: Styling dengan Flexbox/Grid dan animations
- **JavaScript (ES6+)**: Interactive frontend functionality
- **Library Tambahan**:
  - jQuery 3.6+ (jika diperlukan untuk compatibility)
  - Chart.js untuk visualisasi data garansi
  - SweetAlert2 untuk user-friendly notifications

### 1.2 Development Tools

#### Version Control
- **Git**: Version control system
- **Repository**: GitHub/Bitbucket dengan branching strategy

#### Package Management
- **Composer**: PHP dependency management
- **NPM**: Frontend package management

#### Development Environment
- **Local**: XAMPP/Laragon dengan PHP 8.1+
- **Production**: Linux server dengan Apache/Nginx
- **Testing**: PHPUnit untuk unit testing

---

## 2. Detail Arsitektur Data (Database Schema & Relasi)

### 2.1 Entity Relationship Overview

Sistem ini memiliki 3 tabel utama yang saling terkait membentuk ekosistem booking dan garansi:

```
tb_customer (1) ←→ (N) tb_booking (1) ←→ (1) tb_garansi
```

### 2.2 Struktur Tabel Detail

#### tb_customer (Master Data Pelanggan)
```sql
CREATE TABLE tb_customer (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,           -- Hashed password
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    email_verified TINYINT(1) DEFAULT 0,
    email_token VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_email (email),
    INDEX idx_created_at (created_at)
);
```

**Keterangan Penting**:
- `password` WAJIB menggunakan hashing dengan `password_hash()`
- `email` UNIQUE untuk login credentials
- `email_verified` untuk email verification system

#### tb_booking (Transaksi Center)
```sql
CREATE TABLE tb_booking (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    produk_id INT NOT NULL,
    varian_material_id INT,
    kode_booking VARCHAR(20) UNIQUE NOT NULL,  -- Auto-generated
    tanggal_booking DATE NOT NULL,
    status ENUM('pending', 'confirmed', 'rejected', 'in_progress', 'completed') DEFAULT 'pending',
    total_amount DECIMAL(12,2) NOT NULL,
    dp_amount DECIMAL(12,2) NOT NULL,
    dp_proof_file VARCHAR(255),               -- Path ke uploaded file
    dp_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    admin_notes TEXT,
    tanggal_selesai_pengerjaan DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (customer_id) REFERENCES tb_customer(id) ON DELETE CASCADE,
    FOREIGN KEY (produk_id) REFERENCES tb_produk(id),
    FOREIGN KEY (varian_material_id) REFERENCES tb_varian_material(id),
    
    INDEX idx_customer_id (customer_id),
    INDEX idx_status (status),
    INDEX idx_tanggal_booking (tanggal_booking),
    INDEX idx_kode_booking (kode_booking)
);
```

**Hubungan Foreign Key**:
- `customer_id` → `tb_customer.id` (Many-to-One)
- `produk_id` → `tb_produk.id` (Many-to-One)
- `varian_material_id` → `tb_varian_material.id` (Many-to-One)

#### tb_garansi (Metadata Garansi)
```sql
CREATE TABLE tb_garansi (
    id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT NOT NULL UNIQUE,
    nomor_garansi VARCHAR(50) UNIQUE NOT NULL,  -- Auto-generated
    masa_berlaku INT NOT NULL,                   -- Dalam bulan
    status ENUM('active', 'expired', 'claimed', 'voided') DEFAULT 'active',
    tanggal_mulai DATE NOT NULL,                 -- = tanggal_selesai_pengerjaan
    tanggal_berakhir DATE GENERATED ALWAYS AS (
        DATE_ADD(tanggal_mulai, INTERVAL masa_berlaku MONTH)
    ) STORED,
    catatan_klaim TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (booking_id) REFERENCES tb_booking(id) ON DELETE CASCADE,
    
    INDEX idx_booking_id (booking_id),
    INDEX idx_nomor_garansi (nomor_garansi),
    INDEX idx_status (status),
    INDEX idx_tanggal_berakhir (tanggal_berakhir)
);
```

**Ketergantungan Utama**:
- `booking_id` FOREIGN KEY ke `tb_booking.id` - ini adalah link utama
- `tanggal_mulai` = `tb_booking.tanggal_selesai_pengerjaan`
- `tanggal_berakhir` dihitung otomatis: `tanggal_mulai + masa_berlaku`

### 2.3 Tabel Pendukung

#### tb_produk (Katalog Produk)
```sql
CREATE TABLE tb_produk (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_produk VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    harga_default DECIMAL(12,2),
    masa_garansi_default INT,                  -- Dalam bulan
    kategori_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_kategori_id (kategori_id),
    INDEX idx_nama_produk (nama_produk)
);
```

#### tb_varian_material
```sql
CREATE TABLE tb_varian_material (
    id INT PRIMARY KEY AUTO_INCREMENT,
    produk_id INT NOT NULL,
    nama_varian VARCHAR(255) NOT NULL,
    tambahan_harga DECIMAL(12,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (produk_id) REFERENCES tb_produk(id) ON DELETE CASCADE,
    INDEX idx_produk_id (produk_id)
);
```

---

## 3. Spesifikasi Modul Fungsional Kritis

### 3.1 Autentikasi Customer

#### Tantangan Coding Utama:
1. **Secure Password Hashing**
   ```php
   // Registration
   $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
   
   // Login
   if (password_verify($inputPassword, $storedHash)) {
       // Login successful
   }
   ```

2. **Session Management dengan CI4**
   ```php
   // Set session
   session()->set([
       'customer_id' => $customer->id,
       'customer_email' => $customer->email,
       'logged_in' => true
   ]);
   
   // Check authentication
   if (!session()->get('logged_in')) {
       return redirect()->to('/login');
   }
   ```

3. **CSRF Protection**
   - CI4 built-in CSRF token
   - Validasi pada setiap form submission
   - Token refresh mechanism

4. **Email Verification System**
   - Generate verification token
   - Send verification email
   - Handle verification callback
   - Resend verification functionality

#### Implementasi Controller Structure:
```php
class AuthController extends BaseController
{
    public function register()        // POST - Customer registration
    public function login()          // POST - Customer login  
    public function logout()         // GET/POST - Customer logout
    public function forgotPassword() // POST - Password reset request
    public function verifyEmail()    // GET - Email verification
    public function dashboard()      // GET - Customer dashboard
}
```

### 3.2 Booking Online dengan DP

#### Tantangan Coding Utama:

1. **Secure File Upload untuk Bukti DP**
   ```php
   // Validation rules
   $rules = [
       'dp_proof' => [
           'uploaded[dp_proof]',
           'max_size[dp_proof,5120]',    // 5MB max
           'mime_in[dp_proof,image/jpeg,image/png,application/pdf]',
           'ext_in[dp_proof,jpg,jpeg,png,pdf]'
       ]
   ];
   
   // Secure file handling
   $file = $this->request->getFile('dp_proof');
   if ($file->isValid() && !$file->hasMoved()) {
       $newName = $file->getRandomName();
       $file->move(WRITEPATH . 'uploads/dp_proofs', $newName);
   }
   ```

2. **Form Validation Komprehensif**
   ```php
   $validationRules = [
       'customer_name' => 'required|min_length[3]|max_length[255]',
       'email' => 'required|valid_email',
       'phone' => 'required|regex_match[/^[0-9]{10,15}$/]',
       'produk_id' => 'required|is_natural_no_zero',
       'tanggal_booking' => 'required|valid_date[Y-m-d]',
       'dp_amount' => 'required|numeric|greater_than[0]'
   ];
   ```

3. **Booking Code Generation**
   ```php
   function generateBookingCode() {
       do {
           $code = 'BK' . date('Ym') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
       } while ($this->bookingModel->where('kode_booking', $code)->first());
       
       return $code;
   }
   ```

4. **Email Notifications**
   - Booking confirmation to customer
   - New booking notification to admin
   - Status change notifications

#### Controller Structure:
```php
class BookingController extends BaseController
{
    public function index()           // GET - Booking form
    public function create()         // POST - Process booking
    public function history()        // GET - Customer booking history
    public function detail($id)      // GET - Booking detail
    public function uploadDP()       // POST - Upload DP proof
}
```

### 3.3 Modul Admin

#### Tantangan Coding Utama:

1. **Admin Authentication Terpisah**
   ```php
   // Admin session different from customer
   session()->set([
       'admin_id' => $admin->id,
       'admin_role' => $admin->role,
       'admin_logged_in' => true
   ]);
   ```

2. **Payment Validation Interface**
   ```php
   class BookingAdminController extends BaseController
   {
       public function validatePayment($bookingId) {
           // Verify DP proof file
           // Update booking status
           // Send notification to customer
           // Create warranty record if approved
       }
   }
   ```

3. **Status Update Workflow**
   - Pending → Confirmed/Rejected
   - Confirmed → In Progress → Completed
   - Auto-create warranty on completion

4. **File Management untuk DP Proof**
   - Display uploaded images/PDF
   - Download functionality
   - Secure file access control

#### Admin Routes Structure:
```php
// Routes untuk Admin
$routes->group('/admin', ['namespace' => 'App\Controllers\Admin'], function($routes) {
    $routes->get('/', 'DashboardController::index');
    $routes->get('/login', 'AuthController::login');
    $routes->post('/login', 'AuthController::authenticate');
    $routes->get('/bookings', 'BookingController::index');
    $routes->get('/bookings/(:num)', 'BookingController::detail/$1');
    $routes->post('/bookings/(:num)/validate', 'BookingController::validatePayment/$1');
    $routes->post('/bookings/(:num)/update-status', 'BookingController::updateStatus/$1');
});
```

### 3.4 Portal Garansi dengan Countdown

#### Tantangan Coding Utama:

1. **Backend Warranty Calculation**
   ```php
   class WarrantyModel extends BaseModel
   {
       public function calculateWarrantyExpiry($bookingId) {
           $booking = $this->bookingModel->find($bookingId);
           $warranty = $this->where('booking_id', $bookingId)->first();
           
           if ($booking && $warranty) {
               $expiryDate = new DateTime($booking->tanggal_selesai_pengerjaan);
               $expiryDate->add(new DateInterval('P' . $warranty->masa_berlaku . 'M'));
               return $expiryDate;
           }
           return null;
       }
   }
   ```

2. **Real-time Countdown JavaScript**
   ```javascript
   function startCountdown(warrantyId, expiryDate) {
       setInterval(function() {
           const now = new Date().getTime();
           const distance = expiryDate - now;
           
           if (distance < 0) {
               document.getElementById('countdown-' + warrantyId).innerHTML = "EXPIRED";
               return;
           }
           
           const days = Math.floor(distance / (1000 * 60 * 60 * 24));
           const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
           const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
           const seconds = Math.floor((distance % (1000 * 60)) / 1000);
           
           document.getElementById('countdown-' + warrantyId).innerHTML = 
               days + "d " + hours + "h " + minutes + "m " + seconds + "s";
       }, 1000);
   }
   ```

3. **Automatic Warranty Creation**
   ```php
   // Triggered when booking status changes to 'completed'
   public function createWarranty($bookingId) {
       $booking = $this->bookingModel->find($bookingId);
       $produk = $this->produkModel->find($booking->produk_id);
       
       $warrantyData = [
           'booking_id' => $bookingId,
           'nomor_garansi' => $this->generateWarrantyNumber(),
           'masa_berlaku' => $produk->masa_garansi_default,
           'status' => 'active',
           'tanggal_mulai' => $booking->tanggal_selesai_pengerjaan
       ];
       
       return $this->warrantyModel->insert($warrantyData);
   }
   ```

4. **Warranty Status Management**
   - Active: Within warranty period
   - Expired: Past warranty period
   - Claimed: Warranty has been claimed
   - Voided: Cancelled due to policy violation

#### Controller Structure:
```php
class WarrantyController extends BaseController
{
    public function index()           // GET - Customer warranty dashboard
    public function detail($id)      // GET - Warranty detail view
    public function claimWarranty()  // POST - Submit warranty claim
    public function warrantyHistory() // GET - All warranties
    public function checkStatus($id) // AJAX - Check warranty status
}
```

---

## 4. Lingkup Aplikasi dan Deployment

### 4.1 Struktur URL dan Akses

#### Customer Portal
```
https://vinnci.com/login          - Halaman Login Customer
https://vinnci.com/register       - Halaman Registrasi Customer  
https://vinnci.com/portal         - Dashboard Customer (setelah login)
https://vinnci.com/portal/booking - Form Booking Online
https://vinnci.com/portal/history - Riwayat Booking
https://vinnci.com/portal/warranty - Portal Garansi
https://vinnci.com/portal/profile - Profile Management
```

#### Admin Panel
```
https://vinnci.com/admin          - Dashboard Admin
https://vinnci.com/admin/login    - Login Admin
https://vinnci.com/admin/bookings - Manajemen Booking
https://vinnci.com/admin/customers - Manajemen Customer
https://vinnci.com/admin/warranties - Manajemen Garansi
https://vinnci.com/admin/reports  - Laporan dan Analytics
```

### 4.2 Keamanan Implementasi

#### HTTPS Implementation
- **Mandatory**: Semua environment (development, staging, production)
- **SSL Certificate**: Let's Encrypt atau commercial SSL
- **HSTS Headers**: Force HTTPS connections
- **Port Configuration**: 
  - HTTP: 80 (redirect to HTTPS)
  - HTTPS: 443 (primary)

#### Password Security
```php
// Minimum 8 characters, mixed case, numbers, special characters
$passwordRules = [
    'password' => [
        'required',
        'min_length[8]',
        'regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/]'
    ]
];

// Hashing algorithm
$hashedPassword = password_hash($password, PASSWORD_ARGON2ID);
```

#### Session Security
```php
// CI4 Session Configuration
public $session = [
    'driver' => 'CodeIgniter\Session\Handlers\DatabaseHandler',
    'cookieName' => 'vinnci_session',
    'expiration' => 7200, // 2 hours
    'matchIP' => true,
    'timeToUpdate' => 300,
    'regenerateDestroy' => true,
];
```

#### File Upload Security
- **Storage Location**: Outside public directory
- **File Type Validation**: Whitelist approach
- **File Size Limits**: 5MB maximum for DP proof
- **Virus Scanning**: Implement ClamAV jika memungkinkan
- **Access Control**: Authenticated users only

### 4.3 Database Security

#### Connection Security
```php
// Database configuration with SSL
public $default = [
    'DSN'      => '',
    'hostname' => 'localhost',
    'username' => 'vinnci_user',
    'password' => 'secure_password',
    'database' => 'vinnci_portal',
    'DBDriver' => 'MySQLi',
    'DBPrefix' => '',
    'pConnect' => false,
    'DBDebug'  => (ENVIRONMENT !== 'production'),
    'charset'  => 'utf8mb4',
    'DBCollat' => 'utf8mb4_general_ci',
    'swapPre'  => '',
    'encrypt'  => [
        'ssl_verify' => true,
        'ssl_ca'     => '/path/to/ca.pem',
        'ssl_cert'   => '/path/to/client-cert.pem',
        'ssl_key'    => '/path/to/client-key.pem',
    ],
];
```

#### Input Validation
- **XSS Protection**: CI4 built-in filtering
- **SQL Injection Prevention**: Use Query Builder exclusively
- **CSRF Protection**: Enable globally, exempt only API endpoints
- **Input Sanitization**: Trim and validate all user inputs

### 4.4 Deployment Architecture

#### Production Server Requirements
- **Web Server**: Apache 2.4+ dengan mod_rewrite atau Nginx 1.18+
- **PHP Version**: 8.1+ dengan extensions:
  - php-mysql, php-curl, php-json, php-mbstring, php-xml, php-intl, php-gd
- **Database**: MySQL 8.0+ atau MariaDB 10.5+
- **Memory**: Minimum 2GB RAM, Recommended 4GB+
- **Storage**: Minimum 20GB, dengan backup system

#### Environment Configuration
```bash
# .env file for production
CI_ENVIRONMENT = production
app.baseURL = 'https://vinnci.com/'
database.default.hostname = 'localhost'
database.default.database = 'vinnci_portal'
database.default.username = 'vinnci_user'
database.default.password = 'secure_production_password'
app.sessionDriver = 'database'
app.sessionSavePath = 'ci_sessions'
app.encryptionKey = '32_character_random_key'
```

#### Backup Strategy
- **Database Backup**: Daily automated backup dengan retention 30 hari
- **File Backup**: Weekly backup untuk uploaded files
- **Code Backup**: Git tags untuk setiap release
- **Recovery Plan**: RTO 4 hours, RPO 1 hour

---

## 5. Timeline Implementasi & Milestones

### Phase 1: Foundation (Week 1-2)
- Database schema design dan migration
- Basic CI4 setup dan configuration
- Authentication system (customer & admin)

### Phase 2: Core Features (Week 3-4)  
- Booking system dengan file upload
- Admin panel untuk booking management
- Basic warranty system

### Phase 3: Advanced Features (Week 5-6)
- Warranty countdown timer
- Email notifications system
- Advanced admin features

### Phase 4: Testing & Deployment (Week 7-8)
- Comprehensive testing
- Security audit
- Production deployment
- User training

---

## 6. Risk Assessment & Mitigation

### Technical Risks
1. **File Upload Security** → Implement strict validation dan scanning
2. **Session Hijacking** → Use HTTPS dan regenerate session IDs
3. **Database Performance** → Proper indexing dan query optimization
4. **Scalability Issues** → Implement caching dan CDN

### Business Risks
1. **Data Privacy Compliance** → Implement GDPR-like data protection
2. **System Downtime** → Monitoring dan backup systems
3. **User Adoption** → Intuitive UI dan comprehensive training

---

**Document Status**: Final Version  
**Next Review Date**: 1 bulan sebelum implementasi  
**Approval Required**: Project Stakeholder, Technical Lead