<div  class="table-responsive ">
    <table class="table table-striped table-bordered table-hover  ">
        <thead class="thin-border-bottom" style="font-size:12px" >
        <tr >
            <!-- <th style="width:50px">No</th> -->
            <?php foreach( $header as $key => $value ):?>
                <th><?php echo $value ?></th>
            <?php endforeach;?>
        </tr>
        </thead>
        <tbody style="font-size:12px" >
        <?php 
            $no =  ( isset( $number ) && ( $number != NULL) )  ? $number : 1 ;
            foreach( $rows as $ind => $row ):
        ?>
        <tr >
            <!-- <td> <?php echo $no ++ ?> </td> -->
            <?php foreach( $header as $key => $value ):?>
                <td  >
                    <?php 
                        $attr = "";
                        if( is_numeric( $row->$key ) && ( $key != 'phone' && $key != 'username' && $key != 'serial_number' ) )
                            $attr = number_format( $row->$key );
                        else
                            $attr = $row->$key ;
                        if( $key == 'date' || $key == 'create_date' )
                        {
                            if( is_numeric( $row->$key ) )
                                $attr =  date("d/m/Y", $row->$key ) ;
                        }

                        echo $attr;
                    ?>
                </td>
            <?php endforeach;?>
        </tr>
        <?php 
            endforeach;
        ?>
        </tbody>
    </table>
</div>  
