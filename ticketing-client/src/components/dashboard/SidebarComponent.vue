<script>
export default {
  setup() {
    function sidebarMenu() {
      let sidebarMenu = document.querySelector(".sidebar-menu");

      sidebarMenu.classList.toggle("active");
    }

    return {
      sidebarMenu,
    };
  },
};
</script>

<template>
  <div class="sidebar-menu">
    <div class="logo-content">
      <div class="logo">
        <i class="bi bi-collection-play"></i>
        <div class="logo_name">Ticketing</div>
      </div>
      <i class="bi bi-list" id="btn_menu" @click="sidebarMenu"></i>
    </div>
    <ul class="nav">
      <li>
        <router-link :to="{ name: 'admin.dashboard' }">
          <i class="bi bi-layout-text-window-reverse"></i>
          <span class="link_name">Dashboard</span>
        </router-link>
        <span class="tooltip">Dashboard</span>
      </li>
      <li>
        <router-link :to="{ name: 'admin.tiket' }">
          <i class="bi bi-ticket-detailed"></i>
          <span class="link_name">Tiket</span>
        </router-link>
        <span class="tooltip">Tiket</span>
      </li>
      <li>
        <router-link :to="{ name: 'admin.checkin' }">
          <i class="bi bi-card-checklist"></i>
          <span class="link_name">Checkin</span>
        </router-link>
        <span class="tooltip">Checkin</span>
      </li>
      <li>
        <router-link :to="{ name: 'admin.laporan' }">
          <i class="bi bi-card-list"></i>
          <span class="link_name">Laporan</span>
        </router-link>
        <span class="tooltip">Laporan</span>
      </li>
    </ul>
  </div>
</template>

<style>
@import url(./../../assets/css/dashboard.css);
</style>
