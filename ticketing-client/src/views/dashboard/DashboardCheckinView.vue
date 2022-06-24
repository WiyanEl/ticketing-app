<script>
import { ref, reactive } from "@vue/reactivity";
import { useToast } from "vue-toastification";
import axios from "axios";
import sidebarComponent from "@/components/dashboard/SidebarComponent.vue";
import HeaderComponent from "@/components/dashboard/HeaderComponent.vue";

export default {
  components: {
    sidebarComponent,
    HeaderComponent,
  },
  setup() {
    const searching = reactive({
      id_tiket: "",
    });

    const hasilSearch = ref([]);

    const toast = useToast();

    function search() {
      let viewTiket = document.querySelector(".tiket-view");
      let tiketView = document.querySelector("tiket-view");
      axios
        .get(`http://127.0.0.1:8000/api/tiket/cariid/${this.searching.id_tiket}`)
        .then((response) => {
          hasilSearch.value = response.data.data[0];

          viewTiket.classList.remove("d-none");

          document.querySelector(".id-tiket").innerHTML = hasilSearch.value.id_tiket;
          document.querySelector(".name-tiket").innerHTML = hasilSearch.value.name;
          document.querySelector(".email-tiket").innerHTML = hasilSearch.value.email;
          document.querySelector(".telepon-tiket").innerHTML = hasilSearch.value.number_phone;
          document.querySelector(".jkelamin-tiket").innerHTML = hasilSearch.value.jenis_kelamin;
          document.querySelector(".btn-checkin").setAttribute("data-id", hasilSearch.value.id);
        })
        .catch((err) => {
          toast.error("Tiket tidak ditemukan", {
            type: "error",
            position: "top-center",
            timeout: "3000",
          });
          viewTiket.classList.add("d-none");
        });
    }

    function checkin() {
      let idTiket = document.querySelector(".btn-checkin").getAttribute("data-id");

      if (hasilSearch.value.status_checkin > 0) {
        toast.error("Tiket sudah pernah digunakan", {
          type: "error",
          timeout: 5000,
          position: "top-center",
        });
      } else {
        axios
          .put(`http://127.0.0.1:8000/api/tiket/checkin/${idTiket}`)
          .then(() => {
            toast.success("Berhasil checkin", {
              type: "success",
              timeout: 5000,
              position: "top-center",
            });
          })
          .catch((err) => {
            console.log(err.response.data);
          });

        hasilSearch.value.status_checkin = 1;
      }
    }

    return {
      searching,
      hasilSearch,
      search,
      checkin,
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
              <li class="breadcrumb-item active" aria-current="page">Checkin</li>
            </ol>
          </nav>
          <div class="row">
            <div class="col-md-6">
              <h3>Input ID Tiket</h3>
              <hr />
              <div class="card border-0 shadow">
                <div class="card-body">
                  <form @submit.prevent="search">
                    <div class="input-group mb-3">
                      <input v-model="searching.id_tiket" type="text" class="form-control" placeholder="Masukan ID Tiket..." aria-label="Recipient's username" aria-describedby="button-addon2" />
                      <button class="btn btn-info text-light" type="submit" id="button-addon2"><i class="bi bi-search"></i></button>
                    </div>
                  </form>
                  <div class="container tiket-view d-none">
                    <div class="row mt-4">
                      <div class="col-12 border p-2">
                        <h3 class="text-center id-tiket"></h3>
                      </div>
                    </div>
                    <div class="row mt-3">
                      <div class="col">
                        <div class="mb-3">
                          <span class="me-3 fw-bold">Nama</span>
                          <span class="me-3">:</span>
                          <span class="me-3 name-tiket"></span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <div class="mb-3">
                          <span class="me-3 fw-bold">Email</span>
                          <span class="me-3">:</span>
                          <span class="me-3 email-tiket"></span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <div class="mb-3">
                          <span class="me-3 fw-bold">No Telepon</span>
                          <span class="me-3">:</span>
                          <span class="me-3 telepon-tiket"></span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <div class="mb-3">
                          <span class="me-3 fw-bold">Jenis Kelamin</span>
                          <span class="me-3">:</span>
                          <span class="me-3 jkelamin-tiket"></span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <div class="mb-3">
                          <button class="btn btn-success btn-checkin" @click="checkin()">
                            <span><i class="bi bi-check"></i> Checkin</span>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
