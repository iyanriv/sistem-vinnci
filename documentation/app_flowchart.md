flowchart TD
    Start[Start]
    Start --> Auth[User Authentication]
    Auth --> MainMenu[Main Menu]
    MainMenu --> Profile[View Company Profile]
    MainMenu --> RegWarranty[Register Product Warranty]
    RegWarranty --> WarrantyDashboard[Warranty Dashboard]
    WarrantyDashboard --> ClaimSub[Submit Warranty Claim]
    ClaimSub --> Confirm[Confirmation]
    MainMenu --> Reports[Reporting and Analytics]
    MainMenu --> Logout[Logout]
    subgraph AdminFlow
        AdminAuth[Admin Login]
        AdminAuth --> AdminDashboard[Admin Dashboard]
        AdminDashboard --> ReviewClaims[Review Warranty Claims]
        ReviewClaims --> ClaimDecision{Approve or Reject}
        ClaimDecision --> NotifyUser[Notify Customer]
    end