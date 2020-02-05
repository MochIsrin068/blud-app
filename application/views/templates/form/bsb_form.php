
 <?php  $this->load->library( array( 'form_validation' ) );  ?>
 <?php  $this->load->helper(['form']);  ?>
 <!-- - -->
 <?php foreach( $form_data as $form_name => $attr ): ?>
    <?php
        if( $attr['type'] == 'hidden' )
        {
            $form = array(
                'name' => $form_name,
                'type' => $attr['type'],
                'placeholder' => ( isset( $attr['label'] )  ) ? $attr['label'] : '' ,
                  
                
            );
            $value = ( ( isset( $data ) && ( $data != NULL) )   ? ( isset( $data->$form_name ) ? $data->$form_name : '' ) : ''  );
            $form['value'] = ( isset( $attr['value'] )  ) ? $attr['value'] : $value;
            echo form_input( $form );
            continue;
        }
    ?>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="form-group form-float">
                <div class="form-line">
                    <?php

                        $form = array(
                            'name' => $form_name,
                            'id' => $form_name,
                            'type' => $attr['type'],
                            'placeholder' => ( isset( $attr['label'] )  ) ? $attr['label'] : '' ,
                            'class' => 'form-control',  
                            
                        );
                        if( isset( $attr['readonly'] ) )  $form['readonly'] = '';

                        switch(  $attr['type'] )
                        {
                            case 'date':
                                $form['class'] = "form-control datepicker";
                                $form['type'] = "text";
                            case 'password':
                            case 'email':
                            case 'text':
                            case 'number':
                                $value = ( ( isset( $data ) && ( $data != NULL) )   ? ( isset( $data->$form_name ) ? $data->$form_name : '' ) : ''  );
                                $form['value'] = ( isset( $attr['value'] )  ) ? $attr['value'] : $value;
                                echo '<label for="'.$form_name.'" class="control-label">'.$attr["label"].'</label>';
                                echo form_input( $form );
                                break;
                            case 'hidden':
                                $value = ( ( isset( $data ) && ( $data != NULL) )   ? ( isset( $data->$form_name ) ? $data->$form_name : '' ) : ''  );
                                $form['value'] = ( isset( $attr['value'] )  ) ? $attr['value'] : $value;
                                echo form_input( $form );
                                break;
                            case 'textarea':
                                $value = ( ( isset( $data ) && ( $data != NULL) )   ? ( isset( $data->$form_name ) ? $data->$form_name : '' ) : ''  );
                                $form['rows'] = "5";
                                $form['value'] =  ( isset( $attr['value'] )  ) ? $attr['value'] : $value;
                                echo '<label for="" class="control-label">'.$attr["label"].'</label>';
                                echo form_textarea( $form );
                                break;
                            case 'multiple_file':
                                $form['multiple'] = "";
                            case 'file':
                                echo '<label for="" class="control-label">'.$attr["label"].'</label>';
                                echo form_upload( $form );
                                break;
                            case 'select_search':
                                $form['class'] = 'form-control show-tick';
                                $form['data-live-search'] = 'true';
                            case 'select':
                                $form['options'] = ( isset( $attr['options'] )  ) ? $attr['options'] : '';
                                $value = ( ( isset( $data ) && ( $data != NULL) )   ? ( isset( $data->$form_name ) ? $data->$form_name : '' ) : ''  );
                                $form['selected'] = ( isset( $attr['selected'] )  ) ? $attr['selected'] : $value;
                                echo '<label for="" class="control-label">'.$attr["label"].'</label>';
                                echo form_dropdown( $form );
                                break;
                            case 'jns':
                                echo '<label for="" class="control-label">'.$attr["label"].'</label>';
                                echo '<select name="sex" class="form-control">';
                                echo '<option value="Pria">Pria</option>';
                                echo '<option value="Wanita">Wanita</option>';
                                echo '</select>';
                                break;
                            case 'kec':
                                echo '<label for="" class="control-label">'.$attr["label"].'</label>';
                                echo '<select name="district" class="form-control">';
                                echo '<option value="Kendari">Kendari</option>';
                                echo '<option value="Kendari Barat">Kendari Barat</option>';
                                echo '<option value="Poasia">Poasia</option>';
                                echo '<option value="Abeli">Abeli</option>';
                                echo '<option value="Kambu">Kambu</option>';
                                echo '<option value="Mandonga">Mandonga</option>';
                                echo '<option value="Wua-wua">Wua - wua</option>';
                                echo '<option value="Puuwatu">Puuwatu</option>';
                                echo '<option value="Kadia">Kadia</option>';
                                echo '<option value="Baruga">Baruga</option>';
                                echo '</select>';
                                break;
                            case 'hp' :
                                echo '<label for="" class="control-label">'.$attr["label"].'</label>';
                                echo '<input type="text" class="form-control" name="phone" value="'.$attr["value"].'"placeholder="Nomor Telepon" maxlength="12" minlength="12" required="" aria-required="true">';
                                break;
                            case 'ktp' :
                                echo '<label for="" class="control-label">'.$attr["label"].'</label>';
                                echo '<input type="text" class="form-control" name="identity_card_number" value="'.$attr["value"].'"maxlength="16" minlength="16" placeholder="Nomor Ktp" required="" aria-required="true">';
                                break;
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<!--  -->