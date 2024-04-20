<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Cookie;

class CartManagement
{
    // add item to cart
    static function addItemToCart($productId)
    {
        $cartItems = self::getCartItemsFromCookie();

        $existingItem = null;

        foreach ($cartItems as $key => $item) {
            if ($item['product_id'] == $productId) {
                $existingItem = $key;
                break;
            }
        }

        if($existingItem !== null) {
            $cartItems[$existingItem]['quantity']++;
            $cartItems[$existingItem]['total_amount'] = $cartItems[$existingItem]['quantity'] * $cartItems[$existingItem]['unit_amount'];
        } else {
            $product = Product::where('id', $productId)->first(['id', 'name', 'price', 'images']);
            if($product){
                $cartItems[] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'unit_amount' => $product->price,
                    'quantity' => 1,
                    'total_amount' => $product->price,
                    'image' => $product->images[0] ?? null
                ];
            }
        }

        self::addCartItemsToCookie($cartItems);

        return count($cartItems);
    }

    // add item to cart with quantity
    static function addItemToCartWithQuantity($productId, $quantity = 1)
    {
        $cartItems = self::getCartItemsFromCookie();

        $existingItem = null;

        foreach ($cartItems as $key => $item) {
            if ($item['product_id'] == $productId) {
                $existingItem = $key;
                break;
            }
        }

        if($existingItem !== null) {
            $cartItems[$existingItem]['quantity'] = $quantity;
            $cartItems[$existingItem]['total_amount'] = $cartItems[$existingItem]['quantity'] * $cartItems[$existingItem]['unit_amount'];
        } else {
            $product = Product::where('id', $productId)->first(['id', 'name', 'price', 'images']);
            if($product){
                $cartItems[] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'unit_amount' => $product->price,
                    'quantity' => $quantity,
                    'total_amount' => $product->price,
                    'image' => $product->images[0] ?? null
                ];
            }
        }

        self::addCartItemsToCookie($cartItems);

        return count($cartItems);
    }

    // remote item from cart
    static function removeCartItem($productId)
    {
        $cartItems = self::getCartItemsFromCookie();
        foreach ($cartItems as $key => $item) {
            if ($item['product_id'] == $productId) {
                unset($cartItems[$key]);
            }
        }

        self::addCartItemsToCookie($cartItems);

        return $cartItems;
    }

    // add cart items to cookie
    static function addCartItemsToCookie($cartItems)
    {
        Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);
    }

    // clear cart items from cookie
    static function clearCartItems()
    {
        Cookie::queue(Cookie::forget('cart_items'));
    }

    // get all cart items from cookie
    static function getCartItemsFromCookie()
    {
        return json_decode(Cookie::get('cart_items'), true) ?? [];
    }

    // increment item quantity
    static function incrementQuantityToCartItem($productId)
    {
        $cartItems = self::getCartItemsFromCookie();

        foreach ($cartItems as $key => $item) {
            if ($item['product_id'] == $productId) {
                $cartItems[$key]['quantity']++;
                $cartItems[$key]['total_amount'] = $cartItems[$key]['quantity'] * $cartItems[$key]['unit_amount'];
            }
        }

        self::addCartItemsToCookie($cartItems);

        return $cartItems;
    }

    // decrement item quantity
    static function decrementQuantityToCartItem($productId)
    {
        $cartItems = self::getCartItemsFromCookie();

        foreach ($cartItems as $key => $item) {
            if ($item['product_id'] == $productId) {
                if($cartItems[$key]['quantity'] > 1){
                    $cartItems[$key]['quantity']--;
                    $cartItems[$key]['total_amount'] = $cartItems[$key]['quantity'] * $cartItems[$key]['unit_amount'];
                }
            }
        }

        self::addCartItemsToCookie($cartItems);

        return $cartItems;
    }

    // calculate grand total
    static function calculateGrandTotal($items)
    {
        return array_sum(array_column($items, 'total_amount'));
    }
}
