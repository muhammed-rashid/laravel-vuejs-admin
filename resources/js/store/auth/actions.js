import User from "../../api/user";
export default {
    LoginAction(context, user) {
        context.commit("Login", {
            id: user.id,
            email: user.email,
            name: user.name,
            auth: true,
            role: user.role,
        });
    },
    LogOutAction(context) {
        localStorage.removeItem("token");

        context.commit("Login", {
            id: "",
            email: "",
            name: "",
            auth: false,
            role: "",
            verified:'',
        });
    },

    getUserDetails(context){
        return new Promise((resolve, reject) => {
            User.getUserDetails().then((res) => {
                //user exist
                context.commit("Login", {
                    id: res.data.id,
                    email: res.data.email,
                    name: res.data.name,
                    auth: true,
                    role: res.data.role,
                    verified: res.data.email_verified_at,
                 
                });
               
            })
            resolve(true)
        })
    },
};
