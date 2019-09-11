<?php 
	$zaplist = DB('*','tech_oil','tech='.$tech); 
	$oils = DB('*','tech_oil','type="Масло"');
?>
	


<div class="row mb-3 zapBlockIn zap-row">
						<div class="col-sm-8">
							<select name="zaplist" class="form-control mb-1 zaplist">
								
								<?php	foreach ($zaplist as $n) { ?>
										<option value="<?=$n['id']?>"><?=$n['name']?></option>
								<?php } ?>
								<?php	foreach ($oils as $n) { ?>
										<option value="<?=$n['id']?>"><?=$n['name']?></option>
								<?php } ?>
								
							</select>
							<small class="text-secondary">Запчасть</small>
						</div>
						
				
						<div class="col-sm-3">
							
							<input class="form-control mb-1 countzap" type="number" value="0" placeholder="Н-р: 5"/>
							<small class="text-secondary">Кол-во (шт. или л.)</small>
						</div>
                        <div class="col-sm-1 pl-1">
                            <button class="btn btn-outline-secondary form-control pl-0 pr-0 remove-row"><i class="far fa-trash-alt"></i></button>
                        </div>
					</div>
