
    <table class="table-responsive  " >
        <tr>
            <td style="width:65%; font-weight: bold;">UNIT PELAKSANA TEKNIS DANA BERGULIR</td>
            <td style="width:15%;font-size: 8px; ">TAHUN ANGGARAN</td>
            <td style="width:5%">:</td>
            <td style="width:30%"> <?php echo date('Y')?> </td>
        </tr>
    </table>
    <br>
    <table>
        <tr>
            <td style="width:65%;font-weight: bold">PEMERINTAH KOTA KENDARI</td>
            <td style="width:15%;font-size: 8px; ">TANGGAL</td>
            <td style="width:5%">:</td>
            <td style="width:30%"> <?php echo date('d-m-Y')?>  </td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="width:65%;font-weight: bold"></td>
            <td style="width:15%;font-size: 8px; ">NO AKAD</td>
            <td style="width:5%">:</td>
            <td style="width:30%"> <?php echo ' '?> </td>
        </tr>
    </table><br/><br/>


    <table>
        <tr>
            <td style="width:33%;font-weight: bold"></td>
            <td style="text-align:center;width:33%;font-size: 18px;font-weight: bold ">K U I T A N S I</td>
            <td style="width:33%;font-size: 8px; "></td>
        </tr>
        <tr>
            <td style="width:38%;font-weight: bold"></td>
            <td style="text-align:left;width:43%;font-size: 8px;"> NOMOR</td>
            <td style="width:33%;font-size: 8px; "></td>
        </tr>
    </table>
    <br>
    <br>
    <table>
        <tr>
            <td style="width:30%;">SUDAH TERIMA DARI</td>
            <td style="width:5%">:</td>
            <td style="width:85%;font-size: 8px; ">BENDAHARA UPT DANA BERGULIR</td>
        </tr>
        <tr>
            <td style="width:30%;">JUMLAH UANG</td>
            <td style="width:5%">:</td>
            <td style="width:85%;font-size: 8px; ">RP. <?php echo $nominal = '' || $nominal == null ? '' :number_format( $nominal )?></td>
        </tr>
        <tr>
            <td style="width:30%;">UNTUK PEMBAYARAN</td>
            <td style="width:5%">:</td>
            <td style="width:65%;font-size: 8px; ">PINJAMAN DANA BERGULIR</td>
        </tr>
    </table>
    <br>
    <br>
    <table>
        <tr>
            <td style="width:30%;">TERBILANG</td>
            <td style="width:5%">:</td>
            <td style="width:65%;font-size: 8px;border-bottom:0.5px solid black ">LIMA RATUS RIBU RUPIAH</td>
        </tr>
    </table>
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
                    Kendari,  <?php echo date('d-m-Y')?> <br>
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="width:33% ;text-align: center">
                    SETUJU BAYAR<br>
                    PENGGUNA ANGGARAN
            </td>
            <td style="width:33% ;text-align: center">
                    LUNAS BAYAR<br>
                    PADA TANGGAL <?php echo date('d-m-Y')?> <br>
                    BENDAHARA <br>
            </td>
            <td style="width:33% ;text-align: center">
                    <br>
                    <br>
                    YANG MENERIMA<br>
            </td>
        </tr>
    </table>
    <br><br><br><br>
    <table>
        <tr>
            <td style="width:33% ;text-align: center">
                    <u>
                        <?php echo $kepala_upt; ?>
                    </u>
            </td>
            <td style="width:33% ;text-align: center">
                    <u>
                        <?php echo $bendahara; ?>
                    </u>
            </td>
            <td style="width:33% ;text-align: center">
                    <u>
                        <?php echo $name ?>
                    </u>
            </td>
        </tr>
    </table>
    <br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br>

    <div>
        <p style="font-size:10px"><i>Print By SimpleAlir</i></p>
    </div>