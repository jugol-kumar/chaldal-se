<script setup>
    import TopHeader from "../Frontend/Shared/TopHeader.vue"
    import Header from "../Frontend/Shared/Header.vue"
    import HeaderMenu from "../Frontend/Shared/HeaderMenu.vue"

    import 'vue3-carousel/dist/carousel.css'
    import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'
    import {computed, onMounted, ref} from 'vue'
    import CardCarosel from "../../components/CardCarousel.vue";
    import ProductCarousel from "./Modules/ProductCarousel.vue";
    import AdsSection from "./Modules/AdsSection.vue";
    import Layout from "./Shared/Layout.vue";
    import FeaturedCategories from "./Pages/FeaturedCategories.vue";
    import Promo from "./Pages/Promo.vue";
    import Products from "./Pages/Products.vue";
    import {useDataStore} from "../../Store/useDataStore";
    import {useCartStore} from "../../Store/useCartStore";

    const store = useDataStore();
    const cartStore = useCartStore();

    const props = defineProps({
        featuredCategories:[]|null,
        featuredBrands:[]|null,
        homeCategory:[]|null,
        banners:[]|null,

        pageData:[]|null,
        promos:[]|null,
    })

    const sliders = ref({pagination:false, navigation:false,})


    const slides = computed(() =>{
        if (props.banners[0]?.info){
            return JSON.parse(props.banners[0]?.info);
        }
    })
    onMounted(()=>{
        store.initFeaturedCategories()
        cartStore.initCart()
    })

</script>

<template>
    <Head title="Home Page">

    </Head>
    <Layout>
        <section id="dashboard-ecommerce" class="sm-mt-15-rem-min">
            <div class="row match-height slider-section ms-auto">
            <div class="col-xl-12 col-md-12 col-12" v-if="banners[0]?.autoPlay">
                <Carousel :autoplay="5000" :wrap-around="true">
                    <Slide v-for="slide in slides" :key="slide">
                        <div class="carousel__item">
                            <img :src="`${$page.props.auth.MAIN_URL}/storage/${slide.path}`" alt="">
                        </div>
                    </Slide>
                    <template #addons>
                        <Navigation v-if="banners[0]?.isNavigate"/>
                        <Pagination v-if="banners[0]?.isPaginate"/>
                    </template>
                </Carousel>
            </div>

            <div class="col-xl-12 col-md-12 col-12" v-else>
                <Carousel :wrap-around="true">
                    <Slide v-for="slide in slides" :key="slide">
                        <div class="carousel__item">
                            <img :src="`${$page.props.auth.MAIN_URL}/storage/${slide.path}`" alt="">
                        </div>
                    </Slide>
                    <template #addons>
                        <Navigation v-if="banners[0]?.isNavigate"/>
                        <Pagination v-if="banners[0]?.isPaginate"/>
                    </template>
                </Carousel>
            </div>
        </div>



            <FeaturedCategories :categories="props.pageData.homeCats"/>


            <section>
                <div class="p-5 promos">
                    <div class="row justify-content-center" v-for="promo in promos" :key="promo.title">
                        <Promo :promo="promo" v-if="new Date(promo.end_date) > new Date() && promo.status === 1" />
                    </div>
                </div>
            </section>


            <section v-for="cats in store.getFeaturedCategories">
                <div class="mt-3">
                    <h3>{{ cats.title }}</h3>
                    <div class="row match-height">
                        <div class="col-lg-3 mb-4 col-md-3 product-item" v-for="product in cats.products">
                            <Products :product="product"/>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </Layout>
</template>

<style>

/*.carousel__item {*/
/*    min-height: 400px;*/
/*    width: 100%;*/
/*    background-color: var(--vc-clr-primary);*/
/*    color: var(--vc-clr-white);*/
/*    font-size: 20px;*/
/*    border-radius: 8px;*/
/*}*/

.carousel__item, .carousel__item img{
    width: 100% !important;
    height: 100% !important;
}

/*.header-before:before{*/
/*    content: '';*/
/*    width: 12rem;*/
/*    height: 1px;*/
/*    background: var(--bs-primary);*/
/*    position: absolute;*/
/*    top: 2rem;*/
/*    left: 21%;*/
/*}*/
.header-before::before,
.header-before::after{
    display: inline-block;
    content: "";
    border-top: .1rem solid var(--bs-primary);
    width: 10rem;
    margin: 0 1rem;
    transform: translateY(-1rem);

    /*content: '';*/
    /*width: 12rem;*/
    /*height: 1px;*/
    /*background: var(--bs-primary);*/
    /*position: absolute;*/
    /*top: 2rem;*/
}


</style>
