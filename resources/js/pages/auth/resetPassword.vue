<template>
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
    <!--begin::Content-->
    <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
      <!--begin::Logo-->
      <a href="/" class="mb-12">
        <img
          alt="Logo"
          src="/assets/images/logos/main-black.svg"
          class="h-40px"
        />
      </a>

      <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
        <form
          class="form w-100"
          novalidate="novalidate"
          id="kt_password_reset_form"
        >
          <div class="text-center mb-10">
            <h1 class="text-dark mb-3">Reset Password</h1>
            <div class="text-gray-400 fw-bold fs-4">
              Enter your new password
            </div>
          </div>
<div
                class="alert alert-danger mt-3"
                role="alert"
                v-for="error in errors"
                :key="error"
              >
                {{ error }}
              </div>
          <div class="fv-row mb-5">
            <label class="form-label fw-bolder text-gray-900 fs-6"
              >Enter Your New Password</label
            >
            <input
              class="form-control form-control-solid"
              type="email"
              autocomplete="off"
              v-model="password"
            />
            <p
              class="message"
              :class="{ error: v$.password.$errors.length }"
              v-for="error in v$.password.$errors"
              :key="error"
            >
              {{ error.$message.replace("This", "Password") }}
            </p>
          </div>
          <div class="fv-row mb-10">
            <label class="form-label fw-bolder text-gray-900 fs-6"
              >Confirm Your password</label
            >
            <input
              class="form-control form-control-solid"
              type="email"
              autocomplete="off"
              v-model="confirmPassword"
            />
            <p
              class="message"
              :class="{ error: v$.confirmPassword.$errors.length }"
              v-for="error in v$.confirmPassword.$errors"
              :key="error"
            >
              {{
                error.$message
                  .replace("This Field", "Confirm Password")
                  .replace("The value", "Confirm Password")
                  .replace("other value", "password")
              }}
            </p>
          </div>

          <div class="d-flex flex-wrap pb-lg-0">
            <button
              type="button"
              id="kt_password_reset_submit"
              class="btn btn-lg btn-primary fw-bolder me-4"
              @click="resetPassword"
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
</template>
<script>
import User from "../../api/user";
import ErrorHandler from "../../utils/errors"
import { sameAs, required } from "@vuelidate/validators";
import { useVuelidate } from "@vuelidate/core";
export default {
  data() {
    return {
      password: "",
      confirmPassword: "",
      loading:false,
      errors:[]
    };
  },
  setup: () => ({ v$: useVuelidate() }),
  validations() {
    return {
      password: { required },
      confirmPassword: { required, sameAsPassword: sameAs(this.password) },
    };
  },

  methods: {
    resetPassword() {
       this.errors =[]
      //frontet validations
      this.v$.$touch();
      if (this.v$.$invalid) {
        return;
      }
 
      this.loading = true
      User.resetPassword({
        password: this.password,
        password_confirmation: this.confirmPassword,
        token:this.$route.query.token
      })
        .then((res) => {
           this.loading = false
           this.$router.push('/login')
        })
        .catch((err) => {
          this.loading = false
          this.errors = ErrorHandler(err.response)
        });
    },
  },
};
</script>