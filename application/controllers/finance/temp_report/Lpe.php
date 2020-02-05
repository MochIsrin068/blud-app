<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lpe extends Finance_Controller {
	public function __construct(){
        parent::__construct();
        $this->load->library('excel');

    }

    public function index(){
        //load PHPExcel library
		$this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Laporan Perubahan Ekuitas');
		
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
        
        // CUSTOM STYLE
        $this->excel->getActiveSheet()->getStyle('A1:C24')->getAlignment()->setWrapText(true);
        $this->excel->getActiveSheet()->getStyle('A7:C14')->applyFromArray($all);


        //SET DIMENSI TABEL
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(45);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        

        // STARTTING HEADER
        // STARTING HEADER
        $this->excel->getActiveSheet()->mergeCells('A1:C1');
		$this->excel->getActiveSheet()->setCellValue('A1', 'UPT DANA BERGULIR');
		$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->mergeCells('A2:C2');
		$this->excel->getActiveSheet()->setCellValue('A2', 'BADAN LAYANAN UMUM DAERAH "HARUM"');
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
		$this->excel->getActiveSheet()->setCellValue('A4', 'LAPORAN PERUBAHAN EKUITAS');
		$this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->mergeCells('A5:C5');
		$this->excel->getActiveSheet()->setCellValue('A5', 'UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN 31 DESEMBER 2018 DAN 2017');
		$this->excel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A5')->getFont()->setSize(10);
        
        // End Header

        // Start Heder Table
        $this->excel->getActiveSheet()->setCellValue('A7', 'URAIAN');
		$this->excel->getActiveSheet()->getStyle('A7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A7')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A7')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('B7', '2018');
		$this->excel->getActiveSheet()->getStyle('B7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B7')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B7')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('C7', '2017');
		$this->excel->getActiveSheet()->getStyle('C7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C7')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C7')->getFont()->setBold(true);

        // STARTING BODY TABLE
        $this->excel->getActiveSheet()->setCellValue('A8', 'Ekuitas Awal 01 Januari');
		$this->excel->getActiveSheet()->getStyle('A8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('A8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A8')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A8')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('A9', '    Surplus/Defisit-LO Dampak Kumulatif');
        $this->excel->getActiveSheet()->getStyle('A9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('A9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A9')->getFont()->setSize(11);
        
        $this->excel->getActiveSheet()->setCellValue('A10', 'Perubahan Kebijakan/ Kesalahan Mendasar :');
		$this->excel->getActiveSheet()->getStyle('A10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('A10')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A10')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A10')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('B8', 2991891086);
		$this->excel->getActiveSheet()->getStyle('B8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('B8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B8')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B8')->getNumberFormat()->setFormatCode($numberFormat);
    
        
        $this->excel->getActiveSheet()->setCellValue('B9', 88830715);
		$this->excel->getActiveSheet()->getStyle('B9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('B9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B9')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B9')->getNumberFormat()->setFormatCode($numberFormat);
        
        $this->excel->getActiveSheet()->setCellValue('C8', 3148136236);
		$this->excel->getActiveSheet()->getStyle('C8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('C8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C8')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C8')->getNumberFormat()->setFormatCode($numberFormat);
        
        $this->excel->getActiveSheet()->setCellValue('C9', 156245150);
		$this->excel->getActiveSheet()->getStyle('C9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('C9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C9')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C9')->getNumberFormat()->setFormatCode($numberFormat);        
        
        $dataTes = array(
            array('     Koreksi Nilai Persediaan', 0, 0),
            array('     Selisih Revaluasi Aset Tetap', 0, 0),
            array('     Lain - lain', 0, 0),
        );  

        $start = 11;
        for($i = 0; $i < 3; $i++){
            $this->excel->getActiveSheet()->setCellValue('A'.$start, $dataTes[$i][0]);
            $this->excel->getActiveSheet()->getStyle('A'.$start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		    $this->excel->getActiveSheet()->getStyle('A'.$start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A'.$start)->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('B'.$start, $dataTes[$i][1]);
            $this->excel->getActiveSheet()->getStyle('B'.$start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		    $this->excel->getActiveSheet()->getStyle('B'.$start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B'.$start)->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('B'.$start)->getNumberFormat()->setFormatCode($numberFormat);

            $this->excel->getActiveSheet()->setCellValue('C'.$start, $dataTes[$i][2]);
            $this->excel->getActiveSheet()->getStyle('C'.$start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		    $this->excel->getActiveSheet()->getStyle('C'.$start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C'.$start)->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('C'.$start)->getNumberFormat()->setFormatCode($numberFormat);
            $start++;
        }

        $this->excel->getActiveSheet()->setCellValue('A14', 'Ekuitas Akhir 31 Desember');
        $this->excel->getActiveSheet()->getStyle('A14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('A14')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A14')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A14')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('B14', '=B8+B9+B11+B12+B13');
        $this->excel->getActiveSheet()->getStyle('B14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('B14')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B14')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B14')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B14')->getNumberFormat()->setFormatCode($numberFormat);       
        
        $this->excel->getActiveSheet()->setCellValue('C14', '2991891086');
        $this->excel->getActiveSheet()->getStyle('C14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('C14')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C14')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C14')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('C14')->getNumberFormat()->setFormatCode($numberFormat);   


        // START FOOTER
        $this->excel->getActiveSheet()->mergeCells('B18:C18');
        $this->excel->getActiveSheet()->setCellValue('B18', 'Kendari, 31 Januari 2018');
        $this->excel->getActiveSheet()->getStyle('B18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B18')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B18')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->mergeCells('B19:C19');
        $this->excel->getActiveSheet()->setCellValue('B19', 'UPT DANA BERGULIR');
        $this->excel->getActiveSheet()->getStyle('B19')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B19')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B19')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B19')->getFont()->setBold(11);

        $this->excel->getActiveSheet()->mergeCells('B20:C20');
        $this->excel->getActiveSheet()->setCellValue('B20', 'DIREKTUR,');
        $this->excel->getActiveSheet()->getStyle('B20')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B20')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B20')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B20')->getFont()->setBold(11);

        $this->excel->getActiveSheet()->mergeCells('B24:C24');
        $this->excel->getActiveSheet()->setCellValue('B24', 'TOHAMBA, SE');
        $this->excel->getActiveSheet()->getStyle('B24')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B24')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B24')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B24')->getFont()->setBold(11);

        $this->excel->getActiveSheet()->mergeCells('B25:C25');
        $this->excel->getActiveSheet()->setCellValue('B25', '');
        $this->excel->getActiveSheet()->getStyle('B25')->getFont()->setSize(11);
        // END FOOTER

        ob_end_clean();
        $filename='Laporan Perubahan Ekuitas (LPE).xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        $objWriter->save('php://output');

    }
}
