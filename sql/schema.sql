CREATE DATABASE misposts;
USE misposts;

CREATE TABLE archivos (
  idarchivo INT AUTO_INCREMENT PRIMARY KEY,
  nombre_original VARCHAR(255) NOT NULL,
  nombre_guardado VARCHAR(255) NOT NULL,
  ruta VARCHAR(255) NOT NULL,
  fecha_subida DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  usuario_id INT NOT NULL
);

CREATE TABLE comments (
  idcomment INT AUTO_INCREMENT PRIMARY KEY,
  description TEXT NOT NULL,
  fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  idpost INT NOT NULL,
  usuario_id INT NOT NULL
);

CREATE TABLE posts (
  idpost INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  usuario_id INT NOT NULL,
  archivo VARCHAR(255)
);

CREATE TABLE usuario (
  idusuario INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL,
  name VARCHAR(255) NOT NULL,
  habilitado BIT(1) NOT NULL,
  password VARCHAR(255) NOT NULL
);

INSERT INTO usuario (idusuario,email,name,habilitado,password) VALUES
  (1, 'holi@mispostsgrupo5.com','holi', b'1', '$2y$10$p8bMQrGcYwrODWvG8G89P.Q6aLrD1Ku1XI44gGIsnTRO/ccBAtDre'),
  (2, 'a@gmail.com','a', b'1', '$2y$10$Ui4ADbxF/k4zsrlHJc6sXufpRZLSnmqlBpXcfvDEfugZAVb4WcV4m');

INSERT INTO posts (idpost,title,description,fecha,usuario_id) VALUES
  (1, 'Primer post', 'Contenido del primer post', '2025-04-20 08:58:07', 1),
  (2, 'Segundo post', 'Contenido del segundo post', '2025-04-20 08:58:07', 1);

INSERT INTO comments (idcomment,description,fecha,idpost,usuario_id) VALUES
  (1, 'Este es un comentario de prueba', '2025-04-20 08:58:07', 1, 1),
  (2, 'Otro comentario de prueba', '2025-04-20 08:58:07', 2, 1),
  (6, 'h', '2025-04-20 09:06:57', 1, 2),
  (10,'xfgdjkhgfkh', '2025-04-20 10:24:34', 1, 1);