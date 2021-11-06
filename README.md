# DiscountsAPI

## Method 1 - GET :/orders/{orderID}

This will pull the jSON Order live from TeamLeader CRM Environemnt on GitHub by providing the relevant Order ID number. 

## Method 2 - POST :/orders

You can POST raw jSON in the body of a request for it to process the order provided.

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
