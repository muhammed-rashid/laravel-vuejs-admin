<template>
  <div class="d-flex flex-column flex-root">
    <div
      class="
        d-flex
        flex-column flex-column-fluid
        bgi-position-y-bottom
        position-x-center
        bgi-no-repeat bgi-size-contain bgi-attachment-fixed
      "
      style="background-image: url(assets/media/illustrations/sketchy-1/14.png"
    >
      <div
        class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20"
      >
       

        <div class="w-lg-600px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
          <form class="form w-100" novalidate="novalidate" id="kt_sign_up_form">
            <div class="mb-10 text-center">
              <h1 class="text-dark mb-3">Create an Account</h1>

              <div class="text-gray-400 fw-bold fs-4" >
                Already have an account?
                <router-link to="/login" class="link-primary fw-bolder"
                  >Sign in here</router-link
                >
              </div>
              <div
                class="alert alert-danger mt-3"
                role="alert"
                v-for="error in errors"
                :key="error"
              >
                {{ error }}
              </div>
            </div>
            <div class="alert alert-warning mb-3" role="alert" v-if="success">
              {{waringMsg}}<a
                @click="resend"
                ><b>&nbsp;&nbsp; Resend Verification Link</b></a
              >
            </div>
           

            <button
              type="button"
              class="btn btn-light-primary fw-bolder w-100 mb-10"
              @click="LoginWithGoogle"
            >
              <img alt="Logo" src="./google-icon.svg" class="h-20px me-3" />Sign
              in with Google
            </button>

            <div class="d-flex align-items-center mb-10">
              <div class="border-bottom border-gray-300 mw-50 w-100"></div>
              <span class="fw-bold text-gray-400 fs-7 mx-2">OR</span>
              <div class="border-bottom border-gray-300 mw-50 w-100"></div>
            </div>

            <div class="row fv-row mb-7">
              <div class="col-xl-12">
                <label class="form-label fw-bolder text-dark fs-6">
                  User Name</label
                >
                <input
                  class="form-control form-control-lg form-control-solid"
                  type="text"
                  v-model="userName"
                  autocomplete="off"
                />
                <p
                class="message"
                :class="{ error: v$.userName.$errors.length }"
                v-for="error in v$.userName.$errors"
                :key="error"
              >
                {{ error.$message }}
              </p>
              </div>
            </div>

            <div class="fv-row mb-7">
              <label class="form-label fw-bolder text-dark fs-6">Email</label>
              <input
                class="form-control form-control-lg form-control-solid"
                type="email"
                v-model="email"
                autocomplete="off"
              />
               <p
                class="message"
                :class="{ error: v$.email.$errors.length }"
                v-for="error in v$.email.$errors"
                :key="error"
              >
                {{ error.$message }}
              </p>
            </div>

            <div class="mb-10 fv-row" data-kt-password-meter="true">
              <div class="mb-1">
                <label class="form-label fw-bolder text-dark fs-6"
                  >Password</label
                >

                <div class="position-relative mb-3">
                  <input
                    class="form-control form-control-lg form-control-solid"
                    type="password"
                    v-model="password"
                    autocomplete="off"
                  />
                   <p
                class="message"
                :class="{ error: v$.password.$errors.length }"
                v-for="error in v$.password.$errors"
                :key="error"
              >
                {{ error.$message }}
              </p>
                  <span
                    class="
                      btn btn-sm btn-icon
                      position-absolute
                      translate-middle
                      top-50
                      end-0
                      me-n2
                    "
                    data-kt-password-meter-control="visibility"
                  >
                    <i class="bi bi-eye-slash fs-2"></i>
                    <i class="bi bi-eye fs-2 d-none"></i>
                  </span>
                </div>

               
              </div>

              
            </div>

            <div class="fv-row mb-5">
              <label class="form-label fw-bolder text-dark fs-6"
                >Confirm Password</label
              >
              <input
                class="form-control form-control-lg form-control-solid"
                type="password"
                v-model="confirmPassword"
                autocomplete="off"
              />
               <p
                class="message"
                :class="{ error: v$.confirmPassword.$errors.length }"
                v-for="error in v$.confirmPassword.$errors"
                :key="error"
              >
                {{ error.$message.replace('This Field', 'Confirm Password').replace('The value','Confirm Password').replace('other value','password') }}
               
              </p>
              
            </div>

            <div class="fv-row mb-10">
              <label
                class="
                  form-check
                  form-check-custom
                  form-check-solid
                  form-check-inline
                "
              >
                <input
                  class="form-check-input"
                  type="checkbox"
                  name="toc"
                  value="1"
                />
                <span class="form-check-label fw-bold text-gray-700 fs-6"
                  >I Agree
                  <a href="#" class="ms-1 link-primary">Terms and conditions</a
                  >.</span
                >
              </label>
            </div>

            <div class="text-center">
              <button
                type="button"
                id="kt_sign_up_submit"
                class="btn btn-lg btn-primary"
                @click.prevent="RegisterUser"
              >
                <span class="indicator-label" v-if="!loading">Submit</span>
                <span v-else
                  >Please wait...
                  <span
                    class="spinner-border spinner-border-sm align-middle ms-2"
                  ></span
                ></span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import user from "../../api/user";
import ErrorHandler from "../../utils/errors";
import { email, required,sameAs } from "@vuelidate/validators";
import { useVuelidate } from "@vuelidate/core";
export default {
  setup: () => ({ v$: useVuelidate() }),
  validations() {
    return {
      password: { required },
      email: { required, email },
      userName:{required},
      confirmPassword : { required,sameAsPassword: sameAs(this.password )}

    };
  },
  methods: {
    LoginWithGoogle() {
      user.GoogleAuth().then((res) => {
        window.location.href = res.data.data.URL;
      });
    },

    //register a user
    RegisterUser() {
      //frontent validations
      this.v$.$touch();
      if (this.v$.$invalid) {
      
        return;
      }

 this.loading = true

      user
        .RegitserUser({
          userName: this.userName,
          email: this.email,
          password: this.password,
        })
        .then((res) => {
          this.userName = ""
          this.email = ""
          this.password = ""
          this.confirmPassword =""

          
        localStorage.setItem("token", res.data.data.token);
        this.$store.commit('Login',{
        id:res.data.data.id,
        email:res.data.data.email,
        name:res.data.data.name,
        auth:true,
        role:res.data.data.role,
        verified:res.data.data.email_verified_at
      })

         this.loading = false
          this.v$.$reset();
		      this.success = true
          this.errors = []
		      this.waringMsg = res.data.message

          
        })
        .catch((err) => {
           this.loading = false
          this.errors = ErrorHandler(err.response)
        });
    },
    resend() {
      user.ResendVerification().then((res) => this.waringMsg =res.data.message );
    },
  },

  data() {
    return {
      userName: "",
      email: "",
      password: "",
      confirmPassword: "",
      success: false,
	    waringMsg:'',
      errors:[],
      loading:false
    };
  },

  created() {

	
    if (this.$route.params.hash && this.$route.params.hash) {
		user.VerifyEmail(this.$route.fullPath).then(res=>{
      localStorage.setItem('token',res.data.data.token)
      this.$store.commit('Login',{
        id:res.data.data.user.id,
        email:res.data.data.user.email,
        name:res.data.data.user.name,
        auth:true,
        role:res.data.data.user.role,
        verified:res.data.data.user.email_verified_at
      })
			 this.$router.push("/");

		}).catch(err=>{
			this.waringMsg = err.response.data.message
     
     
		})

      
    }

	
  },
};
</script>