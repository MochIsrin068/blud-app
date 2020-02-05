<div  class="table-responsive ">
    <table class="table table-striped table-bordered table-hover" style="font-size:12px" >
        <thead class="thin-border-bottom">
        <tr >
            <th style="width:50px">No</th>
            <?php foreach( $header as $key => $value ):?>
                    <th><?php echo $value ?></th>
            <?php endforeach;?>
            <?php if( isset( $action ) ):?>
                <th style="width:20%" ><?php echo "Aksi" ?></th>
            <?php endif;?>
        </tr>
        </thead>
        <tbody>
        <?php 
            $no =  ( isset( $number ) && ( $number != NULL) )  ? $number : 1 ;
            foreach( $rows as $ind => $row ):
        ?>
        <tr >
            <td> <?php echo $no ++ ?> </td>
            <?php foreach( $header as $key => $value ):?>
                <td  >
                    <?php 
                        $attr = "";
                        if( is_numeric( $row->$key ) && ( $key != 'phone' && $key != 'username' && $key != 'year' ) )
                            $attr = number_format( $row->$key );
                        else
                            $attr = $row->$key ;
                        if( $key == 'date' || $key == 'create_date' )
                            $attr =  date("d/m/Y", $row->$key ) ;

                        echo $attr;
                    ?>
                    <?php 
                        if( $key  == 'title' ):
                            $_status = array(
                                -2 =>"<span class='badge bg-pink'>Belum Selesai</span>",
                                -1 =>"<span class='badge bg-pink'>Di Tolak</span>",
                                0 =>"<span class='badge bg-pink'>Draf</span>",
                                1 =>"<span class='badge bg-green'>Terverifikasi</span>",
                                2 =>"<span class='badge bg-green'>Terverifikasi</span>  <span class='badge bg-green'>DPA</span>",
                                99 =>"<span class='badge bg-orange'>Di Perbaiki</span>",
                            );
                            echo '&nbsp';
                            echo $_status[ $row->status ];
                            
                    ?>
                    <?php 
                        endif;
                    ?>
                </td>
            <?php endforeach;?>
            <?php if( isset( $action ) ):?>
                <td>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <?php 
                                if( $row->status  == -2 ):
                            ?>
                                <li>                                
                                    <a href="<?php echo site_url( "finance/planing/create_detail/").$row->id;?>" style="padding-top:4px !important;padding-bottom:5px !important;margin-top:0px !important;margin-right: 5px" class="btn btn-sm btn-success">Selesaikan</a>
                                </li>
                            <?php
                                endif;
                            ?>
                                <?php 
                                    foreach ( $action as $ind => $value) :
                                ?>
                                    <li>                                
                                        <?php 
                                                switch( $value['type'] )
                                                {
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
                                                    case "button_dropdowns" :
                                                            $value["data"] = $row;
                                                            $this->load->view('templates/actions/button_dropdown', $value ); 
                                                        break;
                                                }
                                        ?>
                                    </li>
                                <?php 
                                    endforeach;
                                ?>
                        </ul>
                    </div>
                </td>
            <?php endif;?>
        </tr>
        <?php 
            endforeach;
        ?>
        </tbody>
    </table>
</div>  