# 📚 Base de Datos - Biblioteca

Este repositorio contiene la estructura de la base de datos para la gestión de una biblioteca. Se incluyen tablas para el manejo de usuarios, documentos, préstamos y más.

---

## 📌 *Estructura de la Base de Datos*
La base de datos se llama *Biblioteca* y contiene las siguientes tablas:

### 📂 *1. Usuarios*
> Almacena los datos de los usuarios de la biblioteca.
| Campo        | Tipo de Dato     | Restricciones              |
|-------------|----------------|---------------------------|
| IdUsuario | INT (PK)       | AUTO_INCREMENT           |
| Nombre    | VARCHAR(50)    | NOT NULL                 |
| Direccion | VARCHAR(50)    | NULLABLE                 |
| Telefono  | VARCHAR(9)     | NULLABLE                 |
| Curso     | INT            | NOT NULL                 |
| Email     | VARCHAR(50)    | UNIQUE, NOT NULL         |
| Clave     | VARCHAR(255)   | NOT NULL (Hash)          |
| Rol       | ENUM('admin', 'usuario') | DEFAULT 'usuario' |

📌 *Nota:* Se usa password_hash() en PHP para almacenar contraseñas de forma segura.

---

### 📂 *2. Documentos*
> Contiene información general sobre los materiales disponibles en la biblioteca.
| Campo          | Tipo de Dato     | Restricciones |
|---------------|----------------|--------------|
| IdDocumento | INT (PK)       | AUTO_INCREMENT |
| Titulo      | VARCHAR(100)   | NOT NULL |
| ISBN        | VARCHAR(20)    | UNIQUE, NULLABLE |
| ListaAutores| VARCHAR(255)   | NULLABLE |
| FechaPublicacion | DATE  | NULLABLE |
| NumEjemplares | INT(2) | DEFAULT 1 |
| Descripcion | TEXT | NULLABLE |
| Materia | VARCHAR(50) | NULLABLE |

📌 *Nota:* Todos los documentos (libros, revistas, multimedia) derivan de esta tabla.

---

### 📂 *3. Libros*
> Tabla que almacena libros específicos.
| Campo      | Tipo de Dato | Restricciones |
|-----------|------------|--------------|
| ISBN    | VARCHAR(20) | UNIQUE, FK -> Documentos.ISBN |
| Titulo  | VARCHAR(100) | FK -> Documentos.Titulo |
| NumPaginas | INT | NULLABLE |

---

### 📂 *4. Revistas*
> Tabla para publicaciones periódicas.
| Campo      | Tipo de Dato | Restricciones |
|-----------|------------|--------------|
| ISBN    | VARCHAR(20) | UNIQUE, FK -> Documentos.ISBN |
| Titulo  | VARCHAR(100) | FK -> Documentos.Titulo |
| Frecuencia | ENUM('diaria', 'semanal', 'mensual', 'anual') | NOT NULL |

---

### 📂 *5. Multimedia*
> Documentos en formato digital o audiovisual.
| Campo      | Tipo de Dato | Restricciones |
|-----------|------------|--------------|
| Titulo  | VARCHAR(100) | FK -> Documentos.Titulo |
| Soporte | VARCHAR(50) | NOT NULL |

---

### 📂 *6. Ejemplares*
> Cada documento tiene copias físicas identificadas con un ID único.
| Campo        | Tipo de Dato | Restricciones |
|-------------|------------|--------------|
| IdEjemplar | INT (PK)  | AUTO_INCREMENT |
| Titulo    | VARCHAR(100) | FK -> Documentos.Titulo |
| Localizacion | VARCHAR(50) | NOT NULL |
| Prestado  | BOOLEAN  | DEFAULT FALSE |

---

### 📂 *7. Préstamos*
> Registro de libros prestados y su estado.
| Campo        | Tipo de Dato | Restricciones |
|-------------|------------|--------------|
| IdPrestamo | INT (PK)  | AUTO_INCREMENT |
| IdUsuario  | INT | FK -> Usuarios.IdUsuario |
| IdEjemplar | INT | FK -> Ejemplares.IdEjemplar |
| FechaInicio | DATE | NOT NULL |
| FechaFin | DATE | NOT NULL |
| Observacion | TEXT | NULLABLE |
| Estado | BOOLEAN | DEFAULT TRUE (Activo) |

📌 *Reglas de Préstamo:*
- *Máximo 6 libros por usuario.*
- *Plazo máximo de 3 semanas.*
- *No se puede reservar un libro prestado.*
- *Si se devuelve tarde, hay una sanción de 4 semanas sin préstamos.*

---

## ⚡ *Métodos Utilizados*
En el sistema PHP, se emplean *métodos para la gestión de la biblioteca*, incluyendo:

### 🔹 *Usuarios*
- registrarUsuario($nombre, $email, $clave): Registra nuevos usuarios con contraseñas encriptadas.
- iniciarSesion($email, $clave): Verifica credenciales y maneja sesiones.
- esAdmin($idUsuario): Devuelve TRUE si el usuario es administrador.

### 🔹 *Libros*
- listarLibros(): Obtiene todos los libros disponibles.
- buscarLibro($filtro): Filtra libros por título, autor o ISBN.
- registrarLibro($titulo, $ISBN, $autores, $materia): Agrega un nuevo libro (solo administradores).

### 🔹 *Préstamos*
- realizarPrestamo($idUsuario, $idEjemplar): Asigna un libro a un usuario.
- devolverPrestamo($idPrestamo): Marca el préstamo como devuelto.
- verHistorialPrestamos($idUsuario): Muestra el historial de préstamos del usuario.

---

## 🔐 *Seguridad y Control de Acceso*
📌 *Control de Acceso Implementado:*
- *Roles (admin, usuario)* para diferenciar permisos.
- *Protección contra SQL Injection* con PDO::prepare().
- *Encriptación de contraseñas* con password_hash().
- *Restricción de operaciones*: Solo los administradores pueden modificar o eliminar registros.

---

## 🚀 *Cómo Usar la Base de Datos*
### 📥 *Instalación*
1. *Clonar el repositorio:*
   ```bash
   git clone https://github.com/Cperseb/DB-Base-Datos-Biblioteca.git
