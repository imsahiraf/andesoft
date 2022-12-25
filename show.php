<?php

class show extends Main {

	public static function show(){
		global $globals, $main;

		if(__req('filter')){

			$cat_id = __req('cat_id');
			$sub_cate_id = __req('sub_cate_id');
			$pro_name = __req('pro_name');

			$where = [];

			if(!empty($cat_id)){
				$where[] = 'cat_id = '. $cat_id;
			}

			if(!empty($sub_cate_id)){
				$where[] = 'sub_cate_id = '. $sub_cate_id;
			}

			if(!empty($pro_name)){
				$where[] = 'pro_name LIKE "%'. $pro_name.'%"';
			}

			if(!empty($where)){

				$where = implode(' AND ', $where);
				$sql = 'SELECT * FROM products WHERE '.$where;
				
			}else{
				$sql = 'CALL get_products()';
			}

			$res = $main->executeQuery($sql);

			if(!empty($res)){
				while($row = mysqli_fetch_assoc($res)){
					$ret[$row['id']] = $row;
				}
			}
			$globals['msg']['filtered'] = $ret;

			return true;
		}

		if(__req('delete')){

			$pro_id = __req('id');
			
			$sql = 'CALL delete_products('.$pro_id.')';

			if($main->executeQuery($sql)){
				$globals['msg']['success'] = 'Data deleted Successfully';
			}else{
				$globals['msg']['error'] = 'Unable to delete the data';
			}

			return true;

		}

		if(__req('pro_name')){

			$pro_name = __req('pro_name');
			$pcat_id = __req('pcat_id');
			$sub_cat = __req('sub_cat');
			$pro_desc = __req('pro_desc');
			$allowTypes = array('jpg','png','jpeg','gif');
			
			$fileNames = array_filter($_FILES['files']['name']); 
			// print_r($fileNames);
			$list = array();
			if(!empty($fileNames)){ 
				foreach($_FILES['files']['name'] as $key=>$val){ 
					$fileName = basename($_FILES['files']['name'][$key]); 
					$targetFilePath = "images/product/" . $fileName;
					if(move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)){ 
						$list[] = $fileName; 
					}
				} 
				
			}

			$list = addslashes(json_encode($list));
			
			$sql = 'CALL insertproduct("'.$pro_name.'", "'.$pro_desc.'", "'.$list.'", "'.$pcat_id.'", "'.$sub_cat.'")';

			if($main->executeQuery($sql)){
				$globals['success'] = 'Product Added Successfully';
				unset($globals['var']);
			}else{
				echo '<script>alert("Unable to add products")</script>';
			}

		}

		$prosql = 'CALL get_products()';
		// $prores = mysqli_query($main->con, $prosql);
		$prores = $main->executeQuery($prosql);

		if(!empty($prores)){
			while($prorow = mysqli_fetch_assoc($prores)){
				$proret[$prorow['id']] = $prorow;
			}
		}
		mysqli_next_result($main->con);
		// $main->next_result();
		$globals['pro_list'] = $proret;

		$catsql = 'CALL get_cat()';
		$catres = $main->executeQuery($catsql);

