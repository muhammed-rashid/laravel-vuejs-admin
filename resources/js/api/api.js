import axios from 'axios'
axios.defaults.withCredentials = true;

const baseApi = axios.create({
    baseURL :'http://127.0.0.1:8000/api/'
})

const Api = ()=>{
    let token = localStorage.getItem('token') 
    if(token){
       // baseApi.defaults.headers.common["Authorization"] = `Bearer ${token}`
       baseApi.defaults.headers.common["Authorization"] = `Bearer ${token}`
    }
    return baseApi
}

export default Api;