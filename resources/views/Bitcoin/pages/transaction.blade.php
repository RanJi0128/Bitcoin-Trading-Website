@extends('Bitcoin.layouts.default')
@section('content')
<div class="page-wrapper">
   <div class="container-fluid">
      <div class="row">
         <!-- column -->
         <div class="col-12">
         @foreach($records as $record)
         <div class="card">
               <div class="card-body">
                    <div class="table-responsive">
                     <table class="table" id="his">
                        <thead>
                           <tr>
                              <th>DateTime:</th>
                              <th>{{ $record->dtime }}</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <td>Hash</td>
                              <td><a target='_blank' href="https://www.blockchain.com/btc/tx/{{$record->hash}}">{{ $record->hash }}</a></td>
                           </tr>
                           <tr>
                              <td>From Adress</td>
                              <td>{{ $record->from_address }}</td>
                           </tr>
                           <tr>
                              <td>From_Owner</td>
                              <td>{{ $record->from_owner }}</td>
                           </tr>
                           <tr>
                              <td>From_Owner_Type</td>
                              <td>{{ $record->from_owner_type }}</td>
                           </tr>
                           <tr>
                              <td>To Address</td>
                              <td>{{ $record->to_address }}</td>
                           </tr>
                           <tr>
                              <td>To_Owner</td>
                              <td>{{ $record->to_owner }}</td>
                           </tr>
                           <tr>
                              <td>To_Owner_Type</td>
                              <td>{{ $record->to_owner_type }}</td>
                           </tr>
                           <tr>
                              <td>TimeStamp</td>
                              <td>{{ $record->timestamp }}</td>
                           </tr>
                           <tr>
                               @php($rdtm=date('m/d/Y H:i:s', $record->timestamp))
                              <td>Readable Timestamp</td>
                              <td>{{ $rdtm }}</td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
            @endforeach   

         </div>
      </div>
   </div>
</div>
@stop

