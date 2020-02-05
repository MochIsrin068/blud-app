    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
        >
    </button>
    <ul class="dropdown-menu">
        <?php
            function print_tree( $datas )
            {
                foreach( $datas as $data )
                {       
                    if( empty( $data->branch ) )
                    {
                        ?>
                        <li style="width:auto" ><a class="dropdown-item clearfix" onclick=" $(this).MessageBox('<?php echo $data->name ?>', '<?php echo $data->id ?>' ,  this); " href="javascript:;"><?php echo $data->code ?></a></li>
                        <?php
                    }
                    else
                    {
                        ?>
                        <li class="dropdown-submenu" style="width:100% !important">
                            <a class="dropdown-item" tabindex="-1" href="javascript:;"> <?php echo $data->code ?> </a>
                            <ul class="dropdown-menu" style="width:100% !important" >
                                <?php
                                    print_tree( $data->branch );
                                ?>
                            </ul>
                        </li>
                        <?php
                    }
                }
            };
            print_tree( $tree );
        ?>
    </ul>