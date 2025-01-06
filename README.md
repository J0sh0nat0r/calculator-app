# Quickstart

## Run
```sh
docker build -t calc --target runtime .
docker run --rm -p 8080:8080 -it calc
```
-> Browse to: <a href="http://localhost:8080">http://localhost:8080</a>

## Supported operators
- `+` - binary add / unary absolute
- `/` - binary divide
- `*` - binary multiply
- `-` - binary subtract / unary negate
- `^` - exponentiation
- `sqrt` - square root

## Example expressions
- `1 + 1 + 2`
- `3 / (5 + 7)`
- `sqrt((((9*9)/12)+(13-4))*2)^2`

## Development
```sh
direnv allow
composer install
npm install
cp .env.example .env
npm run build
sail up -d
php artisan key:generate
sail artisan migrate --seed
sail test
```