		if(!empty($catres)){
			while($row = mysqli_fetch_assoc($catres)){
				$ret[$row['id']] = $row;
			}
		}
		$globals['cat_list'] = $ret;
		
	}

	public static function show_theme(){
		global $globals;
		__header();
		echo '
		<div class="container">
			<div class="fluid pt-4 pb-4">
				<a href="?act=category"><button class="btn btn-primary">Category</button></a>
			</div>
			<div class="fluid pt-4 pb-4">
				<form method="POST" action="?act=show" id="cat_form" enctype="multipart/form-data">
					';
					if(!empty($globals['success'])){
						echo '<label class="bg-success pb-2">'.$globals['success'].'</label>';
					}
					echo '
					<div class="form-group pb-2">
						<label>Product Name</label>
						<input type="text" class="form-control" name="pro_name" value="'.(!empty($globals['var']['pro_name']) ? $globals['var']['pro_name'] : '').'" id="InputCatname" placeholder="Enter product name">
					</div>
					<div class="form-group pb-2">
						<label for="InputPcat">Select Parent Category</label>
						<select name="pcat_id" id="pcat_id" onchange="cat_to_subcat()" class="form-select">
							<option value="0">Select Parent Category</option>
							';
							foreach($globals['cat_list'] as $k => $v){
								if(empty($v['pcat_id'])){
									echo '<option value="'.$k.'">'.$v['cat_name'].'</option>';
								}
							}
							echo'
						</select>
					</div>
					<div class="form-group pb-2">
						<label for="InputPcat">Select Sub Category</label>
						<select name="sub_cat" id="sub_cat" class="form-select">
							<option value="0">Select Sub Category</option>
							';
							foreach($globals['cat_list'] as $k => $v){
								if(!empty($v['pcat_id'])){
									echo '<option value="'.$k.'">'.$v['cat_name'].'</option>';
								}
							}
							echo'
						</select>
					</div>
					<div class="form-group pb-2">
						<label>Images</label>
						<input type="file" name="files[]" class="form-control" multiple>
					</div>
					<div class="form-group pb-2">
						<label>Product Description</label>
						<textarea class="form-control" name="pro_desc" placeholder="Enter product description"></textarea>
					</div>
					<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
			
			<div class="pt-4 pb-4">
				<div class="row">
					<div class="col-md-4">
						<input class="form-control" id="sname" onkeyup="filterBy()" placeholder="Search by Name">
					</div>
					<div class="col-md-4">
						<select id="scat" onchange="filterBy()" class="form-select">
							<option value="0">Select Parent Category</option>
							';
							foreach($globals['cat_list'] as $k => $v){
								if(empty($v['pcat_id'])){
									echo '<option value="'.$k.'">'.$v['cat_name'].'</option>';
								}
							}
							echo'
						</select>
					</div>
					<div class="col-md-4">
						<select id="ssub_cat" onchange="filterBy()" class="form-select">
							<option value="0">Select Sub Category</option>
							';
							foreach($globals['cat_list'] as $k => $v){
								if(!empty($v['pcat_id'])){
									echo '<option value="'.$k.'">'.$v['cat_name'].'</option>';
								}
							}
							echo'
						</select>
					</div>
				</div>
			</div>
			<div class="pt-4 pb-4">
				<table class="table table-striped">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col" class="text-center">Product Name</th>
							<th scope="col" class="text-center">Product Images</th>
							<th scope="col" class="text-center">Category Name</th>
							<th scope="col" class="text-center">Subcategory Name</th>
							<th scope="col">Action</th>
						</tr>
					</thead>
					<tbody id="tbody">';
					$i = 0;
					foreach($globals['pro_list'] as $k => $v){
						$i++;
						echo '<tr>
						<th scope="row">'.$i.'</th>
						<td class="text-center">'.$v['pro_name'].'</td>
						<td class="text-center">
						';
						$img_list = json_decode($v['pro_imgs'], true);
						foreach($img_list as $img){
							echo '<img src="images/product/'.$img.'" height="50" width="50">';
						}
						echo '
						</td>
						<td class="text-center">'.(!empty($v['cat_id']) ? $globals['cat_list'][$v['cat_id']]['cat_name'] : '').'</td>
						<td class="text-center">'.(!empty($v['sub_cate_id']) ? $globals['cat_list'][$v['sub_cate_id']]['cat_name'] : '').'</td>
						<td><a href="javascript:delete_pro(\''.$v['id'].'\')">Delete</a></td>
					</tr>';
					}
						echo '
					</tbody>
				</table>
			</div>
			
		</div>
		<script>
			var cat_list = \''.json_encode($globals['cat_list']).'\';
			cat_list = JSON.parse(cat_list);
			
			function cat_to_subcat(){
				let html = "<option value=\"0\">Select Sub Category</option>";
				var cat = $("#pcat_id").val();
				$.each( cat_list, function( key, value ) {
					if(value.pcat_id == cat){
						html += `<option value="${key}">${value.cat_name}</option>`;
					}
				})
				$("#sub_cat").html(html);
			}

			function delete_pro(id){
				$.get( "?act=show&api=1&delete=1&id="+id, function( data ) {
					var parseData = JSON.parse(data);
					if(parseData.success){
						alert(parseData.success);
						document.location = "?act=show";
					}else{
						alert(parseData.error);
					}
				});
			}

			function filterBy(){
				let cat = $("#scat").val();
				let subcat = $("#ssub_cat").val();
				let name = $("#sname").val();
				$.get( "?act=show&api=1&filter=1&cat_id="+cat+"&sub_cate_id="+subcat+"&pro_name="+name, function( data ) {
					var parseData = JSON.parse(data);
					let html = "";
					let i = 0;
					$.each( parseData.filtered, function( key, value ) {
						i++;
						let cname = (value.cat_id != 0) ? cat_list[value.cat_id]["cat_name"] : "";
						let scname = (value.sub_cate_id != 0) ? cat_list[value.sub_cate_id]["cat_name"] : "";
						html += `<tr>
						<th scope="row">${i}</th>
						<td class="text-center">${value.pro_name}</td>
						<td class="text-center">`;
						let img_list = JSON.parse(value.pro_imgs);
						$.each( img_list, function( k, v ) {
							html += ` <img src="images/product/${v}" height="50" width="50">`
						})
						html += `
						</td>
						<td class="text-center">${cname}</td>
						<td class="text-center">${scname}</td>
						<td><a href="javascript:delete_pro(\''.$v['id'].'\')">Delete</a></td>
					</tr>`;
					})
					$("#tbody").html(html);
				});
			}

		</script>
		';
		__footer();
	}

	public static function show_api(){
		global $globals;
		return $globals['msg'];
	}
}

?>