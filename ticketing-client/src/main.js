import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import Toast from "vue-toastification";

// Vue toast
import "vue-toastification/dist/index.css";

// bootstrap
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap/dist/js/bootstrap.min.js";
import "bootstrap-icons/font/bootstrap-icons.css";

// Style
import "@/assets/css/style.css";

const app = createApp(App);

app.use(Toast);

app.use(router);

app.mount("#app");
