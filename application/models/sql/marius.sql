- SELECT 
--         bt.id as batch_theme_id, 
--         -- last known build_duration
--         COALESCE(lbd.build_duration, 45) as build_duration, 
--         -- elapsed time since the batch was set to running
--         IF(  bt.build_status = 'running', TIME_TO_SEC(TIMEDIFF(bt.last_update, NOW())), 0 )  as elapsed_time
--     FROM ts__download_batches b 
--     INNER JOIN ts__download_batch_themes bt ON bt.batch_id = b.id 
--     LEFT JOIN (
--         SELECT 
--             b1.launcher_template_id, MAX(build_duration) as build_duration
--         FROM 
--             ts__download_batches b1
--         INNER JOIN ts__download_batch_themes t1 ON t1.batch_id = b1.id
--         INNER JOIN (
--             SELECT 
--                 launcher_template_id, MAX(t2.last_update) as last_update
--             FROM ts__download_batch_themes t2
--             INNER JOIN ts__download_batches b2 ON b2.id = t2.batch_id
--             WHERE t2.build_duration > 0 AND t2.build_status = 'completed'
--             GROUP BY launcher_template_id
--         ) lt ON lt.launcher_template_id = b1.launcher_template_id AND lt.last_update = t1.last_update
--         GROUP BY launcher_template_id
--     ) lbd ON lbd.launcher_template_id = b.launcher_template_id
--     INNER JOIN ts__download_batches b2 ON b2.test_only = b.test_only
--     INNER JOIN ts__download_batch_themes bt2 ON bt2.batch_id = b2.id
--     WHERE 
--         bt2.id = 1119 AND 
--         bt2.id >= bt.id AND
--         b.build_status != 'completed' AND 
--         bt.build_status NOT IN ('completed', 'errors')
--         AND b.archived = '0'
-- 
--     ORDER BY (bt.build_status = 'running') DESC, b.priority DESC, bt.id ASC;


-- SELECT COUNT(*) FROM (
--     SELECT builder_id FROM (
--         SELECT 
--             bt.builder_id, TIME_TO_SEC(TIMEDIFF(NOW(), bb.last_seen_online)) as last_seen_online
--         FROM 
--             ts__download_batch_themes bt
--         INNER JOIN ts__download_batches b ON b.id = bt.batch_id
--         INNER JOIN ts__download_batches b2 ON b2.test_only = b.test_only
--         INNER JOIN ts__download_batch_themes bt2 ON b2.id = bt2.batch_id
--         INNER JOIN ts__batch_builders bb ON bb.id = bt.builder_id
--         WHERE 
--             bt.builder_id IS NOT NULL AND
--             bt2.id = 1119
--         ORDER BY bt.last_update DESC
--         LIMIT 10
--     ) foo 
--     WHERE last_seen_online < 3600
--     GROUP BY builder_id
-- ) foo

SET @current_batch_build_eta=0;
SET @job_id=10686;

SELECT 
        getBatchThemeEta(tj.id) INTO @current_batch_build_eta
    FROM 
        ts__download_batch_themes tj
    INNER JOIN ts__theme_test_jobs bt ON bt.batch_id = tj.batch_id
    WHERE job_id = @job_id;

-- SELECT SUM(IF (remaining_time < 0, 15, remaining_time)) FROM (
    SELECT 
        j2.id as job_id, j2.test_id, 
        @current_batch_build_eta,
        IF (
            j2.status = 1, 
            COALESCE(avgd.duration, 300) - TIME_TO_SEC(TIMEDIFF(NOW(), COALESCE(j2.last_status_update, NOW()))), 
            COALESCE(avgd.duration, 300)
        ) as remaining_time, 
        SUM(IF (t3.autoplay_status <> 'offline', 1, 0)) as online_devices, 
        getBatchThemeEta(bt.id) as batch_build_eta
    FROM
        ts__autoplay_jobs j1
    INNER JOIN ts__test_devices t1 ON t1.id = j1.device_id
    INNER JOIN ts__test_devices t2 ON t2.serial_number = t1.serial_number
    -- get all jobs that will run on the same device type as the current job
    INNER JOIN ts__autoplay_jobs j2 ON j2.device_id = t2.id AND j2.status <> 2
    INNER JOIN ts__test_devices t3 ON t3.serial_number = t2.serial_number
    LEFT JOIN (
        -- here we select the avg duration for a test in the last 3 days for each test
        SELECT a.test_id, COALESCE(AVG(duration), 300) as duration FROM 
        ts__autoplay_jobs a INNER JOIN (
            SELECT 
                DATE_SUB(MAX(date_created), INTERVAL 3 DAY)  as last_date_created, test_id 
            FROM ts__autoplay_jobs
            WHERE status = 2
            GROUP BY test_id
        ) foo ON foo.test_id = a.test_id AND foo.last_date_created <= a.date_created
        WHERE duration IS NOT NULL AND duration > 0 AND status = 2
        GROUP BY test_id
    ) avgd ON avgd.test_id = j2.test_id
    LEFT JOIN ts__theme_test_jobs tj ON tj.job_id = j2.id
    LEFT JOIN ts__download_batch_themes bt ON bt.batch_id = tj.batch_id
    WHERE 
        j1.id = @job_id 
        AND (j2.status = 1 OR bt.id IS NOT NULL)
        GROUP BY j2.id
        HAVING batch_build_eta <= @current_batch_build_eta
-- ) foo;

