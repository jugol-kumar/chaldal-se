<script setup>
import {computed, ref, onMounted} from 'vue'
import Layout from "../Shared/Layout.vue";
import Gallery from "../Modules/Gallery.vue";
import ProductCarousel from "../Modules/ProductCarousel.vue";
import {useCartStore} from "../../../Store/useCartStore";
import {useWishListStore} from "../../../Store/useWishListStore";
import {useData} from "../../../Composables/useData";
const store = useCartStore();
const watchlistStore = useWishListStore();
const data = useData();


    onMounted(() =>{
        store.initCart();
        watchlistStore.initWishList();
    })
    const props = defineProps({
        product:Object|null,
        color:Object|null,
        discountData:[]|null,
    })

    const currentSlide = ref(null)
    const sizeRadio = ref(false)
    const breakpoints = {
        700: {
            itemsToShow: 3,
            snapAlign: 'end',
        },
        1024: {
            itemsToShow: 5,
            snapAlign: 'start'
        }
    }

    const images = computed(() =>{
        let images = [];
        for (let i = 0; i< JSON.parse(props.product.images).length; i++){
            images.push("/storage/"+JSON.parse(props.product.images)[i])
        }
        images.push(props.product.thumbnail)
        return images;
    })

    const discountPrice = computed(()=>{
        if (props.discountData?.discount_price && props.discountData?.discount_type){
            return data.discountPrice(props.product.price, props.discountData.discount_price, props.discountData.discount_type);
        }else{
            return false;
        }
    })

    const cartSize = ref(null);
    const selectSize = (event) => cartSize.value = event.target.value;
    const addToCart = (product) =>{
        if (discountPrice.value){
            if(cartSize.value !== null){
                store.addToCart({...product, quantity:1, size:cartSize, sellPrice:discountPrice.value})
            }else{
                $toast.warning("Please chose first your needed size...")
            }
        }else{
            if(cartSize.value !== null){
                store.addToCart({...product, quantity:1, size:cartSize, sellPrice:product.price})
            }else{
                $toast.warning("Please chose first your needed size...")
            }
        }
    }
    const isInCart = computed(() =>{
        return store.cart.find(item => item.id === props.product.id)?.quantity
    })

    const addToWishList = (product) =>{
        if (discountPrice.value){
            if(cartSize.value !== null){
                watchlistStore.addToWishList({...product, quantity:1, size:cartSize, sellPrice:discountPrice.value})
            }else{
                $toast.warning("Please chose first your needed size...")
            }
        }else{
            if(cartSize.value !== null){
                watchlistStore.addToWishList({...product, quantity:1, size:cartSize, sellPrice:product.price })
            }else{
                $toast.warning("Please chose first your needed size...")
            }
        }

    }

</script>

<template>
    <Layout>
        <section class="app-ecommerce-details mt-2">
            <div class="card card-shadow">
                <!-- Product Details starts -->
                <div class="card-body">
                    <div class="row my-2">
                        <div class="col-12 col-md-5">
                            <div class="row flex-column">
                                <Gallery :images="images"/>
                            </div>
                        </div>
                        <div class="col-12 col-md-7">
                            <h1>Sku Id : #_{{ product.sku }}</h1>

                            <h2 class="text-capitalize mt-2 text-primary" v-text="product.title"></h2>

                            <span class="card-text item-company">By
                                <a href="#" class="company-name">{{ product.brand.name }}</a> & Category By
                                <a href="#" class="company-name">{{ product.category.title }}</a>
                            </span>
                            <div class="ecommerce-details-price d-flex flex-wrap mt-1">
                                <h4 class="item-price me-1">
                                    <s v-if="discountPrice">{{ $showPrice(product.price) }}</s>
                                    <span v-else>{{ $showPrice(product.price) }}</span>
                                </h4>
                                <h4 class="item-price me-1" v-if="discountPrice">
                                    {{ discountPrice ? '| '+ $showPrice(discountPrice) : '' }}
                                    <span class="discount-span" :class="data.spanIsRed ? 'discount-span-red' : 'discount-span-black' " v-if="discountPrice">
                                        {{ props.discountData.discount_price }} {{ props.discountData.discount_type === 'flat' ? data.priceSignature : '%' }} Off
                                    </span>
                                </h4>

<!--                                <ul class="unstyled-list list-inline ps-1 border-start">
                                    <li class="ratings-list-item" v-for="item in 4">
                                        <vue-feather type="star" class="filled-star"/>
                                    </li>
                                    <li class="ratings-list-item">
                                        <vue-feather type="star" class="unfilled-star"/>
                                    </li>
                                </ul>-->
                            </div>
                            <p class="card-text">Available - <span class="text-success">{{ product.qty > 0 ? 'In stock' : 'Out of stock' }}</span></p>

                            <p class="card-text" v-text="product.description"></p>
