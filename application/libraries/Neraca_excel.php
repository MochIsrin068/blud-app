<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH."/third_party/PHPExcel.php"; 
require_once APPPATH."/libraries/Excel.php"; 
// library plugin req boostrap
class Neraca_excel   {
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


    public function create(  $DATA , $date,  $semester )
    {

        $year = (int) date('Y', $date );
		$month = (int) date('m', $date );
		$month = $this->_month[ $month ];
        $day = (int) date('d', $date );
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
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        
        // SETTING BORDER
        $this->excel->getActiveSheet()->getStyle('A10:A31')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('B10:B31')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('C10:C31')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('D10:D31')->applyFromArray($outline);


        $this->excel->getActiveSheet()->getStyle('A7:D9')->applyFromArray($all);
        $this->excel->getActiveSheet()->getStyle('A15:D15')->applyFromArray($all);
        $this->excel->getActiveSheet()->getStyle('A17:D17')->applyFromArray($all);
        $this->excel->getActiveSheet()->getStyle('A24:D24')->applyFromArray($all);

        $this->excel->getActiveSheet()->getStyle('A26:B26')->applyFromArray($all);

        $this->excel->getActiveSheet()->getStyle('A31:D31')->applyFromArray($all);
        

        // STARTING HEADER
        
        $this->excel->getActiveSheet()->mergeCells('A2:D2');
		$this->excel->getActiveSheet()->setCellValue('A2', 'UPT DANA BERGULIR');
		$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->mergeCells('A3:D3');
		$this->excel->getActiveSheet()->setCellValue('A3', 'KOTA KENDARI');
		$this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->mergeCells('A4:D4');
		$this->excel->getActiveSheet()->setCellValue('A4', 'NERACA');
		$this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->mergeCells('A5:D5');
		$this->excel->getActiveSheet()->setCellValue('A5', 'SAMPAI '.strtoupper( $month )." ".$year  );
		$this->excel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A5')->getFont()->setSize(10);
        // End Header

        //  STARTING BODY
        //  START HEader Table
        $this->excel->getActiveSheet()->mergeCells('A7:B7');
        $this->excel->getActiveSheet()->mergeCells('C7:D7');
        $this->excel->getActiveSheet()->getStyle('A7:D7')->applyFromArray($all);

		$this->excel->getActiveSheet()->setCellValue('A7', 'ASET');
		$this->excel->getActiveSheet()->getStyle('A7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A7')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A7')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->setCellValue('C7', 'KEWAJIBAN DAN EKUITAS');
		$this->excel->getActiveSheet()->getStyle('C7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C7')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C7')->getFont()->setBold(true);     
        ########################################################################################################################
        ########################################################################################################################
        $this->excel->getActiveSheet()->setCellValue('A8', 'PERKIRAAN');
		$this->excel->getActiveSheet()->getStyle('A8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A8')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A8')->getFont()->setBold(true);  

        $this->excel->getActiveSheet()->setCellValue('B8', '2019');
		$this->excel->getActiveSheet()->getStyle('B8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B8')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B8')->getFont()->setBold(true);  

        $this->excel->getActiveSheet()->setCellValue('C8', 'PERKIRAAN');
		$this->excel->getActiveSheet()->getStyle('C8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C8')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C8')->getFont()->setBold(true);  

        $this->excel->getActiveSheet()->setCellValue('D8', '2019');
		$this->excel->getActiveSheet()->getStyle('D8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('D8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D8')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D8')->getFont()->setBold(true);  
        ########################################################################################################################
        ########################################################################################################################
        $this->excel->getActiveSheet()->setCellValue('A9', 'ASET LANCAR');
		$this->excel->getActiveSheet()->getStyle('A9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('A9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A9')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A9')->getFont()->setBold(true);  

        $rows = array(
            '       KAS DAN BANK',
            '       INVESTASI JANGKA PENDEK',
            '       PIUTANG USAHA',
            '       PERLENGKAPAN KANTOR',
            '       PENYISIHAN PIUTANG ',
        );

        $start_number = 10;
        foreach( $rows as $ind => $row )
        {
            $this->excel->getActiveSheet()->setCellValue('A'.( $start_number + $ind ), $row );
		    $this->excel->getActiveSheet()->getStyle('A'.( $start_number + $ind ) )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		    $this->excel->getActiveSheet()->getStyle('A'.( $start_number + $ind ) )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A'.( $start_number + $ind ) )->getFont()->setSize(11);
        }
        $this->excel->getActiveSheet()->setCellValue('A15', 'TOTAL ASET LANCAR');
		$this->excel->getActiveSheet()->getStyle('A15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('A15')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A15')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A15')->getFont()->setBold(true);  

        $rows = array(
            $DATA->kas_dan_bank ,
            0,
            $DATA->piutang_usaha ,
            0,
            $DATA->penyisihan_piutang ,
        );

        $start_number = 10;
        foreach( $rows as $ind => $row )
        {
            $this->excel->getActiveSheet()->setCellValue('B'.( $start_number + $ind ), $row );
		    $this->excel->getActiveSheet()->getStyle('B'.( $start_number + $ind ) )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		    $this->excel->getActiveSheet()->getStyle('B'.( $start_number + $ind ) )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B'.( $start_number + $ind ) )->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('B'.( $start_number + $ind ) )->getNumberFormat()->setFormatCode($numberFormat);
        }
        $this->excel->getActiveSheet()->setCellValue('B15', '=SUM(B10:B14)');
		$this->excel->getActiveSheet()->getStyle('B15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('B15')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B15')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B15')->getFont()->setBold(true);  
        $this->excel->getActiveSheet()->getStyle('B15')->getNumberFormat()->setFormatCode($numberFormat);
        ###################################################################################################################################################
        ###################################################################################################################################################
        $this->excel->getActiveSheet()->setCellValue('C9', 'KEWAJIBAN ');
		$this->excel->getActiveSheet()->getStyle('C9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('C9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C9')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C9')->getFont()->setBold(true);  

        $rows = array(
            '       UTANG USAHA',
            '       UTANG PAJAK',
            '       BEBAN YANG MASIH HARUS DIBAYARKAN',
            '       PENDAPATAN DITERIMA DIMUKA',
            '        KEWAJIBAN JANGKA PENDEK ',
        );

        $start_number = 10;
        foreach( $rows as $ind => $row )
        {
            $this->excel->getActiveSheet()->setCellValue('C'.( $start_number + $ind ), $row );
		    $this->excel->getActiveSheet()->getStyle('C'.( $start_number + $ind ) )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		    $this->excel->getActiveSheet()->getStyle('C'.( $start_number + $ind ) )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C'.( $start_number + $ind ) )->getFont()->setSize(11);
        }
        $this->excel->getActiveSheet()->setCellValue('C15', 'TOTAL KEWAJIBAN');
		$this->excel->getActiveSheet()->getStyle('C15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('C15')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C15')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C15')->getFont()->setBold(true);  

        $rows = array(
            0,
            0,
            0,
            0,
            0,
        );

        $start_number = 10;
        foreach( $rows as $ind => $row )
        {
            $this->excel->getActiveSheet()->setCellValue('D'.( $start_number + $ind ), $row );
		    $this->excel->getActiveSheet()->getStyle('D'.( $start_number + $ind ) )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		    $this->excel->getActiveSheet()->getStyle('D'.( $start_number + $ind ) )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D'.( $start_number + $ind ) )->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D'.( $start_number + $ind ) )->getNumberFormat()->setFormatCode($numberFormat);
        }
        $this->excel->getActiveSheet()->setCellValue('D15', '=SUM(D10:D14)');
		$this->excel->getActiveSheet()->getStyle('D15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('D15')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D15')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D15')->getFont()->setBold(true);  
        $this->excel->getActiveSheet()->getStyle('D15')->getNumberFormat()->setFormatCode($numberFormat);
        ###################################################################################################################################################
        ###################################################################################################################################################
        $this->excel->getActiveSheet()->setCellValue('A17', 'ASET TETAP');
		$this->excel->getActiveSheet()->getStyle('A17')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('A17')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A17')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A17')->getFont()->setBold(true);  

        $rows = array(
            '       TANAH',
            '       PERALATAN DAN MESIN',
            '       GEDUNG DAN BANGUNAN',
            '       JALAN, JARINGAN DAN INSTALASI',
            '       ASET TETAP LAINNYA ',
            '       KONSTRUKSI DALAM PENGERJAAN ',
        );

        $start_number = 18;
        foreach( $rows as $ind => $row )
        {
            $this->excel->getActiveSheet()->setCellValue('A'.( $start_number + $ind ), $row );
		    $this->excel->getActiveSheet()->getStyle('A'.( $start_number + $ind ) )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		    $this->excel->getActiveSheet()->getStyle('A'.( $start_number + $ind ) )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A'.( $start_number + $ind ) )->getFont()->setSize(11);
        }
        $this->excel->getActiveSheet()->setCellValue('A24', 'JUMLAH ASET  TETAP');
		$this->excel->getActiveSheet()->getStyle('A24')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('A24')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A24')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A24')->getFont()->setBold(true);  

        $rows = array(
            0,
            $DATA->peralatan_n_mesin ,
            $DATA->gedung_n_bangunan ,
            $DATA->jalan_n_instalasi ,
            0,
            0,
        );

        $start_number = 18;
        foreach( $rows as $ind => $row )
        {
            $this->excel->getActiveSheet()->setCellValue('B'.( $start_number + $ind ), $row );
		    $this->excel->getActiveSheet()->getStyle('B'.( $start_number + $ind ) )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		    $this->excel->getActiveSheet()->getStyle('B'.( $start_number + $ind ) )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B'.( $start_number + $ind ) )->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('B'.( $start_number + $ind ) )->getNumberFormat()->setFormatCode($numberFormat);
        }
        $this->excel->getActiveSheet()->setCellValue('B24', '=SUM(B18:B23)');
		$this->excel->getActiveSheet()->getStyle('B24')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('B24')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B24')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B24')->getFont()->setBold(true);  
        $this->excel->getActiveSheet()->getStyle('B24')->getNumberFormat()->setFormatCode($numberFormat);
        ########################################################################################################################
        ########################################################################################################################
        $this->excel->getActiveSheet()->setCellValue('C17', 'EKUITAS');
		$this->excel->getActiveSheet()->getStyle('C17')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('C17')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C17')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C17')->getFont()->setBold(true);  

        $rows = array(
            '       EKUITAS DANA LANCAR',
            '       EKUITAS DANA INVESTASI',
            '       EKUITAS DANA CADANGAN',
            '       ',
            '        ',
            '        ',
        );

        $start_number = 18;
        foreach( $rows as $ind => $row )
        {
            $this->excel->getActiveSheet()->setCellValue('C'.( $start_number + $ind ), $row );
		    $this->excel->getActiveSheet()->getStyle('C'.( $start_number + $ind ) )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		    $this->excel->getActiveSheet()->getStyle('C'.( $start_number + $ind ) )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C'.( $start_number + $ind ) )->getFont()->setSize(11);
        }
        $this->excel->getActiveSheet()->setCellValue('C24', 'JUMLAH EKUITAS');
		$this->excel->getActiveSheet()->getStyle('C24')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('C24')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C24')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C24')->getFont()->setBold(true);  

        $rows = array(
            // $DATA->ekuitas_dana_lancar,
            // $DATA->ekuitas_dana_investasi,
            "=B15",
            "=B26",
            0,
        );

        $start_number = 18;
        foreach( $rows as $ind => $row )
        {
            $this->excel->getActiveSheet()->setCellValue('D'.( $start_number + $ind ), $row );
		    $this->excel->getActiveSheet()->getStyle('D'.( $start_number + $ind ) )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		    $this->excel->getActiveSheet()->getStyle('D'.( $start_number + $ind ) )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D'.( $start_number + $ind ) )->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('D'.( $start_number + $ind ) )->getNumberFormat()->setFormatCode($numberFormat);
        }
        $this->excel->getActiveSheet()->setCellValue('D24', '=SUM(D18:D23)');
		$this->excel->getActiveSheet()->getStyle('D24')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('D24')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D24')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D24')->getFont()->setBold(true);  
        ########################################################################################################################
        ########################################################################################################################
        $this->excel->getActiveSheet()->setCellValue('A25', '  AKUMULASI PENYUSUTAN AKTIVA TETAP');
		$this->excel->getActiveSheet()->getStyle('A25')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('A25')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A25')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->setCellValue('B25', $DATA->akumulasi_penysutan_aset_tetap );
		$this->excel->getActiveSheet()->getStyle('B25')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('B25')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B25')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B25')->getNumberFormat()->setFormatCode($numberFormat);
        ########################################################################################################################
        ########################################################################################################################
        $this->excel->getActiveSheet()->setCellValue('A26', '  TOTAL ASET TETAP');
		$this->excel->getActiveSheet()->getStyle('A26')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('A26')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A26')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A26')->getFont()->setBold(true);  

        $this->excel->getActiveSheet()->setCellValue('B26', '=B24+B25' );
		$this->excel->getActiveSheet()->getStyle('B26')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('B26')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B26')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B26')->getFont()->setBold(true);  
        $this->excel->getActiveSheet()->getStyle('B26')->getNumberFormat()->setFormatCode($numberFormat);
        ################################################################################################################################################################################################################################################
        ################################################################################################################################################################################################################################################
        $this->excel->getActiveSheet()->setCellValue('A28', 'ASET LAINNYA');
		$this->excel->getActiveSheet()->getStyle('A28')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('A28')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A28')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A28')->getFont()->setBold(true);  

        $rows = array(
            '        ASET LAIN-LAIN',
        );

        $start_number = 29;
        foreach( $rows as $ind => $row )
        {
            $this->excel->getActiveSheet()->setCellValue('A'.( $start_number + $ind ), $row );
		    $this->excel->getActiveSheet()->getStyle('A'.( $start_number + $ind ) )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		    $this->excel->getActiveSheet()->getStyle('A'.( $start_number + $ind ) )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A'.( $start_number + $ind ) )->getFont()->setSize(11);
        }
        ########################################################################################################################
        ########################################################################################################################
        $this->excel->getActiveSheet()->setCellValue('A31', '       JUMLAH AKTIVA');
		$this->excel->getActiveSheet()->getStyle('A31')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('A31')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A31')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A31')->getFont()->setBold(true);  

        $this->excel->getActiveSheet()->setCellValue('B31', '=B15+B26');
		$this->excel->getActiveSheet()->getStyle('B31')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('B31')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B31')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('B31')->getFont()->setBold(true);  
        $this->excel->getActiveSheet()->getStyle('B31')->getNumberFormat()->setFormatCode($numberFormat);

        $this->excel->getActiveSheet()->setCellValue('C31', '         JUMLAH KEWAJIBAN DAN EKUITAS');
		$this->excel->getActiveSheet()->getStyle('C31')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('C31')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C31')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C31')->getFont()->setBold(true);  

        $this->excel->getActiveSheet()->setCellValue('D31', '=D15+D24');
		$this->excel->getActiveSheet()->getStyle('D31')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('D31')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D31')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D31')->getFont()->setBold(true);  
        $this->excel->getActiveSheet()->getStyle('D31')->getNumberFormat()->setFormatCode($numberFormat);
       

        // END BODY
        ########################################################################################################################
        ########################################################################################################################

        $this->excel->getActiveSheet()->setCellValue('D33', 'Kendari, '.$day.' '.$month.' '.$year.'');
        $this->excel->getActiveSheet()->getStyle('D33')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D33')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D33')->getFont()->setSize(11);

        // $this->excel->getActiveSheet()->mergeCells('D54:E54');
        $this->excel->getActiveSheet()->setCellValue('D34', 'UPT DANA BERGULIR');
        $this->excel->getActiveSheet()->getStyle('D34')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D34')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D34')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D34')->getFont()->setBold(11);

        // $this->excel->getActiveSheet()->mergeCells('D55:E55');
        $this->excel->getActiveSheet()->setCellValue('D35', 'DIREKTUR,');
        $this->excel->getActiveSheet()->getStyle('D35')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D35')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D35')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D35')->getFont()->setBold(11);

        // $this->excel->getActiveSheet()->mergeCells('D59:E59');
        $this->excel->getActiveSheet()->setCellValue('D39', 'TOHAMBA, SE');
        $this->excel->getActiveSheet()->getStyle('D39')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D39')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D39')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('D39')->getFont()->setBold(11);

        $this->excel->getActiveSheet()->mergeCells('A40:B40');
        $this->excel->getActiveSheet()->setCellValue('A40', 'printed by simpel alir');
        $this->excel->getActiveSheet()->getStyle('A40')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $this->excel->getActiveSheet()->getStyle('A40')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A40')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A40')->getFont()->setBold(11);


        // $this->excel->getActiveSheet()->getStyle('C16:C50')->applyFromArray($all);
        // END SETTING BORDER

        
        ob_end_clean();
        $filename='Neraca Semester '.$semester.' '.$year.'.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        $objWriter->save('php://output');
    }

}
