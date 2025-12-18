# Implementation Plan

- [x] 1. Set up project structure and core infrastructure





  - [x] 1.1 Create directory structure for services, repositories, and dashboard views


    - Create `app/Services/Dashboard/` directory
    - Create `app/Repositories/Contracts/` and `app/Repositories/Eloquent/` directories
    - Create `resources/views/dashboard/` with subdirectories for layouts, components, and role-specific views
    - Create `resources/css/dashboard/` for design system CSS
    - _Requirements: 1.1, 14.1, 14.2_


  - [x] 1.2 Create design system CSS with tokens and base styles


    - Create `tokens.css` with color, typography, spacing, and shadow variables
    - Create `components.css` with base component styles
    - Create `utilities.css` with helper classes
    - _Requirements: 1.3, 1.4, 1.5_


  - [x] 1.3 Create shared dashboard layout component

    - Create `resources/views/dashboard/layouts/app.blade.php` with sidebar, topbar, and content area
    - Implement responsive behavior with mobile sidebar toggle
    - Add RTL support for Arabic interface
    - _Requirements: 1.1, 1.5_


  - [x] 1.4 Write property test for layout consistency

    - **Property 1: Role-Based Access Control Enforcement** (partial - layout rendering)
    - **Validates: Requirements 1.1**

- [x] 2. Implement RBAC middleware and policies






  - [x] 2.1 Create DashboardRoleMiddleware

    - Create `app/Http/Middleware/DashboardRoleMiddleware.php`
    - Implement role checking with support for multiple roles (OR logic)
    - Return 403 for unauthorized access
    - Support admin override for all routes
    - _Requirements: 2.1, 2.2, 2.5_


  - [x] 2.2 Register middleware in kernel

    - Add middleware alias in `app/Http/Kernel.php`
    - _Requirements: 2.1_


  - [x] 2.3 Write property test for RBAC enforcement

    - **Property 1: Role-Based Access Control Enforcement**
    - **Validates: Requirements 2.1, 2.2**

  - [x] 2.4 Write property test for multi-role access


    - **Property 2: Multi-Role Access Grant**
    - **Validates: Requirements 2.3**

  - [x] 2.5 Write property test for admin override


    - **Property 4: Admin Full Access Override**
    - **Validates: Requirements 2.5**

- [x] 3. Implement repository layer






  - [x] 3.1 Create repository interfaces

    - Create `OrderRepositoryInterface.php`
    - Create `UserRepositoryInterface.php`
    - Create `AuditLogRepositoryInterface.php`
    - Create `StoreRepositoryInterface.php`
    - Create `FinancialTransactionRepositoryInterface.php`
    - _Requirements: 14.2, 14.4_


  - [x] 3.2 Implement Eloquent repositories

    - Create `OrderRepository.php` with filtering, pagination, and aggregation methods
    - Create `UserRepository.php` with search and role filtering
    - Create `AuditLogRepository.php` with append-only constraint
    - Create `StoreRepository.php` with owner-scoped queries
    - Create `FinancialTransactionRepository.php`
    - _Requirements: 14.2, 14.4_


  - [x] 3.3 Register repositories in service provider

    - Bind interfaces to implementations in `AppServiceProvider`
    - _Requirements: 14.4_


- [x] 4. Implement AuditLog model and service

  - [x] 4.1 Create/update AuditLog model with immutability
    - Add boot method to prevent updates and deletes
    - Add JSON serialization/deserialization methods
    - _Requirements: 6.1, 6.2, 6.5, 6.6_

  - [x] 4.2 Create AuditService


    - Implement `log()` method for creating audit entries
    - Implement `getAuditLogs()` with filtering
    - Implement `serializeEntry()` and `deserializeEntry()` methods
    - _Requirements: 6.1, 6.3, 6.5, 6.6_


  - [x] 4.3 Write property test for audit log immutability

    - **Property 14: Audit Log Immutability**
    - **Validates: Requirements 6.2**


  - [x] 4.4 Write property test for audit log serialization round-trip

    - **Property 15: Audit Log Serialization Round-Trip**
    - **Validates: Requirements 6.5, 6.6**

- [x] 5. Checkpoint - Ensure all tests pass








  - Ensure all tests pass, ask the user if questions arise.


