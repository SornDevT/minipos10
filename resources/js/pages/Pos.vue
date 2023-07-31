<template>
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class=" card-body">
                    <input type="text" class=" form-control" v-model="search" @keyup.enter="GetAllStore()" placeholder="ຄົ້ນຫາ...">
                </div>
            </div>
            <div class="row" style=" overflow: auto; height: 68vh;">
                    
                    <div class="col-md-3 cursor-pointer" v-for="list in StoreData.data" :key="list.id">
                        <div class="card mb-3" @click="AddProduct(list.id)">
                            <span v-for="item in ListOrder" :key="item.id"> 
                                <span class="num-img" v-if="list.id == item.id"> {{ item.order_amount }} </span>
                            </span>
                            
                            <img :src="url+'/assets/img/'+list.image" v-if="list.image"  class="img-cover">
                            <img :src="url+'/assets/img/no_image.jpg'" v-else  class="img-cover">

                            <div class=" card-body p-1 text-center">
                                {{ list.name }}
                                <p class="card-text">{{ formatPrice(list.price_sell) }} ກີບ</p>
                            </div>
                        </div>
                    </div>
                    
            </div>
        </div>
        <div class="col-md-4">
            <div class="card" style=" height: 80vh; overflow: auto;" >
                <div class="card-body">
                    <label for="customer-name"> ຊື່ລູກຄ້າ: </label>
                    <input type="text" id="customer-name" class=" form-control mb-2" placeholder="...." >
                    <label for="customer-tel"> ເບີໂທ: </label>
                    <input type="text" id="customer-tel" class=" form-control" placeholder="...." >
                    <hr>
                    <div class="d-flex justify-content-between fs-4 text-info fw-bold">
                        <span>ລວມຍອດເງິນ:</span>
                        <span>120.000 ກີບ</span>
                    </div>
                    <button class=" btn btn-info w-100 fw-bold mb-3">ຊຳລ່ະເງິນ</button>
                    <!-- {{ ListOrder }} -->
                    <table class=" table table-bordered">
                        <thead>
                            <tr>
                                <th>ລາຍການ</th>
                                <th>ລາຄາ</th>
                                <th>ລວມຍອດ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="list in ListOrder" :key="list.id">
                                <td>{{ list.name }}</td>
                                <td>{{ list.price_sell }}<br>
                                    <i class='bx bxs-minus-circle text-warning cursor-pointer' @click="RemoveProduct(list.id)"></i> {{ list.order_amount }} <i class='bx bxs-plus-circle text-info cursor-pointer' @click="AddProduct(list.id)"></i> | <i class='bx bxs-trash text-danger cursor-pointer' @click="DeleteProduct(list.id)"></i></td>
                                <td>{{ list.order_amount*list.price_sell }}</td>
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
    name: 'Minipos10Pos',
    setup(){
        const store = useStore()
        return {store}
    },
    data() {
        return {
            StoreData:[],
            ListOrder:[],
            list_page:20,
            sort:'asc',
            search:"",
            url: window.location.origin
        };
    },

    mounted() {
        
    },

    methods: {
        AddProduct(id){
            // console.log(id)

            let item = this.StoreData.data.find((i)=> i.id == id)
            // console.log(item)

            if(item.amount>0){

                let list_order = this.ListOrder.find((i)=> i.id == id)

                    if(list_order){

                        let old_order = list_order.order_amount // ຈຳນວນລາຍການທີ່ໄດ້ເລືອກ
                        if((item.amount - old_order)>0){
                            // ອັບເດດຈຳນວນ
                            list_order.order_amount++
                        } else {
                            this.$swal('ສິນຄ້າ','ສິນຄ້າໂຕນີ້ໝົດແລ້ວ!','error')
                        }
                        

                    } else {

                        // ເພີ່ມຂໍ້ມູນ ເຂົ້າໄປໃໝ່
                        this.ListOrder.push({
                            id: item.id,
                            name: item.name,
                            order_amount: 1,
                            price_sell: item.price_sell
                        })
                    }
            } else {
                this.$swal('ສິນຄ້າ','ສິນຄ້າໂຕນີ້ໝົດແລ້ວ!','error')
            }

           

            
        },
        RemoveProduct(id){
            let list_order = this.ListOrder.find((i)=> i.id == id)
            if(list_order){
                list_order.order_amount--
                if(list_order.order_amount<1){
                    this.ListOrder.splice(this.ListOrder.map((i)=>i.id).indexOf(id),1)
                }
            }
        },
        DeleteProduct(id){
            this.ListOrder.splice(this.ListOrder.map((i)=>i.id).indexOf(id),1)
        },
        formatPrice(value) {
            let val = (value / 1).toFixed(0).replace(",", ".");
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
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

<style  scoped>
    .img-cover{
        object-fit: cover;
        width: 100%;
        height: 120px;
        object-position: center;
    }
    .num-img{
        position: absolute;
        top: 0px;
        right: 0px;
        padding: 5px;
        background-color: crimson;
        color: white;
        border-radius: 0px 10px 0px 10px;
    }
</style>