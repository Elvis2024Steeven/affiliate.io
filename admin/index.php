<?php
// Include authentication check
require_once 'auth/check_auth.php';
require_once 'config/database.php';

$db = new Database();
$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $action = $_POST['action'] ?? '';
        
        switch ($action) {
            case 'create':
                $data = [
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'image_url' => $_POST['image_url'],
                    'amazon_link' => $_POST['amazon_link'],
                    'price' => $_POST['price'] ?? '',
                    'is_featured' => isset($_POST['is_featured']),
                    'display_order' => (int)($_POST['display_order'] ?? 0)
                ];
                $db->createProduct($data);
                $message = 'Produit créé avec succès!';
                break;
                
            case 'update':
                $id = $_POST['id'];
                $data = [
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'image_url' => $_POST['image_url'],
                    'amazon_link' => $_POST['amazon_link'],
                    'price' => $_POST['price'] ?? '',
                    'is_featured' => isset($_POST['is_featured']),
                    'display_order' => (int)($_POST['display_order'] ?? 0)
                ];
                $db->updateProduct($id, $data);
                $message = 'Produit mis à jour avec succès!';
                break;
                
            case 'delete':
                $id = $_POST['id'];
                $db->deleteProduct($id);
                $message = 'Produit supprimé avec succès!';
                break;
        }
    } catch (Exception $e) {
        $error = 'Erreur: ' . $e->getMessage();
    }
}

// Get all products
try {
    $products = $db->getProducts();
} catch (Exception $e) {
    $error = 'Erreur lors du chargement des produits: ' . $e->getMessage();
    $products = [];
}

// Get product for editing
$editProduct = null;
if (isset($_GET['edit'])) {
    try {
        $result = $db->getProduct($_GET['edit']);
        $editProduct = $result[0] ?? null;
    } catch (Exception $e) {
        $error = 'Erreur lors du chargement du produit: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Steeven Recommends</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings text-purple-600 h-8 w-8"><path d="M12.22 2h-.44a2 2 0 0 0-2 2.18l.2 1.83a2 2 0 0 1-.26 1.31l-.24.4a2 2 0 0 1-1.1.81l-1.8.46a2 2 0 0 0-1.48 2.34l.22.88a2 2 0 0 0 2.34 1.48l1.8-.46a2 2 0 0 1 1.31.26l.4.24a2 2 0 0 1 .81 1.1l.46 1.8a2 2 0 0 0 2.34 1.48l.88-.22a2 2 0 0 0 1.48-2.34l-.46-1.8a2 2 0 0 1 .26-1.31l.24-.4a2 2 0 0 1 1.1-.81l1.8-.46a2 2 0 0 0 1.48-2.34l-.22-.88a2 2 0 0 0-2.34-1.48l-1.8.46a2 2 0 0 1-1.31-.26l-.4-.24a2 2 0 0 1-.81-1.1l-.46-1.8A2 2 0 0 0 12.22 2Z"/><circle cx="12" cy="12" r="3"/></svg>
                    <h1 class="text-2xl font-bold text-gray-900">Admin Panel</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">
                        Connecté en tant que: <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong>
                    </span>
                    <a href="../index.html" target="_blank" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition duration-200 flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-external-link w-4 h-4"><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                        <span>Voir le site</span>
                    </a>
                    <a href="auth/logout.php" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-200 flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out w-4 h-4"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16,17 21,12 16,7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                        <span>Déconnexion</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Messages -->
        <?php if ($message): ?>
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Product Form -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">
                        <?php echo $editProduct ? 'Modifier le produit' : 'Ajouter un produit'; ?>
                    </h2>
                    
                    <form method="POST" class="space-y-4">
                        <input type="hidden" name="action" value="<?php echo $editProduct ? 'update' : 'create'; ?>">
                        <?php if ($editProduct): ?>
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($editProduct['id']); ?>">
                        <?php endif; ?>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Titre</label>
                            <input type="text" name="title" required 
                                   value="<?php echo htmlspecialchars($editProduct['title'] ?? ''); ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" required rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"><?php echo htmlspecialchars($editProduct['description'] ?? ''); ?></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">URL de l'image</label>
                            <input type="url" name="image_url" required 
                                   value="<?php echo htmlspecialchars($editProduct['image_url'] ?? ''); ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lien Amazon</label>
                            <input type="url" name="amazon_link" required 
                                   value="<?php echo htmlspecialchars($editProduct['amazon_link'] ?? ''); ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Prix (optionnel)</label>
                            <input type="text" name="price" 
                                   value="<?php echo htmlspecialchars($editProduct['price'] ?? ''); ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ordre d'affichage</label>
                            <input type="number" name="display_order" 
                                   value="<?php echo htmlspecialchars($editProduct['display_order'] ?? '0'); ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="is_featured" id="is_featured" 
                                   <?php echo ($editProduct['is_featured'] ?? true) ? 'checked' : ''; ?>
                                   class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                            <label for="is_featured" class="ml-2 block text-sm text-gray-700">
                                Afficher sur la page d'accueil
                            </label>
                        </div>
                        
                        <div class="flex space-x-3">
                            <button type="submit" 
                                    class="flex-1 bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition duration-200">
                                <?php echo $editProduct ? 'Mettre à jour' : 'Ajouter'; ?>
                            </button>
                            <?php if ($editProduct): ?>
                                <a href="index.php" 
                                   class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                                    Annuler
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-semibold text-gray-900">Produits (<?php echo count($products); ?>)</h2>
                    </div>
                    
                    <div class="divide-y divide-gray-200">
                        <?php foreach ($products as $product): ?>
                            <div class="p-6 flex items-center space-x-4">
                                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['title']); ?>"
                                     class="w-16 h-16 object-cover rounded-lg">
                                
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-medium text-gray-900 truncate">
                                        <?php echo htmlspecialchars($product['title']); ?>
                                    </h3>
                                    <p class="text-sm text-gray-500 line-clamp-2">
                                        <?php echo htmlspecialchars(substr($product['description'], 0, 100)) . '...'; ?>
                                    </p>
                                    <div class="flex items-center space-x-4 mt-2">
                                        <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                            Ordre: <?php echo $product['display_order']; ?>
                                        </span>
                                        <?php if ($product['is_featured']): ?>
                                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">
                                                En vedette
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-2">
                                    <a href="?edit=<?php echo $product['id']; ?>" 
                                       class="text-purple-600 hover:text-purple-700 p-2 rounded-lg hover:bg-purple-50 transition duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-edit w-4 h-4"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z"/></svg>
                                    </a>
                                    
                                    <form method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2 w-4 h-4"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <?php if (empty($products)): ?>
                            <div class="p-12 text-center text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package w-12 h-12 mx-auto mb-4 text-gray-300"><path d="M16.5 9.4 7.55 4.24"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.29,7 12,12 20.71,7"/><line x1="12" x2="12" y1="22" y2="12"/></svg>
                                <p>Aucun produit trouvé</p>
                                <p class="text-sm">Ajoutez votre premier produit pour commencer.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>