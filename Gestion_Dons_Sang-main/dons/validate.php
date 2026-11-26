<?php
    session_start();

    require "../config/connexion.php";
    require "../auth/auth_check.php";

    check_authorization(['Médecin', 'Admin']);

    $sql = "SELECT * FROM dons where status = 'EN STOCK'";
    $stmt = $pdo->query($sql);
    $stmt->execute();
    $donations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/animation.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Validate Donations - AhmedBahri & Thamza</title>
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
                <a href="../donneurs/ajout.php" class="flex items-center px-4 py-3 rounded-xl hover:bg-slate-800 hover:text-white transition-all text-slate-400 font-medium">
                    <i class="fa-solid fa-user-plus w-6"></i> Add Donor
                </a>
                <a href="ajout.php" class="flex items-center px-4 py-3 rounded-xl hover:bg-slate-800 hover:text-white transition-all text-slate-400 font-medium">
                    <i class="fa-solid fa-hand-holding-medical w-6"></i> Add Donation
                </a>
                <a href="../donneurs/liste.php" class="flex items-center px-4 py-3 rounded-xl hover:bg-slate-800 hover:text-white transition-all text-slate-400 font-medium">
                    <i class="fa-solid fa-users w-6"></i> Donors List
                </a>
                <a href="validate.php" class="flex items-center px-4 py-3 rounded-xl bg-indigo-600 text-white font-medium shadow-lg shadow-indigo-600/30 transition-all">
                    <i class="fa-solid fa-clipboard-check w-6"></i> Validate Donations
                </a>
                <a href="transfusion.php" class="flex items-center px-4 py-3 rounded-xl hover:bg-slate-800 hover:text-white transition-all text-slate-400 font-medium">
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
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <h3 class="font-bold text-slate-800 text-lg">Donations Awaiting Validation</h3>
                    <span class="text-xs font-semibold text-slate-400"><?php echo count($donations); ?> Donations</span>
                </div>

                <div class="overflow-x-auto">
                    <?php if (empty($donations)): ?>
                        <div class="text-center py-12 text-slate-400">
                            <i class="fa-solid fa-circle-check text-4xl mb-3 text-emerald-400"></i>
                            <p class="text-sm font-medium">No donations currently awaiting validation.</p>
                        </div>
                    <?php else: ?>
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-xs font-bold uppercase tracking-wider text-slate-400 bg-slate-50 border-b border-slate-100">
                                    <th class="py-3 px-4 rounded-l-xl">Donation ID</th>
                                    <th class="py-3 px-4">Center ID</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4">Date</th>
                                    <th class="py-3 px-4 text-right rounded-r-xl">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                <?php foreach ($donations as $donation): ?>
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="py-3 px-4 font-semibold text-slate-600">#<?php echo htmlspecialchars($donation['id_don']); ?></td>
                                        <td class="py-3 px-4 text-slate-600">Center #<?php echo htmlspecialchars($donation['id_centre']); ?></td>
                                        <td class="py-3 px-4">
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                                <?php echo htmlspecialchars($donation['status']); ?>
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-slate-600"><?php echo htmlspecialchars($donation['date']); ?></td>
                                        <td class="py-3 px-4 text-right">
                                            <a href="consulter.php?id=<?php echo $donation['id_don']; ?>" class="inline-flex items-center px-3 py-1 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-sm transition-all">
                                                Consult &amp; Validate
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
