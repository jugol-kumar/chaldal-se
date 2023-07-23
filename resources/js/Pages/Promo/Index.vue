<script setup>
import layout from "../../Shared/Layout.vue";
import {ref, watch} from "vue";
import debounce from "lodash/debounce";
import {Inertia} from "@inertiajs/inertia";

const props = defineProps({
    promos: Object|[]|null,
    filters: Object,
    url: String,
});

let search = ref(props.filters.search);
let perPage = ref(props.filters.perPage);

watch([search, perPage], debounce(function ([val, val2]) {
    Inertia.get(props.url, {search: val, perPage: val2}, {preserveState: true, replace: true});
}, 300));

</script>

<template>
    <layout>
        <div class="content-header row mb-1">
            <div class="col-12 d-flex align-items-center justify-content-between">
                <h2 class="float-start mb-0">Flash Deals</h2>
                <a :href="`${this.$page.props.auth.ADMIN_URL}/create-promo`" class="btn btn-sm btn-gradient-primary d-flex align-items-center"
                        type="button">
                    <vue-feather type="plus" size="15"/>
                    <span>Add New Category</span>
                </a>
            </div>
        </div>


        <section class="app-user-list">
            <!-- list and filter start -->
            <div class="card">
                <div class="card-datatable table-responsive pt-0">
                    <div class="d-flex justify-content-between align-items-center header-actions mx-0 row mt-75">
                        <div class="col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start">
                            <div class="select-search-area">
                                <label>Show <select class="form-select" v-model="perPage">
                                    <option :value="undefined">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select> entries</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-8 ps-xl-75 ps-0">
                            <div
                                class="d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap">
                                <div class="select-search-area">
                                    <label>Search:<input v-model="search" type="search" class="form-control"
                                                         placeholder="Type here for search"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="user-list-table table">
                        <thead class="table-light">
                        <tr class="">
                            <th class="sorting">#_SL</th>
                            <th class="sorting">Title</th>
                            <th class="sorting">Banner</th>
                            <th class="sorting">Products</th>
                            <th class="sorting">Valid Date</th>
                            <th class="sorting">Status</th>
                            <th class="sorting">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(promo, key) in promos" :key="promo.title">
                            <td>
                                {{ key+1 }}
                            </td>
                            <td>{{ promo.title }}</td>
                            <td>
                                <img :src="promo.banner" alt="" width="120">
                            </td>
                            <td>
                                {{ promo.products_count }} Products
                            </td>
                            <td>
                                {{ $formattedTime(promo.start_date, 'lll') }} - {{ $formattedTime(promo.end_date, 'lll') }}
                            </td>
                            <td>
                                <span v-if="promo.status" class="badge badge-light-success">Active</span>
                                <span v-else class="badge badge-light-warning">Inactive</span>
                            </td>
                            <td>
                                <div class="btn-group dropup dropdown-icon-wrapper">
                                    <button type="button"
                                            class="btn dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                    </button>

                                    <div class="dropdown-menu">
                                        <span class="dropdown-item"
                                              v-if="promo.status">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down-left"><line x1="17" y1="7" x2="7" y2="17"></line><polyline points="17 17 7 17 7 7"></polyline></svg>
                                            <span class="ms-1">Inactive</span>
                                        </span>
                                        <span class="dropdown-item" v-else>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up-right"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                                            <span class="ms-1">Active</span>
                                        </span>

                                        <a :href="`${this.$page.props.auth.ADMIN_URL}/show-promo/${promo.id}`" class="dropdown-item">
                                            <vue-feather type="edit"/>
                                           <span class="ms-1">Edit</span>
                                        </a>
                                        <span class="dropdown-item">
                                            <vue-feather type="trash"/>
                                            <span class="ms-1">Delete</span>
                                        </span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- list and filter end -->
        </section>
    </layout>
</template>



