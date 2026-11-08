# Changelog

## 3.1.1

### Fixed

- The admin `order-comments` API collection is restricted to the comments of the orders placed on the sale units of an administrator restricted to some units (a comment on a national order is left out). An administrator attached to no unit and a superadministrator are unchanged.
- The `order-comments` admin resource now declares its permission (the order one) in `thelia.api.admin_resources`; without it the admin API refuses every non-super administrator.

### Changed

- Requires `thelia/core` 3.3.0 or later (sale unit scope).
