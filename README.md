# Football Events

A real-time football match tracking application with features:
- Display matches by league
- Automatic score and match status updates
- Country flags for each league
- User-friendly and responsive interface

## System Requirements

- Docker and Docker Compose
- Git
- PHP 8.1 or higher
- Composer
- Node.js and npm (for frontend)

## Installation Steps

1. Clone repository:
```bash
git clone https://github.com/vuongkenhnnn/football-events.git
cd football-events
```

2. Install dependencies:
```bash
composer install
```

3. Copy configuration file:
```bash
cp .env.example .env
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Build and run Docker containers:
```bash
docker-compose up -d --build
```

6. Run migrations and seeders:
```bash
docker-compose exec web php artisan migrate --seed
```

## Common Commands
- View logs:
```bash
docker-compose logs -f
```

- Rebuild containers:
```bash
docker-compose down
docker-compose up -d --build
```

## Docker Configuration

The application uses the following services:
- `web`: Laravel application
- `nginx`: Web server
- `db`: MySQL database
- `cron`: Laravel scheduler

## API Endpoints

- `GET /api/matches`: Get list of matches

## Cron Jobs

- `fetch:football-matches`: Update match data every minute

## Troubleshooting

1. If you encounter permission issues:
```bash
sudo chown -R $USER:$USER .
```

2. If containers fail to start:
```bash
docker-compose down
docker system prune
docker-compose up -d --build
```

3. If database issues occur:
```bash
docker-compose down -v
docker-compose up -d --build
```

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request
