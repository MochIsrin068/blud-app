<div  class="table-responsive ">
    <table class="table table-striped table-bordered table-hover  ">
        <thead class="thin-border-bottom">
        <tr >
            <th style="width:50px">No</th>
            <?php foreach( $header as $key => $value ):?>
                <th><?php echo $value ?></th>
            <?php endforeach;?>
            <?php if( isset( $action ) ):?>
                <th><?php echo "Aksi" ?></th>
            <?php endif;?>
        </tr>
        </thead>
        <tbody>
        <?php 
            foreach( $rows as $ind => $row ):
        ?>
        <tr >
            <td> <?php echo $ind+1 ?> </td>
            <?php foreach( $header as $key => $value ):?>
                <td  >
                    <?php
                        if( $key == "images" ):
                    ?>
                            <!-- IMAGE -->
                            <a href="" data-toggle="modal" data-target="#image_<?php echo $ind;?>"><?php echo $row->$key;?></a>
                            <br>
                            <div class="modal fade" id="image_<?php echo $ind;?>" role="dialog">
                                <div class="modal-dialog">
                                <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title"><b>#Image Preview</b></h4>
                                        </div>
                                        <div class="modal-body">
                                        <div class="box-body">
                                            <img src="<?php echo $image_url.$row->$key  ?>" alt="" height="auto" width="500" >
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- IMAGE -->
                    <?php 
                        else :
                    ?>
                                <?php 
                                    $attr = "";
                                    if( is_numeric( $row->$key ) && ( $key != 'phone' && $key != 'username' ) )
                                        $attr = number_format( $row->$key );
                                    else
                                        $attr = $row->$key ;
                                    if( $key == 'date' || $key == 'create_date' )
                                        $attr =  date("d/m/Y", $row->$key ) ;

                                    echo $attr;
                                ?>
                    <?php
                        endif;
                    ?>
                </td>
            <?php endforeach;?>
            <?php if( isset( $action ) ):?>
                <td>
                    <!--  -->
                    <?php 
                        foreach ( $action as $ind => $value) {
                            switch( $value['type'] ){
                                case "link" :
                                        $value["data"] = $row;
                                        $this->load->view('templates/actions/link', $value ); 
                                    break;
                                case "modal_delete" :
                                        $value["data"] = $row;
                                        $this->load->view('templates/actions/modal_delete', $value ); 
                                    break;
                                case "modal_form" :
                                        $value["data"] = $row;
                                        $this->load->view('templates/actions/modal_form', $value ); 
                                    break;
                            }
                        }
                    ?>
                    <!--  -->
                </td>
            <?php endif;?>
        </tr>
        <?php 
            endforeach;
        ?>
        </tbody>
    </table>
</div>  
