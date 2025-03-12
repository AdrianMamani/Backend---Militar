<?php
/**
 * Clase InterfazModel
 * Maneja operaciones relacionadas con la galería y la promoción.
 */
class InterfazModel
{
    private $db;

    /**
     * Constructor de la clase.
     *
     * @param Database $con Objeto de conexión a la base de datos.
     */
    public function __construct(Database $con)
    {
        $this->db = $con->getConexion();
    }

    /**
     * Sube un archivo a la galería.
     *
     * @param string $tipo_archivo Tipo de archivo (imagen/video).
     * @param string $ruta_archivo Ruta del archivo en el servidor.
     * @param string $informacion Información adicional sobre el archivo.
     * @return bool Retorna true si la operación fue exitosa, false en caso contrario.
     */
    public function subirGaleria($tipo_archivo, $ruta_archivo, $informacion)
    {
        $sql = "INSERT INTO Galeria (tipo_archivo, ruta_archivo, informacion) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$tipo_archivo, $ruta_archivo, $informacion]);
    }

    /**
     * Actualiza la información de una promoción.
     *
     * @param int $id_promocion ID de la promoción a actualizar.
     * @param array $datos Datos a actualizar (descripcion, resenia).
     * @return bool Retorna true si la operación fue exitosa, false en caso contrario.
     */
    public function actualizarInformacion($id_promocion, $datos)
    {
        $sql = "UPDATE Promocion SET descripcion = ?, resenia = ? WHERE id_promocion = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$datos['descripcion'], $datos['resenia'], $id_promocion]);
    }

    /**
     * Actualiza un archivo en la galería.
     *
     * @param int $id_galeria ID del archivo en la galería.
     * @param string $ruta_archivo Nueva ruta del archivo.
     * @param string $tipo Nuevo tipo de archivo.
     * @return bool Retorna true si la operación fue exitosa, false en caso contrario.
     */
    public function actualizarGaleria($id_galeria, $ruta_archivo, $tipo)
    {
        $sql = "UPDATE Galeria SET ruta_archivo = ?, tipo_archivo = ? WHERE id_galeria = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$ruta_archivo, $tipo, $id_galeria]);
    }

    /**
     * Sube un video a la galería.
     *
     * @param string $tipo_archivo Tipo de archivo (video).
     * @param string $ruta_archivo Ruta del archivo en el servidor.
     * @param string $informacion Información adicional sobre el video.
     * @return bool Retorna true si la operación fue exitosa, false en caso contrario.
     */
    public function subirVideo($tipo_archivo, $ruta_archivo, $informacion)
    {
        $sql = "INSERT INTO Galeria (tipo_archivo, ruta_archivo, informacion) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$tipo_archivo, $ruta_archivo, $informacion]);
    }

    /**
     * Actualiza la información de un video en la galería.
     *
     * @param int $id_galeria ID del video en la galería.
     * @param string $informacion Nueva información del video.
     * @return bool Retorna true si la operación fue exitosa, false en caso contrario.
     */
    public function actualizarInformacionVideo($id_galeria, $informacion)
    {
        $sql = "UPDATE Galeria SET informacion = ? WHERE id_galeria = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$informacion, $id_galeria]);
    }

     /**
     * Edita la imagen de referencia en la galería.
     *
     * @param int $id_galeria ID de la imagen en la galería.
     * @param string $ruta_archivo Nueva ruta de la imagen.
     * @return bool Retorna true si la operación fue exitosa, false en caso contrario.
     */
    public function editarImagenReferencia($id_galeria, $ruta_archivo)
    {
        $sql = "UPDATE Galeria SET ruta_archivo = ? WHERE id_galeria = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$ruta_archivo, $id_galeria]);
    }
}
