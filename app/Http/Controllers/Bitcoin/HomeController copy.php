<?php
namespace App\Http\Controllers\Bitcoin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use App\Bitcoin;
class HomeController extends Controller
{
    public function __construct()
    {
       // $this->middleware('auth');
    }

    function date_range($first, $last, $step = '+1 day', $output_format = 'm/d/Y' ) {
        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);
        while( $current <= $last ) {
    
            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }
        return $dates;
    }

    public function index(Request $request){
        $daterange=$request->input("daterange");
        if(!isset($daterange)){
            $date = date('m/d/Y'); 
            $daterange=$date." - ".$date;
        }
        $a = explode(" - ",$daterange);
        if($a[0]==$a[1]){
            $date=$a[0];
            $bit_model = new Bitcoin();
            $bitdata =json_decode($bit_model->getHourlyData($date));
            $ams=[];
            $tams=[];
            foreach($bitdata as $row){
                $array1 = DB::table('result')->select(DB::raw('sum(amount_usd) as f0'))->where('dtime', '=',$row->dtime)->first();
                if(!isset($array1))continue;
                $array2 = DB::table('result')->select('timestamp')->where('dtime', '=',$row->dtime)->first();
                if(!isset($array2))continue;
                $array3 = DB::table('result')->select(DB::raw('sum(amount_usd) as f0'))->where([['dtime', '=',$row->dtime],['from_owner_type','=', 'exchange']])->first();
                if(!isset($array3))continue;;
                $array4 = DB::table('result')->select(DB::raw('sum(amount_usd) as f0'))->where([['dtime', '=',$row->dtime],['to_owner_type','=', 'exchange']])->first();
                if(!isset($array4))continue;
                $row->rate=sprintf("%01.2f", $row->rate);
                $row->amount_usd=sprintf("%01.2f", $array1->f0);
                $row->timestamp=$array2->timestamp;
                $row->amount_usd_from=sprintf("%01.2f", $array3->f0);
                $row->amount_usd_to=sprintf("%01.2f", $array4->f0);
                $ams[]=$row;
            }
           // print_r($ams);die;
           return view('Bitcoin.pages.index',["selected"=>'/',"daterange"=>$daterange,'bits' =>$ams]); 
        }else{
            $bit_model = new Bitcoin();
            $dates=$this->date_range($a[0],$a[1]);
            $result_arrays=$bit_model->getRangeData($dates);  
            //print_r($result_arrays); 
            return view('Bitcoin.pages.index1',["selected"=>'/',"daterange"=>$daterange,'bits' =>$result_arrays]);   
        }

    }

    public function transaction(Request $request){
        $daterange=$request->input("daterange");
        if(!isset($daterange)){
            $date = date('m/d/Y'); 
            $daterange=$date." - ".$date;
        }
        $a = explode(" - ",$daterange);

        $bit_model = new Bitcoin();
        $dates=$this->date_range($a[0],$a[1]);
        $result_arrays=$bit_model->getRangeResultData($dates);   
        return view('Bitcoin.pages.transaction',["selected"=>'transaction',"daterange"=>$daterange,'records' =>$result_arrays]);
    }

    public function cron(){
        /******check if the bitcoin data exists******/
        $service_url = "https://api.coindesk.com/v1/bpi/currentprice.json";
        $ch = curl_init($service_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $content=curl_exec($ch);
        curl_close($ch);
        $bcoin=json_decode($content);//convert to scraped data to json object.
        $temp=$bcoin->time->updated;
        $strtime=explode("UTC",$temp);
        $old_date_timestamp = strtotime($strtime[0]); 
        $dtime = date('H-m/d/Y', $old_date_timestamp);//get time 
        $rate=$bcoin->bpi->USD->rate_float;           //get rate
        $bit_model = new Bitcoin();   
        if(!$bit_model->where('dtime','=',$dtime)->exists()){
            $bit_model->insert(array('dtime' => $dtime,'rate' => $rate));
        }

        $_date=date("m/d/Y");
        $_hour=date('H');
        $param=$_hour."-".$_date;
        if(DB::table('crypto')->where([['date','=',$_date],['hour','=',$_hour]])->count()==0){
            $service_url = "https://min-api.cryptocompare.com/data/top/exchanges/full?fsym=BTC&tsym=USD&limit=50&api_key=32471f6af7c712676ba843d012879b6b811366c16fef8471946e0e0530ac4c37";
            $ch = curl_init($service_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $content=curl_exec($ch);
            curl_close($ch);
            $bcoin=json_decode($content);//convert to scraped data to json object.
            $objs=$bcoin->Data->Exchanges;
            foreach($objs as $obj){
                $output=array();
                $output['date']=$_date;
                $output['hour']=$_hour;
                $i=0;$sql_val="";
                foreach($obj as $key => $value) {
                    $output[$key]=$value;
                }
                DB::table('crypto')->insert($output);
            } 
            echo "success0";   
        }

        $_total=0;
        $_date=date("m/d/Y");
        $_hour=date('H');
        $param=$_hour."-".$_date;
        if(DB::table('result')->where('dtime','=',$param)->count()>0){
            //return csrf_token();
        }else{
            $timestamp=time()-3592;
            $service_url = "https://api.whale-alert.io/v1/transactions?api_key=qyyl3RvjdDMkelzJC8s8Vxk0rQ7eFrv9&min_value=500000&start=$timestamp";
            $ch = curl_init($service_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $content=curl_exec($ch);
            curl_close($ch);
            $nodes=json_decode($content);//convert to scraped data to json object.
            $Property = 'transactions';
            if (property_exists($nodes, $Property))
            {
                $rows=$nodes->$Property;
                foreach($rows as $row){
                    $symbol=$row->symbol;
                    if($symbol!="btc")continue;
                    $hash=$row->hash;
                    $timestamp=$row->timestamp;
                    $amount_usd=$row->amount_usd;
                    $from_address=$row->from->address;
                    $from_owner_type=$row->from->owner_type;
                    if($from_owner_type=="unknown"){
                        $from_owner="unknown";
                    }else{
                        $from_owner=$row->from->owner;
                    }
                    $to_address=$row->to->address;
                    $to_owner_type=$row->to->owner_type;
                    if($to_owner_type=="unknown"){
                        $to_owner="unknown";
                    }else{
                        $to_owner=$row->to->owner;
                    }
                    $insert_array=array('dtime'=>$param, 'hash'=>$hash, 'from_address'=>$from_address, 'from_owner'=>$from_owner, 'from_owner_type'=>$from_owner_type, 'to_address'=>$to_address, 'to_owner'=>$to_owner, 'to_owner_type'=>$to_owner_type, 'amount_usd'=>$amount_usd, 'timestamp'=>$timestamp);
                    DB::table('result')->insert($insert_array);
                }
                echo "success1";
            }else{
                echo "no";
            }           
        }
    }

    public function contact(){
        //$service_url = "https://api.coingecko.com/api/v3/coins/markets?vs_currency=USD&amp;ids=bitcoin&amp;order=market_cap_desc&amp;per_page=100&amp;page=1&amp;sparkline=false";
        $service_url = "https://api.coincap.io/v2/exchanges";
       
        $ch = curl_init($service_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $content=curl_exec($ch);
        curl_close($ch);
        $bcoin=json_decode($content);//convert to scraped data to json object.
        print_r($bcoin);
        // $objs=$bcoin->Data->Exchanges;
        // foreach($objs as $obj){
        //     if (property_exists($obj, "MKTCAP"))
        //     {
        //         echo "<table border=1 cellspacing=0>";
        //         $i=0;
        //         $output="";
        //         $insert_array=array();
        //         foreach($obj as $key => $value) {
        //             $i++;
        //             echo "<tr><td>$i</td><td>$key:</td><td>$value</td></tr>";
        //         }
        //         echo "</table><br>";                
        //     }
        // }
    }

    public function crypto(Request $request)
    {
        $daterange=$request->input("daterange");
        if(!isset($daterange)){
            $date = date('m/d/Y'); 
            $daterange=$date." - ".$date;
        }
        $a = explode(" - ",$daterange);

        $bit_model = new Bitcoin();
        $dates=$this->date_range($a[0],$a[1]);
        $result_arrays=$bit_model->getRangeCryptoData($dates); 
       // print_r($result_arrays);
        return view('Bitcoin.pages.crypto',["selected"=>'crypto',"daterange"=>$daterange,'records' =>$result_arrays]);
    }
    public function vtest(){
        return view('Bitcoin.pages.vtest');
    }

    public function domains(){
        return view('Bitcoin.pages.domains');
    }

    public function hosting(){
        return view('Bitcoin.pages.hosting',["selected"=>'hosting']);
    }

    public function pricing(){
        return view('Bitcoin.pages.pricing',["selected"=>'pricing']);
    }

    public function testimonials(){
        return view('Bitcoin.pages.testimonials',["selected"=>'testimonials']);
    }


    public function getAllBitData(){
        return Bitcoin::all();
    }
}
