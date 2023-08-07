<template>
    <div class="card">
  <h5 class="card-header">ລາຍການເຄື່ອນໄຫວ ທຸລະກຳ</h5>
  <div class="card-body">
    <div class="table-responsive text-nowrap">
        <div class=" d-flex justify-content-between mb-2">
            <div class="d-flex align-items-center">

              <div @click="ChangeSort()" class="cursor-pointer d-flex align-items-center">
                <i class='bx bx-sort-up fs-4 me-2' v-if="sort=='asc'"></i>
                <i class='bx bx-sort-down fs-4 me-2' v-if="sort=='desc'"></i>
              </div>
                
                <select class="form-select" v-model="list_page" @change="GetTransection()">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                </select>
            </div>
            <div class=" d-flex">
                <!-- {{  month_type }} -->
                <!-- {{ dmy }} -->
            <div class="btn-group me-2" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-secondary" @click="month_type='m'"> <i class='bx bx-right-arrow-alt' v-if="month_type=='m'"></i> ເດືອນ</button>
                <button type="button" class="btn btn-secondary" @click="month_type='y'" ><i class='bx bx-right-arrow-alt' v-if="month_type=='y'"></i> ປີ</button>
              </div>
                <input type="date" class=" form-control me-2"  v-model="dmy" >
                <button class=" btn btn-success " @click="GetTransection()" > <i class='bx bx-plus'></i> ສະແດງຂໍ້ມູນ</button>
            </div>
        </div> 
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>ວັນທີ່</th>
            <th>ເລກທີ່ທຸລະກຳ</th>
            <th>ປະເພດທຸລະກຳ</th>
            <th>ລາຍລະອຽດ</th>
            <th>ມູນຄ່າ</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="list in Transection.data" :key="list.id">
                <td>{{ date(list.created_at) }}</td>
                <td>{{ list.tran_id }}</td>
                <td>{{ list.tran_type }}</td>
                <td>{{ list.tran_detail }}</td>
                <td class="text-end">{{ formatPrice(list.price) }}</td>
          </tr>

        </tbody>
      </table>
      <pagination :pagination="Transection" :offset="4" @paginate="GetTransection($event)" />
    </div>
  </div>
</div>
</template>

<script>
import axios from 'axios';
import { useStore } from '../store/auth';
import moment from "moment"
export default {
    name: 'Minipos10Transection',
    setup(){
        const store = useStore()
        return {store}
    },
    data() {
        return {
            sort:'desc',
            list_page:10,
            dmy:'',
            month_type:'m',
            Transection:[],
        };
    },

    mounted() {
        
    },
    components:{moment},
    methods: {
        date(value){
            return moment(value).format("DD/MM/YYYY")
        },
        formatPrice(value) {
            let val = (value / 1).toFixed(0).replace(",", ".");
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },
        ChangeSort(){
        if(this.sort == "asc"){
          this.sort = "desc"
        } else {
          this.sort = "asc"
        }
        this.GetTransection()
      },
        GetTransection(page){
            axios.post(`api/transection?page=${page}&lp=${this.list_page}&sort=${this.sort}`,{
                month_type: this.month_type,
                dmy: this.dmy
            }).then((res)=>{

                this.Transection = res.data

            }).catch((error)=>{
                console.log(error)
                if(error){
                    if(error.response.status == 401){
                        this.store.remove_token()
                        this.store.remove_user()
                        localStorage.removeItem("web_token")
                        localStorage.removeItem("web_user")
                        this.$router.push("/login")
                    }
                  }
            })
        }
    },
    created(){
        this.GetTransection()
    }
};
</script>

<style lang="scss" scoped>

</style>