<?php

/**
 * When adding a product to the cart, store its regular price as meta in the cart item
 * because during order creation, WooCommerce only retains the price paid. If the product
 * is on sale, the regular price information is lost, which is needed for my personal needs for invoice generation.
 *
 * @param $cart_item_data array Array of data for a cart item.
 * @param $product_id     integer Product ID.
 * @return mixed
 */
function my_woocommerce_add_cart_item_data( $cart_item_data, $product_id ) {
    $_product = wc_get_product( $product_id );
    $cart_item_data['my_regular_price'] = $_product->get_regular_price();
    return $cart_item_data;
}

add_filter( 'woocommerce_add_cart_item_data', 'my_woocommerce_add_cart_item_data', 10, 2 );

/**
 * Store the same information as above when creating an order line item.
 *
 * @param $item          object Instance of an order item object.
 * @param $cart_item_key string Cart item key.
 * @param $values        array  Array of values for this item.
 * @param $order         object Instance of WC_Order.
 * @return void
 */
function my_woocommerce_checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {
    if ( isset( $values['my_regular_price'] ) ) {
        $item->add_meta_data( 'my_regular_price', $values['my_regular_price'] );
    }
}

add_action( 'woocommerce_checkout_create_order_line_item', 'my_woocommerce_checkout_create_order_line_item', 10, 4 );
