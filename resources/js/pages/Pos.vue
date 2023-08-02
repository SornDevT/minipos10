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
                    <input type="text" id="customer-name" v-model="customer_name" class=" form-control mb-2" placeholder="...." >
                    <label for="customer-tel"> ເບີໂທ: </label>
                    <input type="text" id="customer-tel" v-model="customer_tel" class=" form-control" placeholder="...." >
                    <hr>
                    <div class="d-flex justify-content-between fs-4 text-info fw-bold">
                        <span>ລວມຍອດເງິນ:</span>
                        <span>{{ formatPrice(TotalAmount)}} ກີບ</span>
                    </div>
                    <button class=" btn btn-info w-100 fw-bold mb-3" :disabled="!TotalAmount" @click="ConfirmToPay()" >ຊຳລ່ະເງິນ</button>
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
                                <td>{{ formatPrice(list.price_sell) }}<br>
                                    <i class='bx bxs-minus-circle text-warning cursor-pointer' @click="RemoveProduct(list.id)"></i> {{ list.order_amount }} <i class='bx bxs-plus-circle text-info cursor-pointer' @click="AddProduct(list.id)"></i> | <i class='bx bxs-trash text-danger cursor-pointer' @click="DeleteProduct(list.id)"></i></td>
                                <td>{{ formatPrice(list.order_amount*list.price_sell) }}</td>
                            </tr>
                           
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ConfirmToPayModal" aria-labelledby="modalToggleLabel" tabindex="-1" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalToggleLabel">ການຊຳລ່ະເງິນ</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class=" d-flex justify-content-between">
                    <span>ລວມຍອດເງິນ:</span>
                    <span>{{ formatPrice(TotalAmount)}} ກີບ</span>
                  </div>
                  <div class=" d-flex justify-content-between">
                    <span>ຮັບເງິນນຳລູກຄ້າ:</span>
                    <span>{{ formatPrice(CashAmount) }} ກີບ</span>
                  </div>
                  <div class=" d-flex justify-content-between text-danger " v-if="CashAmount-TotalAmount>0">
                    <span>ເງິນທອນ:</span>
                    <span>{{ formatPrice(CashAmount-TotalAmount)  }} ກີບ</span>
                  </div>
                  <div class=" d-flex justify-content-end">
                    <cleave :options="options" class=" form-control text-end" v-model="CashAmount" placeholder="...." style=" width: 200px;" />
                </div>

                <div class=" d-flex justify-content-center">

                    <div class="row" style=" width: 250px; ">
                        <div class=" col-md-4 mt-2">
                            <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(1)" >1</button>
                        </div>
                        <div class=" col-md-4 mt-2">
                            <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(2)" >2</button>
                        </div>
                        <div class=" col-md-4 mt-2">
                            <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(3)" >3</button>
                        </div>
                        <div class=" col-md-4 mt-2">
                            <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(4)" >4</button>
                        </div>
                        <div class=" col-md-4 mt-2">
                            <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(5)" >5</button>
                        </div>
                        <div class=" col-md-4 mt-2">
                            <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(6)" >6</button>
                        </div>
                        <div class=" col-md-4 mt-2">
                            <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(7)" >7</button>
                        </div>
                        <div class=" col-md-4 mt-2">
                            <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(8)" >8</button>
                        </div>
                        <div class=" col-md-4 mt-2">
                            <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(9)" >9</button>
                        </div>
                        <div class=" col-md-4 mt-2">
                            <button class="btn btn-primary" style=" width: 60px;" @click="AddNum('00')" >00</button>
                        </div>
                        <div class=" col-md-4 mt-2">
                            <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(0)" >0</button>
                        </div>
                        <div class=" col-md-4 mt-2">
                            <button class="btn btn-danger" style=" width: 60px;" @click="AddNum('-')" ><i class='bx bx-left-arrow-alt'></i></button>
                        </div>
                   
                
                    </div>

                </div>

                </div>
                
                <div class="modal-footer d-flex justify-content-center">
                  <button class="btn btn-success" @click="Pay()" :disabled="!(CashAmount>=TotalAmount)" >ຍຶນຍັນຊຳລະເງິນ</button>
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
            customer_name:'',
            customer_tel:'',
            CashAmount:'',
            StoreData:[],
            ListOrder:[],
            list_page:20,
            sort:'asc',
            search:"",
            url: window.location.origin,
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
        };
    },

    mounted() {
        
    },

    computed:{
        TotalAmount(){
            let calculator = this.ListOrder.reduce((num,item) => parseInt(num) + (parseInt(item.price_sell) * parseInt(item.order_amount)),0)
            return calculator;
        }
    },

    methods: {
        AddNum(num){
            if(num == '-'){
                this.CashAmount = this.CashAmount.slice(0,-1)
            } else {
                this.CashAmount = this.CashAmount + num
            }
        },
        ConfirmToPay(){

            $('#ConfirmToPayModal').modal('show')

        },
        Pay(){

            axios.post("api/transection/add",{
                customer_name: this.customer_name,
                customer_tel: this.customer_tel,
                listorder: this.ListOrder,
                acc_type:'income'
            },{ headers:{ "content-type":"multipart/form-data", Authorization: 'Bearer '+ this.store.get_token}}).then((res)=>{
                 if(res.data.success){

                    this.customer_name = ''
                    this.customer_tel = ''
                    this.ListOrder = []
                    this.CashAmount = ''
                    this.GetAllStore()
                    $('#ConfirmToPayModal').modal('hide')

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

        },
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