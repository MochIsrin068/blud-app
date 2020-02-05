<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dpa extends Finance_Controller {
	public function __construct(){
        parent::__construct();
        $this->load->library('excel');

	}

    public function index(){
        //load PHPExcel library
		$this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Dokumen Pelaksanaan Anggaran');
		
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
		
        $this->excel->getActiveSheet()->getStyle('A1:I26')->getAlignment()->setWrapText(true);

		// Procteted
		$this->excel->getActiveSheet()->getProtection()->setSheet(true);
		$this->excel->getActiveSheet()->getProtection()->setSort(true);
		$this->excel->getActiveSheet()->getProtection()->setInsertRows(true);
		$this->excel->getActiveSheet()->getProtection()->setFormatCells(true);

        //SET DIMENSI TABEL
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(42);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
		$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);

		$this->excel->getActiveSheet()->getRowDimension('2')->setRowHeight(40);
		
		// Started Value
		$this->excel->getActiveSheet()->mergeCells('A1:A2');
        $objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName("Kota Kendari");
		$objDrawing->setDescription("Kota Kendari");
		$img = './assets/kendari.png';
		$objDrawing->setPath($img);
		$objDrawing->setOffsetX(10);
		$objDrawing->setOffsetY(10);
		$objDrawing->setCoordinates('A1');
		$objDrawing->setHeight(100);
		$objDrawing->setWidth(75); 
		$objDrawing->setResizeProportional(true);
		$objDrawing->setWorksheet($this->excel->getActiveSheet());

		$this->excel->getActiveSheet()->mergeCells('B1:B2');
        $this->excel->getActiveSheet()->getStyle('A1:B2')->applyFromArray($outline);				
		$this->excel->getActiveSheet()->setCellValue('B1', 'DOKUMEN PELAKSANAAN ANGGARAN SATUAN KERJA PERANGKAT DAERAH');
		$this->excel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B1')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
		
		$this->excel->getActiveSheet()->mergeCells('C1:H1');
        $this->excel->getActiveSheet()->getStyle('C1:H1')->applyFromArray($outline);				
		$this->excel->getActiveSheet()->setCellValue('C1', 'NOMOR DPA SKPD');
		$this->excel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C1')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
		
		//  Handle HEADER
		//   Handle NOmor DPA SKPD
		$this->excel->getActiveSheet()->setCellValue('C2', '4.04');
		$this->excel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C2')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);

		$this->excel->getActiveSheet()->setCellValue('D2', '05');
		$this->excel->getActiveSheet()->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('D2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('D2')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);

		$this->excel->getActiveSheet()->setCellValue('E2', '15');
		$this->excel->getActiveSheet()->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('E2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('E2')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

		$this->excel->getActiveSheet()->setCellValue('F2', '29');
		$this->excel->getActiveSheet()->getStyle('F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('F2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('F2')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);

		$this->excel->getActiveSheet()->setCellValue('G2', '5');
		$this->excel->getActiveSheet()->getStyle('G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('G2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('G2')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);
		
		$this->excel->getActiveSheet()->setCellValue('H2', '2');
		$this->excel->getActiveSheet()->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('H2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('H2')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->getStyle('C2:H2')->applyFromArray($all);				
        $this->excel->getActiveSheet()->getStyle('I1:I2')->applyFromArray($all);				


		$this->excel->getActiveSheet()->mergeCells('I1:I2');
		$this->excel->getActiveSheet()->setCellValue('I1', 'FORMULIR DPA SKPD 2.2.1');
		$this->excel->getActiveSheet()->getStyle('I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('I1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('I1')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);

		$this->excel->getActiveSheet()->mergeCells('A3:I3');
		$this->excel->getActiveSheet()->setCellValue('A3', 'PEMERINTAH KOTA KENDARI');
		$this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);

		$this->excel->getActiveSheet()->mergeCells('A4:I4');
        $this->excel->getActiveSheet()->getStyle('A4:I4')->applyFromArray($bottom);				
		$this->excel->getActiveSheet()->setCellValue('A4', 'Tahun Anggaran 2019');
		$this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(10);

		// Head -----------
		$dataHeader = array(
			array(
				'Urusan Pemerintahan',
				': 4.04.4.04',
				'Urusan Pemerintahan Fungsi Penunjang Keuangan'
			),
			array(
				'Organisasi',
				': 4.04.4.04.05',
				'Badan Pengelola Keuangan dan Aset Daerah'
			),
			array(
				'Program',
				': 4.04.4.04.05.15',
				'Program Peningkatan dan Pengembangan Pengelolaan Keuangan Daerah'
			),
			array(
				'Kegiatan',
				': 4.04.4.04.05.15.29',
				'Peningkatan Pengelolaan Keuagan BLUD Pengelola Dana Bergulir'
			),
			array(
				'Lokasi Kegiatan',
				': Kota Kendari',
				''
			),
			array(
				'Sumber Dana',
				': 1 Pendadpatan Asli Daerah ( P A D )',
				''
			)
			
		);

        $this->excel->getActiveSheet()->getStyle('A9:I9')->applyFromArray($outline);				
        $this->excel->getActiveSheet()->getStyle('A10:I10')->applyFromArray($outline);				

		$start = 5;
		for($i = 0; $i < 6; $i++){
			$this->excel->getActiveSheet()->setCellValue('A'.$start, $dataHeader[$i][0]);
			$this->excel->getActiveSheet()->getStyle('A'.$start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$this->excel->getActiveSheet()->getStyle('A'.$start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('A'.$start)->getFont()->setSize(11);
			$this->excel->getActiveSheet()->getStyle('A'.$start)->getFont()->setBold(true);

			$this->excel->getActiveSheet()->setCellValue('B'.$start, $dataHeader[$i][1]);
			$this->excel->getActiveSheet()->getStyle('B'.$start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('B'.$start)->getFont()->setSize(11);
			$this->excel->getActiveSheet()->getStyle('B'.$start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

			$this->excel->getActiveSheet()->mergeCells('C'.$start.':I'.$start);
			$this->excel->getActiveSheet()->setCellValue('C'.$start, $dataHeader[$i][2]);
			$this->excel->getActiveSheet()->getStyle('C'.$start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('C'.$start)->getFont()->setSize(11);
			$this->excel->getActiveSheet()->getStyle('C'.$start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$start++;
		}
			
		$this->excel->getActiveSheet()->mergeCells('A11:I11');
		$this->excel->getActiveSheet()->setCellValue('A11', 'INDIKATOR DAN TOLAK UKUR KINERJA BELANJA LANGSUNG');
		$this->excel->getActiveSheet()->getStyle('A11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A11')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('A11')->getFont()->setBold(true);
		
		$this->excel->getActiveSheet()->setCellValue('B12', 'TOLAK UKUR KINERJA');
		$this->excel->getActiveSheet()->getStyle('B12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B12')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B12')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('B12')->getFont()->setBold(true);

		$this->excel->getActiveSheet()->mergeCells('C12:I12');
		$this->excel->getActiveSheet()->setCellValue('C12', 'TARGET KINERJA');
		$this->excel->getActiveSheet()->getStyle('C12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C12')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C12')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('C12')->getFont()->setBold(true);

		$dataHeader2 = array(
			array(
				'Masukkan',
				'Jumlah Dana',
				'Rp.'+ 197700000
			),
			array(
				'Keluaran',
				'Laporan Pelaksanaan Pengelolaan Keuangan BLUD HARUM',
				'1 Tahun'
			),
			array(
				'Hasil',
				'Tersedianya Laporan Pelaksanaan Pengelolaan Keuangan BLUD HARUM',
				'100 Persen'
			),
		);

        $this->excel->getActiveSheet()->getStyle('A5:I8')->applyFromArray($outline);

		$start2 = 13;
		for($i = 0; $i < 3; $i++){
			$this->excel->getActiveSheet()->setCellValue('A'.$start2, $dataHeader2[$i][0]);
			$this->excel->getActiveSheet()->getStyle('A'.$start2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$this->excel->getActiveSheet()->getStyle('A'.$start2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('A'.$start2)->getFont()->setSize(11);
			$this->excel->getActiveSheet()->getStyle('A'.$start2)->getFont()->setBold(true);

			$this->excel->getActiveSheet()->setCellValue('B'.$start2, $dataHeader2[$i][1]);
			$this->excel->getActiveSheet()->getStyle('B'.$start2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('B'.$start2)->getFont()->setSize(11);
			$this->excel->getActiveSheet()->getStyle('B'.$start2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

			$this->excel->getActiveSheet()->mergeCells('C'.$start2.':I'.$start2);
			$this->excel->getActiveSheet()->setCellValue('c'.$start2, $dataHeader2[$i][2]);
			$this->excel->getActiveSheet()->getStyle('C'.$start2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('C'.$start2)->getFont()->setSize(11);
			$this->excel->getActiveSheet()->getStyle('C'.$start2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$start2++;
		}

        $this->excel->getActiveSheet()->getStyle('A12:I15')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('A12:A15')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('B12:B15')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('C12:I15')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('A16:I16')->applyFromArray($outline);

		$this->excel->getActiveSheet()->setCellValue('A16', 'Kelompok Sasaran Kegitan');
		$this->excel->getActiveSheet()->getStyle('A16')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A16')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('A16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

		$this->excel->getActiveSheet()->setCellValue('B16', ': BPKAD Kota Kendari');
		$this->excel->getActiveSheet()->getStyle('B16')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B16')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('B16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

		$this->excel->getActiveSheet()->mergeCells('A17:I17');
		$this->excel->getActiveSheet()->setCellValue('A17', 'RINCIAN DOKUMEN PELAKSANAAN ANGGARAN BELANJA LANGSUNG PROGRAM DAN PER KEGIATAN SATUAN KERJA PERANGKAT DAERAH');
		$this->excel->getActiveSheet()->getStyle('A17')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A17')->getFont()->setSize(10);
		$this->excel->getActiveSheet()->getStyle('A17')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('A17')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		
		$this->excel->getActiveSheet()->mergeCells('A18:A19');
        $this->excel->getActiveSheet()->getStyle('A18:A19')->applyFromArray($outline);
		$this->excel->getActiveSheet()->setCellValue('A18', 'KODE REKENING');
		$this->excel->getActiveSheet()->getStyle('A18')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A18')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('A18')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('A18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


		$this->excel->getActiveSheet()->mergeCells('B18:B19');
        $this->excel->getActiveSheet()->getStyle('B18:B19')->applyFromArray($outline);
		$this->excel->getActiveSheet()->setCellValue('B18', 'URAIAN');
		$this->excel->getActiveSheet()->getStyle('B18')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B18')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('B18')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('B18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


		$this->excel->getActiveSheet()->mergeCells('C18:H18');
        $this->excel->getActiveSheet()->getStyle('C18:H18')->applyFromArray($outline);
		$this->excel->getActiveSheet()->setCellValue('C18', 'RINCIAN PERHITUNGAN');
		$this->excel->getActiveSheet()->getStyle('C18')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C18')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('C18')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('C18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$this->excel->getActiveSheet()->mergeCells('C19:D19');
        $this->excel->getActiveSheet()->getStyle('C19:D19')->applyFromArray($outline);
		$this->excel->getActiveSheet()->setCellValue('C19', 'VOLUME');
		$this->excel->getActiveSheet()->getStyle('C19')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C19')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('C19')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('C19')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


		$this->excel->getActiveSheet()->mergeCells('E19:F19');
        $this->excel->getActiveSheet()->getStyle('E19:F19')->applyFromArray($outline);
		$this->excel->getActiveSheet()->setCellValue('E19', 'SATUAN');
		$this->excel->getActiveSheet()->getStyle('E19')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('E19')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('E19')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('E19')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$this->excel->getActiveSheet()->mergeCells('G19:H19');
        $this->excel->getActiveSheet()->getStyle('G19:H19')->applyFromArray($outline);
		$this->excel->getActiveSheet()->setCellValue('G19', 'HARGA SATUAN');
		$this->excel->getActiveSheet()->getStyle('G19')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('G19')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('G19')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('G19')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$this->excel->getActiveSheet()->mergeCells('I18:I19');
        $this->excel->getActiveSheet()->getStyle('I18:I19')->applyFromArray($outline);		
		$this->excel->getActiveSheet()->setCellValue('I18', 'JUMLAH ( Rp )');
		$this->excel->getActiveSheet()->getStyle('I18')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('I18')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('I18')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('I18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		// NUMBER HORIZONTAL	

        $this->excel->getActiveSheet()->getStyle('A20')->applyFromArray($outline);		
		$this->excel->getActiveSheet()->setCellValue('A20', 1);
		$this->excel->getActiveSheet()->getStyle('A20')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A20')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('A20')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('A20')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getStyle('B20')->applyFromArray($outline);		
		$this->excel->getActiveSheet()->setCellValue('B20', 2);
		$this->excel->getActiveSheet()->getStyle('B20')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B20')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('B20')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('B20')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$this->excel->getActiveSheet()->mergeCells('C20:D20');
        $this->excel->getActiveSheet()->getStyle('C20:D20')->applyFromArray($outline);		
		$this->excel->getActiveSheet()->setCellValue('C20', 3);
		$this->excel->getActiveSheet()->getStyle('C20')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C20')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('C20')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('C20')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$this->excel->getActiveSheet()->mergeCells('E20:F20');
        $this->excel->getActiveSheet()->getStyle('E20:F20')->applyFromArray($outline);		
		$this->excel->getActiveSheet()->setCellValue('E20', 4);
		$this->excel->getActiveSheet()->getStyle('E20')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('E20')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('E20')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('E20')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$this->excel->getActiveSheet()->mergeCells('G20:H20');
        $this->excel->getActiveSheet()->getStyle('G20:H20')->applyFromArray($outline);		
		$this->excel->getActiveSheet()->setCellValue('G20', 5);
		$this->excel->getActiveSheet()->getStyle('G20')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('G20')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('G20')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('G20')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$this->excel->getActiveSheet()->setCellValue('I20', '6 = 3 x 5');
        $this->excel->getActiveSheet()->getStyle('I20')->applyFromArray($outline);		
		$this->excel->getActiveSheet()->getStyle('I20')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('I20')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('I20')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('I20')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$this->excel->getActiveSheet()->setCellValue('A21', '5');
        $this->excel->getActiveSheet()->getStyle('A21')->applyFromArray($outline);		
		$this->excel->getActiveSheet()->getStyle('A21')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A21')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('A21')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

		$this->excel->getActiveSheet()->setCellValue('B21', 'BELANJA');
        $this->excel->getActiveSheet()->getStyle('B21')->applyFromArray($outline);		
		$this->excel->getActiveSheet()->getStyle('B21')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B21')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('B21')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

		$this->excel->getActiveSheet()->setCellValue('I21', 197700000);
		$this->excel->getActiveSheet()->getStyle('I21')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('I21')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('I21')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->excel->getActiveSheet()->getStyle('I21')->getNumberFormat()->setFormatCode($accountingFormat);

		$this->excel->getActiveSheet()->getStyle('C21:I21')->applyFromArray($all);			
		$this->excel->getActiveSheet()->mergeCells('C21:D21');			
		$this->excel->getActiveSheet()->mergeCells('E21:F21');			
		$this->excel->getActiveSheet()->mergeCells('G21:H21');			

		$this->excel->getActiveSheet()->mergeCells('A23:C23');
        $this->excel->getActiveSheet()->getStyle('A23:C23')->applyFromArray($outline);
        $this->excel->getActiveSheet()->getStyle('A24:C24')->applyFromArray($outline);
		$this->excel->getActiveSheet()->setCellValue('A23', 'RENCANA PENARIKAN PER TRIWULAN');
		$this->excel->getActiveSheet()->getStyle('A23')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A23')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('A23')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('A23')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$this->excel->getActiveSheet()->mergeCells('D23:F23');
        $this->excel->getActiveSheet()->getStyle('D23:F24')->applyFromArray($outline);
		$this->excel->getActiveSheet()->setCellValue('D23', '');
		$this->excel->getActiveSheet()->getStyle('D23')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('D23')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('D23')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('D23')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$this->excel->getActiveSheet()->mergeCells('G23:I23');
        $this->excel->getActiveSheet()->getStyle('G23:I24')->applyFromArray($outline);
		$this->excel->getActiveSheet()->setCellValue('G23', '');
		$this->excel->getActiveSheet()->getStyle('G23')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('G23')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('G23')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('G23')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


		$this->excel->getActiveSheet()->mergeCells('A25:I25');
		$this->excel->getActiveSheet()->setCellValue('A25', 'TIM ANGGARAN PEMERINTAH DAERAH');
		$this->excel->getActiveSheet()->getStyle('A25')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A25')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A25')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('A25')->getFont()->setBold(true);

		$this->excel->getActiveSheet()->setCellValue('A26', 'NO');
		$this->excel->getActiveSheet()->getStyle('A26')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A26')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A26')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('A26')->getFont()->setBold(true);

		$this->excel->getActiveSheet()->setCellValue('B26', 'NAMA');
		$this->excel->getActiveSheet()->getStyle('B26')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B26')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B26')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('B26')->getFont()->setBold(true);

		$this->excel->getActiveSheet()->mergeCells('C26:E26');
		$this->excel->getActiveSheet()->setCellValue('C26', 'NIP');
		$this->excel->getActiveSheet()->getStyle('C26')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C26')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C26')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('C26')->getFont()->setBold(true);

		$this->excel->getActiveSheet()->mergeCells('F26:H26');
		$this->excel->getActiveSheet()->setCellValue('F26', 'JABATAN');
		$this->excel->getActiveSheet()->getStyle('F26')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('F26')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('F26')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('F26')->getFont()->setBold(true);

		$this->excel->getActiveSheet()->setCellValue('I26', 'TANDA TANGAN');
		$this->excel->getActiveSheet()->getStyle('I26')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('I26')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('I26')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('I26')->getFont()->setBold(true);

		// VALUE TBL FOOTER
		$this->excel->getActiveSheet()->setCellValue('A27', '1');
		$this->excel->getActiveSheet()->getStyle('A27')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A27')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A27')->getFont()->setSize(11);

		$this->excel->getActiveSheet()->setCellValue('B27', 'Drs. H. Indra Muhammad');
		$this->excel->getActiveSheet()->getStyle('B27')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$this->excel->getActiveSheet()->getStyle('B27')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B27')->getFont()->setSize(11);

		$this->excel->getActiveSheet()->mergeCells('C27:E27');
		$this->excel->getActiveSheet()->setCellValue('C27', '19199 9918');
		$this->excel->getActiveSheet()->getStyle('C27')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C27')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C27')->getFont()->setSize(11);

		$this->excel->getActiveSheet()->mergeCells('F27:H27');
		$this->excel->getActiveSheet()->setCellValue('F27', 'Plt Sekda');
		$this->excel->getActiveSheet()->getStyle('F27')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('F27')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('F27')->getFont()->setSize(11);

        $this->excel->getActiveSheet()->getStyle('A26:I27')->applyFromArray($all);


		ob_end_clean();
        $filename='Dpa.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        $objWriter->save('php://output');
    }
}