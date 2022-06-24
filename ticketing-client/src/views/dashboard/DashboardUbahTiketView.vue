<script>
import { RouterLink, useRouter, useRoute } from "vue-router";
import { onMounted } from "vue";
import { ref, reactive } from "@vue/reactivity";
import axios from "axios";
import { useToast } from "vue-toastification";
import sidebarComponent from "@/components/dashboard/SidebarComponent.vue";
import HeaderComponent from "@/components/dashboard/HeaderComponent.vue";

export default {
  components: {
    sidebarComponent,
    HeaderComponent,
  },
  setup() {
    const token = localStorage.getItem("token");

    const tiket = reactive({
      id_tiket: "",
      name: "",
      email: "",
      number_phone: "",
      jenis_kelamin: "",
    });

    const validation = ref([]);

    const router = useRouter();
    const route = useRoute();

    const toast = useToast();

    onMounted(() => {
      axios
        .get(`http://127.0.0.1:8000/api/tiket/${route.params.id}`)
        .then((response) => {
          tiket.id_tiket = response.data.data.id_tiket;
          tiket.name = response.data.data.name;
          tiket.email = response.data.data.email;
          tiket.number_phone = response.data.data.number_phone;
          tiket.jenis_kelamin = response.data.data.jenis_kelamin;
        })
        .catch((err) => {
          console.log(err.response.data);
        });
    });

    function update() {
      let btnUbahTiket = document.querySelector(".btn-ubah-tiket");
      btnUbahTiket.removeChild(btnUbahTiket.children[0]);
      btnUbahTiket.innerHTML = `
        <span>
          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
          Loading...
        </span>
      `;
      axios.defaults.headers.common.Authorization = `Bearer ${token}`;
      axios
        .put(`http://127.0.0.1:8000/api/tiket/${route.params.id}`, this.tiket)
        .then(() => {
          router.push({
            name: "admin.tiket",
          });

          toast.success("Tiket berhasil diubah", {
            type: "success",
            timeout: 3000,
            position: "top-center",
          });
        })
        .catch((err) => {
          validation.value = err.response.data;

          btnUbahTiket.removeChild(btnUbahTiket.children[0]);
          btnUbahTiket.innerHTML = `
          <span><i class="bi bi-pen"></i> Ubah</span>
        `;
        });
    }

    return {
      tiket,
      validation,
      router,
      route,
      update,
    };
  },
};
</script>

<template>
  <div class="content">
    <sidebarComponent />
    <div class="home_content">
      <div class="wrapper">
        <HeaderComponent />
        <div class="container">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><router-link :to="{ name: 'admin.dashboard' }" class="text-success text-decoration-none">Home</router-link></li>
              <li class="breadcrumb-item"><router-link :to="{ name: 'admin.tiket' }" class="text-success text-decoration-none">Tiket</router-link></li>
              <li class="breadcrumb-item active" aria-current="page">Ubah Tiket</li>
            </ol>
          </nav>
          <div class="row">
            <div class="col-md-8">
              <div class="row mt-4">
                <div class="col-6">
                  <h3>Ubah Tiket</h3>
                </div>
                <div class="col-6 d-flex">
                  <router-link :to="{ name: 'admin.tiket' }" class="btn btn-success ms-auto"><i class="bi bi-arrow-left-short"></i> Kembali</router-link>
                </div>
              </div>
              <hr />
              <div class="card border-0 shadow">
                <div class="card-body">
                  <form action="" @submit.prevent="update">
                    <div class="mb-2">
                      <label for="id-tiket" class="form-label">ID Tiket</label>
                      <input v-model="tiket.id_tiket" type="text" class="form-control" id="id-tiket" readonly />
                      <span v-if="validation.id_tiket" class="text-danger">
                        {{ validation.id_tiket[0] }}
                      </span>
                    </div>
                    <div class="mb-2">
                      <label for="name" class="form-label">Nama</label>
                      <input v-model="tiket.name" type="text" class="form-control" id="name" />
                      <span v-if="validation.name" class="text-danger">
                        {{ validation.name[0] }}
                      </span>
                    </div>
                    <div class="mb-2">
                      <label for="email" class="form-label">Email</label>
                      <input v-model="tiket.email" type="email" class="form-control" id="email" />
                      <span v-if="validation.email" class="text-danger">
                        {{ validation.email[0] }}
                      </span>
                    </div>
                    <div class="mb-2">
                      <label for="number-phone" class="form-label">Telepon</label>
                      <input v-model="tiket.number_phone" type="text" class="form-control" id="number-phone" />
                      <span v-if="validation.number_phone" class="text-danger">
                        {{ validation.number_phone[0] }}
                      </span>
                    </div>
                    <div class="mb-3">
                      <label for="jenis-kelamin" class="form-label">Jenis Kelamin</label>
                      <select v-model="tiket.jenis_kelamin" id="jenis-kelamin" class="form-select">
                        <option>-- Pilih Jenis Kelamin --</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                      </select>
                      <span v-if="validation.jenis_kelamin" class="text-danger">
                        {{ validation.jenis_kelamin[0] }}
                      </span>
                    </div>
                    <div class="mb-2">
                      <button type="submit" class="btn btn-success btn-ubah-tiket">
                        <span><i class="bi bi-pen"></i> Ubah</span>
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
