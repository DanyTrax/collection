# Assets de AdminLTE 4

Este proyecto asume que los assets compilados de AdminLTE 4 se encuentran en este directorio (`adminlte/`) en las carpetas `dist/` y `plugins/`. Puedes prepararlos usando cualquiera de los métodos siguientes:

## 1. Instalación rápida (sin build)

- **NPM**

  ```bash
  npm install admin-lte@4.0.0-rc3 --save-dev
  mkdir -p adminlte
  cp -R node_modules/admin-lte/dist adminlte/
  ```

- **Yarn**

  ```bash
  yarn add admin-lte@4.0.0-rc3
  mkdir -p adminlte
  cp -R node_modules/admin-lte/dist adminlte/
  ```

- **Composer** (útil en hosting compartidos con SSH + PHP)

  ```bash
  composer require "almasaeed2010/adminlte=4.0.0-rc3"
  mkdir -p adminlte
  cp -R vendor/almasaeed2010/adminlte/dist adminlte/
  ```

## 2. Compilar desde el repositorio oficial

```bash
git clone https://github.com/ColorlibHQ/AdminLTE.git tmp-adminlte
cd tmp-adminlte
npm install
npm run build
cp -R dist ../adminlte/
cd ..
rm -rf tmp-adminlte
```

## 3. Copia manual

Si ya cuentas con una copia compilada, basta con subir la carpeta `dist/` dentro de `adminlte/` mediante FTP o el administrador de archivos de tu hosting.

> **Nota:** Los plugins de terceros (DataTables, FontAwesome, etc.) se cargan mediante CDN desde los layouts, así que basta con tener `adminlte/dist` disponible localmente.
