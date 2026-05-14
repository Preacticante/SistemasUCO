DROP DATABASE IF EXISTS Dias_descanso;
CREATE DATABASE Dias_descanso;
USE Dias_descanso;

CREATE TABLE usuario(
    nombre_completo VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL PRIMARY KEY,
    contrasena VARCHAR(255) NOT NULL
);

CREATE TABLE puestos(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);sys_config

INSERT INTO puestos (nombre) VALUES 
('Intendente'),                 -- ID 1
('Ejecutivo de Mantenimiento'), -- ID 2
('Ejecutivo de Seguridad'),     -- ID 3
('Auxiliar de Mantenimiento'),  -- ID 4
('Ayudante General'),           -- ID 5
('Guardia de Seguridad');       -- ID 6

CREATE TABLE empleados(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(100),
    fecha_ingreso DATE NOT NULL,
    puesto_id INT,
	FOREIGN KEY (puesto_id) REFERENCES puestos(id)
);


CREATE TABLE registros_descanso(
    id INT AUTO_INCREMENT PRIMARY KEY,
    empleado_id INT,
    anio_calendario INT,
    mes INT,
    dias_tomados INT,
    FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE CASCADE
);

CREATE TABLE ley_vacaciones(
    anios_antiguedad INT PRIMARY KEY,
    dias_derecho INT
);

INSERT INTO ley_vacaciones (anios_antiguedad, dias_derecho) VALUES 
(1, 12), (2, 14), (3, 16), (4, 18), (5, 20),
(6, 22), (7, 22), (8, 22), (9, 22), (10, 22),
(11, 24), (12, 24), (13, 24), (14, 24), (15, 24),
(16, 26), (17, 26), (18, 26), (19, 26), (20, 26),
(21, 28), (22, 28), (23, 28), (24, 28), (25, 28),
(26, 30), (27, 30), (28, 30), (29, 30), (30, 30),
(31, 32), (32, 32), (33, 32), (34, 32), (35, 32);

INSERT INTO empleados (id, nombre_completo, fecha_ingreso, puesto_id) VALUES 
(1, 'Fabián Ramos Juan José', '1999-08-09', 1),
(2, 'Matehuala Vargas Roberto Carlos', '2000-04-06', 2), 
(3, 'Arreola Morales José', '2003-02-15', 4),
(4, 'Ramírez García Yolanda', '2008-04-16', 1),
(5, 'Perez Reséndiz Sergio Antonio', '2010-04-05', 5),
(6, 'Dominguez Morales Juan', '2010-10-06', 6),
(7, 'Zamora Gutiérrez Juan Alberto', '2020-07-08', 1),
(8, 'Bocanegra Lucio Martín', '2022-01-07', 6),
(9, 'Jiménez Ruiz Alberto', '2023-05-04', 1),
(10, 'Ruíz Ramírez Ma Guadalupe', '2023-10-10', 1),
(11, 'Cruz Lugo Angélica María', '2023-10-23', 1),
(12, 'Aranda Gutiérrez Rolando', '2024-07-02', 6),
(13, 'Hernández Tamayo Miguel Ángel', '2024-10-23', 6),
(14, 'Morales Medrano Gerardo', '2025-02-26', 1),
(15, 'Rivera Sánchez Mariano', '2025-06-04', 6),
(16, 'Peña Aldape Alfredo Ismael', '2025-06-26', 6),
(17, 'Olguín Ruiz María Concepción', '2026-03-25', 1);