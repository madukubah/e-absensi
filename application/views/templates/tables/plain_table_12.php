<div  class="table-responsive ">
    <table class="table table-striped table-bordered table-hover  ">
        <thead class="thin-border-bottom" style="font-size:12px" >
        <tr>
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
                        if( is_numeric( $row->$key ) && ( $key != 'phone' && $key != 'username' && $key != 'code' && $key != 'year' ) )
                            $attr = number_format( $row->$key );
                        else
                            $attr = $row->$key ;
                        if( $key == 'date' || $key == 'create_date' || $key == 'time' )
                            $attr =  date("d/m/Y", $row->$key ) ;
                        if( $key == 'month' && is_numeric( $row->$key )  )
                            $attr = Util::MONTH[ $row->$key ] ;

                        echo $attr;
                    ?>
                </td>
            <?php endforeach;?>
            <?php if( isset( $action ) ):?>
                <td>
                    <!--  -->
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
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
