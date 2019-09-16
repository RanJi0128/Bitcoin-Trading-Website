<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Bitcoin extends Model
{
    protected $table = 'bitcoin';
    public function getAllData()
    {
        return $this->all();
    }

    public function getHourlyData($date)
    {
        return $this->where('dtime','like',"%-$date")->get();
    }
    
    public function getHourlyDataMin($date)
    {
        return $this->where('dtime','like',"%-$date")->min('rate');
    }

    public function getRangeData($dates){ 
        $arrays=array();
        foreach($dates as $date){
            $avg=$this->where('dtime','like',"%-$date")->avg('rate');
            $amount_usd=DB::table('result')->where('dtime','like','%-'.$date)->sum('amount_usd');
            $amount_usd_from=DB::table('result')->where([['dtime','like',"%-$date"],['from_owner_type','=','exchange']])->sum('amount_usd');
            $amount_usd_to=DB::table('result')->where([['dtime','like',"%-$date"],['to_owner_type','=','exchange']])->sum('amount_usd');
            if(isset($avg))$arrays[]=array('date'=>$date,'rate'=> $avg,'amount_usd'=>$amount_usd,'amount_usd_from'=>$amount_usd_from,'amount_usd_to'=>$amount_usd_to);
        }
        return $arrays;
    }

    public function getRangeResultData($dates){ 
        $arrays=array();
        foreach($dates as $date){
            $rows=DB::table('result')->where('dtime','like','%-'.$date)->get();
            foreach($rows as $row){
                $arrays[]=$row;
            }
        }
        return $arrays;
    }

    public function getRangeCryptoData($dates){ 
        $arrays=array();
        foreach($dates as $date){
            $exchanges=DB::table('exchange')->select('name')->distinct()->where([['date','=',$date],['rank','<',21]])->get();
            foreach($exchanges as $exchange){
                //$arrays[$exchange->name]=array();
                $rows=DB::table('exchange')->where([['name','=',$exchange->name],['date','=',$date],['rank','<',21]])->get();
                foreach($rows as $row){
                    $name=$row->name;
                    $mrow=DB::table('exchange')->where([['name','=',$name],['id','<',$row->id]])->orderBy('id', 'desc')->first();
                    if($mrow){
                        $mrow=DB::table('exchange')->where('id','=',$mrow->id)->first();
                        $row->volumehour=$row->volumeUsd-$mrow->volumeUsd;
                    }else{
                        $row->volumehour=0;
                    }
                    $arrays[$exchange->name][]=$row;
                }
            }
        }
        return $arrays;
    }
    public function getRangeCryptoData1($dates){ 
        $arrays=array();
        foreach($dates as $date){
            //$rows=DB::table('crypto')->where([['date','=',$date],['MKTCAP','<>','0']])->get();
           // $rows=DB::table('exchange')->where([['date','=',$date],['rank','<',21]])->get();
            $rows=DB::table('exchange')->where([['date','=',$date],['rank','<',21]])->get();
            foreach($rows as $row){
                $name=$row->name;
                $mrow=DB::table('exchange')->where([['name','=',$name],['id','<',$row->id]])->orderBy('id', 'desc')->first();
                if($mrow){
                    $mrow=DB::table('exchange')->where('id','=',$mrow->id)->first();
                    $row->volumehour=$row->volumeUsd-$mrow->volumeUsd;
                }else{
                    $row->volumehour=0;
                }
                $arrays[]=$row;
            }
        }
        return $arrays;
    }

    public function getprevious($name){
        //$rows=DB::table('exchange')->where(['name','=',$name])->get();
        return DB::table('exchange')->where(['name','=',$name])->max('id')->get();
  
    }
}
