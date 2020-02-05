<section class="content">
	<!-- <div class="container-fluid"> -->
		<div class="block-header">
			<h2><?php echo $block_header ?></h2>
		</div>
        <div class="row clearfix">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="card">
                  <div class="header">
                      <div class="row clearfix">
                          <div class="col-md-12">
                                <!-- alert  -->
                                <?php
                                    echo $alert;
                                ?>
                                <!-- alert  -->
                          </div>
                      </div>
                      <!--  -->
                      <div class="row clearfix" style="margin-bottom:-10px">
                        <div class="col-md-6">
                          <h2>
                              <?php echo strtoupper($header)?>
                              <small><?php echo $sub_header ?></small>
                          </h2>
                        </div>
                        <!-- search form -->
                        <div class="col-md-6">
                          <div class="row clearfix">
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-10">
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12" style="margin-bottom:0px!important">
                                    </div>
                                    <div class="col-lg-6 col-md-4 col-sm-12 col-xs-12">
                                        <!--  -->
                                            <?php echo $model_form_add?>
                                        <!--  -->
                                    </div>
                                </div>
                            </div>

                          </div>
                        </div>
                      </div>
                  </div>
                  <div class="body">
                        <!-- <?php echo var_dump( $accounts ) ?> -->
                        <!-- HIRARKI -->
                        <div style="  " >
                            <div class="tree" >
                                <ul>
                                    <?php
                                        function print_tree( $datas )
                                        {
                                            foreach( $datas as $data )
                                            {       
                                                    echo  '<li>';
                                                          echo '<a href="#">'.$data->code." -> ".$data->name.'</a>';
                                                        ?>
                                                        <button class="btn btn-white btn-info btn-bold btn-xs" data-toggle="modal" data-target="#add_account_<?php echo $data->id ?>">
                                                            + 
                                                        </button>
                                                        <button class="btn btn-white btn-info btn-bold btn-xs" data-toggle="modal" data-target="#edit_account_<?php echo $data->id ?>">
                                                            Edit
                                                        </button>
                                                        <button class="btn btn-white btn-danger btn-bold btn-xs" data-toggle="modal" data-target="#delete_account_<?php echo $data->id ?>">
                                                            X
                                                        </button>
                                                        <?php echo $data->description?>
                                                        <?php
                                                        echo "<ul>";
                                                            print_tree( $data->branch );
                                                        echo "</ul>";
                                                    echo  '</li>';
                                            }
                                        };
                                        print_tree( $tree );
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <!-- HIRARKI -->
                  </div>
              </div>
          </div>
      </div>
	<!-- </div> -->
  <?php
    foreach( $accounts as $account )
    {
        $model_form_add = array(
          "name" => "Tambah Child Rekening",
          "modal_id" => "add_account_",
          "button_color" => "primary",
          "url" => site_url("setting/account/add/"),
          "form_data" => array(
            "code" => array(
              'type' => 'text',
              'label' => "Kode",
              'value' => '',
            ),
            "name" => array(
              'type' => 'text',
              'label' => "Nama",
              'value' => '',
            ),
            "description" => array(
              'type' => 'textarea',
              'label' => "Deskripsi",
              'value' => '',
            ),
            "account_id" => array(
              'type' => 'hidden',
              'readonly' => '',
              'label' => "account_id",
              'value' => $account->id,
            ),
          ),
          'data' => $account,
          'param' => "id",
        );
        $this->load->view('templates/actions/modal_form_no_button', $model_form_add ); 
        $model_form_edit = array(
          "name" => "Edit Rekening",
          "modal_id" => "edit_account_",
          "button_color" => "primary",
          "url" => site_url("setting/account/edit/"),
          "form_data" => array(
            "code" => array(
              'type' => 'text',
              'label' => "Kode",
            ),
            "name" => array(
              'type' => 'text',
              'label' => "Nama",
            ),
            "description" => array(
              'type' => 'textarea',
              'label' => "Deskripsi",
            ),
            "account_id" => array(
              'type' => 'hidden',
              'readonly' => '',
              'label' => "account_id",
            ),
            "id" => array(
              'type' => 'hidden',
              'readonly' => '',
              'label' => "id",
            ),
          ),
          'data' => $account,
          'param' => "id",
        );
        $this->load->view('templates/actions/modal_form_no_button', $model_form_edit ); 

        $model_form_delete =array(
          "type" => "modal_delete",
          "modal_id" => "delete_account_",
          "url" => site_url("setting/account/delete/"),
          "button_color" => "danger",
          "param" => "id",
          "form_data" => array(
            "id" => array(
              'type' => 'hidden',
              'label' => "id",
            ),
          ),
          'data' => $account,
          "title" => "Rekening",
          "data_name" => "name",
        );
        $this->load->view('templates/actions/modal_delete_no_button', $model_form_delete ); 
    }
  ?>
</section>

