import { createRouter, createWebHistory } from "vue-router";
import HomeView from "@/views/HomeView.vue";
import LoginView from "@/views/auth/LoginView.vue";
import RegisterView from "@/views/auth/RegisterView.vue";
import DashboardHome from "@/views/dashboard/DashboardHomeView.vue";
import DashboardTiket from "@/views/dashboard/DashboardTiketView.vue";
import DashboardTiketUbah from "@/views/dashboard/DashboardUbahTiketView.vue";
import DashboardCheckin from "@/views/dashboard/DashboardCheckinView.vue";
import DashboardLaporan from "@/views/dashboard/DashboardLaporanView.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: HomeView,
    },
    {
      path: "/admin/login",
      name: "admin.login",
      component: LoginView,
    },
    {
      path: "/admin/register",
      name: "admin.register",
      component: RegisterView,
    },
    {
      path: "/admin/dashboard",
      name: "admin.dashboard",
      component: DashboardHome,
    },
    {
      path: "/admin/tiket",
      name: "admin.tiket",
      component: DashboardTiket,
    },
    {
      path: "/admin/tiket/:id",
      name: "admin.tiket.ubah",
      component: DashboardTiketUbah,
    },
    {
      path: "/admin/checkin",
      name: "admin.checkin",
      component: DashboardCheckin,
    },
    {
      path: "/admin/laporan",
      name: "admin.laporan",
      component: DashboardLaporan,
    },
  ],
});

export default router;
