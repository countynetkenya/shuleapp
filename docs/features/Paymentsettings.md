# Feature: Paymentsettings

## Overview
**Controller**: `mvc/controllers/Paymentsettings.php`  
**Primary Purpose**: Configure payment gateway settings (Stripe, PayPal, M-Pesa, etc.) for online fee payments  
**User Roles**: Admin (1), Systemadmin (5)  
**Status**: ✅ Active (Disabled in demo mode)

## Functionality
### Core Features
- Configure multiple payment gateways per school
- Store gateway-specific credentials (API keys, secrets, merchant IDs)
- Enable/disable gateways individually
- Dynamic validation rules per gateway using PaymentGateway library
- Batch update gateway options

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | paymentsettings | Configure gateway settings | paymentsettings |
| `rules()` | N/A | Internal: Get gateway-specific validation rules | N/A |
| `unique_field()` | N/A | Validation callback for required fields | N/A |

## Data Layer
### Models Used
- payment_gateway_m, payment_gateway_option_m

### Database Tables
- `payment_gateway` - Gateway definitions
  - payment_gateway_id (PK)
  - name (Stripe, PayPal, PayUmoney, Voguepay, M-Pesa)
  - slug, status (0/1 enabled)
- `payment_gateway_option` - Gateway configuration options
  - payment_gateway_option_id (PK)
  - payment_gateway_id (FK)
  - payment_option (e.g., 'stripe_secret_key')
- `payment_gateway_option_values` - School-specific option values
  - id (PK), payment_gateway_option_id (FK)
  - payment_value (encrypted credential)
  - schoolID

## Validation Rules
### Dynamic Per Gateway
- Rules loaded from `libraries/PaymentGateway/Service/{Gateway}.php`
- Language files: `language/{lang}/{gateway}_rules_lang.php`
- Example Stripe rules:
  - `stripe_status`: optional
  - `stripe_public_key`: required if status=1
  - `stripe_secret_key`: required if status=1
- Validation callback `unique_field()` checks required if gateway active

## Dependencies & Interconnections
### Depends On (Upstream)
- **PaymentGateway Library**: `libraries/PaymentGateway/PaymentGateway.php`

### Used By (Downstream)
- **Make_payment**: Loads gateway config for checkout
- **Invoice**: Online payment links
- **Payment**: Gateway callback processing

### Related Features
- Make_payment, Invoice, Payment, Mpesa, Safaricom, Stripe

## User Flows
### Primary Flow: Configure Gateway
1. Admin navigates to paymentsettings/index
2. View all available gateways with current settings
3. Select gateway (tabs or accordion)
4. Enter gateway-specific credentials:
   - Stripe: Public Key, Secret Key, Webhook Secret
   - PayPal: Client ID, Secret
   - M-Pesa: Consumer Key, Consumer Secret, Passkey, Shortcode
5. Enable/disable gateway with status checkbox
6. Submit performs batch update of gateway option values
7. Redirects with success message

## Edge Cases & Limitations
- **Demo Mode Block**: Controller constructor redirects if `config_item('demo') == true`
- **No Encryption**: Payment credentials stored in plaintext in database
- **Batch Update Only**: Updates all options at once, not incremental
- **Gateway Status Commented**: Code to update gateway status is commented out
- **No Test Mode Toggle**: No UI to switch between sandbox/production
- **Language Loading**: Loads all gateway language files even if not active

## Configuration
### Environment Variables (recommended but not implemented)
- Should use `getenv()` for sensitive gateway credentials
- Currently stores directly in database

### Gateway-Specific Settings
Each gateway has unique options defined in PaymentGateway library:
- **Stripe**: public_key, secret_key, webhook_secret
- **PayPal**: client_id, secret
- **PayUmoney**: merchant_key, merchant_salt
- **Voguepay**: merchant_id
- **M-Pesa**: consumer_key, consumer_secret, passkey, shortcode

## Notes for AI Agents
### PaymentGateway Library Pattern
```php
$this->payment_gateway = new PaymentGateway();
$rules = $this->payment_gateway->gateway($gateway_slug)->rules();
```
- Factory pattern: `gateway()` loads specific gateway class
- Each gateway class in `libraries/PaymentGateway/Service/`
- Returns validation rules array

### Batch Update Logic
```php
$array = [];
foreach($rules as $rule) {
    $key = $rule['field'];
    if($gateway_options[$key]) {
        $array[] = [
            'payment_gateway_option_id' => $gateway_options[$key]->id,
            'payment_value' => $this->input->post($key),
            'schoolID' => $schoolID
        ];
    }
}
$this->payment_gateway_option_m->update_batch_payment_gateway_option_values($array, 'payment_gateway_option_id');
```

### Security Concerns
- **Plaintext Storage**: Credentials stored unencrypted
- **No Access Logs**: No audit trail of who changed gateway settings
- **No IP Whitelist**: No restriction on callback IP addresses
- **Recommendation**: Implement encryption for payment_value column

### Demo Mode Check
```php
if(config_item('demo')) {
    $this->session->set_flashdata('error', 'In demo payment setting module is disable!');
    redirect(base_url('dashboard/index'));
}
```

### Language File Convention
- File: `language/{lang}/{gateway_slug}_rules_lang.php`
- Defines field labels for validation error messages
- Example: `$lang['stripe_public_key'] = "Stripe Public Key";`

### Gateway Status Update (Commented Out)
```php
// Line 80-82: Code to update gateway status is commented
// May indicate future feature or removed functionality
```

### Multi-Gateway Support
- School can enable multiple gateways simultaneously
- Student/parent chooses gateway at checkout
- Each gateway processes independently