- [x] 6. Implement FinancialTransaction model enhancements





  - [x] 6.1 Update FinancialTransaction model with immutability after approval

    - Add `is_immutable` field handling
    - Add boot method to prevent updates on immutable records
    - Add `approve()` method
    - _Requirements: 6.4, 13.3_


  - [x] 6.2 Write property test for financial record immutability

    - **Property 16: Financial Record Immutability After Approval**
    - **Validates: Requirements 6.4**


- [x] 7. Implement MetricsService



  - [x] 7.1 Create MetricsService with calculation methods

    - Implement `calculateRevenue()` with date range and optional store filtering
    - Implement `calculateOrderCount()`
    - Implement `calculateGrowthPercentage()`
    - Implement `formatCurrency()` with locale support
    - Implement `formatPercentage()` returning value, color, and icon
    - _Requirements: 3.1, 3.4, 3.5_



  - [x] 7.2 Write property test for currency formatting
    - **Property 5: Currency Formatting Consistency**
    - **Validates: Requirements 3.4**


  - [x] 7.3 Write property test for percentage color coding


    - **Property 6: Percentage Change Color Coding**
    - **Validates: Requirements 3.5**

- [x] 8. Implement shared Blade components









  - [x] 8.1 Create stat-card component

    - Create `resources/views/dashboard/components/stat-card.blade.php`
    - Support title, value, change percentage, icon, and color props
    - _Requirements: 1.2, 3.4, 3.5_


  - [x] 8.2 Create data-table component

    - Create `resources/views/dashboard/components/data-table.blade.php`
    - Support columns, data, searchable, sortable, exportable props
    - Include loading skeleton and empty state
    - _Requirements: 1.2, 4.1, 4.2, 4.3, 4.5_


  - [x] 8.3 Create supporting components

    - Create `badge.blade.php` with type and size props
    - Create `button.blade.php` with variant, size, loading props
    - Create `modal.blade.php` with title and size props
    - Create `alert.blade.php` with type and dismissible props
    - Create `empty-state.blade.php`
    - Create `loading-skeleton.blade.php`
    - _Requirements: 1.2, 15.1, 15.3_

- [x] 9. Implement sidebar and topbar components






  - [x] 9.1 Create sidebar component with role-based menu


    - Create `resources/views/dashboard/components/sidebar.blade.php`
    - Show menu items based on user roles
    - Highlight active menu item
    - _Requirements: 1.1, 2.1_

  - [x] 9.2 Create topbar component


    - Create `resources/views/dashboard/components/topbar.blade.php`
    - Include search, notifications, and user menu
    - _Requirements: 1.1_

- [x] 10. Implement data filtering and pagination service





  - [x] 10.1 Create DataTableService for server-side operations

    - Implement pagination with configurable page sizes
    - Implement column sorting
    - Implement search filtering
    - Implement date range filtering
    - _Requirements: 4.1, 4.2, 4.3, 4.4_

  - [x] 10.2 Write property test for pagination bounds





    - **Property 7: Pagination Bounds**
    - **Validates: Requirements 4.1**

  - [x] 10.3 Write property test for sort order correctness





    - **Property 8: Sort Order Correctness**
    - **Validates: Requirements 4.2**


  - [x] 10.4 Write property test for search filter correctness

    - **Property 9: Search Filter Correctness**
    - **Validates: Requirements 4.3**


  - [x] 10.5 Write property test for date range filter correctness


    - **Property 10: Date Range Filter Correctness**
    - **Validates: Requirements 4.4**

- [x] 11. Checkpoint - Ensure all tests pass






  - Ensure all tests pass, ask the user if questions arise.

- [x] 12. Implement ExportService






  - [x] 12.1 Create ExportService for CSV and PDF exports

    - Implement `exportToCSV()` with streaming response
    - Implement `exportToPDF()` using DomPDF
    - Implement `queueLargeExport()` for datasets > 1000 rows
    - Log all exports to audit log
    - _Requirements: 5.1, 5.2, 5.3, 5.5_


  - [x] 12.2 Write property test for CSV export completeness

    - **Property 11: CSV Export Completeness**
    - **Validates: Requirements 5.1**


  - [x] 12.3 Write property test for export role filtering

    - **Property 12: Export Role Filtering Consistency**
    - **Validates: Requirements 5.4**


  - [x] 12.4 Write property test for audit log creation on export

    - **Property 13: Audit Log Creation on Sensitive Actions**
    - **Validates: Requirements 5.5, 6.1**

