<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Applicant_services
{
    // user var
    protected $id;
    protected $identity;
    protected $first_name;
    protected $last_name;
    protected $phone;
    protected $address;
    protected $email;
    protected $group_id;
    protected $surename	;
    protected $birthplace;
    protected $sex;
    protected $district;
    protected $identity_card_number;
    protected $rt;
    protected $rw;
    
    function __construct(){
        $this->load->model('m_budget_plan');
        $this->id		      ='';
        $this->identity		='';
        $this->first_name	="";
        $this->last_name	="";
        $this->address		="";
        $this->email		  ="";
        $this->group_id		= 2;
        $this->surename		= "";
        $this->birthplace	= "";
        $this->sex		    = "";
        $this->address		= "";
        $this->district		= "";
        $this->identity_card_number	= "";
        $this->rt	= "";
        $this->rw	= "";
    }
    public function __get($var)
  	{
  		return get_instance()->$var;
  	}

    public $name = [
      'id',
      'user_id',
      'party_id',
    ];

    public $label =  [
      'id' => 'Pemohon Id',
      'user_id' => 'Nama Pemohon',
      'users_username'  => 'Nama Pemohon',
      'party_id' => 'Nama Kelompok',
      'party_name' => 'Nama Kelompok',
      'phone' => 'No Pemohon',
    ];

    public $type =  [
      'id' => 'number',
      'user_id' => 'select',
      'party_id' => 'select',
    ];

    public function validation_config($type=null){
        $arr_con = [];
        $userId = ($type!='edit') ? 'trim|required|is_unique[table_user_party.user_id]' : 'trim|required';

        foreach ($this->name as $key => $value) {
          if($value!='id'){
            switch ($value) {
              case 'user_id':
                $arr = array(
                  'field' => $value,
                  'label' => $this->label[$value],
                  'rules' =>  $userId,
                  'errors' => array(
                              'required'      => 'Field %s tidak boleh kosong.',
                              'is_unique'      => 'User %s telah di gunakan.',
                            )
                );
                break;

              default:
                $arr = array(
                  'field' => $value,
                  'label' => $this->label[$value],
                  'rules' =>  'trim|required',
                  'errors' => array(
                              'required' => 'Field %s tidak boleh kosong  .',
                      )
                );
                break;
            }

            array_push($arr_con, $arr);
          }
        }

    		return $arr_con;
  	}

    public function form_data($form_value=null, $param=null){
        $this->load->model('m_user');
        $this->load->model('m_fund_cluster');
        $user = $this->m_user->getWhere('group_id=2');
        $kelompok = $this->m_fund_cluster->get();

        foreach ($user as $k => $v) {
          $select['user_id'][$v->id] = $v->first_name.' '.$v->last_name;
        }

        foreach ($kelompok as $k => $v) {
          $select['party_id'][$v->id] = $v->name;
        }


    	foreach ($this->name as $key => $value) {
          if($form_value!=null){
            if(isset($form_value->{$value})&&$form_value->{$value}!=null){
              $val = $form_value->{$value};
            }else{
              $val = $this->form_validation->set_value($value);
            }
          }else{
            $val = $this->form_validation->set_value($value);
          }

          switch ($this->type[$value]) {
            case 'select':
              $data[$value] = array(
                'name' => $value,
                'label' => $value,
                'id' => $value,
                'type' => $this->type[$value],
                'placeholder' => $this->label[$value],
                'option' => $select[$value],
                'class' => 'form-control show-tick',
                'value' => $val,
              );
              break;

            default:
              $data[$value] = array(
                'name' => $value,
                'label' => $value,
                'id' => $value,
                'type' => $this->type[$value],
                'placeholder' => $this->label[$value],
                'class' => 'form-control',
                'value' => $val,
              );
              break;
          }

        };
        unset($data['id']);
    		return $data;
  	}
    
    public function get_table_config( $_page, $start_number = 1 )
    {
        $table["header"] = array(
          'username' => 'username',
          'user_fullname' => 'Nama Lengkap',
          'phone' => 'No Telepon',
          'identity_card_number' => 'NIK',
        );
        $table["number"] = $start_number;  
        $table[ "action" ] = array(
          array(
            "type" => "link",
            "button_color" => "primary",
            "name" => "Detail",
            "url" => site_url( "fund/applicant/detail/"),
            "param" => "id",
            "form_data" => array(
              "id" => array(
                'type' => 'hidden',
                'label' => "id",
              ),
            ),
            "title" => "User",
            "data_name" => "user_fullname",
          ),
          array(
            "name" => 'X',
            "type" => "modal_delete",
            "modal_id" => "delete_category_",
            "url" => site_url( $_page."delete/"),
            "button_color" => "danger",
            "param" => "id",
            "form_data" => array(
              "id" => array(
                'type' => 'hidden',
                'label' => "id",
              ),
            ),
            "title" => "User",
            "data_name" => "user_fullname",
          ),

        );      
        // $table[ "action" ] = array(
        //   array(
        //     "type" => "link",
        //     "button_color" => "primary",
        //     "name" => "Lengkapi Dokumen",
        //     "url" => site_url( "fund/document_applicant/create/"),
        //     "param" => "id",
        //   ),
        //   array(
        //     "name" => 'X',
        //     "type" => "modal_delete",
        //     "modal_id" => "delete_category_",
        //     "url" => site_url( $_page."delete/"),
        //     "button_color" => "danger",
        //     "param" => "id",
        //     "form_data" => array(
        //       "id" => array(
        //         'type' => 'hidden',
        //         'label' => "id",
        //       ),
        //     ),
        //     "title" => "User",
        //     "data_name" => "user_fullname",
        //   ),

        // );
        
        return $table;
    }

    public function _view_get_form_data( $user_id = -1 )
    {
      if( $user_id != -1 )
      {
        // echo $user_id;
        $user 				= $this->ion_auth_model->user( $user_id )->row();
        $this->identity		= $user->username;
        $this->first_name	= $user->first_name;
        $this->last_name	= $user->last_name;
        $this->phone		  = $user->phone;
        // $this->address		=$user->address;
        $this->email		  = $user->email;
        $this->group_id		= $user->group_id;
      }
      $form_data["form_data"] = array(
            "first_name" => array(
              'type' => 'text',
              'label' => "Nama Depan",
              'readonly' => "",
              'value' => $this->first_name,
            ),
            "last_name" => array(
              'type' => 'text',
              'label' => "Nama Belakang",
              'readonly' => "",
              'value' => $this->last_name,
            ),
            // "email" => array(
            //   'type' => 'text',
            //   'label' => "Email",
            //   'readonly' => "",
            //   'value' =>  $this->email,			  
            // ),
            "phone" => array(
              'type' => 'number',
              'label' => "Nomor Telepon",
              'readonly' => "",
              'value' =>  $this->phone, 
            ),
            "group_id" => array(
              'type' => 'hidden',
              'label' => "Group",
              'value' => 2,
            ),
      );
      return $form_data;
    }

    public function get_form_data( $user_id = -1 )
    {
      if( $user_id != -1 )
      {
        $user 				= $this->ion_auth_model->user( $user_id )->row();
        $this->id		      =$user->id;
        $this->identity		=$user->username;
        $this->first_name	=$user->first_name;
        $this->last_name	=$user->last_name;
        $this->phone		  =$user->phone;
        // $this->address		=$user->address;
        $this->email		=$user->email;
        $this->group_id		=$user->group_id;
      }

      $select['sex'] = ['Pria','Wanita'];
      $select['kec'] = ['Kendari','Kendari Barat','Poasia', 'Abeli', 'Kambu', 'Matabubu', 'Mandonga'];
      $form_data["form_data"] = array(
            "first_name" => array(
              'type' => 'text',
              'label' => "Nama Depan",
              'value' => $this->form_validation->set_value('first_name', $this->first_name),
            ),
            "last_name" => array(
              'type' => 'text',
              'label' => "Nama Belakang",
              'value' => $this->form_validation->set_value('last_name', $this->last_name),
            ),
            // "email" => array(
            //   'type' => 'text',
            //   'label' => "Email",
            //   'value' => $this->form_validation->set_value('email', $this->email),			  
            // ),
            // "phone" => array(
            //   'type' => 'number',
            //   'label' => "Nomor Telepon",
            //   'value' => $this->form_validation->set_value('phone', $this->phone),			  
            // ),
            "identity_card_number" => array(
              'type' => 'ktp',
              'label' => "Nomor Ktp",
              'value' => $this->form_validation->set_value('identity_card_number', $this->identity_card_number),			  
            ),

            "phone" => array(
              'type' => 'hp',
              'label' => "Nomor Telepon",
              'value' => $this->form_validation->set_value('phone', $this->phone),			  
            ),

            "birthplace" => array(
              'type' => 'text',
              'label' => "Tempat Lahir",
              'value' => $this->form_validation->set_value('birthplace', $this->birthplace),			  
            ),

            "sex" => array(
              'type' => 'jns',
              'label' => "Jenis Kelamin",
              'value' => $this->form_validation->set_value('sex', $this->sex),			  
              // 'options' => $select['sex'],
            ),

            "address" => array(
              'type' => 'text',
              'label' => "Alamat",
              'value' => $this->form_validation->set_value('address', $this->address),			  
            ),

            // "district" => array(
            //   'type' => 'kec',
            //   'label' => "Kecamatan",
            //   'value' => $this->form_validation->set_value('district', $this->district),		
            //   'options' => $select['kec'],
            // ),

            // "identity_card_number" => array(
            //   'type' => 'number',
            //   'label' => "Nomor Ktp",
            //   'value' => $this->form_validation->set_value('identity_card_number', $this->identity_card_number),			  
            // ),

            "rt" => array(
              'type' => 'text',
              'label' => "RT",
              'value' => $this->form_validation->set_value('rt', $this->rt),			  
            ),

            "rw" => array(
              'type' => 'text',
              'label' => "RW",
              'value' => $this->form_validation->set_value('rw', $this->rw),			  
            ),

            "group_id" => array(
              'type' => 'hidden',
              'label' => "Group",
              'value' => 2,
            ),
            "id" => array(
              'type' => 'hidden',
              'label' => "ID",
              'value' => $this->id,
            ),
      );
      return $form_data;
    }

    public function get_multupla_form_data( $user_id = -1 )
    {
      if( $user_id != -1 )
      {
        $user 				= $this->ion_auth_model->user( $user_id )->row();
        $this->id		      =$user->id;
        $this->identity		=$user->username;
        $this->first_name	=$user->first_name;
        $this->last_name	=$user->last_name;
        $this->phone		  =$user->phone;
        // $this->address		=$user->address;
        $this->email		=$user->email;
        $this->group_id		=$user->group_id;
      }

      $select['sex'] = ['Pria','Wanita'];
      $select['kec'] = ['Kendari','Kendari Barat','Poasia', 'Abeli', 'Kambu', 'Matabubu', 'Mandonga'];
      $form_data["form_data"] = array(
            "first_name" => array(
              'type' => 'text',
              'label' => "Nama Depan",
              'value' => $this->form_validation->set_value('first_name', $this->first_name),
            ),
            "last_name" => array(
              'type' => 'text',
              'label' => "Nama Belakang",
              'value' => $this->form_validation->set_value('last_name', $this->last_name),
            ),
            "identity_card_number" => array(
              'type' => 'ktp',
              'label' => "Nomor Ktp",
              'value' => $this->form_validation->set_value('identity_card_number', $this->identity_card_number),			  
            ),

            "phone" => array(
              'type' => 'hp',
              'label' => "Nomor Telepon",
              'value' => $this->form_validation->set_value('phone', $this->phone),			  
            ),

            "birthplace" => array(
              'type' => 'text',
              'label' => "Tempat Lahir",
              'value' => $this->form_validation->set_value('birthplace', $this->birthplace),			  
            ),

            "sex" => array(
              'type' => 'jns',
              'label' => "Jenis Kelamin",
              'value' => $this->form_validation->set_value('sex', $this->sex),			  
              // 'options' => $select['sex'],
            ),

            "address" => array(
              'type' => 'text',
              'label' => "Alamat",
              'value' => $this->form_validation->set_value('address', $this->address),			  
            ),

            "district" => array(
              'type' => 'kec',
              'label' => "Kecamatan",
              // 'value' => $this->form_validation->set_value('district', $this->district),		
              // 'options' => $select['kec'],
            ),

            "rt" => array(
              'type' => 'text',
              'label' => "RT",
              'value' => $this->form_validation->set_value('rt', $this->rt),			  
            ),

            "rw" => array(
              'type' => 'text',
              'label' => "RW",
              'value' => $this->form_validation->set_value('rw', $this->rw),			  
            ),

            "group_id" => array(
              'type' => 'hidden',
              'label' => "Group",
              'value' => 2,
            ),
            "id" => array(
              'type' => 'hidden',
              'label' => "ID",
              'value' => $this->id,
            ),
      );
      return $form_data;
    }
}
?>
