import { createRouter, createWebHistory } from "vue-router";

//component loading
import Login from "../pages/auth/login.vue";
import Register from "../pages/auth/register.vue";
import AdminLayout from "../layouts/dashboard/dashboardLayout.vue";
import Dashboard from "../pages/admin/dashboard.vue";
import Forgot from "../pages/auth/forgetPassword";
import Reset from "../pages/auth/resetPassword";
import store from "../store/index";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "/login",
            component: Login,
            meta: { guest: true },
        },
        {
            path: "/register",
            component: Register,
            meta: { guest: true },
        },

        {
            path: "/verify-email/:id/:hash",
            component: Register,
            meta: { guest: true },
        },
        {
            path: "/forgot-password",
            component: Forgot,
            meta: { guest: true },
        },
        {
            path: "/reset-password",
            component: Reset,
            meta: { guest: true },
        },
        {
            path: "/admin",
            component: AdminLayout,
            meta: { authorize: ["user"] },
            children: [
                {
                    path: "dashboard",
                    component: Dashboard,
                },
            ],
        },
    ],
});

router.beforeEach((to, from, next) => {
    // redirect to login page if not logged in and trying to access a restricted page
    const { authorize } = to.meta;
    const currentUser = store.getters.getVerified;
    if (authorize && !currentUser) {
        store.dispatch("getUserDetails").then((res) => {
            if (!store.getters.getVerified) {
                next("/login");
            } else {
                //check if user has the role to access the page
                if (
                    authorize.length &&
                    !authorize.includes(store.getters.getUserRole)
                ) {
                    // role not authorised so redirect to home page

                    return next("/");
                } else {
                    return next();
                }
            }
        });
    } else {
        if (to.meta.guest) {
            //check if user is authenticated
            store.dispatch("getUserDetails").then((res) => {
                if (store.getters.getVerified == "") {
                    return next();
                } else {
                    next("/");
                }
            });
        } else {
            return next();
        }
    }
});
export default router;
