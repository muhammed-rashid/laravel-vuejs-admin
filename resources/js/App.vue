<template>
  <router-view></router-view>
  <p>{{details}}</p>
</template>
<script>

import User from './api/user'
export default {

beforeCreate(){
  if(this.$cookies.get('authentication')){
    
    localStorage.setItem('token',this.$cookies.get('authentication').authToken)
    this.$cookies.remove('authentication')
  }else{
    User.getUserDetails().then(res=>{
        //user exist
        localStorage.setItem('role',res.data.role)
        this.$store.dispatch('LoginAction',res.data)
    }).catch(err=>{
        this.store.dispatch('LogOutAction')
    })
  }

}

}
</script>