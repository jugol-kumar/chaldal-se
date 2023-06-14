<script setup>
import layout from "../Shared/Layout.vue";
import Pagination from "../../../components/Pagination.vue";
import TreeView from "../../../components/TreeView.vue";
import Products from '../Pages/Products.vue'
import {ref, watch} from 'vue'
import {Inertia} from "@inertiajs/inertia";
import debounce from "lodash/debounce";

const props = defineProps({
    products:[]|null,
    filters: Object,
})

const listView = ref(false)
const showList = (e) => {
    listView.value = true;
    var $gridCont = document.getElementById('productView');
    $gridCont.classList.add('list-view');
}
const gridList = (e) => {
    listView.value = false;
    var $gridCont = document.getElementById('productView');
    $gridCont.classList.remove('list-view');
}

// let search = ref(props.filters?.search);
// let orderBy = ref(props.filters?.perPage);
//
// watch([search, orderBy], debounce(function ([val, val2]) {
//     if (val.length > 5){
//         Inertia.get(props.main_url, { search: val, orderBy: val2}, { preserveState: true, replace: true });
//     }
// }, 300));




</script>

<template>
    <layout :search="props.filters">
        <section class="app-user-list my-5 sm-mt">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span>{{ products.total }} results found <span v-if="props?.filters?.search"> search for</span> <strong > {{ props?.filters?.search }}</strong> </span>
                    </div>


<!--                    <div class="card">
                        <div class="card-body p-0 position-relative d-flex align-items-center">
                            <div class="col-md-9 position-relative">
                                <input type="text" class="form-control p-1 border-0" v-model="search" placeholder="what your looking for ...">
                                <span class="position-absolute d-none d-lg-block" style="right: 20px;top: 16px;color: #c7c7cf;">
                                    <vue-feather type="search" size="18"/>
                                </span>
                            </div>

                            <div class="col-md-3">
                                <select v-model="orderBy" class="form-control border-0 shadow-none">
                                    <option :value="undefined">Sorting</option>
                                    <option value="price_low_high">Price Low To High</option>
                                    <option value="price_high_low">Price High To Low</option>
                                    <option value="newest_first">Newest First</option>
                                    <option value="oldest_first">Oldest First</option>
                                </select>
                            </div>
                        </div>
                    </div>-->



                    <div class="container-sm grid-container" id="productView">
                        <div class="row match-height">
                            <div class="col-lg-3 mb-4 col-md-3 product-item" v-for="item in products.data" :key="'item-'+item.id">
                                <Products :product="item.product"/>
                            </div>

                            <Pagination :links="products.links" :from="products.from" :to="products.to" :total="products.total" notShowNumber/>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </layout>
</template>

<style lang="scss">
.card {
    margin-bottom: 1rem;
}

.list-view {
    .row {
        > [class*='col-'] {
            max-width: 100%;
            flex: 0 0 100%;
        }
    }
    .card {
        @media (max-width: 575.98px) {
            flex-direction: column;
        }
        flex-direction: row;
        > .card-img-top {
            width: auto;
        }

        .card-body {
            display: inline-block;
        }
    }
}

.ecommerce-card{
    box-shadow: 0 0px 14px -1px rgb(34 41 47 / 32%) !important;
    margin-top: 18px !important;
    transition: 0.3s all ease;
    flex: 1 1 auto;
    overflow: hidden;
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
