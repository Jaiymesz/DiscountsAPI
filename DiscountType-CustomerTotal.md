# Discount Type - Customer Total

This discount type allows a discount on the whole cart to be applied if the customers historic revenue spend meets a set threshold.

Here is an example of a Customer Total discount:

> A customer who has already bought for over € 1000, gets a discount of 10% on the whole order.

## Basic Parameters

| Key | Description |
| ----------- | ----------- |
| **name** | Name of the discount |
| **description** | Description of the discount |
| **expiration** | Expiration date of the discount - once in the pass, it won't be applied (YYYY-MM-DD) |
| **type** | *CustomerTotal* |
| **total** | Total Revenue amount on customers account to meet threshold for discount |
| **discountType** | For a percentage discount, use the "*%*" symbol, for a flat rate discount, use the "*€*" symbol |
| **discountValue** | For a percentage type, enter the total percentage discount (EG: "*20*" for 20% off), for a flat rate discount, enter the whole decimal amount in EURO (EG: "*2.50*" for €2.50) |

## Example

```
[ 
    {
      "name": "10% Customer Loyalty Discount",
      "description": "A customer who has already bought for over € 1000, gets a discount of 10% on the whole order.",
      "expiration": "2021-12-01",
      "type": "CustomerTotal",
      "total": 1000,
      "discountType": "%",
      "discountValue": 10
    }
]
```