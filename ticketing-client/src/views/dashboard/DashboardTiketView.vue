<script>
import { RouterLink } from "vue-router";
import { onMounted, ref } from "vue";
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
    const tikets = ref([]);

    const toast = useToast();

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

    function destroy(id, index) {
      axios.delete(`http://127.0.0.1:8000/api/tiket/${id}`).then(() => {
        tikets.value.data.splice(index, 1);

        toast.info("Tiket berhasil dihapus!", {
          type: "info",
          timeout: 3000,
          position: "top-center",
        });
      });
    }

    return {
      tikets,
      destroy,
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
              <li class="breadcrumb-item active" aria-current="page">Tiket</li>
            </ol>
          </nav>

          <h3>Data Tiket</h3>
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
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody v-for="(tiket, index) in tikets.data" :key="tiket.id">
                    <tr v-if="tiket.status_checkin == 0">
                      <td>{{ index + 1 }}</td>
                      <td>{{ tiket.id_tiket }}</td>
                      <td>{{ tiket.name }}</td>
                      <td>{{ tiket.email }}</td>
                      <td>{{ tiket.number_phone }}</td>
                      <td>{{ tiket.jenis_kelamin }}</td>
                      <td>
                        <router-link :to="{ name: 'admin.tiket.ubah', params: { id: tiket.id } }" class="btn btn-sm btn-success me-1"><i class="bi bi-pencil-square"></i></router-link>
                        <button class="btn btn-sm btn-danger" @click="destroy(tiket.id, index)"><i class="bi bi-trash"></i></button>
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
