<script setup>
import {useForm} from "@inertiajs/inertia-vue3";
import {ref} from 'vue'
import ProductCard from "../Modules/ProductCard.vue";
import {useWishListStore} from "../../../Store/useWishListStore";
import {useCartStore} from "../../../Store/useCartStore";
import {Inertia} from "@inertiajs/inertia";
import CustomerLayout from "./CustomerLayout.vue";
import CLayout from "./CLayout";
import Pagination from "../../../components/Pagination.vue";
import Modal from "../../../components/Modal.vue";
import ImageUploader from "../../../components/ImageUploader.vue";
const store = useWishListStore();
const cartStore = useCartStore();
const props = defineProps({
    orders:[]|null,
    success:String|null,
})
const wishListToCart= (item) =>{
    cartStore.addToCart(item);
    store.removeFromWishList(item);
    $toast.success("Moved Wishlist To Cart...")
}




const formData = useForm({
    name:null,
    email:null,
    phone:null,
})
const authData = useForm({
    current:null,
    new:null,
    confirmed:null,
})
const isLoading = ref(false)
const updateCustomerProfile = () => {
    formData.post('/update-user-profile', {
        preserveState: true,
        replace: true,
        onStart: res => {
            isLoading.value = true;
        },
        onSuccess: page => {
            isLoading.value = false;
            formData.reset();
            $sToast.fire({
                icon: 'success',
                title: page.props.info.message,
            })
        },
        onError: errors => {
            document.getElementById('actionModal').$vb.modal.hide()
            isLoading.value = false;
            $toast.error("Validation Errors...")
        }
    })
}
const passIsLoad = ref(false);
const updateCustomerPassword = () =>{
    authData.post('/update-user-credentials', {
        preserveState: true, replace: true,
        onStart: res => passIsLoad.value = true,
        onSuccess: page => {
            passIsLoad.value = false;
            formData.reset();
        },
        onError: errors => $toast.error(errors)
    })
}

const refadForm = useForm({
    orderId:null,
    region:null,
    image:null,
    message:null,
})
const refenadOrfer = (id) =>{
    refadForm.orderId = id;
    document.getElementById('referand_order').$vb.modal.show()
}
const submitRefand = () =>{
    refadForm.post('/save-refand-request', {
        preserveState: true,
        replace: true,
        onSuccess: page => {
            isLoading.value = false;
            refadForm.reset();
            $sToast.fire({
                icon: 'success',
                title: page.props.info.message,
            })
            document.getElementById('referand_order').$vb.modal.hide()
        },
        onError: errors => {
            document.getElementById('referand_order').$vb.modal.hide()
            isLoading.value = false;
            $toast.error("Validation Errors...")
        }
    })
}



</script>

<template>
    <CLayout>
        <div class="mt-5">
            <div class="table-responsive fs-md mb-4">
                <table class="table table-borderless table-striped table-hover">
                    <thead class="table-light">
                    <tr class=null>
                        <th class="sorting">Id</th>
                        <th class="sorting">price</th>
                        <th class="sorting">Order Status</th>
                        <th class="sorting">Payment Method</th>
                        <th class="sorting">Payment Status</th>
                        <th class="sorting"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="order in orders.data" :key="order.id">
                        <td>
                            #{{$page.props.auth.APP_NAME+"_"+ order.id }}
                        </td>
                        <td>
                            {{ $showPrice(order?.grand_total) }}
                        </td>
                        <td>
                            <span class="badge bg-light-primary text-capitalize">{{ order.order_status }}</span>
                        </td>
                        <td class="text-uppercase">{{ order.payment_method }}</td>

                        <td>
                            <span class="badge bg-light-primary text-capitalize">{{ order.payment_status }}</span>
                            <info v-if="order.order_refand" :title="`Your Refand Status Is ${order.order_refand.status}`"/>
                        </td>
                        <td class="order-action d-flex align-items-center">

                            <a :href="$page.props.auth.MAIN_URL+'/single-order/'+order.id" class="btn btn-icon btn-gradient-primary btn-sm me-1"
                               v-c-tooltip="'Show This Invoice.'">
                                <vue-feather type="eye" size="15"/>
                            </a>
                            <a :href="$page.props.auth.MAIN_URL+'/print-invoice/'+order.id" target="_blank"
                               class="btn  btn-icon btn-gradient-info btn-sm"
                               v-c-tooltip="'Print This Invoice'">
                                <vue-feather type="printer" size="15"/>
                            </a>

                            <a v-if="order.payment_status === 'paid' && order.order_refand === null" href="javascript:void(0)"
                               @click="refenadOrfer(order.id)"
                               class="btn  btn-icon btn-gradient-danger btn-sm ms-1">
                                <div class="d-flex align-items-center">
                                    <vue-feather type="corner-up-left" size="15"/>
                                    <span style="margin-left:2px;">Refand</span>
                                </div>
                            </a>

                            <a v-else-if="order.payment_status !== 'paid' && order.order_status === 'pending'"
                               :href="$page.props.auth.MAIN_URL+'/customer/cancel-order/'+order.id"
                               class="btn  btn-icon btn-gradient-danger btn-sm ms-1">
                                <div class="d-flex align-items-center">
                                    <vue-feather type="x" size="15"/>
                                    <span style="margin-left:2px;">Cancel</span>
                                </div>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <Pagination :links="orders.links" :from="orders.from" :to="orders.to" :total="orders.total"/>
            </div>
        </div>
    </CLayout>



    <Modal id="referand_order" title="Refand order" v-vb-is:modal size="md">
        <div class="modal-body flex-grow-1">
            <form @submit.prevent="submitRefand">
                <div class="mb-1">
                    <label>Region</label>
                    <input v-model="refadForm.region" class="form-control" placeholder="e.g Order Region">
                </div>

                <div class="mb-1">
                    <label>Icon Image</label>
                    <ImageUploader v-model="refadForm.image"/>
                </div>

                <div class="mb-1">
                    <label>About Refand</label>
                    <textarea v-model="refadForm.message" class="form-control" placeholder="e.g About Your Problem" rows="8"></textarea>

                </div>

                <div class="d-flex flex-wrap mb-0">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="reset" class="btn btn-outline-secondary ms-1">Reset</button>
                </div>
            </form>
        </div>
    </Modal>

</template>



<style lang="sass" scoped>
// Image input
.image-input
    text-aling: center
    input
        display: none
    label
        display: block
        color: #FFF
        background: #000
        padding: .3rem .6rem
        font-size: 115%
        cursor: pointer
        i
            font-size: 125%
            margin-right: .3rem
        &:hover
            i
                animation: shake .35s
    img
        max-width: 175px
        display: none
    span
        display: none
        text-align: center
        cursor: pointer

// animation keyframes
@keyframes shake
    0%
        transform: rotate(0deg)
    25%
        transform: rotate(10deg)
    50%
        transform: rotate(0deg)
    75%
        transform: rotate(-10deg)
    100%
        transform: rotate(0deg)

// other styles
body
    display: flex
    justify-content: center
    align-items: center
    height: 350px
    font-family: calibri

.order-action
    a
        font-size: 15px

</style>
