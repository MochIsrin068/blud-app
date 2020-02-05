<?php
    $_month = array(
        '',
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember',
      );
    $year = date('Y', $date);
    $month = $_month[ (int) date('m', $date) ] ;
    $day = date('d', $date);
?> 
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<table>
    <tr>
        <td style="width:3%;font-weight: bold"></td>
        <td style="text-align:center;width:93%;font-size: 14px;font-weight: bold ">RENCANA BISNIS ANGGARAN</td>
        <td style="width:3%;font-size: 8px; "></td>
    </tr>
    <tr>
        <td style="width:3%;font-weight: bold"></td>
        <td style="text-align:center;width:93%;font-size: 14px;font-weight: bold ">UPT DANA BERGULIR</td>
        <td style="width:3%;font-size: 8px; "></td>
    </tr>
    <tr>
        <td style="width:3%;font-weight: bold"></td>
        <td style="text-align:center;width:93%;font-size: 14px;font-weight: bold ">TAHUN <?php echo $year?></td>
        <td style="width:3%;font-size: 8px; "></td>
    </tr>
    
</table>
<br>
<br>
<br>

<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<table border="1" padding="2" >
    <!-- <thead  style="font-size:16px" > -->
    <tr style="font-size:9px;text-align: center;  border-bottom:0.5px solid black" >
        <td style="width:5% ">No </td>
        <td style="width:15% ">Kode Rekening</td>
        <td style="width:40% ">Uraian</td>
        <td style="width:12% ">Satuan</td>
        <td style="width:12% ">Volume</td>
        <td style="width:13% ">Harga( Rp )</td>
    </tr>
    <!-- </thead> -->
    <?php 
        $no =  1;
        foreach( $rows as $ind => $row ):
    ?>
    <tr style="font-size:9px" >
        <td style="text-align: center" ><?php echo $no++ ?></td>
        <?php foreach( $header as $key => $value ):?>
            <td padding="2" height="15" >
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
<br>
<br>
<table>
    <tr>
        <td style="width:33% ;text-align: center">
                
        </td>
        <td style="width:33% ;text-align: center">
                
        </td>
        <td style="width:33% ;text-align: center">
                Kendari,________  <?php echo $month.' '.$year ?> <br>
        </td>
    </tr>
</table>
<table>
    <tr>
        <td style="width:33% ;text-align: center">
                Mengetahuidan Menyetujui<br>
                Direktur
        </td>
        <td style="width:33% ;text-align: center">
                
        </td>
        <td style="width:33% ;text-align: center">
                BENDAHARA<br>
        </td>
    </tr>
</table>
<br><br><br><br>
<table>
    <tr>
        <td style="width:33% ;text-align: center">
                <u>
                    TOHAMBA, SE
                </u>
        </td>
        <td style="width:33% ;text-align: center">
                
        </td>
        <td style="width:33% ;text-align: center">
                <u>
                    MAWARNI, SE
                </u>
        </td>
    </tr>
</table>