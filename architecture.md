# architecture.md

## Overview
A full‑stack Laravel e‑commerce site with:
- Public pages: **Home**, **Shop**, **Blog**, **Communities**, **Contact Us**.
- **Homepage banner** management (add/delete) via admin.
- Product catalog with categories, search & filter.
- **Cart**, **Checkout**, **Orders**, **Wishlist**.
- **Accounts** via email (registration, login, password reset, email verification).
- **Reviews** gated by account **and** prior purchase of the product.

Primary goals: clean architecture, security, test coverage, and production readiness.

---

## Tech Stack
- **Backend**: Laravel 11 (PHP ≥ 8.2)
- **Frontend**: Blade + Alpine.js + Tailwind CSS
- **Database**: MySQL 8 (or PostgreSQL 15)
- **Cache/Queue**: Redis
- **Auth**: Laravel Breeze (email/password) with Email Verification
- **Payments**: Stripe (abstracted via interfaces; easy to swap)
- **Media**: Spatie Laravel Media Library (for product images & banners)
- **Search**: Eloquent + LIKE for baseline; interface for future Scout/Meilisearch
- **Testing**: Pest (feature + unit + browser via Laravel Dusk optional)

---

## High‑Level Architecture
```
app/
  Domain/
    Catalog/ (Products, Categories)
    Orders/ (Cart, Checkout, Orders)
    Content/ (Blog, Banners)
    Community/ (Communities page CMS stubs)
    Users/ (Profile, Addresses, Wishlist)
  Http/
    Controllers/
    Middleware/
  Models/
  Policies/
  Services/
  Actions/
  ViewModels/
  View/Components/
bootstrap/
config/
database/
  factories/
  migrations/
  seeders/
public/
resources/
  views/
  css/ js/
routes/ (web.php, api.php)
```

