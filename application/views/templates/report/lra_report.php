<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<table>
    <tr>
        <td style="width:33%;font-weight: bold"></td>
        <td style="text-align:center;width:33%;font-size: 11px;font-weight: bold ">LAPORAN REALISASI ANGGARAN</td>
        <td style="width:33%;font-size: 8px; "></td>
    </tr>
    <tr>
        <td style="width:33%;font-weight: bold"></td>
        <td style="text-align:center;width:33%;font-size: 11px;font-weight: bold ">UPT DANA BERGULIR</td>
        <td style="width:33%;font-size: 8px; "></td>
    </tr>
    <tr>
        <td style="width:33%;font-weight: bold"></td>
        <td style="text-align:center;width:33%;font-size: 11px;font-weight: bold ">KOTA KENDARI</td>
        <td style="width:33%;font-size: 8px; "></td>
    </tr>
    <tr>
        <td style="width:33%;font-weight: bold"> Bulan <?php echo $month ?> </td>
        <td style="text-align:center;width:33%;font-size: 11px;font-weight: bold "></td>
        <td style="width:33%;font-size: 8px; "></td>
    </tr>
</table>
<br>

<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<table border="1" padding="2" >
    <!-- <thead  style="font-size:16px" > -->
    <tr style="font-size:9px;text-align: center;  border-bottom:0.5px solid black" >
        <td style="width:5% ">No</td>
        <td style="width:15% ">Kode Rekening</td>
        <td style="width:25% ">Uraian</td>
        <td style="width:10% ">Jumlah Anggaran</td>
        <td style="width:10% ">Realisasi Sampai Dengan Bulan Lalu (Rp)</td>
        <td style="width:10% ">Realisasi Bulan Ini (Rp)</td>
        <td style="width:12% ">Realisasi Sampai Dengan Bulan Ini (Rp)</td>
        <td style="width:11 % ">Selisih /(Kurang) (Rp)</td>
    </tr>
    <!-- </thead> -->
    <?php 
        $no =  1;
        foreach( $rows as $ind => $row ):
    ?>
    <tr style="font-size:9px" >
        <td style="text-align: center" ><?php echo $no++ ?></td>
        <?php foreach( $header as $key => $value ):

                if( $key=='account_description' || $key=='account_code'  )
                    echo '<td padding="2" height="10" style="text-align: left"  >';
                else
                    echo '<td padding="2" height="10" style="text-align: right"  >';
            ?>
                <?php 
                    $attr = "";
                    if( is_numeric( $row->$key ) && ( $key != 'phone' && $key != 'username'  && $key != 'serial_number' ) )
                        $attr = number_format( $row->$key );
                    else
                        $attr = $row->$key ;
                    if( $key == 'date' || $key == 'create_date' )
                    {
                        if( is_numeric( $row->$key ) )
                            $attr =  date("d/m/Y", $row->$key ) ;
                    }
                    if( strlen( $row->account_code ) <=3 ) $attr = '<b>'. $attr.'</b>';
                    echo $attr;
                ?>
            </td>
        <?php endforeach;?>
    </tr>
    <?php 
        endforeach;
    ?>
</table>

<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<br>
<br>
<table>
    <tr>
        <td style="width:33% ;text-align: center">
                
        </td>
        <td style="width:33% ;text-align: center">
                
        </td>
        <td style="width:33% ;text-align: center">
                Kendari, <?php echo  $day." ".$month." ".$year ?> <br>
        </td>
    </tr>
</table>
<table>
    <tr>
        <td style="width:33% ;text-align: center">
                
        </td>
        <td style="width:33% ;text-align: center">
                
        </td>
        <td style="width:33% ;text-align: center">
            UPT Dana Bergulir<br>
        </td>
    </tr>
</table>
<br><br><br><br>
<table>
    <tr>
        <td style="width:33% ;text-align: center">
        </td>
        <td style="width:33% ;text-align: center">
                
        </td>
        <td style="width:33% ;text-align: center">
                <u>
                    TOHAMBA, SE
                </u>
        </td>
    </tr>
</table>