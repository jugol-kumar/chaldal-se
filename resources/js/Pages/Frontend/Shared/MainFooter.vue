<script setup>

    import {useDataStore} from "../../../Store/useDataStore";
    import {computed, onMounted} from "vue";
    const store = useDataStore();

    onMounted(()=>{
        store.setSetting('address');
        store.setSetting('phone');
        store.setSetting('email');
        store.setSetting('footer_logo');
        store.setSetting('app_details');
        store.footerData('customPages')
    })

    const pages = computed(() =>{
        return store.settingItem.find(item => item.key === 'customPages')?.val
    })

</script>



<template>
    <footer class="py-5 text-white">
            <div class="container-sm">
                <div class="row pb-5 footerTop gap-4 gap-lg-0">
                    <div class="col-sm-12 col-md-12 col-lg-4 col-12">
                        <div class="address-content">
                            <img :src="`${$page.props.auth.MAIN_URL}/storage/${store.settingItem.find(item => item.key === 'footer_logo')?.val}`" width="80" alt="">
                            <p class="mt-3 sm-footer-about" style="text-align: justify">
                                {{ store.settingItem.find(item => item.key === 'app_details')?.val }}
                            </p>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-lg-2 col-12" v-for="page in pages">
                        <div class="help-center-content">
                            <h1 class="font-weight-bold">{{ page.title }}</h1>
                            <ul class="navbar-nav">
                                <li class="nav-item d-flex align-items-center" v-for="item in  page.pages">
                                    <vue-feather type="arrow-right" size="10"/>
                                    <a :href="$page.props.auth.MAIN_URL+'/pages/'+item.slug" class="ms-1 nav-link">{{ item.title }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>


                    <div class="col-sm-12 col-md-12 col-lg-3 col-12">
                        <div class="address-content">
                            <h1 class="font-weight-bold">Address:</h1>
                            <p>{{ store.settingItem.find(item => item.key === 'address')?.val }}</p>
                            <p class="m-0 p-0"><span class="font-weight-bold">Phone:</span>{{ store.settingItem.find(item => item.key === 'phone')?.val }}</p>
                            <p class="m-0 p-0"> <span class="font-weight-bold">Email:</span> {{ store.settingItem.find(item => item.key === 'email')?.val }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
</template>
