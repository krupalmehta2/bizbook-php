<!DOCTYPE html>
<html>
<head>
    <title>BizBook - Local Business Management System</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f6f9;
            color: #333;
        }

        header {
            background: linear-gradient(90deg, #007bff, #00c6ff);
            color: white;
            padding: 60px 20px;
            text-align: center;
        }
        header h1 {
            margin: 0;
            font-size: 48px;
        }
        header p {
            font-size: 20px;
            margin-top: 10px;
        }

        nav {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            display: inline-block;
            margin: 10px;
            padding: 15px 35px;
            font-size: 16px;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.3s;
            font-weight: bold;
        }
        .btn:hover { opacity: 0.9; }
        .admin { background: #17a2b8; color: #fff; }
        .owner { background: #ffc107; color: #212529; }
        .customer { background: #28a745; color: #fff; }

        section {
            max-width: 1000px;
            margin: 50px auto;
            padding: 0 20px;
        }
        section h2 {
            color: #007bff;
            margin-bottom: 20px;
            text-align: center;
        }
        section p {
            font-size: 16px;
            line-height: 1.8;
            text-align: center;
        }

        ul.features {
            list-style: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        ul.features li {
            background: #fff;
            margin: 10px;
            padding: 20px;
            border-radius: 8px;
            width: 250px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        footer {
            background: #222;
            color: #fff;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
        }
        footer a {
            color: #00c6ff;
            text-decoration: none;
        }
        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>BizBook</h1>
        <p>Empowering Local Businesses with Seamless Online Management</p>
    </header>

    <!-- Login / Navigation -->
    <nav>
        <a href="admin_login.php" class="btn admin">Admin Login</a>
        <a href="ownerlogin.php" class="btn owner">Owner Login</a>
        <a href="customerlogin.php" class="btn customer">Customer Login</a>
        <a href="customerregister.php" class="btn customer">Customer Register</a>
    </nav>

    <!-- About Us -->
    <section>
        <h2>About BizBook</h2>
        <p>BizBook is a professional web application developed by <strong>A2Z Clicks</strong>, a startup dedicated to supporting local businesses. 
        It provides business owners with tools to manage products, services, and table bookings efficiently, while customers can effortlessly place orders, book appointments, and leave feedback. 
        Admins oversee the entire ecosystem to maintain smooth operations.</p>
    </section>

    <!-- Features -->
    <section>
        <h2>Key Features</h2>
        <ul class="features">
            <li><strong>Role-Based Access:</strong> Admin, Owner, Customer</li>
            <li><strong>Business Management:</strong> Products, Services, Tables</li>
            <li><strong>Bookings & Orders:</strong> Real-time tracking</li>
            <li><strong>Customer Reviews:</strong> Feedback & Ratings</li>
            <li><strong>Secure & Reliable:</strong> Session-based login</li>
            <li><strong>Database Driven:</strong> MySQL backend</li>
        </ul>
    </section>

    <!-- Why BizBook -->
    <section>
        <h2>Why BizBook?</h2>
        <p>BizBook simplifies business operations for owners while offering customers an intuitive experience. 
        Our platform ensures seamless booking management, efficient communication, and transparency in service delivery. 
        Designed with modern web technologies, BizBook is secure, scalable, and user-friendly—perfect for growing startups and local enterprises.</p>
    </section>

    <!-- Contact -->
    <section>
        <h2>Contact Us</h2>
        <p><strong>A2Z Clicks</strong> – The Startup Behind BizBook</p>
        <p>Email: <a href="mailto:info@a2zclicks.com">info@a2zclicks.com</a> | Phone: +91 9876543210</p>
        <p>Visit us at: <a href="#">www.a2zclicks.com</a></p>
    </section>

    <!-- Footer -->
    <footer>
        &copy; <?php echo date('Y'); ?> BizBook | Developed by <a href="#">A2Z Clicks</a>
    </footer>

</body>
</html>
