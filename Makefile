.PHONY: laravel-mix build clean cleanmac

serve:
	npm run build && php artisan serve

clean:
	mysql --user=root --password=root_1634 -e "DROP DATABASE laravel;" && php artisan migrate --seed
