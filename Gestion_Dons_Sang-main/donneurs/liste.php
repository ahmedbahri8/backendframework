<?php
    session_start();
    require "../config/connexion.php";
    require "../auth/auth_check.php";

    check_authorization(["Admin", "Secrétaire"]);

    $sql = "SELECT * FROM donneurs";
    $params = [];

    if (!empty($_POST['rhesus']) || !empty($_POST['groupe_sanguin']) || !empty($_POST['ville'])){
        $sql .= ' WHERE 1=1';
        if (!empty($_POST['rhesus'])){
            $sql .= ' AND rhesus = :rhesus';
            $params[':rhesus'] = $_POST['rhesus'];
        }
        if (!empty($_POST['groupe_sanguin'])){
            $sql .= ' AND groupe_sanguin = :groupe_sanguin';
            $params[':groupe_sanguin'] = $_POST['groupe_sanguin'];
        }
        if (!empty($_POST['ville'])){
            $sql .= ' AND ville = :ville';
            $params[':ville'] = $_POST['ville'];
        }
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $donors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/animation.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Donors List - AhmedBahri & Thamza</title>
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
                <a href="ajout.php" class="flex items-center px-4 py-3 rounded-xl hover:bg-slate-800 hover:text-white transition-all text-slate-400 font-medium">
                    <i class="fa-solid fa-user-plus w-6"></i> Add Donor
                </a>
                <a href="../dons/ajout.php" class="flex items-center px-4 py-3 rounded-xl hover:bg-slate-800 hover:text-white transition-all text-slate-400 font-medium">
                    <i class="fa-solid fa-hand-holding-medical w-6"></i> Add Donation
                </a>
                <a href="liste.php" class="flex items-center px-4 py-3 rounded-xl bg-indigo-600 text-white font-medium shadow-lg shadow-indigo-600/30 transition-all">
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

        <main class="flex-1 p-6 space-y-6 overflow-y-auto">
            <!-- Filter Form -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <form method="POST" class="flex flex-col md:flex-row gap-4 items-center justify-between">
                    <div class="flex flex-wrap gap-3 w-full md:w-auto">
                        <select name="groupe_sanguin" id="groupe_sanguin" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm bg-white focus:outline-none focus:border-indigo-500">
                            <option value="">All Blood Types</option>
                            <option value="A" <?php echo (($_POST['groupe_sanguin'] ?? '') === 'A') ? 'selected' : ''; ?>>A</option>
                            <option value="B" <?php echo (($_POST['groupe_sanguin'] ?? '') === 'B') ? 'selected' : ''; ?>>B</option>
                            <option value="AB" <?php echo (($_POST['groupe_sanguin'] ?? '') === 'AB') ? 'selected' : ''; ?>>AB</option>
                            <option value="O" <?php echo (($_POST['groupe_sanguin'] ?? '') === 'O') ? 'selected' : ''; ?>>O</option>
                        </select>

                        <select name="rhesus" id="rhesus" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm bg-white focus:outline-none focus:border-indigo-500">
                            <option value="">All Rhesus</option>
                            <option value="+" <?php echo (($_POST['rhesus'] ?? '') === '+') ? 'selected' : ''; ?>>+</option>
                            <option value="-" <?php echo (($_POST['rhesus'] ?? '') === '-') ? 'selected' : ''; ?>>-</option>
                        </select>

                        <select name="ville" id="ville" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm bg-white focus:outline-none focus:border-indigo-500">
                            <option value="">All Cities</option>
                            <?php
                                $villeStmt = $pdo->query("SELECT DISTINCT ville FROM centres_collecte");
                                $villes = $villeStmt->fetchAll(PDO::FETCH_ASSOC);
                                foreach($villes as $v){
                                    $selected = (($_POST['ville'] ?? '') === $v['ville']) ? 'selected' : '';
                                    echo "<option value='" . htmlspecialchars($v['ville']) . "' $selected>" . htmlspecialchars($v['ville']) . "</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <div class="flex space-x-2 w-full md:w-auto">
                        <button type="submit" class="flex-1 md:flex-initial px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-md shadow-indigo-600/20 transition-all">
                            <i class="fa-solid fa-search mr-1.5"></i> Filter
                        </button>
                        <a href="liste.php" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm rounded-xl transition-all">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Donors Table Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <h3 class="font-bold text-slate-800 text-lg">Registered Donors List</h3>
                    <span class="text-xs font-semibold text-slate-400"><?php echo count($donors); ?> Donors Found</span>
                </div>

                <div class="overflow-x-auto">
                    <?php if (empty($donors)): ?>
                        <div class="text-center py-12 text-slate-400">
                            <i class="fa-solid fa-users-slash text-4xl mb-3 text-slate-300"></i>
                            <p class="text-sm font-medium">No donors found matching your criteria.</p>
                        </div>
                    <?php else: ?>
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-xs font-bold uppercase tracking-wider text-slate-400 bg-slate-50 border-b border-slate-100">
                                    <th class="py-3 px-4 rounded-l-xl">ID</th>
                                    <th class="py-3 px-4">Name</th>
                                    <th class="py-3 px-4">CIN</th>
                                    <th class="py-3 px-4">Blood Group</th>
                                    <th class="py-3 px-4">City</th>
                                    <th class="py-3 px-4 text-right rounded-r-xl">History</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                <?php foreach ($donors as $donor): ?>
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="py-3 px-4 font-semibold text-slate-600">#<?php echo htmlspecialchars($donor['id_donneur']); ?></td>
                                        <td class="py-3 px-4 font-bold text-slate-800"><?php echo htmlspecialchars($donor['nom']); ?></td>
                                        <td class="py-3 px-4 text-slate-600"><?php echo htmlspecialchars($donor['cin']); ?></td>
                                        <td class="py-3 px-4 font-bold text-indigo-600">
                                            <span class="px-2.5 py-1 rounded-lg bg-indigo-50 border border-indigo-100">
                                                <?php echo htmlspecialchars($donor['groupe_sanguin'] . $donor['rhesus']); ?>
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-slate-600"><?php echo htmlspecialchars($donor['ville'] ?? 'N/A'); ?></td>
                                        <td class="py-3 px-4 text-right">
                                            <a href="history.php?id=<?php echo $donor['id_donneur']; ?>" class="inline-flex items-center px-3 py-1 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-sm transition-all">
                                                Details
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

</body>
</html>
