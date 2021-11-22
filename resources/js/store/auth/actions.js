
export default{
    LoginAction(context,user){

        context.commit('Login',{
            id:user.id,
            email:user.email,
            name:user.name,
            auth:true,
            role:user.role
        })
    },
    LogOutAction(context){
        
       
        localStorage.removeItem('token');
 
        context.commit('Login',{
            id:'',
            email:'',
            name:'',
            auth:false,
            role:''

        })
       
      
    },

    

    
}