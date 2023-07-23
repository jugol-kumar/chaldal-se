<script setup>
    import Layout from "../../Frontend/Shared/Layout.vue";
    import {Inertia} from "@inertiajs/inertia";
    import {onMounted, ref} from "vue";
    import {useCartStore} from "../../../Store/useCartStore";
    import {useForm} from "@inertiajs/inertia-vue3";
    import axios from "axios";
    const props = defineProps({
        make_payment:null,
        payment_ssl:null,
        info:[]|null,
        checkoutData:null,
    })
    const store = useCartStore();

    const makePayment = (type)=>{
        Inertia.post(props.make_payment)
    }

    const formData = useForm({
        tramsPolicy:null,
        paymentMethod:'ssl',
    })

    const isLoading = ref(false)
    const submitPayment = ()=>{
        if (formData.tramsPolicy === null){
            $toast.warning("Please check our trams and policies.")
        }else if(formData.paymentMethod === null){
            $toast.warning("Please select your payment method.")
        }
        // else if(formData.paymentMethod != null && formData.paymentMethod === 'ssl'){
        //     axios.post(props.payment_ssl, {data: {}}).then((re) => console.log(re)).catch((er) => console.log(er));
            // Inertia.post(props.payment_ssl,{
            //     formData:formData,
            //     cartItems:store.getCartItems,
            //     totalItems:store.getCartLength,
            //     cartTotalPrice:store.getCartTotalPrice
            // },{
            //     preserveState: true,
            //     replace: true,
            //     onSuccess: page => {
            //         console.log(page)
            //     },
            //     onError: errors => {
            //         document.getElementById('actionModal').$vb.modal.hide()
            //         isLoading.value = false;
            //         $toast.error("Validation Errors...")
            //     }
            // })
        // }

        else{
            axios.post(props.make_payment, {
                formData:formData,
                cartItems:store.getCartItems,
                totalItems:store.getCartLength,
                cartTotalPrice:store.getCartTotalPrice
            }).then((res) => {
                console.log("call res");
                console.log(res.data);

                if (res.data.status != 200){
                    window.location.replace(res.data.url);
                }
                else{
                    window.location.replace(res.data.data.data);
                    // window.open(res.data.data.data, '_blank');
                }

                // if (res.data.state === "paypal"){
                //     // window.location.replace(res.data.data);
                //     console.log("call here paypal res")
                //     console.log(res.data)
                // }else{
                // }


            }).catch((err) =>{
                console.log("call err");
                console.log(err)
            })
        }
        // else{
        //     Inertia.post(props.make_payment,{
        //         formData:formData,
        //         cartItems:store.getCartItems,
        //         totalItems:store.getCartLength,
        //         cartTotalPrice:store.getCartTotalPrice
        //     },{
        //         preserveState: true,
        //         replace: true,
        //         onStart: res => {
        //             isLoading.value = true;
        //         },
        //
        //         onSuccess: page => {
        //             // isLoading.value = false;
        //             // $sToast.fire({
        //             //     icon: 'success',
        //             //     title: page.props.info.message,
        //             // })
        //
        //             console.log("its opend");
        //             console.log(page)
        //             // window.open(newRouteUrl, '_blank');
        //
        //         },
        //
        //         onError: errors => {
        //             document.getElementById('actionModal').$vb.modal.hide()
        //             isLoading.value = false;
        //             $toast.error("Validation Errors...")
        //         }
        //     })
        // }

    }

    onMounted(()=>{
        // store.clearCart();
        // store.initCart();
        // $sToast.fire({
        //     icon: 'success',
        //     title: 'Shipping Info Saved...',
        // })
    })


</script>

<template>
    <Layout>
        <div class="container-sm my-5" style="min-height: 100vh;">
            <!-- Payment -->
            <div id="checkout-payment" class="fv-plugins-bootstrap5 fv-plugins-framework">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Select your payment method</h4>
                            </div>
                            <div class="card-body">
                                <div class="row custom-options-checkable g-1 match-height">
                                    <div class="col-md-4">
                                        <input class="custom-option-item-check" v-model="formData.paymentMethod"
                                               type="radio" name="customOptionsCheckableRadiosWithIcon"
                                               id="customOptionsCheckableRadiosWithIcon1" value="ssl" checked="">
                                        <label class="custom-option-item text-center p-1" for="customOptionsCheckableRadiosWithIcon1">
                                            <img src="../../../../images/ssl.png" width="200" class="p-1"/>
                                            <span class="custom-option-item-title h4 d-block">SSl Commerz</span>
                                            <small>I want to pay by SSl Commerz</small>
                                        </label>
                                    </div>

                                    <div class="col-md-4">
                                        <input class="custom-option-item-check" v-model="formData.paymentMethod" type="radio"
                                               name="customOptionsCheckableRadiosWithIcon"
                                               id="customOptionsCheckableRadiosWithIcon2"
                                               value="cod">
                                        <label class="custom-option-item text-center text-center p-1" for="customOptionsCheckableRadiosWithIcon2">
                                            <img src="../../../../images/cod.png" width="75" class="p-1"/>
                                            <span class="custom-option-item-title h4 d-block">Cash On Delivery</span>
                                            <small>I am happy with cash on delivery</small>
                                        </label>
                                    </div>

                                    <div class="col-md-4">
                                        <input class="custom-option-item-check" v-model="formData.paymentMethod" type="radio"
                                               name="customOptionsCheckableRadiosWithIcon"
                                               id="paypal"
                                               value="paypal">
                                        <label class="custom-option-item text-center text-center p-1" for="paypal">
                                            <img src="../../../../images/paypal.svg" width="175" class="p-1"/>
                                            <span class="custom-option-item-title h4 d-block">Paypal</span>
                                            <small>I am happy to pay with paypal</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 mb-3 mb-xl-0 mx-auto">
                        <div class="col-sm-12">
                            <div class="form-check">
                                <input class="form-check-input" v-model="formData.tramsPolicy"  type="checkbox" id="login">
                                <label class="form-check-label" for="login">I agree to the
                                    <a href="">Terms and Conditions</a> ,
                                    <a href="">Return Policy</a>&
                                    <a href="">Privacy Policy</a>
                                </label>
                            </div>
                        </div>


                        <div class="btn-group mt-5">
                            <button class="btn btn-primary me-2" @click="submitPayment">Pay Now</button>
                            <button class="btn btn-outline-dark" @click="cancleOrder">Cancel</button>
                        </div>
                    </div>

<!--                    &lt;!&ndash; Address right &ndash;&gt;
                    <div class="col-xl-3">
                        <div class="border rounded p-3">
                            <h6>{{ props.checkoutData?.data?.totalItems }} In your bag</h6>
                            &lt;!&ndash; Price Details &ndash;&gt;
                            <h6>Price Details</h6>
                            <dl class="row">
                                <dt class="col-6 fw-normal">Order Total</dt>
                                <dd class="col-6 text-end">৳ {{ props.checkoutData?.data?.cart_total_price }}</dd>

                                <dt class="col-6 fw-normal">Delivery Charges</dt>
                                <dd class="col-6 text-end">
                                    ৳ {{ props.checkoutData?.data?.delivery_charge }}
                                </dd>

                                <hr>

                                <dt class="col-6">Total</dt>
                                <dd class="col-6 fw-semibold text-end mb-0">৳ {{ parseInt(props.checkoutData?.data?.cart_total_price) + parseInt(props.checkoutData?.data?.delivery_charge) }}</dd>

                            </dl>
                        </div>
                    </div>-->
                </div>
            </div>
        </div>
    </Layout>
</template>



<style scoped>

</style>
