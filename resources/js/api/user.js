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
    ResendVerification : ()=>{
        return Api().get('/email/resend')
    }
}