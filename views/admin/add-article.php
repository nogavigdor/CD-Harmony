<?php use Controllers\ArticleController;
use Services\SessionManager;
$csrfToken =SessionManager::generateCSRFToken();
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
          <form action="<?php echo BASE_URL.'/admin/article/add' ?>" method="POST" enctype="multipart/form-data" class="max-w-lg mx-auto p-4 bg-white shadow-md rounded-md">
                <input type="hidden" name="csrf_token" value="<?= $csrfToken; ?>">  
                <input type="hidden" name="userId" id="userId" value="<?= $this->sessionManager->getloggedInUserId(); ?>">
                <h1 class="text-2xl font-semibold text-gray-700">Add Article</h1>
                <!--Article Title -->
                <div class="mb-4">
                    <label for="productTitle" class="block text-sm font-medium text-gray-700">Title:</label>
                    <input type="text" name="articleTitle" id="articleTitle" value=""
                        class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <!-- Content -->
                <div class="mb-4">
                    <label for="articleContent" class="block text-sm font-medium text-gray-700">Content:</label>
                    <textarea name="articleContent" id="articleContent"
                        class="h-72 mt-1 p-2 block w-full  rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50  auto"></textarea>
                </div>
              
              
                <input class="btn" id="SubmitAddArticleForm" type="submit" value="Add Article"/>
            </form>
        </div>
    </div>
</main>
<script>
document.getElementById('SubmitAddArticleForm').addEventListener('click', function (e) {
    e.preventDefault();
    let csrfToken = document.querySelector('input[name="csrf_token"]').value;
    let articleTitle = document.getElementById('articleTitle').value;
    let articleContent = document.getElementById('articleContent').value;
    let userId = document.getElementById('userId').value;
   // let tags = document.getElementById('tags').value;
   // let image = document.getElementById('image').files[0];
    let formData = new FormData();
    formData.append('csrf_token', csrfToken);
    formData.append('articleTitle', articleTitle);
    formData.append('articleContent', articleContent);
    formData.append('userId', userId);
  //  formData.append('tags', tags);
  //  formData.append('image', image);
    fetch('<?php echo BASE_URL.'/admin/article/add' ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    }).then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Article added successfully');
                window.location.href = '<?php echo BASE_URL.'/admin/articles' ?>';
            } else {
                if (data && Object.keys(data).length > 0) {
                    let errorMessage = '';
                    for (let key in data) {
                        errorMessage += data[key] + '\n';
                    }
                    alert('Article could not be added:\n' + errorMessage);
                }
            }
        }).catch(error => {
            console.error('Error:', error);
            alert('There was an error adding the article. Please try again later.');
        });
});


    
    </script>

<?php include 'admin-footer.php' ?>

