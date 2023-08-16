<template>
    <div>
        <div class="row g-4 mb-4">
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span>ລາຍຮັບ</span>
            
            
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-primary">
                <i class='bx bx-download fs-4'></i>
            </span>
          </div>
        </div>
        <div class="d-flex align-items-end mt-2">
              {{ formatPrice(data_income) }} ກີບ 
              <small class="text-success">(+29%)</small>
            </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span>ລາຍຈ່າຍ</span>
            
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-danger">
                <i class='bx bx-trending-down fs-4'></i>
            </span>
          </div>
        </div>
        <div class="d-flex align-items-end mt-2">
              {{ formatPrice(data_expense)  }} ກີບ 
              <small class="text-success">(+18%)</small>
            </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span>ກຳໄລ</span>
            
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-success">
                <i class='bx bxs-objects-vertical-bottom fs-4'></i>
            </span>
          </div>
        </div>
        <div class="d-flex align-items-end mt-2">
             {{ formatPrice(data_income-data_expense) }} ກີບ 
              <small class="text-danger">(-14%)</small>
            </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span>ຍອດໃນສະຕ໋ອກ</span>
            
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-warning">
                <i class='bx bx-store-alt fs-4'></i>
            </span>
          </div>
        </div>
        <div class="d-flex align-items-end mt-2">
              {{ formatPrice(data_store) }} 
              <small class="text-success">(+42%)</small>
            </div>
      </div>
    </div>
  </div>
</div>
    </div>
</template>

<script>
import axios from 'axios';
import { useStore } from '../store/auth';
export default {
    name: 'Minipos10GrapBoard',
    setup(){
        const store = useStore()
        return {store}
    },
    data() {
        return {
            data_income:'',
            data_expense:'',
            data_store:''
        };
    },

    mounted() {
        
    },

    methods: {
        formatPrice(value) {
            let val = (value / 1).toFixed(0).replace(",", ".");
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },
        GetData(){
            axios.get('api/report/grapboard',{ headers:{ Authorization: 'Bearer '+ this.store.get_token}}).then((res)=>{
                 
                this.data_income = res.data.income
                this.data_expense = res.data.expense
                this.data_store = res.data.store

 
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
        this.GetData()
    }
};
</script>

<style lang="scss" scoped>

</style>