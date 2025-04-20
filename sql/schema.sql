CREATE DATABASE IF NOT EXISTS misposts;
USE misposts;

CREATE TABLE usuario (
    idusuario INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    habilitado BIT NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE posts (
    idpost INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, -- Fecha automática
    usuario_id INT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuario(idusuario)
);

CREATE TABLE comments (
    idcomment INT AUTO_INCREMENT PRIMARY KEY,
    description TEXT NOT NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, -- Fecha automática
    idpost INT NOT NULL,
    usuario_id INT NOT NULL, -- Relación con el usuario
    FOREIGN KEY (idpost) REFERENCES posts(idpost),
    FOREIGN KEY (usuario_id) REFERENCES usuario(idusuario)
);

INSERT INTO posts (title, description, usuario_id) 
VALUES ('Primer post', 'Contenido del primer post', 1);

INSERT INTO posts (title, description, usuario_id) 
VALUES ('Segundo post', 'Contenido del segundo post', 1);

-- Crear algunos comentarios de ejemplo
INSERT INTO comments (description, idpost, usuario_id) 
VALUES ('Este es un comentario de prueba', 1, 1);

INSERT INTO comments (description, idpost, usuario_id) 
VALUES ('Otro comentario de prueba', 2, 1);