<?php
    $_month = array(
        '',
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember',
    );
?>
<div  class="table-responsive ">
    <table class="table table-striped table-bordered table-hover  ">
        <thead class="thin-border-bottom" style="font-size:12px" >
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
        <tbody style="font-size:12px" >
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
                        if( is_numeric( $row->$key ) && ( $key != 'phone' && $key != 'username' ) )
                            $attr = number_format( $row->$key );
                        else
                            $attr = $row->$key ;
                        if( $key == 'month' )
                            $attr =  $_month[ $row->$key ] ;

                        echo $attr;
                    ?>
                    <?php 
                        if( $key  == 'month' ):
                            $_status = array(
                                -2 =>"<span class='badge bg-pink'>Belum Selesai</span>",
                                -1 =>"<span class='badge bg-pink'>Di Tolak</span>",
                                0 =>"<span class='badge bg-pink'>Revisi</span>",
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
                <td style="width:15%">
                    <!--  -->
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <?php 
                                foreach ( $action as $ind => $value) :
                            ?>
                                <li  >                                
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
