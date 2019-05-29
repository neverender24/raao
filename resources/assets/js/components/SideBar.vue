<template>
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item nav-profile">
            <div class="nav-link">
              <div class="user-wrapper">
                <div class="profile-image">
                  <!-- <img src="images/faces/face1.jpg" alt="profile image"> -->
                  <span class="fa fa-user fa-3x"></span>
                </div>
                <div class="text-wrapper">
                  <p class="profile-name">{{ user.FullName }}</p>
                  <div>
                    <small class="designation text-muted">{{ user.UserType }}</small>
                    <span class="status-indicator online"></span>
                  </div>
                </div>
              </div>
              <!-- <button class="btn btn-success btn-block">New Project
                <i class="fa fa-plus"></i>
              </button> -->
            </div>
          </li>
          <li class="nav-item">
            <router-link to="dashboard" class="nav-link">
              <i class="menu-icon fa fa-home"></i>
              <span class="menu-title">Dashboard</span>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="raaohs" class="nav-link">
              <i class="menu-icon fa fa-list"></i>
              <span class="menu-title">RAAOH</span>
            </router-link>
          </li>
        </ul>
      </nav>
</template>

<script>
export default {
  props: ["user"]
}
</script>

<style>
a:link{
    color: #000
}
</style>


