<script>
import { RouterLink } from "vue-router";
import { onMounted, ref } from "vue";
import axios from "axios";
import sidebarComponent from "@/components/dashboard/SidebarComponent.vue";
import HeaderComponent from "@/components/dashboard/HeaderComponent.vue";

export default {
  components: {
    sidebarComponent,
    HeaderComponent,
  },
  setup() {
    const tikets = ref([]);

    onMounted(() => {
      axios
        .get("http://127.0.0.1:8000/api/tiket")
        .then((response) => {
          tikets.value = response.data;
        })
        .catch((err) => {
          console.log(err.response.data);
        });
    });

    return {
      tikets,
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
              <li class="breadcrumb-item active" aria-current="page">Laporan</li>
            </ol>
          </nav>

          <h3>Laporan Tiket</h3>
          <hr />
          <div class="card border-0 shadow">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>ID Tiket</th>
                      <th>Nama</th>
                      <th>Email</th>
                      <th>Telepon</th>
                      <th>Jenis Kelamin</th>
                      <th>status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(tiket, index) in tikets.data" :key="tiket.id">
                      <td>{{ index + 1 }}</td>
                      <td>{{ tiket.id_tiket }}</td>
                      <td>{{ tiket.name }}</td>
                      <td>{{ tiket.email }}</td>
                      <td>{{ tiket.number_phone }}</td>
                      <td>{{ tiket.jenis_kelamin }}</td>
                      <td>
                        <span v-if="tiket.status_checkin == 0">
                          <span class="badge rounded-pill text-bg-danger">Belum CHECKIN</span>
                        </span>
                        <span v-else>
                          <span class="badge rounded-pill text-bg-primary">CHECKIN</span>
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
