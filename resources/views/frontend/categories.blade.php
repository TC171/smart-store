<!DOCTYPE html>
<html>
<head>
    <title>Danh mục sản phẩm</title>

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <h2 class="text-center mb-4">
        Danh mục sản phẩm
    </h2>

    <div class="row" id="category-list">

        <!-- Categories sẽ load ở đây -->

    </div>

</div>

<script>

fetch("http://localhost/smart-store/public/api/categories")

.then(res => res.json())

.then(data => {

    let categories = data.data;

    let html = "";

    categories.forEach(cat => {

        html += `
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm">

                <div class="card-body text-center">

                    <h5 class="card-title">
                        ${cat.name}
                    </h5>

                    <a href="/category/${cat.slug}" 
                       class="btn btn-primary">

                        Xem sản phẩm

                    </a>

                </div>

            </div>
        </div>
        `;

    });

    document.getElementById("category-list").innerHTML = html;

});

</script>

</body>
</html>