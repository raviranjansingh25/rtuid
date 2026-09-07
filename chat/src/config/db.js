import mysql from "mysql2/promise";

async function createConnection() {
  try {
    const connection = await mysql.createConnection({
      host: 'localhost',
      user: 'root',
      database: 'hirdle',
      password: ''
    });

    // Handle disconnects by adding an event listener
    connection.on('error', (err) => {
      if (err.code === 'PROTOCOL_CONNECTION_LOST') {
        console.error('Database connection was closed. Attempting to reconnect...');
        createConnection(); // Attempt to recreate the connection
      } else {
        console.error('Error with the database connection:', err);
      }
    });

    console.log('Connected to the database');
    return connection;

  } catch (error) {
    console.error('Error connecting to the database:', error);
    throw error;
  }
}

// Call the function to establish the initial connection
const connection1 = await createConnection();

// Export the connection for use in other parts of your application
export default connection1;