- [x] 13. Implement Admin Dashboard





  - [x] 13.1 Create AdminDashboardService


    - Implement methods for admin KPIs (users, orders, revenue, stores)
    - Implement user search functionality
    - Implement bulk action processing with transactions
    - _Requirements: 7.1, 7.3, 7.5_


  - [x] 13.2 Create AdminDashboardController

    - Create `app/Http/Controllers/Dashboard/AdminDashboardController.php`
    - Implement index, users, orders, stores, and settings actions
    - Apply admin role middleware
    - _Requirements: 7.1, 7.2, 7.3, 7.4_


  - [x] 13.3 Create admin dashboard views

    - Create `resources/views/dashboard/admin/index.blade.php`
    - Create user management views
    - Create order management views
    - Create system alerts view
    - _Requirements: 7.1, 7.2, 7.4_

  - [x] 13.4 Write property test for user search


    - **Property 17: User Search Correctness**
    - **Validates: Requirements 7.3**

  - [x] 13.5 Write property test for bulk action transactionality


    - **Property 18: Bulk Action Transactionality**
    - **Validates: Requirements 7.5**



- [x] 14. Implement Store Owner Dashboard





  - [x] 14.1 Create StoreOwnerDashboardService

    - Implement store-scoped data queries
    - Implement revenue and earnings calculations
    - Implement product analytics
    - _Requirements: 12.1, 12.2, 12.3, 12.5_


  - [x] 14.2 Create StoreOwnerDashboardController

    - Create `app/Http/Controllers/Dashboard/StoreOwnerDashboardController.php`
    - Implement index, products, orders, analytics actions
    - Apply store-owner role middleware
    - Ensure all queries are scoped to owner's store
    - _Requirements: 12.1, 12.2, 12.4_


  - [x] 14.3 Create store owner dashboard views

    - Create `resources/views/dashboard/store-owner/index.blade.php`
    - Create product management views
    - Create order views
    - Create analytics views
    - _Requirements: 12.1, 12.2, 12.5_


  - [x] 14.4 Write property test for store owner data isolation

    - **Property 3: Store Owner Data Isolation**
    - **Validates: Requirements 2.4, 12.1**

  - [x] 14.5 Write property test for store revenue calculation


    - **Property 21: Store Revenue Calculation**
    - **Validates: Requirements 12.3**

- [x] 15. Checkpoint - Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.

- [x] 16. Implement Finance Dashboard





  - [x] 16.1 Create FinanceDashboardService


    - Implement financial KPIs (revenue, expenses, payouts, margins)
    - Implement transaction listing with filters
    - Implement payout approval workflow
    - _Requirements: 13.1, 13.2, 13.3, 13.5_

  - [x] 16.2 Create FinanceDashboardController


    - Create `app/Http/Controllers/Dashboard/FinanceDashboardController.php`
    - Implement index, transactions, payouts, reports actions
    - Apply finance role middleware
    - _Requirements: 13.1, 13.2, 13.4_

  - [x] 16.3 Create finance dashboard views


    - Create `resources/views/dashboard/finance/index.blade.php`
    - Create transaction views
    - Create payout management views
    - Create financial reports views
    - _Requirements: 13.1, 13.4, 13.5_

- [x] 17. Implement IT Dashboard






  - [x] 17.1 Create ITDashboardService

    - Implement system metrics collection
    - Implement log viewing with filters
    - Implement cache management
    - _Requirements: 8.1, 8.2, 8.3_


  - [x] 17.2 Create ITDashboardController

    - Create `app/Http/Controllers/Dashboard/ITDashboardController.php`
    - Implement index, logs, security, performance actions
    - Apply IT role middleware
    - _Requirements: 8.1, 8.2, 8.4, 8.5_


  - [x] 17.3 Create IT dashboard views

    - Create `resources/views/dashboard/it/index.blade.php`
    - Create system logs view
    - Create security alerts view
    - Create performance metrics view
    - _Requirements: 8.1, 8.2, 8.4_

