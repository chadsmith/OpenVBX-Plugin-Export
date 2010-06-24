<?php
	$user = OpenVBX::getCurrentUser();
	$tenant_id = $user->values['tenant_id'];
	if(isset($_POST['id'])){
		$flows = OpenVBX::getFlows(array('id' => $_POST['id'], 'tenant_id' => $tenant_id));
		$data = $flows[0]->values['data'];
		$sms_data = $flows[0]->values['sms_data'];
		$export = md5($data.$sms_data).':'.base64_encode($data).':'.base64_encode($sms_data);
		if('on' == $_POST['file']){
			header('Content-type: text/plain');
			header('Content-Disposition: attachment; filename='.preg_replace('/\W/','',$flows[0]->values['name']).'.ovbx');
			echo $export;
			die();
		}
	}
	$flows = OpenVBX::getFlows(array('tenant_id' => $tenant_id));
?>
<style>
	.vbx-export-flow form{
		margin-top:20px;
	}
	.vbx-export-flow p{
		margin:10px 0;
		padding:0 20px;
	}
	.vbx-export-flow h3{
		font-size:14px;
		padding:0 20px;
		margin-top:20px;
	}
</style>
<div class="vbx-content-main">
	<div class="vbx-content-menu vbx-content-menu-top">
		<h2 class="vbx-content-heading">Export Flow</h2>
	</div><!-- .vbx-content-menu -->
    <div class="vbx-table-section vbx-export-flow">
<?php if(count($flows)): ?>
		<form method="post" action="">
			<fieldset class="vbx-input-container">
				<p>
					<label class="field-label">
						<select name="id" class="medium">
<?php foreach($flows as $flow): ?>
							<option value="<?php echo $flow->values['id']; ?>"<?php echo $_POST['id']==$flow->values['id']?' selected="selected"':''; ?>><?php echo $flow->values['name']; ?></option>
<?php endforeach; ?>
						</select>
					</label>
				</p>
				<p><label><input type="checkbox" name="file" /> Save as file</label></p>
				<p><button type="submit" class="submit-button"><span>Export</span></button></p>
			</fieldset>
		</form>
<?php if($export): ?>
		<h3>Write this down!</h3>
		<p><textarea rows="20" cols="100"><?php echo $export; ?></textarea></p>
<?php endif; ?>
<?php else: ?>
		<h3>You do not have any flows.</h3>
<?php endif; ?>
    </div>
</div>