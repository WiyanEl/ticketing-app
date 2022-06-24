<script>
import sidebarComponent from "@/components/dashboard/SidebarComponent.vue";
import HeaderComponent from "@/components/dashboard/HeaderComponent.vue";

export default {
  components: {
    sidebarComponent,
    HeaderComponent,
  },
};
</script>

<template>
  <div class="content">
    <sidebarComponent />
    <div class="home_content">
      <div class="wrapper">
        <HeaderComponent />
        <div class="row">
          <div class="col">
            <h1>Selamat Datang Admin</h1>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
