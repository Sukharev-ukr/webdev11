# Postman API Testing Guide for SquadSport

This guide explains how to test the SquadSport API endpoints using Postman.

## Prerequisites

1. **Postman** installed (download from https://www.postman.com/downloads/)
2. **SquadSport application** running at `http://localhost`
3. **CSRF Token** (for authenticated endpoints)

## API Endpoints

### 1. Get Today's Matches (Public)

**Endpoint:** `GET http://localhost/index.php?route=api/matches/today`

**Description:** Returns all matches scheduled for today.

**Request:**
- Method: `GET`
- URL: `http://localhost/index.php?route=api/matches/today`
- Headers: None required

**Example Response:**
```json
{
  "matches": [
    {
      "id": 1,
      "sport_id": 1,
      "location_id": 1,
      "creator_id": 1,
      "date_time": "2025-01-15 19:00:00",
      "max_players": 12,
      "min_skill_level": 2,
      "max_skill_level": 5,
      "status": "open",
      "tournament_id": null,
      "sport_name": "Volleyball",
      "location_name": "USC Hall 2"
    }
  ]
}
```

**Postman Setup:**
1. Create a new request
2. Set method to `GET`
3. Enter URL: `http://localhost/index.php?route=api/matches/today`
4. Click "Send"

---

### 2. Get Upcoming Tournaments (Public)

**Endpoint:** `GET http://localhost/index.php?route=api/tournaments/upcoming`

**Description:** Returns all upcoming tournaments.

**Request:**
- Method: `GET`
- URL: `http://localhost/index.php?route=api/tournaments/upcoming`
- Headers: None required

**Example Response:**
```json
{
  "tournaments": [
    {
      "id": 1,
      "sport_id": 1,
      "name": "Amsterdam Winter Volleyball Cup",
      "description": "4-team winter tournament",
      "start_date": "2025-02-01",
      "end_date": "2025-02-02",
      "status": "upcoming"
    }
  ]
}
```

**Postman Setup:**
1. Create a new request
2. Set method to `GET`
3. Enter URL: `http://localhost/index.php?route=api/tournaments/upcoming`
4. Click "Send"

---

## Getting CSRF Token (For Authenticated Endpoints)

To test authenticated endpoints, you need a CSRF token:

1. **Login via Browser:**
   - Go to `http://localhost/index.php?route=auth/login`
   - Login with your credentials
   - Open browser DevTools (F12)
   - Go to Application/Storage → Cookies
   - Find `PHPSESSID` cookie value

2. **Get CSRF Token:**
   - In browser, view page source (Ctrl+U)
   - Search for `<meta name="csrf-token"`
   - Copy the `content` value

3. **Use in Postman:**
   - Add header: `X-CSRF-Token: <your-token>`
   - Or add to request body: `csrf_token: <your-token>`

---

## Testing with Postman Collection

### Option 1: Manual Setup

1. **Create New Collection:**
   - Click "New" → "Collection"
   - Name it "SquadSport API"

2. **Add Environment Variables:**
   - Click "Environments" → "Create Environment"
   - Add variables:
     - `base_url`: `http://localhost`
     - `csrf_token`: (get from browser after login)

3. **Create Requests:**
   - Add requests to collection
   - Use `{{base_url}}` in URLs
   - Use `{{csrf_token}}` in headers/body

### Option 2: Import Collection

Create a JSON file with this structure:

```json
{
  "info": {
    "name": "SquadSport API",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "Get Today's Matches",
      "request": {
        "method": "GET",
        "header": [],
        "url": {
          "raw": "http://localhost/index.php?route=api/matches/today",
          "protocol": "http",
          "host": ["localhost"],
          "path": ["index.php"],
          "query": [
            {"key": "route", "value": "api/matches/today"}
          ]
        }
      }
    },
    {
      "name": "Get Upcoming Tournaments",
      "request": {
        "method": "GET",
        "header": [],
        "url": {
          "raw": "http://localhost/index.php?route=api/tournaments/upcoming",
          "protocol": "http",
          "host": ["localhost"],
          "path": ["index.php"],
          "query": [
            {"key": "route", "value": "api/tournaments/upcoming"}
          ]
        }
      }
    }
  ]
}
```

Then import it in Postman: File → Import → Select JSON file

---

## Common Issues

### 1. "Connection Refused"
- **Solution:** Make sure Docker containers are running:
  ```bash
  docker-compose up -d
  ```

### 2. "404 Not Found"
- **Solution:** Check the route parameter is correct: `?route=api/matches/today`

### 3. "Invalid CSRF Token"
- **Solution:** Get a fresh CSRF token from the browser after logging in

### 4. "CORS Error" (if testing from different origin)
- **Solution:** The API doesn't have CORS headers. Test from `http://localhost` or add CORS headers in `BaseController::json()`

---

## Quick Test Checklist

- [ ] Postman installed
- [ ] Docker containers running
- [ ] Application accessible at `http://localhost`
- [ ] GET `/api/matches/today` returns JSON
- [ ] GET `/api/tournaments/upcoming` returns JSON
- [ ] Response format matches expected structure

---

## Tips

1. **Save Responses:** Use Postman's "Save Response" to compare results
2. **Use Tests:** Add tests in Postman to validate response structure
3. **Environment Switching:** Create different environments for dev/staging/prod
4. **Pre-request Scripts:** Automate CSRF token retrieval if needed

---

## Example Postman Test Script

Add this to the "Tests" tab in Postman:

```javascript
// Test response status
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

// Test response is JSON
pm.test("Response is JSON", function () {
    pm.response.to.be.json;
});

// Test response structure
pm.test("Response has matches array", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('matches');
    pm.expect(jsonData.matches).to.be.an('array');
});
```

---

**Need Help?** Check the application logs or browser console for detailed error messages.

