<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;

class StoreController extends Controller
{
    //
    public function __construct(){
        $this->middleware("auth:api");
    }


    public function index(){
        
        $list_page = \Request::get("list_page");
        $sort = \Request::get("sort");
        $search = \Request::get("search");
        

        // ດຶງຂໍ້ມູນໂດຍມີການ ຈັດລຽງຂໍ້ມູນ
        $store = Store::orderBy("id",$sort)
        ->where("name","LIKE","%{$search}%")
        ->paginate($list_page)
        ->toArray();


        // ດຶງຂໍ້ມູນໂດຍມີການ ຈັດລຽງຂໍ້ມູນ
        // $store = Store::orderBy("id",$sort)
        // ->paginate($list_page)
        // ->toArray();



        return array_reverse($store);

        // ດຶງຂໍ້ມູນຈາກຕາຕະລາງທຳມະດາ

        // $store = Store::orderBy("id","asc")
        // ->get();

        // return $store;

    }


    public function add(Request $request){
        try{// ລະບົບທຳການຄຳນວນ-ເຮັດວຽກ

            //return $request->file('image');

            if($request->file('image')){
                // ຖ້າຫາກວ່າມີ file ຊື່ image ສົ່ງມາໃຫ້ເຮັດວຽກຢູ່ນີ້
                $upload_path = "assets/img";
                $generate_new_name = time().".".$request->image->getClientOriginalExtension();
                $request->image->move(public_path($upload_path),$generate_new_name);
            } else {
                $generate_new_name = "";
            }

            $store = new Store([
                'name' => $request->name,
                'image' => $generate_new_name,
                'amount' => $request->amount,
                'price_buy' => $request->price_buy,
                'price_sell' => $request->price_sell,

            ]);
            $store->save();


            $success = true;
            $message = "ບັນທຶກຂໍ້ມູນ ສຳເລັດ!";
 

        } catch (\Illuminate\Database\QueryException $ex){ 
            // ດຶງຂໍ້ຄວາມ error ຈາກລະບົບອອກມາ
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            "success" => $success,
            "message" => $message,
        ];

        return response()->json($response);
    }

    public function edit($id){

        $store = Store::find($id);
        return $store;

    }

    public function update($id,Request $request){

        try{// ລະບົບທຳການຄຳນວນ-ເຮັດວຽກ

            $store = Store::find($id);
            $upload_path = "assets/img";

            if($request->file('image')){
                // ຖ້າຫາກວ່າມີ file ຊື່ image ສົ່ງມາໃຫ້ເຮັດວຽກຢູ່ນີ້

                // ກວດຊອບຮູບພາບເກົ່າ ແລ້ວລຶບອອກ
                if($store->image){
                    if(file_exists($upload_path."/".$store->image)){
                        unlink($upload_path."/".$store->image);
                    }
                }

                $generate_new_name = time().".".$request->image->getClientOriginalExtension();
                $request->image->move(public_path($upload_path),$generate_new_name);

                $store->update([
                    'name' => $request->name,
                    'image' => $generate_new_name,
                    'amount' => $request->amount,
                    'price_buy' => $request->price_buy,
                    'price_sell' => $request->price_sell,
                ]);


            } else {
          
                if($request->image){ // ກໍລະນີໂຕແປ image ມີຊື່ຮູບພາບສົ່ງມາ ຈະບໍ່ມີການແກ້ໄຂຮູບພາບ

                    $store->update([
                        'name' => $request->name,
                        // 'image' => '',
                        'amount' => $request->amount,
                        'price_buy' => $request->price_buy,
                        'price_sell' => $request->price_sell,
                    ]);

                } else { // ກໍລະນີໂຕແປ image ບໍ່ມີຊື່ຮູບພາບສົ່ງມາ ເຮົາຈະທຳການລຶບຮູບພາບ

                    // ກວດຊອບຮູບພາບເກົ່າ ແລ້ວລຶບອອກ
                    if($store->image){
                        if(file_exists($upload_path."/".$store->image)){
                            unlink($upload_path."/".$store->image);
                        }
                    }


                    $store->update([
                        'name' => $request->name,
                        'image' => '',
                        'amount' => $request->amount,
                        'price_buy' => $request->price_buy,
                        'price_sell' => $request->price_sell,
                    ]);

                }


                
            }


            

            $success = true;
            $message = "ອັບເດດຂໍ້ມູນ ສຳເລັດ!";
 

        } catch (\Illuminate\Database\QueryException $ex){ 
            // ດຶງຂໍ້ຄວາມ error ຈາກລະບົບອອກມາ
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            "success" => $success,
            "message" => $message,
        ];

        return response()->json($response);

    }

    public function delete($id){

        try{// ລະບົບທຳການຄຳນວນ-ເຮັດວຽກ

            $store = Store::find($id);
            $upload_path = "assets/img";
             // ກວດຊອບຮູບພາບ
             if($store->image){
                if(file_exists($upload_path."/".$store->image)){
                    unlink($upload_path."/".$store->image);
                }
            }

            $store->delete();

            $success = true;
            $message = "ລຶບຂໍ້ມູນ ສຳເລັດ!";
 

        } catch (\Illuminate\Database\QueryException $ex){ 
            // ດຶງຂໍ້ຄວາມ error ຈາກລະບົບອອກມາ
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            "success" => $success,
            "message" => $message,
        ];

        return response()->json($response);
    }

}
