<?php
    session_start();
    require "config/connexion.php";
    require "auth/auth_check.php";

    check_authorization(['Médecin', 'Admin', 'Secrétaire']);

    $pages_accessibles = [
        'donneurs/liste.php' => ['Admin', 'Secrétaire'],
        'donneurs/ajout.php' => ['Admin', 'Secrétaire'],
        'dons/validate.php' => ['Médecin', 'Admin'],
        'dons/ajout.php' => ['Secrétaire', 'Admin'],
        'dons/transfusion.php' => ['Admin'],
        'auth/inscrire.php' => ['Admin']
    ];

    function est_page_accessible($page, $role_utilisateur, $pages_accessibles) {
        return isset($pages_accessibles[$page]) && in_array($role_utilisateur, $pages_accessibles[$page]);
    }

    $role_utilisateur = $_SESSION['role'] ?? 'Admin';
    $username_utilisateur = $_SESSION['username'] ?? 'User';

    $stats = [];
    try {
        $sql_donneurs = "SELECT COUNT(*) as total FROM donneurs";
        $stmt = $pdo->query($sql_donneurs);
        $stats['total_donneurs'] = $stmt->fetch()['total'];

        $sql_dons_valides = "SELECT COUNT(*) as total FROM dons WHERE status = 'VALIDE' OR status = 'EN STOCK'";
        $stmt = $pdo->query($sql_dons_valides);
        $stats['total_dons_valides'] = $stmt->fetch()['total'];

        $sql_centres = "SELECT COUNT(DISTINCT hopital_recepteur) as total FROM transfusions";
        $stmt = $pdo->query($sql_centres);
        $stats['total_centres'] = $stmt->fetch()['total'];

    } catch (PDOException $e) {
        die("Erreur lors du chargement des statistiques: " . $e->getMessage());
    }

    $stock_par_groupe = [];
    try {
        $sql_stock = "SELECT 
                        groupe_sanguin, 
                        rhesus,
                        COUNT(*) as quantite,
                        CONCAT(groupe_sanguin, rhesus) as groupe_complet
                    FROM dons NATURAL JOIN donneurs
                    WHERE status = 'VALIDE' OR status = 'EN STOCK'
                    GROUP BY groupe_sanguin, rhesus
                    ORDER BY groupe_sanguin, rhesus";

        $stmt = $pdo->query($sql_stock);
        $stock_par_groupe = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("Erreur lors du chargement du stock: " . $e->getMessage());
    }

    $alertes_besoins = [];
    try {
        $sql_alertes = "SELECT * FROM besoins WHERE niveau_alerte IN ('URGENT', 'CRITIQUE') ORDER BY niveau_alerte DESC";
        $stmt = $pdo->query($sql_alertes);
        $alertes_besoins = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        $alertes_besoins = [];
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
    <title>Dashboard - AhmedBahri & Thamza</title>
</head>
<body class="bg-slate-100 font-sans antialiased min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-72 bg-slate-900 text-slate-300 flex flex-col justify-between hidden md:flex shadow-xl">
        <div>
            <!-- Brand -->
            <div class="h-20 flex items-center px-6 bg-slate-950 border-b border-slate-800">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-violet-500 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-indigo-500/35 mr-3">
                    <i class="fa-solid fa-droplet"></i>
                </div>
                <div>
                    <h1 class="text-white font-bold text-base tracking-wide">AhmedBahri</h1>
                    <span class="text-xs text-indigo-400 font-medium">&amp; Thamza Blood Bank</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-2">
                <p class="px-4 text-xs font-semibold uppercase tracking-wider text-slate-500 mb-3">Menu</p>
                
                <a href="dashboard.php" class="flex items-center px-4 py-3 rounded-xl bg-indigo-600 text-white font-medium shadow-lg shadow-indigo-600/30 transition-all">
                    <i class="fa-solid fa-chart-pie w-6"></i> Dashboard
                </a>

                <?php if (est_page_accessible('donneurs/ajout.php', $role_utilisateur, $pages_accessibles)): ?>
                    <a href="donneurs/ajout.php" class="flex items-center px-4 py-3 rounded-xl hover:bg-slate-800 hover:text-white transition-all text-slate-400 font-medium">
                        <i class="fa-solid fa-user-plus w-6"></i> Add Donor
                    </a>
                <?php endif; ?>

                <?php if (est_page_accessible('dons/ajout.php', $role_utilisateur, $pages_accessibles)): ?>
                    <a href="dons/ajout.php" class="flex items-center px-4 py-3 rounded-xl hover:bg-slate-800 hover:text-white transition-all text-slate-400 font-medium">
                        <i class="fa-solid fa-hand-holding-medical w-6"></i> Add Donation
                    </a>
                <?php endif; ?>

                <?php if (est_page_accessible('donneurs/liste.php', $role_utilisateur, $pages_accessibles)): ?>
                    <a href="donneurs/liste.php" class="flex items-center px-4 py-3 rounded-xl hover:bg-slate-800 hover:text-white transition-all text-slate-400 font-medium">
                        <i class="fa-solid fa-users w-6"></i> Donors List
                    </a>
                <?php endif; ?>

                <?php if (est_page_accessible('dons/validate.php', $role_utilisateur, $pages_accessibles)): ?>
                    <a href="dons/validate.php" class="flex items-center px-4 py-3 rounded-xl hover:bg-slate-800 hover:text-white transition-all text-slate-400 font-medium">
                        <i class="fa-solid fa-clipboard-check w-6"></i> Validate Donations
                    </a>
                <?php endif; ?>

                <?php if (est_page_accessible('dons/transfusion.php', $role_utilisateur, $pages_accessibles)): ?>
                    <a href="dons/transfusion.php" class="flex items-center px-4 py-3 rounded-xl hover:bg-slate-800 hover:text-white transition-all text-slate-400 font-medium">
                        <i class="fa-solid fa-heart-pulse w-6"></i> Transfusions
                    </a>
                <?php endif; ?>

                <?php if (est_page_accessible('auth/inscrire.php', $role_utilisateur, $pages_accessibles)): ?>
                    <a href="auth/inscrire.php" class="flex items-center px-4 py-3 rounded-xl hover:bg-slate-800 hover:text-white transition-all text-slate-400 font-medium">
                        <i class="fa-solid fa-user-shield w-6"></i> Add User
                    </a>
                <?php endif; ?>
            </nav>
        </div>

        <!-- User Profile & Logout Footer -->
        <div class="p-4 bg-slate-950 border-t border-slate-800 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-bold">
                    <?php echo strtoupper(substr($username_utilisateur, 0, 1)); ?>
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-semibold text-white truncate"><?php echo htmlspecialchars($username_utilisateur); ?></p>
                    <p class="text-xs text-indigo-400"><?php echo htmlspecialchars($role_utilisateur); ?></p>
                </div>
            </div>
            <a href="auth/logout.php" title="Logout" class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-red-600 hover:text-white text-slate-400 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0">

        <!-- Top Header for Mobile & Desktop Header -->
        <header class="h-20 bg-white border-b border-slate-200 px-6 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <div class="flex items-center space-x-4">
                <div class="md:hidden flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold">
                        <i class="fa-solid fa-droplet"></i>
                    </div>
                    <span class="font-bold text-slate-800">AhmedBahri &amp; Thamza</span>
                </div>
                <h2 class="text-xl font-bold text-slate-800 hidden md:block">Overview Dashboard</h2>
            </div>

            <div class="flex items-center space-x-4">
                <div class="hidden sm:flex flex-col text-right">
                    <span class="text-xs font-semibold text-slate-400">Signed in as</span>
                    <span class="text-sm font-bold text-slate-700"><?php echo htmlspecialchars($username_utilisateur); ?> (<?php echo htmlspecialchars($role_utilisateur); ?>)</span>
                </div>
                <a href="auth/logout.php" class="md:hidden px-3 py-1.5 rounded-lg bg-red-500 hover:bg-red-600 text-white text-xs font-bold transition-colors">
                    <i class="fa-solid fa-right-from-bracket mr-1"></i> Logout
                </a>
            </div>
        </header>

        <!-- Dashboard Body Content -->
        <main class="flex-1 p-6 space-y-6 overflow-y-auto">

            <!-- Welcome Banner -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-indigo-700 to-violet-800 p-8 text-white shadow-xl">
                <div class="relative z-10 max-w-2xl">
                    <span class="px-3 py-1 rounded-full bg-indigo-500/30 text-indigo-200 text-xs font-semibold backdrop-blur-md border border-indigo-400/30">
                        <i class="fa-solid fa-shield-heart mr-1"></i> Blood Bank Management System
                    </span>
                    <h2 class="text-3xl font-extrabold mt-3 tracking-tight">Welcome back, <?php echo htmlspecialchars($username_utilisateur); ?>!</h2>
                    <p class="mt-2 text-indigo-100 text-sm leading-relaxed">
                        Monitor live blood stocks, manage donors, validate incoming donations, and handle critical hospital transfusion requests seamlessly from your central hub.
                    </p>
                </div>
                <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Stat 1 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Donors</p>
                        <h3 class="text-3xl font-extrabold text-slate-800 mt-2"><?php echo $stats['total_donneurs']; ?></h3>
                        <span class="inline-flex items-center text-xs font-semibold text-emerald-600 mt-2">
                            <i class="fa-solid fa-arrow-trend-up mr-1"></span> Registered in database
                        </span>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl shadow-inner">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>

                <!-- Stat 2 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Valid Donations</p>
                        <h3 class="text-3xl font-extrabold text-slate-800 mt-2"><?php echo $stats['total_dons_valides']; ?></h3>
                        <span class="inline-flex items-center text-xs font-semibold text-emerald-600 mt-2">
                            <i class="fa-solid fa-box-archive mr-1"></i> Available in stock
                        </span>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl shadow-inner">
                        <i class="fa-solid fa-droplet"></i>
                    </div>
                </div>

                <!-- Stat 3 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Hospital Centers</p>
                        <h3 class="text-3xl font-extrabold text-slate-800 mt-2"><?php echo $stats['total_centres']; ?></h3>
                        <span class="inline-flex items-center text-xs font-semibold text-indigo-600 mt-2">
                            <i class="fa-solid fa-hospital mr-1"></i> Active recipients
                        </span>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center text-2xl shadow-inner">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                </div>
            </div>

            <!-- Two Column Tables Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Blood Stock Status -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 flex flex-col">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <i class="fa-solid fa-warehouse"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg">Blood Stock Status</h3>
                                <p class="text-xs text-slate-400">Real-time inventory levels by blood group</p>
                            </div>
                        </div>
                        <a href="dons/validate.php" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors">
                            Manage All <i class="fa-solid fa-arrow-right ml-1"></i>
                        </a>
                    </div>

                    <div class="overflow-x-auto flex-1 max-h-[350px] overflow-y-auto pr-1">
                        <?php if (empty($stock_par_groupe)): ?>
                            <div class="text-center py-12 text-slate-400">
                                <i class="fa-solid fa-box-open text-4xl mb-3 text-slate-300"></i>
                                <p class="text-sm font-medium">No valid donations in stock at the moment.</p>
                            </div>
                        <?php else: ?>
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="text-xs font-bold uppercase tracking-wider text-slate-400 bg-slate-50 border-b border-slate-100">
                                        <th class="py-3 px-4 rounded-l-xl">Blood Group</th>
                                        <th class="py-3 px-4">Quantity</th>
                                        <th class="py-3 px-4">Stock Status</th>
                                        <th class="py-3 px-4 text-right rounded-r-xl">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-sm">
                                    <?php foreach($stock_par_groupe as $groupe): ?>
                                        <tr class="hover:bg-slate-50/80 transition-colors">
                                            <td class="py-3 px-4 font-bold text-indigo-600">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-indigo-50 border border-indigo-100">
                                                    <?php echo htmlspecialchars($groupe['groupe_complet']); ?>
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 font-semibold text-slate-700">
                                                <?php echo $groupe['quantite']; ?> units
                                            </td>
                                            <td class="py-3 px-4">
                                                <?php if ($groupe['quantite'] > 5): ?>
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Optimal
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> Low Stock
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="py-3 px-4 text-right">
                                                <a href="dons/validate.php" class="inline-flex items-center justify-center px-3 py-1 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-sm transition-all">
                                                    Manage
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Urgent Alerts -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 flex flex-col">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg">Urgent Alerts</h3>
                                <p class="text-xs text-slate-400">Critical blood requirements &amp; shortage alerts</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-rose-600 bg-rose-50 px-3 py-1.5 rounded-lg">
                            <?php echo count($alertes_besoins); ?> Active Alerts
                        </span>
                    </div>

                    <div class="overflow-x-auto flex-1 max-h-[350px] overflow-y-auto pr-1">
                        <?php if (empty($alertes_besoins)): ?>
                            <div class="text-center py-12 text-slate-400">
                                <i class="fa-solid fa-circle-check text-4xl mb-3 text-emerald-400"></i>
                                <p class="text-sm font-medium">No urgent alerts at the moment. All stocks are stable.</p>
                            </div>
                        <?php else: ?>
                            <div class="space-y-3">
                                <?php foreach($alertes_besoins as $alerte): ?>
                                    <?php 
                                        $isCritique = $alerte['niveau_alerte'] === 'CRITIQUE';
                                        $alertBg = $isCritique ? 'bg-rose-50 border-rose-200 text-rose-900' : 'bg-amber-50 border-amber-200 text-amber-900';
                                        $badgeBg = $isCritique ? 'bg-rose-600 text-white shadow-rose-600/30' : 'bg-amber-500 text-white shadow-amber-500/30';
                                    ?>
                                    <div class="flex items-center justify-between p-4 rounded-xl border <?php echo $alertBg; ?> shadow-sm">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center font-bold text-slate-800 shadow-sm">
                                                <?php echo htmlspecialchars($alerte['groupe_sanguin'] . $alerte['rhesus']); ?>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-slate-500">Blood Group Requirement</p>
                                                <h4 class="font-bold text-slate-800"><?php echo htmlspecialchars($alerte['groupe_sanguin'] . ' (' . $alerte['rhesus'] . ')'); ?></h4>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <span class="px-3 py-1 rounded-full text-xs font-extrabold shadow-sm <?php echo $badgeBg; ?>">
                                                <?php echo htmlspecialchars($alerte['niveau_alerte']); ?>
                                            </span>
                                            <a href="dons/validate.php" class="px-3 py-1.5 rounded-lg bg-white hover:bg-slate-900 hover:text-white text-slate-700 text-xs font-bold border border-slate-200 shadow-sm transition-all">
                                                View
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

        </main>
    </div>

</body>
</html>
