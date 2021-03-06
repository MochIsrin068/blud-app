<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lpsal extends Finance_Controller {
	public function __construct(){
        parent::__construct();
        $this->load->library('excel');
    }

    public function index(){
        //load PHPExcel library
		$this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('LP Sal');
		
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
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(5);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(60);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(3);


        // Value Statted
        $this->excel->getActiveSheet()->mergeCells('B2:E2');
		$this->excel->getActiveSheet()->setCellValue('B2', 'UPT DANA BERGULIR');
		$this->excel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B2')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
        
        
        $this->excel->getActiveSheet()->mergeCells('B3:E3');
		$this->excel->getActiveSheet()->setCellValue('B3', 'KOTA KENDARI');
		$this->excel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B3')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->mergeCells('B4:E4');
		$this->excel->getActiveSheet()->setCellValue('B4', 'LAPORAN PERUBAHAN SALDO ANGGARAN LEBIH');
		$this->excel->getActiveSheet()->getStyle('B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B4')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B4')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->mergeCells('B5:E5');
		$this->excel->getActiveSheet()->setCellValue('B5', 'PER 31 DESEMBER 2019 DAN 2018');
		$this->excel->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B5')->getFont()->setSize(10);

        // STARTING TABLE

        // START HEADER TABLE
        $this->excel->getActiveSheet()->getStyle('B7:C7')->applyFromArray($outline);				
        $this->excel->getActiveSheet()->setCellValue('C7', 'URAIAN');
		$this->excel->getActiveSheet()->getStyle('C7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C7')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C7')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->getStyle('D7:E7')->applyFromArray($outline);				
        $this->excel->getActiveSheet()->setCellValue('D7', '2019');
		$this->excel->getActiveSheet()->getStyle('D7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('D7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('D7')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D7')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('E7', '2018');
		$this->excel->getActiveSheet()->getStyle('E7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('E7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('E7')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('E7')->getFont()->setBold(true);
        // END HEADER TABLE

        // START VALUE / BODY TABLE
        //  Number
        $this->excel->getActiveSheet()->getStyle('B8:B15')->applyFromArray($outline);	

        for( $i =1; $i <=8; $i++  )
        {
            $this->excel->getActiveSheet()->setCellValue('B'.( $i+7 ), $i );
            $this->excel->getActiveSheet()->getStyle( 'B'.( $i+7 ) )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle( 'B'.( $i+7 ) )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle( 'B'.( $i+7 ) )->getFont()->setSize(11);
        }
        // End Number

        // Uraian
        $this->excel->getActiveSheet()->getStyle('C8:C15')->applyFromArray($outline);				
        $this->excel->getActiveSheet()->setCellValue('C8', 'Saldo Anggaran Lebih Awal');
		$this->excel->getActiveSheet()->getStyle('C8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('C8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C8')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C8')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('C9', 'Penggunaan SAL sebagai Penerimaan Pembiayaan Tahun Berjalan');
		$this->excel->getActiveSheet()->getStyle('C9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('C9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C9')->getFont()->setSize(11);
        
        $this->excel->getActiveSheet()->setCellValue('C10', 'Subtotal (1-2)');
		$this->excel->getActiveSheet()->getStyle('C10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('C10')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C10')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C10')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->setCellValue('C11', 'Sisa Lebih/Kurang Pembiayaan Anggaran (SILPA/SIKPA)');
		$this->excel->getActiveSheet()->getStyle('C11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('C11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C11')->getFont()->setSize(11);
        
        $this->excel->getActiveSheet()->setCellValue('C12', 'Subtotal');
		$this->excel->getActiveSheet()->getStyle('C12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('C12')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C12')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C12')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->setCellValue('C13', 'Koreksi Kesalahan Pembukuan Tahun Sebelumnya');
		$this->excel->getActiveSheet()->getStyle('C13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('C13')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C13')->getFont()->setSize(11);
        
        $this->excel->getActiveSheet()->setCellValue('C14', 'Lain-Lain');
		$this->excel->getActiveSheet()->getStyle('C14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('C14')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C14')->getFont()->setSize(11);
        
        $this->excel->getActiveSheet()->setCellValue('C15', 'Saldo Anggaran Lebih Akhir (5+6+7)');
		$this->excel->getActiveSheet()->getStyle('C15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('C15')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C15')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C15')->getFont()->setBold(true);
        // End Uraian

        // Example Data 2018
        $dt2018 = 40053080;
        $this->excel->getActiveSheet()->getStyle('D8:D15')->applyFromArray($outline);				
        $this->excel->getActiveSheet()->setCellValue('D8', $dt2018);
		$this->excel->getActiveSheet()->getStyle('D8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('D8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D8')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D8')->getNumberFormat()->setFormatCode($numberFormat);
        

        $this->excel->getActiveSheet()->setCellValue('D9', $dt2018);
		$this->excel->getActiveSheet()->getStyle('D9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('D9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D9')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D9')->getNumberFormat()->setFormatCode($numberFormat);
        
        // Example Data 2017
        $dt2017  = -4312152;
        $this->excel->getActiveSheet()->getStyle('E8:E15')->applyFromArray($outline);				
        $this->excel->getActiveSheet()->setCellValue('E8', $dt2017);
		$this->excel->getActiveSheet()->getStyle('E8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('E8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E8')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('E8')->getNumberFormat()->setFormatCode($numberFormat);
        

        $this->excel->getActiveSheet()->setCellValue('E9', $dt2017);
		$this->excel->getActiveSheet()->getStyle('E9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('E9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E9')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('E9')->getNumberFormat()->setFormatCode($numberFormat);


        // HANDLE TANDTANGN
        $this->excel->getActiveSheet()->mergeCells('D19:E19');
        $this->excel->getActiveSheet()->setCellValue('D19', 'Kendari, 31 Januari 2018');
        $this->excel->getActiveSheet()->getStyle('D19')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D19')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D19')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->mergeCells('D20:E20');
        $this->excel->getActiveSheet()->setCellValue('D20', 'UPT DANA BERGULIR');
        $this->excel->getActiveSheet()->getStyle('D20')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D20')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D20')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D20')->getFont()->setBold(11);

        $this->excel->getActiveSheet()->mergeCells('D21:E21');
        $this->excel->getActiveSheet()->setCellValue('D21', 'DIREKTUR,');
        $this->excel->getActiveSheet()->getStyle('D21')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D21')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D21')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D21')->getFont()->setBold(11);

        $this->excel->getActiveSheet()->mergeCells('D25:E25');
        $this->excel->getActiveSheet()->setCellValue('D25', 'TOHAMBA, SE');
        $this->excel->getActiveSheet()->getStyle('D25')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D25')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D25')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D25')->getFont()->setBold(11);

        // END BODY TABLE

        ob_end_clean();
        $filename='Lpsal.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        $objWriter->save('php://output');

    }
    
}
