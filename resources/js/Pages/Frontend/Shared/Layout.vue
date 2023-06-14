<script setup>
import TopHeader from "../../Frontend/Shared/TopHeader.vue"
import Header from "../../Frontend/Shared/Header.vue"
import HeaderMenu from "../../Frontend/Shared/HeaderMenu.vue"
import 'vue3-carousel/dist/carousel.css'
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'
import {computed, onMounted, ref, watch} from 'vue'
import CardCarosel from "../../../components/CardCarousel.vue";
import TreeView from "../../../components/TreeView.vue";
import ProductCarousel from "../Modules/ProductCarousel.vue";
import AdsSection from "../Modules/AdsSection.vue";
import MainFooter from "./MainFooter.vue";
import PreLoader from "../../../components/PreLoader.vue";
import {useDataStore} from "../../../Store/useDataStore";
import debounce from "lodash/debounce";
import {Inertia} from "@inertiajs/inertia";
import {useCartStore} from "../../../Store/useCartStore";
import QuantityButton from "../../../components/QuantityButton.vue";
import UserInfo from "../Modules/UserInfo.vue";
import UserLoginForm from "../Pages/UserLoginForm.vue";
import LoginUserInfo from "../Pages/LoginUserInfo.vue";
import UserImageWithUpload from '../Customer/UserImageWithUpload.vue'

const cartStore = useCartStore();
const store = useDataStore();

onMounted(()=>{
    cartStore.initCart();
    store.setSetting('facebook_profile');
    store.setSetting('instagram_profile');
    store.setSetting('twitter_profile');
    store.setSetting('linkedin_profile');
    store.footerData('customPages')
})
const props = defineProps({
    parentCategories:[]|null,
    featuredCategories:[]|null,
    topCategories:[]|null,
    featuredBrands:[]|null,
    search:Object,
})

const showSidebar = ref(true)
const toggleSidebar = () => showSidebar.value = !showSidebar.value;

const sliders = ref({pagination:false, navigation:false,})
const show = ref(true)
onMounted(()=>{
    setTimeout(()=>{
        show.value = false
    }, 1000)
    store.setSetting('header_logo');

    store.initTopCategories()
})

const search = ref(props?.search?.search)
watch([search], debounce(function ([val]) {
    if (val.length > 5 || val.length <= 0) {
        Inertia.get('/products', {search: val}, {preserveState: true, replace: true});
    }
}, 300));
const clearSearch = () => {
    search.value = null;
    Inertia.get('/products', {search: null}, {preserveState: true, replace: true});
}


const openSubMenuUl = ref(null);
const openChildMenuUl = ref(null);
const openSubMenu = (node) => openSubMenuUl.value = node.id;
const openChildMenu = (node) => openChildMenuUl.value = node.id;

const pages = computed(() =>{
    return store.settingItem.find(item => item.key === 'customPages')?.val
})


</script>
<template>
    <PreLoader v-if="show"/>
