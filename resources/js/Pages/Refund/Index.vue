<script setup>
import layout from "../../Shared/Layout.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import debounce from "lodash/debounce";
import {computed, onMounted, ref, watch} from 'vue';
import OffcanvasCom from "../../components/Offcanvas.vue";
import {Inertia} from "@inertiajs/inertia";
import {useSlug} from '../../Composables/useSlug.js'
import {Tooltip} from 'bootstrap'
import ImageUploader from "../../components/ImageUploader.vue";
import Swal from "sweetalert2";
import axios from "axios";
import {Offcanvas} from 'bootstrap';
import Pagination from "../../components/Pagination.vue";
import {useAction} from '../../Composables/useAction.js';
import Switch from "../../components/Switch.vue";
import Modal from "../../components/Modal.vue";

const {deleteItem, copyToClipboard} = useAction();

const slugTitle = useSlug();

const props = defineProps({
    refunds:[] | null,
    parent_categories:[] | null,
    main_url:String | null,
    errors:Object | null,
    filters: Object,
});

let createForm = useForm({
    title: null,
    parent_id:null,
    order_level:null,
    type:null,
    icon:null,
    banner:null,
});

let updateForm = useForm({
    id: null,
    title: null,
    parent_id:null,
    order_level:null,
    type:null,
    icon:null,
    banner:null,
});

const isLoading = ref(false);
const isShow = ref(null);

const addItem = () => {
    isShow.value = null;
    createForm.post(props.main_url,{
        preserveState: true,
        replace: true,
        onStart: res =>{
            console.log("res "+ res)
            isLoading.value = true;
        },
        onSuccess: page => {
            document.getElementById('addItemModal').$vb.modal.hide()
            isLoading.value = false;
            createForm.reset();
            $sToast.fire({
                icon: 'success',
                title: 'Signed in successfully'
            })
        },
        onError: errors => {
            isLoading.value = false;
            $toast.error("Validation Errors...")
        }
    });
}
const showItem = (id, type) =>{
    isShow.value = null;
    axios.get(`${props.main_url}/${id}`).then((res) =>{
        isShow.value = res.data;
        document.getElementById('showModal').$vb.modal.show()
    }).catch((err) =>{
        $toast.error(err.message)
    })
}
const updateItem = () =>{
    updateForm.post(props.main_url+"/update-with-files/"+updateForm.id, {
        preserveState: true,
        replace: true,
        onStart: res =>{
            console.log("res "+ res)
            // isLoading.value = true;
        },
        onSuccess: page => {
            document.getElementById('editItemModal').$vb.modal.hide()
            isLoading.value = false;
            createForm.reset();
            $sToast.fire({
                icon: 'success',
                title: 'Signed in successfully'
            })
        },
        onError: errors => {
            isLoading.value = false;
            $toast.error("Validation Errors...")
        }
    })
}

const updateToprefund = (updateIsTop, isTop) =>{
    axios.get(updateIsTop+'?isTop='+isTop).then((res) =>{
        $sToast.fire({
            icon: 'success',
            title: 'Update Successfully Done...'
        })
    }).catch((err) =>{
        console.log(err)
    })
}


const search = ref();
const perPage = ref();

watch([search, perPage], debounce(function ([val, val2]) {
    Inertia.get(props.main_url, { search: val, perPage: val2 }, { preserveState: true, replace: true });
}, 300));

</script>

