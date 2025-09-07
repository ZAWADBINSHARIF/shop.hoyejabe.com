# SMS Integration with SMSQ Global API

This application integrates with SMSQ Global SMS API for sending SMS notifications, OTPs, and order updates.

## Configuration

Add the following environment variables to your `.env` file:

```env
SMSQ_API_KEY=your_api_key_here
SMSQ_CLIENT_ID=your_client_id_here
SMSQ_SENDER_ID=your_sender_id_or_phone
```

## Usage Examples

### 1. Using the HasSmsNotifications Trait

Add the trait to your Customer model:

```php
use App\Traits\HasSmsNotifications;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasSmsNotifications, Notifiable;
    
    // ... rest of your model
}
```

### 2. Sending OTP

```php
// In your controller or service
$customer = Customer::find(1);

// Send OTP for verification
$otp = $customer->sendOtp('verification');

// Send OTP for password reset
$otp = $customer->sendOtp('password_reset');

// Verify OTP
if ($customer->verifyOtp($userInputOtp, 'verification')) {
    // OTP is valid
} else {
    // OTP is invalid or expired
}
```

### 3. Sending Custom SMS

```php
// Send a custom SMS to a customer
$customer = Customer::find(1);
$customer->sendSms('Your order has been shipped!');

// With custom sender ID
$customer->sendSms('Special offer just for you!', 'PROMO');
```

### 4. Using the SmsService

```php
use App\Services\SmsService;

$smsService = new SmsService();

// Send order confirmation
$order = Order::find(1);
$smsService->sendOrderConfirmation($order);

// Send order status update
$smsService->sendOrderStatusUpdate($order);

// Send bulk promotional SMS
$message = "Hi {first_name}, enjoy 20% off on your next purchase!";
$smsService->sendBulkPromotional($message, [1, 2, 3]); // Customer IDs

// Send direct SMS
$smsService->sendSms('01712345678', 'Your message here');
```

### 5. In Order Model

```php
// Automatically send SMS when order status changes
class Order extends Model
{
    protected static function booted()
    {
        static::updated(function (Order $order) {
            if ($order->isDirty('order_status')) {
                app(SmsService::class)->sendOrderStatusUpdate($order);
            }
        });
    }
}
```

### 6. Using Notifications Directly

```php
use App\Notifications\SendOtpNotification;
use App\Notifications\SendSmsNotification;

// Send OTP notification
$customer->notify(new SendOtpNotification('123456', 'login'));

// Send custom SMS notification
$customer->notify(new SendSmsNotification('Your custom message'));
```

## SMS Templates

The system supports different message templates for various purposes:

- **Verification**: OTP for account verification
- **Login**: OTP for secure login
- **Password Reset**: OTP for password recovery
- **Order Confirmation**: OTP for order verification

## Phone Number Format

The system automatically formats phone numbers for Bangladesh:
- Removes special characters and spaces
- Converts `01XXXXXXXXX` to `8801XXXXXXXXX`
- Ensures country code `880` is present

## Error Handling

All SMS operations are logged:
- Successful sends are logged as `info`
- Failed sends are logged as `error` with response details
- Exceptions are caught and logged

## Queue Support

SMS notifications implement `ShouldQueue` for better performance:
- SMS are sent asynchronously via queue workers
- Prevents blocking the main application flow
- Retry failed SMS automatically

Make sure queue workers are running:
```bash
php artisan queue:work
```

## Testing

To test SMS without sending actual messages, set mail driver to `log` in `.env`:
```env
MAIL_MAILER=log
```

SMS content will be logged to `storage/logs/laravel.log` for testing.