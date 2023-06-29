ALTER TABLE `users`
    ADD COLUMN `display_all_categories_flag` tinyint(4) DEFAULT 1 AFTER `remember_token`;

ALTER TABLE `users`
    ADD COLUMN `role` tinyint(4) DEFAULT 0 AFTER `remember_token`;

ALTER TABLE `users`
    ADD COLUMN `auto_play_flag` tinyint(4) DEFAULT 0 AFTER `display_all_categories_flag`;
