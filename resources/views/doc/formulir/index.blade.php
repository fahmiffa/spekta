<!DOCTYPE html>
<html>
<head>
    <title>{{env('APP_NAME')}}</title>
</head>
<style>
  body{
    font-size: 12px;
  }
    table {
    border-collapse: collapse;
    border-spacing: 0;
  }
  td {
    border: 1px solid black;    
  }

img {
    object-fit: cover;
    width: 50px;
    height: 50px;
}
</style>
<body>
  @include('doc.formulir.header')   

  <main style="margin-top: 1rem">
    <p style="font-weight: bold; margin-top:1rem;">{{$form->tag}}</p>
    <table autosize="1" style="width: 100%">        
            @php $no=0; @endphp
            @foreach($form->title as $row)        
                @if($loop->first)                
                    <tr style="font-weight: bold; text-align:center">
                        <td width="5%" class="text-center">{{$alphabet_letter = chr(65 + $no++)}}.</td>
                        <td colspan="2" width="50%">{{$row->name}}</td>
                        <td width="10%">Status</td>                        
                        <td width="35%">Catatan / Saran</td>
                    </tr>
                    @foreach($row->items as $items)
                      <tr>
                          <td style="text-align: right; vertical-align:top">{{$loop->iteration}}&nbsp;</td>
                          <td colspan="2">{{$items->name}}</td>           
                          <td></td>
                          <td></td>                                                  
                      </tr>
                          @foreach($items->sub as $key)
                          <tr>
                              <td></td>
                              <td width="1%" style="vertical-align:top;border-right:0px">{{abjad($loop->index)}}. </td>
                              <td style="border-left:0px">{{$key->name}}</td>   
                              <td></td>
                              <td></td>
                          </tr>
                          @endforeach 
                    @endforeach 
                @else           
                <tr style="font-weight: bold;">
                    <td style="text-align: center">{{$alphabet_letter = chr(65 + $no++)}}.</td>
                    <td colspan="4">{{$row->name}}</td>           
                </tr>
                @foreach($row->items as $items)
                      <tr>
                          <td style="text-align: right;vertical-align:top">{{$loop->iteration}}&nbsp;</td>
                          <td colspan="2">{{$items->name}}</td>           
                          <td></td>
                          <td></td>        
                      </tr>
                        @foreach($items->sub as $key)
                        <tr>
                            <td></td>
                            <td width="1%" style="vertical-align:top;border-right:0px">{{abjad($loop->index)}}. </td>
                            <td style="border-left:0px">{{$key->name}}</td>                    
                            <td></td>
                            <td></td>
                        </tr>
                        @endforeach 
                @endforeach 
                @endif
            @endforeach          
    </table>
  </main>    

  @include('doc.formulir.footer')   
</body>
</html>