    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
        >
    </button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item clearfix" onclick=" $(this).MessageBox('msg', this); " href="javascript:;">Action</a></li>
        <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
        <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
        <li class="dropdown-submenu">
            <a class="dropdown-item" tabindex="-1" href="#">level 1</a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" tabindex="-1" href="#">level 2</a></li>
                <li class="dropdown-submenu">
                    <a class="dropdown-item" tabindex="-1" href="#">level 1</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" tabindex="-1" href="#">level 2</a></li>
                    </ul>
                </li>
            </ul>
        </li>
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