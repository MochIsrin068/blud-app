<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contract extends Finance_Controller{

    private $services = null;
    private $name = null;
    private $parent_page = 'finance';
    private $current_page = 'finance/contract';
    private $form_data = null;

    public function __construct(){
        parent::__construct();
        $this->load->library('services/Contract_services');
        $this->services = new Contract_services;
        $this->name = $this->services->name;
        $this->load->model(array('m_fund_cluster','m_fund_applicant', 'm_document_applicant', 'm_user'));
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
        $fetch['start'] = $pagination['start_record'];
        $fetch['limit'] = $pagination['limit_per_page'];
        $fetch['like'] = ($key!=null) ? array("name" => $this->name, "key" => $key) : null;
        $fetch['order'] = array("field"=>"id","type"=>"ASC");
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
        $this->data["block_header"] = "Document Contract";
        $this->data["header"] = "KELOMPOK Di SETUJUI";
        $this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

        $this->render( "finance/contract/content");
    }


    public function detail($id=null){
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
        $fetch['order'] = array("field"=>"id","type"=>"ASC");
        $fetch['where'] = array("table_user_party.party_id"=> $id);
        $myTable = 'table_users';
        $member = $this->m_fund_cluster->fetchMember($fetch, $myTable);
        // echo var_dump($this->m_fund_cluster->db);
        // return ;

        $this->data['parent_page'] = $this->current_page;
        $this->data['anggota'] = $member;
        $this->data['name'] = $this->name;
        $this->data['myid'] = $id;
        $this->data['detail'] = true;
        $this->data["block_header"] = "Kelompok Management";
        $this->data["header"] = "Detail Kelompok";
        $this->data["sub_header"] = 'Halaman Ini Hanya Berisi Informasi Detail Dari Data';
        $this->render("finance/contract/detail");
    }



    public function cetakAkad(){
        $this->load->library('Phpword');
        
		$phpWord = new \PhpOffice\PhpWord\PhpWord();
		$phpWord->getCompatibility()->setOoxmlVersion(14);
        $phpWord->getCompatibility()->setOoxmlVersion(15);
        
        
        $id = $this->input->post('idMember');
        $idPrty = $this->input->post('idParty');

		$targetFile = "./uploads/dokumen/";
		$filename = 'Akad Kredit Pinjaman.docx';

        $u['user_id'] = $id;
        $userDoc = $this->m_document_applicant->getWhere($u);

        foreach($userDoc as $ud){
            $usr['id'] = $id;
            $userParty = $this->m_fund_applicant->getWhere($u);
            $partyName = null;

            $idp['id'] = $idPrty;
            $party = $this->m_fund_cluster->getWhere($idp);   
            foreach($party as $prt){
                $partyName = $prt->name;
            }             

            $user = $this->m_user->getWhere($usr);
            foreach($user as $un){
                $section = $phpWord->addSection();
                $section->addText('AKAD KREDIT PINJAMAN', array('bold' => true,'underline' => 'single','name'=> 'calibri','size' => 11,),array('align' => 'center', 'spaceAfter' => 20));

                $section->addTextBreak(1);
                $section->addText(htmlspecialchars("\tNomor :"), array('bold' => true,'name'=> 'calibri','size' => 11,),array(
                    'align' => 'left',
                    'spaceAfter' => 20,
                    'tabs' => array(
                            new \PhpOffice\PhpWord\Style\Tab('left'),
                        )
                ));


                $section->addTextBreak(1);
                $section->addText('Pada hari ini ................................ Tanggal ................. bertempat di Kendari, yang bertanda tangan di bawah ini :', array('bold' => false,'name'=> 'calibri','size' => 11,),array('align' => 'justify', 'spaceAfter' => 20));


                $phpWord->addNumberingStyle(
                    'multilevel',
                    array(
                        'type' => 'multilevel',
                        'levels' => array(
                            array('format' => 'decimal', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360),
                            array('format' => 'upperLetter', 'text' => '%2.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720),
                        )
                    )
                );
                $section->addTextBreak(1);
                $section->addListItem('TOHAMBA, SE. (Direktur) selaku pihak pertama yang berkedudukan di jalan Drs. H. Abd. Silondae No. 37 Kota Kendari dalam hal ini bertindak untuk dan atas nama UPT BLUD Dana Bergulirberdasarkan Peraturan Walikota Nomor : 60 2015, selanjutnya dalam perjanjian ini disebut PIHAK PERTAMA. ----------------------------------------------------------------------------------------', 0, null, 'multilevel', array('align' => 'justify', 'spaceAfter' => 20));

                $section->addListItem($un->first_name.' '.$un->last_name.', yang bertempat tinggal di Jl. Pasaeno No. 08 RT. 06 / RW. 02 Kel. Bende Kec. Kadia dengan No. KTP. '. $ud->identity_card_number .' / Klp '.$partyName.', selanjutnya disebut PIHAK KEDUA. ----------------------------------------------------------------------------------------------------------------------------', 0, null, 'multilevel', array('align' => 'justify', 'spaceAfter' => 20));


                $section->addTextBreak(1);
                $section->addText('Dengan terlebih dahulu menerangkan bahwa : ', array('bold' => false,'name'=> 'calibri','size' => 11,),array('align' => 'left','spaceAfter' => 20));
                $section->addText('Berdasarkan Keputusan Walikota Kendari No. : 164 Tahun 2015 tanggal 2 Januari 2016, telah ditujukan oleh Walikota Kendari untuk mengelola dana bergulir melalui BLUD berdasarkan ketentuan dan persyaratan yang ditetapkan. ----------------------------------------------------------------------------------------------', array('bold' => false,'name'=> 'calibri','size' => 11,),array('align' => 'justify','spaceAfter' => 20));
                $section->addText('Oleh karena itu kedua belah pihak telah sepakat untuk mengadakan perjanjian sesuai dengan ketentuan sebagai berikut : ------------------------------------------------------------------------------------------------', array('bold' => false,'name'=> 'calibri','size' => 11,),array('align' => 'justify','spaceAfter' => 20));

                
                $section->addTextBreak(1);
                $section->addText('Pasal I', array('bold' => true,'name'=> 'calibri','size' => 11,),array('align' => 'center', 'spaceAfter' => 20));
                $section->addText('JUMLAH KREDIT', array('bold' => true,'name'=> 'calibri','size' => 11,),array('align' => 'center', 'spaceAfter' => 20));
                $section->addText('PIHAK PERTAMA memberikan pinjaman modal kepada PIHAK KEDUA sejumlah Rp. 500.000,- (Lima Ratus Ribu Rupiah), yang sejak ditandatanganinya perjanjian ini maka secara hukum PIHAK KEDUA mengakui secara sah telah berutang kepada PIHAK PERTAMA atas uang sejumlah tersebut di atas yang dibebankan kepada PIHAK KEDUA yang berkaitan dengan pinjaman yang dimanfaatkan PIHAK KEDUA. ', array('bold' => false,'name'=> 'tahoma','size' => 11,),array('align' => 'justify','spaceAfter' => 20));

                $section->addTextBreak(1);
                $section->addText('Pasal II', array('bold' => true,'name'=> 'calibri','size' => 11,),array('align' => 'center', 'spaceAfter' => 20));
                $section->addText('UANG JASA DAN SISTEM TANGGUNG RENTENG', array('bold' => true,'name'=> 'calibri','size' => 11,),array('align' => 'center', 'spaceAfter' => 20));


                $phpWord->addNumberingStyle(
                    'huruf',
                    array(
                        'type' => 'multilevel',
                        'levels' => array(
                            array('format' => 'decimal', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360),
                            array('format' => 'upperLetter', 'text' => '%2.', 'left' => 360, 'hanging' => 360, 'tabPos' => 320),
                        )
                    )
                );

                $section->addListItem('PIHAK KEDUA diwajibkan membayar dimuka jasa pinjaman modal sebesar Rp. 30.000,- (Tiga Puluh Ribu Rupiah), selama periode pinjaman. ---------------------------------------------------------------------------', 1, null, 'huruf', array('align' => 'justify','spaceAfter' => 20));

                $section->addListItem('PIHAK KEDUA mengikat diri dengan sistem TANGGUNG RENTENG yang dimaksud adalah :', 1, null, 'huruf', array('align' => 'justify','spaceAfter' => 20));

                $section->addText('   - Mengetahui teman kelompoknya', array('bold' => false,'name'=> 'calibri','size' => 11,),array('align' => 'justify','spaceAfter' => 20));
                $section->addText('   - Bertanggung jawab penuh atas kelompoknya', array('bold' => false,'name'=> 'calibri','size' => 11,),array('align' => 'justify','spaceAfter' => 20));
                $section->addText('   - Apabila dalam satu kelompok terdapat nasabah macet, dan atau menunggak maka PIHAK KEDUA bersedia membayar sisa tunggakan tersebut.', array('bold' => false,'name'=> 'calibri','size' => 11,),array('align' => 'justify','spaceAfter' => 20));


                $section->addTextBreak(1);
                $section->addText('Pasal III', array('bold' => true,'name'=> 'calibri','size' => 11,),array('align' => 'center', 'spaceAfter' => 20));
                $section->addText('JANGKA WAKTU DAN ANGSURAN KREDIT', array('bold' => true,'name'=> 'calibri','size' => 11,),array('align' => 'center', 'spaceAfter' => 20));

                $section->addText('a. PIHAK KEDUA diwajibkan melunasi pinjaman dalam jangka waktu 100 (seratus) hari dan mengikat diri pada PIHAK PERTAMA untuk membayar lunas pinjaman yang telah diterima tersebut paling lambat tanggal ...................................... dengan perhitungan angsuran secara mingguan. ------------', array('bold' => false,'name'=> 'calibri','size' => 11,),array('align' => 'justify','spaceAfter' => 20));

                $section->addText('b. Jika PIHAK KEDUA tidak dapat melunasi kewajiban pada PIHAK PERTAMA sampai tanggal .............................................. maka akad kredit ini tetap berlaku sampai pinjaman PIHAK KEDUA lunas. ', array('bold' => false,'name'=> 'calibri','size' => 11,),array('align' => 'justify','spaceAfter' => 20));

                $section->addText('c. Angsuran pokok untuk pertama kalinya sudah dibayar kembali selambat – lambatnya oleh PIHAK KEDUA pada tanggal ......................................................... setiap kali angsurannya dengan Grace Period selama (0) hari dengan jumlah angsuran sebagai berikut : --------------------------------------------', array('bold' => false,'name'=> 'calibri','size' => 11,),array('align' => 'justify','spaceAfter' => 20));

                $section->addText('   - Angsuran pokok :', array('bold' => false,'name'=> 'calibri','size' => 11,),array('align' => 'left','spaceAfter' => 20));
                $section->addText('     - Minggu I – XIII   =  Rp. 35.000,- / Minggu', array('bold' => false,'name'=> 'calibri','size' => 11,),array('align' => 'left','spaceAfter' => 20));
                $section->addText('     - Minggu Ke XIV	    = Rp. 45.000,- :', array('bold' => false,'name'=> 'calibri','size' => 11,),array('align' => 'left','spaceAfter' => 20));

                $section->addTextBreak(1);
                $section->addText('Pasal IV', array('bold' => true,'name'=> 'calibri','size' => 11,),array('align' => 'center', 'spaceAfter' => 20));
                $section->addText('PENUTUP', array('bold' => true,'name'=> 'calibri','size' => 11,),array('align' => 'center', 'spaceAfter' => 20));
                $section->addText('Mengenai perjanjian ini dengan segala akibatnya, para pihak memilih kedudukan hukum yang tetap dan tidak berubah di kepaniteraan Pengadilan Negeri Kendari .-------------------------------------------------', array('bold' => false,'name'=> 'calibri','size' => 11,),array('align' => 'justify','spaceAfter' => 20));

                $section->addText('Kendari, ...........................................', array('bold' => false,'name'=> 'calibri','size' => 11,),array('align' => 'right','spaceAfter' => 20));

                $section->addText('Direktur UPT PPK-BLUD                    Yang Menerima', array('bold' => false,'name'=> 'calibri','size' => 11,),array('align' => 'center','spaceAfter' => 20));
                $section->addText('  PIHAK PERTAMA,                       PIHAK KEDUA,', array('bold' => true,'name'=> 'calibri','size' => 11,),array('align' => 'center','spaceAfter' => 20));
                
                $section->addTextBreak(2);
                $section->addText('  TAHOMBA, SE.,                    '.$un->first_name.' '.$un->last_name.', ', array('bold' => true,'name'=> 'calibri','size' => 11,),array('align' => 'center','spaceAfter' => 20));
            }
        }

		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
		$objWriter->save($filename);
		// send results to browser to download
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.$filename);
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($filename));
		flush();
		readfile($filename);
		unlink($filename); // deletes the temporary file
		exit;
    }


}
