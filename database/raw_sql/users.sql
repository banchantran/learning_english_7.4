ALTER TABLE `learning_english`.`users`
    ADD COLUMN `display_all_categories_flag` tinyint(4) DEFAULT 1 AFTER `remember_token`;

ALTER TABLE `learning_english`.`users`
    ADD COLUMN `role` tinyint(4) DEFAULT 0 AFTER `remember_token`;
