# Tech Stack for Sistem-Vinnci

This document explains, in everyday terms, the technology choices for the Sistem-Vinnci project—a web system that combines a company profile with a product warranty management module. You don’t need to be a tech expert to understand why each technology was chosen and how it helps the project.

## 1. Frontend Technologies  
These are the tools that run in your web browser and shape what you see and interact with.

• React  
  - A popular JavaScript library for building interactive web pages.  
  - Makes it easy to update parts of the page without reloading the whole site (for example, showing warranty status without a full page refresh).  

• React Router  
  - Lets us define multiple “pages” inside a single-page app.  
  - Enables smooth navigation between Company Profile, Warranty Dashboard, Login, and other screens.  

• Axios  
  - A simple tool for talking to the backend (server) and fetching or sending data (like registering a product or loading company details).  

• Tailwind CSS  
  - A styling toolkit that provides ready-made design classes (colors, spacing, typography).  
  - Helps us build a clean, consistent, and responsive look quickly—so the site works well on phones, tablets, and desktops.  

• Vite (or Create React App)  
  - A build tool that bundles and optimizes all our code and assets for fast page loading.  
  
**How these enhance user experience:**  
• Fast page updates and visual feedback when interacting with forms or menus.  
• Consistent design across different device sizes.  
• Smooth navigation feels like a native app rather than a clunky website.

## 2. Backend Technologies  
These tools run on the server, where they process data, enforce business rules, and interact with the database.

• Node.js with Express  
  - Node.js lets us write server–side code in JavaScript (the same language we use on the frontend).  
  - Express is a lightweight framework that handles incoming requests (like “register this product”) and decides what to do next.  

• PostgreSQL Database  
  - A reliable, open-source database system ideal for structured data (company profiles, product records, warranty details, user accounts).  
  
• Sequelize (ORM)  
  - A library that simplifies talking to PostgreSQL.  
  - Lets developers work with JavaScript objects instead of writing raw database queries.  

• RESTful API  
  - A set of clearly defined web addresses (endpoints) for each operation, such as `GET /company-profile` or `POST /warranties`.  
  - Makes it easy for the frontend (and future external systems) to interact with the server.

• Passport.js with JWT (JSON Web Tokens)  
  - Passport.js provides user authentication tools.  
  - JWTs are secure tokens that prove a user’s identity without storing session data on the server.  

**How these work together:**  
1. The frontend sends a request (for example, “give me the user’s warranty list”).  
2. Express receives it, checks the user’s JWT, and passes the request to a controller.  
3. The controller uses Sequelize to retrieve data from PostgreSQL.  
4. The data is sent back to the frontend in JSON format.

## 3. Infrastructure and Deployment  
This section covers where the code lives, how it gets updated, and how we make sure the system is stable and scalable.

• Version Control: Git & GitHub  
  - Git tracks all changes, so we can safely add features or roll back mistakes.  
  - GitHub hosts the repository and enables team collaboration through pull requests and code reviews.

• Continuous Integration / Continuous Deployment (CI/CD): GitHub Actions  
  - Automatically runs tests and linters whenever code is pushed or a pull request is opened.  
  - Deploys the latest approved code to hosting when changes are merged.

• Hosting Platform: Amazon Web Services (AWS)  
  - EC2 (virtual servers) or AWS Elastic Beanstalk for running the backend.  
  - S3 (static file storage) and CloudFront (content delivery network) for serving the frontend quickly around the globe.  
  - RDS (managed PostgreSQL) for the database.

• Containerization (Optional): Docker  
  - Packages the app and its dependencies into a portable container.  
  - Ensures the app runs the same way in every environment (local, staging, production).

**Benefits of these choices:**  
• Reliable and repeatable deployments—reduces human error.  
• Ability to scale servers up or down based on demand.  
• Clear tracking of code changes and easy collaboration among developers.

## 4. Third-Party Integrations  
These external services add functionality without having to build everything from scratch.

• SendGrid or Mailgun (Email Service)  
  - Sends automated emails and notifications (e.g., warranty expiration reminders, claim status updates).  

• Twilio (SMS Notifications)  
  - Optional service for sending text-message alerts alongside emails.

• Google Analytics  
  - Tracks user behavior on the site (page views, form submissions) to help administrators understand usage patterns.

• Sentry (Error Tracking)  
  - Monitors the live application for runtime errors and crashes, alerting developers in real time.

**How they enhance functionality:**  
• Automated, reliable communication with customers.  
• Insights into how users interact with the system.  
• Faster identification and resolution of issues.

## 5. Security and Performance Considerations  
Key measures to protect data and keep the user experience smooth.

Security Measures:
• HTTPS Everywhere  
  - All data between the browser and server is encrypted.  

• Data Encryption at Rest  
  - Sensitive information in the database (like passwords) is stored in encrypted form.  

• Input Validation & Sanitization  
  - Ensures that all user input is checked for malicious content (protects against SQL injection and cross-site scripting).  

• Rate Limiting & CORS  
  - Prevents abuse by limiting how often a single client can make requests.  
  - Configures which domains are allowed to access the API.

Performance Optimizations:
• Caching with Redis  
  - Stores frequently requested data (like company profile info) in a fast, in-memory store to reduce database load.  

• Lazy Loading & Code Splitting  
  - The frontend only loads code needed for the current page, speeding up initial load times.  

• Image Optimization & CDN  
  - Uses compressed images and global content delivery networks to serve assets quickly to users everywhere.  

## 6. Conclusion and Overall Tech Stack Summary  
Sistem-Vinnci’s technology choices are designed to balance ease of development, reliability, and user-friendly performance:

• **Frontend:** React + Tailwind CSS for a fast, responsive, and maintainable user interface.  
• **Backend:** Node.js, Express, and PostgreSQL with Sequelize for solid API and data management.  
• **Infrastructure:** GitHub Actions, AWS, and optional Docker for consistent, scalable deployments.  
• **Integrations:** SendGrid/Mailgun, Twilio, Google Analytics, and Sentry to handle communication, insights, and error monitoring.  
• **Security & Performance:** HTTPS, encryption, input checks, caching, and CDNs to keep data safe and pages lightning-fast.

These technologies work together to support the project’s goals: a clear company profile, robust warranty management, secure user access, and a smooth experience for both customers and administrators.