<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Telimed</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333333;
        }

        p {
            color: #555555;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4caf50;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            color: #888888;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Welcome to Our Community!</h1>
        <p>Dear {{ $mailData['user'] }},</p>
        <p>We are Telimed to welcome you to our community. Thank you for joining us!</p>
        <p>Here are a few things you can do to get started:</p>
        <ul>
            <li>Explore our website and discover exciting features.</li>
            <li>Connect with other members and share your experiences.</li>
            <li>Stay updated on the latest news and events.</li>
        </ul>
        <p>Feel free to reach out if you have any questions or need assistance. We're here to help!</p>
        <a href="{{ $mailData['link'] }}" class="button">Get Started</a>
        <p class="footer">Best regards,<br>Telimed Team</p>
    </div>

</body>

</html>