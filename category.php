<?php

class category extends Main {

	public static function category(){
		global $globals, $main;

		if(__req('delete')){

			$globals['cat_id'] = __req('id');
			$sql = 'CALL delete_cat('.$globals['cat_id'].')';

			if($main->executeQuery($sql)){
				$globals['msg']['success'] = 'Data deleted Successfully';
			}else{
				$globals['msg']['error'] = 'Unable to deete the data';
			}

		}

		if(__req('cat_name')){
			$catname = __req('cat_name');
			$pcatid = __req('pcat_id');

			$sql = 'CALL insertcat("'.$catname.'", "'.$pcatid.'")';

			if($main->executeQuery($sql)){
				$globals['success'] = 'Category Added Successfully';
				unset($globals['var']);
			}else{
				echo '<script>alert("Unable to add category")</script>';
			}
		}

		$sql = 'CALL get_cat()';
		$res = $main->executeQuery($sql);

		if(!empty($res)){
			while($row = mysqli_fetch_assoc($res)){
				$ret[$row['id']] = $row;
			}
		}
		$globals['cat_list'] = $ret;

	}

	public static function category_theme(){
		global $globals;
		__header();
		echo '
		<div class="container">
			<div class="fluid pt-4 pb-4">
				<a href="?act=show"><button class="btn btn-primary">Products</button></a>
			</div>
			<div class="fluid pt-4 pb-4">
				<form method="POST" action="?act=category" id="cat_form">
					';
					if(!empty($globals['success'])){
						echo '<label class="bg-success pb-2">'.$globals['success'].'</label>';
					}
					echo '
					<div class="form-group pb-2">
						<label for="InputCatname">Category Name</label>
						<input type="text" class="form-control" name="cat_name" value="'.(!empty($globals['catname']) ? $globals['catname'] : '').'" id="InputCatname" placeholder="Enter category name">
					</div>
					<div class="form-group pb-2">
						<label for="InputPcat">Select Parent Category</label>
						<select name="pcat_id" class="form-select">
							<option value="0">Select Parent Category</option>
							';
							foreach($globals['cat_list'] as $k => $v){
								// If in case we require parent category only then do this or directly echo
								if(empty($v['pcat_id'])){
									echo '<option value="'.$k.'">'.$v['cat_name'].'</option>';
								}
							}
							echo'
						</select>
					</div>
					<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
			
			<div class="pt-4 pb-4">
				<table class="table table-striped">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col" class="text-center">Category Name</th>
							<th scope="col" class="text-center">Category Parent Name</th>
							<th scope="col">Action</th>
						</tr>
					</thead>
					<tbody>';
					$i = 0;
					foreach($globals['cat_list'] as $k => $v){
						$i++;
						echo '<tr>
						<th scope="row">'.$i.'</th>
						<td class="text-center">'.$v['cat_name'].'</td>
						<td class="text-center">'.(!empty($v['pcat_id']) ? $globals['cat_list'][$v['pcat_id']]['cat_name'] : '').'</td>
						<td><a href="javascript:delete_cat(\''.$v['id'].'\')">Delete</a></td>
					</tr>';
					}
						echo '
					</tbody>
				</table>
			</div>
			
		</div>
		<script>
			function delete_cat(id){
				$.get( "?act=category&api=1&delete=1&id="+id, function( data ) {
					var parseData = JSON.parse(data);
					if(parseData.success){
						alert(parseData.success);
						document.location = "?act=category";
					}else{
						alert(parseData.error);
					}
				});
			}
		</script>
		';
		__footer();

	}

	public static function category_api(){
		global $globals;
		return $globals['msg'];
	}
}

?>