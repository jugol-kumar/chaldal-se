<script setup>
import layout from "../../Shared/Layout.vue";
import {ref, watch} from "vue";
import debounce from "lodash/debounce";
import ImageUploader from "../../components/ImageUploader.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";

const props = defineProps({
    products:[]|null,
    save_url:null,
})

const selectedProduct = ref(null);

const formData = useForm({
    title:null,
    banner:null,
    start_date:null,
    end_date:null,
})


const saveDells = () =>{
    Inertia.post(props.save_url, {dells:formData, products:selectedProduct.value}, {
        onSuccess: () => {
            formData.reset()
            $sToast.fire({
                icon:'success',
                title:"promo save successfully done."
            })
        }
    })
}


</script>

<template>
    <layout>
        <div class="content-header row mb-1">
            <div class="col-12 d-flex align-items-center justify-content-between">
                <h2 class="float-start mb-0">Flash Deals</h2>
                <a :href="`${this.$page.props.auth.ADMIN_URL}/promo`" class="btn btn-sm btn-gradient-primary d-flex align-items-center"
                   type="button">
                    <vue-feather type="arrow-left" size="15"/>
                    <span>Go Back</span>
                </a>
            </div>
        </div>


        <section class="app-user-list">
            <!-- list and filter start -->
            <div class="card">
                <div class="card-body">
                    <form @submit.prevent="saveDells" action="" class="col-md-8 m-auto">
                        <div class="mb-1">
                            <label>Title</label>
                            <input type="text" class="form-control" v-model="formData.title" placeholder="e.g Dells Title...">
                        </div>

                        <div class="mb-1">
                            <label>Banner</label>
                            <ImageUploader v-model="formData.banner" />
                        </div>

                        <div class="mb-1">
                            <label>Start Date</label>
                            <Datepicker v-model="formData.start_date" placeholder="e.g Dells Start Date..."/>
                        </div>

                        <div class="mb-1">
                            <label>End Date</label>
                            <Datepicker v-model="formData.end_date" placeholder="e.g Dells End Date..."/>
                        </div>

                        <div class="mb-1">
                            <label>Selected Products</label>
                            <vSelect v-model="selectedProduct" label="title" :reduce="item => item" :options="products" multiple placeholder="e.g Selected Some Products In This Promo"/>
                        </div>

                        <button class="btn btn-primary" type="submit">Save Dells</button>
                    </form>
                </div>
            </div>


            <div v-if="selectedProduct?.length" class="card">
                <div class="card-body">
                    <table class="category-list-table table">
                        <thead class="table-light">
                        <tr class=null>
                            <th class="sorting">Name</th>
                            <th class="sorting">Price</th>
                            <th class="sorting">Discount</th>
                            <th class="sorting">Discount Type</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr v-for="product in selectedProduct" :key="product.id">
                                <td>
                                    <img :src="product?.thumbnail" alt="" width="65">
                                    {{ product.title }}
                                </td>
                                <td>{{ $showPrice(product.price) }}</td>
                                <td>
                                    <input class="form-control" v-model="product.fdiscount" type="text">
                                </td>
                                <td>
                                    <select name="experience_type"
                                            v-model="product.fdicounttype"
                                            class="form-control selectpicker">
                                        <option disabled selected value="null"> e.g Select Type</option>
                                        <option value="percentage">Percentage %</option>
                                        <option value="flat">Flat ৳</option>
                                    </select>
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



