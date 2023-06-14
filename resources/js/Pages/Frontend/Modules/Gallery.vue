<template>
    <Carousel id="gallery" :items-to-show="1" :wrapAround="true" v-model="currentSlide">
        <Slide v-for="slide in images" :key="slide">
            <div class="carousel__item">
                <img :src="slide">
            </div>
        </Slide>
    </Carousel>

    <Carousel class="mt-1"
        id="thumbnails"
        :items-to-show="4"
        :wrapAround="true"
        v-model="currentSlide"
        ref="carousel"
    >
        <Slide v-for="(slide, index) in images" :key="slide">
            <div class="carousel__item" @click="slideTo(index)">
                <img :src="slide" alt="">
            </div>
        </Slide>
    </Carousel>
</template>

<script setup>
import { ref } from 'vue'
import { Carousel, Navigation, Slide, Pagination } from 'vue3-carousel'


defineProps({
    images:{
        type:Array,
        required:true
    }
})
    const currentSlide = ref(0)
    const slideTo = (val)=> {
        currentSlide.value = val
    }

</script>

<style scoped>
    #gallery .carousel__item{
        width: 100%;
        height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #thumbnails .carousel__item{
        width: 100%;
        height: 100px;
        margin: 0 8px;
    }

    #thumbnails .carousel__item img{
        width: 100px;
        height: 100px;
        object-fit: contain;
    }
    #gallery .carousel__item img{
        width: 350px;
        height: 250px;
    }


</style>
