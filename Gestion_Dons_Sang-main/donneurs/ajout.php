<?php 
    session_start();

    require "../config/connexion.php";
    require "../auth/auth_check.php";

    check_authorization(['Admin', 'Secrétaire']);

    $message = "";
    $messageColor = "text-indigo-600";

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $check = "SELECT cin FROM donneurs where cin = :cin";
        $checkStmt = $pdo->prepare($check);
        $checkStmt->bindParam(":cin", $_POST['cin']);
        $checkStmt->execute();

        $exists = $checkStmt->fetch(PDO::FETCH_ASSOC);

        $reqVille = "SELECT ville from centres_collecte where id_centre = :id_centre";
        $villeStmt = $pdo->prepare($reqVille);
        $villeStmt->bindParam(":id_centre", $_POST['id_centre']);
        $villeStmt->execute();
        $ville = $villeStmt->fetch(PDO::FETCH_ASSOC);
        if (!$ville){
            $_SESSION['message'] = "Error: City doesn't exist";
            $_SESSION['messageType'] = "error";
        } else{

            if (!$exists){
                $sql = "INSERT INTO donneurs (nom, cin, groupe_sanguin, rhesus, ville) VALUES (:nom, :cin, :groupe_sanguin, :rhesus, :ville)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":nom", $_POST['name']);
                $stmt->bindParam(":cin", $_POST['cin']);
                $stmt->bindParam(":groupe_sanguin", $_POST['groupe_sanguin']);
                $stmt->bindParam(":rhesus", $_POST['rhesus']);
                $stmt->bindParam(":ville", $ville['ville']);

                if ($stmt->execute()){
                    $_SESSION['message'] = "Donor was added successfully!";
                    $_SESSION['messageType'] = "success";
                } else {
                    $_SESSION['message'] = "Error: adding donor!";
                }
            } else {
                $_SESSION['message'] = "Error: Donor already exists!";
            }
        }
        header('Location: ajout.php');
        exit();
    }

    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $msgType = $_SESSION['messageType'] ?? 'error';
        $messageColor = $msgType === 'success' ? 'text-emerald-600 bg-emerald-50 border-emerald-200' : 'text-rose-600 bg-rose-50 border-rose-200';
        unset($_SESSION['message'], $_SESSION['messageType']);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/animation.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Add Donor - AhmedBahri & Thamza</title>
</head>
<body class="bg-slate-100 font-sans antialiased min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-72 bg-slate-900 text-slate-300 flex flex-col justify-between hidden md:flex shadow-xl">
        <div>
            <div class="h-20 flex items-center px-6 bg-slate-950 border-b border-slate-800">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-violet-500 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-indigo-500/35 mr-3">
                    <i class="fa-solid fa-droplet"></i>
                </div>
                <div>
                    <h1 class="text-white font-bold text-base tracking-wide">AhmedBahri</h1>
                    <span class="text-xs text-indigo-400 font-medium">&amp; Thamza Blood Bank</span>
                </div>
            </div>

            <nav class="p-4 space-y-2">
                <p class="px-4 text-xs font-semibold uppercase tracking-wider text-slate-500 mb-3">Menu</p>
                <a href="../dashboard.php" class="flex items-center px-4 py-3 rounded-xl hover:bg-slate-800 hover:text-white transition-all text-slate-400 font-medium">
                    <i class="fa-solid fa-chart-pie w-6"></i> Dashboard
                </a>
                <a href="ajout.php" class="flex items-center px-4 py-3 rounded-xl bg-indigo-600 text-white font-medium shadow-lg shadow-indigo-600/30 transition-all">
                    <i class="fa-solid fa-user-plus w-6"></i> Add Donor
                </a>
                <a href="../dons/ajout.php" class="flex items-center px-4 py-3 rounded-xl hover:bg-slate-800 hover:text-white transition-all text-slate-400 font-medium">
                    <i class="fa-solid fa-hand-holding-medical w-6"></i> Add Donation
                </a>
                <a href="liste.php" class="flex items-center px-4 py-3 rounded-xl hover:bg-slate-800 hover:text-white transition-all text-slate-400 font-medium">
                    <i class="fa-solid fa-users w-6"></i> Donors List
                </a>
                <a href="../dons/validate.php" class="flex items-center px-4 py-3 rounded-xl hover:bg-slate-800 hover:text-white transition-all text-slate-400 font-medium">
                    <i class="fa-solid fa-clipboard-check w-6"></i> Validate Donations
                </a>
                <a href="../dons/transfusion.php" class="flex items-center px-4 py-3 rounded-xl hover:bg-slate-800 hover:text-white transition-all text-slate-400 font-medium">
                    <i class="fa-solid fa-heart-pulse w-6"></i> Transfusions
                </a>
            </nav>
        </div>

        <div class="p-4 bg-slate-950 border-t border-slate-800 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-bold">
                    <?php echo strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)); ?>
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-semibold text-white truncate"><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></p>
                    <p class="text-xs text-indigo-400"><?php echo htmlspecialchars($_SESSION['role'] ?? 'Admin'); ?></p>
                </div>
            </div>
            <a href="../auth/logout.php" title="Logout" class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-red-600 hover:text-white text-slate-400 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0">
        <header class="h-20 bg-white border-b border-slate-200 px-6 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <div class="flex items-center space-x-4">
                <a href="../dashboard.php" class="text-slate-500 hover:text-indigo-600 font-semibold text-sm transition-colors">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Back to Dashboard
                </a>
            </div>
            <a href="../auth/logout.php" class="md:hidden px-3 py-1.5 rounded-lg bg-red-500 hover:bg-red-600 text-white text-xs font-bold">
                <i class="fa-solid fa-right-from-bracket mr-1"></i> Logout
            </a>
        </header>

        <main class="flex-1 p-6 flex items-center justify-center">
            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-lg w-full max-w-lg">
                <div class="text-center mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl mx-auto mb-3 shadow-inner">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <h1 class="text-2xl font-extrabold text-slate-800">Add Blood Donor</h1>
                    <p class="text-xs text-slate-400 mt-1">Register a new donor into the database</p>
                </div>

                <form action="ajout.php" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">CIN Number</label>
                        <input required type="text" name="cin" id="cin" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm" placeholder="Enter CIN number">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Full Name</label>
                        <input required type="text" name="name" id="name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm" placeholder="Donor full name">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Center ID</label>
                        <input required type="number" name="id_centre" id="id_centre" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm" placeholder="Center ID">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Blood Group</label>
                            <select required name="groupe_sanguin" id="groupe_sanguin" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm bg-white">
                                <option value="">Select Group</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="AB">AB</option>
                                <option value="O">O</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Rhesus</label>
                            <select required name="rhesus" id="rhesus" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm bg-white">
                                <option value="">Select Rhesus</option>
                                <option value="+">+</option>
                                <option value="-">-</option>
                            </select>
                        </div>
                    </div>

                    <?php if (!empty($message)): ?>
                        <div id="message" class="p-3 rounded-xl border text-sm text-center font-semibold <?php echo $messageColor; ?>">
                            <?php echo htmlspecialchars($message); ?>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="w-full py-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-lg shadow-indigo-600/30 transition-all cursor-pointer">
                        Add Donor
                    </button>
                </form>
            </div>
        </main>
    </div>

    <script>
        setTimeout(() => {
            const msg = document.getElementById("message");
            if (msg) msg.style.display = 'none';
        }, 5000);
    </script>
</body>
</html>
