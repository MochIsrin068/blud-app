<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH."/third_party/PHPExcel.php"; 
require_once APPPATH."/libraries/Excel.php"; 
// library plugin req boostrap
class LO_excel   {
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

    public function create( $DATA , $date, $semester )
    {

        $year = (int) date('Y', $date );
		$month = (int) date('m', $date );
		$month = $this->_month[ $month ];
        $day = (int) date('d', $date );
        
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
        $accountingFormat = '_("Rp "* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)';
        
        $this->excel->getActiveSheet()->getStyle('A1:F25')->getAlignment()->setWrapText(true);

        $this->excel->getActiveSheet()->getStyle('C10:D31')->getNumberFormat()->setFormatCode($numberFormat);
        //SET DIMENSI TABEL
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(7);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(23);
        
        // STARTING HEADER
        
        $this->excel->getActiveSheet()->mergeCells('B2:C2');
		$this->excel->getActiveSheet()->setCellValue('B2', 'UPT DANA BERGULIR');
		$this->excel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B2')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->mergeCells('B3:C3');
		$this->excel->getActiveSheet()->setCellValue('B3', 'KOTA KENDARI');
		$this->excel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B3')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->mergeCells('B4:C4');
		$this->excel->getActiveSheet()->setCellValue('B4', 'LAPORAN OPERASIONAL');
		$this->excel->getActiveSheet()->getStyle('B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B4')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B4')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->mergeCells('B5:C5');
		$this->excel->getActiveSheet()->setCellValue('B5', 'SAMPAI '.strtoupper( $month )." ".$year );
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


        $this->excel->getActiveSheet()->setCellValue('C9', $year);
		$this->excel->getActiveSheet()->getStyle('C9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C9')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C9')->getFont()->setBold(true); 

    ####################################################################################################################################################
    ####################################################################################################################################################


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
        $this->excel->getActiveSheet()->setCellValue('C15', $DATA->beban_pegawai );
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
        $this->excel->getActiveSheet()->setCellValue('C18', $DATA->pendapatan );
		$this->excel->getActiveSheet()->getStyle('C18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('C18')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C18')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C18')->getNumberFormat()->setFormatCode($numberFormat);
        ####################################################################################################################################################
        ####################################################################################################################################################
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
        ####################################################################################################################################################
        ####################################################################################################################################################
        
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
            array('         Beban Pegawai', $DATA->beban_pegawai ),
            array('         Beban Barang dan Jasa', $DATA->belanja_barang),
            array('         Beban Penyisihan Piutang', $DATA->penyisihan_piutang),
            array('         Beban Akumulasi Penyusutan', $DATA->penysutan_aset_tetap),
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

        // END Header Table
        // END BODY


        // START FOOTER
        $this->excel->getActiveSheet()->mergeCells('C35:C35');
        $this->excel->getActiveSheet()->setCellValue('C35', 'Kendari, '.$day.' '.$month.' '.$year.'');
        $this->excel->getActiveSheet()->getStyle('C35')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C35')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C35')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->mergeCells('C36:C36');
        $this->excel->getActiveSheet()->setCellValue('C36', 'UPT DANA BERGULIR');
        $this->excel->getActiveSheet()->getStyle('C36')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C36')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C36')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C36')->getFont()->setBold(11);

        $this->excel->getActiveSheet()->mergeCells('C37:C37');
        $this->excel->getActiveSheet()->setCellValue('C37', 'DIREKTUR,');
        $this->excel->getActiveSheet()->getStyle('C37')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C37')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C37')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C37')->getFont()->setBold(11);

        $this->excel->getActiveSheet()->mergeCells('C41:C41');
        $this->excel->getActiveSheet()->setCellValue('C41', 'TOHAMBA, SE');
        $this->excel->getActiveSheet()->getStyle('C41')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C41')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C41')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C41')->getFont()->setBold(11);
        // END FOOTER
        $this->excel->getActiveSheet()->mergeCells('A42:C42');
        $this->excel->getActiveSheet()->setCellValue('A42', 'printed by simpel alir');
        $this->excel->getActiveSheet()->getStyle('A42')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $this->excel->getActiveSheet()->getStyle('A42')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A42')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A42')->getFont()->setBold(11);
        // SETTING BORDER
        $this->excel->getActiveSheet()->getStyle('A8:C9')->applyFromArray($outline);


        $this->excel->getActiveSheet()->getStyle('A8:A31')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('B8:B31')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('C8:C31')->applyFromArray($outline);

        $this->excel->getActiveSheet()->getStyle('B10:B19')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('B21:B27')->applyFromArray($outline);

        $this->excel->getActiveSheet()->getStyle('C10:C19')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('C21:C27')->applyFromArray($outline);

        $this->excel->getActiveSheet()->getStyle('A31:C31')->applyFromArray($all);


        // END SETTING BORDER

        ob_end_clean();
        $filename='LO (Laporan Operasional) Semester '.$semester.'.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        $objWriter->save('php://output');
    }
}
