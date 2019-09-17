<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<table>
    <tr>
        <td style="text-align:center;font-size: 18px;font-weight: bold "><?php echo $title ?></td>
    </tr>
    
</table>
<br>
<br>
<br>

<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<table border="1" padding="2" >
    <!-- <thead  style="font-size:16px" > -->
    <tr style="font-size:9px;text-align: center;  border-bottom:0.5px solid black" >
        <td style="width:5% ">No</td>
        <td style="width:23% ">Nama Barang</td>
        <td style="width:23% ">Bulan</td>
        <td style="width:23% ">Tahun</td>
        <td style="width:23% ">Jumlah</td>
    </tr>
    <!-- </thead> -->
    <?php 
        $no =  1;
        // $rows = array();
        foreach( $rows as $ind => $row ):
    ?>
    <?php 
        if( $no == count( $rows ) ):
    ?>
        <tr style="font-size:9px; color : yellow" bgcolor="yellow">
    <?php 
        else:
    ?>
        <tr style="font-size:9px; color : yellow" >
    <?php 
        endif;
    ?>
        <td><?php echo $no++ ?></td>
        <?php foreach( $header as $key => $value ):?>
            <td padding="2" height="15"  >
                <?php 
                    $attr = "";
                    if( is_numeric( $row->$key ) && ( $key != 'phone' && $key != 'username'  && $key != 'year' ) )
                        $attr = number_format( $row->$key );
                    else
                        $attr = $row->$key ;
                    if( $key == 'date' || $key == 'create_date' )
                    {
                        if( is_numeric( $row->$key ) )
                            $attr =  date("d/m/Y", $row->$key ) ;
                    }
                    if( $key == 'month' )
                    {
                        $attr =  Util::MONTH[ $row->$key ];
                    }

                    echo $attr;
                ?>
            </td>
        <?php endforeach;?>
    </tr>
    <?php 
        endforeach;
    ?>
</table>

<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////// -->