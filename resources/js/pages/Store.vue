<template>
    <div>
        <div class="card">
  <h5 class="card-header">ສະຕ໋ອກສິນຄ້າ</h5>
  <div class="card-body">

    <!-- <button @click="showAlert">Hello world</button> -->

    <div v-if="ShowForm">
        <div class=" d-flex justify-content-end mb-2">
            <button class="btn btn-success me-2" :disabled="CheckForm" @click="SaveStore()"> <i class='bx bx-save'></i> ບັນທຶກ</button>
            <button class="btn btn-secondary" @click="CancelStore()"><i class='bx bx-x'></i> ຍົກເລີກ</button>                
        </div>
        <div class="row">
            <div class=" col-md-4 text-center" style="position: relative;">
              <button type="button" v-if="FormStore.image" @click="RemoveImg()" class="btn rounded-pill btn-icon btn-danger" style="position: absolute; top: 0px;  right: 4px;">
                <i class='bx bx-trash fs-4'></i>
              </button>
              <img :src="image_pre" class=" rounded cursor-pointer" @click="$refs.img_store.click()" style=" width: 80%;" alt="">
              <input type="file" ref="img_store" style=" display: none;" @change="onSelect"> 
            </div>
            <div class="col-md-8"> 

           <!-- {{ FormStore  }} <br> -->
          <label for="name" class="form-label fs-6">ຊື່ສິນຄ້າ <span class=" text-danger">*</span></label>
          <input type="text" class="form-control mb-2" id="name" v-model="FormStore.name" placeholder="ກະລຸນາປ້ອນຊື່..." >

          <label for="amount" class="form-label fs-6">ຈຳນວນ <span class=" text-danger">*</span></label>
          <cleave :options="options" class="form-control mb-2" id="amount" v-model="FormStore.amount" placeholder="ກະລຸນາປ້ອນຈຳນວນ..." />
         <div class="row">
            <div class="col-md-6">
                <label for="price_sell" class="form-label fs-6">ລາຄາຊື້ <span class=" text-danger">*</span></label>
                <cleave :options="options"  class="form-control mb-2" v-model="FormStore.price_buy" id="price_sell" placeholder="ກະລຸນາປ້ອນລາຄາຊື້..." />
            </div>
            <div class="col-md-6">
                <label for="price_buy" class="form-label fs-6">ລາຄາຂາຍ <span class=" text-danger">*</span></label>
          <cleave :options="options" class="form-control mb-2" id="price_buy" v-model="FormStore.price_sell" placeholder="ກະລຸນາປ້ອນລາຄາຂາຍ..." />
            </div>
         </div>


            </div>
        </div>
    </div>


    <div v-if="!ShowForm" class="table-responsive text-nowrap">
        <div class=" d-flex justify-content-between mb-2">
            <div class="d-flex align-items-center">

              <div @click="ChangeSort()" class="cursor-pointer d-flex align-items-center">
                <i class='bx bx-sort-up fs-4 me-2' v-if="sort=='asc'"></i>
                <i class='bx bx-sort-down fs-4 me-2' v-if="sort=='desc'"></i>
              </div>
                
                <select class="form-select" v-model="list_page" @change="GetAllStore()">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                </select>
            </div>
            <div class=" d-flex">
                <input type="text" class=" form-control me-2" v-model="search" placeholder="ຄົ້ນຫາ...." @keyup.enter="GetAllStore()">
                <button class=" btn btn-info " @click="AddStore()"> <i class='bx bx-plus'></i> ເພີ່ມໃໝ່</button>
            </div>
        </div> 
      <table class="table table-bordered">
        <thead>
          <tr>
            <th width="80">ID</th>
            <th width="120">ຮູບ</th>
            <th>ຊື່ສິນຄ້າ</th>
            <th>ລາຄາຊື້</th>
            <th>ຈັດການ</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="list in StoreData.data" :key="list.id">
            <td>{{ list.id }}</td>
            <td>
              <img :src="url+'/assets/img/'+list.image" v-if="list.image" class=" rounded w-100" alt="">
              <img :src="url+'/assets/img/no_image.jpg'" v-else class=" rounded w-100" alt="">
            </td>
            <td> {{ list.name  }} </td>
            <td> {{ formatPrice(list.price_buy) }} ກີບ </td>
            <td>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="javascript:void(0);" @click="EditStore(list.id)"><i class="bx bx-edit-alt me-1"></i> ແກ້ໄຂ</a>
                  <a class="dropdown-item" href="javascript:void(0);" @click="DeleteStore(list.id)" ><i class="bx bx-trash me-1"></i> ລຶບ</a>
                </div>
              </div>
            </td>
          </tr>
         

        </tbody>
      </table>
      <pagination :pagination="StoreData" :offset="4" @paginate="GetAllStore($event)" />
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
            list_page:5,
            sort:'asc',
            search:"",
            StoreData:[],
            ShowForm: false,
            FormType: true,
            EditID:'',
            FormStore:{
                name:'',
                image:'',
                amount:'',
                price_sell:'',
                price_buy:''
            },
            options: {
                  // prefix: '₭ ',
                  numeral: true,
                  numeralPositiveOnly: true,
                  noImmediatePrefix: true,
                  rawValueTrimPrefix: true,
                  numeralIntegerScale: 10,
                  numeralDecimalScale: 2,
                  numeralDecimalMark: ',',
                  delimiter: '.'
                },
            image_pre: window.location.origin+'/assets/img/add-img.png',
            url: window.location.origin
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
      formatPrice(value) {
            let val = (value / 1).toFixed(0).replace(",", ".");
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },
      showAlert() {
        // Use sweetalert2
        // this.$swal('Hello Vue world!!!');

        this.$swal({
          toast: true,
          position: 'top-end',
          icon: 'success',
          title: 'Your work has been saved',
          showConfirmButton: false,
          timer: 1500
        });

      },
      RemoveImg(){
        this.FormStore.image = ""
        this.image_pre = window.location.origin+'/assets/img/add-img.png'
      },
      onSelect(event){
        // console.log(event)
        this.FormStore.image = event.target.files[0];
        let reader = new FileReader();
        reader.readAsDataURL(this.FormStore.image);
        reader.addEventListener("load",function(){
          this.image_pre = reader.result;
        }.bind(this),false);
      },
      ChangeSort(){
        if(this.sort == "asc"){
          this.sort = "desc"
        } else {
          this.sort = "asc"
        }
        this.GetAllStore()
      },
        AddStore(){
            this.FormStore.name = ''
            this.FormStore.image = ''
            this.FormStore.amount = ''
            this.FormStore.price_sell = ''
            this.FormStore.price_buy = ''
            this.ShowForm = true
            this.image_pre = window.location.origin+'/assets/img/add-img.png'
        },
        CancelStore(){
            this.ShowForm = false
        },
        EditStore(id){
          this.EditID = id
          this.FormType = false 

          axios.get(`api/store/edit/${id}`,{ headers:{ Authorization: 'Bearer '+ this.store.get_token}}).then((res)=>{
                 
                this.ShowForm = true

                console.log(res.data)

                this.FormStore = res.data

                if(res.data.image){
                  this.image_pre = this.url + '/assets/img/' + res.data.image
                } else {
                  this.image_pre = this.url + '/assets/img/add-img.png'
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


        },
        DeleteStore(id){


          this.$swal({
              title: 'ທ່ານແນ່ໃຈບໍ່?',
              text: "ທີ່ຈະລຶບຂໍ້ມູນນີ້!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'ຕົກລົງ',
              cancelButtonText:'ຍົກເລີກ'
            }).then((result) => {
              if (result.isConfirmed) {

                // ສົ່ງ id ໄປລຶບຂໍ້ມູນ
                axios.delete(`api/store/delete/${id}`,{ headers:{ Authorization: 'Bearer '+ this.store.get_token}}).then((res)=>{
                 
                 if(res.data.success){
                    console.log(res.data.message)
                    this.GetAllStore()

                    this.$swal(
                      'Deleted!',
                      res.data.message,
                      'success'
                    )

                 } else {
                  this.$swal(
                      'Deleted!',
                      res.data.message,
                      'error'
                    )
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

              }
            });

        },
        SaveStore(){
            if(this.FormType){ 
                // ເພີ່ມຂໍ້ມູນໃໝ່

                axios.post("api/store/add",this.FormStore,{ headers:{ "content-type":"multipart/form-data", Authorization: 'Bearer '+ this.store.get_token}}).then((res)=>{
                 if(res.data.success){

                    this.ShowForm = false
                    this.GetAllStore()

                    this.$swal({
                      toast: true,
                      position: 'top-end',
                      icon: 'success',
                      title: res.data.message,
                      showConfirmButton: false,
                      timer: 2500
                    });

                 } else {

                  this.$swal({
                      position: 'center',
                      icon: 'error',
                      title: res.data.message,
                      showConfirmButton: false,
                      timer: 4500
                    });

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

                axios.post(`api/store/update/${this.EditID}`,this.FormStore,{ headers:{ "content-type":"multipart/form-data", Authorization: 'Bearer '+ this.store.get_token}}).then((res)=>{
                 if(res.data.success){

                    this.ShowForm = false
                    this.GetAllStore()

                    this.$swal({
                      toast: true,
                      position: 'top-end',
                      icon: 'success',
                      title: res.data.message,
                      showConfirmButton: false,
                      timer: 2500
                    });

                 } else{
                  this.$swal({
                      position: 'center',
                      icon: 'error',
                      title: res.data.message,
                      showConfirmButton: false,
                      timer: 4500
                    });
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

            }
        },
        GetAllStore(page){

            axios.get(`api/store?page=${page}&list_page=${this.list_page}&sort=${this.sort}&search=${this.search}`,{ headers:{ Authorization: 'Bearer '+ this.store.get_token}}).then((res)=>{
                 
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
    watch:{
      search(){
        if(this.search == ''){
          this.GetAllStore()
        }
      }
    },
    created(){
        this.GetAllStore()
    }
};
</script>

<style lang="scss" scoped>

</style>