<!--                            <ul class="product-features list-unstyled">
                                <li class="d-flex align-items-center mb-1">
                                    <vue-feather type="shopping-cart"/>
                                    <span class="ms-1">Free Shipping</span>
                                </li>
                            </ul>-->
                            <hr />

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4>Color</h4>
                                            <div class="color-box p-1 d-block" :style="{background:color.code}"></div>
                                            <small>{{ color.name }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4>Size's</h4>
                                            <select class="select2 form-select" @change="selectSize">
                                                <option value="null" selected disabled>I want this size.</option>
                                                <option :value="item" v-for="item in JSON.parse(product.sizes)">{{ item }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10" v-if="sizeRadio">
                                    <div class="card" v-for="item in JSON.parse(product.sizes)">
                                        <div class="card-header">
                                            <h4 class="card-title">Basic Radio</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row custom-options-checkable g-1">
                                                <div class="col">
                                                    <input class="custom-option-item-check" type="radio" name="customOptionsCheckableRadios" id="customOptionsCheckableRadios1" value="">
                                                    <label class="custom-option-item p-1" for="customOptionsCheckableRadios1">
                                                        {{ item }}
                                                    </label>
                                                </div>
                                                <div class="col">
                                                    <input class="custom-option-item-check" type="radio" name="customOptionsCheckableRadios" id="customOptionsCheckableRadios2" value="">
                                                    <label class="custom-option-item p-1" for="customOptionsCheckableRadios2">
                                                        {{ item }}
                                                    </label>
                                                </div>
                                                <div class="col">
                                                    <input class="custom-option-item-check" type="radio" name="customOptionsCheckableRadios" id="customOptionsCheckableRadios3" value="">
                                                    <label class="custom-option-item p-1" for="customOptionsCheckableRadios3">
                                                        {{ item }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>




                            <hr />
                            <div class="d-flex flex-column flex-sm-row pt-1">
                                <button v-if="isInCart === undefined" @click="addToCart(product)"  class="btn btn-primary btn-cart me-0 me-sm-1 mb-1 mb-sm-0 d-flex align-items-center">
                                    <vue-feather type="shopping-cart" class="me-50"/>
                                    <span class="add-to-cart">{{"Add to cart"}}</span>
                                </button>

                                <button v-else @click="store.incrementQty(product.id)"
                                        :disabled="isInCart === product.qty"
                                        class="btn btn-primary btn-cart me-0 me-sm-1 mb-1 mb-sm-0 d-flex align-items-center">
                                    <vue-feather type="shopping-cart" class="me-50"/>
                                    <span class="add-to-cart">{{ isInCart+" Item In Cart" }}</span>
                                </button>


                                <button @click="watchlistStore.removeFromWishList(product)"
                                        v-if="watchlistStore.wishList.find(item => item.id === product.id)"
                                        class="btn btn-outline-secondary btn-wishlist me-0 me-sm-1 mb-1 mb-sm-0 d-flex align-items-center">
                                    <vue-feather type="heart" class="me-50"/>
                                    <span>Remove Form Wishlist</span>
                                </button>

                                <button @click="addToWishList(product)" v-else class="btn btn-outline-secondary btn-wishlist me-0 me-sm-1 mb-1 mb-sm-0 d-flex align-items-center">
                                    <vue-feather type="heart" class="me-50"/>
                                    <span>Add To Wishlist</span>
                                </button>


<!--                                <div class="btn-group dropdown-icon-wrapper btn-share">
                                    <button type="button" class="btn btn-icon hide-arrow btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <vue-feather type="share-2"></vue-feather>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a href="#" class="dropdown-item d-flex align-items-center">
                                            <vue-feather type="facebook"/>
                                            <span class="add-to-cart ms-1">Facebook</span>
                                        </a>
                                        <a href="#" class="dropdown-item d-flex align-items-center">
                                            <vue-feather type="twitter"/>
                                            <span class="add-to-cart ms-1">Twitter</span>
                                        </a>
                                        <a href="#" class="dropdown-item d-flex align-items-center">
                                            <vue-feather type="instagram"/>
                                            <span class="add-to-cart ms-1">Facebook</span>
                                        </a>
                                    </div>
                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<!--
            <div class="card">
                <div class="card-body">
                    &lt;!&ndash; Item features starts &ndash;&gt;
                    <div class="item-features">
                        <div class="row text-center">
                            <div class="col-12 col-md-4 mb-4 mb-md-0">
                                <div class="w-75 mx-auto">
                                    <i data-feather="award"></i>
                                    <h4 class="mt-2 mb-1">100% Original</h4>
                                    <p class="card-text">Chocolate bar candy canes ice cream toffee. Croissant pie cookie halvah.</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 mb-4 mb-md-0">
                                <div class="w-75 mx-auto">
                                    <i data-feather="clock"></i>
                                    <h4 class="mt-2 mb-1">10 Day Replacement</h4>
                                    <p class="card-text">Marshmallow biscuit donut dragée fruitcake. Jujubes wafer cupcake.</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 mb-4 mb-md-0">
                                <div class="w-75 mx-auto">
                                    <i data-feather="shield"></i>
                                    <h4 class="mt-2 mb-1">1 Year Warranty</h4>
                                    <p class="card-text">Cotton candy gingerbread cake I love sugar plum I love sweet croissant.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    &lt;!&ndash; Item features ends &ndash;&gt;
                </div>
            </div>
-->



            <div class="card card-shadow">
                <div class="card-body">
                    <span v-html="product.specification"></span>
                </div>
            </div>


            <div class="card card-shadow">
                <div class="card-body">
                    <div class="mt-4 mb-2 text-center">
                        <h4>Related Products</h4>
                        <p class="card-text">People also search for this items</p>
                    </div>
                    <ProductCarousel :products="product.category.products"/>
                </div>
            </div>
        </section>
    </Layout>
</template>

<style scoped>
    .card-shadow{
        box-shadow: 0 0px 14px -1px rgb(34 41 47 / 32%) !important;
    }
    .color-box{
        width: 100px;
        height: 15px;
    }
    /*.carousel__slide{*/
    /*    margin: 0 1rem;*/
    /*}*/
    .discount-span{
        padding: 4px 15px;
        border-radius: 50px;
    }
</style>
