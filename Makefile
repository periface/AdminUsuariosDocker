.PHONY: laravel-mix build clean

serve:
	npm run build && php artisan serve

clean:
	mysql --user=root --password=root -e "DROP DATABASE laravel;" && php artisan migrate --seed
