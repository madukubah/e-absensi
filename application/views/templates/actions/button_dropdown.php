<?php
    $data_param = ( ( isset( $param ) ) ? $data->$param : "" );
?>
 <!--  -->
 <div class="dropdown " class="margin-right:20px !important">
    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown"> <?php echo $name ?>
    <span class="caret"></span></button>
    <ul class="dropdown-menu">
        <?php 
            foreach( $links as $link ):
        ?>
                <li><a  href="<?php echo $link['url'].$data_param ?>"> <?php echo $link['name'] ?> </a></li>
        <?php 
            endforeach;
        ?>
    </ul>
</div>
<!--  -->