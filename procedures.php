<?php 

trait Procedure {

	function makeProcedures(){
		
		$sql = 'CREATE PROCEDURE insertcat (
				IN cat_name VARCHAR(255), IN pcat_id INT(255)
			)
			insert into category(cat_name, pcat_id) VALUES(cat_name, pcat_id)';
		$this->executeQuery($sql);

		$sql = 'CREATE PROCEDURE insertproduct (
				IN pro_name VARCHAR(100), IN pro_desc TEXT, IN pro_imgs TEXT, cat_id INT, sub_cate_id INT
			)
			insert into products(pro_name, pro_desc, pro_imgs, cat_id, sub_cate_id) VALUES(pro_name, pro_desc, pro_imgs, cat_id, sub_cate_id)';
		$this->executeQuery($sql);

		$sql = 'CREATE PROCEDURE get_cat ()
		select * from category';
		$this->executeQuery($sql);

		$sql = 'CREATE PROCEDURE get_products ()
		select * from products';
		$this->executeQuery($sql);

		$sql = 'CREATE PROCEDURE delete_cat (
			IN cat_id INT(10)
		)
		delete from category where id = cat_id';
		$this->executeQuery($sql);

		$sql = 'CREATE PROCEDURE delete_products (
			IN pro_id INT(10)
		)
		delete from products where id = pro_id';
		$this->executeQuery($sql);

	}
}

?>