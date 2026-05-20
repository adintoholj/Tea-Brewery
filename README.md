# Tea-Brewery
# BuyTeaCraft

## How to Run

* Navigate to the project directory: `cd ....`.


* Start the application: `docker compose up -d`.


* Access the website in your browser: `http://localhost:8080`.



## Docker Management

* Check active containers and ports: `docker ps`.


* Stop the application when finished for the day: `docker compose down`.


* Restart the application: `docker compose up -d`.


* Rebuild the container: Run `docker compose down` followed by `docker compose up -d --build`.


* Destroy the database volume: Run `docker compose down -v` followed by `docker compose up -d`.



## Database Access

* Login to the database terminal: `docker exec -it BuyTeaCraft_db mysql -u root -ppassword`.


* Select the database: `USE BuyTeaCraft_db;`.


* View all registered users: `SELECT * FROM users;`.


* View all teas, including custom AJAX additions: `SELECT * FROM teas;`.


* Insert a custom test tea: `INSERT INTO teas (name, category, description, price) VALUES ('Special', 'Black Tea', 'desc', 99.99);`.


* Exit the database terminal: `exit;`.
