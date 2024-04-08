<?php
use Services\SessionManager;
use Models\ArticleModel; // Assuming the correct namespace for the ArticleModel class

include 'admin-header.php';

$articleModel = new ArticleModel();
$articles = $articleModel->getAllArticles();

?>

<main class='flex bg-gray-100'>
    <!-- Sidebar -->
    <?php include './partials/admin-sidebar.php' ?>

    <!-- Content Area -->
    <div class="flex-1 p-8">
    <a href="<?php echo BASE_URL.'/admin/article/add/' ?>" class="btn">Add Article</a>
        <h1 class="flex flex-start text-2xl font-semibold mb-8">Articles List</h1>
        <?php if (!empty($articles)) { ?>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Article ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Publish Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Update Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $article) { ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $article->article_id ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $article->title ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $article->publish_date ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $article->update_date ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $article->first_name . ' ' . $article->last_name ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                           
                            <a class="btn" href="<?= BASE_URL . '/admin/article/edit/' . $article->article_id ?>">edit</a>
                            <form action="<?= BASE_URL . '/admin/article/delete/' . $article->article_id ?>" method="POST">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                            </td>
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
