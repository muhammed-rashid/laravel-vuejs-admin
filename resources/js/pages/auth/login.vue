<template>
    <div class="d-flex flex-column flex-root">
		
			<div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed" style="background-image: url(assets/media/illustrations/sketchy-1/14.png">
		
				<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
				
					<a href="/" class="mb-12">
						<img alt="Logo" src="assets/images/logos/main-black.svg" class="h-40px" />
					</a>
				
					<div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
					
						<form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" >
						
							<div class="text-center mb-10">
							
								<h1 class="text-dark mb-3">Sign In to Metronic</h1>
						
								<div class="text-gray-400 fw-bold fs-4">New Here?
								<router-link to="/register" class="link-primary fw-bolder">Create an Account</router-link></div>
							
							</div>
					
							<div class="fv-row mb-10">
						
								<label class="form-label fs-6 fw-bolder text-dark">Email</label>
							
								<input class="form-control form-control-lg form-control-solid" type="text" autocomplete="off" v-model="email" />
					
							</div>
						
							<div class="fv-row mb-10">
							
								<div class="d-flex flex-stack mb-2">
							
									<label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
								
									<a href="" class="link-primary fs-6 fw-bolder">Forgot Password ?</a>
								
								</div>
							
								<input class="form-control form-control-lg form-control-solid" type="password" autocomplete="off"  v-model="password"/>
							
							</div>
						
							<div class="text-center">
							
								<button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5" @click.prevent="login">
									<span class="indicator-label">Continue</span>
									<span class="indicator-progress">Please wait...
									<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
								</button>
							
								<div class="text-center text-muted text-uppercase fw-bolder mb-5">or</div>
							
								<a href="#" class="btn btn-flex flex-center btn-light btn-lg w-100 mb-5">
								<img alt="Logo" src="./google-icon.svg" class="h-20px me-3" />Continue with Google</a>
							
							</div>
						
						</form>
					
					</div>
					
				</div>
			
				
			
			</div>
			
		</div>








</template>

<script>

import User from '../../api/user'
export default {
    data(){
		return{
			email:'',
			password:''
		}
	},
	methods:{
		login(){
		User.Login({email:this.email,password:this.password}).then(res=>{
			console.log(res);
		//auth attemps
		localStorage.setItem("token", res.data.data.token);
        this.$store.commit('Login',{
        id:res.data.data.id,
        email:res.data.data.email,
        name:res.data.data.name,
        auth:true,
        role:res.data.data.role,
        verified:res.data.data.email_verified_at
      })
//direct to routes
this.$router.push('/');


			}).catch(err=>{
				console.log(err);
			})
		}
	}
}
</script>