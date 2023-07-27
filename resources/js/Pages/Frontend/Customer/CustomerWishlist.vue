<script setup>
import {useForm} from "@inertiajs/inertia-vue3";
import {ref} from 'vue'
import ProductCard from "../Modules/ProductCard.vue";
import {useWishListStore} from "../../../Store/useWishListStore";
import {useCartStore} from "../../../Store/useCartStore";
import {onMounted} from "vue";
import {Inertia} from "@inertiajs/inertia";
import CustomerLayout from "./CustomerLayout.vue";
import CLayout from "./CLayout";
import Pagination from "../../../components/Pagination.vue";
import Modal from "../../../components/Modal.vue";
import ImageUploader from "../../../components/ImageUploader.vue";

const store = useWishListStore();
const cartStore = useCartStore();
const props = defineProps({
    orders:[]|null,
    success:String|null,
})
const wishListToCart= (item) =>{
    cartStore.addToCart(item);
    store.removeFromWishList(item);
    $toast.success("Moved Wishlist To Cart...")
}



onMounted(()=>{
    store.initWishList();
})

</script>

<template>
    <CLayout>
        <div class="mt-5">
            <h2>Your Wishlist Products.</h2>
            <div class="row match-height">
                <div class="col-md-4" v-for="item in store.getWishListItems" :key="item.id">
                    <div class="card">
                        <div class="card-body">
                            <button class="btn btn-icon rounded-circle btn-outline-primary waves-effect d-flex align-items-center justify-content-center float-end" @click="wishListToCart(item)">
                                <vue-feather type="shopping-cart" size="15"/>
                            </button>
                            <div class="item-img overflow-hidden">
                                <img class="img-fluid card-img-top p-2" style="max-height: 250px" :src="item.thumbnail" alt="img-placeholder">
                            </div>
                            <div class="text-start p-1 zindex-1 match-height">
                                <p class="text-primary">{{ item?.title?.slice(0, 28) }}
<!--                                    <a class="text-primary" :href="`${$page.props.auth.MAIN_URL}/product/single-product/${item.slug}`"></a>-->
                                </p>
                                <h6 class="item-price">{{ $showPrice(item.price) }}</h6>
<!--                                <p class="card-text  item-description" v-html="`${item?.description?.slice(0, 50)}...`"></p>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </CLayout>
</template>



<style lang="sass" scoped>
// Image input
.image-input
    text-aling: center
    input
        display: none
    label
        display: block
        color: #FFF
        background: #000
        padding: .3rem .6rem
        font-size: 115%
        cursor: pointer
        i
            font-size: 125%
            margin-right: .3rem
        &:hover
            i
                animation: shake .35s
    img
        max-width: 175px
        display: none
    span
        display: none
        text-align: center
        cursor: pointer

// animation keyframes
@keyframes shake
    0%
        transform: rotate(0deg)
    25%
        transform: rotate(10deg)
    50%
        transform: rotate(0deg)
    75%
        transform: rotate(-10deg)
    100%
        transform: rotate(0deg)

// other styles
body
    display: flex
    justify-content: center
    align-items: center
    height: 350px
    font-family: calibri

.order-action
    a
        font-size: 15px

</style>
