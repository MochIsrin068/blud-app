<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Temp extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library(array(
            'form_validation'
        ));
        $this->load->helper('form');
        $this->load->helper(array(
            'url',
            'language'
        ));
        $this->load->model(array('m_temp'));
    }
    public function index(){
        if(!$_POST){
            echo form_open_multipart();
            echo form_upload("filename");
            echo form_submit("submit","submit");
            echo form_close();
        }else{
              $id_skpd =   $this->input->post('id_skpd');
                if (is_uploaded_file($_FILES['filename']['tmp_name'])) {
                    //echo "<h1>" . "File ". $_FILES['filename']['name'] ." Berhasil di Upload" . "</h1>";
                    //echo "<h2>Menampilkan Hasil Upload:</h2>";
                    //readfile($_FILES['filename']['tmp_name']);
                    //$filename= $_FILES['filename']['name'];
                }
                //Import uploaded file to Database, Letakan dibawah sini..
                $handle = fopen($_FILES['filename']['tmp_name'], "r"); //Membuka file dan membacanya
                $no = 0;
                $this->db->trans_begin();
                while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                   
                    
                    $data[18] = $this->time_formatter($data[18]);
                    $data[17] = intval($data[17])*1000;   
                    $data[16] = intval($data[16])*1000;   
                    $data[15] = intval($data[15])*1000;   
                    $data[14] = intval($data[14])*1000;   
                    $data[13] = intval($data[13])*1000;   
                    $data[12] = intval($data[12])*1000;   
                    $data[11] = intval($data[11])*1000;   
                    if($data[10]!=''){
                        $data[10] = $this->time_formatter($data[10]);
                    }else{
                        $tempo = $data[18];
                        $date = new DateTime($tempo); // For today/now, don't pass an arg.
                        $date->modify("-100 day");
                        $data[10] = $date->format("Y-m-d");
                    }
                    
                    foreach ($data as $key => $value) {
                        $data[$key] = addslashes($value);
                    }
                    
                    $import=$this->db->query("INSERT INTO
                    table_temp(
                        kelompok,
                        nama,
                        ttl,
                        jk,
                        alamat,
                        rt,
                        rw,
                        kelurahan,
                        kecamatan,
                        ktp,
                        date_start,
                        pinjaman_pokok,
                        pinjaman_adm,
                        pinjaman_total,
                        angsuran_pokok,
                        angsuran_total,
                        angsuran_adm,
                        sisa_pokok,
                        date_end
                        )
                        VALUES (
                        '$data[0]',
                        '$data[1]',
                        '$data[2]',
                        '$data[3]',
                        '$data[4]',
                        '$data[5]',
                        '$data[6]',
                        '$data[7]',
                        '$data[8]',
                        '$data[9]',
                        '$data[10]',
                        '$data[11]',
                        '$data[12]',
                        '$data[13]',
                        '$data[14]',
                        '$data[15]',
                        '$data[16]',
                        '$data[17]',
                        '$data[18]'
                        )");

                    $no++;

                   

                   
                }
                $this->db->trans_complete();
                echo $no.' Data Masuk Ke Database';
                fclose($handle); //Menutup CSV file
        }
    
    }
    private function time_formatter($t){
        //echo $t;
        $t = explode("/",$t);
        //echo " = ";
        $res = "".$t[2]."-".$t[1]."-".$t[0];
        //echo $res."<br>";
        return $res;
    }
    public function email(){
        $this->load->model('m_temp');
        $data = $this->m_temp->get();
        $email = [];
        foreach($data as $key => $value){
            $em = str_replace(" ","_",$value->nama).'_'.str_replace("-","",$value->date_start);
            array_push($email, $em);
        }
        foreach($email as $v){

        };
        var_dump($this->showDups($email));
        

    }

    function showDups($array)
    {
    $array_temp = array();

    foreach($array as $val)
    {
        if (!in_array($val, $array_temp))
        {
        $array_temp[] = $val;
        }
        else
        {
        echo 'duplicate = ' . $val . '<br />';
        }
    }
    }

    public function parse(){
        $this->load->model('m_temp');
        //$data = $this->m_temp->getWhere(array('kelompok'=>'A.001'));
        $data = $this->m_temp->get();
        $parse = [];
        foreach ($data as $key => $value) {
            $kelompok = $value->kelompok;
            if(sizeof($parse)==0){
                $parse[$kelompok] = [];
            }else{
                if(!array_key_exists($kelompok,$parse)){
                    $parse[$kelompok] = [];
                }
            }
            $parse[$kelompok]['kecamatan'] = $value->kecamatan;
            $arr = array(
                    'nama' => $value->nama,
                    'ttl' => $value->ttl,
                    'jk' => $value->jk,
                    'alamat' => $value->alamat,
                    'rt' => $value->rt,
                    'rw' => $value->rw,
                    'kelurahan' => $value->kelurahan,
                    'kecamatan' => $value->kecamatan,
                    'ktp'=> $value->ktp,
                    'date_start' => $value->date_start,
                    'pinjaman_total' => $value->pinjaman_total,
                    'angsuran_total' => $value->angsuran_total,
                    'date_end' => $value->date_end,

                );
            if(empty($parse[$kelompok]['anggota'])){                    
                $parse[$kelompok]['anggota'][0] = $arr;
            }else{
                array_push($parse[$kelompok]['anggota'], $arr);
            }
            
        }
        //var_dump($parse);
        $this->db->trans_begin();
        foreach ($parse as $k => $v) {
       
        
       /**
         * @package kelompok
         * @return party_id
        */
        $this->db->start_cache();
            $data = array(
            'name' => $v['kecamatan']
            );
            $this->db->insert('table_party', $data);
            $party_id = $this->db->insert_id();

        $this->db->stop_cache();
        $this->db->flush_cache();
        
        $user_status = 1;
        
        foreach($v['anggota'] as $a){
             /**
              * @package user
              * @return user_id
            */
            $this->db->start_cache();
                $nama = explode(" ", $a['nama']);
                $firstname = $nama[0];
                $lastname = $nama[sizeof($nama)-1];
                $username = strtolower($firstname.'_'.$lastname);
                $user['username'] = $username;
                $user['first_name'] = $firstname;
                $user['last_name'] = $lastname;
                $user['group_id'] = 2;
                $user['active'] = 1;
                $user['password'] = password_hash($username, PASSWORD_BCRYPT, array("cost"=>12));
                $user['email'] = $username.'_'.str_replace("-","",$a['date_start']).'@simpealir.com';
                $this->db->insert('table_users', $user);
                $user_id = $this->db->insert_id();
            $this->db->stop_cache();
            $this->db->flush_cache();
            
            
             /**
              * @package userprofile
              * need @var user_id
              */
            $this->db->start_cache();
                $birth = explode(",",$a['ttl']);
                $userprofile['surename'] = $a['nama'];
                $userprofile['birthplace'] = (isset($birth[0])) ? $birth[0] : null;
                $userprofile['birthdate'] = (isset($birth[1])) ? $birth[1] : null;
                $userprofile['sex'] = ($a['jk']=='W') ? 'Wanita': 'Pria';
                $userprofile['address'] = $a['alamat'];
                $userprofile['district'] = $a['kecamatan'];
                $userprofile['village'] = $a['kelurahan'];
                $userprofile['identity_card_number'] = $a['ktp'];
                $userprofile['rt'] = $a['rt'];
                $userprofile['rw'] = $a['rw'];
                $userprofile['user_id'] = $user_id;
               
                $this->db->insert('table_userprofiles', $userprofile);
               
            $this->db->stop_cache();
            $this->db->flush_cache();
            

            /**
              * @package anggota kelompok
              * need @var user_id
              * need @var party_id
              */
            $this->db->start_cache();
                $data = array(
                'user_id' => $user_id,
                'party_id' => $party_id,
                'status' => $user_status
                );
                $this->db->insert('table_user_party', $data);
                
                if($user_status<4) $user_status++;

            $this->db->stop_cache();
            $this->db->flush_cache();
            
             /**
              * @package debt agreement
              * need @var user_id
              * @return dept_agreement_id
              */
            $this->db->start_cache();
                $data = array(
                'user_id' => $user_id,
                'nominal' => $a['pinjaman_total'],
                'begin_date' => $a['date_start'],
                'end_date' => $a['date_end']
                );
                $this->db->insert('table_dept_agreement', $data);
                $dept_agreement_id = $this->db->insert_id();
            $this->db->stop_cache();
            $this->db->flush_cache();
            
             /**
              * @package debt payment
              * need @var user_id
              * need @var debt_agreement_id
              */
            $this->db->start_cache();
                $data = array(
                'dept_agreement_id' => $dept_agreement_id,
                'nominal' => $a['angsuran_total'],
                'date' => date('Y-m-d')
                );
                $this->db->insert('table_dept_payment', $data);
            $this->db->stop_cache();
            $this->db->flush_cache();
            
        }
        
        }

        $this->db->trans_complete();
    }
}
