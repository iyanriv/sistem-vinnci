# Frontend Guidelines for sistem-vinnci

This document outlines the frontend architecture, design principles, and technologies used in the *sistem-vinnci* project. It’s written in everyday language so anyone—technical or not—can understand how the frontend is set up, why certain choices were made, and how to maintain or expand it.

## 1. Frontend Architecture

### 1.1 Overview
- **Framework:** React (with Create React App or Vite for bootstrapping).  
- **Language:** TypeScript for predictable code and easier collaboration.  
- **Build Tools:** Webpack (built into CRA) or Vite for fast builds and live reload.  

### 1.2 How It’s Organized
- **Single-Page Application (SPA):** All pages load in one HTML shell; routing handles view changes.  
- **Folder Structure:**  
  • `src/components/` – reusable UI pieces (buttons, cards)  
  • `src/containers/` – connect components to app logic  
  • `src/pages/` – top-level views (CompanyProfile, WarrantyDashboard, Login)  
  • `src/services/` – API calls and business logic helpers  
  • `src/state/` – global state (Redux slices or Context providers)  
  • `src/styles/` – global style files, theme definitions  
  • `src/App.tsx` – root component with router and providers  

### 1.3 Why This Works
- **Scalability:** Clear separation of components, pages, and logic lets you add features without creating “spaghetti code.”  
- **Maintainability:** Consistent folder layout and TypeScript types make it easy for new team members to jump in.  
- **Performance:** Code splitting (lazy loading), tree-shaking, and optimized assets keep the app snappy even as it grows.

## 2. Design Principles

1. **Usability:** Interfaces are intuitive—forms guide users, buttons clearly indicate actions, and feedback appears on every interaction.  
2. **Accessibility:** We follow WCAG 2.1 AA guidelines: semantic HTML, ARIA labels where needed, and high-contrast color combinations.  
3. **Responsiveness:** Layouts adapt to desktop, tablet, and mobile via CSS Grid/Flexbox and media queries.  
4. **Consistency:** Reuse the same components, spacing, and typography across the app so users feel at home on every page.

### How We Apply Them
- **Form Validation:** Instant feedback on required fields and error messages announced for screen-readers.  
- **Keyboard Navigation:** All interactive controls are reachable by Tab and Enter keys.  
- **Mobile-First:** Build layouts from the smallest screen up, then enhance for larger viewports.

## 3. Styling and Theming

### 3.1 Styling Approach
- **Tailwind CSS:** Utility-first classes speed up development and enforce consistency.  
- **Custom CSS Modules (when needed):** For component-specific styles and overrides.  
- **PostCSS:** Auto-prefixing and future CSS features.

### 3.2 Theme Handling
- **Single Source of Truth:** Theme tokens (colors, spacing, font sizes) live in `src/styles/theme.ts`.  
- **Dark/Light Mode Support:** Optional toggle that adjusts CSS variables at runtime.

### 3.3 Visual Style
- **Style:** Modern flat design with subtle glassmorphism accents (semi-transparent panels and soft shadows).  
- **Color Palette:**  
  • Primary Blue: #0052CC  
  • Secondary Teal: #00BFA5  
  • Success Green: #28A745  
  • Warning Yellow: #FFC107  
  • Error Red: #DC3545  
  • Neutral Light: #F5F5F5  
  • Neutral Dark: #333333  

- **Fonts:**  
  • Headings – ‘Inter’, sans-serif, bold  
  • Body – ‘Inter’, sans-serif, regular

## 4. Component Structure

- **Atomic Design:**  
  • Atoms (buttons, inputs, labels)  
  • Molecules (form groups, card headers)  
  • Organisms (company profile module, warranty registration form)  
  • Templates/Pages (full screens with layout and multiple organisms)

- **Reuse & Composition:** Components receive data via props and emit events via callbacks, so they don’t know where data comes from. This isolation makes them easy to test and combine in new ways.

## 5. State Management

- **Redux Toolkit:** Central store with slices for user, warranty, and company profile data.  
- **RTK Query:** Simplifies API calls, caching, and loading status.  
- **Local State:** `useState` or `useReducer` inside components for form-specific or ephemeral data.

### Sharing Data
- Components dispatch actions to update global state.  
- Selectors read state and feed it as props.  
- RTK Query auto-invalidates and refetches when mutations occur (e.g., registering a product).

## 6. Routing and Navigation

- **React Router v6:**  
  • `/` – Redirects to `/profile` or `/dashboard` based on login.  
  • `/profile` – Company Profile page.  
  • `/warranty/register` – Product Registration form.  
  • `/warranty/dashboard` – Warranty status overview.  
  • `/claims` – Claim submission and history.  
  • `/reports` – Admin reports and analytics.  
  • `/login`, `/logout`, `/404` – Auth and fallback.

- **Protected Routes:** Higher-order component checks user roles and redirects to login if needed.

## 7. Performance Optimization

1. **Code Splitting:** Lazy-load pages with `React.lazy` and `<Suspense>`.  
2. **Tree-Shaking:** Only bundle used parts of libraries.  
3. **Image Optimization:** Serve WebP or compressed JPEG/PNG; use `loading="lazy"` for off-screen images.  
4. **Minification & Compression:** Built-in in production builds via Terser and gzip/Brotli serving.  
5. **Asset Caching:** Proper HTTP cache headers for static files.

These steps reduce initial load time and keep interactions fast.

## 8. Testing and Quality Assurance

- **Unit Tests:** Jest + React Testing Library for components and hooks.  
- **Integration Tests:** Verify interactions between components and slices.  
- **End-to-End (E2E):** Cypress covers critical user flows (login, register product, submit claim).  
- **Linting & Formatting:** ESLint (with Airbnb or custom rules) and Prettier enforce consistent style.  
- **CI Pipeline:** Runs tests, linting, and builds on each pull request.

## 9. Conclusion and Overall Frontend Summary

Our frontend setup for *sistem-vinnci* uses React, TypeScript, Tailwind CSS, and Redux Toolkit to build a scalable, maintainable, and performant SPA. We follow strong design principles—usability, accessibility, and responsiveness—while maintaining a modern flat look with glassmorphism touches. The atomic component structure, centralized state management, and modular styling ensure that the app can grow without accruing technical debt. Testing at every layer and performance optimizations guarantee a smooth user experience. Together, these guidelines ensure that anyone joining the project can quickly understand, maintain, and extend the frontend to meet future needs.