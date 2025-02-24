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
    id_contacto INT UNIQUE,
    nombres VARCHAR(100),
    fecha_nac DATE,
    cargo VARCHAR(50),
    descripcion VARCHAR(50),
    estado VARCHAR(50),
    FOREIGN KEY (id_promocion) REFERENCES Promocion(id_promocion) ON DELETE SET NULL,
    FOREIGN KEY (id_especialidad) REFERENCES Especialidad(id_especialidad) ON DELETE SET NULL,
    FOREIGN KEY (id_contacto) REFERENCES Contacto(id_contacto) ON DELETE CASCADE ON UPDATE CASCADE
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
    FOREIGN KEY (id_miembro) REFERENCES Miembro(id_miembro) ON DELETE CASCADE, 
	FOREIGN KEY (id_noticia) REFERENCES Noticia(id_noticia) ON DELETE SET NULL,
    FOREIGN KEY (id_evento) REFERENCES Evento(id_evento) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Tabla: Miembros_Logros 
CREATE TABLE Miembros_Logros (
    id_miembro INT NOT NULL,
    id_logro INT NOT NULL,
    id_galeria INT DEFAULT NULL,
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
    id_contacto INT UNIQUE,
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
    lugar VARCHAR(50),
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

#INSERTANDO DATOS EN CONTACTO 
INSERT INTO contacto (num_contacto,correo,lugar)VALUES ("916912549","erksg.10.26@gmail.com","ATE");
INSERT INTO contacto (num_contacto,correo,lugar)VALUES ("916912111","pedro_sq@gmail.com","SJL");
INSERT INTO contacto (num_contacto,correo,lugar)VALUES ("916563668","fatima@gmail.com","MIRAFLORES");

#INSERTANDO DATOS EN USUARIO 
INSERT INTO usuario (nombre_usuario,contrasena,rol) VALUES ("enrike",password("enrike123"),"ADMIN");
INSERT INTO usuario (nombre_usuario,contrasena,rol) VALUES ("angie",password("angie123"),"ADMIN");

#INSERTANDO DATOS EN ESPECIALIDAD 
INSERT INTO especialidad (nombre_especialidad,descripcion)VALUES ("ENFERMERO MILITAR","ENFERMERO MILITAR");
INSERT INTO especialidad (nombre_especialidad,descripcion)VALUES ("ENFEREMERO VETERINARIO MILITAR","ENFEREMERO VETERINARIO MILITAR");
INSERT INTO especialidad (nombre_especialidad,descripcion)VALUES ("AUXILIARES DE CARTOGRAFIA","AUXILIARES DE CARTOGRAFIA");
INSERT INTO especialidad (nombre_especialidad,descripcion)VALUES ("AUXILIAR DE ESTADO MAYOR","AUXILIAR DE ESTADO MAYOR");

#INSERTANDO DATOS EN PROMOCION 
INSERT INTO promocion (nombre_promocion,fecha_creacion,resolucion_creacion,fecha_culminacion,resolucion_culminacion,descripcion,mision,vision,resenia)
VALUES ("XIV PROMOCION ALBERTO REYES GAMARRA","1974-01-01","XIV","1976-01-01","XIV-FIN","LA XIV PROMOCION ALBERTO REYES GAMARRA FUE CREADO UN 16MAR83, FECHA
DE INGRESO A LA ESCUELA TECNICA DEL EJERCITO LA XIV PROMOCION ESTUVO INTEGRADA POR PERSONAL DE LA POLICIA DE
INVESTIGACIONES, GUARDIA REPUBLICANA Y GUARDIA CIVIL Y PEERSONAL
MILITAR DE LA REPUBLICA DE PANAMA.", "LA XIV PROMOCION DESPUES DE HABER EGRESADO DE SU FORMACION TECNICA
Y MILITAR EN LA ESCUELA TECNICA DEL EJERCITO, COMO SUB OFICIAL, SE
CAPACITARON EN DIVERSOS CURSOS TACTICOS COMO COMANDO, ANFIBIOS
CAIDA LIBRE, ENTRE OTROS DE ACUERDO CON LA SITUACION QUE SE VIVIA EN EL
FRENTE INTERNO Y EXTERNO, REQUERIDOS POR EL EJECRTO EN INNOVACIÓN,
EMPRENDIMIENTO Y PROYECCIÓN A LA COMUNIDAD, COMO PARTE DEL
DESARROLLO NACIONAL.","LA XIV PROMOCION ARG, DESDE SU INICIO PARA FORTALECER LA SOLIDARIDAD Y
PROPICIAR LA UNIÓN DE SUS INTEGRANTES Y FAMILIARES Y LA PRESERVACIÓN
DE SUS INTEGRANTES PROPUSO EL APORTE VOLUNTARIO FINANCIERO ENTRE
SUS INTEGRANTES PARA FOMENTAR ACTIVIDADES EDUCATIVAS, CULTURALES,
DEPORTIVAS Y RECREATIVAS A SUS INTEGRANTES ASOCIADOS","SU FORMACIÓN Y PERFECCIONAMIENTO CULMINO SATISFACTOERIAMENTE EN
DIC85, CON RESOLUCION M INISTERIAL RM No 0268-86GU/CP DE 25FEB86,
ASCENDIERON AL GRADO DE SUB OFICIAL CAPACITADO EN SU ESPECIALIDAD Y
PREPARADO PARA DESEMPEÑARSE TÉCNICA Y EFICIENTEMENTE EN LAS
UNIDADES DESIGNADO A LO LARGO Y ANCHO DEL TERRITORIO NACIONAL.");

#INSERTANDO DATOS EN MIEMBRO 
INSERT INTO miembro (id_promocion,id_especialidad,id_contacto,nombres,fecha_nac,cargo,descripcion,estado) 
VALUES (1,1,1,"keler samaniego","1996-10-26","subOficial","descripcion","ACTIVO");
INSERT INTO miembro (id_promocion,id_especialidad,id_contacto,nombres,fecha_nac,cargo,descripcion,estado) 
VALUES (1,2,2,"pedro suarez","1996-02-12","Teniente","descripcion","ACTIVO");
INSERT INTO miembro (id_promocion,id_especialidad,id_contacto,nombres,fecha_nac,cargo,descripcion,estado) 
VALUES (1,3,3,"felipe solano","1972-11-21","Sargento","descripcion","ACTIVO");

#INSERTANDO DATOS EN LOGRO 
INSERT INTO logro (titulo, descripcion) VALUES ("Ascenso a Sargento","Ascensos de rango");
INSERT INTO logro (titulo, descripcion) VALUES ("Ascenso a Teniente","Ascensos de rango");
INSERT INTO logro (titulo, descripcion) VALUES ("Ascenso a Capitan","Ascensos de rango");
INSERT INTO logro (titulo, descripcion) VALUES ("Ascenso a Sargento II","Ascensos de rango");

#INSERTANDO DATOS EN GALERIA 
INSERT INTO galeria (id_promocion,id_miembro,id_noticia,id_evento,tipo_archivo,ruta_archivo,informacion)
VALUES (null,1,null,null,"png","imageKeler1.png","ascenso a cabo");
INSERT INTO galeria (id_promocion,id_miembro,id_noticia,id_evento,tipo_archivo,ruta_archivo,informacion)
VALUES (null,1,null,null,"png","imageKeler2.png","ascenso a subOficial");
INSERT INTO galeria (id_promocion,id_miembro,id_noticia,id_evento,tipo_archivo,ruta_archivo,informacion)
VALUES (null,null,null,null,"png","imageTeniente.png","logro del ascenso a teniente");
INSERT INTO galeria (id_promocion,id_miembro,id_noticia,id_evento,tipo_archivo,ruta_archivo,informacion)
VALUES (null,null,null,null,"png","imageCabo.png","logro del ascenso a cabo");
INSERT INTO galeria (id_promocion,id_miembro,id_noticia,id_evento,tipo_archivo,ruta_archivo,informacion)
VALUES (null,null,null,null,"png","imageCapitan.png","logro del ascenso a capitan");

#INSERTANDO DATOS EN MIEMBROS_LOGROS 
INSERT INTO miembros_logros (id_miembro, id_logro,id_galeria,fecha) VALUES (1, 1 ,1,"1978-09-25");
INSERT INTO miembros_logros (id_miembro, id_logro,id_galeria,fecha) VALUES (1, 2 ,2,"1979-11-09");

#INSERTANDO DATOS EN PERSONA 
INSERT INTO persona (nombres, contacto) VALUES ("angie tamara","978546512");
INSERT INTO persona (nombres, contacto) VALUES ("nahomi jessica","952364884");
INSERT INTO persona (nombres, contacto) VALUES ("carla flores","900586123");
INSERT INTO persona (nombres, contacto) VALUES ("sofia rojas","999854623");
INSERT INTO persona (nombres, contacto) VALUES ("bruce stefano","958642112");
INSERT INTO persona (nombres, contacto) VALUES ("yenny vasquez","963257845");

#INSERTANDO DATOS EN EVENTO  
INSERT INTO evento (nombre_evento,lugar_evento,fecha_evento,descripcion,confirmacion_asistencia)
VALUES ("Fiesta Navideña","Chaclacayo Mz C lote 9", "2025/02/28","Fiesta navideña para los integrantes de la promocion",1);

INSERT INTO evento (nombre_evento,lugar_evento,fecha_evento,descripcion,confirmacion_asistencia)
VALUES ("Carnavales","Santa Anita - Los Ruiseñores 562", "2025/03/15","Carnavales organizado por los miembros de la promocion",1);

#INSERTANDO DATOS EN EVENTO_MIEMBROS
INSERT INTO evento_miembro (id_evento, id_miembro) VALUES (1,1);
INSERT INTO evento_miembro (id_evento, id_miembro) VALUES (1,2);
INSERT INTO evento_miembro (id_evento, id_miembro) VALUES (2,2);
INSERT INTO evento_miembro (id_evento, id_miembro) VALUES (2,1);

#INSERTANDO DATOS EN EVENTO_PERSONA
INSERT INTO evento_persona (id_evento, id_persona) VALUES (1,1);
INSERT INTO evento_persona (id_evento, id_persona) VALUES (1,2);
INSERT INTO evento_persona (id_evento, id_persona) VALUES (1,3);
INSERT INTO evento_persona (id_evento, id_persona) VALUES (1,4);
INSERT INTO evento_persona (id_evento, id_persona) VALUES (2,3);
INSERT INTO evento_persona (id_evento, id_persona) VALUES (2,1);

#INSERTANDO DATOS EN LA TABLA NOTICIA
INSERT INTO noticia (titulo,descripcion,fecha_publicacion) VALUES ("Gran Chocolatada","Es organizado por la promocion XIV ...", "2025/02/23" );
INSERT INTO noticia (titulo,descripcion,fecha_publicacion) VALUES ("BabyShower Pamela","Es organizado por la promocion XIV ...", "2025/02/19" );
INSERT INTO noticia (titulo,descripcion,fecha_publicacion) VALUES ("Boda del miembro Cobida","Es organizado por la promocion XIV ...", "2025/01/25" );
INSERT INTO noticia (titulo,descripcion,fecha_publicacion) VALUES ("Entrega de Canastas a las madres de la promocion","Es organizado por la promocion XIV ...", "2024/12/23" );

#INSERTANDO DATOS EN LA TABLA NOTICIA_PERSONA
INSERT INTO noticia_persona (id_noticia, id_persona) VALUES (1,3);
INSERT INTO noticia_persona (id_noticia, id_persona) VALUES (1,4);
INSERT INTO noticia_persona (id_noticia, id_persona) VALUES (1,5);

INSERT INTO noticia_persona (id_noticia, id_persona) VALUES (2,1);
INSERT INTO noticia_persona (id_noticia, id_persona) VALUES (2,2);
INSERT INTO noticia_persona (id_noticia, id_persona) VALUES (2,3);
INSERT INTO noticia_persona (id_noticia, id_persona) VALUES (2,4);
INSERT INTO noticia_persona (id_noticia, id_persona) VALUES (2,5);

# PROCEDIMIENTOS ALMACENADOS 
# LISTAR TODOS LOS MIEMBROS CON SUS CONTACTOS , LOGROS E IMAGENES 
CREATE PROCEDURE listMiembros()
SELECT 
    m.id_miembro,
    m.nombres,
    m.cargo,
    m.descripcion,
    c.num_contacto,
    c.correo,
    c.lugar,
    (
        SELECT GROUP_CONCAT(l.titulo SEPARATOR ' , ')
        FROM miembros_logros ml
        INNER JOIN logro l ON ml.id_logro = l.id_logro
        WHERE ml.id_miembro = m.id_miembro
    ) AS logros,
    (
        SELECT GROUP_CONCAT(g.ruta_archivo SEPARATOR ' , ')
        FROM galeria g
        WHERE g.id_miembro = m.id_miembro
    ) AS ruta_imagenes
FROM 
    miembro m
INNER JOIN 
    contacto c ON m.id_contacto = c.id_contacto
GROUP BY 
    m.id_miembro, m.nombres, m.cargo, m.descripcion, c.num_contacto, c.correo, c.lugar;


# PROCEDIMIENTO ALMACENADO MIEMBRO POR ID
DELIMITER $$
CREATE PROCEDURE miembrosById(in cod int)
BEGIN
SELECT 
    m.id_miembro,
    m.nombres,
    m.cargo,
    m.descripcion,
    c.num_contacto,
    c.correo,
    c.lugar,
    (
        SELECT GROUP_CONCAT(l.titulo SEPARATOR ' , ')
        FROM miembros_logros ml
        INNER JOIN logro l ON ml.id_logro = l.id_logro
        WHERE ml.id_miembro = m.id_miembro
    ) AS logros,
    (
        SELECT GROUP_CONCAT(g.ruta_archivo SEPARATOR ' , ')
        FROM galeria g
        WHERE g.id_miembro = m.id_miembro
    ) AS ruta_imagenes
FROM 
    miembro m
INNER JOIN 
    contacto c ON m.id_contacto = c.id_contacto
WHERE m.id_miembro= cod
GROUP BY 
    m.id_miembro, m.nombres, m.cargo, m.descripcion, c.num_contacto, c.correo, c.lugar;
END $$
DELIMITER ;

# PROCEDIMIENTO ALMACENADO PARA LISTAR EL EVENTO ASOCIADO A LA PERSONA
DELIMITER $$
CREATE PROCEDURE listEventoPersona()
BEGIN

SELECT p.id_personas
,p.nombres as Invitado
, p.contacto  as Contacto
, e.nombre_evento as Evento
FROM persona p 
INNER JOIN evento_persona ep 
ON p.id_personas = ep.id_persona
INNER JOIN evento e
ON  ep.id_evento = e.id_evento;

END $$
DELIMITER 



# PROCEDIMIENTO ALMACENADO PARA LISTAR EL EVENTO ASOCIADO A LA PERSONA POR EL ID EVENTO
DELIMITER $$
CREATE PROCEDURE listEventoPersonaById(in id_evento int)
BEGIN

SELECT e.id_evento , p.id_personas
,p.nombres as Invitado
, p.contacto  as Contacto
, e.nombre_evento as Evento
FROM persona p 
INNER JOIN evento_persona ep 
ON p.id_personas = ep.id_persona
INNER JOIN evento e
ON  ep.id_evento = e.id_evento
WHERE e.id_evento = id_evento;

END $$
DELIMITER 


#PROCEDIMIENTO ALMACENADO PARA LISTAR LOS EVENTOS 
DELIMITER $$
CREATE PROCEDURE listEvento()
BEGIN

SELECT  e.id_evento
,e.nombre_evento 
, e.descripcion  
, e.lugar_evento
, e.fecha_evento 
, GROUP_CONCAT( m.nombres SEPARATOR ' , ' ) AS Organizadores
, GROUP_CONCAT(c.num_contacto separator ' , ' ) AS Celulares
, GROUP_CONCAT(c.correo separator ' , ' )	AS Email
FROM evento e
INNER JOIN evento_miembro em
ON e.id_evento = em.id_evento
INNER JOIN miembro m 
ON em.id_miembro = m.id_miembro
INNER JOIN contacto c 
ON m.id_contacto = c.id_contacto
GROUP BY  e.descripcion  , e.lugar_evento, e.fecha_evento  ORDER BY fecha_evento;
END $$
DELIMITER 


#PROCEDIMIENTO ALMACENADO PARA LISTAR LOS EVENTOS POR ID
DELIMITER $$
CREATE PROCEDURE EventoById(in id_evento int)
BEGIN

SELECT  e.id_evento
,e.nombre_evento 
, e.descripcion  
, e.lugar_evento
, e.fecha_evento 
, GROUP_CONCAT( m.nombres SEPARATOR ' , ' ) AS Organizadores
, GROUP_CONCAT(c.num_contacto separator ' , ' ) AS Celulares
, GROUP_CONCAT(c.correo separator ' , ' )	AS Email
FROM evento e
INNER JOIN evento_miembro em
ON e.id_evento = em.id_evento
INNER JOIN miembro m 
ON em.id_miembro = m.id_miembro
INNER JOIN contacto c 
ON m.id_contacto = c.id_contacto
WHERE e.id_evento=id_evento
GROUP BY  e.descripcion  , e.lugar_evento, e.fecha_evento  ORDER BY fecha_evento;
END $$
DELIMITER 

# LISTAR ORGANIZADORES DE LOS EVENTOS POR ID_EVENTO

DELIMITER $$
CREATE PROCEDURE listEventoOrganizador(in id_evento int)
BEGIN

SELECT em.id_evento, m.id_miembro , m.nombres , c.num_contacto , c.correo 
FROM evento_miembro em
INNER JOIN miembro m 
ON em.id_miembro = m.id_miembro
INNER JOIN contacto c
ON m.id_contacto = c.id_contacto
WHERE em.id_evento =id_evento;
END $$
DELIMITER 

# LISTAR NOTICIA CON INFORMACION DE LA PERSONAS INVOLUCRADAS
DELIMITER $$
CREATE PROCEDURE listNoticiasAndPersonas()
BEGIN
SELECT 
  n.id_noticia,
  n.titulo,
   (
        SELECT GROUP_CONCAT(p.nombres SEPARATOR ' , ')
        FROM noticia_persona np
        INNER JOIN persona p 
        ON np.id_persona = p.id_personas
        WHERE np.id_noticia = n.id_noticia
    ) AS Personas,
  n.descripcion,
  n.fecha_publicacion
FROM 
    noticia n
GROUP BY n.titulo,n.descripcion,n.fecha_publicacion;
END $$
DELIMITER ;

# LISTADO DE PERSONAS INVOLUCRADAS A 1 NOTICIA POR SU ID
DELIMITER $$
CREATE PROCEDURE ListNoticiaPersonaByIdNoticia(in id_noticia int)
BEGIN
SELECT n.id_noticia,p.id_personas, n.titulo ,p.nombres
FROM noticia n
INNER JOIN  noticia_persona np
ON n.id_noticia = np.id_noticia
INNER JOIN persona p
ON np.id_persona = p.id_personas
WHERE n.id_noticia = id_noticia;
END $$
DELIMITER 

# PROCEDMIENTO PARA LISTAR LOS LOGROS DE CADA MIEMBRO
DELIMITER $$
CREATE PROCEDURE listMiembroLogros(IN idMiembro int)
BEGIN

SELECT ml.id_miembro, l.id_logro , l.titulo , ml.fecha
FROM miembros_logros ml 
INNER JOIN logro l
ON ml.id_logro = l.id_logro 
WHERE id_miembro= idMiembro;

END $$
DELIMITER 

--
SHOW TABLES;
--
