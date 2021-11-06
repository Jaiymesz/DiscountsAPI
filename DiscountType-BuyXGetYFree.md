# Discount Type - Buy X Get Y Free

This discount type allows a free item to be added when a certain number of items have been added.

Here is an example of a Buy X Get Y Free discount:

> For every product of category "Switches" (id 2), when you buy five, you get a sixth for free.

## Basic Parameters

| Key | Description |
| ----------- | ----------- |
| **name** | Name of the discount |
| **description** | Description of the discount |
| **expiration** | Expiration date of the discount - once in the pass, it won't be applied (YYYY-MM-DD) |
| **type** | *BuyXGetYFree* |
| **CategoryID** | Category ID of products eligible for this discount |
| **ProductID** | Product ID of specific product eligible for this discount - if any item in category, use -1 |
| **criteriaQty** | The Criteria Quantity of items to meet the discount threshold |
| **multiples** | Whether multiple free items can be given, for example if buy one get one free, can 3 be purchased to receive 3 free? *true* or *false*|
| **totalFree** | When criteriaQty is met - how many free items are given? For example Buy 2 get 2 free would require this being set to 2. |

## Example

```
[ 
    {
      "name": "Buy 5 Switches get 1 Free",
      "description": "For every product of category \"Switches\" (id 2), when you buy five, you get a sixth for free.",
      "expiration": "2021-12-01",
      "type": "BuyXGetYFree",
      "CategoryID": 2,
      "ProductID": -1,
      "criteriaQty": 5,
      "multiples": true,
      "totalFree": 1
    }
]
```