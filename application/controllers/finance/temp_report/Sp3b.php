<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sp3b extends Finance_Controller {
	public function __construct(){
        parent::__construct();
        $this->load->library('excel');

    }

    public function index(){
        //load PHPExcel library
		$this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Sp3b');
			
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


        $this->excel->getActiveSheet()->getStyle('A:F')->getFont()->setName('Tahoma');			
        $this->excel->getActiveSheet()->getStyle('A21:F41')->getAlignment()->setWrapText(true);

        $this->excel->getActiveSheet()->getStyle('A21:F41')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('A21:F41')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('A23:A40')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('B23:B40')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('C23:C40')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('D23:D40')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('E23:E40')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('F23:F40')->applyFromArray($outline);

        $this->excel->getActiveSheet()->getStyle('A41:F41')->applyFromArray($all);
        
        //SET DIMENSI TABEL
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(26);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);

        // Value Statted
        $this->excel->getActiveSheet()->mergeCells('A1:F1');
		$this->excel->getActiveSheet()->setCellValue('A1', 'UPT DANA BERGULIR');
		$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->mergeCells('A2:F2');
		$this->excel->getActiveSheet()->setCellValue('A2', 'BADAN LAYANAN UMUM DAERAH "HARUM" KOTA KENDARI');
		$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->mergeCells('A3:F3');
		$this->excel->getActiveSheet()->setCellValue('A3', 'SURAT PERNYATAAN PENGESAHAN PENDAPATAN DAN BELANJA (SP3B)');
		$this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->mergeCells('A4:F4');
		$this->excel->getActiveSheet()->setCellValue('A4', 'TAHUN ANGGARAN 2018');
		$this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);

        
		$this->excel->getActiveSheet()->setCellValue('B5', 'Tanggal :');
        $this->excel->getActiveSheet()->getStyle('B5')->getFont()->setSize(11);
        
        $this->excel->getActiveSheet()->setCellValue('D5', 'Nomor :');
        $this->excel->getActiveSheet()->getStyle('D5')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->getStyle('A5')->applyFromArray($bottom);
        $this->excel->getActiveSheet()->getStyle('B5')->applyFromArray($bottom);
        $this->excel->getActiveSheet()->getStyle('C5')->applyFromArray($bottom);
        $this->excel->getActiveSheet()->getStyle('D5')->applyFromArray($bottom);
        $this->excel->getActiveSheet()->getStyle('E5')->applyFromArray($bottom);
        $this->excel->getActiveSheet()->getStyle('F5')->applyFromArray($bottom);
        
        $this->excel->getActiveSheet()->mergeCells('A7:D7');        
        $this->excel->getActiveSheet()->setCellValue('A7', 'Direktur Unit Pelaksana Teknis Dana Bergulir Kota Kendari memohon kepada :');
        $this->excel->getActiveSheet()->getStyle('A7')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->mergeCells('A8:D8');        
        $this->excel->getActiveSheet()->setCellValue('A8', 'Bendahara Umum Daerah selaku PPKD');
        $this->excel->getActiveSheet()->getStyle('A8')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->mergeCells('A9:D9');        
        $this->excel->getActiveSheet()->setCellValue('A9', 'agar mengesahkan dan membukukan pendapatan dan belanja BLUD sejumlah :');
        $this->excel->getActiveSheet()->getStyle('A9')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->mergeCells('A10:B10');        
        $this->excel->getActiveSheet()->setCellValue('A10', '1. Saldo Awal');
        $this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A10')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->mergeCells('A11:B11');        
        $this->excel->getActiveSheet()->setCellValue('A11', '2. Pendapatan');
        $this->excel->getActiveSheet()->getStyle('A11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A11')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->mergeCells('A12:B12');        
        $this->excel->getActiveSheet()->setCellValue('A12', '3. Belanja');
        $this->excel->getActiveSheet()->getStyle('A12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A12')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A12')->getFont()->setSize(11);
        
        $this->excel->getActiveSheet()->mergeCells('A13:B13');        
        $this->excel->getActiveSheet()->setCellValue('A13', '4. Pembiayan');
        $this->excel->getActiveSheet()->getStyle('A13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A13')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A13')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->mergeCells('A14:B14');        
        $this->excel->getActiveSheet()->setCellValue('A14', '5. Saldo Akhir');
        $this->excel->getActiveSheet()->getStyle('A14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A14')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A14')->getFont()->setSize(11);


        $saldoAwl = 2610732537;
        $this->excel->getActiveSheet()->setCellValue('C10', $saldoAwl);
        $this->excel->getActiveSheet()->getStyle('C10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
		$this->excel->getActiveSheet()->getStyle('C10')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C10')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C10')->getNumberFormat()->setFormatCode($accountingFormat);

        $exmplePendapatanLra = 5430236;
        $this->excel->getActiveSheet()->setCellValue('C11', $exmplePendapatanLra);
        $this->excel->getActiveSheet()->getStyle('C11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
		$this->excel->getActiveSheet()->getStyle('C11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C11')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C11')->getNumberFormat()->setFormatCode($accountingFormat);

        $exmpleBelanjaLra = 1143200;
        $this->excel->getActiveSheet()->setCellValue('C12', $exmpleBelanjaLra);
        $this->excel->getActiveSheet()->getStyle('C12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
		$this->excel->getActiveSheet()->getStyle('C12')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C12')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C12')->getNumberFormat()->setFormatCode($accountingFormat);

        $exmplePembayaranLra = 1150000;
        $this->excel->getActiveSheet()->setCellValue('C13', $exmplePembayaranLra);
        $this->excel->getActiveSheet()->getStyle('C13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
		$this->excel->getActiveSheet()->getStyle('C13')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C13')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C13')->getNumberFormat()->setFormatCode($accountingFormat);

        $saldoAkhir = $saldoAwl + $exmplePendapatanLra - $exmpleBelanjaLra + $exmplePembayaranLra ;
        $this->excel->getActiveSheet()->setCellValue('C14', $saldoAkhir);
        $this->excel->getActiveSheet()->getStyle('C14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
		$this->excel->getActiveSheet()->getStyle('C14')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C14')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C14')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C14')->getNumberFormat()->setFormatCode($accountingFormat);

        $this->excel->getActiveSheet()->mergeCells('A16:D16');        
        $this->excel->getActiveSheet()->setCellValue('A16', 'Untuk Bulan Januari Tahun Anggaran 2018');
        $this->excel->getActiveSheet()->getStyle('A16')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->mergeCells('A17:D17');        
        $this->excel->getActiveSheet()->setCellValue('A17', 'Dasar Pengesahan : Urusan Organisasi Unit Pelaksana Teknis Dana Bergulir Kota Kendari');
        $this->excel->getActiveSheet()->getStyle('A17')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->setCellValue('B18', 'Program,');
        $this->excel->getActiveSheet()->getStyle('B18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B18')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B18')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->setCellValue('C18', 'Kegiatan,');
        $this->excel->getActiveSheet()->getStyle('C18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C18')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C18')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->setCellValue('B19', '………………');
        $this->excel->getActiveSheet()->getStyle('B19')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B19')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B19')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->setCellValue('C19', '………………');
        $this->excel->getActiveSheet()->getStyle('C19')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C19')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C19')->getFont()->setSize(11);

        // HANDLE TABLE
        // HANDLE TABLE HEDER
        $this->excel->getActiveSheet()->mergeCells('A21:B21');
        $this->excel->getActiveSheet()->setCellValue('A21', 'PENDAPATAN');
        $this->excel->getActiveSheet()->getStyle('A21')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A21')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A21')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A21')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->mergeCells('C21:D21');
        $this->excel->getActiveSheet()->setCellValue('C21', 'BELANJA');
        $this->excel->getActiveSheet()->getStyle('C21')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C21')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C21')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C21')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->mergeCells('E21:F21');
        $this->excel->getActiveSheet()->setCellValue('E21', 'PEMBIAYAAN');
        $this->excel->getActiveSheet()->getStyle('E21')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E21')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E21')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('E21')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('A22', 'Kode Rekening');
        $this->excel->getActiveSheet()->getStyle('A22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A22')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A22')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A22')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('B22', 'Jumlah');
        $this->excel->getActiveSheet()->getStyle('B22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B22')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B22')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B22')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('C22', 'Kode Rekening');
        $this->excel->getActiveSheet()->getStyle('C22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C22')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C22')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C22')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('D22', 'Jumlah');
        $this->excel->getActiveSheet()->getStyle('D22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D22')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D22')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D22')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('E22', 'Kode Rekening');
        $this->excel->getActiveSheet()->getStyle('E22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E22')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E22')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('E22')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('F22', 'Jumlah');
        $this->excel->getActiveSheet()->getStyle('F22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('F22')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('F22')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('F22')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->getStyle('A21:F22')->applyFromArray($all);


        // HANDLE TABLE BODY
        $this->excel->getActiveSheet()->setCellValue('A25', '4.1.4');
        $this->excel->getActiveSheet()->getStyle('A25')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A25')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A25')->getFont()->setSize(11);

        $exampleLraPad = 5430236;
        $this->excel->getActiveSheet()->setCellValue('B25', $exampleLraPad);
        $this->excel->getActiveSheet()->getStyle('B25')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B25')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B25')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B25')->getNumberFormat()->setFormatCode($numberFormat);
        
        $this->excel->getActiveSheet()->setCellValue('C23', '5 . 2 . 1 . 02 . 02');
        $this->excel->getActiveSheet()->getStyle('C23')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C23')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C23')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->setCellValue('C25', '5 . 2 . 2 . 01 . 06');
        $this->excel->getActiveSheet()->getStyle('C25')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C25')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C25')->getFont()->setSize(11);

        $h = 27;
        $koderek = '5 . 2 . 2 . 03 . 0';
        for($i = 1; $i <= 3; $i++){
            $this->excel->getActiveSheet()->setCellValue('C'.$h, $koderek.''.$i);
            $this->excel->getActiveSheet()->getStyle('C'.$h)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C'.$h)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C'.$h)->getFont()->setSize(11);
            $h++;
        }


        $b = 30;
        $kd2 = 2;
        $koderek2 = '5 . 2 . 2 . 06 . 0';
        for($i = 1; $i <= 3; $i++){
            $this->excel->getActiveSheet()->setCellValue('C'.$b, $koderek2.''.$kd2);
            $this->excel->getActiveSheet()->getStyle('C'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C'.$b)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C'.$b)->getFont()->setSize(11);
            $b++;
            $kd2++;
        }

        $b2 = 33;
            $kd3 = 1;
            $koderek3 = '5 . 1 . 2 . 11 . 0';
            for($i = 1; $i <= 2; $i++){
                $this->excel->getActiveSheet()->setCellValue('C'.$b2, $koderek3.''.$kd3);
                $this->excel->getActiveSheet()->getStyle('C'.$b2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('C'.$b2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('C'.$b2)->getFont()->setSize(11);
                $b2++;
                $kd3++;
            }

        $b3 = 35;
            $kd4 = 1;
            $koderek4 = '5 . 1 . 2 . 15 . 0';
            for($i = 1; $i <= 2; $i++){
                $this->excel->getActiveSheet()->setCellValue('C'.$b3, $koderek4.''.$kd4);
                $this->excel->getActiveSheet()->getStyle('C'.$b3)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('C'.$b3)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('C'.$b3)->getFont()->setSize(11);
                $b3++;
                $kd4++;
            }

        $b4 = 37;
        $kd5 = 2;
        $koderek5 = '5 . 1 . 2 . 18 . 0';
        for($i = 1; $i <= 2; $i++){
            $this->excel->getActiveSheet()->setCellValue('C'.$b4, $koderek5.''.$kd5);
            $this->excel->getActiveSheet()->getStyle('C'.$b4)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C'.$b4)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C'.$b4)->getFont()->setSize(11);
            $b4++;
            $kd5++;
        }

        $this->excel->getActiveSheet()->setCellValue('C39', '5 . 2 . 2 . 29 . 05');
        $this->excel->getActiveSheet()->getStyle('C39')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C39')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C39')->getFont()->setSize(11);

        $dc = 23;
        for($i = 1; $i <= 5; $i++){
            $this->excel->getActiveSheet()->setCellValue('D'.$dc, 0);
            $this->excel->getActiveSheet()->getStyle('D'.$dc)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('D'.$dc)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D'.$dc)->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D'.$dc)->getNumberFormat()->setFormatCode($numberFormat);
            $dc++;
        }

        $exampleAirLra = 77000; 
        $this->excel->getActiveSheet()->setCellValue('D28', $exampleAirLra);
        $this->excel->getActiveSheet()->getStyle('D28')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->excel->getActiveSheet()->getStyle('D28')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D28')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D28')->getNumberFormat()->setFormatCode($numberFormat);

        $exampleListrikLra = 366000; 
        $this->excel->getActiveSheet()->setCellValue('D29', $exampleListrikLra);
        $this->excel->getActiveSheet()->getStyle('D29')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->excel->getActiveSheet()->getStyle('D29')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D29')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D29')->getNumberFormat()->setFormatCode($numberFormat);

        $examplePengadanLra = 105000; 
        $this->excel->getActiveSheet()->setCellValue('D30', $examplePengadanLra);
        $this->excel->getActiveSheet()->getStyle('D30')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->excel->getActiveSheet()->getStyle('D30')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D30')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D30')->getNumberFormat()->setFormatCode($numberFormat);

        $dc2 = 31;
        for($i = 1; $i <= 2; $i++){
            $this->excel->getActiveSheet()->setCellValue('D'.$dc2, 0);
            $this->excel->getActiveSheet()->getStyle('D'.$dc2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('D'.$dc2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D'.$dc2)->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D'.$dc2)->getNumberFormat()->setFormatCode($numberFormat);
            $dc2++;
        }

        $exampleMakanMinumLra = 187500; 
        $this->excel->getActiveSheet()->setCellValue('D33', $exampleMakanMinumLra);
        $this->excel->getActiveSheet()->getStyle('D33')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->excel->getActiveSheet()->getStyle('D33')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D33')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D33')->getNumberFormat()->setFormatCode($numberFormat);

        $dc3 = 34;
        for($i = 1; $i <= 4; $i++){
            $this->excel->getActiveSheet()->setCellValue('D'.$dc3, 0);
            $this->excel->getActiveSheet()->getStyle('D'.$dc3)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('D'.$dc3)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D'.$dc3)->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D'.$dc3)->getNumberFormat()->setFormatCode($numberFormat);
            $dc2++;
        }

        $exampleBangunanLra = 407700; 
        $this->excel->getActiveSheet()->setCellValue('D38', $exampleBangunanLra);
        $this->excel->getActiveSheet()->getStyle('D38')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->excel->getActiveSheet()->getStyle('D38')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D38')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D38')->getNumberFormat()->setFormatCode($numberFormat);
        

        $this->excel->getActiveSheet()->setCellValue('D39', 0);
        $this->excel->getActiveSheet()->getStyle('D39')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->excel->getActiveSheet()->getStyle('D39')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D39')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D39')->getNumberFormat()->setFormatCode($numberFormat);

        $this->excel->getActiveSheet()->setCellValue('E25', '7.1.6');
        $this->excel->getActiveSheet()->getStyle('E25')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E25')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E25')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->setCellValue('E26', '7.2.2');
        $this->excel->getActiveSheet()->getStyle('E26')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E26')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E26')->getFont()->setSize(11);

        $exmpleAgr = 1150000;
        $this->excel->getActiveSheet()->setCellValue('F25', $exmpleAgr);
        $this->excel->getActiveSheet()->getStyle('F25')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->excel->getActiveSheet()->getStyle('F25')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('F25')->getFont()->setSize(11);



        // HANDLE TABLE FOOTER
        $this->excel->getActiveSheet()->setCellValue('A41', 'Jumlh Pendapatan');
        $this->excel->getActiveSheet()->getStyle('A41')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A41')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A41')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A41')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('B41', ($exampleLraPad + (0 * 16) / 17));
        $this->excel->getActiveSheet()->getStyle('B41')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B41')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B41')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B41')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B41')->getNumberFormat()->setFormatCode($numberFormat);

        $this->excel->getActiveSheet()->setCellValue('C41', 'Jumlah Belanja');
        $this->excel->getActiveSheet()->getStyle('C41')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C41')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C41')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C41')->getFont()->setBold(true);


        $this->excel->getActiveSheet()->setCellValue('D41', ($exampleAirLra + $exampleListrikLra + $examplePengadanLra + $exampleMakanMinumLra + $exampleBangunanLra + (0 * 12) / 17));
        $this->excel->getActiveSheet()->getStyle('D41')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D41')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D41')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D41')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D41')->getNumberFormat()->setFormatCode($numberFormat);

        $this->excel->getActiveSheet()->setCellValue('E41', 'Jumlah Pembiayaan');
        $this->excel->getActiveSheet()->getStyle('E41')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E41')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E41')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('E41')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('F41', ($exmpleAgr + (0 * 16) / 17));
        $this->excel->getActiveSheet()->getStyle('F41')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('F41')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('F41')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('F41')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('F41')->getNumberFormat()->setFormatCode($numberFormat);

        // HANDLE TANDTANGN
        $this->excel->getActiveSheet()->mergeCells('D43:E43');
        $this->excel->getActiveSheet()->setCellValue('D43', 'Kendari, 31 Januari 2018');
        $this->excel->getActiveSheet()->getStyle('D43')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D43')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D43')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->mergeCells('D45:E45');
        $this->excel->getActiveSheet()->setCellValue('D45', 'UPT DANA BERGULIR');
        $this->excel->getActiveSheet()->getStyle('D45')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D45')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D45')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D45')->getFont()->setBold(11);

        $this->excel->getActiveSheet()->mergeCells('D46:E46');
        $this->excel->getActiveSheet()->setCellValue('D46', 'DIREKTUR,');
        $this->excel->getActiveSheet()->getStyle('D46')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D46')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D46')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D46')->getFont()->setBold(11);

        $this->excel->getActiveSheet()->mergeCells('D49:E49');
        $this->excel->getActiveSheet()->setCellValue('D49', 'TOHAMBA, SE');
        $this->excel->getActiveSheet()->getStyle('D49')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D49')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D49')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D49')->getFont()->setBold(11);


        ob_end_clean();
        $filename='Sp3b.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        $objWriter->save('php://output');
    }	
}