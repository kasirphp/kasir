---
title: Upgrading from v1.x to v2.x
---

# Upgrading from v1.x to v2.x

[[toc]]

::: info
We attempt to document every possible breaking change. Since some of these breaking changes are in obscure parts of the
package only a portion of these changes may actually affect your application.
:::

## High Impact Changes

### Setting Gross Amount and Order ID

::: danger
Level of impact: **High**
:::

Kasir object no longer have a `transaction_details` property. Instead, it is now assigns `gross_amount` and `order_id`
individually.

If you are implementing `transactionDetails()` method to assign the `gross_amount` and `order_id` individually, you need
to update that to `grossAmount()` and `orderId()` method respectively.

Or, if you are implementing `getTransactionDetails()` method to get the `gross_amount` and `order_id` individually, you need to
update that to `getGrossAmount()` and `getOrderId()` method respectively.

```php
class YourController extends Controller
{
    public function transaction(Request $request) {    
        $kasir = Kasir::make() // [!code focus:7]
            ->transactionDetails([ // [!code --]
                'gross_amount' => $request->get('gross_amount'), // [!code --]
                'order_id' => 'order-'.Str::randomUuid(), // [!code --]
            ]); // [!code --]
            ->grossAmount($request->get('gross_amount')) // [!code ++]
            ->orderId('order-'.Str::randomUuid()); // [!code ++]
            
        // [!code focus:6]
        $gross_amount = $kasir->getTransactionDetails()['gross_amount']; // [!code --]
        $gross_amount = $kasir->getGrossAmount(); // [!code ++]

        $order_id = $kasir->getTransactionDetails()['order_id']; // [!code --]
        $order_id = $kasir->getOrderId(); // [!code ++]

        // ...
    }
}
```