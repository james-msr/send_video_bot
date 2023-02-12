set -e

php-fpm -D
nginx -g 'daemon off;'

php /var/www/domains/send_video_bot/artisan schedule:run
php /var/www/domains/send_video_bot/artisan schedule:list
