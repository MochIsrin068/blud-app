<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lak extends Finance_Controller {
	public function __construct(){
        parent::__construct();
        $this->load->library('excel');
    }

    public function index(){
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
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(46);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);

        // STARTING HEADER
        $this->excel->getActiveSheet()->mergeCells('A1:F1');
		$this->excel->getActiveSheet()->setCellValue('A1', 'UPT DANA BERGULIR');
		$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->mergeCells('A2:F2');
		$this->excel->getActiveSheet()->setCellValue('A2', 'BADAN LAYANAN UMUM DAERAH "HARUM"');
		$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->mergeCells('A3:F3');
		$this->excel->getActiveSheet()->setCellValue('A3', 'KOTA KENDARI');
		$this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->mergeCells('A4:F4');
		$this->excel->getActiveSheet()->setCellValue('A4', 'LAPORAN ARUS KAS');
		$this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->mergeCells('A5:F5');
		$this->excel->getActiveSheet()->setCellValue('A5', 'UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN 31 DESEMBER 2018 DAN 2017');
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
        
        $this->excel->getActiveSheet()->setCellValue('C7', 'SALDO 2018');
		$this->excel->getActiveSheet()->getStyle('C7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C7')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C7')->getFont()->setBold(true);     


        $this->excel->getActiveSheet()->setCellValue('C8', '(Rp)');
		$this->excel->getActiveSheet()->getStyle('C8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C8')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C8')->getFont()->setBold(true); 

        $this->excel->getActiveSheet()->setCellValue('D7', 'SALDO 2017');
		$this->excel->getActiveSheet()->getStyle('D7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('D7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D7')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D7')->getFont()->setBold(true);     


        $this->excel->getActiveSheet()->setCellValue('D8', '(Rp)');
		$this->excel->getActiveSheet()->getStyle('D8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('D8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D8')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D8')->getFont()->setBold(true); 


        $this->excel->getActiveSheet()->setCellValue('E7', 'KENAIKAN');
		$this->excel->getActiveSheet()->getStyle('E7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('E7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E7')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('E7')->getFont()->setBold(true);     


        $this->excel->getActiveSheet()->setCellValue('E8', '(PENURUNAN)');
		$this->excel->getActiveSheet()->getStyle('E8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('E8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E8')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('E8')->getFont()->setBold(true); 


        $this->excel->getActiveSheet()->mergeCells('F7:F8');
		$this->excel->getActiveSheet()->setCellValue('F7', '%');
		$this->excel->getActiveSheet()->getStyle('F7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('F7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('F7')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('F7')->getFont()->setBold(true);
        // END FOOTER
        // END Header Table   

        // HORIZONTAL NUMBER
        $abj = array('A','B','C','D');
        for($i = 0; $i < 4; $i++){
		    $this->excel->getActiveSheet()->setCellValue($abj[$i].'9', $i+1);
		    $this->excel->getActiveSheet()->getStyle($abj[$i].'9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		    $this->excel->getActiveSheet()->getStyle($abj[$i].'9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle($abj[$i].'9')->getFont()->setSize(11);
        }

        $this->excel->getActiveSheet()->setCellValue('E9', '5=(3-4)');
		$this->excel->getActiveSheet()->getStyle('E9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('E9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E9')->getFont()->setSize(11);
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

        $this->excel->getActiveSheet()->setCellValue('B41', 'Jumlah Arus Masuk Kas');
		$this->excel->getActiveSheet()->getStyle('B41')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('B41')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B41')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B41')->getFont()->setBold(true);


        $this->excel->getActiveSheet()->setCellValue('B42', '   Arus Keluar');
		$this->excel->getActiveSheet()->getStyle('B42')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('B42')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B42')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B42')->getFont()->setBold(true);

        $dataExmple6 = array(
            '        Pembayaran Pokok Pinjaman',
            '        Pemberian Pinjaman',
        );

        $start6 = 43;
        for($i = 0 ; $i < 2; $i++){
            $this->excel->getActiveSheet()->setCellValue('B'.$start6, $dataExmple6[$i]);
		    $this->excel->getActiveSheet()->getStyle('B'.$start6)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		    $this->excel->getActiveSheet()->getStyle('B'.$start6)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B'.$start6)->getFont()->setSize(11);
            $start6++;
        }

        $this->excel->getActiveSheet()->setCellValue('B45', 'Jumlah Arus Keluar Kas');
		$this->excel->getActiveSheet()->getStyle('B45')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('B45')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B45')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B45')->getFont()->setBold(true);


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

        // MASUK
        $this->excel->getActiveSheet()->setCellValue('B16', 'Jumlah Arus Masuk Kas');
		$this->excel->getActiveSheet()->getStyle('B16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('B16')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B16')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B16')->getFont()->setBold(true);

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
        $exmplSaldo = 65914703;
        $this->excel->getActiveSheet()->setCellValue('C15', $exmplSaldo);
		$this->excel->getActiveSheet()->getStyle('C15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('C15')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C15')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C15')->getNumberFormat()->setFormatCode($numberFormat);

        $exmplSaldo2 = 64356864;
        $this->excel->getActiveSheet()->setCellValue('D15', $exmplSaldo2);
		$this->excel->getActiveSheet()->getStyle('D15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('D15')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D15')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D15')->getNumberFormat()->setFormatCode($numberFormat);

        // Kenaikan / Penurunan
        $res = $exmplSaldo - $exmplSaldo2;
        $this->excel->getActiveSheet()->setCellValue('E15', $res);
		$this->excel->getActiveSheet()->getStyle('E15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('E15')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E15')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('E15')->getNumberFormat()->setFormatCode($numberFormat);

        // Persen
        $this->excel->getActiveSheet()->setCellValue('F15', $res / $exmplSaldo2 * 100);
		$this->excel->getActiveSheet()->getStyle('F15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('F15')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('F15')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('F15')->getNumberFormat()->setFormatCode($numberFormat);


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

        $this->excel->getActiveSheet()->setCellValue('D21', '=SUM(C18:C20)');
		$this->excel->getActiveSheet()->getStyle('D21')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('D21')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D21')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D21')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D21')->getNumberFormat()->setFormatCode($numberFormat);

        $this->excel->getActiveSheet()->setCellValue('E21', '=SUM(C18:C20)');
		$this->excel->getActiveSheet()->getStyle('E21')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('E21')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E21')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('E21')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E21')->getNumberFormat()->setFormatCode($numberFormat);

        $this->excel->getActiveSheet()->setCellValue('C22', '=SUM(C18:C20)');
		$this->excel->getActiveSheet()->getStyle('C22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('C22')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C22')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C22')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C22')->getNumberFormat()->setFormatCode($numberFormat);

        $this->excel->getActiveSheet()->setCellValue('D22', '=SUM(C18:C20)');
		$this->excel->getActiveSheet()->getStyle('D22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('D22')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D22')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D22')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D22')->getNumberFormat()->setFormatCode($numberFormat);

        $this->excel->getActiveSheet()->setCellValue('E22', '=SUM(C18:C20)');
		$this->excel->getActiveSheet()->getStyle('E22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('E22')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E22')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('E22')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E22')->getNumberFormat()->setFormatCode($numberFormat);


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



        $this->excel->getActiveSheet()->setCellValue('D16', '=SUM(D12:D15)');
		$this->excel->getActiveSheet()->getStyle('D16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('D16')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D16')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D16')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D16')->getNumberFormat()->setFormatCode($numberFormat);

        $this->excel->getActiveSheet()->setCellValue('E16', '=SUM(E12:E15)');
		$this->excel->getActiveSheet()->getStyle('E16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('E16')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E16')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('E16')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E16')->getNumberFormat()->setFormatCode($numberFormat);


        // END BODY
        

        // START FOOTER
        $this->excel->getActiveSheet()->mergeCells('D53:E53');
        $this->excel->getActiveSheet()->setCellValue('D53', 'Kendari, 31 Januari 2018');
        $this->excel->getActiveSheet()->getStyle('D53')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D53')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D53')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->mergeCells('D54:E54');
        $this->excel->getActiveSheet()->setCellValue('D54', 'UPT DANA BERGULIR');
        $this->excel->getActiveSheet()->getStyle('D54')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D54')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D54')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D54')->getFont()->setBold(11);

        $this->excel->getActiveSheet()->mergeCells('D55:E55');
        $this->excel->getActiveSheet()->setCellValue('D55', 'DIREKTUR,');
        $this->excel->getActiveSheet()->getStyle('D55')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D55')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D55')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D55')->getFont()->setBold(11);

        $this->excel->getActiveSheet()->mergeCells('D59:E59');
        $this->excel->getActiveSheet()->setCellValue('D59', 'TOHAMBA, SE');
        $this->excel->getActiveSheet()->getStyle('D59')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D59')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D59')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D59')->getFont()->setBold(11);

        // SETTING BORDER
        $this->excel->getActiveSheet()->getStyle('A7:A8')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('B7:B8')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('C7:C8')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('D7:D8')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('E7:E8')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('F7:F8')->applyFromArray($outline);

        $this->excel->getActiveSheet()->getStyle('A9:F9')->applyFromArray($all);
        $this->excel->getActiveSheet()->getStyle('A10:A45')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('B10:B45')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('C10:C45')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('D10:D45')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('E10:E45')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('F10:F45')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('C16:F45')->applyFromArray($all);
        // END SETTING BORDER

        
        ob_end_clean();
        $filename='Lak (Laporan Keuangan).xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        $objWriter->save('php://output');
    }

}