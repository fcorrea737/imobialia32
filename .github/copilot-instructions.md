# Copilot Instructions - Imobialia 32

## Architecture Overview
This is a **multi-tenant property management system** with a Laravel API backend and Nuxt.js admin frontend. The core architectural pattern is tenant isolation through database scoping.

### Key Components
- **Backend**: Laravel 12 API with Sanctum authentication 
- **Frontend**: Nuxt.js 3 + Vuetify admin panel (`frontend/admin-portal/`)
- **Database**: SQLite with multi-tenancy via `tenant_id` foreign keys
- **Build**: Vite for both Laravel assets and standalone frontend

## Multi-Tenancy Pattern
**Critical**: All data models use automatic tenant isolation via `BelongsToTenant` trait and `TenantScope`.

### Tenant Isolation Implementation
- **Global Scope**: `TenantScope` automatically filters all queries by `Auth::user()->tenant_id`
- **Auto-assignment**: `BelongsToTenant` trait sets `tenant_id` on model creation
- **Code Generation**: Models with `code` field get auto-generated tenant-prefixed codes (e.g., "ABC001")
- **Route Keys**: All models use UUIDs for public routing via `getRouteKeyName(): 'uuid'`

```php
// Example: Property model automatically filtered by tenant
$properties = Property::all(); // Only returns current user's tenant properties
```

## Domain Models & Relationships
```
Tenant (Imobiliária)
├── Users (proprietários, inquilinos, funcionários)
├── Properties (imóveis)
│   ├── Contracts (contratos de aluguel)
│   └── MaintenanceTickets (chamados de manutenção)
└── TicketUpdates (atualizações dos chamados)
```

### Key Model Patterns
- **UUIDs**: All models use `HasUuids` trait with `uuid` field for public APIs
- **Soft Deletes**: Properties use `SoftDeletes` 
- **Permissions**: Spatie Laravel Permission integrated
- **Policies**: Authorization via `PropertyPolicy`, `UserPolicy` (internal vs owner access)

## API Structure
- **Public API**: `/api/public/v1/tenants/{tenant_slug}/*` - Property listings without auth
- **Authenticated API**: `/api/v1/*` - CRUD operations with Sanctum auth
- **Middleware**: `ForceJsonResponse` ensures JSON responses for all API routes

### Authentication Roles
- `system_role = 'internal'`: Full access (imobiliária staff)
- `system_role = 'external'`: Limited access (property owners, tenants)

## Development Workflow

### Backend Commands
```bash
# Laravel development
php artisan serve
php artisan migrate --seed
php artisan pint  # Code formatting
php artisan ide-helper:generate  # IDE helpers

# Testing 
php artisan test
```

### Frontend Commands
```bash
cd frontend/admin-portal
pnpm dev          # Development server
pnpm build        # Production build
pnpm lint         # ESLint + fix
```

### Asset Building
```bash
# Laravel assets (Tailwind CSS)
npm run dev       # Watch mode
npm run build     # Production
```

## Code Conventions

### Model Conventions
- Always extend models with `BelongsToTenant` for multi-tenancy
- Use `$fillable` arrays for mass assignment protection
- Cast decimal fields: `'price' => 'decimal:2'`
- Use descriptive relationship method names: `tenantUser()` not `user()`

### API Conventions
- Version all APIs: `/api/v1/`
- Use resource controllers: `PropertyController::class`
- Group related routes with middleware
- Return consistent JSON responses

### Frontend Conventions
- Vuetify components for UI consistency
- Composables for reusable logic (`composables/`)
- TypeScript throughout
- i18n ready with `@nuxtjs/i18n`

## Database Migrations
- Tenant table created first (priority `0001_01_01_000001`)
- Foreign keys to `tenants.id` in all domain tables
- UUID columns for public identifiers
- Proper indexing on `tenant_id` + `uuid` combinations

## Key Files to Reference
- `app/Traits/BelongsToTenant.php` - Multi-tenancy implementation
- `app/Scopes/TenantScope.php` - Automatic query filtering  
- `app/Policies/PropertyPolicy.php` - Authorization patterns
- `routes/api.php` - API structure and middleware
- `frontend/admin-portal/nuxt.config.ts` - Frontend configuration

When working with this codebase, always consider tenant isolation and use the established UUID routing patterns.
