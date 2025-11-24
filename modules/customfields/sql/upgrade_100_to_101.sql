-- CustomFields module upgrade 1.0.0 -> 1.0.1
-- Add helpful indexes to speed up common lookups

ALTER TABLE `{prefix}_customfields_data`
  ADD INDEX `idx_cf_data_target_item` (`target_module`, `item_id`),
  ADD INDEX `idx_cf_data_field` (`field_id`);

-- Note: Replace {prefix} with your XOOPS DB prefix during installation/upgrade.
