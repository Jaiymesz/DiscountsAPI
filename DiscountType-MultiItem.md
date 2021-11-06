# Discount Type - Multi Item

This discount type allows discounts when multiples of the same items are purchased.

Here is an example of a Multi-Item discount:

> If you buy two or more products of category "Tools" (id 1), you get a 20% discount on the cheapest product.

## Basic Parameters

| Key | Description |
| ----------- | ----------- |
| **name** | Name of the discount |
| **description** | Description of the discount |
| **expiration** | Expiration date of the discount - once in the pass, it won't be applied (YYYY-MM-DD) |
| **type** | *MultiItem* |
| **CategoryID** | Category ID of products eligible for this discount |
| **ProductID** | Product ID of specific product eligible for this discount - if any item in category, use -1 |
| **citeriaQty** | The Criteria Quantity of items to meet the discount threshold |
| **discountType** | For a percentage discount, use the "*%*" symbol, for a flat rate discount, use the "*€*" symbol |
| **discountValue** | For a percentage type, enter the total percentage discount (EG: "*20*" for 20% off), for a flat rate discount, enter the whole decimal amount in EURO (EG: "*2.50*" for €2.50) |
| **discountApply** | To apply to the single cheapest item in the cart of eligible items, use "*CheapestItem*". To apply the discount to ALL eligible items, use "*AllEligibleItems*"

## Example

```
[ 
    {
      "name": "Buy 2+ Tools for 20% Off",
      "description": "If you buy two or more products of category \"Tools\" (id 1), you get a 20% discount on the cheapest product.",
      "expiration": "2021-12-01",
      "type": "MultiItem",
      "CategoryID": 1,
      "ProductID": -1,
      "criteriaQty": 2,
      "discountType": "%",
      "discountValue": 20,
      "discountApply": "CheapestItem"
    }
]
```