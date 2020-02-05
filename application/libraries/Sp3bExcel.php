<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH."/third_party/PHPExcel.php"; 
require_once APPPATH."/libraries/Excel.php"; 
// library plugin req boostrap
class Sp3bExcel   {
	protected $excel = NULL;

	public function __construct(  )
	{
        $this->excel = new Excel;
	}

	public function get_headers(  )
	{	
		$header = array(
			'pendapatan_account_code' => 'Kode Rekening',
			'pendapatan_sum' => 'Jumlah',
			'belanja_account_code' => 'Kode Rekening',
			'belanja_sum' => 'Jumlah',
			'pembiayaan_account_code' => 'Kode Rekening',
			'pembiayaan_sum' => 'Jumlah',
		  );
		
		return $header;
	}
	public function create( $sp3b_rows, $year, $month,  $last_balance, $sums )
	{	
		$styleArray2 = array(
			'borders' => array('outline' =>
				array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' =>
					array('argb' => '0000'),
					),
				),
		);
		$all = array(
		'borders' => array('allborders' =>
			array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('argb' => '0000'),
				),
			),
		);	
		//load PHPExcel library
		$this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('SP3B');
			
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


        $this->excel->getActiveSheet()->getStyle('A:F')->getFont()->setName('Tahoma');			
        $this->excel->getActiveSheet()->getStyle('A21:F41')->getAlignment()->setWrapText(true);

        //SET DIMENSI TABEL
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(26);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);

        // Value Statted
        $this->excel->getActiveSheet()->mergeCells('A2:F2');
		$this->excel->getActiveSheet()->setCellValue('A2', 'UPT DANA BERGULIR KOTA KENDARI');
		$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        
        // $this->excel->getActiveSheet()->mergeCells('A2:F2');
		// $this->excel->getActiveSheet()->setCellValue('A2', ' ');
		// $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		// $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		// $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(11);
        // $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->mergeCells('A3:F3');
		$this->excel->getActiveSheet()->setCellValue('A3', 'SURAT PERNYATAAN PENGESAHAN PENDAPATAN DAN BELANJA (SP3B)');
		$this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->mergeCells('A4:F4');
		$this->excel->getActiveSheet()->setCellValue('A4', 'TAHUN ANGGARAN '.$year);
		$this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);

        
		$this->excel->getActiveSheet()->setCellValue('B5', 'Tanggal :'.date("d-m-Y") );
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


        $saldoAwl = (double) $last_balance;
        $this->excel->getActiveSheet()->setCellValue('C10', $saldoAwl);
        $this->excel->getActiveSheet()->getStyle('C10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
		$this->excel->getActiveSheet()->getStyle('C10')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C10')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C10')->getNumberFormat()->setFormatCode($accountingFormat);

        $exmplePendapatanLra = $sums[0];
        $this->excel->getActiveSheet()->setCellValue('C11', $exmplePendapatanLra);
        $this->excel->getActiveSheet()->getStyle('C11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
		$this->excel->getActiveSheet()->getStyle('C11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C11')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C11')->getNumberFormat()->setFormatCode($accountingFormat);

        $exmpleBelanjaLra = $sums[1];
        $this->excel->getActiveSheet()->setCellValue('C12', $exmpleBelanjaLra);
        $this->excel->getActiveSheet()->getStyle('C12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
		$this->excel->getActiveSheet()->getStyle('C12')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C12')->getFont()->setSize(11);
        $this->excel->getActiveSheet()->getStyle('C12')->getNumberFormat()->setFormatCode($accountingFormat);

        $exmplePembayaranLra = $sums[2];
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
        $this->excel->getActiveSheet()->setCellValue('A16', 'Untuk Bulan '.$month.' Tahun Anggaran '.$year.' ');
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

		$this->excel->getActiveSheet()->mergeCells('A21:B21');
        $this->excel->getActiveSheet()->setCellValue('A21', 'PENDAPATAN');
        $this->excel->getActiveSheet()->getStyle('A21')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A21')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A21')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('A21')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('A21:B21')->applyFromArray($all);		

        $this->excel->getActiveSheet()->mergeCells('C21:D21');
        $this->excel->getActiveSheet()->setCellValue('C21', 'BELANJA');
        $this->excel->getActiveSheet()->getStyle('C21')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C21')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C21')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('C21')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('C21:D21')->applyFromArray($all);

        $this->excel->getActiveSheet()->mergeCells('E21:F21');
        $this->excel->getActiveSheet()->setCellValue('E21', 'PEMBIAYAAN');
        $this->excel->getActiveSheet()->getStyle('E21')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E21')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E21')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('E21')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('E21:F21')->applyFromArray($all);
		
	   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   	$A = 65;//65 = A dan di tambah terus
		$row_num = 22;
		$headers = $this->get_headers();

		foreach( $headers as $key => $val )
		{
			$this->excel->getActiveSheet()->setCellValue( chr( $A ).$row_num , $val );
			$this->excel->getActiveSheet()->getStyle( chr( $A ).$row_num )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle( chr( $A ).$row_num )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->getStyle( chr( $A ).$row_num )->getFont()->setSize(11);
			$this->excel->getActiveSheet()->getStyle( chr( $A ).$row_num )->getFont()->setBold(true);
			$this->excel->getActiveSheet()->getStyle( chr( $A ).$row_num )->applyFromArray($styleArray2);
			$A++;
		}
		/////////////////////////////////
		$start_print = $row_num+1;
		foreach( $sp3b_rows as $row )
		{
			$A = 65;//65 = A dan di tambah terus
			$row_num ++;
			
			foreach( $headers as $key => $val )
			{
				if( strlen( $row->pendapatan_account_code ) > 13  )
				{
					$this->excel->getActiveSheet()->getStyle( chr( $A ).$row_num )->getFont()->setBold(true);
					$this->excel->getActiveSheet()->getStyle( chr( $A ).$row_num )->applyFromArray($all);
				}
				$this->excel->getActiveSheet()->getStyle( chr( $A ).$row_num )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				switch( $key )
				{
					case 'pendapatan_sum' :
					case 'belanja_sum' :
					case 'pembiayaan_sum' :
						$this->excel->getActiveSheet()->getStyle( chr( $A ).$row_num )->getNumberFormat()->setFormatCode( '#,##0.00' );
						break;
				}
				$this->excel->getActiveSheet()->setCellValue( chr( $A ).$row_num , $row->$key );							

				$A++;
			}
		}

		$A = 65;//65 = A dan di tambah terus
		$headers = $this->get_headers();
		$styleArray2 = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('argb' => '0000'),
				),
			),
		);
		foreach( $headers as $key => $val )
		{
			$this->excel->getActiveSheet()->getStyle( chr( $A ).$start_print.':'.chr( $A ).$row_num)->applyFromArray($styleArray2);
			$A++;
		}

       //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	   $row_num+= 3;
	   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $this->excel->getActiveSheet()->mergeCells('E'.$row_num.':F'.$row_num.'');
	   $this->excel->getActiveSheet()->setCellValue('E'.$row_num, 'Kendari, 31 '.$month.' '.$year.'');
	   $this->excel->getActiveSheet()->getStyle('E'.$row_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	   $this->excel->getActiveSheet()->getStyle('E'.$row_num)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	   $this->excel->getActiveSheet()->getStyle('E'.$row_num)->getFont()->setSize(11);
	   $row_num++;
	   $this->excel->getActiveSheet()->mergeCells('E'.$row_num.':F'.$row_num);
	   $this->excel->getActiveSheet()->setCellValue('E'.$row_num, 'UPT DANA BERGULIR');
	   $this->excel->getActiveSheet()->getStyle('E'.$row_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	   $this->excel->getActiveSheet()->getStyle('E'.$row_num)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	   $this->excel->getActiveSheet()->getStyle('E'.$row_num)->getFont()->setSize(11);
	   $this->excel->getActiveSheet()->getStyle('E'.$row_num)->getFont()->setBold(11);
	   $row_num++;
	   $this->excel->getActiveSheet()->mergeCells('E'.$row_num.':F'.$row_num);
	   $this->excel->getActiveSheet()->setCellValue('E'.$row_num, 'DIREKTUR,');
	   $this->excel->getActiveSheet()->getStyle('E'.$row_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	   $this->excel->getActiveSheet()->getStyle('E'.$row_num)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	   $this->excel->getActiveSheet()->getStyle('E'.$row_num)->getFont()->setSize(11);
	   $this->excel->getActiveSheet()->getStyle('E'.$row_num)->getFont()->setBold(11);
	   $row_num+=4 ;
	   $this->excel->getActiveSheet()->mergeCells('E'.$row_num.':F'.$row_num);
	   $this->excel->getActiveSheet()->setCellValue('E'.$row_num, 'TOHAMBA, SE');
	   $this->excel->getActiveSheet()->getStyle('E'.$row_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	   $this->excel->getActiveSheet()->getStyle('E'.$row_num)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	   $this->excel->getActiveSheet()->getStyle('E'.$row_num)->getFont()->setSize(11);
	   $this->excel->getActiveSheet()->getStyle('E'.$row_num)->getFont()->setBold(11);

	//    $gdImage = imagecreatefromjpeg( base_url( WATERMARK ) );
	//    $objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
	//    $objDrawing->setName('Sample image');
	//    $objDrawing->setDescription('Sample image');
	//    $objDrawing->setImageResource($gdImage);
	//    $objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
	//    $objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
	//    $objDrawing->setHeight(150);
	//    $objDrawing->setCoordinates('C1');
	//    $objDrawing->setWorksheet($this->excel->getActiveSheet());

		$logo = new PHPExcel_Worksheet_Drawing();
		$logo->setName('Logo')
			->setDescription('Logo')
			->setPath( ( WATERMARK ) , false)
			->setCoordinates('C1');

        ob_end_clean();
        $filename='Sp3b '.$month.' '.$year.'.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        $objWriter->save('php://output');
	}
}
