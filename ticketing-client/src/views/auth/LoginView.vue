<script>
import { RouterLink, useRouter } from "vue-router";
import { reactive, ref } from "@vue/reactivity";
import { onMounted } from "vue";
import { useToast } from "vue-toastification";
import axios from "axios";

export default {
  setup() {
    let user = reactive({
      username: "",
      password: "",
    });

    const token = localStorage.getItem("token");

    const validation = ref([]);

    const router = useRouter();

    const toast = useToast();

    onMounted(() => {
      if (token) {
        return router.push({
          name: "admin.dashboard",
        });
      }
    });

    function login() {
      let btnLogin = document.querySelector(".btn-login");
      btnLogin.removeChild(btnLogin.children[0]);
      btnLogin.innerHTML = `
        <span>
          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
          Loading...
        </span>
      `;

      axios
        .post("http://127.0.0.1:8000/api/login", this.user)
        .then((response) => {
          if (response.data.success) {
            localStorage.setItem("token", response.data.token);
            return router.push({
              name: "admin.dashboard",
            });
          }

          toast.error("Username atau password salah!", {
            type: "error",
            position: "top-center",
            timeout: "3000",
          });
          btnLogin.removeChild(btnLogin.children[0]);
          btnLogin.innerHTML = `
          <span>Login <i class="bi bi-box-arrow-in-right"></i></span>
        `;
        })
        .catch((err) => {
          validation.value = err.response.data;
          btnLogin.removeChild(btnLogin.children[0]);
          btnLogin.innerHTML = `
          <span>Login <i class="bi bi-box-arrow-in-right"></i></span>
        `;
        });
    }

    return {
      user,
      router,
      validation,
      login,
    };
  },
};
</script>

<template>
  <main>
    <header>
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path
          fill="#198754"
          fill-opacity="1"
          d="M0,256L48,261.3C96,267,192,277,288,261.3C384,245,480,203,576,165.3C672,128,768,96,864,90.7C960,85,1056,107,1152,133.3C1248,160,1344,192,1392,208L1440,224L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"
        ></path>
      </svg>
    </header>
    <div class="container auth">
      <div class="row justify-content-center">
        <div class="col-12 col-md-4">
          <form @submit.prevent="login">
            <h1 class="h3 mb-4 fw-normal text-center text-uppercase">Login</h1>

            <div class="mb-3">
              <input v-model="user.username" type="text" class="form-control" placeholder="Username" />
              <span v-if="validation.username" class="text-danger">
                {{ validation.username[0] }}
              </span>
            </div>
            <div class="mb-3">
              <input v-model="user.password" type="password" class="form-control" placeholder="Password" />
              <span v-if="validation.password" class="text-danger">
                {{ validation.password[0] }}
              </span>
            </div>
            <div class="mb-3">
              <button class="w-100 btn btn-lg btn-success btn-login" type="submit">
                <span>Login <i class="bi bi-box-arrow-in-right"></i></span>
              </button>
            </div>
            <div class="mb-3 text-center">
              <span>Belum punya akun? <router-link :to="{ name: 'admin.register' }" class="text-success text-decoration-none">Register</router-link></span>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
</template>

<style>
@import url(./../../assets/css/auth.css);
</style>
