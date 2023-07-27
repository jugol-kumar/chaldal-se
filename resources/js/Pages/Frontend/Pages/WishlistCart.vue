
<template>
    <div class="card">
        <div class="card-body">
            <button class="btn btn-icon rounded-circle btn-outline-primary waves-effect d-flex align-items-center justify-content-center float-end" @click="wishListToCart(item)">
                <vue-feather type="shopping-cart" size="15"/>
            </button>
            <div class="item-img overflow-hidden">
                <img class="img-fluid card-img-top p-2" style="max-height: 250px" :src="item.thumbnail" alt="img-placeholder">
            </div>
            <div class="text-start p-1 zindex-1 match-height">
                <p>
                    <a class="text-primary" :href="`${$page.props.auth.MAIN_URL}/product/single-product/${item.slug}`">{{ item?.title?.slice(0, 28) }}</a>
                </p>
                <h6 class="item-price">{{ item.price}} ৳</h6>
<!--                <p class="card-text  item-description" v-html="`${item?.description?.slice(0, 50)}...`"></p>-->
            </div>
        </div>
    </div>
</template>
