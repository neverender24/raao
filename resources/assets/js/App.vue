<template>
    <div class="container-scroller">
        <nav-bar></nav-bar>
        <div class="container-fluid page-body-wrapper">
            <side-bar :user="user"></side-bar>
            <div class="main-panel">
                <div class="content-wrapper">
                    <router-view></router-view>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import NavBar from "./components/NavBar.vue";
import SideBar from "./components/SideBar.vue";

export default {
    components: {
        NavBar,
        SideBar
    },
    data() {
        return {
            user: []
        };
    },
    mounted() {
        axios
            .post("get-user")
            .then(response => {
                this.user = response.data;
            })
            .catch(error => {
                location.reload();
            });
    }
};
</script>
