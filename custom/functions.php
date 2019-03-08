<?php
/**
 * Functions.php
 *
 * @package  Woo_hiddenproduct
 * @author   NicolaErario
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * functions.php
 * Add PHP snippets here
 */

// create the column and name it "Visibility"
add_filter( 'manage_edit-product_columns', 'add_visibility_product_column', 10);
function add_visibility_product_column($columns) {

$new_columns = [];
foreach( $columns as $key => $column ){
$new_columns[$key] = $columns[$key];
if( $key == 'price' ) {
$new_columns['visibility'] = __( 'Visibility','woocommerce');
} }
return $new_columns;
}

// add content based on catalog setting, hidden or public
add_action( 'manage_product_posts_custom_column', 'add_visibility_product_column_content', 10, 2 );
function add_visibility_product_column_content( $column, $product_id ){
global $post;

if( $column =='visibility' ) {
if( has_term( 'exclude-from-catalog', 'product_visibility', $product_id ) )
echo '<em style="color:grey;font-weight:bold;">' . __("Hidden") . '</em>';
else
echo '<span style="color:green;">' . __("Visible") . '</span>';
} }

// make visibility column sortable for accessibility reasons
add_filter( "manage_edit-product_sortable_columns", 'add_visibility_product_column_sortable' );
function add_visibility_product_column_sortable( $columns )
{
$custom = array(
'visibility'    => 'Visibility',
);
return wp_parse_args( $custom, $columns );
}