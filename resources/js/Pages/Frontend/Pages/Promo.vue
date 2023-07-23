<template>
    <div>
        <h3 class="text-center">{{ promo.title }}</h3>
        <img :src="promo.banner" alt="" height="450">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-content-center justify-content-between">
                    <h3>{{ new Date(props.promo.start_date) > new Date() ? "Sell Start From" : "On Sell Now" }}</h3>
                    <DellsTimer :start_date="props.promo.start_date" :end_date="props.promo.end_date"/>
                    <a :href="`/single-promo-show/${props.promo.id}`" class="btn btn-primary">Show More</a>
                </div>
                <div class="row mt-5 match-height">
                    <div class="col-lg-3 mb-4 col-md-3 product-item" v-for="(product, index) in promo.products" :key="product.title" >
                        <Products :product="product" v-if="index < 5"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import {ref} from 'vue'
import Products from "../Product/OfferProduct.vue";
import DellsTimer from "../Modules/DellsTimer.vue";

const props = defineProps({
    promo:Array,
})



</script>


<style scoped>
    .promos img{
        width: 100%;
        height: 250px !important;
        object-fit: cover;
    }
</style>
