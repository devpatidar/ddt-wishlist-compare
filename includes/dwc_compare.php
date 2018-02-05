<?php
/**
 * Compare functions
 */
/*===============================================================
|| Add To Compare & Remove From Compare List function
=================================================================*/

add_action( 'wp_ajax_nopriv_d2_add_n_remove_compare', 'd2_add_n_remove_compare_ajax' );
add_action( 'wp_ajax_d2_add_n_remove_compare', 'd2_add_n_remove_compare_ajax' );

function d2_add_n_remove_compare_ajax(){
    /* Add To Wishlist */

    if (isset($_POST['compare_prod_id'])) {

        $compare_prod_id = $_POST['compare_prod_id'];

        if (!isset($_COOKIE['d2_compare']['compare_id_'.$compare_prod_id])) {

            $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;

            setcookie('d2_compare[compare_id_'.$compare_prod_id.']', $compare_prod_id, time()+3600, '/', $domain, false);

            if(count($_COOKIE) > 0){
                echo json_encode(array('type' => 'success' , 'msg' => 'added_to_comparelist' ));
            }else{
                echo json_encode(array('type' => 'error' , 'msg' => 'Cookie Disable Or not Support Your Browser' ));
            }
        }else{
            echo json_encode(array('type' => 'already' , 'msg' => 'already_added_to_comparelist' ));
        }
    }

    /* Remove From wishlist */
    if (isset($_POST['del_compare_prod_id'])) {

        $del_compare_prod_id = $_POST['del_compare_prod_id'];

        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;

        setcookie('d2_compare[compare_id_'.$del_compare_prod_id.']', '', time()-1000, '/', $domain , 1);

        echo json_encode(array('type' => 'success' , 'msg' => 'remove_from_comparelist' ));
    }
    die();
}

/*============================================================
 || Add To Compare Button functions
=============================================================*/

function d2_add_to_compare_button_func(){

    $cm_product_id = get_the_id();

    //$compare_page = (themedirect_option('opt_compare_display_page')) ? (esc_url( get_page_link(themedirect_option('opt_compare_display_page')))) : (site_url('/compare/'));

    if (isset($_COOKIE['d2_compare']['compare_id_'.$cm_product_id])) {

        echo '<div class="d2d_compare_wrap">
            <div class="button"><a href="javascript:void(0)" class="d2d_compare_btn d2d_compare_btn_added"></a></div>
            <div class="tool-tip"><a class="browse_compare" href="'.$compare_page.'">Browse Compare</a></div>
        </div>';
    }else{
        echo '<div id="d2d-compare-wrap-id" class="d2d_compare_wrap">
                <div class="button">
                    <a href="javascript:void(0)" data-compare_id="'.$cm_product_id.'" class="d2_add_to_compare d2d_compare_btn d2d_compare_btn_add d2_add_to_compare_'.$cm_product_id.'"></a>
                    <a href="javascript:void(0)" class="d2d_compare_btn d2d_compare_btn_added d2_add_to_compare_'.$cm_product_id.' hide"></a>
                </div>

                <div class="tool-tip">
                    <a href="javascript:void(0)" data-compare_id="'.$cm_product_id.'" class="d2_add_to_compare d2_add_a_compare_'.$cm_product_id.'">Add To Compare</a>
                    <p class="d2_added_a_compare_'.$cm_product_id.' hide"><a class="browse_compare" href="'.$compare_page.'">Browse Compare</a></p>
                </div>
            </div>';
    }
}

// Use shortcode for this [d2_add_to_compare_button]
add_shortcode( 'dwc_add_to_compare_button', 'd2_add_to_compare_button_func' );

/*============================================================================
 || Display Compare product List
==============================================================================*/
function d2_display_compare_product_func(){ 
    ?>
    <div id="d2d_compare_list_id" class="d2d_compare_list container">
        <div class="row">
            <?php
            if (isset($_COOKIE['d2_compare'])) :
                $cm_product_id = '';
                // product message
                if (isset($_GET['add-to-cart'])) {
                    $Product_id = $_GET['add-to-cart'];
                    $get_pro_details = wc_get_product($Product_id);
                    $pro_title = $get_pro_details->post->post_title;
                ?>
                    <div class="woocommerce-message"><a href="<?php echo site_url('/cart/'); ?>" class="button wc-forward">View Cart</a>“<?php echo $pro_title; ?>” has been added to your cart.</div>
                <?php }

                foreach ($_COOKIE['d2_compare'] as $name => $ipvalue) {
                    $ipvalue = htmlspecialchars($ipvalue);
                    $cm_product_id[].= $ipvalue;
                }

                foreach ($cm_product_id as $key => $compare_id) :
                    $product_details = wc_get_product($compare_id);
                    $product_permalink = get_permalink($compare_id);
                    $prod_post_id = $product_details->post->ID;

                    $image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_details->id ), 'single-post-thumbnail' );
                    $imagepath = $image[0];
                ?>
                    <div class="col-md-4 col-sm-6 d2d_repeat_compare" id="cm_product_id_<?php echo $compare_id; ?>" data-product_id="<?php echo $compare_id; ?>">
                        <div class="d2d_close-btn"><a title="Remove this product" href="javascript:void(0)" class="d2_remove_from_compare" data-compare_id="<?php echo $compare_id; ?>"><i class="fa fa-times" aria-hidden="true"></i></a></div>
                        <div class="thumbnail">
                            <a href="<?php echo $product_permalink; ?>"><img class="img-responsive img-hover" src="<?php echo $imagepath; ?>" alt=""></a>
                            <div class="caption">
                                <div class="heading"><a href="<?php echo $product_permalink; ?>"><?php echo $product_details->post->post_title; ?></a></div>
                                <hr class="following">
                                <p class="d2d_short_cont"><?php echo wp_trim_words( $product_details->post->post_content, 50, '...' ); ?></p>
                                <hr class="product">
                                <div class="price"><?php echo $product_details->get_price_html(); ?></div>
                                <div class="text-center">
                                    <?php if($product_details->is_in_stock() == '0') { ?>
                                        <p class="product woocommerce add_to_cart_inline d2d-out-stock" style="border:4px solid #ccc; padding: 12px;">
                                            <a rel="nofollow" href="<?php echo get_permalink($wishlist_product_id); ?>" class="button">View Product</a>
                                        </p> 
                                    <?php } elseif ( $product_details->is_type( 'variable' ) ) { ?>
                                        <p class="product woocommerce add_to_cart_inline d2d-out-stock" style="border:4px solid #ccc; padding: 12px;">
                                            <a rel="nofollow" href="<?php echo get_permalink($wishlist_product_id); ?>" class="button">Select Options</a>
                                        </p>
                                    <?php } else {
                                        echo do_shortcode('[add_to_cart id='.$product_details->id.' show_price="false"]');
                                    }
                                    ?>
                                    <h4 class="stock">
                                        <?php if ($product_details->is_in_stock()) : ?>
                                            <i class="fa fa-check" aria-hidden="true"></i> STOCK
                                        <?php else : ?>
                                            <i aria-hidden="true" class="fa fa-times"></i> SOLD OUT
                                        <?php endif; ?>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php endforeach; ?>
            <?php else: ?>
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <p>No products were added to the Compare</p>
                    </div>
                </div>

            <?php endif; ?>
        </div>
    </div>
<?php
} // End Compare Product

// Use shortcode for this [k2_display_compare_product]
add_shortcode( 'dwc_display_compare_product', 'd2_display_compare_product_func' );