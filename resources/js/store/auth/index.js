import authActions from './actions.js'
import authMutations from './mutations.js'
import authGetters from './getters.js'
export default{

    state(){
        return{
            userId :'',
            auth:false,
            userName:'',
            role:'',
            email:'',
            verified:'',
            init:null
        }
    },
    actions:authActions,
    mutations:authMutations,
    getters:authGetters
}