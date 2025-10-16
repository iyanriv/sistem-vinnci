# Security Guidelines for _sistem-vinnci_

This document defines the security requirements and best practices for the development, deployment, and maintenance of the _sistem-vinnci_ web application. It aligns with the organization’s **Core Security Principles** to ensure a secure, resilient, and maintainable system.

---

## 1. Security by Design

- **Embed security from day one:** Integrate threat modeling, architecture reviews, and security checkpoints into every phase (requirements, design, implementation, testing, deployment).
- **Least Privilege:** Grant services, modules, and users only the minimum permissions needed. For example, the warranty tracker service should only have read/write access to the `warranties` and `products` tables—not the entire database.
- **Defense in Depth:** Layer controls—e.g., network-level firewalls, API gateway rate limits, input validation, and runtime monitoring—to ensure that a failure in one layer does not expose the system.
- **Fail Securely:** On errors (e.g., database downtime), ensure the application fails gracefully without leaking stack traces or sensitive data to users or logs.

---

## 2. Authentication & Access Control

### 2.1 Authentication

- **Strong Password Policies:** Enforce minimum length (≥ 12 characters), complexity (mixed case, digits, symbols), and rotation policies. Reject common/compromised passwords using a denylist.
- **Secure Password Storage:** Hash passwords with Argon2 or bcrypt, unique salt per user, and a high work factor.
- **Session Management:**
  - Use HTTP-only, Secure, SameSite=strict cookies for session IDs.
  - Generate unpredictable, high-entropy session tokens. Invalidate on logout, rotation on privilege elevation.
  - Enforce idle and absolute timeouts (e.g., 30 minutes idle, 8 hours absolute).
- **Multi-Factor Authentication (MFA):** Provide TOTP-based MFA for administrative accounts and optionally for customers.

### 2.2 Role-Based Access Control (RBAC)

- Define roles (e.g., `Admin`, `Customer`).
- Enforce server-side authorization on every endpoint:
  - Only `Admin` can modify company profile content or approve warranty claims.
  - Customers can view/submit claims and register products they own.
- Validate JWTs (if used) with strict `alg` checks, signature verification, and expiration (`exp`).

---

## 3. Input Handling & Processing

- **Server-Side Validation:** Never trust client input. Validate all fields (length, type, format) on the backend.
- **Prevent Injection Attacks:**
  - Use parameterized queries/ORM (e.g., Sequelize, TypeORM, Prisma) to interact with the database.
  - Sanitize inputs used in file paths, commands, or XML/HTML to prevent OS command injection and XXE.
- **XSS Mitigation:**
  - Apply context-aware output encoding (e.g., HTML-encode user-supplied text in the frontend, escape JSON contexts).
  - Enforce a Content Security Policy (CSP) that restricts script sources and disables `unsafe-inline`.
- **File Upload Security (Warranty Claims):**
  - Whitelist MIME types (e.g., images: `image/jpeg`, `image/png`).
  - Enforce size limits (e.g., ≤ 5 MB per file).
  - Rename files with random UUIDs, store outside the webroot, and scan for malware.
  - Validate and sanitize filenames to prevent path traversal.
- **Redirect & Forward Validation:** Use an allow-list for any redirect URLs to prevent open-redirect vulnerabilities.

---

## 4. Data Protection & Privacy

- **Encryption in Transit:** Enforce TLS 1.2+ with HSTS. Redirect HTTP to HTTPS globally.
- **Encryption at Rest:** Encrypt sensitive fields (e.g., PII, warranty receipts) with AES-256 on the database side or application layer.
- **Secret Management:**
  - Store API keys, database credentials, and JWT signing keys in a vault (e.g., AWS Secrets Manager, HashiCorp Vault).
  - Do not commit secrets in source code or configuration files.
- **PII Handling & Compliance:**
  - Collect only necessary PII. Mask or redact in logs and responses.
  - Implement GDPR/CCPA workflows: data subject access requests, right to erasure, breach notification.
- **Secure Database Connections:** Use least-privileged database users, SSL/TLS connections, and network-level restrictions (e.g., security groups).

---

## 5. API & Service Security

- **HTTPS-Only:** All API endpoints must be reachable only over HTTPS.
- **Rate Limiting & Throttling:** Apply per-IP and per-user rate limits (e.g., 100 requests/minute) to prevent DoS and brute-force attacks.
- **CORS Policy:** Restrict origins to the approved frontend domain(s). Reject wildcard (`*`) in production.
- **Input Validation:** Enforce strict JSON schema validation on every endpoint (e.g., using AJV, Joi).
- **Least-Exposure Principle:** Return only the necessary fields in API responses. Avoid leaking internal IDs or debug info.
- **API Versioning:** Prefix endpoints with `/api/v1/` and plan for backward-compatible changes.

---

## 6. Web Application Security Hygiene

- **Anti-CSRF:** Implement synchronizer tokens or double-submit cookies for all state-changing requests.
- **Security Headers:**
  - `Content-Security-Policy`: Restrict script, style, and frame sources.
  - `Strict-Transport-Security`: `max-age=63072000; includeSubDomains; preload`.
  - `X-Content-Type-Options`: `nosniff`.
  - `X-Frame-Options`: `DENY` or CSP `frame-ancestors 'none'`.
  - `Referrer-Policy`: `no-referrer-when-downgrade` or stricter.
- **Secure Cookies:** Always set `HttpOnly`, `Secure`, and an appropriate `SameSite` attribute.
- **Subresource Integrity (SRI):** For any third-party scripts or stylesheets loaded via CDN.

---

## 7. Infrastructure & Configuration Management

- **Hardened Servers:** Disable unused services, close non-essential ports, and remove default accounts.
- **TLS Configuration:** Use strong cipher suites, disable TLS 1.0/1.1, and prefer ECDHE key exchange.
- **Configuration Management:** Keep environment-specific settings in separate, encrypted stores. Never enable debug or verbose logging in production.
- **File System Permissions:** Restrict application directories—only the application user may read/write; configuration files should be read-only.
- **Logging & Monitoring:**
  - Centralize logs (e.g., Splunk, ELK) with access controls.
  - Mask sensitive data. Monitor for anomalies (failed logins, high error rates).

---

## 8. Dependency Management

- **Secure Dependencies:** Choose actively maintained libraries with good security track records.
- **Lockfiles:** Commit `package-lock.json`, `yarn.lock`, or equivalent to ensure deterministic builds.
- **Vulnerability Scanning:** Integrate SCA tools (e.g., Dependabot, Snyk, OWASP Dependency-Check) into CI/CD pipelines to catch known CVEs.
- **Minimal Footprint:** Include only necessary packages; remove unused dependencies.

---

## 9. DevOps & CI/CD Security

- **Pipeline Integrity:** Sign build artifacts. Validate infrastructure-as-code (IaC) templates with tools like Checkov or Terraform Compliance.
- **Credential Management:** Use ephemeral build agents with minimal privileges; inject secrets at runtime via secure variables.
- **Automated Testing:** Include security tests (static code analysis, dynamic scans, penetration test scripts) in CI.
- **Deployment Controls:** Enforce RBAC on production environments. Require peer review and approval for infrastructure changes.

---

## Conclusion

Adhering to these guidelines will help ensure that _sistem-vinnci_ is built and maintained as a secure, reliable, and compliant web application. Security is a continuous effort—regularly review controls, update dependencies, and adapt to emerging threats.