- [x] 18. Implement Customer Support Dashboard






  - [x] 18.1 Create CSDashboardService

    - Implement ticket KPIs
    - Implement ticket assignment
    - Implement satisfaction metrics
    - _Requirements: 9.1, 9.3, 9.4_


  - [x] 18.2 Create CSDashboardController

    - Create `app/Http/Controllers/Dashboard/CSDashboardController.php`
    - Implement index, tickets, feedback actions
    - Apply CS role middleware
    - _Requirements: 9.1, 9.2, 9.5_


  - [x] 18.3 Create CS dashboard views

    - Create `resources/views/dashboard/cs/index.blade.php`
    - Create ticket management views
    - Create feedback views
    - _Requirements: 9.1, 9.2, 9.5_

- [x] 19. Implement HR Dashboard




  - [x] 19.1 Create HRDashboardService

    - Implement employee KPIs
    - Implement attendance tracking
    - Implement leave management with balance adjustment
    - Implement payroll calculation
    - _Requirements: 10.1, 10.2, 10.3, 10.4_


  - [x] 19.2 Create HRDashboardController

    - Create `app/Http/Controllers/Dashboard/HRDashboardController.php`
    - Implement index, employees, attendance, leaves, payroll actions
    - Apply HR role middleware
    - _Requirements: 10.1, 10.2, 10.5_


  - [x] 19.3 Create HR dashboard views

    - Create `resources/views/dashboard/hr/index.blade.php`
    - Create employee management views
    - Create attendance views
    - Create leave management views
    - Create payroll views
    - _Requirements: 10.1, 10.2, 10.5_



  - [x] 19.4 Write property test for leave balance adjustment
    - **Property 19: Leave Balance Adjustment**
    - **Validates: Requirements 10.3**


  - [x] 19.5 Write property test for payroll calculation


    - **Property 20: Payroll Calculation Correctness**
    - **Validates: Requirements 10.4**


- [x] 20. Implement Delivery Supervisor Dashboard





  - [x] 20.1 Create DeliveryDashboardService

    - Implement driver location tracking
    - Implement delivery assignment
    - Implement performance metrics
    - _Requirements: 11.1, 11.3, 11.4_


  - [x] 20.2 Create DeliveryDashboardController

    - Create `app/Http/Controllers/Dashboard/DeliveryDashboardController.php`
    - Implement index, drivers, assignments, tracking actions
    - Apply delivery supervisor role middleware
    - _Requirements: 11.1, 11.2, 11.5_


  - [x] 20.3 Create delivery dashboard views

    - Create `resources/views/dashboard/delivery/index.blade.php`
    - Create driver management views
    - Create assignment views
    - Create map tracking view
    - _Requirements: 11.1, 11.2, 11.5_

- [x] 21. Checkpoint - Ensure all tests pass





  - Ensure all tests pass, ask the user if questions arise.

- [x] 22. Implement form validation and error handling






  - [x] 22.1 Create form request classes for dashboard forms

    - Create validation rules for each dashboard form
    - Implement custom error messages
    - _Requirements: 15.2_


  - [x] 22.2 Implement error display components

    - Create input-error component
    - Create form-level error display
    - Implement auto-dismiss for success messages
    - _Requirements: 15.1, 15.2, 15.3_


  - [x] 22.3 Write property test for form validation errors

    - **Property 22: Form Validation Error Display**
    - **Validates: Requirements 15.2**

- [x] 23. Set up dashboard routes





  - [x] 23.1 Create dashboard route file


    - Create `routes/dashboard.php` with all dashboard routes
    - Apply middleware groups for each role
    - Register route file in RouteServiceProvider
    - _Requirements: 2.1_


  - [x] 23.2 Create dashboard home/selector page

    - Create view showing available dashboards based on user roles
    - _Requirements: 2.3_

- [x] 24. Clean up old dashboard code






  - [x] 24.1 Archive old dashboard files

    - Move old controllers to `app/Http/Controllers/Legacy/`
    - Move old views to `resources/views/legacy/`
    - Update any remaining references
    - _Requirements: N/A - cleanup_


  - [x] 24.2 Remove unused routes

    - Comment out or remove old dashboard routes
    - Ensure no broken links
    - _Requirements: N/A - cleanup_

- [x] 25. Final Checkpoint - Ensure all tests pass





  - Ensure all tests pass, ask the user if questions arise.
