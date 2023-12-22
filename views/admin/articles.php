<?php
use Services\SessionManager;
use models\articleModel;
?>

<?php include 'admin-header.php' ?>

<main class='flex bg-gray-100'>
    <!-- Sidebar -->
    <?php include './partials/admin-sidebar.php' ?>

    <!-- Content Area -->
    <div class="flex-1 p-8">
        <h1 class="flex flex-start text-2xl font-semibold mb-8">articles List</h1>
        <?php
        $articleModel = new ArticleModel();
        $articles = $articlesModel->getAllArticles();
       

        if (!empty($articles)) {
        ?>
            <table class="min-w-full divide-y divide-gray-200">
                <thead  class="bg-gray-50" >
                    <tr>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" >article ID</th>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">status</th>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Name</th>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Email</th>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"</th>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grand Total</th>

                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" colspan="2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $article) { ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap" ><?= $article['article_id'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $article['article_date'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $article['article_status'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $article['article_payment'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $article['customer_name'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $article['customer_email'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $article['article_subtotal'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $article['article_discount'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $article['article_grand_total'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><a href="<?= BASE_URL . '/admin/article/view/' . $article['article_id'] ?>" class="btn btn-primary">View Invoice</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No articles found.</p>
        <?php } ?>
    </div>
</main>

<?php include 'admin-footer.php' ?>
