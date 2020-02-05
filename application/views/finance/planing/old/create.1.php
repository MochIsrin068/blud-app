<script src="<?php echo base_url();?>assets/jquery.js"></script>
<script type="text/javascript">
function test( a, b )
{
    console.log( a );
    console.log( b.parentNode );
}
$(document).ready(function(){
   
    // define the function within the global scope
    $.fn.MessageBox = function(name, id , a) {

        $( a ).parents('td').find('input[name="account_name[]"]').val( name ) ;
        $( a ).parents('td').find('input[name="account_id[]"]').val( id ) ;
    };
    $("#add").click(function(){
        html = get_form();
        console.log( "html" );
        $('#table_body').append(html);
    });
    $("#subtract").click(function(){
        // if( trips - 1 >0 ) trips--;
        length = $('#table_body').children().length;
        if( length > 1 ) $('#table_body').children().last().remove();

        // $("#trips").html( trips );
    });
    $("#content").on('click','.hapus',function(){
        //   if( trips - 1 > 0 )
        //   {
            $(this).parent().parent().remove() ;
        //   }
          return false;
    });
    // $("#content").on('keyup','input[name="account_id[]"]',function(){
    //     console.log( $(this).val() );
    // });

    // $('input[name="description"]').keyup( function(){
    //     console.log( $(this).val() );
    // });
    
    function get_form()
    {
        account_button =  $("#dropdown_button").html() ;
        var html = '';
        html     +=  '<tr >' ;
        html     +=     '<td>' ;
        html     +=         '<div class="row clearfix">' ;
        html     +=             '<div class="col-md-8">' ;
        html     +=                 '<input  type="text" class="form-control"  name="account_name[]" value=""  />' ;
        html     +=                 '<input style="display:none"  type="text" class="form-control"  name="account_id[]" value=""  />' ;
        html     +=             '</div>' ;
        html     +=             '<div class="col-md-4">' ;
        html     +=                 account_button;
        html     +=             '</div>' ;
        html     +=          '</div>' ;
        html     +=     '</td>' ;
        html     +=     '<td>' ;
        html     +=          '<input  type="text" class="form-control"  name="_description[]" value=""  />' ;
        html     +=     '</td>' ;
        html     +=     '<td>' ;
        html     +=          '<input  type="text" class="form-control" min="0"  name="unit[]" value=""  />' ;        
        html     +=     '</td>' ;
        html     +=     '<td>' ;
        html     +=          '<input  type="number" class="form-control" min="0"  name="quantity[]" value=""  />' ;        
        html     +=     '</td>' ;
        html     +=     '<td>' ;
        html     +=          '<input  type="number" class="form-control" min="0"  name="price[]" value=""  />' ;        
        html     +=     '</td>' ;
        html     +=     '<td>' ;
        html     +=          '<button type="button" id="subtract"  class="hapus btn btn-danger" >Hapus</button>' ;
        html     +=     '</td>' ;
        html     +=  '</tr>' ;
        return html;
    }
});
</script>
<script>
    $(".dropdown-toggle").on("mouseenter", function () {
        // make sure it is not shown:
        if (!$(this).parent().hasClass("show")) {
            $(this).click();
        }
    });
    $(".btn-group, .dropdown").on("mouseleave", function () {
        // make sure it is shown:
        if ($(this).hasClass("show")){
            $(this).children('.dropdown-toggle').first().click();
        }
    });
</script>

<style>
.dropdown-submenu {
    position: relative;
}
.dropdown-submenu > a.dropdown-item:after {
    font-family: FontAwesome;
    content: "\f054";
    float: right;
}
.dropdown-submenu > a.dropdown-item:after {
    content: ">";
    float: right;
}
.dropdown-submenu > .dropdown-menu {
    top: 77%;
    left: 100%;
    margin-top: 0px;
    margin-left: 0px;
}
.dropdown-submenu:hover > .dropdown-menu {
    display: block;
}
</style>

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
                                                <a href="<?php echo site_url($parent_page)?>"><button type="button" class="pull-right btn btn-warning" name="button">Kembali</button></a>
                                            <!--  -->
                                        </div>
                                    </div>
                                </div>

                            </div>
                            </div>
                            <!--  -->
                        </div>
                    </div>
                </div>
                <!--  -->
                <?php echo form_open();  ?>							
                <div class="card" >
                    <div class="body">
                        <!--  -->
                            <?php echo $form_add  ?>	
                        <!--  -->
                    </div>
                </div>
                <!--  -->
                <!--  -->
                <div class="card" >
                    <div class="header">
                        <div class="row clearfix" style="margin-bottom:-10px">
                            <div class="col-md-6">
                                <h2>
                                    List RBA
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="body" id="content" >
                    
                        <!--  -->
                            <!--  -->
                            <div class="">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead class="thin-border-bottom">
                                    <tr >
                                        <th>No Rekening</th>
                                        <th>Deskripsi</th>
                                        <th>Satuan</th>
                                        <th>Jumlah</th>
                                        <th>Harga</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="table_body" >

                                    </tbody>
                                </table>
                            </div>   
                            <!--  -->
                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <button id="subtract" class=" pull-right btn btn-bold btn-danger btn-sm " style="margin-left: 5px;" type="button" >
                                        -
                                    </button>
                                    <button id="add" class=" pull-right btn btn-bold btn-primary btn-sm " style="margin-left: 5px;" type="button" >
                                        +
                                    </button>
                                </div>
                            </div>
                            <!--  -->
                    </div>
                </div>
                <!--  -->
                <!--  -->
                <div class="card" >
                    <div class="body">
                        <div id="dropdown_button" style="display:none">
                            <?php echo $dropdown ?>
                        </div>
                        <br>
                        <br>
                        <!--  -->
                      
                        <div class="row clearfix">
                            <div class="col-md-12">
                                <button class=" pull-right btn btn-bold btn-success btn-sm " style="margin-left: 5px;" type="submit" >
                                    Simpan
                                </button>
                                
                            </div>
                        </div>
                        <!--  -->
                    </div>
                </div>
                <!--  -->
                <?php echo form_close();  ?>
          </div>
      </div>
	<!-- </div> -->
</section>
