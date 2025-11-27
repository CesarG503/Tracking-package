SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS logs_sistema;
DROP TABLE IF EXISTS historial_envios;
DROP TABLE IF EXISTS envios;
DROP TABLE IF EXISTS disponibilidad;
DROP TABLE IF EXISTS vehiculo_asignaciones;
DROP TABLE IF EXISTS vehiculos;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(30),
    rol ENUM('admin','repartidor') NOT NULL DEFAULT 'repartidor',
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    licencia VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;


CREATE INDEX idx_users_rol ON users(rol);

CREATE TABLE vehiculos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    marca VARCHAR(80),
    modelo VARCHAR(80),
    placa VARCHAR(30) UNIQUE NOT NULL,
    foto VARCHAR(400),       -- ruta 
    anio YEAR,
    capacidad VARCHAR(80),
    estado ENUM('disponible','asignado','mantenimiento','fuera_servicio') DEFAULT 'disponible',
    observaciones TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE INDEX idx_vehiculos_estado ON vehiculos(estado);


CREATE TABLE vehiculo_asignaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehiculo_id INT NOT NULL,
    repartidor_id INT NOT NULL,
    asignado_por INT,
    fecha_inicio DATETIME NOT NULL,
    fecha_fin DATETIME,
    estado ENUM('activo','finalizado','cancelado') DEFAULT 'activo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_vas_vehiculo FOREIGN KEY (vehiculo_id) REFERENCES vehiculos(id) ON DELETE CASCADE,
    CONSTRAINT fk_vas_repartidor FOREIGN KEY (repartidor_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_vas_asignado_por FOREIGN KEY (asignado_por) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE INDEX idx_vas_repartidor ON vehiculo_asignaciones(repartidor_id);
CREATE INDEX idx_vas_vehiculo ON vehiculo_asignaciones(vehiculo_id);
CREATE INDEX idx_vas_periodo ON vehiculo_asignaciones(fecha_inicio, fecha_fin);


CREATE TABLE disponibilidad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    repartidor_id INT NOT NULL,
    vehiculo_id INT NULL,
    fecha_inicio DATETIME NOT NULL,
    fecha_fin DATETIME NOT NULL,
    tipo ENUM('disponible','ocupado','vacaciones','bloqueo','asignado_envio') DEFAULT 'disponible',
    descripcion TEXT NULL,
    origen_id INT NULL, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_de_repartidor FOREIGN KEY (repartidor_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_de_vehiculo FOREIGN KEY (vehiculo_id) REFERENCES vehiculos(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE INDEX idx_de_rep ON disponibilidad(repartidor_id);
CREATE INDEX idx_de_veh ON disponibilidad(vehiculo_id);
CREATE INDEX idx_de_periodo ON disponibilidad(fecha_inicio, fecha_fin);
CREATE INDEX idx_de_tipo ON disponibilidad(tipo);

CREATE TABLE envios (
    id INT AUTO_INCREMENT PRIMARY KEY,

    remitente_nombre VARCHAR(120) NOT NULL,
    remitente_telefono VARCHAR(30),
    remitente_direccion TEXT NOT NULL,

    destinatario_nombre VARCHAR(120) NOT NULL,
    destinatario_telefono VARCHAR(30),
    destinatario_direccion TEXT NOT NULL,

   
    descripcion TEXT,
    foto_paquete VARCHAR(400),      -- ruta 
    peso DECIMAL(10,2),
    tipo_envio VARCHAR(80),

    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_estimada DATE,

    estado ENUM('pendiente','en_ruta','entregado','devuelto','cancelado') DEFAULT 'pendiente',

    
    vehiculo_asignacion_id INT NULL, 
    repartidor_id INT NULL,         
    disponibilidad_evento_id INT NULL, 

    -- datos de entrega (fusionados, lat/lng y dirección)
    lat DECIMAL(10,7) NULL,
    lng DECIMAL(10,7) NULL,
    direccion_entrega TEXT NULL,
    foto_entrega VARCHAR(400) NULL, -- ruta 

    observaciones TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_env_vas FOREIGN KEY (vehiculo_asignacion_id) REFERENCES vehiculo_asignaciones(id) ON DELETE SET NULL,
    CONSTRAINT fk_env_rep FOREIGN KEY (repartidor_id) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_env_disp FOREIGN KEY (disponibilidad_evento_id) REFERENCES disponibilidad(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE INDEX idx_env_estado ON envios(estado);
CREATE INDEX idx_env_rep ON envios(repartidor_id);
CREATE INDEX idx_env_vas ON envios(vehiculo_asignacion_id);



CREATE TABLE historial_envios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    envio_id INT NOT NULL,
    usuario_id INT,
    estado_anterior ENUM('pendiente','en_ruta','entregado','devuelto','cancelado'),
    estado_nuevo ENUM('pendiente','en_ruta','entregado','devuelto','cancelado') NOT NULL,
    comentario TEXT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    evidencia_ruta VARCHAR(400) NULL, -- ruta 
    CONSTRAINT fk_hist_envio FOREIGN KEY (envio_id) REFERENCES envios(id) ON DELETE CASCADE,
    CONSTRAINT fk_hist_user FOREIGN KEY (usuario_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE INDEX idx_hist_envio ON historial_envios(envio_id);
CREATE INDEX idx_hist_user ON historial_envios(usuario_id);



CREATE TABLE logs_sistema (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    accion VARCHAR(100),
    detalle JSON,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_logs_usuario FOREIGN KEY (usuario_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE INDEX idx_logs_user ON logs_sistema(usuario_id);


