# Anonymous Chat

### Requirements

- Web server with PHP 7.x or higher.
- MySQL database server.
- Access to phpMyAdmin or any other MySQL client.

### Installation Steps

1. **Download and prepare the files:**
   - Download all project files (`config.php`, `admin.php`, `register.php`, `login.php`, `chat.php`, `logout.php`, `admin_login.php`, `admin_logout.php`...) and place them in the root directory of your web server.

2. **Set up the database:**
   - Access your MySQL client (phpMyAdmin or another).
   - Create a new database (e.g., `chat_db`).
   - Run the following SQL script to create the necessary tables:

     ```sql
     CREATE TABLE chat_rooms (
         id INT AUTO_INCREMENT PRIMARY KEY,
         chat_hash VARCHAR(32) NOT NULL,
         password VARCHAR(255) NOT NULL,
         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
     );

     CREATE TABLE messages (
         id INT AUTO_INCREMENT PRIMARY KEY,
         chat_hash VARCHAR(32) NOT NULL,
         message TEXT NOT NULL,
         timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
     );

     CREATE TABLE invitations (
         id INT AUTO_INCREMENT PRIMARY KEY,
         invitation_link VARCHAR(32) NOT NULL,
         usage_limit INT DEFAULT 0,
         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
     );

     CREATE TABLE announcements (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
     );
     ```

3. **Configure `config.php`:**
   - Open `config.php` and set up the database connection parameters:

     ```php
     <?php
     $host = 'localhost';
     $db = 'chat_db'; // Database name
     $user = 'root'; // MySQL username
     $pass = ''; // MySQL password
     $charset = 'utf8mb4';

     $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
     $options = [
         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
         PDO::ATTR_EMULATE_PREPARES => false,
     ];

     try {
         $pdo = new PDO($dsn, $user, $pass, $options);
     } catch (PDOException $e) {
         throw new PDOException($e->getMessage(), (int)$e->getCode());
     }

     function encrypt($data) {
         return openssl_encrypt($data, 'aes-256-cbc', 'your_secret_key', 0, 'your_iv');
     }

     function decrypt($data) {
         return openssl_decrypt($data, 'aes-256-cbc', 'your_secret_key', 0, 'your_iv');
     }
     ?>
     ```

     - Replace `'your_secret_key'` and `'your_iv'` with your own keys and initialization vectors for encryption.

4. **Set up the administrator:**
   - Open `admin_login.php` and set an admin password:

     ```php
     if ($_SERVER["REQUEST_METHOD"] == "POST") {
         $password = $_POST['password'];
         if ($password == 'admin_password') { // Change 'admin_password' to your password
             session_start();
             $_SESSION['admin_logged_in'] = true;
             header("Location: admin.php");
             exit();
         } else {
             echo "Invalid password.";
         }
     }
     ```

5. **Access the admin panel:**
   - Open your web browser and go to `http://yourdomain.com/admin_login.php`.
   - Log in with the admin password you set earlier.
   - Generate invitation links and manage messages from the admin panel.

6. **Register and use the chat:**
   - Use the generated invitation links to register for the chat.
   - Log in with the generated password and start chatting.
