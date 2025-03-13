<h3>Installation instructions</h2>

<b>1. Clone the Repository</b>

<i>git clone https://github.com/shar0nnn/data-scraper.git

cd data-scraper</i>

<b>2. Copy the Environment File</b>

<i>cp .env.example .env</i>

<b>Then update the .env file with your configuration database settings, for example:</b>

<i>DB_DATABASE=laravel

DB_USERNAME=sail

DB_PASSWORD=password</i>

<b>3. Install Composer Dependencies</b>

<i>docker run --rm -u "\$(id -u):\$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html laravelsail/php84-composer:latest composer install --ignore-platform-reqs</i>

<b>4. Start Laravel Sail</b>

<i>./vendor/bin/sail up -d</i>

<b>5. Generate Application Key</b>

<i>./vendor/bin/sail artisan key:generate</i>

<b>6. Install NPM Dependencies</b>

<i>./vendor/bin/sail npm install</i>

<b>then start Vite in development mode</b>

<i>./vendor/bin/sail npm run dev</i>

<b>7. Run Migrations</b>

<i>./vendor/bin/sail artisan migrate --seed</i>
