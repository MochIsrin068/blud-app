<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lo extends Finance_Controller {
	public function __construct(){
        parent::__construct();
        $this->load->library('excel');
    }

    public function index(){
        //load PHPExcel library
		$this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('LAPORAN OPERASIONAL');
		
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
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(7);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(23);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(23);
        
        // STARTING HEADER
        $this->excel->getActiveSheet()->mergeCells('B1:D1');
		$this->excel->getActiveSheet()->setCellValue('B1', 'UPT DANA BERGULIR');
		$this->excel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B1')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->mergeCells('B2:D2');
		$this->excel->getActiveSheet()->setCellValue('B2', 'BADAN LAYANAN UMUM DAERAH "HARUM"');
		$this->excel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B2')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->mergeCells('B3:D3');
		$this->excel->getActiveSheet()->setCellValue('B3', 'KOTA KENDARI');
		$this->excel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B3')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->mergeCells('B4:D4');
		$this->excel->getActiveSheet()->setCellValue('B4', 'LAPORAN OPERASIONAL');
		$this->excel->getActiveSheet()->getStyle('B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B4')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B4')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->mergeCells('B5:D5');
		$this->excel->getActiveSheet()->setCellValue('B5', 'UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN 31 DESEMBER 2018');
		$this->excel->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B5')->getFont()->setSize(10);
        // End Header

		$this->excel->getActiveSheet()->setCellValue('D7', '');
		$this->excel->getActiveSheet()->getStyle('D7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('D7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D7')->getFont()->setSize(10);
        $this->excel->getActiveSheet()->getStyle('D7')->getNumberFormat()->setFormatCode($accountingFormat);

        //  STARTING BODY
        //  START HEader Table
        $this->excel->getActiveSheet()->mergeCells('A8:A9');
        $this->excel->getActiveSheet()->mergeCells('B8:B9');

        $this->excel->getActiveSheet()->setCellValue('A8', 'NO. URUT');
		$this->excel->getActiveSheet()->getStyle('A8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A8')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A8')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->setCellValue('B8', 'URAIAN');
		$this->excel->getActiveSheet()->getStyle('B8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B8')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B8')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->setCellValue('C8', 'TAHUN');
		$this->excel->getActiveSheet()->getStyle('C8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C8')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C8')->getFont()->setBold(true);     


        $this->excel->getActiveSheet()->setCellValue('C9', '31/12/2018');
		$this->excel->getActiveSheet()->getStyle('C9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C9')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C9')->getFont()->setBold(true); 

        $this->excel->getActiveSheet()->setCellValue('D8', 'TAHUN');
		$this->excel->getActiveSheet()->getStyle('D8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('D8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D8')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D8')->getFont()->setBold(true);     


        $this->excel->getActiveSheet()->setCellValue('D9', '31/12/2017');
		$this->excel->getActiveSheet()->getStyle('D9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('D9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D9')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D9')->getFont()->setBold(true);


        $this->excel->getActiveSheet()->setCellValue('B10', 'KEGIATAN OPERASIONAL');
		$this->excel->getActiveSheet()->getStyle('B10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('B10')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B10')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->setCellValue('A11', 'I.');
		$this->excel->getActiveSheet()->getStyle('A11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('A11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A11')->getFont()->setSize(11);


        $this->excel->getActiveSheet()->setCellValue('B11', 'PENDAPATAN LO');
		$this->excel->getActiveSheet()->getStyle('B11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('B11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B11')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->setCellValue('B12', 'Pendapatan Jasa Layanan Pinjaman');
		$this->excel->getActiveSheet()->getStyle('B12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('B12')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B12')->getFont()->setSize(11);


        $this->excel->getActiveSheet()->setCellValue('A14', 'II.');
		$this->excel->getActiveSheet()->getStyle('A14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('A14')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A14')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->setCellValue('B14', 'PENDPATAN TRANSFER');
		$this->excel->getActiveSheet()->getStyle('B14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('B14')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B14')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->setCellValue('B15', 'Pendapatan APBD - Operasional');
		$this->excel->getActiveSheet()->getStyle('B15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('B15')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B15')->getFont()->setSize(11);

        $exmpleLO = 386000000;
        $this->excel->getActiveSheet()->setCellValue('C15', $exmpleLO);
		$this->excel->getActiveSheet()->getStyle('C15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('C15')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C15')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C5')->getNumberFormat()->setFormatCode($numberFormat);

        //   III
        $this->excel->getActiveSheet()->setCellValue('A17', 'II.');
		$this->excel->getActiveSheet()->getStyle('A17')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('A17')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A17')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->setCellValue('B17', 'PENDAPATAN LAIN - LAIN YANG SAH');
		$this->excel->getActiveSheet()->getStyle('B17')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('B17')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B17')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->setCellValue('B18', 'Pendapatan Jasa Giro/ Bunga Bank');
		$this->excel->getActiveSheet()->getStyle('B18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('B18')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B18')->getFont()->setSize(11);

        $exmpleLO2 = 65914703;
        $this->excel->getActiveSheet()->setCellValue('C18', $exmpleLO2);
		$this->excel->getActiveSheet()->getStyle('C18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('C18')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C18')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C18')->getNumberFormat()->setFormatCode($numberFormat);

        $exmpleTriwulan = 65914703;
        $this->excel->getActiveSheet()->setCellValue('D18', $exmpleTriwulan);
		$this->excel->getActiveSheet()->getStyle('D18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('D18')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D18')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D18')->getNumberFormat()->setFormatCode($numberFormat);


        $this->excel->getActiveSheet()->setCellValue('B20', 'JUMLAH PENDAPATAN');
		$this->excel->getActiveSheet()->getStyle('B20')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('B20')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B20')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B20')->getFont()->setBold(true);


        $this->excel->getActiveSheet()->setCellValue('C20', '=SUM(C12:C19)');
		$this->excel->getActiveSheet()->getStyle('C20')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('C20')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C20')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C20')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('D20', '=SUM(D12:D19)');
		$this->excel->getActiveSheet()->getStyle('D20')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('D20')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D20')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D20')->getFont()->setBold(true);

        // IV
        $this->excel->getActiveSheet()->setCellValue('A23', 'IV.');
		$this->excel->getActiveSheet()->getStyle('A23')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('A23')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A23')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->setCellValue('B23', 'BEBAN');
		$this->excel->getActiveSheet()->getStyle('B23')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('B23')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B23')->getFont()->setSize(11);


        $dataExm = array(
            array('         Beban Pegawai', 386000000, 0),
            array('         Beban Barang dan Jasa', 23012484, '=23343784+29500000'),
            array('         Beban Penyisihan Piutang', 125230300, 150545000),
            array('         Beban Akumulasi Penyusutan', 6502634, 17213230),
        );

        $st = 24;
        for($i = 0 ; $i < 4 ; $i++){
            $this->excel->getActiveSheet()->setCellValue('B'.$st, $dataExm[$i][0]);
            $this->excel->getActiveSheet()->getStyle('B'.$st)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $this->excel->getActiveSheet()->getStyle('B'.$st)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B'.$st)->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('C'.$st, $dataExm[$i][1]);
            $this->excel->getActiveSheet()->getStyle('C'.$st)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('C'.$st)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C'.$st)->getFont()->setSize(11);

            $this->excel->getActiveSheet()->setCellValue('D'.$st, $dataExm[$i][2]);
            $this->excel->getActiveSheet()->getStyle('D'.$st)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $this->excel->getActiveSheet()->getStyle('D'.$st)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D'.$st)->getFont()->setSize(11);
            $st++;
        }

        $this->excel->getActiveSheet()->setCellValue('B28', 'JUMLAH BEBAN');
		$this->excel->getActiveSheet()->getStyle('B28')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('B28')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B28')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B28')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('C28', '=SUM(C24:C27)');
		$this->excel->getActiveSheet()->getStyle('C28')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('C28')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C28')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C28')->getFont()->setBold(true);


        $this->excel->getActiveSheet()->setCellValue('D28', '=SUM(D24:D27)');
		$this->excel->getActiveSheet()->getStyle('D28')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('D28')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D28')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D28')->getFont()->setBold(true);


        // V
        $this->excel->getActiveSheet()->setCellValue('A31', 'V.');
		$this->excel->getActiveSheet()->getStyle('A31')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('A31')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A31')->getFont()->setSize(11);


        $this->excel->getActiveSheet()->setCellValue('B31', 'SURPLUS/ DEFISIT - LO');
		$this->excel->getActiveSheet()->getStyle('B31')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('B31')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B31')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B31')->getFont()->setBold(true);


        $this->excel->getActiveSheet()->setCellValue('C31', '=C20-C28');
		$this->excel->getActiveSheet()->getStyle('C31')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('C31')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C31')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C31')->getFont()->setBold(true);


        $this->excel->getActiveSheet()->setCellValue('D31', '=D20-D28');
		$this->excel->getActiveSheet()->getStyle('D31')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('D31')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D31')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D31')->getFont()->setBold(true);


        // END Header Table
        // END BODY


        // START FOOTER
        $this->excel->getActiveSheet()->mergeCells('C35:D35');
        $this->excel->getActiveSheet()->setCellValue('C35', 'Kendari, 31 Januari 2018');
        $this->excel->getActiveSheet()->getStyle('C35')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C35')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C35')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->mergeCells('C36:D36');
        $this->excel->getActiveSheet()->setCellValue('C36', 'UPT DANA BERGULIR');
        $this->excel->getActiveSheet()->getStyle('C36')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C36')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C36')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C36')->getFont()->setBold(11);

        $this->excel->getActiveSheet()->mergeCells('C37:D37');
        $this->excel->getActiveSheet()->setCellValue('C37', 'DIREKTUR,');
        $this->excel->getActiveSheet()->getStyle('C37')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C37')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C37')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C37')->getFont()->setBold(11);

        $this->excel->getActiveSheet()->mergeCells('C41:D41');
        $this->excel->getActiveSheet()->setCellValue('C41', 'TOHAMBA, SE');
        $this->excel->getActiveSheet()->getStyle('C41')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C41')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C41')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C41')->getFont()->setBold(11);
        // END FOOTER

        // SETTING BORDER
        $this->excel->getActiveSheet()->getStyle('A8:A9')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('B8:B9')->applyFromArray($outline);

        $this->excel->getActiveSheet()->getStyle('C8:C9')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('D8:D9')->applyFromArray($outline);

        $this->excel->getActiveSheet()->getStyle('A10:A30')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('B10:B19')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('B21:B27')->applyFromArray($outline);

        $this->excel->getActiveSheet()->getStyle('C10:C19')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('C21:C27')->applyFromArray($outline);

        $this->excel->getActiveSheet()->getStyle('D10:D19')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('D21:D27')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('B20:D20')->applyFromArray($all);
        $this->excel->getActiveSheet()->getStyle('B28:D28')->applyFromArray($all);
        $this->excel->getActiveSheet()->getStyle('A31:D31')->applyFromArray($all);


        // END SETTING BORDER

        ob_end_clean();
        $filename='LO (Laporan Operasional).xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        $objWriter->save('php://output');
    }
}
