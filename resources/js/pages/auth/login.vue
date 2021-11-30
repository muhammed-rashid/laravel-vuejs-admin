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
        <a href="/" class="mb-12">
          <img
            alt="Logo"
            src="assets/images/logos/main-black.svg"
            class="h-40px"
          />
        </a>

        <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
          <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form">
            <div class="text-center mb-10">
              <h1 class="text-dark mb-3">Sign In to Metronic</h1>

              <div class="text-gray-400 fw-bold fs-4">
                New Here?
                <router-link to="/register" class="link-primary fw-bolder"
                  >Create an Account</router-link
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
               <div class="alert alert-warning mb-3 mt-3" role="alert" v-if="warning">
              Email is not yet verified<a
                @click="resend"
                ><b>&nbsp;&nbsp; Resend Verification Link</b></a
              >
            </div>
            </div>

            <div class="fv-row mb-10">
              <label class="form-label fs-6 fw-bolder text-dark">Email</label>

              <input
                class="form-control form-control-lg form-control-solid"
                type="text"
                autocomplete="off"
                v-model="email"
                :class="{ error: v$.email.$errors.length }"
              />
              <div class="error" v-if="!v$.email.required">
                Name is required
              </div>
              <p
                class="message"
                :class="{ error: v$.email.$errors.length }"
                v-for="error of v$.email.$errors"
                :key="error"
              >
                {{ error.$message }}
              </p>
            </div>

            <div class="fv-row mb-10">
              <div class="d-flex flex-stack mb-2">
                <label class="form-label fw-bolder text-dark fs-6 mb-0"
                  >Password</label
                >

                <router-link to="reset-password" class="link-primary fs-6 fw-bolder"
                  >Forgot Password ?</router-link
                >
              </div>

              <input
                class="form-control form-control-lg form-control-solid error"
                type="password"
                autocomplete="off"
                v-model="password"
              />
              <p
                class="message"
                :class="{ error: v$.password.$errors.length }"
                v-for="error in v$.password.$errors"
                :key="error"
              >
                {{ error.$message }}
              </p>
            </div>

            <div class="text-center">
              <button
                type="submit"
                id="kt_sign_in_submit"
                class="btn btn-lg btn-primary w-100 mb-5"
                @click.prevent="login"
              >
                <span class="indicator-label" v-if="!loading">Sign In</span>
                <span v-else
                  >Please wait...
                  <span
                    class="spinner-border spinner-border-sm align-middle ms-2"
                  ></span
                ></span>
              </button>

              <div class="text-center text-muted text-uppercase fw-bolder mb-5">
                or
              </div>

               <button
              type="button"
              class="btn btn-light-primary fw-bolder w-100 mb-10"
              @click="LoginWithGoogle"
            >
              <img alt="Logo" src="./google-icon.svg" class="h-20px me-3" />Sign
              in with Google
            </button>

            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import User from "../../api/user";
import ErrorHandler from "../../utils/errors";
import { email, required } from "@vuelidate/validators";
import { useVuelidate } from "@vuelidate/core";
export default {
  data() {
    return {
      email: "",
      password: "",
      errors: [],
      loading: false,
      warning:false,
    };
  },

  setup: () => ({ v$: useVuelidate() }),
  validations() {
    return {
      password: { required },
      email: { required, email },
    };
  },

  methods: {
    login() {
      //frontet validations
      this.v$.$touch();
      if (this.v$.$invalid) {
        return;
      }

      (this.loading = true),
      this.warning = false;
        this.errors = []
        User.Login({ email: this.email, password: this.password })
          .then((res) => {
            //auth attemps
            (this.loading = false)

            if(res.status == 203){
                //handle email verification isuues here
                localStorage.setItem("token", res.data.data.token);
                  this.warning = true;
            }else{




            localStorage.setItem("token", res.data.data.token);
            this.$store.commit("Login", {
              id: res.data.data.user.id,
              email: res.data.data.user.email,
              name: res.data.data.user.name,
              auth: true,
              role: res.data.data.user.role,
              verified: res.data.data.user.email_verified_at,
            });
            //direct to routes
            this.$router.push("/");
            }
          })
          .catch((err) => {
            (this.loading = false), (this.errors = ErrorHandler(err.response));
          });
    },
    resend() {
      User.ResendVerification().then((res) => this.waringMsg = res.data.message );
    },
     LoginWithGoogle() {
      User.GoogleAuth().then((res) => {
        window.location.href = res.data.data.URL;
      });
    },
  },
};
</script>

<style >
.message {
  margin-top: 5px;
  font-size: 12px;
  color: red;
  display: none;
  margin-left: 5px;
}
.error {
  display: block;
}
</style>