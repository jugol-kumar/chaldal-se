<template>

    <CLayout>
        <div class="mt-5">
            <div class="table-responsive fs-md mb-4">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                    <tr class=null>
                        <th class="sorting">Id</th>
                        <th class="sorting">Amount</th>
                        <th class="sorting">Refund Status</th>
                        <th class="sorting">Payment Method</th>
                        <th class="sorting">Payment Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="refands in refunds" :key="refands.id">
                        <td>
                            #{{$page.props.auth.APP_NAME+"_"+ refands.id }}
                        </td>
                        <td>
                            {{ $showPrice(refands?.grand_total) }}
                        </td>
                        <td>
                            <span class="badge bg-light-primary text-capitalize">{{ refands.order_refand.status }}</span>
                        </td>
                        <td class="text-uppercase">{{ refands.payment_method }}</td>

                        <td>
                            <span class="badge bg-light-primary text-capitalize">{{ refands.payment_status }}</span>
                            <info v-if="refands.refands_refand" :title="`Your Refand Status Is ${refands.refands_refand.status}`"/>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </CLayout>


</template>
<script setup>
import CLayout from "./CLayout.vue";

const props = defineProps({
    refunds:[]|null,
})

</script>
