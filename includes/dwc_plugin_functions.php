<?php 
/*
 * Plugin Core functions 
 */


/**
 * Get Wishlist Options
 */

function dwc_get_wishlist_options($id = ''){

	$dwc_wishlist_options = get_option('dwc_wishlist_options');
	if ($dwc_wishlist_options && $id) {
		return $dwc_wishlist_options[$id];
	}else{
		return $dwc_wishlist_options;
	}
}


/**
 * Get Compare Options
 */

function dwc_get_compare_options($id = ''){

	$dwc_compare_options = get_option('dwc_compare_options');
	if ($dwc_compare_options && $id) {
		return $dwc_compare_options[$id];
	}else{
		return $dwc_compare_options;
	}
}