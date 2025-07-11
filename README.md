# 📘 MetaBD

**MetaBD** es una herramienta web desarrollada en PHP 8.3 que permite inspeccionar y documentar las tablas de una base de datos MariaDB. Es útil para equipos que desean tener claridad sobre qué almacena cada tabla y dónde se utiliza dentro de un sistema.

Repositorio oficial: [https://github.com/wpadillav/MetaBD.git](https://github.com/wpadillav/MetaBD.git)

---

## 🚀 Características

- Visualización de bases de datos y sus tablas.
- Detalles técnicos de columnas (tipo, claves, comentarios, etc.).
- Campos de documentación editable por tabla:
  - ¿Qué guarda esta tabla?
  - ¿Dónde se usa esta tabla?
- Interfaz simple, clara y funcional.
- Persistencia de la documentación en archivos locales.

---

## 🧱 Requisitos

- PHP >= 8.3
- Servidor web (Apache, Nginx o PHP Built-in Server)
- MariaDB o MySQL
- Composer

---

## ⚙️ Instalación

1. **Clona el repositorio:**

   ```bash
   git clone https://github.com/wpadillav/MetaBD.git
   cd MetaBD
    ````

2. **Instala dependencias con Composer:**

   ```bash
   composer install
   ```

3. **Copia el archivo de entorno y configúralo:**

   ```bash
   cp .env_example .env
   ```

   Edita `.env` con tus datos reales:

   ```dotenv
   DB_HOST=localhost
   DB_PORT=3306
   DB_NAME=doc_db
   DB_USER=root
   DB_PASS=tu_contraseña
   DB_DESARROLLO="Nombre de tu proyecto o entorno"
   ```

4. **Inicia el servidor (opcional):**

   Si quieres probarlo rápidamente con el servidor embebido de PHP:

   ```bash
   php -S localhost:8000 -t public
   ```

   Luego visita: [http://localhost:8000](http://localhost:8000)

---

## 📁 Estructura del Proyecto

```
MetaBD/
│
├── app/               # Lógica principal (DB, lectura de esquema)
│   ├── Database.php
│   ├── SchemaReader.php
│   └── TableMetadata.php
│
├── public/            # Punto de entrada del sistema
│   ├── index.php
│   └── table_detail.php
│
├── storage/           # Almacena la documentación generada
│   └── .gitkeep
│
├── views/             # Plantillas HTML/PHP
│   ├── layout.php
│   └── overview.php
│
├── .env               # Configuración del entorno
├── .env_example       # Ejemplo de configuración
├── .gitignore
├── composer.json
└── composer.lock
```

---

## 🧩 Dependencias

Este proyecto utiliza:

* [`vlucas/phpdotenv`](https://github.com/vlucas/phpdotenv) – para gestionar variables de entorno.

Autocarga PSR-4 configurada en `composer.json`:

```json
{
  "require": {
    "vlucas/phpdotenv": "^5.5"
  },
  "autoload": {
    "psr-4": {
      "App\\": "app/"
    }
  }
}
```

Después de instalar, asegúrate de regenerar la autoload:

```bash
composer dump-autoload
```

---

## 🔧 Tecnologías Usadas

| Tecnología              | Descripción                      |
| ----------------------- | -------------------------------- |
| 🐘 **PHP 8.3**          | Lenguaje de backend principal    |
| 🐬 **MariaDB/MySQL**    | Motor de base de datos           |
| ⚙️ **Composer**         | Gestor de dependencias PHP       |
| 🧪 **vlucas/phpdotenv** | Manejo de variables de entorno   |
| 🧭 **HTML/CSS**         | Interfaz sencilla y responsiva   |
| 🗃️ **PSR-4**           | Autocarga estándar de clases PHP |

---

## 🤝 Archivos de Comunidad

| Archivo                                      | Descripción                                                   |
| -------------------------------------------- | ------------------------------------------------------------- |
| [`LICENSE`](./LICENSE)                       | Licencia MIT del proyecto                                     |
| [`CODE_OF_CONDUCT.md`](./CODE_OF_CONDUCT.md) | Código de conducta para todos los participantes               |
| [`SECURITY.md`](./SECURITY.md)               | Protocolo para reportar vulnerabilidades de forma responsable |
| [`CONTRIBUTING.md`](./CONTRIBUTING.md)       | Guía paso a paso para contribuir al proyecto                  |

---

## 📝 Licencia

Este proyecto está bajo la licencia [MIT](LICENSE). Puedes modificarla si necesitas otro tipo de licencia.

---

## 👨‍💻 Autor

**William Padilla**
📧 [william.padilla@proton.me](mailto:william.padilla@proton.me)
GitHub: [@wpadillav](https://github.com/wpadillav)
