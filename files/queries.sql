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

INSERT INTO productos(nombre, precio, idMarca, modelo, idColor, descripcion, caracteristicas, idTipoPublico, nombreImagen, cantImagenes) VALUES('',0.00, 0, '',0,'','',0,'',0);

INSERT INTO productos(nombre, precio, idMarca, modelo, idColor, descripcion, caracteristicas, idTipoPublico, nombreImagen, cantImagenes) VALUES('Reloj Casio MTP-1183A-2A-Plateado',769.00, 1, 'MTP-1183A-2A',2,NULL,'Análogo;Movimiento de Cuarzo;Ventana fechadora;Pila durable hasta 7 años;Resistencia al agua de 30M;Diseño original hace más de 20 años',1,'CS-A-5',3);

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

CREATE PROCEDURE p_obtenerProductosXTipoPublico(
    p_idTipoPublic INT    
)
SELECT p.id, p.nombre as producto, m.nombre as marca, tp.nombre as tipoPublico, p.precio, p.nombreImagen, p.cantImagenes , p.idTipoPublico FROM productos as p inner join tipoPublico as tp ON p.idTipoPublico = tp.id inner join marcas as m ON m.id = p.idMarca WHERE p.idTipoPublico = p_idTipoPublic;

CREATE PROCEDURE p_cantProductosXTipoPublico(
    p_idTipoPublic INT
)
SELECT COUNT(*) as cantProductos FROM productos WHERE idTipoPublico = p_idTipoPublic;

CREATE TABLE usuarios(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombreUsuario VARCHAR(128) NOT NULL,
    clave VARCHAR(64),
    esTemp SMALLINT
);

ALTER TABLE usuarios
    ADD idCarrito INT;

ALTER TABLE usuarios
ADD CONSTRAINT idCarrito_FK FOREIGN KEY(idCarrito) REFERENCES carrito(id);

CREATE PROCEDURE p_crearUsuarioTemp(
    p_nombreUsuario VARCHAR(128)
)
INSERT INTO carrito(total, pagado) VALUES(0.00, 0);
DECLARE @p_idCarrito INT;
SET @p_idCarrito = (SELECT id FROM carrito ORDER BY id DESC LIMIT 1);
INSERT INTO usuarios(nombreUsuario, esTemp, idCarrito) VALUES(p_nombreUsuario, 1, @p_idCarrito);
SELECT id FROM usuarios ORDER BY id DESC LIMIT 1;

CREATE TABLE carrito(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    total DECIMAL(10,2),
    pagado SMALLINT
);

CREATE TABLE productosEnCarrito(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    idProducto INT NOT NULL,
    idCarrito INT NOT NULL,
    CONSTRAINT idProducto_FK FOREIGN KEY(idProducto) REFERENCES productos(id),
    CONSTRAINT idCarrito_FK FOREIGN KEY(idCarrito) REFERENCES carrito(id)
);

CREATE PROCEDURE p_agregarACarrito(
    p_idProducto INT,
    p_idUsuario INT
)
BEGIN
    SET @p_idCarrito = (SELECT idCarrito FROM usuarios WHERE id = p_idUsuario);
    INSERT INTO productosEnCarrito(idProducto, idCarrito) VALUES(p_idProducto, @p_idCarrito);
    SELECT id FROM productosEnCarrito ORDER BY id DESC LIMIT 1;
END;