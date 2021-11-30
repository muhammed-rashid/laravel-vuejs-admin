import Api from './api'

export default {
    RegitserUser : (user)=>{
        return Api().post('/auth/register',user)
    },
    GoogleAuth :async ()=>{
        return Api().get('/auth/redirect')
    },
    getUserDetails : ()=>{
        return Api().get('/auth/user')
    },
    logOut: async ()=>{
        return Api().get('/auth/logout')
    },
    ResendVerification : (data)=>{
        return Api().post('/email/resend',data)
    },
    VerifyEmail :(url) =>{
        return Api().get(url)
    },
    //login apis
    Login:(data)=>{
        return Api().post('auth/login',data)
    },
    //send reset email
    forgotPassword:(data)=>{
        return Api().post('auth/forgot-password',data)
    },
    resetPassword:(data)=>{
        return Api().post('/auth/reset-password',data)
    }
}