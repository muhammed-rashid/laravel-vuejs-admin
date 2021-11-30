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
          src="assets/images/logos/main-black.svg"
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
            <h1 class="text-dark mb-3">Forgot Password ?</h1>
            <div class="text-gray-400 fw-bold fs-4">
              Enter your email to reset your password.
            </div>
          </div>
          <div
            class="alert alert-warning mb-3 mt-3"
            role="alert"
            v-if="warning != ''"
          >
            {{ warning }}
          </div>
          <div class="fv-row mb-10">
            <label class="form-label fw-bolder text-gray-900 fs-6">Email</label>
            <input
              class="form-control form-control-solid"
              type="email"
              v-model="email"
              autocomplete="off"
            />
            <p
                class="message"
                :class="{ error: v$.email.$errors.length }"
                v-for="error of v$.email.$errors"
                :key="error"
              >
                {{ error.$message }}
              </p>
          </div>

          <div class="d-flex flex-wrap justify-content-center pb-lg-0">
            <button
              type="button"
              id="kt_password_reset_submit"
              class="btn btn-lg btn-primary fw-bolder me-4"
              @click="sendResetLink"
            >
              <span class="indicator-label" v-if="!loading">Submit</span>
              <span v-else
                >Please wait...
                <span
                  class="spinner-border spinner-border-sm align-middle ms-2"
                ></span
              ></span>
            </button>
            <router-link
              to="/login"
              class="btn btn-lg btn-light-primary fw-bolder"
              >Cancel</router-link
            >
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { email, required } from "@vuelidate/validators";
import { useVuelidate } from "@vuelidate/core";
import User from "../../api/user";
export default {
    setup: () => ({ v$: useVuelidate() }),
  validations() {
    return {
     
      email: { required, email },
    };
  },
  data() {
    return {
      email: "",
      loading: false,
      warning: "",
    };
  },
  methods: {
    sendResetLink() {
      //   this.v$.$touch();
      // if (this.v$.$invalid) {
      //   return;
      // }
      this.loading = true;
      User.forgotPassword({ email: this.email })
        .then((res) => {
          
          this.loading = false;
          this.warning = res.data.message;
        })
        .catch((err) => {
          this.loading = false;
          this.warning = err.response.data.message;
        });
    },
  },
};
</script>