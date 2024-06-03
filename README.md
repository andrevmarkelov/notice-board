# Notice Board Project

## Development Environment

- **System**: notice-board.local
- **Database**: MySQL 8.0
- **Backend**: PHP 8.3

## Getting Started

To set up and run the project, execute the following commands in your terminal:

```sh
git clone https://github.com/andrevmarkelov/notice-board
cd notice-board
composer install
npm install
npm run build
cp .env.example .env
php artisan key:generate
php artisan serve
php artisan migrate --seed
npm run dev
```

## API Endpoints

### Get All Ads

* **Endpoint:** `GET /api/v1/ads`
* **Description:** Retrieves a list of all ads.
* **Query Parameters:**
  + `limit`: Specify the number of ads to retrieve (e.g., `limit=2`).
  + `sort_fields[created_at]`: Sort by creation date (`asc` or `desc`).
  + `sort_fields[price]`: Sort by price (`asc` or `desc`).

### Get Specific Ad

* **Endpoint:** `GET /api/v1/ad/{id}`
* **Description:** Retrieves a specific ad by ID.
* **Query Parameters:**
  + `fields`: Specify additional fields to display, separated by commas (e.g., `fields=description,photos`).
  + `description`: Include the ad description.
  + `photos`: Include the ad photos (the main photo is removed to avoid duplicates).

### Create New Ad

* **Endpoint:** `POST /api/v1/ad`
* **Description:** Creates a new ad.
* **Request Body:**
 ```json
{
  "title": "title",
  "description": "description",
  "price": "14.25",
  "photos": [
    "https://i.imgur.com/DTtbvLp.png",
    "https://i.imgur.com/DTtbvLp.png",
    "https://i.imgur.com/DTtbvLp.png"
  ]
}
```

### Test Results

![Test Results](https://i.imgur.com/DTtbvLp.png)
