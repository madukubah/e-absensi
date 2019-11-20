    <form action="">
        <div class="row mb-2">
            <div class="col-md-4 col-sm-12 ">
                <?php
                $form = array(
                    'name' => 'date',
                    'id' => 'date',
                    'class' => 'form-control',
                );
                $attr = $form_data['date'];
                $form['options'] = $attr['options'];
                $form['selected'] = $attr['selected'];
                echo '<label for="" class="control-label">' . $attr["label"] . '</label>';
                echo form_dropdown($form);
                ?>
            </div>
            <div class="col-md-4 col-sm-12 ">
                <?php
                $form = array(
                    'name' => 'month',
                    'id' => 'month',
                    'class' => 'form-control',
                );
                $attr = $form_data['month'];
                $form['options'] = $attr['options'];
                $form['selected'] = $attr['selected'];
                echo '<label for="" class="control-label">' . $attr["label"] . '</label>';
                echo form_dropdown($form);
                ?>
            </div>
            <?php if (isset($form_data['year'])) : ?>
                <div class="col-md-4 col-sm-12 ">
                    <?php
                        $form = array(
                            'name' => 'year',
                            'id' => 'year',
                            'class' => 'form-control',
                        );
                        $attr = $form_data['year'];
                        $form['options'] = $attr['options'];
                        $form['selected'] = $attr['selected'];
                        echo '<label for="" class="control-label">' . $attr["label"] . '</label>';
                        echo form_dropdown($form);
                        ?>
                <?php endif; ?>
                <?php if (isset($form_data['opd'])) : ?>
                    <div class="col-md-4 col-sm-12 ">
                        <?php
                            $form = array(
                                'name' => 'opd',
                                'id' => 'opd',
                                'class' => 'form-control',
                            );
                            $attr = $form_data['opd'];
                            $form['options'] = $attr['options'];
                            $form['selected'] = $attr['selected'];
                            echo '<label for="" class="control-label">' . $attr["label"] . '</label>';
                            echo form_dropdown($form);
                            ?>
                        <input type="hidden" name="group_by" value="date">
                    </div>
                <?php endif; ?>

                <div class="col-md-4 col-sm-12 " style="margin-top:32px">
                    <button type="submit" class="btn btn-primary btn-sm" data-toggle="modal" style="margin-left: 5px;">
                        Filter
                    </button>
                </div>
                </div>
    </form>
    <!-- - -->