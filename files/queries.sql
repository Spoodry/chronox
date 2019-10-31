CREATE TABLE productos(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    modelo VARCHAR(255) NOT NULL,
    idColor INT,
    descripcion VARCHAR(512) NOT NULL,
    caracteristicas VARCHAR(512)
);

ALTER TABLE productos
    ADD nombreImagen VARCHAR(32);

ALTER TABLE productos
    ADD cantImagenes INT;

ALTER TABLE productos
    ADD idTipoPublico INT;

ALTER TABLE productos
    ADD CONSTRAINT idTipoPublico_FK FOREIGN KEY(idTipoPublico) REFERENCES tipoPublico(id);

ALTER TABLE productos
    ADD CONSTRAINT idColor_FK FOREIGN KEY(idColor) REFERENCES colores(id);

CREATE TABLE colores(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(64) NOT NULL,
    RGB VARCHAR(16) NOT NULL,
);

INSERT INTO colores(nombre, RGB) VALUES('','');

INSERT INTO colores(nombre, RGB) VALUES('Azul','41,98,255');
INSERT INTO colores(nombre, RGB) VALUES('Plata','224,224,224');

CREATE TABLE tipoPublico(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(64) NOT NULL
);

INSERT INTO tipoPublico(nombre) VALUES('Hombres');
INSERT INTO tipoPublico(nombre) VALUES('Mujeres');
INSERT INTO tipoPublico(nombre) VALUES('Niños');

INSERT INTO productos(nombre, precio, modelo, idColor, descripcion, caracteristicas, idTipoPublico, nombreImagen, cantImagenes) VALUES('',0.00,'',0,'','',0,'',0);

INSERT INTO productos(nombre, precio, modelo, idColor, descripcion, caracteristicas, idTipoPublico, nombreImagen, cantImagenes) VALUES('Reloj Casio MTP-1183A-2A-Plateado',769.00,'MTP-1183A-2A',2,NULL,'Análogo;Movimiento de Cuarzo;Ventana fechadora;Pila durable hasta 7 años;Resistencia al agua de 30M;Diseño original hace más de 20 años',1,'CS-A-5',3);

CREATE TABLE marcas(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(64) NOT NULL
);

INSERT INTO marcas(nombre) VALUES('');
INSERT INTO marcas(nombre) VALUES('Casio');
INSERT INTO marcas(nombre) VALUES('Rolex');
INSERT INTO marcas(nombre) VALUES('Seiko');

ALTER TABLE productos
    ADD idMarca INT NOT NULL AFTER precio;

UPDATE productos SET idMarca = 1 WHERE id = 1;

CREATE PROCEDURE p_obtenerProductos(
    
)
SELECT p.id, p.nombre as producto, m.nombre as marca, p.precio, p.nombreImagen, p.cantImagenes FROM productos as p inner join marcas as m ON p.idMarca = m.id;

CREATE PROCEDURE p_cantProductos(

)
SELECT COUNT(*) FROM productos;

CREATE PROCEDURE p_obtenerProductosXMarca(
    p_idMarca INT    
)
SELECT p.id, p.nombre as producto, m.nombre as marca, p.precio, p.nombreImagen, p.cantImagenes FROM productos as p inner join marcas as m ON p.idMarca = m.id WHERE p.idMarca = p_idMarca;

CREATE PROCEDURE p_cantProductosXMarca(
    p_idMarca INT
)
SELECT COUNT(*) as cantProductos FROM productos WHERE idMarca = p_idMarca;