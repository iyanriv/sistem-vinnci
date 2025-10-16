# App Flow Document for sistem-vinnci

## Onboarding and Sign-In/Sign-Up

When a new visitor arrives at the sistem-vinnci web application, they first land on a welcoming page that highlights the company’s profile information and briefly explains the warranty services. At the top of this page, there are clear options to either sign in or sign up. For users who choose to create a new account, clicking the sign-up link opens a registration form where they can enter their full name, email address, and a secure password. After filling in the required fields and agreeing to the terms of service, the user submits the form and is prompted to verify their email. A verification link is sent to the address provided. Once the user clicks the link in that email, their account becomes active and they are redirected back to the application to complete their first login.

Users may also choose to sign up or sign in using social logins such as Google. When they select this method, the application redirects them to the Google authentication screen and requests permission to share basic profile information. After granting permission, users are returned to the sistem-vinnci dashboard without needing to fill out additional forms.

Returning users can sign in by entering their registered email and password or by using the same social login they originally selected. If a user forgets their password, clicking the "Forgot Password" link leads to a page where they enter their email. An email with a secure reset link is sent. The user follows that link, chooses a new password, and is then able to log in again. To sign out at any point, the user clicks their profile icon in the top navigation bar and selects the Sign Out option, which immediately ends the session and returns them to the landing page.

## Main Dashboard or Home Page

After signing in, the user arrives at the main dashboard, which serves as the central hub for all activities. A header bar displays the company logo on the left, a notifications bell in the center, and the user’s profile picture or initials on the right. Along the left side of the screen, a sidebar provides links for Dashboard, Register Product, Warranty Status, Submit Claim, Notifications, Reporting, and Account Settings.

The central area of the dashboard shows a summary panel with key figures such as the total number of registered products, warranties nearing expiration, and recent claim activity. Below the summary panel, there is a feed of system announcements and reminders about upcoming warranty expirations. Users can click on any summary metric or feed item to navigate directly to the relevant detailed view.

From this dashboard, users move seamlessly to any other section by selecting an item in the sidebar. For example, clicking "Register Product" takes the user to the product registration form, while clicking "Warranty Status" shows a list of all warranties and their current expiration dates.

## Detailed Feature Flows and Page Transitions

When a customer clicks on "Register Product," they see a form requesting the product’s serial number, purchase date, and an optional upload field for proof of purchase documents or images. After completing and submitting the form, the user is taken to a confirmation page that displays the newly registered product with its warranty expiration date. A button on this page allows the user to return to the dashboard or register another product.

From the "Warranty Status" page, a customer sees a table of all their registered products with columns for product name, serial number, purchase date, warranty end date, and a status badge indicating active or expired. Clicking on a specific product row opens a detailed view. This detailed view shows the full warranty history, an option to download a warranty certificate, and a link to submit a claim for that product.

When a user chooses to "Submit Claim," they are presented with a form asking for details about the issue, the date of the incident, and file upload fields for photos or supporting documents. Once the claim is submitted, the user is redirected to a claim summary page where they can see the claim number, current status, and any notes from the customer service team. A navigation link on that page allows them to return to the warranty details view or the main dashboard.

Administrators access an admin panel that mirrors the user interface but includes additional pages under the sidebar for "Manage Claims," "Edit Company Profile," and "Reporting and Analytics." In "Manage Claims," admins see a list of incoming claims with options to approve, reject, or request further information. Selecting a claim opens its detail page, where an admin can change the status, add comments, and send notifications to the customer. After making updates, the admin clicks Save and is returned to the claims list.

Under "Edit Company Profile," administrators can change text fields, upload new logo images, update contact information, and alter the displayed service list. Pressing Save on this page writes changes to the database and returns the admin to the public company profile view to verify updates.

The "Reporting and Analytics" page presents interactive charts and tables that show metrics such as total registered products, pending claims, average claim resolution time, and warranties expired in the past month. Date filters at the top allow admins to adjust the reporting period. Clicking an export button generates a downloadable CSV file of the currently displayed data. After exporting or reviewing the data, the admin clicks a link in the sidebar to return to the dashboard or another admin page.

## Settings and Account Management

The "Account Settings" page is available in the sidebar for both regular customers and administrators. On this page, users can update personal information such as name, email address, and phone number. There is also a section for changing the password, where the user enters their current password followed by a new password and confirmation. A separate area lets the user configure notification preferences, choosing whether to receive email alerts for upcoming warranty expirations, claim status updates, or system announcements.

All changes on the Account Settings page are applied when the user clicks the Save button, after which a brief confirmation message appears at the top of the screen. A link at the bottom of the page returns the user to the main dashboard, ensuring they never lose their place in the application.

## Error States and Alternate Paths

If a user submits any form with invalid or missing data, the page highlights the affected fields in red and displays a clear error message explaining what needs to be corrected. For example, an invalid serial number triggers a notice that the format is not recognized. If network connectivity drops during a request, a banner appears at the top of the screen stating "You are offline. Please check your connection and try again." Any in-progress form data remains on screen so the user does not lose their work when connectivity is restored.

When users attempt to access pages for which they lack permissions, such as a regular customer trying to reach the admin panel, they are redirected to an "Access Denied" page. This page briefly explains that they do not have the required privileges and includes a button to return to the dashboard.

If a user navigates to a non-existent route, a friendly 404 page appears with a message that the page could not be found and offers a link back to the dashboard. Throughout the application, all error messages are displayed in plain language and suggest the next steps to help users continue their journey without confusion.

## Conclusion and Overall App Journey

A typical new user begins by signing up, verifying their email, and exploring the company profile on the landing page. After completing registration, they log in and encounter the main dashboard summarizing their warranty information. From there, they register products, monitor warranty statuses, and submit claims when necessary. Administrators follow a parallel path enhanced with options to edit the company profile, process warranty claims, and review analytics. At every step, clear navigation in the sidebar and persistent header ensures users can move smoothly between pages. Should any errors or permission issues arise, informative messages guide the user back to a valid state. This cohesive application flow guarantees that users progress naturally from account creation through daily warranty management tasks and administrative duties without encountering dead ends or confusion.