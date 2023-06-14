<script setup>
    import Layout from "../../Frontend/Shared/Layout.vue";
    import {useCartStore} from "../../../Store/useCartStore";
    import {onMounted} from 'vue'
    import CartItemCard from '../Modules/CartItemCard.vue'
    import QuantityButton from '../../../components/QuantityButton.vue'
    import {useWishListStore} from "../../../Store/useWishListStore";
    const watchlistStore = useWishListStore();
    const store = useCartStore();
    onMounted(()=>{
        store.initCart();
    })


    const wishListToCart= (item) =>{
        watchlistStore.addToWishList(item);
        store.removeFromCart(item);
        $toast.success("Moved Cart To Wishlist...")
    }

</script>

<template>
    <Layout>
        <div class="container-sm my-5">
            <div class="row">
                <!-- Cart left -->
                <div class="col-xl-8 mb-3 mb-xl-0">
                    <div class="card details-cart-item" v-if="store.getCartItems.length > 0" v-for="item in store.getCartItems" :key="item.id" >
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div class="d-flex col-8">
                                <img class="me-1" :src="item.thumbnail" alt="" width="100">
                                <div class="d-flex flex-column">
                                    <h3 class="text-capitalize text-primary">{{ item.title }}</h3>
                                    <div>
                                        <span>{{ item.sellPrice }} <vue-feather type="x" size="12"/> {{ item.quantity }} = {{ item.sellPrice * item.quantity }} ৳</span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-column align-items-end">
                                <vue-feather type="x" size="15" class="cursor-pointer" @click="store.removeFromCart(item)"/>
                                <QuantityButton :productId="item.id" :maxQty="item.qty" class="mt-1"/>
                                <button class="btn btn-sm btn-primary mt-2" @click="wishListToCart(item)">Move to Wish list</button>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center h-100" v-else>
                        Your Cart Is Empty.
                    </div>
                </div>
                <!-- Cart right -->
                <div class="col-xl-4">
                    <div class="border rounded p-3 mb-3">

                        <!-- Offer -->
<!--
                        <h6>Offer</h6>
                        <div class="row g-3 mb-3">
                            <div class="col-8 col-xxl-8 col-xl-12">
                                <input type="text" class="form-control" placeholder="Enter Promo Code" aria-label="Enter Promo Code">
                            </div>
                            <div class="col-4 col-xxl-4 col-xl-12">
                                <div class="d-grid">
                                    <button type="button" class="btn btn-label-primary">Apply</button>
                                </div>
                            </div>
                        </div>
-->


<!--                        <hr class="mx-n3">-->

                        <!-- Price Details -->
                        <h6>Price Details</h6>
                        <dl class="row mb-0">
                            <dt class="col-6 fw-normal">Bag Total</dt>
                            <dd class="col-6 text-end">{{ store.getCartTotalPrice }} ৳</dd>
<!--
                            <dt class="col-6 fw-normal">Coupon Discount</dt>
                            <dd class="col-6 text-success text-end"> -$98.00</dd>

                            <dt class="col-6 fw-normal">Order Total</dt>
                            <dd class="col-6 text-end">$1100.00</dd>-->


<!--                            <dt class="col-6 fw-normal">Vat</dt>
                            <dd class="col-6 text-end">৳ 5.00 </dd>

                            <dt class="col-6 fw-normal">Tax</dt>
                            <dd class="col-6 text-end">৳ 5.00 </dd>-->

                            <hr>

                            <dt class="col-6">Total</dt>
                            <dd class="col-6 fw-semibold text-end mb-0">{{ store.getCartTotalPrice }} ৳</dd>
                        </dl>
                    </div>
                    <div class="d-grid mb-3">
                        <a :href="$page.props.auth.MAIN_URL+'/checkout'" v-if="store.getCartLength > 0" class="btn btn-primary btn-next">Place Order here</a>
                        <button v-else disabled="true" class="btn btn-primary btn-next" v-c-tooltip="'Add Product On Your Cart Before Plase Order'">Place Order here</button>
                    </div>
                </div>
            </div>



