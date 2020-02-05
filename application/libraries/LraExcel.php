<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH."/third_party/PHPExcel.php"; 
require_once APPPATH."/libraries/Excel.php"; 
// library plugin req boostrap
class LraExcel   {
	protected $excel = NULL;
	protected $style = array(
		'no' => array(
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			],
		),
		'account_code' => array(
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			],
		),
		'account_description' => array(
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			],
		),
		'dpa_budget' => array(
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			],
			'numberFormat' => [
				'formatCode ' => '#,##0.00',
			],
		),
		'last_month' => array(
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			],
		),
		'this_month' => array(
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			],
		),
		'until_this_month' => array(
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			],
		),
		'difference' => array(
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			],
		),
	);
	

	public function __construct(  )
	{
        $this->excel = new Excel;
	}

	public function get_headers(  )
	{	
		$header = array(
			'no' => 'No',
			'account_code' => 'Kode Rekening',
			'account_description' => 'Uraian',
			'dpa_budget' => 'Jumlah Anggaran',
			'last_month' => 'Realisasi Sampai Dengan Bulan Lalu (Rp)',
			'this_month' => 'Realisasi Bulan Ini (Rp)',
			'until_this_month' => 'Realisasi Sampai Dengan Bulan Ini (Rp)',
			'difference' => 'Selisih /(Kurang) (Rp)',
		  );
		
		return $header;
	}
	public function create( $lra_rows, $year, $month )
	{	
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
			$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
			$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
			$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
			$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
			$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
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
			$this->excel->getActiveSheet()->setCellValue('B6', 'Berikut ini kami sampaikan realisasi atas Penggunaan Dana Bulan '.$month.' '.$year.' Sebagai Berikut');
			$this->excel->getActiveSheet()->getStyle('B6')->getFont()->setSize(11);


			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$A = 65;//65 = A dan di tambah terus
			$row_num = 8;
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
			$no = 0;
			$start_print = $row_num+1;
			foreach( $lra_rows as $row )
			{
				$A = 65;//65 = A dan di tambah terus
				$row_num ++;
				
				foreach( $headers as $key => $val )
				{
					if( strlen( $row->account_code ) < 5  )
					{
						$this->excel->getActiveSheet()->getStyle( chr( $A ).$row_num )->getFont()->setBold(true);
					}

					$this->excel->getActiveSheet()->getStyle( chr( $A ).$row_num )->applyFromArray( $this->style[ $key ] );
					switch( $key )
					{
						case 'dpa_budget' :
						case 'last_month' :
						case 'this_month' :
						case 'until_this_month' :
						case 'difference' :
							$this->excel->getActiveSheet()->getStyle( chr( $A ).$row_num )->getNumberFormat()->setFormatCode( '#,##0.00' );
							break;
					}
					switch( $key )
					{
						case 'no' :
							break;
						// case 'until_this_month' :
							// $formula = '='.chr( $A - 1 ).$row_num.'+'.chr( $A - 2 ).$row_num;
							// $this->excel->getActiveSheet()->setCellValue( chr( $A ).$row_num , $formula );
							// break;
						// case 'difference' :
							// $formula = '='.chr( $A - 4 ).$row_num.'-'.chr( $A - 1 ).$row_num;
							// $this->excel->getActiveSheet()->setCellValue( chr( $A ).$row_num , $formula );
							// break;
						default:
							$this->excel->getActiveSheet()->setCellValue( chr( $A ).$row_num , $row->$key );							
							break;
					}
					$A++;
				}
				// $no++;
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

			$row_num+= 3;
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$this->excel->getActiveSheet()->mergeCells('G'.$row_num.':H'.$row_num.'');
            $this->excel->getActiveSheet()->setCellValue('G'.$row_num, 'Kendari, 31 '.$month.' '.$year.'');
            $this->excel->getActiveSheet()->getStyle('G'.$row_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G'.$row_num)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G'.$row_num)->getFont()->setSize(11);
			$row_num++;
            $this->excel->getActiveSheet()->mergeCells('G'.$row_num.':H'.$row_num);
            $this->excel->getActiveSheet()->setCellValue('G'.$row_num, 'UPT DANA BERGULIR');
            $this->excel->getActiveSheet()->getStyle('G'.$row_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G'.$row_num)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G'.$row_num)->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('G'.$row_num)->getFont()->setBold(11);
			$row_num++;
            $this->excel->getActiveSheet()->mergeCells('G'.$row_num.':H'.$row_num);
            $this->excel->getActiveSheet()->setCellValue('G'.$row_num, 'DIREKTUR,');
            $this->excel->getActiveSheet()->getStyle('G'.$row_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G'.$row_num)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G'.$row_num)->getFont()->setSize(11);
			$this->excel->getActiveSheet()->getStyle('G'.$row_num)->getFont()->setBold(11);
			$row_num+=4 ;
            $this->excel->getActiveSheet()->mergeCells('G'.$row_num.':H'.$row_num);
            $this->excel->getActiveSheet()->setCellValue('G'.$row_num, 'TOHAMBA, SE');
            $this->excel->getActiveSheet()->getStyle('G'.$row_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G'.$row_num)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G'.$row_num)->getFont()->setSize(11);
            $this->excel->getActiveSheet()->getStyle('G'.$row_num)->getFont()->setBold(11);

			ob_end_clean();
                  $filename='Laporan Realisasi Anggaran '.$month.' '.$year.'.xls'; //save our workbook as this file name
                  header('Content-Type: application/vnd.ms-excel'); //mime type
                  header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                  header('Cache-Control: max-age=0'); //no cache

            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        $objWriter->save('php://output');
	}
}
