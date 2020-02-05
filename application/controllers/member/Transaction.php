<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends Member_Controller{

    private $services = null;
    private $name = null;
    private $parent_page = 'member';
    private $current_page = 'member/transaction';
    // private $current_page2 = 'member/userParty';
    private $form_data = null;


    public function __construct(){
        parent::__construct();
        $this->load->library('services/Transaction_services');
        $this->services = new Transaction_services;
        $this->name = $this->services->name;
        $this->form_data = $this->services->form_data();
        $this->load->model(
        array(
            'm_fund_cluster',
            'm_fund_applicant',
            'm_dept_agreement',
            'm_dept_payment'
            )
        );
    }


    public function index(){
        //basic variable
        $key = $this->input->get('key');
        $page = ($this->uri->segment(4)) ? ($this->uri->segment(4) - 1) : 0;

        $tabel_cell = ['id','name'];
        $tabel_cell_select = ['id','name'];
        //pagination parameter
        $pagination['base_url'] = base_url($this->current_page) .'/index';
        $pagination['total_records'] = (isset($key)) ? $this->m_fund_cluster->search_count($key, $this->name) : $this->m_fund_cluster->get_total();
        $pagination['limit_per_page'] = 10;
        $pagination['start_record'] = $page*$pagination['limit_per_page'];
        $pagination['uri_segment'] = 4;
        //set pagination
        if ($pagination['total_records']>0) $this->data['links'] = $this->setPagination($pagination);


        //fetch data from database
        $fetch['select'] = ['*'];
        $fetch['select_join'] = 
        [
          'table_request.status',
        ];
        $fetch['join'] = 
        [
          array('table'=>'table_request','id'=>'party_id','join'=>'left'),
          array('table'=>'table_user_party','id'=>'party_id','join'=>'left')
        ];
        $userIdSession = $this->session->userdata('user_id');
        $fetch['where'] = array("table_request.status" => 4, "table_user_party.user_id" => $userIdSession);
        $fetch['start'] = $pagination['start_record'];
        $fetch['limit'] = $pagination['limit_per_page'];
        $fetch['like'] = ($key!=null) ? array("name" => $this->name, "key" => $key) : null;
        $fetch['order'] = array("field"=>"id","type"=>"DESC");
        $for_table = $this->m_fund_cluster->fetch($fetch);


        //get flashdata
        $alert = $this->session->flashdata('alert');
        $this->data["key"] = ($key!=null) ? $key : false;
        $this->data["alert"] = (isset($alert)) ? $alert : NULL ;
        $this->data["for_table"] = $for_table;

        // $this->data["table"] = $this->load->view('templates/tables/cluster_table', $for_table, true);

        $this->data["table_header"] = $this->services->tabel_header($tabel_cell);
        $this->data["number"] = $pagination['start_record'];
        $this->data["current_page"] = $this->current_page;
        // $this->data["current_page2"] = $this->current_page2;
        $this->data["block_header"] = "Kelompok Management";
        $this->data["header"] = "TABLE KELOMPOK";
        $this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $array_kecamatan = array(
          "kendari" => "kendari",
          "Kendari Barat" => "Kendari Barat",
          "Poasia" => "Poasia",
          "Abeli" => "Abeli",
          "Kambu" => "Kambu",
          "Mandonga" => "Mandonga",
          "Wua-wua" => "Wua-wua",
          "Puuwatu" => "Puuwatu",
          "Kadia" => "Kadia",
          "Baruga" => "Baruga",
        );
        $model_form_add = array(
        "name" => "Tambah",
        "modal_id" => "add_party_",
        "button_color" => "primary",
        "url" => site_url( $this->current_page."/create_party/" ),
        "form_data" => array(
          "name" => array(
            'type' => 'select_search',
            'label' => "Kecamatan",
            'options' => $array_kecamatan,
          ),
        ),
        'data'=> NULL,
      );
      $this->data[ "model_form_add" ] = $this->load->view('templates/actions/modal_form', $model_form_add, true ); 
      $this->render( "member/transaction/content");
    }


    public function detail( $id=null){
      $idUser = $this->input->post('id');

      $getAgreement = null;


      if($idUser !== false){
        $data['user_id'] = $idUser;
        $getAgreement = $this->m_dept_agreement->getWhere($data);
      }


      $party_id = $id;
      if($id==null){
        redirect($this->current_page);
      }
      $w['id'] = $id;
      $form_value = $this->m_fund_cluster->getWhere($w);
      if($form_value==false){
        redirect($this->current_page);
      }else{
        $form_value = $form_value[0];
      }

      $fetch['select'] = ['*'];
      $fetch['select_join'] = 
      [
        'table_user_party.party_id',
        'table_user_party.status',
      ];

      $fetch['join'] = [array('table'=>'table_user_party','id'=>'user_id','join'=>'left')];
      $fetch['order'] = array("field"=>"table_user_party.status","type"=>"ASC");
      $fetch['where'] = array("table_user_party.party_id"=> $id);
      $myTable = 'table_users';
      $member = $this->m_fund_cluster->fetchMember($fetch, $myTable);

      $getUser['party_id'] = $party_id;
      $userPartyId = $this->m_fund_applicant->getWhere($getUser);
        
      foreach($getAgreement as $ga){
        // Handle Sum
        $x['dept_agreement_id'] = $ga->id;
        $getPayment = $this->m_dept_payment->sum($x);        
        $this->data['totalPayment'] = $getPayment[0]->nominal;

        // Handle All
        $getPaymentAll = $this->m_dept_payment->getWhere($x);    
        $this->data['historyPayment'] = $getPaymentAll;

      }

      $this->data['party_id'] = $party_id;
      $this->data['form_data'] = $this->services->form_data($form_value);
      $this->data['parent_page'] = $this->current_page;
      $this->data['anggota'] = $member;
      $this->data['nominal'] = $getAgreement;
      $this->data['name'] = $this->name;
      $this->data['myid'] = $id;
      $this->data['detail'] = true;
      $this->data["block_header"] = "Detail Piutang";
      $this->data["header"] = "Detail Kelompok";
      $this->data["sub_header"] = 'Halaman Ini Hanya Berisi Informasi Detail Dari Data';
      $this->render("member/transaction/detail");
    }

    public function pay($id, $totalAgreement, $totalPayment){

        $nPayment = $this->input->post('nominal');
        $input_data['dept_agreement_id'] = $this->input->post('id');
        $input_data['nominal'] = $nPayment;
        $input_data['date'] = date('Y-m-d');

        $partyId =$this->input->post('partyId');      

        $totalPaymentNow = $totalPayment + $nPayment;
        if($totalPaymentNow > $totalAgreement){
            $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, 'Pebayarn Ditolak, Pembayaran Melebihi Utang'));
            redirect($this->current_page.'/detail/'.$id);
        }else{
          $insert = $this->m_dept_payment->add($input_data);
          if($insert){
            $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, 'Input data berhasil, Silahkan Tekan Tombol ">" Untuk Melihat Detailnya'));
            redirect($this->current_page.'/detail/'.$id);
          }
        }
    }


    public function cetak($partyid=null){
      if($partyid==null){
        redirect($current_page);
      }
      $data = $partyid;
      $getUserParty =  $this->m_dept_payment->getUserParty($data);

      $this->load->library('excel');
      //load PHPExcel library
		  $this->excel->setActiveSheetIndex(0);
      //name the worksheet
      $this->excel->getActiveSheet()->setTitle('SETORAN DANA BERGULIR');
		

      $all = array(
  		  'borders' => array('allborders' =>
  			array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' =>
  				array('argb' => '0000'),
  				),
  			),
      );	

      $numberFormat = '#,##0.00';	
      $accountingFormat = '_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)';

      $no = 1;

      //SET DIMENSI TABEL
		  $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(7);
		  $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		  $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
      $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(23);
      $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(23);
      $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(23);
      $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(23);


      // header
      // STARTING HEADER
      $this->excel->getActiveSheet()->mergeCells('A1:G1');
		  $this->excel->getActiveSheet()->setCellValue('A1', ' REKAPITULASI PENYALURAN DAN PENERIMAAN SETORAN');
		  $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		  $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
      $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        
      $this->excel->getActiveSheet()->mergeCells('A2:G2');
		  $this->excel->getActiveSheet()->setCellValue('A2', 'PINJAMAN DANA BERGULIR UPT. DANA BERGULIR BPKAD KOTA KENDARI');
		  $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		  $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
      $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);


      $this->excel->getActiveSheet()->mergeCells('A3:G3');
		  $this->excel->getActiveSheet()->setCellValue('A3', 'BULAN '.date('M Y'));
		  $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		  $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
      $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);


      ////////////////////////////////////////////////////////////////////
      $this->excel->getActiveSheet()->mergeCells('A5:A6');
		  $this->excel->getActiveSheet()->setCellValue('A5', 'NO');
		  $this->excel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $this->excel->getActiveSheet()->getStyle('A5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		  $this->excel->getActiveSheet()->getStyle('A5')->getFont()->setSize(12);
      $this->excel->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);


      $this->excel->getActiveSheet()->mergeCells('B5:B6');
		  $this->excel->getActiveSheet()->setCellValue('B5', 'KLP');
		  $this->excel->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $this->excel->getActiveSheet()->getStyle('B5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		  $this->excel->getActiveSheet()->getStyle('B5')->getFont()->setSize(12);
      $this->excel->getActiveSheet()->getStyle('B5')->getFont()->setBold(true);

      $this->excel->getActiveSheet()->mergeCells('C5:C6');
		  $this->excel->getActiveSheet()->setCellValue('C5', 'NAMA');
		  $this->excel->getActiveSheet()->getStyle('C5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $this->excel->getActiveSheet()->getStyle('C5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		  $this->excel->getActiveSheet()->getStyle('C5')->getFont()->setSize(12);
      $this->excel->getActiveSheet()->getStyle('C5')->getFont()->setBold(true);


      $this->excel->getActiveSheet()->mergeCells('D5:E5');
		  $this->excel->getActiveSheet()->setCellValue('D5', 'ANGSURAN');
		  $this->excel->getActiveSheet()->getStyle('D5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $this->excel->getActiveSheet()->getStyle('D5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		  $this->excel->getActiveSheet()->getStyle('D5')->getFont()->setSize(12);
      $this->excel->getActiveSheet()->getStyle('D5')->getFont()->setBold(true);

		  $this->excel->getActiveSheet()->setCellValue('D6', 'TANGGAL');
		  $this->excel->getActiveSheet()->getStyle('D6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $this->excel->getActiveSheet()->getStyle('D6')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		  $this->excel->getActiveSheet()->getStyle('D6')->getFont()->setSize(12);
      $this->excel->getActiveSheet()->getStyle('D6')->getFont()->setBold(true);

      $this->excel->getActiveSheet()->setCellValue('E6', 'POKOK');
		  $this->excel->getActiveSheet()->getStyle('E6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $this->excel->getActiveSheet()->getStyle('E6')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		  $this->excel->getActiveSheet()->getStyle('E6')->getFont()->setSize(12);
      $this->excel->getActiveSheet()->getStyle('E6')->getFont()->setBold(true);

		  $this->excel->getActiveSheet()->setCellValue('F5', 'SISA POKOK');
		  $this->excel->getActiveSheet()->getStyle('F5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $this->excel->getActiveSheet()->getStyle('F5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		  $this->excel->getActiveSheet()->getStyle('F5')->getFont()->setSize(12);
      $this->excel->getActiveSheet()->getStyle('F5')->getFont()->setBold(true);


		  $this->excel->getActiveSheet()->setCellValue('G5', 'TANGGAL JATUH TEMPO');
		  $this->excel->getActiveSheet()->getStyle('G5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $this->excel->getActiveSheet()->getStyle('G5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		  $this->excel->getActiveSheet()->getStyle('G5')->getFont()->setSize(12);
      $this->excel->getActiveSheet()->getStyle('G5')->getFont()->setBold(true);

      ////////////////////////////////////////////// END HEADER //////////////////////////////////////////////////


      $start = 8;
      $m = null;
      // echo var_dump($this->db->queries);return;

      $partyName = null;
      foreach($getUserParty as $gup){
          $this->excel->getActiveSheet()->setCellValue('A'.$start, $no);
		      $this->excel->getActiveSheet()->getStyle('A'.$start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		      $this->excel->getActiveSheet()->getStyle('A'.$start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          $this->excel->getActiveSheet()->getStyle('A'.$start)->getFont()->setSize(12);
          
          $partyName = $gup->name.'-'.$gup->party_id;

          $this->excel->getActiveSheet()->setCellValue('B'.$start, $gup->name.'-'.$gup->party_id);
		      $this->excel->getActiveSheet()->getStyle('B'.$start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		      $this->excel->getActiveSheet()->getStyle('B'.$start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          $this->excel->getActiveSheet()->getStyle('B'.$start)->getFont()->setSize(12);

          $this->excel->getActiveSheet()->setCellValue('C'.$start, $gup->first_name.' '.$gup->last_name);
		      $this->excel->getActiveSheet()->getStyle('C'.$start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		      $this->excel->getActiveSheet()->getStyle('C'.$start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          $this->excel->getActiveSheet()->getStyle('C'.$start)->getFont()->setSize(12);

          $x['dept_agreement_id'] = $gup->id_agreement;
          $getPayment = $this->m_dept_payment->sum($x);        
          $sum = $this->data['totalPayment'] = $getPayment[0]->nominal;


          $getPaymentDate = $this->m_dept_payment->getWhere($x);
          $i = 0;

          foreach($getPaymentDate as $p){
            $tgl = explode('-', $p->date);
						$bln = explode('-', $p->date);
						$tahun = explode('-', $p->date);
            $date = $tgl[2].'-'.$bln[1].'-'.$tahun[0];
            
            // DATE
            $this->excel->getActiveSheet()->setCellValue('D'.($start + $i), $date);
            $this->excel->getActiveSheet()->getStyle('D'.($start + $i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D'.($start + $i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D'.($start + $i))->getFont()->setSize(12);

            // NOMINAL
            $this->excel->getActiveSheet()->setCellValue('E'.($start + $i), $p->nominal);
            $this->excel->getActiveSheet()->getStyle('E'.($start + $i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('E'.($start + $i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('E'.($start + $i))->getFont()->setSize(12);
            $this->excel->getActiveSheet()->getStyle('E'.($start + $i))->getNumberFormat()->setFormatCode($numberFormat);


            $i++;
            // $s++;
          }

          $this->excel->getActiveSheet()->setCellValue('F'.($start + 1), ($gup->nominal - $sum));
          $this->excel->getActiveSheet()->getStyle('F'.($start + 1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $this->excel->getActiveSheet()->getStyle('F'.($start + 1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          $this->excel->getActiveSheet()->getStyle('F'.($start + 1))->getFont()->setSize(12);
          $this->excel->getActiveSheet()->getStyle('F'.($start + 1))->getFont()->setBold(true);
          $this->excel->getActiveSheet()->getStyle('F'.($start + 1))->getNumberFormat()->setFormatCode($numberFormat);


          $dateDay = $getPaymentDate[0]->date;
          $tempoDown =  date('Y-m-d', strtotime($dateDay."+99 days"));
          $da = explode('-', $tempoDown);
          $tempoDownInd = $da[2].'-'.$da[1].'-'.$da[0];

          $this->excel->getActiveSheet()->setCellValue('G'.($start + 1), $tempoDownInd);
          $this->excel->getActiveSheet()->getStyle('G'.($start + 1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $this->excel->getActiveSheet()->getStyle('G'.($start + 1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          $this->excel->getActiveSheet()->getStyle('G'.($start + 1))->getFont()->setSize(12);
          $this->excel->getActiveSheet()->getStyle('G'.($start + 1))->getFont()->setBold(true);
          $this->excel->getActiveSheet()->getStyle('G'.($start + 1))->getNumberFormat()->setFormatCode($numberFormat);


          $m = $start;
          $start = $start + (2 + $i);
          $no++;
      }


      ////////////////////////////////////////////////////////////////////////////////////

      $this->excel->getActiveSheet()->mergeCells('A'.($m+3).':D'.($m+3));
      $this->excel->getActiveSheet()->setCellValue('A'.($m+3), 'TOTAL');
		  $this->excel->getActiveSheet()->getStyle('A'.($m+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $this->excel->getActiveSheet()->getStyle('A'.($m+3))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('A'.($m+3))->getFont()->setSize(12);
      $this->excel->getActiveSheet()->getStyle('A'.($m+3))->getFont()->setBold(true);


      $this->excel->getActiveSheet()->setCellValue('E'.($m+3), '=SUM(E8:E'.($m+2).')');
		  $this->excel->getActiveSheet()->getStyle('E'.($m+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $this->excel->getActiveSheet()->getStyle('E'.($m+3))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('E'.($m+3))->getFont()->setSize(12);
      $this->excel->getActiveSheet()->getStyle('E'.($m+3))->getFont()->setBold(true);
      $this->excel->getActiveSheet()->getStyle('E'.($m+3))->getNumberFormat()->setFormatCode($numberFormat);


      $this->excel->getActiveSheet()->setCellValue('F'.($m+3), '=SUM(F8:F'.($m+2).')');
		  $this->excel->getActiveSheet()->getStyle('F'.($m+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $this->excel->getActiveSheet()->getStyle('F'.($m+3))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('F'.($m+3))->getFont()->setSize(12);
      $this->excel->getActiveSheet()->getStyle('F'.($m+3))->getFont()->setBold(true);
      $this->excel->getActiveSheet()->getStyle('F'.($m+3))->getNumberFormat()->setFormatCode($numberFormat);

      $this->excel->getActiveSheet()->setCellValue('G'.($m+3), '');
		  $this->excel->getActiveSheet()->getStyle('G'.($m+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		  $this->excel->getActiveSheet()->getStyle('G'.($m+3))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('G'.($m+3))->getFont()->setSize(12);
      $this->excel->getActiveSheet()->getStyle('G'.($m+3))->getFont()->setBold(true);

      $this->excel->getActiveSheet()->getStyle('A5:G'.($m+3))->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('A5:G'.($m+3))->applyFromArray($all);


      $this->excel->getActiveSheet()->mergeCells('A'.($m+4).':C'.($m+4));
		  $this->excel->getActiveSheet()->setCellValue('A'.($m+4), 'Print By SimpleAlir');
		  $this->excel->getActiveSheet()->getStyle('A'.($m+4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		  $this->excel->getActiveSheet()->getStyle('A'.($m+4))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		  $this->excel->getActiveSheet()->getStyle('A'.($m+4))->getFont()->setSize(10);
      $this->excel->getActiveSheet()->getStyle('A'.($m+4))->getFont()->setBold(true);
      $this->excel->getActiveSheet()->getStyle('A'.($m+4))->getFont()->setItalic(true);


      ob_end_clean();
      $filename='Laporan Harian '.$partyName.'.xls'; //save our workbook as this file name
      header('Content-Type: application/vnd.ms-excel'); //mime type
      header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
      header('Cache-Control: max-age=0'); //no cache

      $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

      $objWriter->save('php://output');

    }

}