<!--    <TopHeader/>-->
<!--    <Header/>
    <HeaderMenu :categories="props.parentCategories"/>-->


    <nav class="navbar navbar-expand-md fixed-top bg-primary">
        <div class="container-fluid">
            <div class="d-flex alitems-center justify-content-between sm-nav-width">
                <a class="btn d-lg-none d-block" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
                    <vue-feather class="text-black" type="menu"/>
                </a>
                <button class="btn d-none d-lg-block" @click="toggleSidebar">
                    <vue-feather class="text-black" type="menu"/>
                </button>
                <a href="/">
                    <img :src="`${$page.props.auth.MAIN_URL}/storage/${store.settingItem.find(item => item.key === 'header_logo')?.val}`" width="85" alt="">
                </a>
            </div>

            <div class="header-search position-relative">
                <input type="text" class="form-control p-1"  v-model="search" placeholder="what are you looking for...?">
                <button v-if="props?.search?.search" class="position-absolute clear-search" @click="clearSearch">
                    <vue-feather type="x"/>
                </button>
            </div>

            <div class="d-flex align-items-center justify-content-between icon-sm-style" style="gap:10px;">
                <a class="link-item" href="https://worldvectorlogo.com/downloaded/twitter-3"
                   v-if="store.settingItem.find(item => item.key === 'facebook_profile')?.val" target="_blank">
                    <vue-feather type="facebook" size="25"/>
                </a>
                <a class="link-item" href="https:://my.ctpbd.com" v-if="store.settingItem.find(item => item.key === 'instagram_profile')?.val" target="_blank">
                    <vue-feather type="instagram" size="25"/>
                </a>
                <a class="link-item" href="" v-if="store.settingItem.find(item => item.key === 'twitter_profile')?.val" target="_blank">
                    <vue-feather type="twitter" size="25"/>
                </a>
                <a class="link-item" :href="store.settingItem.find(item => item.key === 'linkedin_profile')?.val"
                   v-if="store.settingItem.find(item => item.key === 'linkedin_profile')?.val" target="_blank">
                    <vue-feather type="linkedin" size="25"/>
                </a>
            </div>

            <div class="d-lg-block d-none text-white">
                <UserInfo v-if="$page.props.auth?.user"/>
                <a href="/customer/login" v-else class="btn border text-white fs-4 fw-bold">Sign in</a>
            </div>
        </div>
    </nav>

    <nav class="navbar navbar-expand-md fixed-bottom bg-primary d-lg-none">
        <div class="container-fluid">
            <a class="btn btn-sm d-lg-none bg-white p-5-px"
               data-bs-toggle="offcanvas"
               href="#userProfile" role="button" aria-controls="offcanvasExample">
                <vue-feather class="text-black" type="user"/>
            </a>

            <a href="/" class="bottom-nav-logo">
                <img class="w-100 h-100 p-5-px" :src="`${$page.props.auth.MAIN_URL}/storage/${store.settingItem.find(item => item.key === 'header_logo')?.val}`"
                    alt="">
            </a>

            <a class="btn btn-sm d-lg-none  bg-white p-5-px position-relative"
               data-bs-toggle="modal"
               data-bs-target="#addItemModal">
                <vue-feather class="text-black" type="shopping-bag"/>

                <span class="position-absolute small-cart-count">
                    {{ cartStore.getCartLength }}
                </span>
            </a>
        </div>
    </nav>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="userProfile" aria-labelledby="offcanvasExampleLabel">
        <button type="button" class="sm-canvas-close" data-bs-dismiss="offcanvas" aria-label="Close">
            <vue-feather type="x" size="12"/>
        </button>
        <div class="offcanvas-body d-flex flex-column" :class="$page.props.auth?.user ? 'justify-content-between' : 'justify-content-center'">
<!--            <div v-if="$page.props.auth?.user">

                <div class="d-flex flex-column align-items-center justify-content-center gap-1">
                    <div class="d-flex align-items-center flex-column">
                        <div class="avatar-upload">
                            <div class="avatar-edit">
                                <input type='file' @input="uploadImage" id="imageUpload" accept=".png, .jpg, .jpeg" />
                                <label for="imageUpload" class="d-flex align-items-center justify-content-center">
                                    <vue-feather type="edit" size="10"/>
                                </label>
                            </div>
                            <div class="avatar-preview">
                                <div v-if="profileImage" id="imagePreview" :style="{ 'background-image': 'url(' + profileImage + ')' }">
                                </div>
                                <div v-else id="imagePreview2" :style="{ 'background-image': 'url(' + $page.props.auth.user.avatar + ')' }">
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-sm my-1" v-if="profileImage !== null" @click="updateProfileImage">upload</button>
                    </div>
                    <div>
                        <strong class="m-0">{{ $page.props.auth.user?.name }}</strong>
                        <p class="m-0">{{ $page.props.auth.user?.email }}</p>
                        <p class="m-0">{{ $page.props.auth.user?.phone }}</p>
                    </div>
                </div>

                <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a href="/" class="nav-link" >Home</a>
                    <a href="/customer/profile" class="nav-link">Profile</a>
                    <a href="/customer/orders" class="nav-link" >Orders</a>
                    <a :href="$page.props.auth.MAIN_URL+'/logout'" class="nav-link">Logout</a>
                </div>

            </div>-->
            <aside class="col-lg-4 pt-4 pt-lg-0 pe-xl-5"  v-if="$page.props.auth?.user">
                <div class="bg-white rounded-3 pt-1 mb-5 mb-lg-0 h-100" style="border:1px solid #ebebeb">
                    <div class="d-flex flex-column align-items-center justify-content-center gap-1">
                        <UserImageWithUpload/>
                        <div>
                            <strong class="m-0">{{ $page.props.auth.user?.name }}</strong>
                            <p class="m-0">{{ $page.props.auth.user?.email }}</p>
                            <p class="m-0">{{ $page.props.auth.user?.phone }}</p>
                        </div>
                    </div>
                    <div class="d-block collapse" id="account-menu">
                        <div class="bg-secondary px-4 py-3">
                            <h3 class="fs-sm mb-0 text-muted">Dashboard</h3>
                        </div>
                        <ul class="list-unstyled mb-0">
                            <li class="border-bottom mb-0">
                                <a class="nav-link-style d-flex align-items-center px-4 py-1 active" href="/dashboard">
                                    <vue-feather class="me-2" type="shopping-bag"/>
                                    Orders
                                    <span class="fs-sm text-primary ms-auto">
                                        <vue-feather type="arrow-right"/>
                                    </span>
                                </a>
                            </li>

                            <li class="border-bottom mb-0">
                                <a class="nav-link-style d-flex align-items-center px-4 py-1 active" href="/customer/profile">
                                    <vue-feather class="me-2" type="user"/>
                                    Profile info
                                    <span class="fs-sm text-primary ms-auto">
                                        <vue-feather type="arrow-right"/>
                                    </span>
                                </a>
                            </li>
                            <li class="border-bottom mb-0">
                                <a class="nav-link-style d-flex align-items-center px-4 py-1 active" href="/customer/address">
                                    <vue-feather class="me-2" type="radio"/>
                                    Address
                                    <span class="fs-sm text-primary ms-auto">
                                        <vue-feather type="arrow-right"/>
                                    </span>
                                </a>
                            </li>
                            <li class="border-bottom mb-0">
                                <a class="nav-link-style d-flex align-items-center px-4 py-1 active" href="/logout">
                                    <vue-feather class="me-2" type="log-out"/>
                                    Logout
                                    <span class="fs-sm text-primary ms-auto">
                                        <vue-feather type="arrow-right"/>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </aside>

            <div class="d-flex align-items-center justify-content-center flex-column" v-else>
                <a href="/customer/login" class="btn btn-primary">Sign In</a>
            </div>

