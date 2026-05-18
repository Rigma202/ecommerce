# Blade Templates — Mini Order Management System

## File Map

```
resources/views/
├── layouts/
│   └── app.blade.php              # Main layout (sidebar + topbar + flash)
├── components/
│   ├── nav-link.blade.php         # Active-aware sidebar link
│   └── status-badge.blade.php    # Pending / Completed badge
├── dashboard.blade.php            # Dashboard with stat cards + recent orders
├── customers/
│   ├── index.blade.php            # List with search + pagination
│   └── form.blade.php             # Shared create/edit form
├── products/
│   ├── index.blade.php            # List with search + stock badges + pagination
│   └── form.blade.php             # Shared create/edit form
├── orders/
│   ├── index.blade.php            # List with search/status filter + pagination
│   ├── create.blade.php           # Dynamic multi-product order form (JS-driven)
│   └── show.blade.php             # Order detail with customer sidebar
└── vendor/pagination/
    └── tailwind.blade.php         # Custom pagination (register in AppServiceProvider)
```

## Controller Hints

### Customers / Products
- `index`  — pass `$customers` / `$products` 
- `create` — return `view('customers.form')` / `view('products.form')`
- `edit`   — return `view('customers.form', compact('customer'))` etc.

### Dashboard
```php
return view('dashboard', [
    'totalCustomers' => Customer::count(),
    'totalProducts'  => Product::count(),
    'totalOrders'    => Order::count(),
    'pendingOrders'  => Order::where('status', 'Pending')->count(),
    'recentOrders'   => Order::with('customer')->latest()->take(8)->get(),
]);
```

### Orders — Create
```php
return view('orders.create', [
    'customers' => Customer::orderBy('name')->get(),
    'products'  => Product::where('stock_quantity', '>', 0)->orderBy('name')->get(),
]);
```

### Orders — Show
```php
return view('orders.show', [
    'order' => $order->load('customer', 'orderItems.product'),
]);
```

## Registering Pagination
In `AppServiceProvider::boot()`:
```php
Paginator::defaultView('vendor.pagination.tailwind');
```

## Tailwind Config
The templates use standard Tailwind utility classes. No custom config needed.
Add to `tailwind.config.js` content:
```js
'./resources/views/**/*.blade.php'
```

## Notes
- `form.blade.php` is reused for both create and edit — detect via `isset($customer)` / `isset($product)`.
- The order create page JS calculates a live running total client-side.
- Status badge component is reused across dashboard, index, and show views.
- Add `withCount('orders')` in the customers query for the orders count column.
- The `orders.complete` route should be a `PATCH` route pointing to a controller method that sets `status = 'Completed'`.
