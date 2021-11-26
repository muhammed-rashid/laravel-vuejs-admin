require("./bootstrap");

import { createApp } from "vue";
import router from "./routes/router";
import VueCookie from "vue3-cookies";
import store from "./store/index.js";
import App from "./App.vue";
import { useCookies } from "vue3-cookies";
const { cookies } = useCookies();

//check if user have social login redirect
if (cookies.get("authentication")) {
  console.log(cookies.get("authentication"));
    localStorage.setItem("token", cookies.get("authentication").authToken);
    cookies.remove("authentication");
    getUser();
} else {
    getUser();
}

function getUser() {
    store
        .dispatch("getUserDetails")
        .then((res) => {
            const app = createApp(App);
            app.use(VueCookie);
            app.use(store);
            app.use(router);
            app.mount("#app");
        })
        .catch((err) => {
            //launch and dispach logout
            store.dispatch("LogOutAction");
            const app = createApp(App);
            app.use(VueCookie);
            app.use(store);
            app.use(router);
            app.mount("#app");
        });
}


