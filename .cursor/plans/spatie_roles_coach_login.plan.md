---
name: ""
overview: ""
todos: []
isProject: false
---

# Spatie Roles (admin, coach, frontdesk) + Coach Login

## Overview

- **Roles**: `admin`, `coach`, `frontdesk` only.
- **Coach login**: Link Coach to User so a coach record can have a login. Add `user_id` to `coaches`; when a coach is given a login, create a User (or link existing), assign role `coach`, and set `coaches.user_id`.
- **Permissions**: Define permissions and assign by role; protect routes and sidebar with `@can` / middleware.

---

## 1. Install and configure Spatie

- `composer require spatie/laravel-permission`
- Publish config and migration: `php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"`
- Run Spatie migration (creates `permissions`, `roles`, `model_has_roles`, `model_has_permissions`, `role_has_permissions`).
- Register middleware aliases in `bootstrap/app.php`: `role` and `permission` (see Spatie docs).

---

## 2. Schema: names on User, Coach linked by user_id

**2a. Users table and model**

- **Migration**: Add `first_name` and `last_name` to `users` (e.g. nullable string each). Optionally keep `name` for backward compatibility (e.g. nullable, or backfill from `first_name + last_name` and use a mutator/accessor for `name` from now on).
- **User model** [app/Models/User.php](app/Models/User.php):
    - Add `first_name`, `last_name` to `$fillable` (and keep `name` if you retain it).
    - Add `getFullNameAttribute()` returning `trim("{$this->first_name} {$this->last_name}")` or fallback to `$this->name` when first/last are empty.
    - Add Spatie `HasRoles` trait and `hasOne(Coach::class)`.

**2b. Coaches table and model**

- **Migration**: Add `user_id` (nullable, foreignId to `users.id`, nullOnDelete) to `coaches`. Remove columns `first_name`, `last_name`, and `email` from `coaches`.
- **Coach model** [app/Models/Coach.php](app/Models/Coach.php):
    - Remove `first_name`, `last_name`, `email` from `$fillable`; add `user_id`.
    - Add `belongsTo(User::class)`.
    - Keep `getFullNameAttribute()` but implement as: `return $this->user ? $this->user->full_name : 'N/A';` (or empty string). So all existing uses of `$coach->full_name` in views and [app/Services/DashboardService.php](app/Services/DashboardService.php) keep working.

**2c. Existing data (test only – no migration needed)**

- Current data are test data and can be deleted. No need to migrate existing coaches to Users. Options: run a fresh migration (e.g. `migrate:fresh`) or in a migration that drops `first_name`/`last_name` from coaches, first truncate or delete coach rows if needed. New coaches will be created via the create flow (User first, then Coach with user_id).

**2d. Coach create/edit flow**

- **Create coach**: Collect first_name, last_name, email (and optionally password for “login now”). Create `User` (first_name, last_name, email, password); optionally assign role `coach`; create `Coach` with `user_id`, and other coach-only fields (phone, specialty, address, status, etc.). Coach has no email column; use `$coach->user->email` everywhere.
- **Edit coach**: When editing coach, update the linked `User`’s `first_name`, `last_name` and `email`. Update coach-only fields on `Coach`.
- **Ordering**: Replace `Coach::orderBy('first_name')` with ordering by user name, e.g. `Coach::with('user')->get()->sortBy('user.full_name')` or a join: `Coach::join('users', 'coaches.user_id', '=', 'users.id')->orderBy('users.first_name')` (ensure user_id is not null for all coaches if you require it).

**2e. Nullable user_id**

- If `user_id` is nullable: coaches without a user have no name unless you add a fallback (e.g. display “No user linked”). If you require every coach to have a user, make `user_id` non-nullable after backfill and create User for every new coach.

---

## 3. Roles and permissions

**Roles (exactly three)**

- `admin` – full access.
- `coach` – dashboard, members (view/assign PT, log PT), PT packages/sessions (own or assigned), access logs (view).
- `frontdesk` – dashboard, members, subscriptions, RFID/keyfobs, access logs (view); no settings, no WiFi config, no coach management (or limited view).

**Suggested permissions**

- `view_dashboard`
- `manage_members`
- `manage_subscriptions`
- `manage_coaches`
- `manage_pt_packages`
- `manage_pt_session_plans`
- `manage_rfid_cards`
- `view_access_logs`
- `manage_settings`
- `manage_wifi_configurations`

**Assignment**

- **admin**: all of the above.
- **coach**: `view_dashboard`, `manage_members` (so they can log PT, assign PT), `manage_pt_packages`, `manage_pt_session_plans`, `view_access_logs`. Optionally restrict in app logic so coaches only see their own PT data (e.g. filter by `coach_id` from `auth()->user()->coach->id`).
- **frontdesk**: `view_dashboard`, `manage_members`, `manage_subscriptions`, `manage_rfid_cards`, `view_access_logs`. No `manage_coaches`, `manage_settings`, `manage_wifi_configurations`.

