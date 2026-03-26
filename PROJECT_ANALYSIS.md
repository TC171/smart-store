# PROJECT_ANALYSIS.md

## Cấu Trúc Thư Mục Dự Án Smart-Store

Dự án là một **Laravel E-commerce Admin Panel** (Laravel 12.x) với focus vào quản lý backend. Cấu trúc chuẩn Laravel:

```
c:/laragon/www/smart-store/
├── app/
│   ├── Console/Commands/TestVariantCreation.php
│   ├── Http/Controllers/Admin/          # Các controller admin đầy đủ
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── ProductController.php       # CRUD + import Excel, toggle status
│   │   ├── CategoryController.php
│   │   ├── BrandController.php
│   │   ├── BannerController.php
│   │   ├── CouponController.php
│   │   ├── ProductVariantController.php
│   │   ├── ProductAttributeController.php
│   │   ├── OrderController.php         # Update status/payment
│   │   ├── ReviewController.php        # Approve/reject
│   │   ├── UserController.php
│   │   ├── AdminController.php
│   │   ├── CustomerController.php
│   │   └── InventoryHistoryController.php
│   ├── Http/Middleware/AdminMiddleware.php
│   ├── Imports/ProductsImport.php      # Import sản phẩm từ Excel
│   ├── Models/                         # Eloquent models với relationships
│   │   ├── User.php (roles: admin/staff/customer)
│   │   ├── Product.php (category, brand, variants, images)
│   │   ├── ProductVariant.php
│   │   ├── ProductAttribute.php (color/storage/ram)
│   │   ├── Category.php (hierarchical)
│   │   ├── Brand.php
│   │   ├── Banner.php
│   │   ├── Coupon.php
│   │   ├── Order.php / OrderItem.php
│   │   ├── Review.php
│   │   └── InventoryHistory.php
│   └── Providers/AppServiceProvider.php
├── config/                             # Config files chuẩn
├── database/
│   ├── migrations/                     # Nhiều migrations (có duplicates)
│   │   ├── products, categories, brands, variants, orders, carts, wishlists, etc.
│   ├── factories/UserFactory.php
│   └── seeders/ (ProductSeeder, OrderSeeder, etc.)
├── public/ (index.php, favicon.ico)
├── resources/
│   ├── css/app.css, js/app.js
│   └── views/
│       ├── welcome.blade.php           # Laravel default welcome
│       └── admin/                      # Admin views đầy đủ (dashboard, products, orders...)
│           ├── layouts/
│           ├── partials/
│           └── [products/, orders/, etc.]
│       └── customer/ (dashboard, profile, orders) # Closures trong routes
├── routes/web.php                      # Admin + basic customer routes
├── composer.json (maatwebsite/excel, sanctum)
├── package.json, vite.config.js
├── smart-store (3).sql                 # Database dump
└── docs/TODO.md
```

**Dependencies chính**: Laravel 12, Excel import, Sanctum (API?).

**Database**: MySQL (Laragon), tables cho products/variants (SKU unique), orders, carts, wishlists, inventory tracking.

## Chức Năng Đã Có (Từ Routes/Controllers/Models/Views)

### 1. **Admin Panel (prefix: /admin)**
   - **Auth**: Login/logout (AdminMiddleware: role=admin/staff)
   - **Dashboard**: Stats cơ bản
   - **CRUD đầy đủ**:
     | Module | Features |
     |--------|----------|
     | Products | List/filter/sort(AJAX), create/edit/delete, import Excel, toggle status, show variants/pricing/stock |
     | Categories | CRUD (hierarchical: parent_id) |
     | Brands | CRUD |
     | Banners | CRUD + delete image |
     | Coupons | CRUD |
     | Product Attributes | CRUD (color/storage/ram/sort_order) |
     | Variants | CRUD (auto-generate SKU from product+attrs) |
     | Orders | List/show, update status/payment |
     | Reviews | List, approve/reject, delete |
     | Users/Customers/Admins | CRUD riêng (role-based) |
     | Inventory History | CRUD |
   - **Advanced**: Product variants combo (color+storage+ram), unique SKU, stock tracking.

### 2. **Customer Area (prefix: /customer, auth required)**
   - Dashboard/profile (views only, closures)
   - Orders list/detail (user-owned only)

### 3. **Frontend**: Chỉ welcome.blade.php (Laravel default). **KHÔNG CÓ SHOP FRONTEND**.

### 4. **Other**:
   - Excel import products
   - SoftDeletes trên models
   - Relationships đầy đủ (Product->variants->inventory)
   - Pagination/AJAX filters
   - Vietnamese locale/timezone

**Trạng thái**: Admin panel **~80% hoàn thiện** (CRUD đầy đủ, logic tốt). Frontend customer cơ bản. **Public shop thiếu hoàn toàn**.
