<!-- <?php 
$currentPage = 'page_name';
include 'includes/sidebar.php';
?> -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Seto Semero Kebele</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <style>
    * {
        /* box-sizing: border-box; */
        margin: 0;
        padding: 0;
    }

    body {
        font-family: "Poppins", sans-serif;
        background: #f5f5f5;
        /* smoke white */
        min-height: 100vh;
        overflow-x: hidden;
    }

    /* Add an attractive shadow to the main container */
    .page-container {
        margin: 32px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18), 0 1.5px 6px 0 rgba(30, 64, 175, 0.08);
        border-radius: 24px;
        background: #fff;
    }

    .navbar {
        background-color: #f3f6fd;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
    }

    .logo {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e40af;
    }

    .nav-link {
        color: #4b5563;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .nav-link:hover {
        color: #1e40af;
    }

    .btn-register {
        background-color: #1e40af;
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 9999px;
        transition: background-color 0.3s ease;
    }

    .btn-register:hover {
        background-color: #3b82f6;
    }

    .hero-content {
        max-width: 600px;
        margin: 0 auto;
        font-size: 0.95rem;
        /* Minimized font size */
    }

    .heading {
        font-size: 2rem;
        /* Smaller heading */
        font-weight: 700;
        color: #1f2937;
        animation: fadeInUp 1s ease-out;
    }

    .sub-heading {
        font-size: 1rem;
        /* Smaller subheading */
        color: #4b5563;
        animation: fadeInUp 1.2s ease-out;
    }

    .btn-primary {
        background-color: #1e40af;
        color: white;
        font-weight: 600;
        padding: 0.75rem 2rem;
        border-radius: 9999px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(30, 64, 175, 0.3);
    }

    .btn-primary:hover {
        background-color: #3b82f6;
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
    }

    .card {
        background-color: white;
        border-radius: 0.75rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .step-card {
        background-color: #1e40af;
        border-radius: 9999px;
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s ease;
    }

    .step-card:hover {
        transform: scale(1.05);
    }

    .footer {
        background-color: #1f2937;
        color: white;
    }

    /* Make the hero image smaller */
    .hero-section-img {
        max-width: 450px;
        width: 90%;
        height: auto;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .heading {
            font-size: 1.8rem;
        }

        .sub-heading {
            font-size: 1rem;
        }

        .nav-link {
            display: none;
        }

        .btn-register {
            padding: 0.25rem 1rem;
        }
    }

    .step-card svg {
        color: #fff;
        fill: #fff;
    }
    </style>
</head>

<body>
    <div class="page-container">
        <!-- Navbar -->
        <nav class="navbar py-4 px-6 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a2 2 0 012-2h2a2 2 0 012 2v5m-4 0h4">
                    </path>
                </svg>
                <span class="logo">Seto Semero Kebele</span>
            </div>
            <div class="flex space-x-6 items-center">
                <a href="#home" class="nav-link">Home</a>
                <a href="#our-services" class="nav-link">Services</a>
                <a href="#our-contact" class="nav-link">Contact</a>
                <a href="login.php" class="nav-link">Login</a>
                <a href="register.php" class="btn-register">Register</a>
            </div>
        </nav>

        <!-- Hero Section -->
        <section id="home"
            class="pt-24 pb-12 flex flex-col md:flex-row items-center justify-center text-center md:text-left">
            <div class="hero-content md:mr-8">
                <h1 class="heading mb-4">
                    Welcome to Seto Semero Kebele's Online Appointment System
                </h1>
                <p class="sub-heading mb-8">
                    Schedule your appointments easily and efficiently with our online
                    booking system. Save time and manage your visits seamlessly.
                </p>
                <a href="#book-appointment">
                    <button class="btn-primary">Book an Appointment</button>
                </a>
            </div>
            <div class="md:ml-8 mt-8 md:mt-0">
                <img src="image1.png" alt="" class="hero-section-img">
            </div>
        </section>

        <!-- Services Section -->
        <section id="our-services" class="py-12 px-6 bg-white">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-2xl font-semibold text-center mb-8">Our Services</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="card p-6 text-center">
                        <svg class="w-12 h-12 mx-auto mb-4 text-blue-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                        <h3 class="font-semibold text-lg">ID Card Services</h3>
                        <p class="text-gray-600">
                            Apply for new ID cards or renew existing ones with ease.
                        </p>
                    </div>
                    <div class="card p-6 text-center">
                        <svg class="w-12 h-12 mx-auto mb-4 text-blue-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        <h3 class="font-semibold text-lg">Housing Services</h3>
                        <p class="text-gray-600">
                            Handle housing registration and related documentation.
                        </p>
                    </div>
                    <div class="card p-6 text-center">
                        <svg class="w-12 h-12 mx-auto mb-4 text-blue-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <h3 class="font-semibold text-lg">Document Authentication</h3>
                        <p class="text-gray-600">
                            Get your documents verified and authenticated officially.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="py-12 px-6">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-2xl font-semibold text-center mb-8">How It Works</h2>
                <div class="flex justify-center space-x-10">
                    <div class="flex flex-col items-center">
                        <div class="step-card mb-2">
                            <!-- Register Icon -->
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-800">Register</p>
                        <span class="text-sm text-gray-500 text-center mt-1">Create your account with basic information
                        </span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="step-card mb-2">
                            <!-- Book Icon -->
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M6 2a1 1 0 00-1 1v1H4a1 1 0 00-1 1v10a1 1 0 001 1h12a1 1 0 001-1V5a1 1 0 00-1-1h-1V3a1 1 0 00-1-1H6zm1 0h8v1H7V2zm9 5v8H4V7h12z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-800">Book</p>
                        <span class="text-sm text-gray-500 text-center mt-1">Select service and preferred time
                            slot</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="step-card mb-2">
                            <!-- Confirm Icon -->
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 00-1.414 0L9 11.586 6.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l7-7a1 1 0 00-1.414-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-800">Confirm</p>
                        <span class="text-sm text-gray-500 text-center mt-1">Receive confirmation and reminders</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="step-card mb-2">
                            <!-- Visit Icon -->
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-800">Visit</p>
                        <span class="text-sm text-gray-500 text-center mt-1">Visit the office at scheduled time</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer id="our-contact" class=" footer py-6 px-6 text-center">
            <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Contact Information</h3>
                    <p>
                        <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.773-1.548a1 1 0 011.06-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z">
                            </path>
                        </svg>
                        +251 11 234 5678
                    </p>
                    <p>
                        <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                        </svg>
                        info@setosemero.gov.et
                    </p>
                    <p>
                        <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Seto Semero,JImma, Ethiopia
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-2">Office Hours</h3>
                    <p>Monday - Friday: 2:30 - 11:00</p>
                    <p>Saturday - Sunday: Closed</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-2">Follow Us</h3>
                    <div class="flex justify-center space-x-4">
                        <a href="#"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M22.675 0H1.325C.593 0 0 .593 0 1.325v21.351C0 23.407.593 24 1.325 24h11.494v-9.294H9.691v-3.621h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.621h-3.12V24h6.116c.732 0 1.325-.593 1.325-1.324V1.325C24 .593 23.407 0 22.675 0z" />
                            </svg></a>
                        <a href="#"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482A13.87 13.87 0 011.67 3.899a4.924 4.924 0 001.518 6.573 4.903 4.903 0 01-2.228-.616v.061a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg></a>
                    </div>
                </div>
            </div>
            <p class="mt-4 text-sm">
                © 2025 Seto Semero Kebele. All rights reserved.
            </p>
        </footer>
    </div>

    <script>
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener("click", function(e) {
            e.preventDefault();
            document.querySelector(this.getAttribute("href")).scrollIntoView({
                behavior: "smooth",
            });
        });
    });

    // Button click animation
    document.querySelectorAll(".btn-primary").forEach((button) => {
        button.addEventListener("click", () => {
            button.style.transform = "scale(0.95)";
            setTimeout(() => {
                button.style.transform = "scale(1)";
            }, 100);
        });
    });
    </script>
</body>

</html>