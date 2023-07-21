<template>
    <div>
        <div class="card">
  <h5 class="card-header">Bordered Table</h5>
  <div class="card-body">

    <div v-if="ShowForm">
        <div class=" d-flex justify-content-end mb-2">
            <button class="btn btn-success me-2" :disabled="CheckForm" @click="SaveStore()"> <i class='bx bx-save'></i> ບັນທຶກ</button>
            <button class="btn btn-secondary" @click="CancelStore()"><i class='bx bx-x'></i> ຍົກເລີກ</button>                
        </div>
        <div class="row">
            <div class=" col-md-4">pic</div>
            <div class="col-md-8"> 

           {{ FormStore  }} <br>
          <label for="name" class="form-label fs-6">ຊື່ສິນຄ້າ <span class=" text-danger">*</span></label>
          <input type="text" class="form-control mb-2" id="name" v-model="FormStore.name" placeholder="ກະລຸນາປ້ອນຊື່..." >

          <label for="amount" class="form-label fs-6">ຈຳນວນ <span class=" text-danger">*</span></label>
          <input type="text" class="form-control mb-2" id="amount" v-model="FormStore.amount" placeholder="ກະລຸນາປ້ອນຈຳນວນ..." >
         <div class="row">
            <div class="col-md-6">
                <label for="price_sell" class="form-label fs-6">ລາຄາຊື້ <span class=" text-danger">*</span></label>
                <input type="text" class="form-control mb-2" v-model="FormStore.price_buy" id="price_sell" placeholder="ກະລຸນາປ້ອນລາຄາຊື້..." >
            </div>
            <div class="col-md-6">
                <label for="price_buy" class="form-label fs-6">ລາຄາຂາຍ <span class=" text-danger">*</span></label>
          <input type="text" class="form-control mb-2" id="price_buy" v-model="FormStore.price_sell" placeholder="ກະລຸນາປ້ອນລາຄາຂາຍ..." >
            </div>
         </div>


            </div>
        </div>
    </div>


    <div v-if="!ShowForm" class="table-responsive text-nowrap">
        <div class=" d-flex justify-content-between mb-2">
            <div class=" d-flex align-items-center">
                <i class='bx bx-sort-up fs-4 me-2'></i>
                <select class="form-select">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                </select>
            </div>
            <div class=" d-flex">
                <input type="text" class=" form-control me-2" placeholder="ຄົ້ນຫາ....">
                <button class=" btn btn-info " @click="AddStore()"> <i class='bx bx-plus'></i> ເພີ່ມໃໝ່</button>
            </div>
        </div> 
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>ຮູບ</th>
            <th>ຊື່ສິນຄ້າ</th>
            <th>ລາຄາຊື້</th>
            <th>ຈັດການ</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="list in StoreData.data" :key="list.id">
            <td>{{ list.id }}</td>
            <td>image</td>
            <td> {{ list.name  }} </td>
            <td> {{ list.price_buy }} </td>
            <td>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> ແກ້ໄຂ</a>
                  <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> ລຶບ</a>
                </div>
              </div>
            </td>
          </tr>
         

        </tbody>
      </table>
    </div>
  </div>
</div>
    </div>
</template>

<script>

import axios from 'axios';
import { useStore } from '../store/auth';

export default {
    name: 'Minipos10Store',
    setup(){
        const store = useStore()
        return {store}
    },
    data() {
        return {
            StoreData:[],
            ShowForm: false,
            FormType: true,
            EditID:'',
            FormStore:{
                name:'',
                imagse:'',
                amount:'',
                price_sell:'',
                price_buy:''
            },
        };
    },

    mounted() {
        
    },
    computed:{
        CheckForm(){
            if(this.FormStore.name == '' || this.FormStore.amount == '' || this.FormStore.price_buy =='' || this.FormStore.price_sell == ''){
                return true
            } else {
                return false
            }
        }
    },

    methods: {
        AddStore(){
            this.FormStore.name = ''
            this.FormStore.imagse = ''
            this.FormStore.amount = ''
            this.FormStore.price_sell = ''
            this.FormStore.price_buy = ''
            this.ShowForm = true
        },
        CancelStore(){
            this.ShowForm = false
        },
        SaveStore(){
            if(this.FormType){ 
                // ເພີ່ມຂໍ້ມູນໃໝ່

                axios.post("api/store/add",this.FormStore,{ headers:{ "content-type":"multipart/form-data", Authorization: 'Bearer '+ this.store.get_token}}).then((res)=>{
                 if(res.data.success){

                    this.ShowForm = false
                    this.GetAllStore()

                 } else {

                 }

                }).catch((error)=>{
                  console.log(error);
                  if(error){
                    if(error.response.status == 401){
                        this.store.remove_token()
                        this.store.remove_user()
                        localStorage.removeItem("web_token")
                        localStorage.removeItem("web_user")
                        this.$router.push("/login")
                    }
                  }
                });

            } else {
                // ອັບເດດຂໍ້ມູນ

            }
        },
        GetAllStore(){

            axios.get("api/store",{ headers:{ Authorization: 'Bearer '+ this.store.get_token}}).then((res)=>{
                 
                this.StoreData = res.data

                }).catch((error)=>{
                  console.log(error);
                  if(error){
                    if(error.response.status == 401){
                        this.store.remove_token()
                        this.store.remove_user()
                        localStorage.removeItem("web_token")
                        localStorage.removeItem("web_user")
                        this.$router.push("/login")
                    }
                  }
                });

        }
    },
    created(){
        this.GetAllStore()
    }
};
</script>

<style lang="scss" scoped>

</style>