<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lra extends Finance_Controller {
	public function __construct(){
        parent::__construct();
        $this->load->library('excel');

	}

    public function index(){
        //load PHPExcel library
			$this->excel->setActiveSheetIndex(0);
                  //name the worksheet
                  $this->excel->getActiveSheet()->setTitle('Laporan Realisasi Anggaran');

					
					//STYLING
  			$styleArray2 = array(
  				'borders' => array('outline' =>
  					array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' =>
  						array('argb' => '0000'),
  						),
  					),
                  );		

            $numberFormat = '#,##0.00';		
								
			//SET DIMENSI TABEL
			$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
			$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(21);
			$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(63);
			$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
			$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
			$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
			$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
			$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
            $this->excel->getActiveSheet()->getStyle('A:I')->getFont()->setName('Tahoma');			
            $this->excel->getActiveSheet()->getStyle('A8:I52')->getAlignment()->setWrapText(true);

		
			$this->excel->getActiveSheet()->mergeCells('A1:I1');
			$this->excel->getActiveSheet()->setCellValue('A1', 'LAPORAN REALISASI ANGGARN');
			$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(11);
			$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
			
			$this->excel->getActiveSheet()->mergeCells('A2:I2');
			$this->excel->getActiveSheet()->setCellValue('A2', 'UPT DANA BERGULIR');
			$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
            
            $this->excel->getActiveSheet()->mergeCells('A3:I3');
			$this->excel->getActiveSheet()->setCellValue('A3', 'BADAN LAYANAN UMUM DAERAH "HARUM"');
			$this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
            
            $this->excel->getActiveSheet()->mergeCells('A4:I4');
			$this->excel->getActiveSheet()->setCellValue('A4', 'KOTA KENDARI');
			$this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);
            

            $this->excel->getActiveSheet()->mergeCells('B6:I6');
			$this->excel->getActiveSheet()->setCellValue('B6', 'Berikut ini kami sampaikan realisasi atas Penggunaan Dana Bulan Januari 2018 Sebagai Berikut');
			$this->excel->getActiveSheet()->getStyle('B6')->getFont()->setSize(11);
			
            
			$this->excel->getActiveSheet()->setCellValue('A8', 'NO');
			$this->excel->getActiveSheet()->getStyle('A8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('A8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A8')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('A8')->getFont()->setBold(true);

            $this->excel->getActiveSheet()->setCellValue('B8', 'Kode Rekening');
			$this->excel->getActiveSheet()->getStyle('B8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('B8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B8')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('B8')->getFont()->setBold(true);

            $this->excel->getActiveSheet()->setCellValue('C8', 'Uraian');
			$this->excel->getActiveSheet()->getStyle('C8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('C8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C8')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('C8')->getFont()->setBold(true);

            $this->excel->getActiveSheet()->setCellValue('D8', 'Jumlah Anggaran');
			$this->excel->getActiveSheet()->getStyle('D8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('D8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D8')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D8')->getFont()->setBold(true);
            
            $this->excel->getActiveSheet()->setCellValue('E8', 'Realisasi Sampai Dengan Bulan Lalu (Rp)');
			$this->excel->getActiveSheet()->getStyle('E8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('E8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('E8')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('E8')->getFont()->setBold(true);

            $this->excel->getActiveSheet()->setCellValue('F8', 'Realisasi Bulan Ini (Rp)');
			$this->excel->getActiveSheet()->getStyle('F8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('F8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F8')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('F8')->getFont()->setBold(true);

            $this->excel->getActiveSheet()->setCellValue('G8', 'Realisasi Sampai Dengan Bulan Ini (Rp)');
			$this->excel->getActiveSheet()->getStyle('G8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('G8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G8')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('G8')->getFont()->setBold(true);

            $this->excel->getActiveSheet()->setCellValue('H8', 'Selisih / (Kurang) (Rp)');
			$this->excel->getActiveSheet()->getStyle('H8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('H8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H8')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('H8')->getFont()->setBold(true);

            $this->excel->getActiveSheet()->setCellValue('I8', '%');
			$this->excel->getActiveSheet()->getStyle('I8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('I8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('I8')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('I8')->getFont()->setBold(true);
            
            
            // HANDLE PENDAPTAN:
            $this->excel->getActiveSheet()->setCellValue('A10', '1');
			$this->excel->getActiveSheet()->getStyle('A10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('A10')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A10')->getFont()->setSize(11);

            //  KODE REKENING VALUE 1
            $this->excel->getActiveSheet()->setCellValue('B11', '4.1');
			$this->excel->getActiveSheet()->getStyle('B11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('B11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B11')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('B13', '4.1.4');
			$this->excel->getActiveSheet()->getStyle('B13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('B13')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B13')->getFont()->setSize(11);

            //  URAIAN VALUE 1
            $this->excel->getActiveSheet()->setCellValue('C10', 'Pendapatan');
			$this->excel->getActiveSheet()->getStyle('C10')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C10')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('C10')->getFont()->setBold(true);

            $this->excel->getActiveSheet()->setCellValue('C11', 'PENDAPATAN ASLI DAERAH (PAD) - LRA');
			$this->excel->getActiveSheet()->getStyle('C11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C11')->getFont()->setSize(11);

            // JUMLH
            $this->excel->getActiveSheet()->setCellValue('C15', 'Jumlah');
			$this->excel->getActiveSheet()->getStyle('C15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('C15')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C15')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('C15')->getFont()->setBold(true);

            // Jumlah Anggaran
            $this->excel->getActiveSheet()->setCellValue('D11', 0);
			$this->excel->getActiveSheet()->getStyle('D11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('D11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D11')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D11')->getNumberFormat()->setFormatCode($numberFormat);

            $this->excel->getActiveSheet()->setCellValue('D13', 0);
			$this->excel->getActiveSheet()->getStyle('D13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('D13')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D13')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D13')->getNumberFormat()->setFormatCode($numberFormat);

            $this->excel->getActiveSheet()->setCellValue('D15', 0);
            $this->excel->getActiveSheet()->getStyle('D15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('D15')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D15')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D15')->getNumberFormat()->setFormatCode($numberFormat);

            // Realisasi Bulan Lalu
            $this->excel->getActiveSheet()->setCellValue('E11', 0);
			$this->excel->getActiveSheet()->getStyle('E11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('E11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('E11')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('E11')->getNumberFormat()->setFormatCode($numberFormat);

            $this->excel->getActiveSheet()->setCellValue('E13', 0);
			$this->excel->getActiveSheet()->getStyle('E13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('E13')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('E13')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('E11')->getNumberFormat()->setFormatCode($numberFormat);

            $this->excel->getActiveSheet()->setCellValue('E15', 0);
            $this->excel->getActiveSheet()->getStyle('E15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('E15')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('E15')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('E15')->getNumberFormat()->setFormatCode($numberFormat);

            // Realisasi Bulan Ini
            $this->excel->getActiveSheet()->setCellValue('F11', 0);
			$this->excel->getActiveSheet()->getStyle('F11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('F11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F11')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('F11')->getNumberFormat()->setFormatCode($numberFormat);

            $rbi = 5430236;
            $this->excel->getActiveSheet()->setCellValue('F13', $rbi);
			$this->excel->getActiveSheet()->getStyle('F13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('F13')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F13')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D15')->getNumberFormat()->setFormatCode($numberFormat);

            $rbi2 = 5430236;
            $this->excel->getActiveSheet()->setCellValue('F15', $rbi2);
            $this->excel->getActiveSheet()->getStyle('F15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('F15')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F15')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('F15')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('D15')->getNumberFormat()->setFormatCode($numberFormat);



            // Realisasi Sampai Bulan Ini
            $this->excel->getActiveSheet()->setCellValue('G11', 0);
			$this->excel->getActiveSheet()->getStyle('G11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('G11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G11')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('G11')->getNumberFormat()->setFormatCode($numberFormat);

            $rsbi  =  5430236;
            $this->excel->getActiveSheet()->setCellValue('G13', $rsbi);
			$this->excel->getActiveSheet()->getStyle('G13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('G13')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G13')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('G13')->getNumberFormat()->setFormatCode($numberFormat);

            $rsbi2  =  5430236;
            $this->excel->getActiveSheet()->setCellValue('G15', $rsbi2);
            $this->excel->getActiveSheet()->getStyle('G15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('G15')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G15')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('G15')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('G15')->getNumberFormat()->setFormatCode($numberFormat);

            // Selisih
            $this->excel->getActiveSheet()->setCellValue('H11', 0);
			$this->excel->getActiveSheet()->getStyle('H11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('H11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H11')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('H11')->getNumberFormat()->setFormatCode($numberFormat);

            $slsh = 5430236;
            $this->excel->getActiveSheet()->setCellValue('H13', $slsh);
			$this->excel->getActiveSheet()->getStyle('H13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('H13')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H13')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('H13')->getNumberFormat()->setFormatCode($numberFormat);

            $slsh2 = 5430236;
            $this->excel->getActiveSheet()->setCellValue('H15', $slsh2);
            $this->excel->getActiveSheet()->getStyle('H15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('H15')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H15')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('H15')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('H15')->getNumberFormat()->setFormatCode($numberFormat);


            //  HANDLE BELANJA:
            $this->excel->getActiveSheet()->setCellValue('A18', '2');
			$this->excel->getActiveSheet()->getStyle('A18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('A18')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A18')->getFont()->setSize(11);


            // No Rekening:
            $this->excel->getActiveSheet()->setCellValue('B19', '5.2.1');
			$this->excel->getActiveSheet()->getStyle('B19')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('B19')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B19')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('B20', '5 . 2 . 1 . 02 . 02');
			$this->excel->getActiveSheet()->getStyle('B20')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('B20')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B20')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('B22', '5 . 2 . 2 . 01 . 06');
			$this->excel->getActiveSheet()->getStyle('B22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('B22')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B22')->getFont()->setSize(11);

            // URAIAN 
            $this->excel->getActiveSheet()->setCellValue('C18', 'Belanja');
			$this->excel->getActiveSheet()->getStyle('C18')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C18')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('C18')->getFont()->setBold(true);

            $this->excel->getActiveSheet()->setCellValue('C19', 'Belanja Pegawai');
			$this->excel->getActiveSheet()->getStyle('C19')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C19')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('C19')->getFont()->setBold(true);

            $this->excel->getActiveSheet()->setCellValue('C20', 'Insentif Hari Raya/ Tahunan');
			$this->excel->getActiveSheet()->getStyle('C20')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C20')->getFont()->setSize(11);

            $bns = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => 'FF0000'),
                'size'  => 11,
            ));
            $this->excel->getActiveSheet()->setCellValue('C21', 'Bonus Pengelola UPTD');
			$this->excel->getActiveSheet()->getStyle('C21')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C21')->applyFromArray($bns);

            $this->excel->getActiveSheet()->setCellValue('C22', 'Honorarium PNS yang diperbantukan di UPTD');
			$this->excel->getActiveSheet()->getStyle('C22')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C22')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('C23', 'Honorarium non PNS');
			$this->excel->getActiveSheet()->getStyle('C23')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C23')->getFont()->setSize(11);
            
            // Jumlah Anggaran
            $b1 = 29500000;
            $this->excel->getActiveSheet()->setCellValue('D20', $b1);
			$this->excel->getActiveSheet()->getStyle('D20')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('D20')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D20')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D20')->getNumberFormat()->setFormatCode($numberFormat);
            
            $b2 = 114000000;
            $this->excel->getActiveSheet()->setCellValue('D21', $b2);
			$this->excel->getActiveSheet()->getStyle('D21')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('D21')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D21')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D21')->getNumberFormat()->setFormatCode($numberFormat);


            $b3 = 48000000;
            $this->excel->getActiveSheet()->setCellValue('D22', $b3);
			$this->excel->getActiveSheet()->getStyle('D22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('D22')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D22')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D22')->getNumberFormat()->setFormatCode($numberFormat);


            $b4 = 354000000;
            $this->excel->getActiveSheet()->setCellValue('D23', $b4);
			$this->excel->getActiveSheet()->getStyle('D23')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('D23')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D23')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D23')->getNumberFormat()->setFormatCode($numberFormat);


            $sum = ($b1 + $b2 + $b3 + $b4) / 4;
            $this->excel->getActiveSheet()->setCellValue('D24', $sum);
			$this->excel->getActiveSheet()->getStyle('D24')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('D24')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D24')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D24')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('D24')->getNumberFormat()->setFormatCode($numberFormat);
            $this->excel->getActiveSheet()->getStyle('D24')->getFont()->setBold(true);


            // Realisasi Sampi bulan Lalu
            $this->excel->getActiveSheet()->setCellValue('E20', '-');
			$this->excel->getActiveSheet()->getStyle('E20')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('E20')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('E20')->getFont()->setSize(11);
            
            $this->excel->getActiveSheet()->setCellValue('E21', '-');
			$this->excel->getActiveSheet()->getStyle('E21')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('E21')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('E21')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('E22', '-');
			$this->excel->getActiveSheet()->getStyle('E22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('E22')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('E22')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('E23', '-');
			$this->excel->getActiveSheet()->getStyle('E23')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('E23')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('E23')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('E24', '-');
			$this->excel->getActiveSheet()->getStyle('E24')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('E24')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('E24')->getFont()->setSize(11);


            // Realisasi Bulan ini
            $this->excel->getActiveSheet()->setCellValue('F20', '-');
			$this->excel->getActiveSheet()->getStyle('F20')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('F20')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F20')->getFont()->setSize(11);
            
            $this->excel->getActiveSheet()->setCellValue('F21', '-');
			$this->excel->getActiveSheet()->getStyle('F21')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('F21')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F21')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('F22', '-');
			$this->excel->getActiveSheet()->getStyle('F22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('F22')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F22')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('F23', '-');
			$this->excel->getActiveSheet()->getStyle('F23')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('F23')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F23')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('F24', '-');
			$this->excel->getActiveSheet()->getStyle('F24')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('F24')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F24')->getFont()->setSize(11);


            // Realisasi Sampai Bulan Ini
            $this->excel->getActiveSheet()->setCellValue('G20', '-');
			$this->excel->getActiveSheet()->getStyle('G20')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('G20')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G20')->getFont()->setSize(11);
            
            $this->excel->getActiveSheet()->setCellValue('G21', '-');
			$this->excel->getActiveSheet()->getStyle('G21')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('G21')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G21')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('G22', '-');
			$this->excel->getActiveSheet()->getStyle('G22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('G22')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G22')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('G23', '-');
			$this->excel->getActiveSheet()->getStyle('G23')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('G23')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G23')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('G24', '-');
			$this->excel->getActiveSheet()->getStyle('G24')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('G24')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G24')->getFont()->setSize(11);


            // SELISIH
            $s1 = 29500000;
            $this->excel->getActiveSheet()->setCellValue('H20', $s1);
			$this->excel->getActiveSheet()->getStyle('H20')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('H20')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H20')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('H20')->getNumberFormat()->setFormatCode($numberFormat);

            
            $s2 = 114000000;
            $this->excel->getActiveSheet()->setCellValue('H21', $s2);
			$this->excel->getActiveSheet()->getStyle('H21')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('H21')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H21')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('H21')->getNumberFormat()->setFormatCode($numberFormat);

            $s3 = 48000000;
            $this->excel->getActiveSheet()->setCellValue('H22', $s3);
			$this->excel->getActiveSheet()->getStyle('H22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('H22')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H22')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('H22')->getNumberFormat()->setFormatCode($numberFormat);

            $s4 = 354000000;
            $this->excel->getActiveSheet()->setCellValue('H23', $s4);
			$this->excel->getActiveSheet()->getStyle('H23')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('H23')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H23')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('H23')->getNumberFormat()->setFormatCode($numberFormat);


            $sum2 = ($s1 + $s2 + $s3 + $s4) / 4;
            $this->excel->getActiveSheet()->setCellValue('H24', $sum2);
			$this->excel->getActiveSheet()->getStyle('H24')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->excel->getActiveSheet()->getStyle('H24')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H24')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('H24')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('H24')->getNumberFormat()->setFormatCode($numberFormat);


            //  Belnja Barang Jsa Rekening

            $h = 26;
            $koderek = '5 . 2 . 2 . 03 . 0';
            for($i = 1; $i <= 3; $i++){
                $this->excel->getActiveSheet()->setCellValue('B'.$h, $koderek.''.$i);
                $this->excel->getActiveSheet()->getStyle('B'.$h)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('B'.$h)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('B'.$h)->getFont()->setSize(11);
                $h++;
            }


            $b = 29;
            $kd2 = 2;
            $koderek2 = '5 . 2 . 2 . 06 . 0';
            for($i = 1; $i <= 3; $i++){
                $this->excel->getActiveSheet()->setCellValue('B'.$b, $koderek.''.$kd2);
                $this->excel->getActiveSheet()->getStyle('B'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('B'.$b)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('B'.$b)->getFont()->setSize(11);
                $b++;
                $kd2++;
            }
            

            $this->excel->getActiveSheet()->setCellValue('B32', '5 . 1 . 2 . 11 . 01');
            $this->excel->getActiveSheet()->getStyle('B32')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B32')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B32')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('B33', '5 . 1 . 2 . 11 . 02');
            $this->excel->getActiveSheet()->getStyle('B33')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B33')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B33')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('B34', '5 . 1 . 2 . 15 . 01');
            $this->excel->getActiveSheet()->getStyle('B34')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B34')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B34')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('B35', '5 . 1 . 2 . 15 . 02');
            $this->excel->getActiveSheet()->getStyle('B35')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B35')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B35')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('B36', '5 . 1 . 2 . 18 . 02');
            $this->excel->getActiveSheet()->getStyle('B36')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B36')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B36')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('B37', '5 . 1 . 2 . 18 . 03');
            $this->excel->getActiveSheet()->getStyle('B37')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B37')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B37')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('B40', '5 . 2 . 2 . 29 . 05');
            $this->excel->getActiveSheet()->getStyle('B40')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B40')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B40')->getFont()->setSize(11);


            // Uraian : 
            $this->excel->getActiveSheet()->setCellValue('C25', 'Belanja Barang Jasa');
            $this->excel->getActiveSheet()->getStyle('C25')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C25')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('C25')->getFont()->setBold(true);

            $this->excel->getActiveSheet()->setCellValue('C26', 'Belanja Alat Tulis Kantor');
            $this->excel->getActiveSheet()->getStyle('C26')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C26')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('C27', 'Belanja Air');
            $this->excel->getActiveSheet()->getStyle('C27')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C27')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('C28', 'Belanja Listrik');
            $this->excel->getActiveSheet()->getStyle('C28')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C28')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('C29', 'Belanja Penggandaan');
            $this->excel->getActiveSheet()->getStyle('C29')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C29')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('C30', 'Belanja Surat Kabar');
            $this->excel->getActiveSheet()->getStyle('C30')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C30')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('C31', 'Pembayaran PBB');
            $this->excel->getActiveSheet()->getStyle('C31')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C31')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('C32', 'Belanja Makanan dan Minuman Harian Pegawai');
            $this->excel->getActiveSheet()->getStyle('C32')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C32')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('C33', 'Belanja Makanan dan Minuman Rapat');
            $this->excel->getActiveSheet()->getStyle('C33')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C33')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('C34', 'Belanja Perjalanan Dinas Dalam Daerah');
            $this->excel->getActiveSheet()->getStyle('C34')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C34')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('C35', 'Belanja Perjalanan Dinas Luar Daerah');
            $this->excel->getActiveSheet()->getStyle('C35')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C35')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('C36', 'Belanja Pemeliharan Peralatan dan Mesin');
            $this->excel->getActiveSheet()->getStyle('C36')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C36')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('C37', 'Belanja Pemeliharan Gedung dan Bangunan');
            $this->excel->getActiveSheet()->getStyle('C37')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C37')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('C40', 'Belanja Modal Peralatan dan Mesin - Pengadaan Peralatan Personal Komputer - Printer');
            $this->excel->getActiveSheet()->getStyle('C40')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C40')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('C42', 'Jumlah');
            $this->excel->getActiveSheet()->getStyle('C42')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C42')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C42')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('C42')->getFont()->setBold(true);

            $this->excel->getActiveSheet()->setCellValue('C43', 'SURPLUS/ (DEFISIT)');
            $this->excel->getActiveSheet()->getStyle('C43')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('C43')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C43')->getFont()->setBold(true);



            //  Realisasi Anggaran
            $r1 = 25000000;
            $this->excel->getActiveSheet()->setCellValue('D26', $r1);
            $this->excel->getActiveSheet()->getStyle('D26')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('D26')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D26')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D26')->getNumberFormat()->setFormatCode($numberFormat);


            $r2 = 6000000;
            $this->excel->getActiveSheet()->setCellValue('D27', $r2);
            $this->excel->getActiveSheet()->getStyle('D27')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('D27')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D27')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D27')->getNumberFormat()->setFormatCode($numberFormat);

            $r3 = 12000000;
            $this->excel->getActiveSheet()->setCellValue('D28', $r3);
            $this->excel->getActiveSheet()->getStyle('D28')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('D28')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D28')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D28')->getNumberFormat()->setFormatCode($numberFormat);

            $r4 = 7200000;
            $this->excel->getActiveSheet()->setCellValue('D29', $r4);
            $this->excel->getActiveSheet()->getStyle('D29')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('D29')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D29')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D29')->getNumberFormat()->setFormatCode($numberFormat);

            $r5 = 2400000;
            $this->excel->getActiveSheet()->setCellValue('D30', $r5);
            $this->excel->getActiveSheet()->getStyle('D30')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('D30')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D30')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D30')->getNumberFormat()->setFormatCode($numberFormat);

            $r6 = 3000000;
            $this->excel->getActiveSheet()->setCellValue('D31', $r6);
            $this->excel->getActiveSheet()->getStyle('D31')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('D31')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D31')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D31')->getNumberFormat()->setFormatCode($numberFormat);

            $r7 = 6150000;
            $this->excel->getActiveSheet()->setCellValue('D32', $r7);
            $this->excel->getActiveSheet()->getStyle('D32')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('D32')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D32')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D32')->getNumberFormat()->setFormatCode($numberFormat);


            $r8 = 5000000;
            $this->excel->getActiveSheet()->setCellValue('D33', $r8);
            $this->excel->getActiveSheet()->getStyle('D33')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('D33')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D33')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D33')->getNumberFormat()->setFormatCode($numberFormat);

            $r9 = 90000000;
            $this->excel->getActiveSheet()->setCellValue('D34', $r9);
            $this->excel->getActiveSheet()->getStyle('D34')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('D34')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D34')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D34')->getNumberFormat()->setFormatCode($numberFormat);

            $r10 = 6000000;
            $this->excel->getActiveSheet()->setCellValue('D35', $r10);
            $this->excel->getActiveSheet()->getStyle('D35')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('D35')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D35')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D35')->getNumberFormat()->setFormatCode($numberFormat);

            $r11 = 6000000;
            $this->excel->getActiveSheet()->setCellValue('D36', $r11);
            $this->excel->getActiveSheet()->getStyle('D36')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('D36')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D36')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D36')->getNumberFormat()->setFormatCode($numberFormat);

            $r12 = 20000000;
            $this->excel->getActiveSheet()->setCellValue('D37', $r12);
            $this->excel->getActiveSheet()->getStyle('D37')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('D37')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D37')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D37')->getNumberFormat()->setFormatCode($numberFormat);

            $mysum = ($r1 + $r2 + $r3 + $r4 + $r5 + $r6 + $r7 + $r8 + $r9 + $r10 + $r11 + $r12) / 12;
            $this->excel->getActiveSheet()->setCellValue('D38', $mysum);
            $this->excel->getActiveSheet()->getStyle('D38')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('D38')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D38')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D38')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('D38')->getNumberFormat()->setFormatCode($numberFormat);

            $da = 5000000;
            $this->excel->getActiveSheet()->setCellValue('D40', $da);
            $this->excel->getActiveSheet()->getStyle('D40')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('D40')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D40')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D40')->getNumberFormat()->setFormatCode($numberFormat);

            $jlmhall = $sum2 + $mysum + $da;
            $this->excel->getActiveSheet()->setCellValue('D42', $jlmhall);
            $this->excel->getActiveSheet()->getStyle('D42')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('D42')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D42')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D42')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('D42')->getNumberFormat()->setFormatCode($numberFormat);


            //  Realisasi Tahun ini
            $rth2 = 77000;
            $this->excel->getActiveSheet()->setCellValue('F27', $rth2);
            $this->excel->getActiveSheet()->getStyle('F27')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('F27')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F27')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('F27')->getNumberFormat()->setFormatCode($numberFormat);

            $rth3 = 366000;
            $this->excel->getActiveSheet()->setCellValue('F28', $rth3);
            $this->excel->getActiveSheet()->getStyle('F28')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('F28')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F28')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('F28')->getNumberFormat()->setFormatCode($numberFormat);

            $rth4 = 105000;
            $this->excel->getActiveSheet()->setCellValue('F29', $rth4);
            $this->excel->getActiveSheet()->getStyle('F29')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('F29')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F29')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('F29')->getNumberFormat()->setFormatCode($numberFormat);

            $rth7 = 187500;
            $this->excel->getActiveSheet()->setCellValue('F32', $rth7);
            $this->excel->getActiveSheet()->getStyle('F32')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('F32')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F32')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('F32')->getNumberFormat()->setFormatCode($numberFormat);

            $rth12 = 407700;
            $this->excel->getActiveSheet()->setCellValue('F37', $rth12);
            $this->excel->getActiveSheet()->getStyle('F37')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('F37')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F37')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('F37')->getNumberFormat()->setFormatCode($numberFormat);

            $sum3 = ($rth2 + $rth3 + $rth3 + $rth7 + $rth12) / 12;
            $this->excel->getActiveSheet()->setCellValue('F38', $sum3);
            $this->excel->getActiveSheet()->getStyle('F38')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('F38')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F38')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('F38')->getFont()->setBold(11);
            $this->excel->getActiveSheet()->getStyle('F38')->getNumberFormat()->setFormatCode($numberFormat);

            $this->excel->getActiveSheet()->setCellValue('F42', $sum3);
            $this->excel->getActiveSheet()->getStyle('F42')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('F42')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F42')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('F42')->getFont()->setBold(11);
            $this->excel->getActiveSheet()->getStyle('F42')->getNumberFormat()->setFormatCode($numberFormat);

            $this->excel->getActiveSheet()->setCellValue('F43', $sum3 + 5430236);
            $this->excel->getActiveSheet()->getStyle('F43')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('F43')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F43')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('F43')->getFont()->setBold(11);
            $this->excel->getActiveSheet()->getStyle('F43')->getNumberFormat()->setFormatCode($numberFormat);


            //  Realisasi Tahun ini
            $this->excel->getActiveSheet()->setCellValue('G27', $rth2);
            $this->excel->getActiveSheet()->getStyle('G27')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('G27')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G27')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('G27')->getNumberFormat()->setFormatCode($numberFormat);

            $this->excel->getActiveSheet()->setCellValue('G28', $rth3);
            $this->excel->getActiveSheet()->getStyle('G28')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('G28')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G28')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('G28')->getNumberFormat()->setFormatCode($numberFormat);
            
            $this->excel->getActiveSheet()->setCellValue('G29', $rth4);
            $this->excel->getActiveSheet()->getStyle('G29')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('G29')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G29')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('G29')->getNumberFormat()->setFormatCode($numberFormat);

            $this->excel->getActiveSheet()->setCellValue('G32', $rth7);
            $this->excel->getActiveSheet()->getStyle('G32')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('G32')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G32')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('G32')->getNumberFormat()->setFormatCode($numberFormat);
            
            $this->excel->getActiveSheet()->setCellValue('G37', $rth12);
            $this->excel->getActiveSheet()->getStyle('G37')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('G37')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G37')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('G37')->getNumberFormat()->setFormatCode($numberFormat);

            $this->excel->getActiveSheet()->setCellValue('G38', $sum3);
            $this->excel->getActiveSheet()->getStyle('G38')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('G38')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G38')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('G38')->getFont()->setBold(11);
            $this->excel->getActiveSheet()->getStyle('G38')->getNumberFormat()->setFormatCode($numberFormat);

            $this->excel->getActiveSheet()->setCellValue('G42', $sum3);
            $this->excel->getActiveSheet()->getStyle('G42')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('G42')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G42')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('G42')->getFont()->setBold(11);
            $this->excel->getActiveSheet()->getStyle('G42')->getNumberFormat()->setFormatCode($numberFormat);

            $this->excel->getActiveSheet()->setCellValue('G43', $sum3 + 5430236);
            $this->excel->getActiveSheet()->getStyle('G43')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('G43')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G43')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('G43')->getFont()->setBold(11);
            $this->excel->getActiveSheet()->getStyle('G43')->getNumberFormat()->setFormatCode($numberFormat);

            //  Realisasi Anggaran
            $this->excel->getActiveSheet()->setCellValue('H26', $r1);
            $this->excel->getActiveSheet()->getStyle('H26')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('H26')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H26')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('H26')->getNumberFormat()->setFormatCode($numberFormat);

            $this->excel->getActiveSheet()->setCellValue('H27', $r2);
            $this->excel->getActiveSheet()->getStyle('H27')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('H27')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H27')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('H27')->getNumberFormat()->setFormatCode($numberFormat);            

            $this->excel->getActiveSheet()->setCellValue('H28', $r3);
            $this->excel->getActiveSheet()->getStyle('H28')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('H28')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H28')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('H28')->getNumberFormat()->setFormatCode($numberFormat);

            $this->excel->getActiveSheet()->setCellValue('H29', $r4);
            $this->excel->getActiveSheet()->getStyle('H29')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('H29')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H29')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('H29')->getNumberFormat()->setFormatCode($numberFormat);

            $this->excel->getActiveSheet()->setCellValue('H30', $r5);
            $this->excel->getActiveSheet()->getStyle('H30')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('H30')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H30')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('H30')->getNumberFormat()->setFormatCode($numberFormat);

            $this->excel->getActiveSheet()->setCellValue('H31', $r6);
            $this->excel->getActiveSheet()->getStyle('H31')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('H31')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H31')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('H31')->getNumberFormat()->setFormatCode($numberFormat);

            $this->excel->getActiveSheet()->setCellValue('H32', $r7);
            $this->excel->getActiveSheet()->getStyle('H32')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('H32')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H32')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('H32')->getNumberFormat()->setFormatCode($numberFormat);

            $this->excel->getActiveSheet()->setCellValue('H33', $r8);
            $this->excel->getActiveSheet()->getStyle('H33')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('H33')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H33')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('H33')->getNumberFormat()->setFormatCode($numberFormat);

            $this->excel->getActiveSheet()->setCellValue('H34', $r9);
            $this->excel->getActiveSheet()->getStyle('H34')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('H34')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H34')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('H34')->getNumberFormat()->setFormatCode($numberFormat);

            $this->excel->getActiveSheet()->setCellValue('H35', $r10);
            $this->excel->getActiveSheet()->getStyle('H35')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('H35')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H35')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('H35')->getNumberFormat()->setFormatCode($numberFormat);

            $this->excel->getActiveSheet()->setCellValue('H36', $r11);
            $this->excel->getActiveSheet()->getStyle('H36')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('H36')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H36')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('H36')->getNumberFormat()->setFormatCode($numberFormat);

            $this->excel->getActiveSheet()->setCellValue('H37', $r12);
            $this->excel->getActiveSheet()->getStyle('H37')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('H37')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H37')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('H37')->getNumberFormat()->setFormatCode($numberFormat);

            $this->excel->getActiveSheet()->setCellValue('H38', $mysum);
            $this->excel->getActiveSheet()->getStyle('H38')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('H38')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H38')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('H38')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('H38')->getNumberFormat()->setFormatCode($numberFormat);

            $this->excel->getActiveSheet()->setCellValue('H40', $da);
            $this->excel->getActiveSheet()->getStyle('H40')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('H40')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H40')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('H40')->getNumberFormat()->setFormatCode($numberFormat);

    
            $this->excel->getActiveSheet()->setCellValue('H42', $jlmhall);
            $this->excel->getActiveSheet()->getStyle('H42')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('H42')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H42')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('H42')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('H42')->getNumberFormat()->setFormatCode($numberFormat);


            // hANDLE Pembiayaan
            $this->excel->getActiveSheet()->setCellValue('B48', '7 . 1 . 6');
            $this->excel->getActiveSheet()->getStyle('B48')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B48')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B48')->getFont()->setSize(11);
            
            $this->excel->getActiveSheet()->setCellValue('B50', '7 . 2 . 2');
            $this->excel->getActiveSheet()->getStyle('B50')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B50')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B50')->getFont()->setSize(11);

            // sxsx
            $this->excel->getActiveSheet()->setCellValue('C46', 'Pembiayaan');
            $this->excel->getActiveSheet()->getStyle('C46')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C46')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('C46')->getFont()->setBold(true);

            $this->excel->getActiveSheet()->setCellValue('C47', 'Penerimaan Pembiayaan');
            $this->excel->getActiveSheet()->getStyle('C47')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C47')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('C47')->getFont()->setBold(true);

            $this->excel->getActiveSheet()->setCellValue('C48', 'Penerimaan Kembali Pinjaman dana Bergulir');
            $this->excel->getActiveSheet()->getStyle('C58')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C58')->getFont()->setSize(11);


            $this->excel->getActiveSheet()->setCellValue('C49', 'Pengeluaran Pembiayaan');
            $this->excel->getActiveSheet()->getStyle('C49')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C49')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('C49')->getFont()->setBold(true);

            $this->excel->getActiveSheet()->setCellValue('C50', 'Penerimaan Pinjaman dana Bergulir');
            $this->excel->getActiveSheet()->getStyle('C50')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C50')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('C51', 'Pembiayaan Netto');
            $this->excel->getActiveSheet()->getStyle('C51')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('C51')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C51')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('C51')->getFont()->setBold(true);

            // kjhdsihds
            $pb1 = 324000000;
            $this->excel->getActiveSheet()->setCellValue('D48', $pb1);
            $this->excel->getActiveSheet()->getStyle('D48')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('D48')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D48')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D48')->getNumberFormat()->setFormatCode($numberFormat);

            $pb2 = 331250000;
            $this->excel->getActiveSheet()->setCellValue('D50', $pb2);
            $this->excel->getActiveSheet()->getStyle('D50')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('D50')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D50')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D50')->getNumberFormat()->setFormatCode($numberFormat);


            $pb3 = 1150000;
            $this->excel->getActiveSheet()->setCellValue('F48', $pb3);
            $this->excel->getActiveSheet()->getStyle('F48')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('F48')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F48')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('F48')->getNumberFormat()->setFormatCode($numberFormat);

            $pb4 = 0;
            $this->excel->getActiveSheet()->setCellValue('F50', $pb4);
            $this->excel->getActiveSheet()->getStyle('F50')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('F50')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F50')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('F50')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('F50')->getNumberFormat()->setFormatCode($numberFormat);

            $this->excel->getActiveSheet()->setCellValue('F51', $pb3 - $pb4);
            $this->excel->getActiveSheet()->getStyle('F51')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('F51')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F51')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('F51')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('F51')->getNumberFormat()->setFormatCode($numberFormat);

            $pb5 = 1150000;
            $this->excel->getActiveSheet()->setCellValue('G48', $pb5);
            $this->excel->getActiveSheet()->getStyle('G48')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('G48')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G48')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('G48')->getNumberFormat()->setFormatCode($numberFormat);

            $pb6 = 0;
            $this->excel->getActiveSheet()->setCellValue('G50', $pb6);
            $this->excel->getActiveSheet()->getStyle('G50')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('G50')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G50')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('G50')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('G50')->getNumberFormat()->setFormatCode($numberFormat);

            $this->excel->getActiveSheet()->setCellValue('G51', $pb5 - $pb6);
            $this->excel->getActiveSheet()->getStyle('G51')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('G51')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G51')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('G51')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('G51')->getNumberFormat()->setFormatCode($numberFormat);

            $pb7 = 324000000;
            $this->excel->getActiveSheet()->setCellValue('H48', $pb7);
            $this->excel->getActiveSheet()->getStyle('H48')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('H48')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H48')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('H48')->getNumberFormat()->setFormatCode($numberFormat);

            $pb8 = 331250000;
            $this->excel->getActiveSheet()->setCellValue('H50', $pb8);
            $this->excel->getActiveSheet()->getStyle('H50')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('H50')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H50')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('H50')->getNumberFormat()->setFormatCode($numberFormat);

            $this->excel->getActiveSheet()->mergeCells('G56:H56');
            $this->excel->getActiveSheet()->setCellValue('G56', 'Kendari, 31 Januari 2018');
            $this->excel->getActiveSheet()->getStyle('G56')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G56')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G56')->getFont()->setSize(11);

            $this->excel->getActiveSheet()->mergeCells('G57:H57');
            $this->excel->getActiveSheet()->setCellValue('G57', 'UPT DANA BERGULIR');
            $this->excel->getActiveSheet()->getStyle('G57')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G57')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G57')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('G57')->getFont()->setBold(11);

            $this->excel->getActiveSheet()->mergeCells('G58:H58');
            $this->excel->getActiveSheet()->setCellValue('G58', 'DIREKTUR,');
            $this->excel->getActiveSheet()->getStyle('G58')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G58')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G58')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('G58')->getFont()->setBold(11);

            $this->excel->getActiveSheet()->mergeCells('G61:H61');
            $this->excel->getActiveSheet()->setCellValue('G61', 'TOHAMBA, SE');
            $this->excel->getActiveSheet()->getStyle('G61')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G61')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G61')->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('G61')->getFont()->setBold(11);


            // ALL Border     

            $all = array(
  				'borders' => array('allborders' =>
  					array(
                          'style' => PHPExcel_Style_Border::BORDER_THIN,
                          'color' => array('argb' => '0000'),
  						),
                      ),
  			    );	
            $no = 52;
            $this->excel->getActiveSheet()->getStyle('A9:A52')->applyFromArray($styleArray2);
            $this->excel->getActiveSheet()->getStyle('B9:B52')->applyFromArray($styleArray2);
            $this->excel->getActiveSheet()->getStyle('C9:C52')->applyFromArray($styleArray2);
            $this->excel->getActiveSheet()->getStyle('D9:D52')->applyFromArray($styleArray2);
            $this->excel->getActiveSheet()->getStyle('E9:E52')->applyFromArray($styleArray2);
            $this->excel->getActiveSheet()->getStyle('F9:F52')->applyFromArray($styleArray2);
            $this->excel->getActiveSheet()->getStyle('G9:G52')->applyFromArray($styleArray2);
            $this->excel->getActiveSheet()->getStyle('H9:H52')->applyFromArray($styleArray2);
            $this->excel->getActiveSheet()->getStyle('I9:I52')->applyFromArray($styleArray2);

            $this->excel->getActiveSheet()->getStyle('A8:I52')->applyFromArray($styleArray2);
            $this->excel->getActiveSheet()->getStyle('D24:H24')->applyFromArray($all);
            $this->excel->getActiveSheet()->getStyle('D24:H24')->applyFromArray($all);
            $this->excel->getActiveSheet()->getStyle('D15:H15')->applyFromArray($all);
            $this->excel->getActiveSheet()->getStyle('D16:H16')->applyFromArray($all);
            $this->excel->getActiveSheet()->getStyle('D38:H38')->applyFromArray($all);
            $this->excel->getActiveSheet()->getStyle('D42:H42')->applyFromArray($all);
            $this->excel->getActiveSheet()->getStyle('D43:H43')->applyFromArray($all);
            $this->excel->getActiveSheet()->getStyle('D44:H44')->applyFromArray($all);
            $this->excel->getActiveSheet()->getStyle('D51:H51')->applyFromArray($all);
            $this->excel->getActiveSheet()->getStyle('D52:H52')->applyFromArray($all);
            $this->excel->getActiveSheet()->getStyle('A8:I8')->applyFromArray($all);

			ob_end_clean();
                  $filename='Laporan Realisasi Anggaran.xls'; //save our workbook as this file name
                  header('Content-Type: application/vnd.ms-excel'); //mime type
                  header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                  header('Cache-Control: max-age=0'); //no cache

            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        $objWriter->save('php://output');
    }
}