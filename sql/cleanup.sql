SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE comments;
TRUNCATE TABLE posts;
SET FOREIGN_KEY_CHECKS = 1;
SELECT 'Tablas limpiadas y auto-incrementos reiniciados correctamente.' AS 'Resultado';