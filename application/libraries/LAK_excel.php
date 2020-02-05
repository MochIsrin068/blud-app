<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH."/third_party/PHPExcel.php"; 
require_once APPPATH."/libraries/Excel.php"; 
// library plugin req boostrap
class LAK_excel   {
	protected $excel = NULL;
	private $_month = array(
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
	public function __construct(  )
	{
        $this->excel = new Excel;
	}


    public function create(  $DATA , $date )
    {

        $year = (int) date('Y', $date->date );
		$month = (int) date('m', $date->date );
		$month = $this->_month[ $month ];
        $day = (int) date('d', $date->date );
        //load PHPExcel library
		$this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('LAK');
		
		//STYLING
  		//STYLING
  		$outline = array(
  		'borders' => array('outline' =>
  			array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' =>
  				array('argb' => '0000'),
  				),
  			),
        );	

        $all = array(
  		'borders' => array('allborders' =>
  			array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' =>
  				array('argb' => '0000'),
  				),
  			),
          );	

        $bottom = array(
  		'borders' => array('bottom' =>
  			array('style' => PHPExcel_Style_Border::BORDER_MEDIUM,'color' =>
  				array('argb' => '0000'),
  				),
  			),
          );		

        $numberFormat = '#,##0.00';	
        $accountingFormat = '_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)';
        
        $this->excel->getActiveSheet()->getStyle('A1:F25')->getAlignment()->setWrapText(true);


