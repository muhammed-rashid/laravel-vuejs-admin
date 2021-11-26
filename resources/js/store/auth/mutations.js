export default {
    Login(state, payload) {

        state.userId = payload.id;
        state.userName = payload.name;
        state.role = payload.role;
        state.auth = payload.auth;
        state.email = payload.email;
        state.verified = payload.verified;
      
    },
};