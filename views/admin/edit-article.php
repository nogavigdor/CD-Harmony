<?php use Controllers\ArticleController;
use Services\SessionManager;
$csrfToken=SessionManager::generateCSRFToken();
?>
<?php include 'admin-header.php' ?>

<main class='content bg-primary'>
    <div class="flex bg-gray-100">
        <!-- Sidebar -->
        <?php include './partials/admin-sidebar.php' ?>

        <!-- Content Area -->
        <div class="flex-1 p-8">
            <?php
            ?>
            <form action="<?= BASE_URL.'/admin/article/edit' ?>" method="POST" enctype="multipart/form-data" class="max-w-lg mx-auto p-4 bg-white shadow-md rounded-md">
                <input type="hidden" name="csrf_token" id="csrf_token" value="<?= $csrfToken; ?>"> 
                <h3 class="text-2xl font-semibold text-gray-700">Edit Article</h3>
                <!-- User ID -->
                <div class="mb-4">
                    <label for="userId" class="block text-base font-medium text-gray-700">Select User:</label>
                    <select name="userId" id="userId" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <?php foreach ($usersAdminAndEditor as $user): ?>
                            <option value="<?= $user['user_id']; ?>" <?= $user['user_id'] == $currentUser? 'selected' : '' ?>>
                                <?= $user['first_name']." ".$user['last_name'] ?>
                            </option>
                        <?php endforeach; ?>
                        </select>
                 </div>
                <!-- Article Title -->
                <div class="mb-4">
                    <label for="articleTitle" class="block text-base font-medium text-gray-700">Article Title:</label>
                    <input type="text" name="articleTitle" id="articleTitle" value="<?= htmlspecialchars($article->title ?? ''); ?>"
                        class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <!-- Publish Date -->
                <h4 class="mb-4">
                    <?= "Publish Date: " . htmlspecialchars($article->publish_date ?? ''); ?>
                </h4>

                <!-- Content -->
                <div class="mb-4">
                    <label for="articleContent" class="block text-sm font-medium text-gray-700">Content:</label>
                    <textarea name="articleContent" id="articleContent"
                        class="h-72 mt-1 p-2 block w-full  rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50  auto"><?= htmlspecialchars($article->content?? ''); ?></textarea>
                </div>
 
                <input class="btn" id="SubmitUpdateArticleForm" type="submit" value="Update Article"/>
                <input type="hidden" name="article_id" id="article_id" value="<?= $article->article_id ?? ''; ?>">
                <input type="hidden" name="_method" id="_method" value="PUT">
            </form>
        </div>
    </div>
</main>
<script>
   document.getElementById('SubmitUpdateArticleForm').addEventListener('click', async function (e) {
    e.preventDefault();
    let csrfToken = document.querySelector('input[name="csrf_token"]').value;
    let articleTitle = document.getElementById('articleTitle').value;
    let articleContent = document.getElementById('articleContent').value;
    let userId = document.getElementById('userId').value;
    let articleId = document.getElementById('article_id').value;
    let _method = document.getElementById('_method').value;
    //let tags = document.getElementById('tags').value;
    let formData = new FormData();
    formData.append('csrfToken', csrfToken);
    formData.append('articleTitle', articleTitle);
    formData.append('articleContent', articleContent);
    formData.append('userId', userId);
    //formData.append('tags', tags);
    formData.append('articleId', articleId);
    formData.append('_method', _method);

    try {
        const response = await fetch('<?php echo BASE_URL.'/admin/article/update' ?>', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        if (data.status === 'success') {
                alert('Article was updated successfully');
                window.location.href = '<?php echo BASE_URL.'/admin/articles' ?>';
            } else {
                if (data && Object.keys(data).length > 0) {
                    let errorMessage = '';
                    for (let key in data) {
                        errorMessage += data[key] + '\n';
                    }
                    alert('Article could not be updated:\n' + errorMessage);
                }
            }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while processing your request');
    }
});


    
    </script>

<?php include 'admin-footer.php' ?>
