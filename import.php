<?php
	if(isset($_POST['name'])){
		$name=htmlentities($_POST['name']);
		if(($file=$_FILES['file']['tmp_name'])&&is_uploaded_file($file))
			$import=file_get_contents($file);
		else
			$import=$_POST['data'];
		$parts=explode(':',$import);
		$data = base64_decode($parts[1]);
		$sms_data = base64_decode($parts[2]);
		if(trim($parts[0])==md5($data.$sms_data)){
			$user = OpenVBX::getCurrentUser();
			$user_id = $user->values['id'];
			$tenant_id = $user->values['tenant_id'];
			$ci =& get_instance();
			$ci->db->insert('flows', array(
				'name' => $name,
				'user_id' => $user_id,
				'data' => $data,
				'sms_data' => $sms_data,
				'tenant_id' => $tenant_id
			));
		}
		else
			$error = true;
	}
?>
<style>
	.vbx-import-flow form{
		margin-top:20px;
	}
	.vbx-import-flow p{
		margin:10px 0;
		padding:0 20px;
	}
</style>
<div class="vbx-content-main">
	<div class="vbx-content-menu vbx-content-menu-top">
		<h2 class="vbx-content-heading">Import Flow</h2>
	</div><!-- .vbx-content-menu -->
    <div class="vbx-table-section vbx-import-flow">
		<form method="post" action="" enctype="multipart/form-data">
			<p><label>Flow name<br/><input type="text" name="name" /></label></p>
			<p><label>File<br/><input type="file" name="file" /></label></p>
			<p>or</p>
			<p><label>Paste<br/><textarea rows="20" cols="100" name="data"></textarea></label></p>
			<p><input type="submit" value="Import" /></p>
		</form>
<?php if($error): ?>
		<p>Invalid import.</p>
<?php endif; ?>
    </div>

</div>