Seeder: create permissions, create roles `admin`, `coach`, `frontdesk`, assign permissions as above. Create one default admin user and assign role `admin`.

---

## 4. Giving a coach a login (“add login” flow)

- **Option A – From Coach list**: On coach index or show, add action “Give login” / “Add login”. That action:
    - Creates a `User` (name and email from coach create flow; here set password and assign role) or, if coach already has a user, just set password and assign role.
    - Assigns role `coach` to that user.
    - Sets `coach.user_id = user.id` and saves.
- **Option B – From User management (future)**: If you add a “Users” CRUD later, you could create a User, assign role `coach`, and link to an existing Coach.

Recommended: Option A. Add route and controller method (e.g. `CoachController@giveLogin` and `storeLogin`) or a dedicated `CoachLoginController`; form with password (and optionally “send credentials by email”). After creation, redirect back with success.

---

## 5. Route protection

- Keep all current admin routes behind `auth`.
- Add permission middleware per section (e.g. `middleware(['auth', 'permission:manage_members'])` for member routes, etc.) so that:
    - **admin** can access everything.
    - **coach** can access dashboard, members, PT packages/sessions, access logs (view).
    - **frontdesk** can access dashboard, members, subscriptions, RFID cards, access logs (view).

Use the same permission names in `@can` in the sidebar so nav items match what the user can access.

---

## 6. UI

- **Header**: Show `auth()->user()->full_name` (and optionally primary role: admin / coach / frontdesk).
- **Sidebar**: Wrap each section in `@can('permission_name')` so only allowed roles see the links.
- **Coach list/show**: Add “Give login” / “Add login” button when `coach.user_id` is null and current user has permission to manage coaches (or admin). Hide or show “Has login” when `coach.user_id` is set.

---

## 7. Optional: coach-scoped data

- In controllers for PT sessions, member PT packages, or coach dashboard, when the logged-in user has role `coach`, filter by `auth()->user()->coach->id` (e.g. only show their clients or their PT sessions). Admin and frontdesk see all. This keeps coaches from seeing other coaches’ data.

---

## Files to add or change

| Action        | File                                                                                                                                                                            |
| ------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Install       | `composer require spatie/laravel-permission`                                                                                                                                    |
| Publish       | Spatie config + migration                                                                                                                                                       |
| Add migration | Add `first_name`, `last_name` to `users`                                                                                                                                        |
| Add migration | Add `user_id` to `coaches`; remove `first_name`, `last_name`, `email` from `coaches`                                                                                            |
| Modify        | [app/Models/User.php](app/Models/User.php) – first_name, last_name, getFullNameAttribute(), HasRoles, hasOne(Coach::class)                                                      |
| Modify        | [app/Models/Coach.php](app/Models/Coach.php) – remove first_name/last_name/email, add user_id, belongsTo(User), getFullNameAttribute from user; use user->email for coach email |
| Modify        | [app/Http/Controllers/CoachController.php](app/Http/Controllers/CoachController.php) – create/update User when creating/editing coach; order by user name                       |
| Modify        | Coach create/edit views – first_name, last_name (stored on User)                                                                                                                |
| Add           | Seeder: permissions, roles (admin, coach, frontdesk), assign permissions, create 1 admin user                                                                                   |
| Modify        | [bootstrap/app.php](bootstrap/app.php) – register role/permission middleware                                                                                                    |
| Modify        | [routes/web.php](routes/web.php) – add permission middleware to route groups                                                                                                    |
| Add / modify  | Coach “give login” flow: route, controller method(s), form (e.g. in coach show or index)                                                                                        |
| Modify        | [resources/views/layout/header.blade.php](resources/views/layout/header.blade.php) – real user name and role                                                                    |
| Modify        | [resources/views/layout/sidebar.blade.php](resources/views/layout/sidebar.blade.php) – @can() around nav items                                                                  |
| Modify        | Coach index/show view – “Add login” when coach has no user_id                                                                                                                   |

---

## Summary

- **Names and email**: `first_name`, `last_name`, and `email` on **User** only; removed from **Coach**. Coach name = `$coach->user->full_name`; coach email = `$coach->user->email`.
- **Coach–User**: `coaches.user_id` → `users`. Coach create = create User then Coach; edit coach updates User name.
- **Roles**: admin, coach, frontdesk.
- **Coach login**: give login = create User (or link), assign role `coach`, set `coach.user_id`.
- **Permissions**: Defined and assigned per role; routes and sidebar gated by permission.
- **UI**: Header shows user full name and role; sidebar @can(); coach list/show “Add login” when coach has no user_id.
