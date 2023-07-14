import { defineStore } from "pinia";

export const useStore = defineStore('auth',{
    state:() => ({
        token:null,
        user:null
    }),
    getters:{
        get_token: (state) => state.token,
        get_user: (state) => state.user,
    },
    actions:{
        set_token(token_new){
            this.token = token_new
        },
        set_user(user_new){
            this.user = user_new
        },
        remove_token(){
            this.token = null
        },
        remove_user(){
            this.user = null
        }
    }

});