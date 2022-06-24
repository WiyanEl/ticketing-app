<script>
import { RouterLink, useRouter } from "vue-router";
import { onMounted, ref } from "vue";
import axios from "axios";

export default {
  setup() {
    const token = localStorage.getItem("token");

    const router = useRouter();

    const user = ref("");

    onMounted(() => {
      if (!token) {
        return router.push({
          name: "admin.login",
        });
      }

      axios.defaults.headers.common.Authorization = `Bearer ${token}`;
      axios
        .get("http://127.0.0.1:8000/api/user")
        .then((response) => {
          user.value = response.data;
        })
        .catch((err) => {
          console.log(err.response.data);
        });
    });

    function dropdownUser() {
      const dropdown = document.querySelector(".dropdown-user");
      dropdown.classList.toggle("active");
    }

    function logout() {
      axios.defaults.headers.common.Authorization = `Bearer ${token}`;
      axios
        .post("http://127.0.0.1:8000/api/logout")
        .then((response) => {
          if (response.data.success) {
            localStorage.removeItem("token");
            return router.push({
              name: "admin.login",
            });
          }
        })
        .catch((err) => {
          console.log(err.response.data);
        });
    }

    return {
      token,
      router,
      user,
      dropdownUser,
      logout,
    };
  },
};
</script>

<template>
  <header class="d-flex header-dashboard">
    <div class="title-page d-flex align-items-center">
      <h5>Ticketing Concert</h5>
    </div>
    <div class="user-info ms-auto d-flex align-items-center pe-4">
      <div class="dropdown-header">
        <button type="button" class="border-0 bg-transparent toggle-dropdown" @click="dropdownUser">{{ user.name }}</button>
        <ul class="dropdown-user shadow">
          <li>
            <router-link :to="{ name: 'admin.dashboard' }" class="text-decoration-none text-underline-none text-dark"><i class="bi bi-layout-text-window-reverse"></i> Dashboard</router-link>
          </li>
          <li @click.prevent="logout"><i class="bi bi-box-arrow-left text-danger"></i> <span class="text-dark">Logout</span></li>
        </ul>
      </div>
    </div>
  </header>
</template>

<style>
@import url(./../../assets/css/dashboard.css);
</style>
