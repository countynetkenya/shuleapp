# 0004 - Lazy Load SMS Libraries

## Context
The application was experiencing slow performance on every page load.
Investigation revealed that `mvc/libraries/Admin_Controller.php` (the base controller for most of the application) was unconditionally loading 6 different SMS gateway libraries (`bongasms`, `smsleopard`, `clickatell`, `twilio`, `bulk`, `msg91`) in its `__construct`.
Each of these libraries performs database queries (and potentially external network calls) in its own constructor to retrieve settings and initialize clients.
This meant every single page request (Dashboard, Student List, etc.) incurred a penalty of 5-10 extra database queries and potential network blocking provided by these unused libraries.

## Decision
We refactored `Admin_Controller` and its child controllers (`Mailandsms`, `Safaricom`, `api/v10/Sattendance`) to **lazy load** these libraries only when they are actually needed.
The `load->library()` calls were moved from `Admin_Controller::__construct` to the specific methods that send messages or verify gateways.

## Consequences
- **Positive**: Significant reduction in initialization time and database load for all pages.
- **Positive**: Reduced risk of page failures if an unused SMS provider's API is down or misconfigured.
- **Negative**: Developers must remember to load the library (or use the helper method) before sending an SMS in any new controller.

## Status
Accepted
