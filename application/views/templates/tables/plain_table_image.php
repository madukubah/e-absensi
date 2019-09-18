<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover  ">
        <thead>
            <tr>
                <th style="width:50px">No</th>
                <?php foreach ($header as $key => $value) : ?>
                    <th><?php echo $value ?></th>
                <?php endforeach; ?>
                <?php if (isset($action)) : ?>
                    <th><?php echo "Aksi" ?></th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = (isset($number) && ($number != NULL))  ? $number : 1;
            foreach ($rows as $ind => $row) :
                ?>
                <tr>
                    <td> <?php echo $no++ ?> </td>
                    <?php foreach ($header as $key => $value) : ?>
                        <td>
                            <?php
                                if( $key == "images" || $key == "image" ):
                            ?>
                                    <img class=" img-fluid mb-2 " src="<?=$row->$key ?>" width="200" height="auto" >
                            <?php 
                                else :
                            ?>
                                    <?php
                                        $attr = "";
                                        if (is_numeric($row->$key) && ($key != 'phone' && $key != 'username'))
                                            $attr = number_format($row->$key);
                                        else
                                            $attr = $row->$key;
                                        if ($key == 'date' || $key == 'create_date' || $key == 'time')
                                            $attr =  date("d/m/Y", $row->$key);

                                        echo $attr;
                                    ?>
                            <?php
                                endif;
                            ?>
                        </td>
                    <?php endforeach; ?>
                    <?php if( isset( $action ) ):?>
                        <td>
                            <!--  -->
                            <!-- <div class="btn-group"> -->
                                <!-- <ul class="nav navbar-nav"> -->
                                    <?php 
                                        foreach ( $action as $ind => $value) :
                                    ?>
                                        <!-- <li>                                 -->
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
                                                        case "modal_form_multipart" :
                                                                $value["data"] = $row;
                                                                $this->load->view('templates/actions/modal_form_multipart', $value ); 
                                                            break;
                                                        case "button_dropdowns" :
                                                                $value["data"] = $row;
                                                                $this->load->view('templates/actions/button_dropdown', $value ); 
                                                            break;
                                                    }
                                            ?>
                                        <!-- </li> -->
                                    <?php 
                                        endforeach;
                                    ?>
                                <!-- </ul> -->
                            <!-- </div> -->
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