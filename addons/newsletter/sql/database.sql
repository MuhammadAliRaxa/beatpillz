INSERT INTO `settings` (`key`, `value`)
SELECT 'newsletter', '{\"status\":0,\"popup_status\":1,\"footer_status\":1,\"register_new_users\":0,\"popup_image\":\"images\\/newsletter\\/newsletter.svg\",\"popup_reminder_time\":\"24\",\"api_key\":\"\",\"audience_id\":\""}'
WHERE NOT EXISTS (
    SELECT 1 FROM `settings` WHERE `key` = 'newsletter'
);
