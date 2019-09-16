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
        $service_url = "https://api.coincap.io/v2/exchanges";
        $ch = curl_init($service_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $content=curl_exec($ch);
        curl_close($ch);
        $bcoin=json_decode($content);//convert to scraped data to json object.
        $data=$bcoin->data;
        if(DB::table('exchange')->where([['date','=',$_date],['hour','=',$_hour]])->count()==0)
        {
            foreach($data as $obj){
                $exchangeId=$obj->exchangeId;
                $name=$obj->name;
                $rank=(!isset($obj->rank) ? 0 : $obj->rank);
                $percentTotalVolume=(!isset($obj->percentTotalVolume) ? 0 : $obj->percentTotalVolume);
                $volumeUsd=(!isset($obj->volumeUsd) ? 0 : $obj->volumeUsd);
                $tradingPairs=(!isset($obj->tradingPairs) ? 0 : $obj->tradingPairs);
                $socket=(!isset($obj->socket) ? 0 : $obj->socket);
                $exchangeUrl=$obj->exchangeUrl;
                $updated=$obj->updated;
                $insert_array=array('date'=>$_date, 'hour'=>$_hour, 'exchangeId'=>$exchangeId, 'name'=>$name, 'rank'=>$rank, 'percentTotalVolume'=>$percentTotalVolume, 'volumeUsd'=>$volumeUsd, 'tradingPairs'=>$tradingPairs, 'socket'=>$socket, 'exchangeUrl'=>$exchangeUrl, 'updated'=>$updated);
                //print_r($insert_array);
                DB::table('exchange')->insert($insert_array);
            } 
        }

        $_total=0;
        $_date=date("m/d/Y");
        $_hour=date('H');
        $param=$_hour."-".$_date;
        $timestamp=time()-3592;
        $service_url = "https://api.whale-alert.io/v1/transactions?api_key=qyyl3RvjdDMkelzJC8s8Vxk0rQ7eFrv9&min_value=500000&start=$timestamp";
        $ch = curl_init($service_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $content=curl_exec($ch);
        curl_close($ch);
        $nodes=json_decode($content);//convert to scraped data to json object.
        if(DB::table('result')->where('dtime','=',$param)->count()==0){
            $Property = 'transactions';
            if (property_exists($nodes, $Property))
            {
                if(DB::table('result')->where('dtime','=',$param)->count()>0){
            
                }else{
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
                }
            }       
        }
        return csrf_token();
    }

    public function contact(){
        $timestamp=time()-3592;
        $service_url = "https://api.whale-alert.io/v1/transactions?api_key=qyyl3RvjdDMkelzJC8s8Vxk0rQ7eFrv9&min_value=500000&start=$timestamp";
        $ch = curl_init($service_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $content=curl_exec($ch);
        curl_close($ch);
        $nodes=json_decode($content);//convert to scraped data to json object.
        print_r($nodes);
    }

    public function exchanges(Request $request)
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
        //print_r($result_arrays);
        return view('Bitcoin.pages.exchanges',["selected"=>'exchanges',"daterange"=>$daterange,'records' =>$result_arrays]);
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
