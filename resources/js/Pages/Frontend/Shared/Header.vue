
<script setup>
    import DarkToggle from "../../../components/DarkToggle.vue";
    import CartItems from "../Modules/CartItems.vue";
    import WishList from "../Modules/WishList.vue";
    import UserInfo from "../Modules/UserInfo.vue";
    import {useDataStore} from "../../../Store/useDataStore";
    import {onMounted} from "vue";
    const store = useDataStore();

    onMounted(()=>{
        store.setSetting('header_logo');
    })
</script>

<template>
    <nav class="header-navbar navbar navbar-expand-lg align-items-center navbar-light">
        <div class="container-sm">
            <div class="navbar-container d-flex align-items-center justify-content-between">
                <a href="/">
                    <img :src="`${$page.props.auth.MAIN_URL}/storage/${store.settingItem.find(item => item.key === 'header_logo')?.val}`" width="40" alt="">
                </a>
         <!--       <div class="card m-0 shadow-none">
                    <div class="card-body p-0 d-flex">
                        <select class="form-control border-0" name="" id="" >
                            <option selected>Category</option>
                        </select>
                        <input type="text" class="form-control border-0" placeholder="Find your needs">
                    </div>
                </div>-->

                <div>
                    <ul class="nav navbar-nav align-items-center ms-auto">
<!--                        <li class="nav-item d-none d-lg-block">
                            <dark-toggle />
                        </li>-->

                        <li class="nav-item dropdown dropdown-cart me-25">
                            <CartItems/>
                        </li>
                        <li class="nav-item dropdown dropdown-cart me-25">
                            <WishList/>
                        </li>

                        <li class="nav-item dropdown dropdown-user" v-if="$page.props.auth?.user">
                            <user-info/>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</template>