### Layers & Conventions
- **Controllers** thin → call **Actions**/**Services** and return **ViewModels** to Blade.
- **Policies/Gates** for admin, order ownership, review creation.
- **Form Requests** for validation.
- **Events**: `OrderPlaced`, `UserRegistered` (send emails, clear carts).
- **Jobs/Listeners** queued via Redis.

---

## User Roles
- **Guest**: browse, add to cart (session), register.
- **Customer**: all guest + wishlist, checkout, orders, reviews.
- **Admin**: manage products, categories, banners, orders, blog posts, users.

Use `users.role` (enum: `customer`, `admin`) or spatie/laravel-permission if granular.

---

## Data Model

### Users & Profile
- **users**: id, name, email (unique), email_verified_at, password, role(enum), remember_token, timestamps
- **user_addresses**: id, user_id, type(enum: shipping,billing), name, phone, line1, line2, city, state, postcode, country_code, is_default(bool), timestamps
- **wishlists**: id, user_id, timestamps
- **wishlist_items**: id, wishlist_id, product_id, timestamps (unique: wishlist_id+product_id)

### Catalog
- **categories**: id, parent_id(nullable), name, slug(unique), description, is_active(bool), timestamps
- **products**: id, sku(unique), name, slug(unique), description, price(decimal 10,2), compare_at_price(decimal 10,2 nullable), inventory(int), is_active(bool), featured(bool), timestamps
- **category_product** (pivot): category_id, product_id (composite PK)
- **product_images** (via media library or table): id, product_id, disk, path, alt_text, sort_order

### Reviews
- **reviews**: id, user_id, product_id, order_id(nullable), rating(tinyint 1–5), title, body, is_approved(bool), timestamps
  - Constraint: allow create only if user purchased (`orders` contains product).

### Cart & Orders
- **carts**: id, user_id(nullable), session_id(nullable), currency, timestamps
- **cart_items**: id, cart_id, product_id, price, quantity, timestamps
- **orders**: id, user_id, number(unique), status(enum: pending, paid, failed, fulfilled, cancelled, refunded), subtotal, discount_total, tax_total, shipping_total, grand_total, currency, payment_method, payment_ref, placed_at, timestamps
- **order_items**: id, order_id, product_id, name_snapshot, price, quantity, total, timestamps
- **payments**: id, order_id, provider, amount, currency, status(enum: initiated, succeeded, failed, refunded), provider_charge_id, payload(json), timestamps
- **shipments**: id, order_id, carrier, tracking_no, status, shipped_at, delivered_at, timestamps

### Content
- **banners**: id, title, image (media), link_url, is_active(bool), sort_order, timestamps
- **posts**: id, title, slug, excerpt, body, published_at, user_id, timestamps

Indexes on all foreign keys + `products.slug`, `categories.slug`, `orders.number`.

---

## Key Features

### 1) Homepage with Image Banner Management
- Admin CRUD for **banners** with image upload (Spatie media).
- Public **Home** shows active banners slider.

### 2) Navigation
- Static Blade layout with menus: Home, Shop, Blog, Communities, Contact Us.

### 3) Shop
- Product listing with filters: keyword, category (with children), min/max price, sort (popular, newest, price asc/desc).
- Pagination (12/24 per page).
- Product detail page with gallery, price, stock, reviews, add‑to‑cart, add to wishlist.

### 4) Cart & Checkout
- Guests: cart bound to session; on login, merge carts.
- Checkout: shipping/billing address, delivery method (stub), payment (Stripe test keys by default).
- Place order → create `orders`, `order_items`, capture payment, decrement inventory, dispatch `OrderPlaced`.

### 5) Wishlist
- Auth‑only; toggle item; show list; move to cart.

### 6) Reviews
- Auth‑only **and** user must have completed order with product.
- Admin moderation (auto‑approve option).

### 7) Accounts
- Laravel Breeze: registration, login, forgot/reset password, **email verification required** for checkout & reviews.
- Profile management & addresses.

### 8) Blog & Communities
- Simple CMS for posts; Communities page as static CMS stub (editable sections in DB or Markdown files).

### 9) Contact Us
- Contact form (store + email to admin), spam protection via honeypot.

---

## Routing Overview

`routes/web.php`
- Public: `/` (Home), `/shop`, `/shop/{category?}`, `/product/{slug}`
- Cart: `/cart`, `/cart/items` (POST/PATCH/DELETE)
- Checkout: `/checkout` (GET/POST), `/orders/{number}` (GET)
- Wishlist (auth): `/wishlist`, `/wishlist/toggle/{product}`
- Reviews (auth): `/reviews` (POST), `/product/{slug}/reviews` (GET)
- Blog: `/blog`, `/blog/{slug}`
- Communities: `/communities`
- Contact: `/contact` (GET/POST)
- Dashboard (admin): `/admin` with resources for banners, products, categories, posts, orders

`routes/api.php` (optional): JSON endpoints for SPA bits.

---

## Controllers / Actions (examples)
- `HomeController@index` → banners, featured products
- `ShopController@index` → filter query → `Catalog\Queries\ProductSearchQuery`
- `ProductController@show`
- `CartController@index|store|update|destroy` (uses `CartService`)
- `CheckoutController@index|store` → `PlaceOrderAction`
- `WishlistController@index|toggle`
- `ReviewController@store` (policy: `canReview(user, product)`)
- `Admin\BannerController` (resource)
- `Admin\ProductController` (resource)
- `Admin\CategoryController` (resource)
- `Admin\PostController` (resource)
- `Admin\OrderController@index|show|update`

---

## Services / Actions (core)
- `CartService` — get/merge session/user cart; totals.
- `InventoryService` — reserve/decrement/restore.
- `PaymentGateway` (interface) → `StripeGateway` (implementation).
- `PlaceOrderAction` — validate, create order, charge, adjust inventory, events.
- `CreateReviewAction` — enforce purchase rule; create review.
- `UploadBannerAction` — image attach & ordering.

---

## Policies
- `OrderPolicy@view` → owner or admin.
- `ReviewPolicy@create` → user has paid order containing product.
- `AdminPolicy` middleware → role = admin.

---

## Validation & Security
- Form Requests for all mutations.
- CSRF + Honeypot on public forms.
- Rate limiting on auth & contact endpoints.
- Email verification required middleware on checkout and review routes.

---

## Database Migrations (outline)
- Create users; add `role` enum.
- Addresses; categories (nested set optional) + products + pivot.
- Product images via media library table or bespoke.
- Reviews with unique constraint `(user_id, product_id)` optional.
- Carts & cart_items; orders & order_items; payments; shipments.
- Banners; posts.

---

## Seeders
- Admin user (`admin@example.com` / random password), demo categories, demo products with images, demo banners, demo post.

---

## UI & Components (Blade)
- `layouts/app.blade.php` with nav.
- Components: `x-breadcrumbs`, `x-product-card`, `x-price`, `x-rating`, `x-banner-slider`.

---

## Search & Filtering
- Request params: `q`, `category`, `min_price`, `max_price`, `sort`, `page`.
- Query object encapsulates Eloquent filters.
- Use MySQL FULLTEXT (optional) or Meilisearch later — keep interface for swap.

---

## Payments
- ENV: `STRIPE_KEY`, `STRIPE_SECRET`.
- Use PaymentIntent; store payload JSON in `payments`.
- Cash on Delivery feature flag for quick demos.

---

## Emails & Notifications
- `OrderPlacedMail` to customer & admin.
- `NewReviewAwaitingApprovalMail` to admin (if moderation on).

---

## Logging & Observability
- Monolog channel `payments`.
- Model events log for orders.

---

## Performance
- Cache category tree; featured products; banner list.
- Use eager loading for product → images, categories, avg rating.

---

## Testing Strategy (Pest)
- **Unit**: Services (Cart, Inventory, PaymentGateway fake).
- **Feature**: add to cart, checkout happy‑path, wishlist toggle, review gating, admin CRUD banners.
- **HTTP**: filters on shop listing; email verification guard.

---

## Deployment
- Env: PHP‑FPM + Nginx; Redis; MySQL.
- `php artisan down --secret=...` for zero‑downtime; `php artisan migrate --force`.
- Run queues + schedule.
- Cloud storage (S3) for media in prod.

---

# BUILD PROMPT (copy/paste to scaffold project)

You are a senior Laravel engineer. Generate a **complete Laravel 11 project** matching the following spec. Create files with correct namespaces, classes, Blade views, tests, factories, seeders, and sample data. Where choices are needed, use reasonable defaults described below. Use PHP 8.2+ features.

## Project Setup
1. Initialize: `laravel new shop` (or `composer create-project laravel/laravel shop`)
2. Install packages:
   - `composer require laravel/breeze spatie/laravel-medialibrary stripe/stripe-php`
   - `php artisan breeze:install blade`
   - `npm install`
   - `php artisan migrate`
3. Configure `.env`: DB, Redis, mail, `STRIPE_KEY`, `STRIPE_SECRET`.

## Domain & Data
- Create migrations and models for: users (role enum), user_addresses, categories (parent_id), products, category_product, reviews, carts, cart_items, orders, order_items, payments, shipments, banners, posts.
- Add factories & seeders to generate 10 categories (nested), 40 products (with images), 3 banners, 3 posts, and an admin user.
- Use Spatie Media Library on `Product` and `Banner` for images.

## Routes
Define routes in `routes/web.php`:
- Home `/` → `HomeController@index`
- Shop `/shop` with filters; `/product/{slug}`
- Cart `/cart` (GET), `/cart/items` (POST), `/cart/items/{id}` (PATCH/DELETE)
- Checkout `/checkout` (GET/POST), `/orders/{number}`
- Wishlist `/wishlist` (GET), `/wishlist/toggle/{product}` (POST)
- Reviews `/reviews` (POST)
- Blog `/blog`, `/blog/{slug}`
- Communities `/communities`
- Contact `/contact` (GET/POST)
- Admin `/admin` with resource controllers for banners, products, categories, posts, orders; protect with `role:admin` middleware.

## Controllers & Actions
Implement:
- `HomeController@index` — banners + featured products
- `ShopController@index` — parse query params, return paginated list using `ProductSearchQuery`
- `ProductController@show`
- `CartController@index|store|update|destroy` using `CartService`
- `CheckoutController@index|store` using `PlaceOrderAction`
- `WishlistController@index|toggle`
- `ReviewController@store` using `CreateReviewAction`
- Admin resources: `Admin\{Banner,Product,Category,Post,Order}Controller`

## Services / Actions
Create classes:
- `Services\CartService` (session/user cart merge, totals)
- `Services\InventoryService`
- `Services\Payments\PaymentGateway` (interface), `Services\Payments\StripeGateway`
- `Actions\PlaceOrderAction`
- `Actions\CreateReviewAction`
- `Actions\UploadBannerAction`
- `Queries\ProductSearchQuery` (filters)

## Policies & Middleware
- Add `ReviewPolicy@create` (must have paid order with product).
- Add `OrderPolicy@view`.
- Guard checkout & reviews with `verified` middleware.
- `role:admin` middleware.

## Views (Blade + Tailwind + Alpine)
Create:
- layout with nav (Home, Shop, Blog, Communities, Contact Us), auth links, cart count, wishlist link.
- Home: banner slider component + featured grid.
- Shop: filters sidebar (q, category tree, price range) and product grid.
- Product: gallery, details, add‑to‑cart, wishlist button, reviews.
- Cart: list with quantity update, remove, totals.
- Checkout: address forms, order summary, Stripe card element, place order.
- Orders: thank‑you page with order number.
- Wishlist: grid.
- Blog index + post page.
- Communities static page.
- Contact form page.
- Admin: CRUD pages for banners (image upload & delete), products, categories, posts, orders.

## Models (key attributes & relations)
- `User` hasMany `Order`, `Review`, `Address`; hasOne `Wishlist`.
- `Product` belongsToMany `Category`; hasMany `Review`; hasMedia (images).
- `Category` self‑referencing parent/children; belongsToMany `Product`.
- `Order` hasMany `OrderItem`, `Payment`.
- `Cart` hasMany `CartItem`.
- `Wishlist` hasMany through items.
- `Banner` hasMedia (image).

## Business Rules
- Only **logged‑in + verified** users can checkout or review.
- Reviews allowed only if the user has a **PAID** order including the product.
- On successful order: decrement inventory; emit `OrderPlaced` event; clear cart.
- Merge guest cart with user cart on login.

## Testing (Pest)
Implement tests for:
- Cart add/update/remove; merge on login.
- Checkout: happy path (Stripe fake), inventory decrement.
- Wishlist: toggle and listing (auth only).
- Reviews: reject without purchase; accept with purchase; moderation.
- Admin: can manage banners and products; guests forbidden.
- Shop filters produce expected results.

## Commands & Seeders
- Artisan command `make:admin {email}` to promote a user.
- Seeders create admin + demo data + attach media placeholders.

## Emails & Notifications
- `OrderPlacedMail` to customer; notify admin channel (log or mail).

## Deployment Notes
- Env variables for Stripe, queue, cache, S3.
- Supervisor config for `queue:work`.

## Deliverables
- Compiles, migrates, seeds without error.
- Passing test suite for defined cases.
- Clean code with PHPStan level 6 and Pint formatting.

> Proceed to generate all necessary files, code, and tests according to this specification. Ensure controllers, routes, models, migrations, policies, views, and services compile. Provide any additional scaffolding needed for the banner slider and admin CRUD.