<!--            <UserLoginForm v-else/>-->
        </div>
    </div>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <button type="button" class="sm-canvas-close" data-bs-dismiss="offcanvas" aria-label="Close">
            <vue-feather type="x" size="12"/>
        </button>
        <div class="offcanvas-body d-flex flex-column justify-content-between">
            <nav class="sidebar">
                <div class="sidebar-header">
                    <h3>Category</h3>
                </div>
                <ul style="margin-bottom:10rem">
                    <li class="initially_selected selected"
                        v-for="node in store.getTopCategories" :key="node.title"
                        @click="openSubMenu(node)"
                        :class="openSubMenuUl === node.id && node?.sub_categories.length > 0 ? 'first_lavel_border_left' : ''"
                    >
                        <a class="parent_a" :href="node?.sub_categories.length > 0 ? 'javascript:void(0)' : `${$page.props.auth.MAIN_URL}/category/${node.slug}`">
                            <img :src="node.icon" style="max-width: 30px; max-height: 30px; "
                                 class="img-fluid"  alt="avatar">
                            {{ node.title }}
                            <vue-feather :type="openSubMenuUl === node.id ? 'chevron-down' : 'chevron-right'" size="13" v-if="node?.sub_categories.length > 0"/>
                        </a>
                        <ul class="sub_class" :class="openSubMenuUl === node.id ? '' : 'd-none'" v-if="node?.sub_categories.length > 0">
                            <li v-for="item in node.sub_categories" :key="item?.id"
                                @click="openChildMenu(item)"
                                :class="openChildMenuUl === item.id && item.child_categories.length > 0 ? 'first_lavel_border_left' : ''"
                                class="sub_category_active active_color">
                                <a :href="item?.child_categories.length > 0 ? 'javascript:void(0)' : `${$page.props.auth.MAIN_URL}/sub-category/${item.slug}`">
                                    {{ item?.title }}
                                    <vue-feather :type="openChildMenuUl === item.id ? 'chevron-down' : 'chevron-right'" size="13" v-if="item.child_categories.length > 0"/>
                                </a>
                                <ul class="third_level" :class="openChildMenuUl === item.id ? '' : 'd-none'" v-if="item.child_categories.length > 0">
                                    <li class="level_three_active active_color"
                                        v-for="cItem in item.child_categories" :key="cItem?.id">
                                        <a :href="`${$page.props.auth.MAIN_URL}/child-category/${cItem.slug}`">
                                            {{ cItem.title }}
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <div class="row main-container m-auto" style="width:100%; min-height: 100vh">
        <div v-if="showSidebar" class="col-md-2 main-sidebar mt-3 ps-0">
            <nav class="sidebar">
                <div class="sidebar-header">
                    <h3>Category</h3>
                </div>
                <ul style="margin-bottom:10rem">
                    <li class="initially_selected selected"
                        v-for="node in store.getTopCategories" :key="node.title"
                        @click="openSubMenu(node)"
                        :class="openSubMenuUl === node.id && node?.sub_categories.length > 0 ? 'first_lavel_border_left' : ''"
                    >
                        <a class="parent_a" :href="node?.sub_categories.length > 0 ? 'javascript:void(0)' : `${$page.props.auth.MAIN_URL}/category/${node.slug}`">
                            <img :src="node.icon" style="max-width: 30px; max-height: 30px; "
                                 class="img-fluid"  alt="avatar">
                            {{ node.title }}
                            <vue-feather :type="openSubMenuUl === node.id ? 'chevron-down' : 'chevron-right'" size="13" v-if="node?.sub_categories.length > 0"/>
                        </a>
                        <ul class="sub_class" :class="openSubMenuUl === node.id ? '' : 'd-none'" v-if="node?.sub_categories.length > 0">
                            <li v-for="item in node.sub_categories" :key="item?.id"
                                @click="openChildMenu(item)"
                                :class="openChildMenuUl === item.id && item.child_categories.length > 0 ? 'first_lavel_border_left' : ''"
                                class="sub_category_active active_color">
                                <a :href="item?.child_categories.length > 0 ? 'javascript:void(0)' : `${$page.props.auth.MAIN_URL}/sub-category/${item.slug}`">
                                    {{ item?.title }}
                                    <vue-feather :type="openChildMenuUl === item.id ? 'chevron-down' : 'chevron-right'" size="13" v-if="item.child_categories.length > 0"/>
                                </a>
                                <ul class="third_level" :class="openChildMenuUl === item.id ? '' : 'd-none'" v-if="item.child_categories.length > 0">
                                    <li class="level_three_active active_color"
                                        v-for="cItem in item.child_categories" :key="cItem?.id">
                                        <a :href="`${$page.props.auth.MAIN_URL}/child-category/${cItem.slug}`">
                                            {{ cItem.title }}
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>





        <div :class="showSidebar ? 'col-md-10' : 'col-md-12'" class="sm-mt-15-rm" style="overflow: hidden">
            <slot/>

            <div style="height:60px;"  class="bg-primary text-black d-flex align-items-center justify-content-between p-3 sm-footer-style">
                <div class="text-white">
                    &copy; All Right Rechiverd
                </div>

                <div class="text-white d-flex gap-1 footer-link ">
                    <a v-for="page in pages" class="text-white" href="#">{{ page.title }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <a
        data-bs-toggle="modal"
        data-bs-target="#addItemModal"
        class="cart-details-button" style="right: 0">
        <div class="text-white">
            <vue-feather type="shopping-bag"/>
            <p>{{ cartStore.getCartLength }} Items</p>
        </div>
        <p class="cart-price-sidebar">{{ $showPrice(cartStore.getCartTotalPrice) }}</p>
    </a>



    <div class="modal modal-slide-in fade sm-cart-modal" id="addItemModal" aria-hidden="true" v-vb-is:modal>
        <div class="modal-dialog sm-cart-width">
            <div class="modal-content p-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                <div class="modal-header mb-1">
                    <h5 class="modal-title d-flex justify-content-between">
                        <div class="align-middle">{{ cartStore.getCartLength }} items in cart </div>
<!--                        <div class="badge badge-light-danger cursor-pointer ms-1" @click="cartStore.clearCart()" v-c-tooltip="'Clear This Cart All Items...'">
                            x clear
                        </div>-->
                    </h5>
                </div>
                <div class="modal-body d-flex flex-column justify-content-between flex-grow-1">
                    <div class="scrolling-cart">
                        <div class="card details-cart-item sm-pr-2" v-if="cartStore.getCartItems.length > 0" v-for="item in cartStore.getCartItems" :key="item.id" >
                            <div class="card-body d-flex align-items-center justify-content-between sm-pl-0">
                                <div class="d-flex col-8">
                                    <img class="me-1" :src="item.thumbnail" alt="" width="100">
                                    <div class="d-flex flex-column">
                                        <h3 class="text-capitalize text-primary fs-6">{{ item.title.slice(0,20) }}</h3>
                                        <div>
                                            <span>{{ item.price }} <vue-feather type="x" size="12"/> {{ item.quantity }} = {{ $showPrice(item.price * item.quantity) }} </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-end">
                                    <vue-feather type="x" size="15" class="cursor-pointer remove-form-cart-button" v-c-tooltip="'Remove Form Cart...'" @click="cartStore.removeFromCart(item)"/>
                                    <QuantityButton :productId="item.id" :maxQty="item.qty" class="mt-1"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="border rounded p-1 mb-3">
                            <h6>Price Details</h6>
                            <dl class="row mb-0">
                                <dt class="col-6 fw-normal">Bag Total</dt>
                                <dd class="col-6 text-end">{{ $showPrice(cartStore.getCartTotalPrice) }}</dd>
                            </dl>
                        </div>
                        <div class="d-grid mb-3">
                            <a :href="$page.props.auth.MAIN_URL+'/checkout'" v-if="cartStore.getCartLength > 0" class="btn btn-primary btn-next">Place Order here</a>
                            <button v-else disabled="true" class="btn btn-primary btn-next" v-c-tooltip="'Add Product On Your Cart Before Plase Order'">Place Order here</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<!--
    <div class="bg-primary">
        <main-footer/>
    </div>-->




    <button class="btn btn-primary btn-icon scroll-top" type="button">
        <vue-feather type="arrow-up"/>
    </button>
    <!-- END: Footer-->

</template>
<style scoped>
.scrolling-cart{
    max-height: 75vh;
    overflow-y: scroll;
    overflow-x: hidden;
}
.remove-form-cart-button{
    margin-right: 13px;
    background: var(--bs-primary);
    padding: 5px;
    border-radius: 7px;
    color: white;
}
.cat-image .img-sec{
    width: 26px;
    height: 26px;
}
.cat-image .img-sec img{
    width:inherit;
    height: inherit;
}
.cat-image a{
    font-size: 12px !important;
    font-weight: 500 !important;
    color: black !important;
}
.cat-group .sub-group{
    border-left: 1px solid #e9e7e7 !important;
    border-radius: 0 !important;
}
.cat-image:hover,
.cat-group .sub-group .sum-item:hover{
    background: linear-gradient(306deg, #ffd3b9, transparent);
}
.cat-group .sub-group .sum-item{
    border: none !important;
    line-height: 0 !important;
    /*padding: 12px 37px !important;*/
    color: black !important;
}
.child-group {
    /*margin: 15px !important;*/
    background: white !important;
}

.child-group .child-list-item{
    border:none !important;
}
.clear-search{
    top: 12px;
    background: none;
    border: none;
    right: 13px;
    color: #a17eb7;
}
.link-item{
    border: 1px solid white;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 6px;
    color: white;
    border-radius: 5px;
    transition: 0.3s all ease;
}
.link-item:hover{
    background: white;
    color: var(--bs-primary);
}
.footer-link a:hover{
    text-decoration: underline;
}
</style>
<style lang="scss" scoped>
.avatar-upload {
    position: relative;
    max-width: 205px;
    //margin: 50px auto;
    .avatar-edit {
        position: absolute;
        right: 12px;
        z-index: 1;
        top: 10px;
        input {
            display: none;
            + label {
                display: inline-block;
                width: 34px;
                height: 34px;
                margin-bottom: 0;
                border-radius: 100%;
                background: #FFFFFF;
                border: 1px solid transparent;
                box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
                cursor: pointer;
                font-weight: normal;
                transition: all .2s ease-in-out;
                &:hover {
                    background: #f1f1f1;
                    border-color: #d6d6d6;
                }
                &:after {
                    color: #757575;
                    position: absolute;
                    top: 10px;
                    left: 0;
                    right: 0;
                    text-align: center;
                    margin: auto;
                }
            }
        }
    }
    .avatar-preview {
        width: 150px;
        height: 150px;
        position: relative;
        border-radius: 100%;
        border: 6px solid var(--bs-primary);
        box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
        > div {
            width: 100%;
            height: 100%;
            border-radius: 100%;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }
    }
}
</style>
