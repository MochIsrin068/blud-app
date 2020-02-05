<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Document_applicant_services{
// user var
    protected $id;
    protected $user_id;
    protected $identity_card;
    protected $property_tax;
    protected $electricity_bills;
    protected $water_bills;
    protected $letter_of_recommendation;
    protected $date;
    protected $nominal;
    protected $status;
    
    function __construct(){
        $this->load->model('m_budget_plan');
        $this->id		      ='';
        $this->user_id		='';
        $this->identity_card	= '';
        $this->property_tax	= '';
        $this->electricity_bills		  = '';
        $this->water_bills		= '';
        $this->date		  = '';
        $this->nominal		  = '';
        $this->status		  = '';
    }
    public function __get($var)
  	{
  		return get_instance()->$var;
  	}

    public $name = [
        'id',
        'user_id',
        // 'identity_card_number',
        'identity_card',
        'property_tax',
        'electricity_bills',
        'water_bills',
        'letter_of_recommendation',
        'date',
        'nominal',
        'status',
    ];

    public $label =  [
        'id' => 'Documentt Id',
        'user_id' => 'Nama Pemohon',
        // 'identity_card_number' => 'Nomor KTP',
        'identity_card' => 'Foto KTP',
        'property_tax' => 'Pajak Bangunan',
        'electricity_bills' => 'Buku Rekening Listrik',
        'water_bills' => 'Buku Rekening Air',
        'letter_of_recommendation' => 'Surat Rekomendasi',
        'date' => 'Tanggal',
        'nominal' => 'Nominal Pinjaman',
        'status' => 'Status Kelengkapan',
    ];

    public $type =  [
        'id' => 'number',
        'user_id' => 'select',
        // 'identity_card_number' => 'text',
        'identity_card' => 'file',
        'property_tax' => 'file',
        'electricity_bills' => 'file',
        'water_bills' => 'file',
        'letter_of_recommendation' => 'file',
        'date' => 'date',
        'nominal' => 'text',
        'status' => 'select',
    ];

  

    public function form_data($userId, $form_value=null, $param=null){
        $this->load->model(array('m_fund_applicant', 'm_user'));
        $w['user_id'] = $userId;
        $u['id'] = $userId;
        $user = $this->m_fund_applicant->getWhere($w);
        $usr = $this->m_user->getWhere($u);
        
        // foreach ($user as $k => $v) {
          foreach($usr as $i){
            $select['user_id'][$i->id] = $i->first_name.' '.$i->last_name;
          }
        // }

        $select['status'] = ['Lengkap','Tidak Lengkap'];

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
              $class = $param == 'detail' ? 'form-control readonly' : 'form-control show-tick';
              $data[$value] = array(
                'name' => $value,
                'label' => $value,
                'id' => $value,
                'type' => $this->type[$value],
                'placeholder' => $this->label[$value],
                'data-live-search' => "true",
                'option' => $select[$value],
                'class' => $class,
                'value' => $val,
              );
              break;
            case 'file':
              $data[$value] = array(
                'name' => $value,
                'label' => $value,
                'id' => $value,
                'type' => $this->type[$value],
                'placeholder' => $this->label[$value],
                'class' => '',
                'value' => $val,
              );
              break;
            case 'date':
              $data[$value] = array(
                'name' => $value,
                // 'label' => $value,
                // 'id' => $value,
                'type' => $this->type[$value],
                'placeholder' => '',
                'class' => 'form-control',
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

    public function tabel_header($arr){
      $label = [];
      foreach ($arr as $key => $value) {
        $label[$value] = $this->label[$value];
      }
      if(isset($label['id'])) unset($label['id']);
      return $label;
    }


    public function get_form_data( $user_id = -1 )
    {
      if( $user_id != -1 )
      {
        $user 				= $this->ion_auth_model->user( $user_id )->row();
        $this->id		      =$user->id;
        $this->user_id		=$user->user_id;
        $this->identity_card		=$user->identity_card;
        $this->property_tax	=$user->property_tax;
        $this->electricity_bills	=$user->electricity_bills;
        $this->water_bills		  =$user->water_bills;
        $this->letter_of_recommendation		=$user->letter_of_recommendation;
        $this->date		=$user->date;
        $this->nominal		=$user->nominal;
        $this->status		=$user->status;
      }

      $form_data["form_data"] = array(
            "identity_card" => array(
              'type' => 'file',
              'name' => 'identity_card',
              'label' => "Scan Ktp",
              'value' => $this->form_validation->set_value('identity_card', $this->identity_card),
            ),
            "property_tax" => array(
              'type' => 'file',
              'label' => "Pajak Bangunan",
              'value' => $this->form_validation->set_value('property_tax', $this->property_tax),
            ),
            "electricity_bills" => array(
              'type' => 'file',
              'label' => "Rekening Listrik",
              'value' => $this->form_validation->set_value('electricity_bills', $this->electricity_bills),			  
            ),

            "water_bills" => array(
              'type' => 'file',
              'label' => "Rekening Air",
              'value' => $this->form_validation->set_value('water_bills', $this->water_bills),			  
            ),

            "letter_of_recommendation" => array(
              'type' => 'file',
              'label' => "Surat Rekomendasi",
              'value' => $this->form_validation->set_value('letter_of_recommendation', $this->letter_of_recommendation),			  
              // 'options' => $select['sex'],
            ),
      );
      return $form_data;
    }

}
?>
