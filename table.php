<?php

// Extended class Main
trait Table {

	function makeTables() {

		// Category Table
		$sql = 'CREATE TABLE IF NOT EXISTS `andesoft`.`category` (
			`id` INT NOT NULL AUTO_INCREMENT,
			`cat_name` VARCHAR(255) NOT NULL,
			`pcat_id` INT NULL,
			PRIMARY KEY (`id`)
		) ENGINE = InnoDB;';
		$this->executeQuery($sql);

		// Product Table
		$sql = 'CREATE TABLE IF NOT EXISTS `andesoft`.`products` (
			`id` INT NOT NULL AUTO_INCREMENT,
			`pro_name` VARCHAR(100) NOT NULL,
			`pro_desc` TEXT NOT NULL,
			`pro_imgs` TEXT NOT NULL,
			`cat_id` INT NOT NULL,
			`sub_cate_id` INT NOT NULL,
			PRIMARY KEY (`id`)
		) ENGINE = InnoDB;';
		$this->executeQuery($sql);

	}

}

?>