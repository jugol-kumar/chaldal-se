<template>
    <Carousel  :breakpoints="breakpoints"  :items-to-show="2.5" :wrap-around="false">
        <Slide v-for="item in props.products" :key="item.id">
            <div class="card ecommerce-card position-relative">
                <div class="item-img overflow-hidden">
                    <img class="img-fluid card-img-top p-lg-2 p-sm-0" style="max-height: 250px" :src="item.thumbnail" alt="img-placeholder">
                </div>
                <div class="text-start p-1 zindex-1 match-height">
                    <h2>
                        <a class="text-primary" :href="`${$page.props.auth.MAIN_URL}/product/single-product/${item.slug}`">{{ item.title.slice(0, 28) }}</a>
                    </h2>
                    <h6 class="item-price">{{ $showPrice(item.price) }}</h6>
                    <p class="card-text  item-description" v-html="`${item.description?.slice(0, 50)}...`"></p>
                </div>
            </div>
        </Slide>
        <template #addons>
            <Navigation v-if="props.navigation"/>
            <Pagination v-if="props.pagination"/>
        </template>
    </Carousel>
</template>

<script setup>
    import { defineComponent } from 'vue'
    import { Carousel, Navigation, Slide, Pagination } from 'vue3-carousel'
    import 'vue3-carousel/dist/carousel.css'
    import ProductCard from "./ProductCard.vue";

    const props = defineProps({
        products:{
            type:Array,
            required:true,
        },
        navigation:{
            type:Boolean,
            default:false
        },
        pagination:{
            type:Boolean,
            default:false
        }
    })
    const settings = {itemsToShow: 1, snapAlign: 'center'}
    const breakpoints = {
        360: {
            itemsToShow: 3,
            snapAlign: 'center',
        },
        700: {
            itemsToShow: 3,
                snapAlign: 'end',
        },
        1024: {
            itemsToShow: 5,
            snapAlign: 'start'
        }
    }
</script>

<style scoped>
.carousel__slide{
    margin: 0 20px;
    display: flex;
    flex-flow: column;
}

.ecommerce-card{
    box-shadow: 0 0px 14px -1px rgb(34 41 47 / 32%) !important;
    margin-top: 18px !important;
    transition: 0.3s all ease;
    flex: 1 1 auto;
}

.ecommerce-card img{
    transition: 0.3s all ease;
}
.ecommerce-card:hover{
    box-shadow: 0 0px 14px 2px rgb(34 41 47 / 54%) !important;
    transform: scale(1.04);
}

.ecommerce-card:hover .item-name a{
    color: var(--bs-primary);
}
.ecommerce-card:hover img{
    transform: scale(1.4);
}

</style>
