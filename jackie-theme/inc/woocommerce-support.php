<?php
/**
 * WooCommerce Support & Customization
 */

if (!defined('ABSPATH')) exit;

/* ---------- Remove default WooCommerce wrappers ---------- */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

function jackie_woo_wrapper_start() {
    echo '<section class="section"><div class="container">';
}
add_action('woocommerce_before_main_content', 'jackie_woo_wrapper_start', 10);

function jackie_woo_wrapper_end() {
    echo '</div></section>';
}
add_action('woocommerce_after_main_content', 'jackie_woo_wrapper_end', 10);

/* ---------- Products per page ---------- */
function jackie_woo_products_per_page($cols) {
    return 12;
}
add_filter('loop_shop_per_page', 'jackie_woo_products_per_page');

/* ---------- Products per row ---------- */
function jackie_woo_loop_columns() {
    return 3;
}
add_filter('loop_shop_columns', 'jackie_woo_loop_columns');

/* ---------- Related products ---------- */
function jackie_woo_related_products($args) {
    $args['posts_per_page'] = 3;
    $args['columns'] = 3;
    return $args;
}
add_filter('woocommerce_output_related_products_args', 'jackie_woo_related_products');

/* ---------- Add shop link to nav ---------- */
function jackie_add_shop_nav_item($items, $args) {
    if ($args->theme_location === 'primary' && class_exists('WooCommerce')) {
        $shop_url = wc_get_page_permalink('shop');
        $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
        $cart_url = wc_get_cart_url();

        $shop_item = '<li class="menu-item"><a href="' . esc_url($shop_url) . '">Shop</a></li>';
        $cart_item = '<li class="menu-item menu-item-cart"><a href="' . esc_url($cart_url) . '"><i class="fas fa-shopping-cart"></i>';
        if ($cart_count > 0) {
            $cart_item .= ' <span class="cart-count">' . $cart_count . '</span>';
        }
        $cart_item .= '</a></li>';

        $items .= $shop_item . $cart_item;
    }
    return $items;
}
add_filter('wp_nav_menu_items', 'jackie_add_shop_nav_item', 10, 2);

/* ---------- WooCommerce Cart Fragment for AJAX ---------- */
function jackie_cart_fragment($fragments) {
    $cart_count = WC()->cart->get_cart_contents_count();
    $fragments['.cart-count'] = '<span class="cart-count">' . $cart_count . '</span>';
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'jackie_cart_fragment');

/* ---------- WooCommerce Styles ---------- */
function jackie_woo_styles() {
    if (!class_exists('WooCommerce')) return;

    $css = '
    /* WooCommerce Shop Grid */
    .woocommerce ul.products {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
    }
    .woocommerce ul.products li.product {
        width: 100% !important;
        margin: 0 !important;
        float: none !important;
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 0;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .woocommerce ul.products li.product:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
    }
    .woocommerce ul.products li.product a img {
        border-radius: 0;
        margin: 0;
    }
    .woocommerce ul.products li.product .woocommerce-loop-product__title {
        font-family: var(--font-heading);
        font-size: 1.1rem;
        font-weight: 700;
        padding: 16px 20px 0;
        color: var(--text-primary);
    }
    .woocommerce ul.products li.product .price {
        padding: 8px 20px;
        font-family: var(--font-heading);
        font-weight: 700;
        color: var(--purple);
    }
    .woocommerce ul.products li.product .button,
    .woocommerce ul.products li.product a.added_to_cart {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin: 0 20px 20px;
        padding: 10px 24px;
        background: var(--gradient-primary);
        color: #fff;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }
    .woocommerce ul.products li.product .button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(124, 58, 237, 0.3);
    }

    /* Single Product */
    .woocommerce div.product {
        background: var(--card-bg);
        border-radius: var(--radius-lg);
        padding: 40px;
        border: 1px solid var(--border-color);
    }
    .woocommerce div.product .product_title {
        font-family: var(--font-heading);
        font-weight: 800;
        font-size: 2rem;
        color: var(--text-primary);
    }
    .woocommerce div.product p.price {
        font-family: var(--font-heading);
        font-weight: 700;
        font-size: 1.5rem;
        color: var(--purple);
    }
    .woocommerce div.product .single_add_to_cart_button {
        background: var(--gradient-primary) !important;
        border-radius: 30px !important;
        padding: 14px 32px !important;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    .woocommerce div.product .single_add_to_cart_button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(124, 58, 237, 0.4);
    }

    /* Cart & Checkout */
    .woocommerce .cart-collaterals .cart_totals,
    .woocommerce-checkout #payment {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 30px;
    }
    .woocommerce #respond input#submit,
    .woocommerce a.button,
    .woocommerce button.button,
    .woocommerce input.button {
        background: var(--gradient-primary);
        color: #fff;
        border-radius: 30px;
        padding: 12px 28px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }
    .woocommerce #respond input#submit:hover,
    .woocommerce a.button:hover,
    .woocommerce button.button:hover,
    .woocommerce input.button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(124, 58, 237, 0.3);
    }

    /* Cart count badge */
    .cart-count {
        background: var(--orange);
        color: #fff;
        font-size: 0.7rem;
        font-weight: 700;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-left: 4px;
    }

    /* Responsive Shop */
    @media (max-width: 768px) {
        .woocommerce ul.products { grid-template-columns: 1fr 1fr; gap: 16px; }
    }
    @media (max-width: 480px) {
        .woocommerce ul.products { grid-template-columns: 1fr; }
        .woocommerce div.product { padding: 20px; }
    }
    ';

    wp_add_inline_style('jackie-theme-style', $css);
}
add_action('wp_enqueue_scripts', 'jackie_woo_styles', 20);
