# Project Requirements Document (PRD)

## 1. Project Overview

**sistem-vinnci** is a web-based platform that combines two main functions: a public-facing Company Profile and an end-to-end Warranty/Guarantee Management system. The Company Profile module lets businesses showcase their brand—"About Us," contact information, product or service offerings, and a portfolio—in a clean, responsive interface. The Warranty/Guarantee system allows customers to register products, track active warranty periods, and submit claims, while administrators can manage those warranties and claims from a secure backend panel.

This platform is being built to replace manual, paper-based warranty tracking and to give companies a modern digital storefront at the same time. Key success criteria include: 1) easy and accurate product registrations by customers, 2) intuitive administration of claims and company content, 3) fast page loads (<2s) across devices, and 4) robust data security and compliance with regional privacy rules.

---

## 2. In-Scope vs. Out-of-Scope

### In-Scope (Version 1.0)
- Public Company Profile pages (About Us, Contact, Products/Services, Portfolio)
- Secure Admin Panel to update text, images, links in the Company Profile
- Customer account creation and login (email/password)
- Product Warranty Registration form (serial number, purchase date, proof upload)
- Warranty Status Dashboard for customers (remaining coverage, expiration date)
- Warranty Claim Submission (form + photo/document attachments)
- Admin Claim Management (view, approve, reject, comment)
- Email + in-app notifications (e.g., warranty expiration reminders)
- Basic reporting: total Registered Products, Pending/Resolved Claims, Avg. Processing Time
- RESTful API endpoints for all operations
- Responsive design (desktop/tablet/mobile)

### Out-of-Scope (Deferred to Later Releases)
- Integration with external CRMs or e-commerce platforms
- Payment gateway for extended warranty sales
- Multi-language support or localization
- Native mobile apps (iOS/Android)
- Advanced analytics/dashboard visualizations
- Third-party AI or chatbots for customer support
- Role-based dashboards beyond Admin vs. Customer
- Bulk import/export beyond simple CSV downloads

---

## 3. User Flow

**Customer Journey:** A new customer lands on the public site and clicks “Sign Up.” They provide an email, create a password, and verify their account via email link. After logging in, they click “Register Product,” enter the serial number, purchase date, and optionally upload a receipt. Once submitted, they can navigate to their “My Warranties” dashboard to see each product’s status, coverage end date, and any active claims. To file a claim, they click “Submit Claim,” fill in details, attach photos, and hit “Send.” The system sends them email updates as the claim moves through Pending → Approved/Rejected.

**Administrator Journey:** An admin visits the `/admin` portal, logs in with staff credentials, and lands on the Admin Dashboard. From here, they can click “Edit Company Profile” to update About Us text or swap out images. Under “Warranty Claims,” they see a queue of new submissions; clicking a claim displays attachments and customer notes, and they can choose Approve or Reject with feedback. The admin also reviews automated reports to check total registered items or claim turnaround times. When a warranty is nearing expiration, the system has already sent reminder emails, but the admin can also trigger manual notifications if needed.

---

## 4. Core Features

- **Company Profile Module**: Public pages editable via admin, dynamic content blocks (text, images, links)
- **User Authentication**: Secure sign-up, login, password reset; role-based access (Customer vs. Admin)
- **Warranty Registration & Tracking**: Form-based registration, validity calculation, dashboard view
- **Warranty Claim Management**: Submission form, file uploads, admin review workflow, status updates
- **Notifications**: Automated email/in-app alerts for registration confirmation, claim updates, and expiration reminders
- **Reporting & Analytics**: Dashboard showing counts of registered products, claims by status, average processing times, with CSV export
- **RESTful API**: Endpoints for all CRUD operations (e.g., `/api/register`, `/api/warranties`, `/api/claims`)
- **Responsive UI**: Mobile-first design with consistent look across desktop, tablet, and phone

---

## 5. Tech Stack & Tools

- **Frontend**: React (with functional components and hooks), TypeScript, Tailwind CSS for styling, React Router for navigation
- **Backend**: Node.js 14+ with Express.js or NestJS (RESTful services), TypeScript
- **Database**: PostgreSQL for relational data (users, products, warranties, claims)
- **ORM**: Prisma or TypeORM to define models and migrations
- **Authentication**: JSON Web Tokens (JWT) for session management
- **Notifications**: Nodemailer (email), in-app notifications via WebSocket or polling
- **Dev Tools**: Git & GitHub (branching strategy), ESLint + Prettier, Jest for unit tests, Supertest for API tests
- **Deployment**: Docker containers, hosted on AWS (ECS or EKS) or DigitalOcean, CI/CD via GitHub Actions

---

## 6. Non-Functional Requirements

- **Performance**: Initial page load under 2 seconds on 3G+ connections; API response times under 300 ms for common endpoints
- **Security**: HTTPS everywhere; JWT tokens stored securely (HTTP-only cookies); data at rest encrypted; OWASP Top 10 protections (XSS, CSRF, SQLi)
- **Compliance**: GDPR-friendly data handling (user can delete personal data); CCPA considerations if targeting California
- **Scalability**: Support 1,000+ concurrent users in version 1; horizontal scaling via stateless services and managed database
- **Usability**: WCAG 2.1 AA accessibility standards; clear error messaging; form validation feedback
- **Reliability**: 99.9% uptime SLA; daily database backups; error monitoring via Sentry or equivalent

---

## 7. Constraints & Assumptions

- **Hosting Environment**: Cloud provider (AWS/DigitalOcean) offering managed PostgreSQL
- **Team Skillset**: Familiarity with JavaScript/TypeScript full-stack development
- **Third-Party Services**: Availability of email SMTP and hosting provider’s WebSocket support
- **Budget & Timeline**: MVP to be delivered in 8–12 weeks by a 3-developer team
- **Regulatory**: Target market requires GDPR compliance; no PCI scope for V1 (no payment processing yet)

---

## 8. Known Issues & Potential Pitfalls

- **File Upload Size**: Large image attachments might overload the server—limit uploads to 5 MB and use S3 storage
- **Concurrent Updates**: Race conditions if two admins edit the Company Profile simultaneously—implement optimistic locking or version checks
- **Notification Failures**: If email provider has rate limits, fallback to retry logic or secondary SMTP
- **Data Consistency**: Partial failures during multi-step warranty registration (e.g., DB error after file upload)—use database transactions and cleanup routines
- **Browser Compatibility**: Older browsers may not support modern JS features—include polyfills or transpile accordingly


---

This PRD contains all the necessary details for an AI model or development team to generate subsequent technical documents without guesswork. It defines clear boundaries, workflows, and feature requirements, ensuring alignment from design through deployment.