        //SET DIMENSI TABEL
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(60);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);

        // STARTING HEADER
        
        $this->excel->getActiveSheet()->mergeCells('A2:C2');
		$this->excel->getActiveSheet()->setCellValue('A2', 'UPT DANA BERGULIR');
		$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->mergeCells('A3:C3');
		$this->excel->getActiveSheet()->setCellValue('A3', 'KOTA KENDARI');
		$this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->mergeCells('A4:C4');
		$this->excel->getActiveSheet()->setCellValue('A4', 'LAPORAN ARUS KAS');
		$this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->mergeCells('A5:C5');
		$this->excel->getActiveSheet()->setCellValue('A5', 'SAMPAI '.strtoupper( $month )." ".$year  );
		$this->excel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A5')->getFont()->setSize(10);
        // End Header

        //  STARTING BODY
        //  START HEader Table
        $this->excel->getActiveSheet()->mergeCells('A7:A8');
        $this->excel->getActiveSheet()->mergeCells('B7:B8');

		$this->excel->getActiveSheet()->setCellValue('A7', 'NO');
		$this->excel->getActiveSheet()->getStyle('A7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A7')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A7')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->setCellValue('B7', 'URAIAN');
		$this->excel->getActiveSheet()->getStyle('B7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B7')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B7')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->setCellValue('C7', 'SALDO '.$year);
		$this->excel->getActiveSheet()->getStyle('C7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C7')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C7')->getFont()->setBold(true);     


        $this->excel->getActiveSheet()->setCellValue('C8', '(Rp)');
		$this->excel->getActiveSheet()->getStyle('C8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C8')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C8')->getFont()->setBold(true); 

        // END FOOTER
        // END Header Table   

        // HORIZONTAL NUMBER
        $abj = array('A','B','C');
        for($i = 0; $i < 3; $i++){
		    $this->excel->getActiveSheet()->setCellValue($abj[$i].'9', $i+1);
		    $this->excel->getActiveSheet()->getStyle($abj[$i].'9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		    $this->excel->getActiveSheet()->getStyle($abj[$i].'9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle($abj[$i].'9')->getFont()->setSize(11);
        }
        // END HORIZONTAL NUMBER

        // STARTING BODY VALUE
        // SATU
		$this->excel->getActiveSheet()->setCellValue('A10', '1');
		$this->excel->getActiveSheet()->getStyle('A10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A10')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A10')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A10')->getFont()->setBold(true);
        

        $this->excel->getActiveSheet()->setCellValue('B10', 'Arus Kas dari Aktivitas Operasi');
		$this->excel->getActiveSheet()->getStyle('B10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('B10')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B10')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B10')->getFont()->setBold(true);

        // MASUK
        $this->excel->getActiveSheet()->setCellValue('B11', '   Arus Masuk Kas');
		$this->excel->getActiveSheet()->getStyle('B11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('B11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B11')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B11')->getFont()->setBold(true);


        // DUA

        $this->excel->getActiveSheet()->setCellValue('A24', '2');
		$this->excel->getActiveSheet()->getStyle('A24')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A24')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A24')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A24')->getFont()->setBold(true);
        

        $this->excel->getActiveSheet()->setCellValue('B24', 'Arus Kas dari Aktivitas Investasi');
		$this->excel->getActiveSheet()->getStyle('B24')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('B24')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B24')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B24')->getFont()->setBold(true);

        // MASUK
        $this->excel->getActiveSheet()->setCellValue('B25', '   Arus Masuk Kas');
		$this->excel->getActiveSheet()->getStyle('B25')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('B25')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B25')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B25')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('B30', '   Arus Keluar');
		$this->excel->getActiveSheet()->getStyle('B30')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('B30')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B30')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B30')->getFont()->setBold(true);


        $this->excel->getActiveSheet()->setCellValue('A37', '3');
		$this->excel->getActiveSheet()->getStyle('A37')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A37')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A37')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A37')->getFont()->setBold(true);


        
        $this->excel->getActiveSheet()->setCellValue('B37', 'Arus Kas dari Aktivitas Pendanaan');
		$this->excel->getActiveSheet()->getStyle('B37')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('B37')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B37')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B37')->getFont()->setBold(true);
        

        ###################################################################################################################################################
        ###################################################################################################################################################
        $dataExmple5 = array(
            '       Perolehan Pinjaman',
            '        Penerimaan Kembalil Pinjaman',
        );

        $start5 = 39;
        for($i = 0 ; $i < 2; $i++){
            $this->excel->getActiveSheet()->setCellValue('B'.$start5, $dataExmple5[$i]);
		    $this->excel->getActiveSheet()->getStyle('B'.$start5)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		    $this->excel->getActiveSheet()->getStyle('B'.$start5)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B'.$start5)->getFont()->setSize(11);
            $start5++;
        }
        $this->excel->getActiveSheet()->setCellValue('C40', $DATA->pendanaan_masuk);
		$this->excel->getActiveSheet()->getStyle('C40')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('C40')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C40')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C40')->getNumberFormat()->setFormatCode($numberFormat);

        ###################################################################################################################################################
        ###################################################################################################################################################

        $this->excel->getActiveSheet()->setCellValue('B41', 'Jumlah Arus Masuk Kas');
		$this->excel->getActiveSheet()->getStyle('B41')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('B41')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B41')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B41')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('C41', '=SUM(C39:C40)');
		$this->excel->getActiveSheet()->getStyle('C41')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('C41')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C41')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C41')->getNumberFormat()->setFormatCode($numberFormat);
        $this->excel->getActiveSheet()->getStyle('C41')->getFont()->setBold(true);


        $this->excel->getActiveSheet()->setCellValue('B42', '   Arus Keluar');
		$this->excel->getActiveSheet()->getStyle('B42')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('B42')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B42')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B42')->getFont()->setBold(true);
        ########################################################################################################################
        ########################################################################################################################
        $dataExmple6 = array(
            '        Pembayaran Pokok Pinjaman',
            '        Pemberian Pinjaman',
        );

        $start6 = 43;
        for($i = 0 ; $i < 2; $i++)
        {
            $this->excel->getActiveSheet()->setCellValue('B'.$start6, $dataExmple6[$i]);
		    $this->excel->getActiveSheet()->getStyle('B'.$start6)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		    $this->excel->getActiveSheet()->getStyle('B'.$start6)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B'.$start6)->getFont()->setSize(11);
            $start6++;
        }
        $this->excel->getActiveSheet()->setCellValue('C44', $DATA->pendanaan_keluar);
		$this->excel->getActiveSheet()->getStyle('C44')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('C44')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C44')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C44')->getNumberFormat()->setFormatCode($numberFormat);
        ########################################################################################################################
        ########################################################################################################################
        $this->excel->getActiveSheet()->setCellValue('B45', 'Jumlah Arus Keluar Kas');
		$this->excel->getActiveSheet()->getStyle('B45')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('B45')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B45')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B45')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('C45', "=SUM(C43:C44)");
		$this->excel->getActiveSheet()->getStyle('C45')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('C45')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C45')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C45')->getNumberFormat()->setFormatCode($numberFormat);
        $this->excel->getActiveSheet()->getStyle('C45')->getFont()->setBold(true);

        ########################################################################################################################
        ########################################################################################################################
        $this->excel->getActiveSheet()->setCellValue('B46', 'Arus Kas Bersih dari Aktivitas Pendanaan');
		$this->excel->getActiveSheet()->getStyle('B46')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('B46')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B46')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B46')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('C46', "=C41-C45");
		$this->excel->getActiveSheet()->getStyle('C46')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('C46')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C46')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C46')->getNumberFormat()->setFormatCode($numberFormat);
        $this->excel->getActiveSheet()->getStyle('C46')->getFont()->setBold(true);
        ########################################################################################################################
        ########################################################################################################################

        $dataExmple = array(
            '       Pendapatan Usaha dari Jasa Layanan',
            '       Pendapatan APBD',
            '       Pendapatan Usaha Lainnya',
            '       Pendapatan Lain-Lain yang Sah',
        );

        $start = 12;
        for($i = 0 ; $i < 4; $i++){
            $this->excel->getActiveSheet()->setCellValue('B'.$start, $dataExmple[$i]);
		    $this->excel->getActiveSheet()->getStyle('B'.$start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		    $this->excel->getActiveSheet()->getStyle('B'.$start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B'.$start)->getFont()->setSize(11);
            $start++;
        }

        $this->excel->getActiveSheet()->setCellValue('B16', 'Jumlah Arus Masuk Kas');
		$this->excel->getActiveSheet()->getStyle('B16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('B16')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B16')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B16')->getFont()->setBold(true);


        // KELUAR
        $this->excel->getActiveSheet()->setCellValue('B17', '   Arus Keluar Kas');
		$this->excel->getActiveSheet()->getStyle('B17')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('B17')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B17')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B17')->getFont()->setBold(true);
        ################################################################################################################################################################################################################################################
        ################################################################################################################################################################################################################################################
        $dataExmple2 = array(
            '       Pembayaran Layanan',
            '       Pembayaran Umum dan Administrasi',
            '       Pembayaran Lainnya',
        );

        $start2 = 18;
        for($i = 0 ; $i < 3; $i++){
            $this->excel->getActiveSheet()->setCellValue('B'.$start2, $dataExmple2[$i]);
		    $this->excel->getActiveSheet()->getStyle('B'.$start2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		    $this->excel->getActiveSheet()->getStyle('B'.$start2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B'.$start2)->getFont()->setSize(11);
            $start2++;
        }
        $this->excel->getActiveSheet()->setCellValue('C19', $DATA->kas_keluar);
		$this->excel->getActiveSheet()->getStyle('C19')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('C19')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C19')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C19')->getNumberFormat()->setFormatCode($numberFormat);
        ################################################################################################################################################################################################################################################
        ################################################################################################################################################################################################################################################
        // MASUK
        $this->excel->getActiveSheet()->setCellValue('B16', 'Jumlah Arus Masuk Kas');
		$this->excel->getActiveSheet()->getStyle('B16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('B16')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B16')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B16')->getFont()->setBold(true);

        ########################################################################################################################
        // KELUAR
        $this->excel->getActiveSheet()->setCellValue('B21', 'Jumlah Arus Keluar Kas');
		$this->excel->getActiveSheet()->getStyle('B21')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('B21')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B21')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B21')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('B22', 'Arus Kas Bersih dari Aktivitas Operasi');
		$this->excel->getActiveSheet()->getStyle('B22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('B22')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B22')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B22')->getFont()->setBold(true);


        
        $dataExmple3 = array(
            '       Penjualan Aset Tetap',
            '       Penjualan Investasi Jangka Panjang',
            '       Penjualan Aset Lainnya',
        );

        $start3 = 26;
        for($i = 0 ; $i < 3; $i++){
            $this->excel->getActiveSheet()->setCellValue('B'.$start3, $dataExmple3[$i]);
		    $this->excel->getActiveSheet()->getStyle('B'.$start3)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		    $this->excel->getActiveSheet()->getStyle('B'.$start3)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B'.$start3)->getFont()->setSize(11);
            $start3++;
        }

        // MASUK
        $this->excel->getActiveSheet()->setCellValue('B29', 'Jumlah Arus Masuk Kas');
		$this->excel->getActiveSheet()->getStyle('B29')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('B29')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B29')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B29')->getFont()->setBold(true);


        $dataExmple4 = array(
            '       Perolehan Aset Tetap',
            '       Perolehan Investasi Jangka Panjang',
            '       Penjualan Aset Lainnya',
        );

        $start4 = 31;
        for($i = 0 ; $i < 3; $i++){
            $this->excel->getActiveSheet()->setCellValue('B'.$start4, $dataExmple4[$i]);
		    $this->excel->getActiveSheet()->getStyle('B'.$start4)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		    $this->excel->getActiveSheet()->getStyle('B'.$start4)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B'.$start4)->getFont()->setSize(11);
            $start4++;
        }

        $this->excel->getActiveSheet()->setCellValue('B34', 'Jumlah Arus Keluar Kas');
		$this->excel->getActiveSheet()->getStyle('B34')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('B34')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B34')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B34')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('B35', 'Arus Kas Bersih dari Aktivitas Operasi');
		$this->excel->getActiveSheet()->getStyle('B35')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('B35')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B35')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B35')->getFont()->setBold(true);

        // SALDO
        // $exmplSaldo = 65914703;
        $this->excel->getActiveSheet()->setCellValue('C15', $DATA->kas_masuk );
		$this->excel->getActiveSheet()->getStyle('C15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('C15')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C15')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C15')->getNumberFormat()->setFormatCode($numberFormat);


        // HASIl
        $this->excel->getActiveSheet()->setCellValue('C16', '=SUM(C12:C15)');
		$this->excel->getActiveSheet()->getStyle('C16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('C16')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C16')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C16')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C16')->getNumberFormat()->setFormatCode($numberFormat);


        $this->excel->getActiveSheet()->setCellValue('C21', '=SUM(C18:C20)');
		$this->excel->getActiveSheet()->getStyle('C21')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('C21')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C21')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C21')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C21')->getNumberFormat()->setFormatCode($numberFormat);


        $this->excel->getActiveSheet()->setCellValue('C22', '=C16 - C21');
		$this->excel->getActiveSheet()->getStyle('C22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('C22')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C22')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C22')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C22')->getNumberFormat()->setFormatCode($numberFormat);

        $this->excel->getActiveSheet()->setCellValue('C29', '');
		$this->excel->getActiveSheet()->getStyle('C29')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('C29')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C29')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C29')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C29')->getNumberFormat()->setFormatCode($numberFormat);

        
        $this->excel->getActiveSheet()->setCellValue('D29', '');
		$this->excel->getActiveSheet()->getStyle('D29')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('D29')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D29')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D29')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D29')->getNumberFormat()->setFormatCode($numberFormat);

        
        $this->excel->getActiveSheet()->setCellValue('E29', '');
		$this->excel->getActiveSheet()->getStyle('E29')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('E29')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E29')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('E29')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E29')->getNumberFormat()->setFormatCode($numberFormat);

        // END BODY
        ########################################################################################################################
        ########################################################################################################################
        $this->excel->getActiveSheet()->setCellValue('B48', 'Kenaikan Bersih Kas');
		$this->excel->getActiveSheet()->getStyle('B48')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('B48')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B48')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B48')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('C48', '=C46+C22');
		$this->excel->getActiveSheet()->getStyle('C48')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('C48')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C48')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C48')->getNumberFormat()->setFormatCode($numberFormat);

        $this->excel->getActiveSheet()->setCellValue('B49', 'Kas dan Setara Kas Awal');
		$this->excel->getActiveSheet()->getStyle('B49')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('B49')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B49')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B49')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('C49', $DATA->saldo_awal_kas );
		$this->excel->getActiveSheet()->getStyle('C49')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('C49')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C49')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C49')->getNumberFormat()->setFormatCode($numberFormat);

        $this->excel->getActiveSheet()->setCellValue('B50', 'Jumlah Saldo Kas');
		$this->excel->getActiveSheet()->getStyle('B50')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('B50')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B50')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B50')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('C50', '=SUM(C48:C49)');
		$this->excel->getActiveSheet()->getStyle('C50')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('C50')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C50')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C50')->getNumberFormat()->setFormatCode($numberFormat);
        $this->excel->getActiveSheet()->getStyle('C50')->getFont()->setBold(true);
        ########################################################################################################################
        ########################################################################################################################

        // START FOOTER
        // $this->excel->getActiveSheet()->mergeCells('D53:E53');
        $this->excel->getActiveSheet()->setCellValue('C53', 'Kendari, '.$day.' '.$month.' '.$year.'');
        $this->excel->getActiveSheet()->getStyle('C53')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C53')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C53')->getFont()->setSize(11);

        // $this->excel->getActiveSheet()->mergeCells('D54:E54');
        $this->excel->getActiveSheet()->setCellValue('C54', 'UPT DANA BERGULIR');
        $this->excel->getActiveSheet()->getStyle('C54')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C54')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C54')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C54')->getFont()->setBold(11);

        // $this->excel->getActiveSheet()->mergeCells('D55:E55');
        $this->excel->getActiveSheet()->setCellValue('C55', 'DIREKTUR,');
        $this->excel->getActiveSheet()->getStyle('C55')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C55')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C55')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C55')->getFont()->setBold(11);

        // $this->excel->getActiveSheet()->mergeCells('D59:E59');
        $this->excel->getActiveSheet()->setCellValue('C59', 'TOHAMBA, SE');
        $this->excel->getActiveSheet()->getStyle('C59')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C59')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C59')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C59')->getFont()->setBold(11);

        $this->excel->getActiveSheet()->mergeCells('A60:E60');
        $this->excel->getActiveSheet()->setCellValue('A60', 'printed by simpel alir');
        $this->excel->getActiveSheet()->getStyle('A60')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $this->excel->getActiveSheet()->getStyle('A60')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A60')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A60')->getFont()->setBold(11);

        // SETTING BORDER
        $this->excel->getActiveSheet()->getStyle('A7:A8')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('B7:B8')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('C7:C8')->applyFromArray($outline);


        $this->excel->getActiveSheet()->getStyle('A9:C9')->applyFromArray($all);
        $this->excel->getActiveSheet()->getStyle('A10:A50')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('B10:B50')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('C10:C50')->applyFromArray($all);

        // $this->excel->getActiveSheet()->getStyle('C16:C50')->applyFromArray($all);
        // END SETTING BORDER

        
        ob_end_clean();
        $filename='Lak '.$month.' '.$year.'(Laporan Keuangan).xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        $objWriter->save('php://output');
    }

}
