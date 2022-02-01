# DiscountsAPI

## Method 1 - GET :/order/{orderID}

This will pull the JSON Order live from the Data Store on GitHub by providing the relevant Order ID number. 

## Method 2 - POST :/order

You can POST raw JSON in the body of a request for it to process the order provided.

## Creating New / Adjusting Discounts

A file has been created in [/src/classes/discounts.json](/src/classes/discounts.json) where new discounts can be added at any time in the 3 types required for the excersise. 

1. **[MultiItem](/DiscountType-MultiItem.md)** = A discount (% or Flat) is applied when multiple items are purchased of any quantity.
2. **[BuyXGetYFree](/DiscountType-BuyXGetYFree.md)** = A free item is given when a certain number of products are purchased - optionally can be enabled/disabled in multiples.
3. **[CustomerTotal](/DiscountType-CustomerTotal.md)** = A discount (% or Flat) is applied if the customers total revenue meets a specified threshold. 

New Discount types can be added in [/src/classes/discountTypes/](/src/classes/discountTypes/) when required ensuring the name formation remains as "TYPE.class.php"

# Caveats

## Possible issues

1. With MultiItem discounts, it is not clear if all of the cheapest items should be discounted, or just a single item within the cheapest item.
2. With MultiItem discounts, it is not clear if when purchasing more than 2 items if multiple discounts should apply (EG. 6 "Tools" = 3 items at 20% off.)
3. It was not clear if multiple discounts could apply to the same order - if not, the priority of this (which discount should be applied over another).
4. For BuyXGetYFree, it is not clear if you can buy 3 of one item, and 2 of another item in a certain category to be eligible.
5. For BuyXGetYFree, it is not clear if the free item should be pre-added by the customer or not (IE: Buy 5 get 6th free will discount the 6th item added to nil, rather than adding another item automatically).

## Potential Improvements/Adjustments

1. Better error handling
2. Connect to a database instead of an API
3. Authentication / Security Tokens
4. Adding discounted line item price totals.

## Examples
### Order 1
#### Request 
```
{
  "id": "1",
  "customer-id": "1",
  "items": [
    {
      "product-id": "B102",
      "quantity": "10",
      "unit-price": "4.99",
      "total": "49.90"
    }
  ],
  "total": "49.90"
}
```
#### Response
```
{
    "id": "1",
    "customer-id": "1",
    "items": [
        {
            "product-id": "B102",
            "quantity": "10",
            "unit-price": "4.99",
            "total": "49.90"
        },
        {
            "product-id": "B102",
            "quantity": "2",
            "unit-price": "0.00",
            "total": "0.00"
        }
    ],
    "total": "49.90",
    "discount-amount": "0.00",
    "discounts-applied": [
        "Buy 5 Switches get 1 Free (Added 2 Free of Press button)"
    ]
}
```

### Order 2
#### Request
```
{
  "id": "2",
  "customer-id": "2",
  "items": [
    {
      "product-id": "B102",
      "quantity": "5",
      "unit-price": "4.99",
      "total": "24.95"
    }
  ],
  "total": "24.95"
}
```
#### Response
```
{
    "id": "2",
    "customer-id": "2",
    "items": [
        {
            "product-id": "B102",
            "quantity": "5",
            "unit-price": "4.99",
            "total": "24.95"
        },
        {
            "product-id": "B102",
            "quantity": "1",
            "unit-price": "0.00",
            "total": "0.00"
        }
    ],
    "total": "22.45",
    "discount-amount": "2.50",
    "discounts-applied": [
        "10% Customer Loyalty Discount",
        "Buy 5 Switches get 1 Free (Added 1 Free of Press button)"
    ]
}
```
### Order 3
#### Request
```
{
  "id": "3",
  "customer-id": "3",
  "items": [
    {
      "product-id": "A101",
      "quantity": "2",
      "unit-price": "9.75",
      "total": "19.50"
    },
    {
      "product-id": "A102",
      "quantity": "1",
      "unit-price": "49.50",
      "total": "49.50"
    }
  ],
  "total": "69.00"
}
```
#### Response
```
{
    "id": "3",
    "customer-id": "3",
    "items": [
        {
            "product-id": "A101",
            "quantity": "2",
            "unit-price": "9.75",
            "total": "19.50"
        },
        {
            "product-id": "A102",
            "quantity": "1",
            "unit-price": "49.50",
            "total": "49.50"
        }
    ],
    "total": "67.05",
    "discount-amount": "1.95",
    "discounts-applied": [
        "Buy 2+ Tools for 20% Off (Applied against A101 - Screwdriver)"
    ]
}
```
### Invalid Customer ID (Error Handling)
#### Request
```
{
    "id": "4",
    "customer-id": "6",
    "items": [
        {
            "product-id": "A101",
            "quantity": "2",
            "unit-price": "9.75",
            "total": "19.50"
        }
    ],
    "total": "19.50"
}
```
#### Response
```
{
    "id": "4",
    "customer-id": "6",
    "items": [
        {
            "product-id": "A101",
            "quantity": "2",
            "unit-price": "9.75",
            "total": "19.50"
        }
    ],
    "total": "19.50",
    "discount-error": "Customer ID could not be located."
}
```
