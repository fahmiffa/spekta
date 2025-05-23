  <p>Saran dan Masukkan Lain :</p>               
  <table style="width:100%">          
    <tr>         
        <td width="30%" style="border:none">     
          {!! $form->footer->item !!}
        </td>
        <td width="30%" style="border:none">    
        </td>
        <td width="40%" style="border:none">
          <p style="text-align:center">Slawi, {{dateID($form->created_at)}}</p>                   
          <center><img src="data:image/png;base64, {{ $qrCode }}"></center>
          <p style="text-align:center">DPUPR Kabupaten Tegal</p>
        </td>                                                                     
    </tr>
  </table>    
