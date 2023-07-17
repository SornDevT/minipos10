<template>
      <div class="d-flex justify-content-center">
        <div class="authentication-wrapper authentication-basic container-p-y col-md-4">
    <div class="authentication-inner">
      <!-- Register -->
      <div class="card">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center">
            <a href="index.html" class="app-brand-link gap-2">
              <span class="app-brand-logo demo">


</span>
              <span class="app-brand-text demo text-body fw-bolder">MiniPOS</span>
            </a>
          </div>
          <!-- /Logo -->
          <h4 class="mb-2">ລົງທະບຽນບັນຊີໃໝ່</h4>

          <div class="mb-3">
              <label for="name" class="form-label">ຊື່ຜູ້ໃຊ້:</label>
              <input type="text" class="form-control" id="name" v-model="name"  placeholder="ກະລຸນາປ້ອນຊື່ຜູ້ໃຊ້..." autofocus="">
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">ອີເມວລ໌:</label>
              <input type="text" class="form-control" id="email"  v-model="email" placeholder="ກະລຸນາປ້ອນອີເມວລ໌..." autofocus="">
            </div>
            <div class="mb-3 form-password-toggle">
                <label class="form-label" for="password">ລະຫັດຜ່ານ:</label>
        
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" v-model="password" name="password" placeholder="············" aria-describedby="password">
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
            </div>
            <div class="mb-3 form-password-toggle">
                <label class="form-label" for="password">ຍືນຍັນລະຫັດຜ່ານ:</label>
        
              <div class="input-group input-group-merge">
                <input v-model="password2" type="password" id="password" class="form-control"  name="password" placeholder="············" aria-describedby="password">
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
            </div>

            <div class="alert alert-warning" role="alert" v-if="show_error">
                  {{ text_error }}
            </div>
            
            <div class="mb-3">
              <button class="btn btn-primary d-grid w-100" @click="register()" >ລົງທະບຽນ</button>
            </div>
    

          <p class="text-center">
            <span>ມີຊື່ຜູ້ໃຊ້ແລ້ວ </span>
            <router-link to="/login">
              <span>ໄປໜ້າ ເຂົ້າລະບົບ</span>
            </router-link>
          </p>
        </div>
      </div>
      <!-- /Register -->
    </div>
  </div>
    </div>
</template>

<script>

import axios from 'axios';

export default {
    name: 'Minipos10Register',

    data() {
        return {
            name:'',
            email:'',
            password:'',
            password2:'',
            show_error: false,
            text_error:''
        };
    },

    mounted() {
        
    },

    methods: {
        register(){
                // alert('Register')
            if(this.name == '' || this.email == '' || this.password == '' ){
                this.show_error = true;
                this.text_error = 'ກະລຸນາປ້ອນຂໍ້ມູນໃຫ້ຄົບຖ້ວນ';
            } else {

                if(this.password.length>5){

                    if(this.password == this.password2){
                        this.show_error = false;
                        this.text_error = '';

                        // ສົ່ງຂໍ້ມູນໄປລົງທະບຽນ

                        axios.post('api/register',{
                            name: this.name,
                            email: this.email,
                            password: this.password
                        }).then((res)=>{
                             console.log(res.data.success);
                             if(res.data.success){
                              this.show_error = false;
                               this.text_error = '';

                               this.$router.push("/login");

                             } else {
                              // console.log('err')
                               this.show_error = true;
                               this.text_error = res.data.message;
                             }

                        }).catch((err)=>{
                            console.log(err)
                        });

                    } else {
                        this.show_error = true;
                    this.text_error = 'ລະຫັດຜ່ານບໍ່ກົງກັນ!';
                    }

                } else {
                    this.show_error = true;
                    this.text_error = 'ລະຫັດຜ່ານຕ້ອງຫລາຍກ່ວາ 5 ຕົວອັກສອນ!';
                }   

                
            }
        }
    },
};
</script>

<style lang="scss" scoped>

</style>