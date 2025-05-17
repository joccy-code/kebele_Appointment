<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seto Semero - Book Appointment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fb;
            display: flex;
            min-height: 100vh;
        }
        
        .main-content {
            flex-grow: 1;
            padding: 30px;
            margin-left: 250px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .heading {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .notification-icon {
            background-color: #3b82f6;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            cursor: pointer;
        }
        
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .user-info {
            display: flex;
            flex-direction: column;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 16px;
            color: #1f2937;
        }
        
        .user-role {
            color: #6b7280;
            font-size: 14px;
        }
        
        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            position: relative;
        }
        
        .progress-line {
            position: absolute;
            top: 25px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #dee2e6;
            z-index: 0;
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
            flex: 1;
        }
        
        .step-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #dee2e6;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        .step.active .step-number {
            background-color: #3B71CA;
        }
        
        .step-text {
            color: #6c757d;
            font-size: 14px;
        }
        
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .service-card {
            background-color: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            cursor: pointer;
            transition: box-shadow 0.3s, border-color 0.3s;
        }
        
        .service-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border-color: #3B71CA;
        }
        
        .service-card.selected {
            border-color: #3B71CA;
            box-shadow: 0 5px 15px rgba(59, 113, 202, 0.2);
        }
        
        .service-icon {
            width: 40px;
            height: 40px;
            border-radius: 5px;
            background-color: #e7f1ff;
            color: #3B71CA;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }
        
        .service-icon.house {
            background-color: #f8f9fa;
            color: #495057;
        }
        
        .service-icon.transfer {
            background-color: #f8f9fa;
            color: #495057;
        }
        
        .service-icon.inquiries {
            background-color: #f8f9fa;
            color: #495057;
        }
        
        .service-title {
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .service-description {
            color: #6c757d;
            font-size: 14px;
        }
        
        .footer {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 500;
            cursor: pointer;
        }
        
        .btn-outline {
            background-color: transparent;
            border: 1px solid #dee2e6;
            color: #6c757d;
        }
        
        .btn-primary {
            background-color: #3B71CA;
            border: none;
            color: white;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .helper-icon {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #ffffff;
            color: #3B71CA;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php 
    $currentPage = 'appointment';
    include 'includes/sidebar.php';
    ?>
    
    <div class="main-content">
        <div class="header">
            <div class="heading">Book Appointment</div>
            <div class="user-profile">
                <i class="far fa-bell notification-icon"></i>
                <img src="https://randomuser.me/api/portraits/women/12.jpg" alt="Profile" class="avatar">
                <div class="user-info">
                    <div class="user-name">Sarah Johnson</div>
                    <div class="user-role">Resident</div>
                </div>
            </div>
        </div>
        
        <div class="progress-steps">
            <div class="progress-line"></div>
            <div class="step active">
                <div class="step-number">1</div>
                <div class="step-text">Select Service</div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-text">Select Date</div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-text">Select Time</div>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <div class="step-text">Confirm</div>
            </div>
        </div>
        
        <div class="services-grid">
            <div class="service-card" data-service="birth-certificate">
                <div class="service-icon">
                    <i class="far fa-file-alt"></i>
                </div>
                <div class="service-title">Birth Certificate</div>
                <div class="service-description">Request a new birth certificate or get a copy</div>
            </div>
            <div class="service-card" data-service="kebele-id">
                <div class="service-icon">
                    <i class="far fa-id-card"></i>
                </div>
                <div class="service-title">Kebele ID Card</div>
                <div class="service-description">Apply for or renew your Kebele ID card</div>
            </div>
            <div class="service-card" data-service="marriage-certificate">
                <div class="service-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <div class="service-title">Marriage Certificate</div>
                <div class="service-description">Get your marriage certificate</div>
            </div>
            <div class="service-card" data-service="house-request">
                <div class="service-icon house">
                    <i class="fas fa-home"></i>
                </div>
                <div class="service-title">Kebele House Request</div>
                <div class="service-description">Apply for Kebele housing services</div>
            </div>
            <div class="service-card" data-service="ownership-transfer">
                <div class="service-icon transfer">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div class="service-title">House Ownership Transfer</div>
                <div class="service-description">Transfer house ownership</div>
            </div>
            <div class="service-card" data-service="general-inquiries">
                <div class="service-icon inquiries">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div class="service-title">General Inquiries</div>
                <div class="service-description">Get help with general questions</div>
            </div>
        </div>
        
        <div class="footer">
            <button class="btn btn-outline">Cancel</button>
            <button class="btn btn-primary">
                Continue
                <div class="helper-icon">?</div>
            </button>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const serviceCards = document.querySelectorAll('.service-card');
            let selectedService = null;
            
            // Function to handle service selection
            function selectService(card) {
                // Remove selected class and styles from all cards
                serviceCards.forEach(c => {
                    c.classList.remove('selected');
                    c.style.transform = 'translateY(0)';
                    c.style.borderColor = '#e5e7eb';
                    c.style.boxShadow = 'none';
                });
                
                // Add selected class and styles to clicked card
                card.classList.add('selected');
                card.style.transform = 'translateY(-2px)';
                card.style.borderColor = '#3b82f6';
                card.style.boxShadow = '0 5px 15px rgba(59, 130, 246, 0.2)';
                
                // Update selected service
                selectedService = card.dataset.service;
                
                // Enable continue button if a service is selected
                const continueBtn = document.querySelector('.btn-primary');
                if (continueBtn) {
                    continueBtn.disabled = false;
                }
            }
            
            // Add click event listeners to all service cards
            serviceCards.forEach(card => {
                // Click handler
                card.addEventListener('click', () => selectService(card));
                
                // Hover effects
                card.addEventListener('mouseenter', () => {
                    if (!card.classList.contains('selected')) {
                        card.style.transform = 'translateY(-2px)';
                        card.style.borderColor = '#3b82f6';
                        card.style.boxShadow = '0 5px 15px rgba(59, 130, 246, 0.1)';
                    }
                });
                
                card.addEventListener('mouseleave', () => {
                    if (!card.classList.contains('selected')) {
                        card.style.transform = 'translateY(0)';
                        card.style.borderColor = '#e5e7eb';
                        card.style.boxShadow = 'none';
                    }
                });
            });
            
            // Add click handler for continue button
            const continueBtn = document.querySelector('.btn-primary');
            if (continueBtn) {
                continueBtn.addEventListener('click', () => {
                    if (selectedService) {
                        window.location.href = `datehome.php?service=${selectedService}`;
                    }
                });
            }
        });
    </script>
</body>
</html>
