<header>    
    <table style="width: 100%; border:none">
      <tr>
        <td style="border:none"><img class="img" src="{{gambar('kab.png')}}"/></td>
        <td width="100%" style="border:none; text-align:center">
          <p><span style="font-weight: bold; font-size:0.8rem;text-wrap:none">{{$form->titles}}</span>
          <br>No.&nbsp;&nbsp;&nbsp;/SPm-SIMBG/&nbsp;&nbsp;&nbsp;/2023
          </p>
        </td>
        <td style="border:none"><img class="img" src="{{gambar('logo.png')}}" /></td>
      </tr>
    </table>             
  </header>
  <div style="margin: auto; display:block; width:600px; max-width:100%">
    @php  $header = json_decode($form->header->item); @endphp      
      <table style="width:100%; margin-top: 1rem" align="center">          
        <tr>
            @foreach ($header as $item)            
                @if($item)
                  <td width="40%" style="border:none">{{$item}} </td>
                  <td width="60%" style="border:none">: </td>                                            
                @endif    
                @if($loop->iteration % 2 === 0)               
              </tr><tr>
                @endif                
              @endforeach                    
      </table>          
  </div>