<template>
    <layout>
        <div class="content-header row mb-1">
            <div class="col-12 d-flex align-items-center justify-content-between">
                <h2 class="float-start mb-0">refund List</h2>
                <button class="btn btn-sm btn-gradient-primary d-flex align-items-center"
                        type="button"
                        data-bs-toggle="modal"
                        data-bs-target="#addItemModal">
                    <vue-feather type="plus" size="15"/>
                    <span>Add New refund</span>
                </button>
            </div>
        </div>

        <section class="app-user-list">
            <!-- list and filter start -->
            <div class="card">
                <div class="card-datatable table-responsive pt-0 px-2">
                    <div class="d-flex align-items-center justify-content-between border-bottom">
                        <div class="select-search-area d-flex align-items-center">
                            <select class="form-select" v-model="perPage">
                                <option :value="undefined">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                        <div
                            class="d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap">
                            <div class="select-search-area">
                                <label>Search
                                    <input v-model="search"
                                           type="search"
                                           class="form-control"
                                           placeholder="What You Find ?"
                                           aria-controls="DataTables_Table_0">
                                </label>
                            </div>
                        </div>
                    </div>

                    <table class="refund-list-table table">
                        <thead class="table-light">
                        <tr class=null>
                            <th class="sorting">Id</th>
                            <th class="sorting">Order Id</th>
                            <th class="sorting">Region</th>
                            <th class="sorting">Prob Image</th>
                            <th class="sorting">About Problem</th>
                            <th class="sorting">Created At</th>
                            <th class="sorting">Status</th>
                            <th class="sorting"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="refund in refunds.data" :key="refund.id">
                            <td>#_{{ refund.id }}</td>
                            <td>#{{$page.props.auth.APP_NAME+"_"+ refund.order.id }}</td>
                            <td>{{ refund.title}}</td>
                            <td>
                                <img :src="refund.prob_img" alt="" style="object-fit: contain; width: 100%; height: 150px;">
                            </td>
                            <td>{{ refund.about }}</td>

                            <td>{{ refund.created_at }}</td>
                            <td>
                                {{ refund.status }}
                            </td>
                            <td>
                                <a :href="`/panel/single-order/${refund.order.id}`" class="text-primary cursor-pointer" target="_blank">
                                    <vue-feather type="eye" size="15"/>
                                </a>
                            </td>
                            <!--                            <td>
                                                            <button class="btn btn-primary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                                Action
                                                            </button>
                                                            <div class="dropdown-menu p-0" aria-labelledby="dropdownMenuButton">
                                                                <button class="dropdown-item d-flex align-items-center text-info w-100" type="button" @click="showItem(refund.id, 'edit')">
                                                                    <vue-feather type="edit" size="15"/>
                                                                    <span class="ms-1">Edit</span>
                                                                </button>
                                                                <button class="dropdown-item d-flex align-items-center text-danger w-100" type="button" @click="deleteItem(refund.id)">
                                                                    <vue-feather type="trash" size="15"/>
                                                                    <span class="ms-1">Delete</span>
                                                                </button>
                                                            </div>
                                                        </td>-->
                        </tr>
                        </tbody>
                    </table>
                    <Pagination :links="refunds.links" :from="refunds.from" :to="refunds.to" :total="refunds.total"/>
                </div>
            </div>
        </section>
<!--

        <div class="modal modal-slide-in fade" id="addItemModal" aria-hidden="true" v-vb-is:modal>
            <div class="modal-dialog">
                <div class="modal-content p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                    <div class="modal-header mb-1">
                        <h5 class="modal-title">
                            <span class="align-middle">Add new refund</span>
                        </h5>
                    </div>
                    <div class="modal-body flex-grow-1">
                        <form @submit.prevent="addItem">
                            <div class="mb-1">
                                <label class="form-label">Title</label>
                                <input class="form-control readonly"
                                       v-model="createForm.title"
                                       type="text" placeholder="e.g latest faction"/>
                                <span class="text-danger" v-if="props.errors.title">{{ props.errors.title }}</span>
                            </div>

                            <div class="mb-1">
                                <label>Order Number</label> <info title="Low number order level coming first"/>
                                <input type="number" v-model="createForm.order_level" class="form-control" placeholder="Low Number Height Priority">
                                <span class="text-danger" v-if="props.errors.order_level">{{ props.errors.order_level }}</span>
                            </div>

                            <div class="mb-1">
                                <label>Icon Image</label> <info title="Low number order level coming first"/>
                                <ImageUploader v-model="createForm.icon" />
                                <span class="text-danger" v-if="props.errors.icon">{{ props.errors.icon }}</span>
                            </div>

                            <div class="mb-1">
                                <label>Banner Image</label> <info title="Low number order level coming first"/>
                                <ImageUploader v-model="createForm.banner" />
                                <span class="text-danger" v-if="props.errors.banner">{{ props.errors.banner }}</span>
                            </div>

                            <div class="d-flex flex-wrap mb-0">
                                <button v-if="!isLoading" type="submit" class="btn btn-primary">Submit</button>
                                <button v-else class="btn btn-primary" type="button" disabled>
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Loading...
                                </button>
                                <button type="reset" class="btn btn-outline-secondary ms-1">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>-->
    </layout>
</template>
