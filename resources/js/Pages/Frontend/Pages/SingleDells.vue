<template>
    <Layout>
        <section>

            <div class="row mx-auto">
                <div class="col-md-12 mx-auto">
                    <img :src="props.promo.banner" alt="" class="w-100 details-banner-h" style="object-fit: unset;">
                    <div class="row match-height mt-4">
                        <div class="d-flex align-content-center justify-content-between mb-5">
                            <h2>{{ props.promo.title }}</h2>
                            <DellsTimer :start_date="props.promo.start_date" :end_date="props.promo.end_date"/>
                        </div>
                        <div class="col-lg-3 mb-4 col-md-3 product-item" v-for="product in props.promo.products">
                            <OfferProduct :product="product" v-if="product"/>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </Layout>
</template>

<script setup>

import Products from '../Pages/Products.vue'
import Layout from '../Shared/Layout.vue'
import DellsTimer from "../Modules/DellsTimer.vue";
import OfferProduct from "../Product/OfferProduct.vue";

const props = defineProps({
    promo:null,
})

</script>
<style scoped>

.category-details h3 a:hover{
    text-decoration: underline;
}
.cat-box{
    box-shadow: 2px 1px 12px 0px #ffdac3;
    padding: 2rem;
    border-radius: 15px;
    transition: 0.3s all ease;
}
.cat-box:hover{
    box-shadow:none;
}
</style>
