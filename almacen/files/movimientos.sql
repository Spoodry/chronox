CREATE TABLE movimientosEquipos(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fecha DATETIME NOT NULL,
    idEquipo INT NOT NULL,
    idTipoMovimiento INT NOT NULL
);

DELIMITER $$
CREATE TRIGGER movInsertEquipo AFTER INSERT ON equipos FOR EACH ROW BEGIN
    SET @fecha = (SELECT NOW());
    INSERT INTO movimientosEquipos(fecha, idEquipo, idTipoMovimiento, Serie) VALUES(@fecha, NEW.id, 1, NEW.Serie);
END $$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER movDeleteEquipo BEFORE DELETE ON equipos FOR EACH ROW BEGIN
    SET @fecha = (SELECT NOW());
    INSERT INTO movimientosEquipos(fecha, idEquipo, idTipoMovimiento, Serie) values(@fecha, OLD.id, 2, OLD.Serie);
END $$
DELIMITER ;


CREATE TABLE tiposMovimientos(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(64) NOT NULL
);
INSERT INTO tiposMovimientos(nombre) VALUES('alta');            --id = 1
INSERT INTO tiposMovimientos(nombre) VALUES('baja');            --id = 2
INSERT INTO tiposMovimientos(nombre) VALUES('actualizar');      --id = 3

INSERT INTO equipo(Serie,Marca, Modelo, Tipo, Asignacion, Economico) VALUES('SMGK4JH134', 'Samsung', 'Grand Prime+',0017,'P014','2020-1');

ALTER TABLE movimientosEquipos
    ADD Serie VARCHAR(64);

ALTER TABLE equipo
    ADD estatus SMALLINT NOT NULL DEFAULT 1;

DELIMITER $$
CREATE PROCEDURE proc_eliminarEquipo(
    p_id INT
)
BEGIN
    UPDATE equipos SET estatus = 0, Asignacion = "P000" WHERE id = p_id;
END $$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER movUpdateEquipo AFTER UPDATE ON equipos
 FOR EACH ROW BEGIN
    SET @fecha = (SELECT NOW());
    SET @estatus = (SELECT estatus FROM equipos WHERE id = OLD.id);
    IF @estatus = 1 THEN
        INSERT INTO movimientosEquipos(fecha, idEquipo, idTipoMovimiento, Serie) values(@fecha, OLD.id, 3, OLD.Serie);
    END IF;
END $$
DELIMITER ;

ALTER TABLE usuarios
    ADD id INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;

ALTER TABLE usuarios
    ADD usuario VARCHAR(64) NOT NULL;

ALTER TABLE usuarios
    ADD clave VARCHAR(32) NOT NULL;

CREATE PROCEDURE proc_login (
    p_usuario VARCHAR(64), 
    p_clave VARCHAR(32)
)
SELECT id, idUsuario, nomUsuario FROM usuarios WHERE usuario = p_usuario AND clave = (SELECT MD5(p_clave));

UPDATE usuarios SET clave = (SELECT MD5('juan123')) WHERE usuario = 'JuanPablo85'

UPDATE usuarios SET clave = (select MD5('Vegetta777')), usuario = 'Exxomylie' where id = 15;

---Cambiar el nombre de tabla equipo a equipos

ALTER TABLE movimientosEquipos
    ADD query VARCHAR(512);

DELIMITER $$
CREATE PROCEDURE proc_altaEquipo(
    p_Serie VARCHAR(10),
    p_Marca VARCHAR(30),
    p_Modelo VARCHAR(30),
    p_Tipo VARCHAR(30),
    p_Asignacion VARCHAR(4),
    p_Economico VARCHAR(7),
    p_Imagen VARCHAR(255)
)
BEGIN
    INSERT INTO equipos(Serie,Marca,Modelo,Tipo,Asignacion,Economico,Imagen) VALUES(p_Serie, p_Marca, p_Modelo, p_Tipo, p_Asignacion, p_Economico, p_Imagen);
    SELECT id FROM equipos ORDER BY id DESC LIMIT 1;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE proc_nuevoMovimientoEquipo(
    p_idEquipo INT, 
    p_idTipoMovimiento INT,
    p_query VARCHAR(512)
)
BEGIN
    SET @fecha = (SELECT NOW());
    SET @serie = (SELECT Serie FROM equipos WHERE id = p_idEquipo);
    INSERT INTO movimientosEquipos(fecha, idEquipo, idTipoMovimiento, Serie, query) VALUES(@fecha, p_idEquipo, p_idTipoMovimiento, @serie, p_query);
END $$
DELIMITER ;