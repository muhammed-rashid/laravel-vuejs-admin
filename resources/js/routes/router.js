import { createRouter, createWebHistory } from "vue-router";

//component loading
import Login from '../pages/auth/login.vue'
import Register from '../pages/auth/register.vue'
import AdminLayout from '../layouts/dashboard/dashboardLayout.vue'
import Dashboard from '../pages/admin/dashboard.vue'
import Forgot from '../pages/auth/forgetPassword'
import Reset from '../pages/auth/resetPassword'
import store from '../store/index'







const router = createRouter({
    history:createWebHistory(),
    routes :[
        {
            path:'/login',
            component :Login,
            meta:{guest:true}
        },
        {
            path:'/register',
            component :Register,
            meta:{guest:true}
        },
       
        {
            path:'/verify-email/:id/:hash',
            component:Register,
            meta:{guest:true}
        },
        {
            path:'/forgot-password',
            component:Forgot,
            meta:{guest:true}
        },
        {
            path:'/reset-password',
            component:Reset,
            meta:{guest:true}
        },
        {
            path:'/admin',
            component : AdminLayout,
            meta: { authorize: ['user'],
        },
            children:[
                {
                    path:'dashboard',
                    component:Dashboard
                }
            ]
        }
    ]
})

router.beforeEach((to, from, next) => {
   
    // redirect to login page if not logged in and trying to access a restricted page
    const { authorize } = to.meta;
    const currentUser = store.getters.getVerified;

    if (authorize) {
        if (!currentUser) {
            // not logged in so redirect to login page with the return url
            return next({ path: "/login" });
        }
        // check if route is restricted by role
        if (
            authorize.length &&
            !authorize.includes(store.getters.getUserRole)
        ) {
            // role not authorised so redirect to home page
            return next(from.path);
        }
    }
    next();
});

//guest pages

router.beforeEach((to, from, next) => {

    if (to.meta.guest && store.getters.getVerified) {
        return next({path:'/'}); 
    }

    return next();
});
export default router



