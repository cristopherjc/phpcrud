
use crud_db;

-- Script para la creación de la base de datos del sistema
CREATE TABLE bodegas (
	id int AUTO_INCREMENT PRIMARY KEY,
    cod_bodega varchar(10) NOT NULL UNIQUE,
    ciudad varchar(100) NOT NULL,
    direccion varchar(100) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=INNODB;

CREATE TABLE usuarios (
    id int AUTO_INCREMENT PRIMARY KEY,
    cedula varchar(10) NOT NULL UNIQUE,
    alias varchar(20) NOT NULL UNIQUE,
    nombres varchar(100) NOT NULL,
    apellidos varchar(100) NOT NULL,
    correo varchar(100) NOT NULL UNIQUE,
    clave varchar(255) NOT NULL,
    rol ENUM('sysadmin', 'admin_bodega', 'empleado')
    	NOT NULL DEFAULT 'empleado',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bodega_id) REFERENCES bodegas(id) ON DELETE SET NULL
) ENGINE=INNODB;

CREATE TABLE proveedores (
	id int AUTO_INCREMENT PRIMARY KEY,
    ruc varchar(13) NOT NULL UNIQUE,
    nombre varchar(100) NOT NULL,
    telefono varchar(15) NOT NULL,
    ciudad varchar(100) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=INNODB;

CREATE TABLE categorias (
	id int AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(100) NOT NULL UNIQUE,
    descripcion varchar(255)
) ENGINE=INNODB;

CREATE TABLE productos (
	id int AUTO_INCREMENT PRIMARY KEY,
    id_bodega int NOT NULL,
    id_proveedor int,
    id_categoria int,
    nombre varchar(100) NOT NULL,
    descripcion text,
    precio_compra decimal(10,2),
    precio_venta decimal(10,2),
    stock int NOT NULL  DEFAULT 0,
    stock_minimo int NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_bodega) REFERENCES bodegas(id) ON DELETE CASCADE,
    FOREIGN KEY (id_proveedor) REFERENCES proveedores(id) ON DELETE SET NULL,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id) ON DELETE SET NULL
) ENGINE=INNODB;

CREATE TABLE movimientos_log (
	id int AUTO_INCREMENT PRIMARY KEY,
    id_producto int NOT NULL,
    id_usuario int,
    id_bodega int NOT NULL,
    tipo ENUM('entrada', 'salida') NOT NULL,
    cantidad int NOT NULL,
    comentario varchar(255),
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_producto) REFERENCES productos(id) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE SET NULL,
    FOREIGN KEY (id_bodega) REFERENCES bodegas(id) ON DELETE CASCADE
) ENGINE=INNODB;
