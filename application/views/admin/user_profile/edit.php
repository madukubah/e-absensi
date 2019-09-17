<section class="content">
	<div class="container-fluid">
		<div class="block-header">
			<h2>
				<?php echo $headline?>
			</h2>
		</div>
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="header">
						<h2>
							EDIT
							<small>Different sizes and widths</small>
						</h2>
						<ul class="header-dropdown m-r--5">
							<a href="<?php echo site_url($parent_page)?>"><button type="button" class="btn btn-warning" name="button">Kembali</button></a>
						</ul>
					</div>
					<div class="body">

						<h2 class="card-inside-title">Basic Input</h2>
						<div class="row clearfix">
							<div class="col-lg-12">
								<?php if(isset($alert)) echo $alert; ?>
							</div>
							<?php echo form_open($form_action);?>
							<?php foreach ($form_data as $key => $value): ?>
								<div class="col-sm-12">
									<div class="form-group form-float">
										<div class="form-line">
											<?php
												switch ($value['type']) {
													case 'select':
															$label =  $value['placeholder'];
															$options =  $value['option'];
															$name = $value['name'];
															$selected = $value['value'];
															unset($value['placeholder']);
															unset($value['option']);
															unset($value['name']);
															echo "<p' class='text-mute'>$label</p>";
															echo form_dropdown($name, $options, $selected, $value);
														break;
													case 'textarea':
														$label =  $value['placeholder'];
														unset($value['placeholder']);
														echo "<label class='form-label'>$label</label>";
														echo form_textarea($value);
														break;
													default:
															$label =  $value['placeholder'];
															unset($value['placeholder']);
															echo "<label class='form-label'>$label</label>";
															echo form_input($value);
														break;
												}

											?>
										</div>
									</div>
								</div>
							<?php endforeach; ?>

								<div class="col-sm-12 ">
										<button type="clear" class="btn float-left btn-warning waves-effect">Clear</button>
										<button type="submit" class="btn float-left btn-primary waves-effect">Simpan</button>
								</div>
							<?php echo form_close();?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</section>
