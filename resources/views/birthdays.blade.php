<!DOCTYPE html>
<html>
<head>
    <title>🎉 Employee Birthday Celebrations! 🎂</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap');

        body {
            background: linear-gradient(135deg, #ffd1ff 0%, #fae8ff 50%, #e8f5ff 100%);
            font-family: 'Nunito', sans-serif;
            position: relative;
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
        }

        /* Animated background elements */
        .confetti {
            position: fixed;
            width: 10px;
            height: 10px;
            background-color: #ff6b6b;
            position: fixed;
            animation: confetti-fall 8s linear infinite;
            z-index: -1;
        }

        @keyframes confetti-fall {
            0% { transform: translateY(-100vh) rotate(0deg); }
            100% { transform: translateY(100vh) rotate(720deg); }
        }

        .birthday-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
        }

        .page-title {
            text-align: center;
            color: #ff4d8d;
            margin: 40px 0;
            font-size: 48px;
            text-shadow: 3px 3px 0px rgba(255,77,141,0.2);
            position: relative;
            padding-bottom: 20px;
            animation: title-bounce 1s ease-out;
        }

        @keyframes title-bounce {
            0% { transform: translateY(-50px); opacity: 0; }
            50% { transform: translateY(20px); }
            100% { transform: translateY(0); opacity: 1; }
        }

        .page-title::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 200px;
            height: 6px;
            background: linear-gradient(90deg, #ff6b6b, #4a90e2, #ff4d8d);
            border-radius: 3px;
            animation: rainbow 3s linear infinite;
        }

        @keyframes rainbow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .month-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            padding: 20px;
            animation: fade-in 0.8s ease-out;
        }

        @keyframes fade-in {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .month-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            animation: card-pop 0.5s ease-out;
            border: 3px solid transparent;
        }

        .month-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            border-color: #ff4d8d;
        }

        @keyframes card-pop {
            0% { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        .month-header {
            background: linear-gradient(135deg, #ff4d8d 0%, #ff6b6b 100%);
            color: white;
            padding: 25px;
            font-size: 26px;
            font-weight: bold;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }

        .month-header::before {
            content: "🎉";
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 24px;
            animation: swing 2s infinite;
        }

        .month-header::after {
            content: "🎈";
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 24px;
            animation: swing 2s infinite reverse;
        }

        @keyframes swing {
            0%, 100% { transform: translateY(-50%) rotate(-10deg); }
            50% { transform: translateY(-50%) rotate(10deg); }
        }

        .employee-item {
            display: flex;
            align-items: center;
            padding: 20px;
            background: rgba(255, 255, 255, 0.8);
            margin: 15px;
            border-radius: 15px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .employee-item:hover {
            transform: translateX(10px);
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 5px 15px rgba(255,77,141,0.2);
        }

        .employee-item:hover::before {
            content: "🎉";
            position: absolute;
            right: 15px;
            animation: pop-in 0.5s ease-out;
        }

        @keyframes pop-in {
            0% { transform: scale(0) rotate(-180deg); opacity: 0; }
            100% { transform: scale(1) rotate(0deg); opacity: 1; }
        }

        .profile-image {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            margin-right: 20px;
            object-fit: cover;
            border: 4px solid #ff4d8d;
            box-shadow: 0 5px 15px rgba(255,77,141,0.3);
            transition: all 0.3s ease;
        }

        .profile-placeholder {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ff4d8d 0%, #ff6b6b 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            font-size: 28px;
            color: white;
            border: 4px solid #ff4d8d;
            box-shadow: 0 5px 15px rgba(255,77,141,0.3);
            transition: all 0.3s ease;
        }

        .employee-item:hover .profile-image,
        .employee-item:hover .profile-placeholder {
            transform: scale(1.1) rotate(5deg);
        }

        .employee-name {
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .employee-item:hover .employee-name {
            color: #ff4d8d;
            transform: translateX(5px);
        }

        .birthday-date {
            font-size: 16px;
            color: #666;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .birthday-date::before {
            content: "🎂";
            font-size: 20px;
            animation: cake-bounce 1s infinite;
        }

        @keyframes cake-bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .no-birthdays {
            text-align: center;
            color: #666;
            padding: 40px;
            font-style: italic;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 15px;
            border: 3px dashed #ff4d8d;
            margin: 15px;
            transition: all 0.3s ease;
        }

        .no-birthdays:hover {
            background: rgba(255, 255, 255, 0.95);
            transform: scale(1.02);
        }

        .home-button {
            position: fixed;
            top: 20px;
            left: 20px;
            padding: 15px 30px;
            background: linear-gradient(135deg, #ff4d8d 0%, #ff6b6b 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            box-shadow: 0 5px 20px rgba(255,77,141,0.3);
            transition: all 0.3s ease;
            font-weight: 700;
            font-size: 16px;
            z-index: 1000;
        }

        .home-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255,77,141,0.4);
            background: linear-gradient(135deg, #ff6b6b 0%, #ff4d8d 100%);
        }

        /* Add dynamic confetti */
        @keyframes confetti-fall {
            0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
        }

        .confetti {
            position: fixed;
            width: 10px;
            height: 10px;
            background-color: #ff4d8d;
            position: fixed;
            animation: confetti-fall 8s linear infinite;
            z-index: -1;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .month-grid {
                grid-template-columns: 1fr;
                padding: 10px;
            }

            .birthday-container {
                padding: 10px;
            }

            .page-title {
                font-size: 36px;
                margin-top: 80px;
            }

            .home-button {
                padding: 12px 24px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <script>
        // Create confetti elements
        function createConfetti() {
            const colors = ['#ff4d8d', '#ff6b6b', '#4a90e2', '#ffd700'];
            for (let i = 0; i < 50; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.animationDelay = Math.random() * 5 + 's';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                document.body.appendChild(confetti);
            }
        }
        createConfetti();
    </script>

    <a href="{{ route('home') }}" class="home-button">← Back to Home</a>
    <div class="birthday-container">
        <h1 class="page-title">🎉 Birthday Celebrations! 🎉</h1>

        <div class="month-grid">
            @foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                <div class="month-card">
                    <div class="month-header">
                        {{ $month }}
                    </div>
                    <div class="month-content">
                        @if(isset($employees[$month]) && $employees[$month]->count() > 0)
                            <div class="employee-list">
                                @foreach($employees[$month] as $employee)
                                    <div class="employee-item">
                                        @if($employee->profile)
                                            <img src="{{ asset('storage/' . $employee->profile) }}"
                                                 alt="Profile"
                                                 class="profile-image">
                                        @else
                                            <div class="profile-placeholder">
                                                {{ substr($employee->first_name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="employee-info">
                                            <div class="employee-name">
                                                {{ $employee->first_name }} {{ $employee->last_name }}
                                            </div>
                                            <div class="birthday-date">
                                                {{ $employee->birth_day }} {{ $month }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="no-birthdays">
                                No birthdays this month... yet! 🎈
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
