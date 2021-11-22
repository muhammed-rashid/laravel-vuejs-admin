import { createRouter, createWebHistory } from "vue-router";

//component loading
import Login from '../pages/auth/login.vue'
import Register from '../pages/auth/register.vue'
import AdminLayout from '../layouts/dashboard/dashboardLayout.vue'
import Dashboard from '../pages/admin/dashboard.vue'

const router = createRouter({
    history:createWebHistory(),
    routes :[
        {
            path:'/login',
            component :Login
        },
        {
            path:'/register',
            component :Register
        },
        {
            path:'/authorize',
            component:Login,
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
    const currentUser = localStorage.getItem("token");

    if (authorize) {
        if (!currentUser) {
            // not logged in so redirect to login page with the return url
            return next({ path: "/login" });
        }
        // check if route is restricted by role
        if (
            authorize.length &&
            !authorize.includes(localStorage.getItem('role'))
        ) {
            // role not authorised so redirect to home page
            return next(false);
        }
    }
    next();
});



export default router