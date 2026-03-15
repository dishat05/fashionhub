<?php
echo "<img src='game_over_banner.jpg' alt='Game Over' width='300'><br>";

session_start();

$total_questions = 5;
$score_file = 'scores.txt';
$upload_dir = 'uploads/';

// Handle name, level & avatar upload
if (isset($_POST['player_name']) && isset($_POST['level'])) {
    $_SESSION['player_name'] = trim($_POST['player_name']);
    $_SESSION['level'] = $_POST['level'];
    $_SESSION['question_num'] = 1;
    $_SESSION['score'] = 0;
    $_SESSION['message'] = '';

    // Handle avatar upload
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $avatar_name = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_dir . $avatar_name);
        $_SESSION['avatar'] = $upload_dir . $avatar_name;
    } else {
        $_SESSION['avatar'] = '';
    }

    generateQuestion();
}

// Handle answer submission
if (isset($_POST['answer'])) {
    $user_answer = intval($_POST['answer']);
    $num1 = $_SESSION['num1'];
    $num2 = $_SESSION['num2'];
    $operator = $_SESSION['operator'];

    switch ($operator) {
        case '+': $correct = $num1 + $num2; break;
        case '-': $correct = $num1 - $num2; break;
        case '*': $correct = $num1 * $num2; break;
        case '/': $correct = $num1 / $num2; break;
    }

    if ($user_answer == $correct) {
        $_SESSION['score']++;
        $_SESSION['message'] = "Correct!";
        $_SESSION['sound'] = "correct";
    } else {
        $_SESSION['message'] = "Wrong! Correct answer was $correct.";
        $_SESSION['sound'] = "wrong";
    }

    $_SESSION['question_num']++;

    if ($_SESSION['question_num'] <= $total_questions) {
        generateQuestion();
    } else {
        // Save to file with timestamp
        file_put_contents($score_file, $_SESSION['player_name'] . ":" . $_SESSION['score'] . ":" . time() . "\n", FILE_APPEND);
        header("Location: math_game_with_avatars.php");
        exit();
    }
}

