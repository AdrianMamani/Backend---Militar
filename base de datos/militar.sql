CREATE DATABASE IF NOT EXISTS db_militar;
USE db_militar;

-- Tabla: Usuario
CREATE TABLE Usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    rol VARCHAR(50) NOT NULL
) ENGINE=InnoDB;


-- Tabla: Contacto
CREATE TABLE Contacto (
    id_contacto INT AUTO_INCREMENT PRIMARY KEY,
    num_contacto VARCHAR(12),
    correo VARCHAR(60),
    lugar TEXT
) ENGINE=InnoDB;

-- Tabla: Promoción
CREATE TABLE Promocion (
    id_promocion INT AUTO_INCREMENT PRIMARY KEY,
    nombre_promocion VARCHAR(100),
    fecha_creacion DATE,
    resolucion_creacion VARCHAR(100),
    fecha_culminacion DATE,
    resolucion_culminacion VARCHAR(100),
    descripcion TEXT,
    mision TEXT,
    vision TEXT,
    resenia TEXT
) ENGINE=InnoDB;

-- Tabla: Especialidad
CREATE TABLE Especialidad (
    id_especialidad INT AUTO_INCREMENT PRIMARY KEY,
    nombre_especialidad VARCHAR(100),
    descripcion TEXT
) ENGINE=InnoDB;

-- Tabla: Logro
CREATE TABLE Logro (
    id_logro INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(50),
    descripcion TEXT
) ENGINE=InnoDB;

-- Tabla: Miembro
CREATE TABLE Miembro (
    id_miembro INT AUTO_INCREMENT PRIMARY KEY,
    id_promocion INT ,
    id_especialidad INT ,
    id_contacto INT,
    nombres VARCHAR(100),
    fecha_nac DATE,
    cargo VARCHAR(50),
    descripcion VARCHAR(50),
    estado VARCHAR(50),
    FOREIGN KEY (id_promocion) REFERENCES Promocion(id_promocion) ON DELETE SET NULL,
    FOREIGN KEY (id_especialidad) REFERENCES Especialidad(id_especialidad) ON DELETE SET NULL,
    FOREIGN KEY (id_contacto) REFERENCES Contacto(id_contacto) ON DELETE SET NULL
) ENGINE=InnoDB;



-- Tabla: Evento
CREATE TABLE Evento (
    id_evento INT AUTO_INCREMENT PRIMARY KEY,
    nombre_evento VARCHAR(100),
    lugar_evento VARCHAR(60),
    fecha_evento DATE,
    descripcion TEXT,
    confirmacion_asistencia BOOLEAN
) ENGINE=InnoDB;

CREATE TABLE Evento_Miembro (
    id_evento INT,
    id_miembro INT,
    PRIMARY KEY (id_evento, id_miembro),
    FOREIGN KEY (id_evento) REFERENCES Evento(id_evento) ON DELETE CASCADE,
    FOREIGN KEY (id_miembro) REFERENCES Miembro(id_miembro) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabla: Persona
CREATE TABLE Persona (
    id_personas INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(80),
    contacto VARCHAR(12)
) ENGINE=InnoDB;

CREATE TABLE Evento_Persona (
    id_evento INT,
    id_persona INT,
    PRIMARY KEY (id_evento, id_persona),
    FOREIGN KEY (id_evento) REFERENCES Evento(id_evento) ON DELETE CASCADE,
    FOREIGN KEY (id_persona) REFERENCES Persona(id_personas) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabla: Noticia
CREATE TABLE Noticia (
    id_noticia INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100),
    descripcion TEXT,
    fecha_publicacion DATE
) ENGINE=InnoDB;

-- Tabla: Noticia_Personas
CREATE TABLE Noticia_Persona (
    id_noticia INT,
    id_persona INT,
    PRIMARY KEY (id_noticia, id_persona),
    FOREIGN KEY (id_noticia) REFERENCES Noticia(id_noticia) ON DELETE CASCADE,
    FOREIGN KEY (id_persona) REFERENCES Persona(id_personas) ON DELETE CASCADE
) ENGINE=InnoDB;


-- Tabla: InMemoriam
CREATE TABLE InMemoriam (
    id_inmemoriam INT AUTO_INCREMENT PRIMARY KEY,
    id_miembro INT,
    fecha_fallecimiento DATE,
    descripcion TEXT,
    FOREIGN KEY (id_miembro) REFERENCES Miembro(id_miembro) ON DELETE CASCADE
) ENGINE=InnoDB;


-- Tabla: Galeria
CREATE TABLE Galeria (
    id_galeria INT AUTO_INCREMENT PRIMARY KEY,
    id_promocion INT DEFAULT NULL,
    id_miembro INT DEFAULT NULL,
    id_noticia INT DEFAULT NULL,
    id_evento INT DEFAULT NULL,
    tipo_archivo VARCHAR(50),
    ruta_archivo VARCHAR(255),
    informacion TEXT,
    FOREIGN KEY (id_promocion) REFERENCES Promocion(id_promocion) ON DELETE SET NULL,
    FOREIGN KEY (id_miembro) REFERENCES Miembro(id_miembro) ON DELETE SET NULL,
	FOREIGN KEY (id_noticia) REFERENCES Noticia(id_noticia) ON DELETE SET NULL,
    FOREIGN KEY (id_evento) REFERENCES Evento(id_evento) ON DELETE SET NULL
) ENGINE=InnoDB;



-- Tabla: Miembros_Logros 
CREATE TABLE Miembros_Logros (
    id_miembro INT NOT NULL,
    id_logro INT NOT NULL,
    id_galeria INT NOT NULL,
    fecha DATE NOT NULL,
    PRIMARY KEY (id_miembro, id_logro, id_galeria),
    FOREIGN KEY (id_miembro) REFERENCES Miembro(id_miembro) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_logro) REFERENCES Logro(id_logro) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_galeria) REFERENCES Galeria(id_galeria) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Tabla: Comentarios
