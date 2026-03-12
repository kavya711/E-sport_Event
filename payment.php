<?php
session_start();
if (!isset($_SESSION["user"])) {
    // For demo purposes, we will allow access but normally require login
    // header("Location: login.php?need_login=1");
    // exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Checkout | Elite Arena</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #0a0a0f;
            --bg-card: rgba(20, 20, 30, 0.7);
            --primary: #6366f1;
            --primary-glow: rgba(99, 102, 241, 0.4);
            --accent: #ec4899;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border: rgba(255, 255, 255, 0.1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(99, 102, 241, 0.15), transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(236, 72, 153, 0.15), transparent 25%);
        }

        .checkout-container {
            display: flex;
            flex-direction: row;
            width: 100%;
            max-width: 1000px;
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            margin: 2rem;
        }

        /* Summary Section */
        .summary-section {
            flex: 1;
            background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0) 100%);
            padding: 3rem;
            border-right: 1px solid var(--border);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 3rem;
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
        }

        .brand-logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 12px;
            display: grid;
            place-items: center;
            box-shadow: 0 0 20px var(--primary-glow);
        }

        .plan-details h2 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(to right, #fff, var(--text-muted));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .plan-details p {
            color: var(--text-muted);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .price-display {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 2rem;
            display: flex;
            align-items: baseline;
            gap: 0.5rem;
        }

        .price-display span {
            font-size: 1rem;
            color: var(--text-muted);
            font-weight: 400;
        }

        .feature-list {
            list-style: none;
        }

        .feature-list li {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            color: var(--text-main);
        }

        .feature-list li::before {
            content: "✓";
            color: var(--accent);
            font-weight: bold;
            background: rgba(236, 72, 153, 0.1);
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            font-size: 0.8rem;
        }

        /* Payment Section */
        .payment-section {
            flex: 1.2;
            padding: 3rem;
        }

        .payment-section h3 {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            font-weight: 600;
        }

        .payment-methods {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .method-btn {
            flex: 1;
            padding: 1rem;
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: var(--text-main);
            font-family: inherit;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .method-btn.active {
            border-color: var(--primary);
            background: rgba(99, 102, 241, 0.1);
            box-shadow: 0 0 20px var(--primary-glow);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-row {
            display: flex;
            gap: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        input {
            width: 100%;
            padding: 1rem;
            background: rgba(0,0,0,0.2);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: white;
            font-family: inherit;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

        .pay-btn {
            width: 100%;
            padding: 1.2rem;
            background: linear-gradient(135deg, var(--primary), #4f46e5);
            border: none;
            border-radius: 12px;
            color: white;
            font-family: inherit;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            margin-top: 1rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
            position: relative;
            overflow: hidden;
        }

        .pay-btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.2), transparent);
            transform: skewX(-20deg);
            transition: 0.5s;
        }

        .pay-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(99, 102, 241, 0.4);
        }

        .pay-btn:hover::after {
            left: 150%;
        }
        
        .secure-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1.5rem;
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        @media (max-width: 768px) {
            .checkout-container {
                flex-direction: column;
            }
            .summary-section {
                border-right: none;
                border-bottom: 1px solid var(--border);
            }
        }
    </style>
</head>
<body>

    <div class="checkout-container">
        <!-- Summary Side -->
        <div class="summary-section">
            <a href="index.php" style="text-decoration:none; color:inherit;">
                <div class="brand">
                    <div class="brand-logo">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <span>Elite Arena</span>
                </div>
            </a>

            <div class="plan-details">
                <h2>Elite Pass Registration</h2>
                <p>Unlock premium tournament slots, priority support, and exclusive custom room access for your squad.</p>
                
                <div class="price-display">
                    ₹499 <span>/ tournament</span>
                </div>

                <ul class="feature-list">
                    <li>Guaranteed slot in Tier-1 Scrims</li>
                    <li>Priority anti-cheat review</li>
                    <li>Highlight reel inclusion</li>
                    <li>Exclusive Discord role</li>
                </ul>
            </div>
        </div>

        <!-- Payment Side -->
        <div class="payment-section">
            <h3>Payment Details</h3>
            
            <div class="payment-methods">
                <button class="method-btn active" type="button">Credit Card</button>
                <button class="method-btn" type="button">UPI / Wallet</button>
            </div>

            <form action="payment.php" method="POST" id="payment-form">
                <?php if($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                    <div style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid rgba(16, 185, 129, 0.2);">
                        Payment Successful! Redirecting to dashboard...
                        <script>
                            setTimeout(() => { window.location.href = 'index.php?payment=success'; }, 3000);
                        </script>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="name">Cardholder Name</label>
                    <input type="text" id="name" name="name" placeholder="John Doe" required>
                </div>

                <div class="form-group">
                    <label for="card">Card Number</label>
                    <input type="text" id="card" name="card" placeholder="0000 0000 0000 0000" maxlength="19" required>
                </div>

                <div class="form-row">
                    <div class="form-group" style="flex: 1;">
                        <label for="expiry">Expiry Date</label>
                        <input type="text" id="expiry" name="expiry" placeholder="MM/YY" maxlength="5" required>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label for="cvc">CVC</label>
                        <input type="text" id="cvc" name="cvc" placeholder="123" maxlength="4" required>
                    </div>
                </div>

                <button type="submit" class="pay-btn">Pay ₹499 securely</button>
                
                <div class="secure-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    Payments are 256-bit encrypted
                </div>
            </form>
        </div>
    </div>

    <script>
        // Simple input formatting for demo
        document.getElementById('card').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            let formattedValue = '';
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) formattedValue += ' ';
                formattedValue += value[i];
            }
            e.target.value = formattedValue;
        });

        document.getElementById('expiry').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });

        // Method toggle
        const btns = document.querySelectorAll('.method-btn');
        btns.forEach(btn => {
            btn.addEventListener('click', () => {
                btns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            });
        });
    </script>
</body>
</html>