<!--
            &lt;!&ndash; Address &ndash;&gt;
            <div id="checkout-address" class="content fv-plugins-bootstrap5 fv-plugins-framework">
                <div class="row">
                    &lt;!&ndash; Address left &ndash;&gt;
                    <div class="col-xl-9 mb-3 mb-xl-0">

                        &lt;!&ndash; Select address &ndash;&gt;
                        <p>Select your preferable address</p>
                        <div class="row mb-3">
                            <div class="col-md mb-md-0 mb-2">
                                <div class="form-check custom-option custom-option-basic checked">
                                    <label class="form-check-label custom-option-content" for="customRadioAddress1">
                                        <input name="customRadioTemp" class="form-check-input" type="radio" value="" id="customRadioAddress1" checked="">
                                        <span class="custom-option-header">
                      <span class="fw-semibold">John Doe (Default)</span>
                      <span class="badge bg-label-primary">Home</span>
                    </span>
                                        <span class="custom-option-body">
                      <small>4135 Parkway Street, Los Angeles, CA, 90017.<br> Mobile : 1234567890 Cash / Card on delivery available</small>
                      <hr>
                      <span class="d-flex">
                        <a class="me-2" href="javascript:void(0)">Edit</a> <a href="javascript:void(0)">Remove</a>
                      </span>
                    </span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-check custom-option custom-option-basic">
                                    <label class="form-check-label custom-option-content" for="customRadioAddress2">
                                        <input name="customRadioTemp" class="form-check-input" type="radio" value="" id="customRadioAddress2">
                                        <span class="custom-option-header">
                      <span class="fw-semibold">ACME Inc.</span>
                      <span class="badge bg-label-success">Office</span>
                    </span>
                                        <span class="custom-option-body">
                      <small>87 Hoffman Avenue, New York, NY, 10016.<br>Mobile : 1234567890 Cash / Card on delivery available</small>
                      <hr>
                      <span class="d-flex">
                        <a class="me-2" href="javascript:void(0)">Edit</a> <a href="javascript:void(0)">Remove</a>
                      </span>
                    </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-label-primary mb-4" data-bs-toggle="modal" data-bs-target="#addNewAddress">Add new address</button>

                        &lt;!&ndash; Choose Delivery &ndash;&gt;
                        <p class="mt-2">Choose Delivery Speed</p>
                        <div class="row">
                            <div class="col-md mb-md-0 mb-2">
                                <div class="form-check custom-option custom-option-icon position-relative checked">
                                    <label class="form-check-label custom-option-content" for="customRadioDelivery1">
                    <span class="custom-option-body">
                      <i class="bx bx-user"></i>
                      <span class="custom-option-title">Standard</span>
                      <span class="badge bg-label-success btn-pinned">FREE</span>
                      <small>Get your product in 1 Week.</small>
                    </span>
                                        <input name="customRadioIcon" class="form-check-input" type="radio" value="" id="customRadioDelivery1" checked="">
                                    </label>
                                </div>
                            </div>
                            <div class="col-md mb-md-0 mb-2">
                                <div class="form-check custom-option custom-option-icon position-relative">
                                    <label class="form-check-label custom-option-content" for="customRadioDelivery2">
                    <span class="custom-option-body">
                      <i class="bx bx-crown"></i>
                      <span class="custom-option-title">Express</span>
                      <span class="badge bg-label-secondary btn-pinned">$10</span>
                      <small>Get your product in 3-4 days.</small>
                    </span>
                                        <input name="customRadioIcon" class="form-check-input" type="radio" value="" id="customRadioDelivery2">
                                    </label>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-check custom-option custom-option-icon position-relative">
                                    <label class="form-check-label custom-option-content" for="customRadioDelivery3">
                    <span class="custom-option-body">
                      <i class="bx bx-rocket"></i>
                      <span class="custom-option-title">Overnight</span>
                      <span class="badge bg-label-secondary btn-pinned">$15</span>
                      <small>Get your product in 1 day.</small>
                    </span>
                                        <input name="customRadioIcon" class="form-check-input" type="radio" value="" id="customRadioDelivery3">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    &lt;!&ndash; Address right &ndash;&gt;
                    <div class="col-xl-3">
                        <div class="border rounded p-3 mb-3">

                            &lt;!&ndash; Estimated Delivery &ndash;&gt;
                            <h6>Estimated Delivery Date</h6>
                            <ul class="list-unstyled">
                                <li class="d-flex gap-3">
                                    <div class="flex-shrink-0">
                                        <img src="../../assets/img/products/1.png" alt="google home" class="w-px-50">
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0"><a class="text-body" href="javascript:void(0)">Google - Google Home - White</a></p>
                                        <p class="fw-semibold">18th Nov 2021</p>
                                    </div>
                                </li>
                                <li class="d-flex gap-3">
                                    <div class="flex-shrink-0">
                                        <img src="../../assets/img/products/2.png" alt="google home" class="w-px-50">
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0"><a class="text-body" href="javascript:void(0)">Apple iPhone 11 (64GB, Black)</a></p>
                                        <p class="fw-semibold">20th Nov 2021</p>
                                    </div>
                                </li>
                            </ul>

                            <hr class="mx-n3">

                            &lt;!&ndash; Price Details &ndash;&gt;
                            <h6>Price Details</h6>
                            <dl class="row mb-0">

                                <dt class="col-6 fw-normal">Order Total</dt>
                                <dd class="col-6 text-end">$1100.00</dd>

                                <dt class="col-6 fw-normal">Delivery Charges</dt>
                                <dd class="col-6 text-end"><s>$5.00</s> <span class="badge bg-label-success">Free</span></dd>

                                <hr>

                                <dt class="col-6">Total</dt>
                                <dd class="col-6 fw-semibold text-end mb-0">$1100.00</dd>
                            </dl>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary btn-next">Place Order</button>
                        </div>
                    </div>
                </div>
            </div>

            &lt;!&ndash; Payment &ndash;&gt;
            <div id="checkout-payment" class="content fv-plugins-bootstrap5 fv-plugins-framework">
                <div class="row">
                    &lt;!&ndash; Payment left &ndash;&gt;
                    <div class="col-xl-9 mb-3 mb-xl-0">
                        &lt;!&ndash; Offer alert &ndash;&gt;
                        <div class="alert alert-success" role="alert">
                            <div class="d-flex gap-3">
                                <div class="flex-shrink-0">
                  <span class="badge badge-center rounded-pill bg-success border-label-success p-3 me-2">
                    <i class="bx bx-sm bx-purchase-tag fs-4"></i>
                  </span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">Bank Offers</div>
                                    <ul class="list-unstyled mb-0">
                                        <li> - 10% Instant Discount on Bank of America Corp Bank Debit and Credit cards</li>
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>

                        &lt;!&ndash; Payment Tabs &ndash;&gt;
                        <div class="col-xxl-6 col-lg-8">
                            <ul class="nav nav-pills mb-3" id="paymentTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pills-cc-tab" data-bs-toggle="pill" data-bs-target="#pills-cc" type="button" role="tab" aria-controls="pills-cc" aria-selected="true">Home</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-cod-tab" data-bs-toggle="pill" data-bs-target="#pills-cod" type="button" role="tab" aria-controls="pills-cod" aria-selected="false" tabindex="-1">Cash On Delivery</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-gift-card-tab" data-bs-toggle="pill" data-bs-target="#pills-gift-card" type="button" role="tab" aria-controls="pills-gift-card" aria-selected="false" tabindex="-1">Gift Card</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="paymentTabsContent">
                                &lt;!&ndash; Credit card &ndash;&gt;
                                <div class="tab-pane fade show active" id="pills-cc" role="tabpanel" aria-labelledby="pills-cc-tab">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label w-100" for="paymentCard">Card Number</label>
                                            <div class="input-group input-group-merge">
                                                <input id="paymentCard" name="paymentCard" class="form-control credit-card-mask" type="text" placeholder="1356 3215 6548 7898" aria-describedby="paymentCard2">
                                                <span class="input-group-text cursor-pointer p-1" id="paymentCard2"><span class="card-type"></span></span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label" for="paymentCardName">Name</label>
                                            <input type="text" id="paymentCardName" class="form-control" placeholder="John Doe">
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <label class="form-label" for="paymentCardExpiryDate">Exp. Date</label>
                                            <input type="text" id="paymentCardExpiryDate" class="form-control expiry-date-mask" placeholder="MM/YY">
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <label class="form-label" for="paymentCardCvv">CVV Code</label>
                                            <div class="input-group input-group-merge">
                                                <input type="text" id="paymentCardCvv" class="form-control cvv-code-mask" maxlength="3" placeholder="654">
                                                <span class="input-group-text cursor-pointer" id="paymentCardCvv2"><i class="bx bx-help-circle text-muted" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Card Verification Value" data-bs-original-title="Card Verification Value"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="switch">
                                                <input type="checkbox" class="switch-input">
                                                <span class="switch-toggle-slider">
                          <span class="switch-on"></span>
                          <span class="switch-off"></span>
                        </span>
                                                <span class="switch-label">Save card for future billing?</span>
                                            </label>
                                        </div>
                                        <div class="col-12">
                                            <button type="button" class="btn btn-primary btn-next me-sm-3 me-1">Submit</button>
                                            <button type="reset" class="btn btn-label-secondary">Cancel</button>
                                        </div>
                                    </div>
                                </div>

                                &lt;!&ndash; COD &ndash;&gt;
                                <div class="tab-pane fade" id="pills-cod" role="tabpanel" aria-labelledby="pills-cod-tab">
                                    <p>Cash on Delivery is a type of payment method where the recipient make payment for the order at the time of delivery rather than in advance.</p>
                                    <button type="button" class="btn btn-primary btn-next">Pay On Delivery</button>
                                </div>

                                &lt;!&ndash; Gift card &ndash;&gt;
                                <div class="tab-pane fade" id="pills-gift-card" role="tabpanel" aria-labelledby="pills-gift-card-tab">
                                    <h6>Enter Gift Card Details</h6>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="giftCardNumber" class="form-label">Gift card number</label>
                                            <input type="number" class="form-control" id="giftCardNumber" placeholder="Gift card number">
                                        </div>
                                        <div class="col-12">
                                            <label for="giftCardPin" class="form-label">Gift card pin</label>
                                            <input type="number" class="form-control" id="giftCardPin" placeholder="Gift card pin">
                                        </div>
                                        <div class="col-12">
                                            <button type="button" class="btn btn-primary btn-next">Redeem Gift Card</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    &lt;!&ndash; Address right &ndash;&gt;
                    <div class="col-xl-3">
                        <div class="border rounded p-3">

                            &lt;!&ndash; Price Details &ndash;&gt;
                            <h6>Price Details</h6>
                            <dl class="row">

                                <dt class="col-6 fw-normal">Order Total</dt>
                                <dd class="col-6 text-end">$1100.00</dd>

                                <dt class="col-6 fw-normal">Delivery Charges</dt>
                                <dd class="col-6 text-end"><s>$5.00</s> <span class="badge bg-label-success">Free</span></dd>

                                <hr>

                                <dt class="col-6">Total</dt>
                                <dd class="col-6 fw-semibold text-end mb-0">$1100.00</dd>

                                <dt class="col-6">Deliver to:</dt>
                                <dd class="col-6 fw-semibold text-end mb-0"><span class="badge bg-label-primary">Home</span></dd>
                            </dl>
                            &lt;!&ndash; Address Details &ndash;&gt;
                            <address>
                                <span class="fw-semibold"> John Doe (Default),</span><br>
                                4135 Parkway Street, <br>
                                Los Angeles, CA, 90017. <br>
                                Mobile : +1 906 568 2332
                            </address>
                            <a href="javascript:void(0)">Change address</a>
                        </div>
                    </div>
                </div>
            </div>

            &lt;!&ndash; Confirmation &ndash;&gt;
            <div id="checkout-confirmation" class="content fv-plugins-bootstrap5 fv-plugins-framework active dstepper-block">
                <div class="row mb-3">
                    <div class="col-12 col-lg-8 offset-lg-2 text-center mb-3">
                        <h4 class="mt-2">Thank You! 😇</h4>
                        <p>Your order <a href="javascript:void(0)">#1536548131</a> has been placed!</p>
                        <p>We sent an email to <a href="mailto:john.doe@example.com">john.doe@example.com</a> with your order confirmation and receipt. If the email hasn't arrived within two minutes, please check your spam folder to see if the email was routed there.</p>
                        <p><span class="fw-semibold"><i class="bx bx-time-five"></i> Time placed:</span> 25/05/2020 13:35pm</p>
                    </div>
                    &lt;!&ndash; Confirmation details &ndash;&gt;
                    <div class="col-12">
                        <ul class="list-group list-group-horizontal-md">
                            <li class="list-group-item flex-fill">
                                <h6><i class="bx bx-map"></i> Shipping</h6>
                                <address>
                                    John Doe <br>
                                    4135 Parkway Street,<br>
                                    Los Angeles, CA 90017,<br>
                                    USA <br>
                                    +123456789
                                </address>
                            </li>
                            <li class="list-group-item flex-fill">
                                <h6><i class="bx bx-credit-card"></i> Billing Address</h6>
                                <address>
                                    John Doe <br>
                                    4135 Parkway Street,<br>
                                    Los Angeles, CA 90017,<br>
                                    USA <br>
                                    +123456789
                                </address>
                            </li>
                            <li class="list-group-item flex-fill">
                                <h6><i class="bx bx-train"></i> Shipping Method</h6>
                                <span class="fw-semibold">Preferred Method:</span><br>
                                Standard Delivery<br>
                                (Normally 3-4 business days)
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="row">
                    &lt;!&ndash; Confirmation items &ndash;&gt;
                    <div class="col-xl-9 mb-3 mb-xl-0">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <div class="d-flex gap-3">
                                    <div class="flex-shrink-0">
                                        <img src="../../assets/img/products/1.png" alt="google home" class="w-px-75">
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <a href="javascript:void(0)" class="text-body">
                                                    <h6>Google - Google Home - White</h6>
                                                </a>
                                                <div class="text-muted mb-1 d-flex flex-wrap"><span class="me-1">Sold by:</span> <a href="javascript:void(0)" class="me-1">Apple</a> <span class="badge bg-label-success">In Stock</span></div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-md-end">
                                                    <div class="my-2 my-lg-4"><span class="text-primary">$299/</span><s class="text-muted">$359</s></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex gap-3">
                                    <div class="flex-shrink-0">
                                        <img src="../../assets/img/products/2.png" alt="google home" class="w-px-75">
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <a href="javascript:void(0)" class="text-body">
                                                    <h6>Apple iPhone 11 (64GB, Black)</h6>
                                                </a>
                                                <div class="text-muted mb-1 d-flex flex-wrap"><span class="me-1">Sold by:</span> <a href="javascript:void(0)" class="me-1">Apple</a> <span class="badge bg-label-success">In Stock</span></div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-md-end">
                                                    <div class="my-2 my-lg-4"><span class="text-primary">$299/</span><s class="text-muted">$359</s></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    &lt;!&ndash; Confirmation total &ndash;&gt;
                    <div class="col-xl-3">
                        <div class="border rounded p-3">
                            &lt;!&ndash; Price Details &ndash;&gt;
                            <h6>Price Details</h6>
                            <dl class="row mb-0">

                                <dt class="col-6 fw-normal">Order Total</dt>
                                <dd class="col-6 text-end">$1100.00</dd>

                                <dt class="col-6 fw-normal">Delivery Charges</dt>
                                <dd class="col-6 text-end"><s>$5.00</s> <span class="badge bg-label-success">Free</span></dd>

                                <hr>

                                <dt class="col-6">Total</dt>
                                <dd class="col-6 fw-semibold text-end mb-0">$1100.00</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>


-->


       <!--     <div class="row justify-content-center">
                <div class="col-lg-8">
                    <CartItemCard  v-for="item in store.getCartItems" :key="item.id" :item="item"/>
                </div>
            </div>-->
        </div>
    </Layout>
</template>



<style scoped>

</style>
