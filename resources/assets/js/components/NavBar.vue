<template>
    <div>
        <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div
                class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center"
            >
                <a class="navbar-brand brand-logo" href="index.html">
                    <img src="images/logo.svg" alt="logo">
                </a>
                <a class="navbar-brand brand-logo-mini" href="index.html">
                    <img src="images/logo-mini.svg" alt="logo">
                </a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center">
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item dropdown d-none d-xl-inline-block">
                        <a class="nav-link" href="#" data-toggle="dropdown" aria-expanded="false">
                            <!-- <span class="profile-text">Hello, Richard V.Welsh !</span> -->
                            <span class="fa fa-cog fa-2x"></span>
                        </a>
                        <div
                            class="dropdown-menu dropdown-menu-right navbar-dropdown"
                            aria-labelledby="UserDropdown"
                        >
                            <!-- <a
                                class="dropdown-item"
                                data-toggle="modal"
                                data-target="#changePasswordModal"
                            >Change Password</a> -->
                            <a href="#" v-on:click="logout()" class="dropdown-item">Sign Out</a>
                        </div>
                    </li>
                </ul>
                <button
                    class="navbar-toggler navbar-toggler-right d-lg-none align-self-center"
                    type="button"
                    data-toggle="offcanvas"
                >
                    <span class="fa fa-list"></span>
                </button>
            </div>
        </nav>
        <change-password></change-password>
    </div>
</template>

<script>
import ChangePassword from "../pages/users/changePassword";

export default {
    components: {
        ChangePassword
    },
    data() {
        return {
            user: []
        };
    },
    methods: {
        logout() {
            axios
                .post("logout")
                .then(response => {})
                .catch(error => {
                    location.reload();
                });
        }
    }
};
</script>
