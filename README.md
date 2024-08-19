# Laravel Test

## Configuración

1. Clona el repositorio:
```bash
git clone https://github.com/tu-usuario/laravel-test.git
cd laravel-test

composer install

##Antes de hacer migracion crea la base de datos en my sql con el mismo nombre descrito en el .env

php artisan migrate
php artisan db:seed

##Para las notificaciones por email en necesario configurar la credenciales en el .env

php artisan serve

##Test
php artisan test