CREATE TABLE Comentarios (
    id_comentario INT AUTO_INCREMENT PRIMARY KEY,
    id_galeria INT,
    descripcion VARCHAR(100),
    nombre_completo VARCHAR(80),
    FOREIGN KEY (id_galeria) REFERENCES Galeria(id_galeria) ON DELETE CASCADE
) ENGINE=InnoDB;


-- Tabla: Categoria
CREATE TABLE Categoria (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    descripcion TEXT
) ENGINE=InnoDB;

-- Tabla: Tesorero
CREATE TABLE Tesorero (
    id_tesorero INT AUTO_INCREMENT PRIMARY KEY,
    id_contacto INT,
    nombre_completo VARCHAR(50),
    FOREIGN KEY (id_contacto) REFERENCES Contacto(id_contacto) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabla: Asociado
CREATE TABLE Asociado (
    id_asociado INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(50),
    lugar VARCHAR(50),
    fecha_creacion DATE,
    fecha_modificacion DATE
) ENGINE=InnoDB;

-- Tabla: Aportacion
CREATE TABLE Aportacion (
    id_aportacion INT AUTO_INCREMENT PRIMARY KEY,
    id_categoria INT,
    id_tesorero INT,
    monto_ene DECIMAL(10, 2),
    monto_feb DECIMAL(10, 2),
    monto_mar DECIMAL(10, 2),
    monto_abr DECIMAL(10, 2),
    monto_may DECIMAL(10, 2),
    monto_jun DECIMAL(10, 2),
    monto_jul DECIMAL(10, 2),
    monto_ago DECIMAL(10, 2),
    monto_sep DECIMAL(10, 2),
    monto_oct DECIMAL(10, 2),
    monto_nov DECIMAL(10, 2),
    monto_dic DECIMAL(10, 2),
    total DECIMAL(10, 2),
    fecha_creacion DATE,
    fecha_modificacion DATE,
    FOREIGN KEY (id_categoria) REFERENCES Categoria(id_categoria) ON DELETE CASCADE,
    FOREIGN KEY (id_tesorero) REFERENCES Tesorero(id_tesorero) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabla: Aportaciones_Asociados
CREATE TABLE Aportaciones_Asociados (
    id_aportacion INT,
    id_asociado INT,
    PRIMARY KEY (id_aportacion, id_asociado), 
    FOREIGN KEY (id_aportacion) REFERENCES Aportacion(id_aportacion) ON DELETE CASCADE,
    FOREIGN KEY (id_asociado) REFERENCES Asociado(id_asociado) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabla: Pago
CREATE TABLE Pago (
    id_pago INT AUTO_INCREMENT PRIMARY KEY,
    id_aportacion INT,
    mes VARCHAR(10),
    monto DECIMAL(10, 2),
    fecha DATE,
    FOREIGN KEY (id_aportacion) REFERENCES Aportacion(id_aportacion) ON DELETE CASCADE
) ENGINE=InnoDB;

--
SHOW TABLES;
