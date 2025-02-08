SET FOREIGN_KEY_CHECKS=0;


DROP TABLE IF EXISTS /*TABLE_PREFIX*/t_item_beta;
CREATE TABLE /*TABLE_PREFIX*/t_item_beta(
  fk_i_item_id INT(11) UNSIGNED NOT NULL UNIQUE,
  s_phone VARCHAR(100),
  i_condition VARCHAR(100),
  i_transaction VARCHAR(100),
  i_sold VARCHAR(100),

  PRIMARY KEY (fk_i_item_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

INSERT INTO /*TABLE_PREFIX*/t_item_beta (fk_i_item_id) SELECT pk_i_id FROM /*TABLE_PREFIX*/t_item;

DROP TABLE IF EXISTS /*TABLE_PREFIX*/t_item_stats_beta;
CREATE TABLE /*TABLE_PREFIX*/t_item_stats_beta(
  fk_i_item_id INT(11) UNSIGNED NOT NULL,
  i_num_phone_clicks INT(10) DEFAULT 0,
  dt_date DATE,

  PRIMARY KEY (fk_i_item_id, dt_date)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';



DROP TABLE IF EXISTS /*TABLE_PREFIX*/t_category_beta;
CREATE TABLE /*TABLE_PREFIX*/t_category_beta(
  fk_i_category_id INT(11) UNSIGNED NOT NULL UNIQUE,
  s_color VARCHAR(100),
  s_icon VARCHAR(100),

  PRIMARY KEY (fk_i_category_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

INSERT INTO /*TABLE_PREFIX*/t_category_beta (fk_i_category_id) SELECT pk_i_id FROM /*TABLE_PREFIX*/t_category;


ALTER TABLE /*TABLE_PREFIX*/t_item_beta ADD CONSTRAINT /*TABLE_PREFIX*/t_item_beta_ibfk_1 FOREIGN KEY (fk_i_item_id) REFERENCES /*TABLE_PREFIX*/t_item(pk_i_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE /*TABLE_PREFIX*/t_item_stats_beta ADD CONSTRAINT /*TABLE_PREFIX*/t_item_stats_beta_ibfk_1 FOREIGN KEY (fk_i_item_id) REFERENCES /*TABLE_PREFIX*/t_item(pk_i_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE /*TABLE_PREFIX*/t_category_beta ADD CONSTRAINT /*TABLE_PREFIX*/t_category_beta_ibfk_1 FOREIGN KEY (fk_i_category_id) REFERENCES /*TABLE_PREFIX*/t_category(pk_i_id) ON DELETE CASCADE ON UPDATE CASCADE;


SET FOREIGN_KEY_CHECKS=1;