// Game over & show chart
if (isset($_SESSION['question_num']) && $_SESSION['question_num'] > $total_questions) {
    $player_name = htmlspecialchars($_SESSION['player_name']);
    $player_score = $_SESSION['score'];

    echo "<h1>Well done, $player_name!</h1>";
    if ($_SESSION['avatar']) {
        echo "<img src='{$_SESSION['avatar']}' alt='Avatar' width='100'><br>";
    }
    echo "<p>Your score: $player_score / $total_questions</p>";

    // Rankings
    echo "<h2>Rankings:</h2>";
    if (file_exists($score_file)) {
        $lines = file($score_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $ranking = [];
        foreach ($lines as $line) {
            list($name, $score, $ts) = explode(':', $line);
            $ranking[] = ['name' => $name, 'score' => intval($score), 'time' => $ts];
        }
        usort($ranking, fn($a, $b) => $b['score'] - $a['score']);
        echo "<ol>";
        foreach ($ranking as $rank) {
            echo "<li>" . htmlspecialchars($rank['name']) . ": " . $rank['score'] . "</li>";
        }
        echo "</ol>";
    }

    // Chart for THIS player's scores over time
    echo "<h2>Your Scores Over Time</h2>";
    echo "<div id='chart_div' style='width: 700px; height: 400px;'></div>";

    echo "<script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>";
    echo "<script type='text/javascript'>
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Date', 'Score'],
    ";

    // Filter only this player's scores
    $player_scores = [];
    foreach ($lines as $line) {
        list($name, $score, $ts) = explode(':', $line);
        if ($name == $_SESSION['player_name']) {
            $player_scores[] = ["'" . date('Y-m-d H:i', $ts) . "'", intval($score)];
        }
    }
    foreach ($player_scores as $ps) {
        echo "[" . $ps[0] . ", " . $ps[1] . "],";
    }

    echo "]);
            var options = {
                title: 'Your Scores Over Time',
                curveType: 'function',
                legend: { position: 'bottom' }
            };
            var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>";

    session_destroy();
    echo '<p><a href="math_game_with_avatars.php">Play Again</a></p>';
    exit();
}

// Show start page
if (!isset($_SESSION['level'])) {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Math Game - Avatars & Chart</title>
    <style>
        body {
            background-image: url(bg.jpg);
            background-size: cover;
            text-align: center;
            font-family: Arial, sans-serif;
            color: #333;
        }
        .form-box {
            background: rgba(255,255,255,0.9);
            display: inline-block;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Static Banner Image -->
    <img src="math_banner.jpg" alt="Math Banner" width="300"><br><br>

    <h1>Math Game</h1>
    <h2>Enter Name, Select Difficulty & Upload Avatar</h2>

    <div class="form-box">
        <form method="post" enctype="multipart/form-data">
            <label>Name: <input type="text" name="player_name" required></label><br><br>
            <label><input type="radio" name="level" value="easy" required> Easy (1-10)</label><br>
            <label><input type="radio" name="level" value="medium"> Medium (1-50)</label><br>
            <label><input type="radio" name="level" value="hard"> Hard (1-100)</label><br><br>
            <label>Upload Avatar: <input type="file" name="avatar" accept="image/*"></label><br><br>
            <input type="submit" value="Start Game">
        </form>
    </div>
</body>
</html>
<?php
    exit();
}

// Generate question
function generateQuestion() {
    $level = $_SESSION['level'];
    $range = $level === 'easy' ? 10 : ($level === 'medium' ? 50 : 100);
    $operators = ['+', '-', '*', '/'];
    $operator = $operators[array_rand($operators)];
    do {
        $num1 = rand(1, $range);
        $num2 = rand(1, $range);
        if ($operator == '/') {
            $num2 = rand(1, $range);
            $num1 = $num2 * rand(1, $range);
        }
    } while ($operator == '/' && $num2 == 0);
    $_SESSION['num1'] = $num1;
    $_SESSION['num2'] = $num2;
    $_SESSION['operator'] = $operator;
}

$num1 = $_SESSION['num1'];
$num2 = $_SESSION['num2'];
$operator = $_SESSION['operator'];
$question_num = $_SESSION['question_num'];
$score = $_SESSION['score'];
$message = $_SESSION['message'] ?? '';
$sound = $_SESSION['sound'] ?? '';
$_SESSION['message'] = '';
$_SESSION['sound'] = '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Math Game Play</title>
    <style>
        body { text-align: center; font-family: Arial, sans-serif; }
    </style>
    <script>
        let timeLeft = 10;
        let timer = setInterval(() => {
            document.getElementById('timer').innerHTML = timeLeft;
            timeLeft--;
            if (timeLeft < 0) {
                clearInterval(timer);
                alert("Time's up!");
                window.location.href = "math_game_with_avatars.php?timeout=1";
            }
        }, 1000);

        window.onload = () => {
            <?php if ($sound == "correct"): ?>
                document.getElementById('correct').play();
            <?php elseif ($sound == "wrong"): ?>
                document.getElementById('wrong').play();
            <?php endif; ?>
        };
    </script>
</head>
<body>
    <!-- Static Logo on Gameplay Page -->
    <img src="game_logo.png" alt="Game Logo" width="150"><br><br>

    <h1>Math Game - <?php echo htmlspecialchars($_SESSION['player_name']); ?></h1>

    <!-- Show uploaded avatar if available -->
    <?php if ($_SESSION['avatar']) echo "<img src='{$_SESSION['avatar']}' alt='Avatar' width='100'><br>"; ?>

    <h2>Question <?php echo $question_num; ?> / <?php echo $total_questions; ?></h2>
    <p>Time: <span id="timer">10</span> sec</p>
    <p>What is <?php echo "$num1 $operator $num2"; ?> ?</p>

    <?php if ($message) echo "<p style='color:" . (strpos($message,'Correct')!==false?'green':'red') . ";'>$message</p>"; ?>

    <form method="post">
        <input type="number" name="answer" required>
        <input type="submit" value="Submit Answer">
    </form>
    <p>Score: <?php echo $score; ?></p>

    <audio id="correct" src="https://www.soundjay.com/button/beep-07.wav"></audio>
    <audio id="wrong" src="https://www.soundjay.com/button/beep-10.wav"></audio>
</body>
</html>

<?php
// Timeout handler
if (isset($_GET['timeout']) && $_GET['timeout'] == 1) {
    $num1 = $_SESSION['num1'];
    $num2 = $_SESSION['num2'];
    $operator = $_SESSION['operator'];
    switch ($operator) {
        case '+': $correct = $num1 + $num2; break;
        case '-': $correct = $num1 - $num2; break;
        case '*': $correct = $num1 * $num2; break;
        case '/': $correct = $num1 / $num2; break;
    }
    $_SESSION['message'] = "Time's up! Correct answer was $correct.";
    $_SESSION['sound'] = "wrong";
    $_SESSION['question_num']++;
    if ($_SESSION['question_num'] <= $total_questions) {
        generateQuestion();
        header("Location: math_game_with_avatars.php");
        exit();
    } else {
        file_put_contents($score_file, $_SESSION['player_name'] . ":" . $_SESSION['score'] . ":" . time() . "\n", FILE_APPEND);
        header("Location: math_game_with_avatars.php");
        exit();
    }
}
?>


