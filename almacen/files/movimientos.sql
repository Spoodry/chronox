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

ALTER TABLE movimientosEquipos
    ADD idUsuario INT NOT NULL AFTER id;

CREATE PROCEDURE proc_obtenerDatosEquipo(
    p_idEquipo INT
)
SELECT Serie, Marca, Modelo, te.NomEquipo AS Tipo, u.nomUsuario AS Asignacion, Economico, Imagen FROM equipos AS e
INNER JOIN tipoequipo AS te ON e.Tipo = te.IdTipo
LEFT JOIN usuarios AS u ON e.Asignacion = u.idUsuario WHERE e.id = p_idEquipo;

UPDATE usuarios SET clave = (select MD5('Laptop123')), usuario = 'SrJovan' where id = 25;

UPDATE usuarios SET nomUsuario = 'Juan Pablo Altamirano' WHERE id = 14;

ALTER TABLE aditamentos
    ADD id INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;

ALTER TABLE aditamentos
    ADD idAditamento VARCHAR(5) NOT NULL AFTER id;

ALTER TABLE aditamentos
    ADD idAsignacion INT NOT NULL AFTER idAditamento;

UPDATE aditamentos SET idAditamento = 'D0001', idAsignacion = 2 WHERE id = 1;
UPDATE aditamentos SET idAditamento = 'D0002', idAsignacion = 2 WHERE id = 2;
UPDATE aditamentos SET idAditamento = 'D0003', idAsignacion = 1 WHERE id = 3;
UPDATE aditamentos SET idAditamento = 'D0004', idAsignacion = 1 WHERE id = 4;
UPDATE aditamentos SET idAditamento = 'D0005', idAsignacion = 3 WHERE id = 5;
UPDATE aditamentos SET idAditamento = 'D0006', idAsignacion = 3 WHERE id = 6;
UPDATE aditamentos SET idAditamento = 'D0007', idAsignacion = 4 WHERE id = 7;
UPDATE aditamentos SET idAditamento = 'D0008', idAsignacion = 4 WHERE id = 8;
UPDATE aditamentos SET idAditamento = 'D0009', idAsignacion = 5 WHERE id = 9;
UPDATE aditamentos SET idAditamento = 'D0010', idAsignacion = 5 WHERE id = 10;
UPDATE aditamentos SET idAditamento = 'D0011', idAsignacion = 6 WHERE id = 11;
UPDATE aditamentos SET idAditamento = 'D0012', idAsignacion = 6 WHERE id = 12;
UPDATE aditamentos SET idAditamento = 'D0013', idAsignacion = 7 WHERE id = 13;
UPDATE aditamentos SET idAditamento = 'D0014', idAsignacion = 7 WHERE id = 14;
UPDATE aditamentos SET idAditamento = 'D0015', idAsignacion = 8 WHERE id = 15;
UPDATE aditamentos SET idAditamento = 'D0016', idAsignacion = 8 WHERE id = 16;

ALTER TABLE aditamentos
    DROP COLUMN Marca;

ALTER TABLE aditamentos
    DROP COLUMN Modelo;

ALTER TABLE aditamentos
    DROP COLUMN Asignación;

ALTER TABLE aditamentos
    DROP COLUMN Economico;

DELIMITER $$
CREATE PROCEDURE proc_agregarAditamento(
    p_idAsignacion INT,
    p_TipoAditamento VARCHAR(6),
    p_Tipo VARCHAR(30)
)
BEGIN
    SET @idNuevo = (SELECT MAX(id) FROM aditamentos) + 1;
    SET @idNuevo = IFNULL(@idNuevo,1);
    SET @idAditamento = CONCAT('D', (SELECT LPAD(@idNuevo, 4, '0')));
    INSERT INTO aditamentos(idAditamento, idAsignacion, TipoAditamento, Tipo) VALUES(@idAditamento, p_idAsignacion, p_TipoAditamento, p_Tipo);
    SELECT id FROM aditamentos ORDER BY id DESC LIMIT 1;
END $$
DELIMITER ;

ALTER TABLE movimientosEquipos
    ADD idAditamento INT AFTER idEquipo;

INSERT INTO tiposMovimientos(nombre) VALUES('alta-aditamento');

UPDATE tiposMovimientos
set nombre = 'alta-equipo' where id = 1;

UPDATE tiposMovimientos
set nombre = 'baja-equipo' where id = 2;

UPDATE tiposMovimientos
set nombre = 'actualizar-equipo' where id = 3;

CREATE PROCEDURE proc_obtenerHistorial(
    p_idEquipo INT
)
SELECT me.id, me.idUsuario, u.nomUsuario, fecha, idEquipo, ad.idAditamento, ad.TipoAditamento, ta.Aditamento, ad.Tipo AS descAditamento, idTipoMovimiento, tm.nombre AS tipoMovimiento, Serie FROM movimientosEquipos AS me
INNER JOIN tiposMovimientos AS tm ON me.idTipoMovimiento = tm.id 
INNER JOIN usuarios AS u ON me.idUsuario = u.id
LEFT JOIN aditamentos AS ad ON me.idAditamento = ad.id 
LEFT JOIN tipoaditamentos AS ta ON ad.TipoAditamento = ta.idAditamento WHERE idEquipo = p_idEquipo;

DELIMITER $$
CREATE PROCEDURE proc_nuevoMovimientoEquipo(
    p_idUsuario INT,
    p_idEquipo INT, 
    p_idAditamento INT,
    p_idTipoMovimiento INT,
    p_query VARCHAR(512)
)
BEGIN
    SET @fecha = (SELECT NOW());
    SET @serie = (SELECT Serie FROM equipos WHERE id = p_idEquipo);
    INSERT INTO movimientosEquipos(idUsuario, fecha, idEquipo, idAditamento, idTipoMovimiento, Serie, query) VALUES(p_idUsuario, @fecha, p_idEquipo, p_idAditamento, p_idTipoMovimiento, @serie, p_query);
END $$
DELIMITER ;

CREATE TABLE tiposUsuarios(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(64) NOT NULL
);

INSERT INTO tiposUsuarios(descripcion) VALUES('Super Admin');
INSERT INTO tiposUsuarios(descripcion) VALUES('Admin Inventario');
INSERT INTO tiposUsuarios(descripcion) VALUES('Operador');
INSERT INTO tiposUsuarios(descripcion) VALUES('Personal');

ALTER TABLE usuarios
    ADD idTipoUsuario INT NOT NULL AFTER idUsuario;

UPDATE usuarios SET idTipoUsuario = 4;

UPDATE usuarios SET idTipoUsuario = 1 WHERE id = 14;
UPDATE usuarios SET idTipoUsuario = 2 WHERE id = 15;
UPDATE usuarios SET idTipoUsuario = 3 WHERE id = 25;
UPDATE usuarios SET idTipoUsuario = 3 WHERE id = 30;

SELECT u.id, idUsuario, idTipoUsuario, tu.descripcion AS tipoUsuario, nomUsuario, usuario FROM usuarios AS u INNER JOIN tiposUsuarios AS tu ON u.idTipoUsuario = tu.id;

ALTER TABLE usuarios
    ADD correo VARCHAR(64) NOT NULL AFTER nomUsuario;

UPDATE usuarios SET correo = 'jan080599xd@gmail.com' WHERE id = 14;
UPDATE usuarios SET correo = 'edsonwtfnniga@gmail.com' WHERE id = 15;
UPDATE usuarios SET correo = 'jovankipp99@gmail.com' WHERE id = 25;

