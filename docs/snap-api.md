---
title: Snap

---

<!-- TOC -->
  * [Getting Started](#getting-started)
  * [Convert from Kasir Instance](#convert-from-kasir-instance)
  * [Snap Redirect](#snap-redirect)
  * [Snap Pop-up](#snap-pop-up)
  * [Configuring Enabled Payment Methods](#configuring-enabled-payment-methods)
  * [Configuring Redirect URL After Payment](#configuring-redirect-url-after-payment)
<!-- TOC -->

## Getting Started

Snap is a pre-built interface offered by Midtrans to start accepting payment easily. Snap can be displayed as a pop-up
within your app or as a web page redirect URL hosted by Midtrans.

<img width="210" align="center" alt="Midtrans Snap Pop-up" src="https://user-images.githubusercontent.com/36572342/212890194-36435c27-bd56-4f27-9dea-1e8812ff9ce7.png">

> Before you start using this API, you may also need to configure the [Transaction Details](transaction-details).

## Convert from Kasir Instance

If you already have a Kasir instance, you can convert it to Snap instance by calling `snap()` method.

```php
// use \Kasir\Kasir\Kasir;

$snap = Snap::make() // [!code focus]
    ->customerDetails($user) // [!code focus]
    ->billingAddress($billing_address) // [!code focus]
    ->shippingAddress($shipping_address) // [!code focus]
    ->itemDetails($items); // [!code focus]

// is equal to // [!code focus]

$kasir = Kasir::make()
    ->customerDetails($user)
    ->billingAddress($billing_address)
    ->shippingAddress($shipping_address)
    ->itemDetails($items);

$snap = $kasir->snap(); // [!code focus]
```

`$snap` is now a `Snap` instance. You can use it to generate Snap redirect URL or Snap pop-up.

## Snap Redirect

To use Snap Redirect, you can use `Snap` class that comes with Kasir. Create a `Snap` class with the transaction
details, and call the `redirect()` method.

```php
// use Kasir\Kasir\Snap;

$snap = Snap::make()
    ->grossAmount(20000)
    ->customerDetails($user)
    ->billingAddress($billing_address)
    ->shippingAddress($shipping_address)
    ->itemDetails($items)
    
return $snap->redirect(); // [!code focus]
```

Or from a Kasir instance.

```php
$kasir = Kasir::make()
    ->grossAmount(20000)
    ->customerDetails($user)
    ->billingAddress($billing_address)
    ->shippingAddress($shipping_address)
    ->itemDetails($items);
    
return $kasir->snap()->redirect(); // [!code focus]
```

This method will request Midtrans for a redirect URL and automatically redirect your page to Midtrans' hosted Snap page.
After the payment is successful, you can redirect the Snap page back to your application back.
Read [Configuring Redirect URL After Payment](#configuring-redirect-url-after-payment).

## Snap Pop-up

To use Snap Pop-up, create a `Snap` class with the transaction details.

```php
$snap = Snap::make()
    ->customerDetails($user)
    ->billingAddress($billing_address)
    ->shippingAddress($shipping_address)
    ->itemDetails($items);
```

Then, call the `getToken()` method to get the Snap token and pass it to your view.

```php
$token = $snap->getToken();

return view('checkout', compact('token'));
```

This method will request Midtrans for a Snap token.

Create a button in your view and add some ID to your button element. Then, include `<x-kasir::snap-script />` blade
component in your view, and pass the Snap token and button ID to the component.

```html
<!-- This is the Snap pop-up button. -->
<button id="pay-button">Pay!</button>

<!-- This is the Snap pop-up script. -->
<x-kasir::snap-script :id="pay-button" :token={{ $token }}/>
```

After the payment is successful, you can redirect the Snap page back to your application back. Read
[Configuring Redirect URL After Payment](#configuring-redirect-url-after-payment).

## Configuring Enabled Payment Methods

By default, Midtrans allows all payment methods to be used in payments. However, you can specify the accepted payment
methods using the `enablePayments()` method. This method accepts an array of strings defining what the accepted payment
methods are.

The following example will enable only 'shopeepay' and 'bca_va' as the API's accepted payment methods:

```php
Snap::make()
    ->itemDetails($items)
    ->customerDetails($user)
    ->enablePayments(['shopeepay', 'bca_va']);
```

You can also disable some payment methods using the disablePayment() method, which will prevent the API from making
payment requests using the defined payment methods.

The following example will disable only 'credit_card' from the APIs accepted payment methods.

```php
Snap::make()
    ->itemDetails($items)
    ->customerDetails($user)
    ->disablePayments(['credit_card']); // This will disable credit card as the payment method.
```

The accepted array values are:

- `credit_card`: Credit Card
- `cimb_clicks`: CIMB Clicks
- `bca_klikbca`: BCA KlikBCA
- `bca_klikpay`: BCA KlikPay
- `bri_epay`: BNI ePay
- `echannel`: Mandiri Virtual Account
- `permata_va`: Permata Virtual Account
- `bca_va`: BCA Virtual Account
- `bni_va`: BNI Virtual Account
- `bri_va`: BRI Virtual Account
- `danamon_online`: Danamon Online
- `uob_ezpay`: UOB EZ Pay
- `gopay`: GoPay
- `shopeepay`: ShopeePay
- `indomaret`: Indomaret
- `alfamart`: Alfamart
- `akulaku`: Akulaku
- `kredivo`: Kredivo

## Configuring Redirect URL After Payment

Please refer to this Midtrans
documentation: https://docs.midtrans.com/docs/snap-advanced-feature#configuring-redirect-url