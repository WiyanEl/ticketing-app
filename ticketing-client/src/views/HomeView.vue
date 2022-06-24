<script>
import { ref, reactive } from "@vue/reactivity";
import { useToast } from "vue-toastification";
import axios from "axios";

export default {
  setup() {
    const tiket = reactive({
      name: "",
      email: "",
      number_phone: "",
      jenis_kelamin: "",
    });

    const searching = reactive({
      email: "",
    });

    const hasilSearch = ref([]);

    const validation = ref([]);

    const toast = useToast();

    function pesanTiket() {
      let btnPesanTiket = document.querySelector(".btn-pesan-tiket");
      btnPesanTiket.removeChild(btnPesanTiket.children[0]);
      btnPesanTiket.innerHTML = `
        <span>
          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
          Loading...
        </span>
      `;

      axios
        .post("http://127.0.0.1:8000/api/tiket", this.tiket)
        .then(() => {
          toast.success("Tiket berhasil dipesan", {
            type: "success",
            timeout: 3000,
            position: "top-center",
            dismissible: true,
          });

          tiket.name = "";
          tiket.email = "";
          tiket.number_phone = "";
          tiket.jenis_kelamin = "";

          btnPesanTiket.removeChild(btnPesanTiket.children[0]);
          btnPesanTiket.innerHTML = `
          <span><i class="bi bi-cart-plus"></i> Pesan</span>
        `;
        })
        .catch((err) => {
          validation.value = err.response.data;
          btnPesanTiket.removeChild(btnPesanTiket.children[0]);
          btnPesanTiket.innerHTML = `
          <span><i class="bi bi-cart-plus"></i> Pesan</span>
        `;
        });
    }

    function search() {
      let viewTiket = document.querySelector(".tiket-view");
      axios
        .get(`http://127.0.0.1:8000/api/tiket/search/${this.searching.email}`)
        .then((response) => {
          hasilSearch.value = response.data.data[0];

          viewTiket.classList.remove("d-none");

          document.querySelector(".id-tiket").innerHTML = hasilSearch.value.id_tiket;
          document.querySelector(".name-tiket").innerHTML = hasilSearch.value.name;
          document.querySelector(".email-tiket").innerHTML = hasilSearch.value.email;
          document.querySelector(".telepon-tiket").innerHTML = hasilSearch.value.number_phone;
        })
        .catch((err) => {
          toast.error("Tiket tidak ditemukan", {
            type: "error",
            position: "top-center",
            timeout: "3000",
          });
        });
    }

    function clearSearch() {
      let viewTiket = document.querySelector(".tiket-view");

      if (!viewTiket.classList.contains("d-none")) {
        viewTiket.classList.add("d-none");
      }

      this.searching.email = "";
    }

    return {
      tiket,
      validation,
      pesanTiket,
      searching,
      hasilSearch,
      search,
      clearSearch,
    };
  },
};
</script>

<template>
  <main class="home">
    <div class="container">
      <div class="row py-5">
        <div class="col-12">
          <h1 class="text-center title">Concert Ticketing</h1>
        </div>
      </div>
      <div class="row form-home">
        <div class="col-12 col-md-6">
          <div class="card">
            <div class="card-body">
              <h3 class="text-center text-uppercase mb-3">Isi Biodata</h3>
              <form action="" @submit.prevent="pesanTiket">
                <div class="mb-3">
                  <input v-model="tiket.name" type="text" class="form-control py-2 px-3" placeholder="Nama Lengkap" />
                  <span v-if="validation.name" class="text-danger">
                    {{ validation.name[0] }}
                  </span>
                </div>
                <div class="mb-3">
                  <input v-model="tiket.email" type="email" class="form-control py-2 px-3" placeholder="Email" />
                  <span v-if="validation.email" class="text-danger">
                    {{ validation.email[0] }}
                  </span>
                </div>
                <div class="mb-3">
                  <input v-model="tiket.number_phone" type="number" class="form-control py-2 px-3" placeholder="Nomor Telepon" maxlength="15" />
                  <span v-if="validation.number_phone" class="text-danger">
                    {{ validation.number_phone[0] }}
                  </span>
                </div>
                <div class="mb-3">
                  <select v-model="tiket.jenis_kelamin" class="form-select py-2 px-3">
                    <option>-- Pilih Jenis Kelamin --</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                  </select>
                  <span v-if="validation.jenis_kelamin" class="text-danger">
                    {{ validation.jenis_kelamin[0] }}
                  </span>
                </div>
                <div class="mb-3 d-flex">
                  <button type="submit" class="btn btn-success btn-pesan-tiket">
                    <span><i class="bi bi-cart-plus"></i> Pesan</span>
                  </button>
                  <button type="button" class="btn btn-info text-light ms-auto" data-bs-toggle="modal" data-bs-target="#infoTiket" @click="clearSearch">Lihat <i class="bi bi-ticket-detailed"></i></button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="col-md-6 d-none d-md-block">
          <img src="./../assets/images/bg/home-bg.png" alt="Gambar Home" class="img-fluid img-hero" />
        </div>
      </div>
    </div>

    <!-- Modal view tiket -->
    <div class="modal fade" id="infoTiket" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header border-0">
            <h5 class="modal-title" id="exampleModalLabel">Lihat Tiket</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="search">
              <div class="input-group mb-3">
                <input v-model="searching.email" type="text" class="form-control" placeholder="Masukan email..." aria-label="Recipient's username" aria-describedby="button-addon2" />
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
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal view tiket -->
  </main>
</template>

<style>
@import url(./../assets/css/home.css);
</style>
