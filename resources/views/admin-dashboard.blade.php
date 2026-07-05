<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard | THREADLAB</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,700;0,800;1,700;1,800&family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
</head>
<body class="admin-body">
    <header class="admin-topbar">
        <a href="{{ route('admin.dashboard') }}" class="admin-logo">VOLT_ADMIN</a>
        <nav class="admin-topnav" aria-label="Admin top navigation">
            <a href="#" class="is-active">System_Status</a>
            <a href="#">Logs</a>
            <a href="#">Reports</a>
        </nav>
        <div class="admin-top-actions">
            <button type="button" aria-label="Notifications">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M18 8a6 6 0 1 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"></path>
                    <path d="M10 21h4"></path>
                </svg>
            </button>
            <button type="button" aria-label="Settings">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z"></path>
                    <path d="M19.4 15a1.8 1.8 0 0 0 .36 2l.04.04a2.2 2.2 0 0 1-3.11 3.11l-.04-.04a1.8 1.8 0 0 0-2-.36 1.8 1.8 0 0 0-1 1.65V21a2.2 2.2 0 0 1-4.4 0v-.06a1.8 1.8 0 0 0-1-1.65 1.8 1.8 0 0 0-2 .36l-.04.04a2.2 2.2 0 1 1-3.11-3.11l.04-.04a1.8 1.8 0 0 0 .36-2 1.8 1.8 0 0 0-1.65-1H2a2.2 2.2 0 0 1 0-4.4h.06a1.8 1.8 0 0 0 1.65-1 1.8 1.8 0 0 0-.36-2l-.04-.04A2.2 2.2 0 1 1 6.42 3l.04.04a1.8 1.8 0 0 0 2 .36h.02A1.8 1.8 0 0 0 9.5 1.75V1.7a2.2 2.2 0 0 1 4.4 0v.06a1.8 1.8 0 0 0 1 1.65 1.8 1.8 0 0 0 2-.36l.04-.04a2.2 2.2 0 0 1 3.11 3.11l-.04.04a1.8 1.8 0 0 0-.36 2v.02a1.8 1.8 0 0 0 1.65 1H21a2.2 2.2 0 0 1 0 4.4h-.06a1.8 1.8 0 0 0-1.54 1.42Z"></path>
                </svg>
            </button>
            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBnfhBTAR42k3DjoAdqz6TtW9Zlp9af9Hsrwe7CJ9CNGg02GCLa25-X5NhDRHgNx9oYQcwRobvVygbmbYZxyRJy1ZfsAc4ICAeGBpL1-_wd2v1oONfuhDrbPGXurknMQK3zuZWhEmRWB2eW6W8gYhL-7x-_dARcIGRwKD0KrXAQLg2Eoy6oEpOfXpfXc-azjZSZVIlZmH-w0xnuCOisRMNwNvPOZuHI2jjz2T4mVLWzT-IJy4Q-kGHT-zdfhlo8sTovoTqXeMn2wmw" alt="Admin profile">
        </div>
    </header>

    <aside class="admin-sidebar">
        <div class="admin-core">
            <span aria-hidden="true"></span>
            <div>
                <strong>Kinetic_Core</strong>
                <small>V2.0.48_Stable</small>
            </div>
        </div>
        <nav class="admin-side-nav" aria-label="Admin sidebar">
            <a href="#dashboard" class="is-active" data-admin-page-link="dashboard">
                <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                Dashboard
            </a>
            <a href="#products" data-admin-page-link="products">
                <svg viewBox="0 0 24 24"><path d="M12 3 4 7v10l8 4 8-4V7l-8-4Z"></path><path d="M4 7l8 4 8-4"></path><path d="M12 11v10"></path></svg>
                Products
            </a>
            <a href="#analytics" data-admin-page-link="analytics">
                <svg viewBox="0 0 24 24"><path d="M4 19V5"></path><path d="M4 19h16"></path><path d="M8 15l3-4 3 2 4-6"></path></svg>
                Analytics
            </a>
            <a href="#system-logs" data-admin-page-link="system-logs">
                <svg viewBox="0 0 24 24"><path d="m7 8 4 4-4 4"></path><path d="M13 16h4"></path><rect x="3" y="4" width="18" height="16" rx="2"></rect></svg>
                System Logs
            </a>
        </nav>
        <button type="button" class="admin-deploy">Deploy Update</button>
        <div class="admin-sidebar-foot">
            <a href="#">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"></circle><path d="M9.8 9a2.4 2.4 0 0 1 4.4 1.4c0 1.8-2.2 2.1-2.2 3.6"></path><path d="M12 17h.01"></path></svg>
                Support
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">
                    <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><path d="M16 17l5-5-5-5"></path><path d="M21 12H9"></path></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <main class="admin-main">
        <section id="dashboard" class="admin-page-panel is-active" data-admin-page="dashboard">
            <section class="admin-hero">
                <div>
                    <h1>System <span>Overview</span></h1>
                    <p>Real-time transaction matrix and operational velocity. Core stability maintained at <strong>99.98%</strong>.</p>
                </div>
                <aside>
                    <span>Live Node</span>
                    <strong>Metro_Manila_01</strong>
                </aside>
            </section>

            <section class="admin-metrics" aria-label="Summary metrics">
                <article>
                    <span>Total Orders</span>
                    <strong>{{ number_format($totalOrders) }}</strong>
                    <p>+12.4% vs last period</p>
                </article>
                <article class="is-revenue">
                    <span>Total Revenue</span>
                    <strong>PHP {{ number_format($totalRevenue) }}</strong>
                    <p>Stable_Margin_Confirmed</p>
                </article>
                <article>
                    <span>Pending Orders</span>
                    <strong>{{ number_format($pendingOrders) }}</strong>
                    <p><i></i> Awaiting Dispatch</p>
                </article>
                <article>
                    <span>Completed Orders</span>
                    <strong>{{ number_format($completedOrders) }}</strong>
                    <p>Fulfillment_Success</p>
                </article>
            </section>

            <section class="admin-orders">
                <header>
                <div>
                    <h2>Recent <span>Orders</span></h2>
                    <p>Latest customer activity with live order status management</p>
                </div>
                <div>
                    <button type="button">Recent Feed</button>
                    <button type="button">Live Status</button>
                </div>
            </header>
            @if (session('admin_status_success'))
                <p class="form-success">{{ session('admin_status_success') }}</p>
            @endif
            <div class="admin-table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Customer Name</th>
                            <th>Value</th>
                            <th>Placed</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Last Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>#{{ $order->reference }}</td>
                                <td><span>{{ \Illuminate\Support\Str::of($order->customer_name)->explode(' ')->map(fn ($part) => \Illuminate\Support\Str::substr($part, 0, 1))->take(2)->implode('') }}</span> {{ $order->customer_name }}</td>
                                <td>PHP {{ number_format($order->total, 2) }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}<small>{{ $order->created_at->format('g:i A') }}</small></td>
                                <td>
                                    <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="admin-inline-form">
                                        @csrf
                                        @method('PATCH')
                                        <label class="sr-only" for="payment-status-{{ $order->id }}">Payment status for {{ $order->reference }}</label>
                                        <select id="payment-status-{{ $order->id }}" name="payment_status" class="admin-inline-select admin-inline-select-payment" onchange="this.form.submit()">
                                            @foreach ($paymentStatusOptions as $paymentStatusOption)
                                                <option value="{{ $paymentStatusOption }}" @selected($order->payment_status === $paymentStatusOption)>{{ \Illuminate\Support\Str::headline($paymentStatusOption) }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="admin-inline-form">
                                        @csrf
                                        @method('PATCH')
                                        <label class="sr-only" for="status-{{ $order->id }}">Update status for {{ $order->reference }}</label>
                                        <select id="status-{{ $order->id }}" name="status" class="admin-inline-select admin-inline-select-status" onchange="this.form.submit()">
                                            @foreach ($statusOptions as $statusOption)
                                                <option value="{{ $statusOption }}" @selected($order->status === $statusOption)>{{ \Illuminate\Support\Str::headline($statusOption) }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                <td>{{ ($order->status_updated_at ?? $order->updated_at)->format('M d, Y') }}<small>{{ ($order->status_updated_at ?? $order->updated_at)->format('g:i A') }}</small></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">No orders yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            </section>
        </section>

        <section id="products" class="admin-page-panel admin-products" data-admin-page="products">
            <header>
                <div>
                    <h2>Product <span>Workspace</span></h2>
                    <p>Manage your catalog and upload real storefront products from one place.</p>
                </div>
            </header>
            @if (session('admin_product_success'))
                <p class="form-success">{{ session('admin_product_success') }}</p>
            @endif
            @if ($errors->any())
                <p class="form-error">Please review the product form fields and try again.</p>
            @endif
            <div class="admin-product-subnav" role="tablist" aria-label="Product submenu">
                <button type="button" class="admin-product-subtab is-active" data-admin-products-tab="all-products">All Products</button>
                <button type="button" class="admin-product-subtab" data-admin-products-tab="add-products">Add Products</button>
            </div>
            <div class="admin-products-panels">
                <section class="admin-products-panel is-active" data-admin-products-panel="all-products">
                    <div class="admin-product-list-head">
                        <h3>All Products</h3>
                        <span>{{ $products->count() }} listed</span>
                    </div>
                    <div class="admin-table-wrap">
                        <table class="admin-products-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Stocks</th>
                                    <th>Category</th>
                                    <th>Product ID</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    <tr class="admin-products-row" data-admin-edit-product data-product-id="{{ $product->id }}" tabindex="0">
                                        <td><img src="{{ $product->displayImageUrl() }}" alt="{{ $product->name }}" class="admin-products-thumb"></td>
                                        <td>
                                            <button type="button" class="admin-product-name-button" tabindex="-1">
                                                {{ $product->name }}
                                            </button>
                                        </td>
                                        <td>{{ $product->formattedPrice() }}</td>
                                        <td>{{ number_format($product->stock) }}</td>
                                        <td>{{ $product->category }}</td>
                                        <td>#{{ $product->id }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">No products uploaded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="admin-products-panel" data-admin-products-panel="add-products">
                    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="admin-product-form" id="admin-product-add-form">
                        @csrf
                        <label>
                            <span>Title</span>
                            <input type="text" name="title" value="{{ old('title') }}" placeholder="Essential Black Tee" required>
                        </label>
                        <label class="admin-product-form-full">
                            <span>Description</span>
                            <textarea name="description" rows="5" placeholder="Write the product details here..." required>{{ old('description') }}</textarea>
                        </label>
                        <label>
                            <span>Price</span>
                            <input type="number" name="price" value="{{ old('price') }}" min="1" step="1" placeholder="799" required>
                        </label>
                        <label>
                            <span>Stocks</span>
                            <input type="number" name="stock" value="{{ old('stock', 100) }}" min="0" step="1" placeholder="100" required>
                        </label>
                        <label>
                            <span>Category</span>
                            <select name="category" required>
                                @foreach ($productCategories as $productCategory)
                                    <option value="{{ $productCategory }}" @selected(old('category', 'Basic') === $productCategory)>{{ $productCategory }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label>
                            <span>Featured Image</span>
                            <input type="file" name="featured_image" id="add-product-featured-image" accept="image/*" required>
                            <div class="admin-image-preview-list" id="add-featured-preview-list"></div>
                        </label>
                        <label>
                            <span>Gallery Images</span>
                            <input type="file" name="gallery_images[]" id="add-product-gallery-images" accept="image/*" multiple>
                            <div class="admin-image-preview-list" id="add-gallery-preview-list"></div>
                            <small>Maximum of 4 images.</small>
                        </label>
                        <div class="admin-product-form-full admin-product-submit">
                            <button type="submit">Upload Product</button>
                        </div>
                    </form>
                </section>
            </div>
        </section>

        <section id="analytics" class="admin-page-panel admin-empty-page" data-admin-page="analytics">
            <div>
                <h1>Analytics</h1>
                <p>This page is ready for reports, charts, and store insights later.</p>
            </div>
        </section>

        <section id="system-logs" class="admin-page-panel admin-empty-page" data-admin-page="system-logs">
            <div>
                <h1>System Logs</h1>
                <p>This page is ready for admin logs and system activity later.</p>
            </div>
        </section>

    </main>
    <div class="admin-product-modal" id="admin-product-modal">
        <div class="admin-product-modal-dialog">
            <div class="admin-product-modal-head">
                <div class="admin-product-list-head">
                    <h3>Edit Product</h3>
                    <span>Update product info, stocks, images, or delete it</span>
                </div>
                <button type="button" class="admin-product-modal-close" data-admin-product-modal-close aria-label="Close edit product">
                    &times;
                </button>
            </div>
            <form method="POST" action="{{ route('admin.products.update', $products->first()?->id ?? 0) }}" enctype="multipart/form-data" class="admin-product-form" id="admin-product-edit-form">
                @csrf
                @method('PATCH')
                <label>
                    <span>Title</span>
                    <input type="text" name="title" id="edit-product-title" placeholder="Select a product" required>
                </label>
                <label>
                    <span>Price</span>
                    <input type="number" name="price" id="edit-product-price" min="1" step="1" required>
                </label>
                <label>
                    <span>Stocks</span>
                    <input type="number" name="stock" id="edit-product-stock" min="0" step="1" required>
                </label>
                <label>
                    <span>Category</span>
                    <select name="category" id="edit-product-category" required>
                        @foreach ($productCategories as $productCategory)
                            <option value="{{ $productCategory }}">{{ $productCategory }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="admin-product-form-full">
                    <span>Description</span>
                    <textarea name="description" id="edit-product-description" rows="5" required></textarea>
                </label>
                <label>
                    <span>Replace Featured Image</span>
                    <input type="file" name="featured_image" id="edit-product-featured-image" accept="image/*">
                    <input type="hidden" name="remove_featured_image" id="edit-remove-featured-image" value="0">
                    <div class="admin-image-preview-list" id="edit-featured-preview-list"></div>
                </label>
                <label>
                    <span>Replace Gallery Images</span>
                    <input type="file" name="gallery_images[]" id="edit-product-gallery-images" accept="image/*" multiple>
                    <input type="hidden" name="removed_gallery_images" id="edit-removed-gallery-images" value="">
                    <div class="admin-image-preview-list" id="edit-gallery-preview-list"></div>
                    <small>Upload up to 4 new images to replace the current gallery.</small>
                </label>
                <div class="admin-product-form-full admin-product-submit admin-product-submit-split">
                    <button type="submit">Save Changes</button>
                </div>
            </form>
            <form method="POST" action="{{ route('admin.products.destroy', $products->first()?->id ?? 0) }}" id="admin-product-delete-form" class="admin-product-delete-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="admin-product-delete-button" onclick="return confirm('Delete this product? This cannot be undone.')">Delete Product</button>
            </form>
        </div>
    </div>
    <script>
        (() => {
            const adminPageLinks = document.querySelectorAll('[data-admin-page-link]');
            const adminPagePanels = document.querySelectorAll('[data-admin-page]');
            const productTabs = document.querySelectorAll('[data-admin-products-tab]');
            const productPanels = document.querySelectorAll('[data-admin-products-panel]');
            const productRows = document.querySelectorAll('[data-admin-edit-product]');
            const addForm = document.getElementById('admin-product-add-form');
            const productData = {!! $products->mapWithKeys(fn ($product) => [
                $product->id => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'category' => $product->category,
                    'image' => $product->displayImageUrl(),
                    'gallery' => $product->gallery ?? [],
                ],
            ])->toJson(JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!};
            const modal = document.getElementById('admin-product-modal');
            const modalCloseButtons = document.querySelectorAll('[data-admin-product-modal-close]');
            const editForm = document.getElementById('admin-product-edit-form');
            const deleteForm = document.getElementById('admin-product-delete-form');
            const titleInput = document.getElementById('edit-product-title');
            const descriptionInput = document.getElementById('edit-product-description');
            const priceInput = document.getElementById('edit-product-price');
            const stockInput = document.getElementById('edit-product-stock');
            const categoryInput = document.getElementById('edit-product-category');
            const featuredImageField = document.getElementById('edit-product-featured-image');
            const galleryImagesField = document.getElementById('edit-product-gallery-images');
            const removeFeaturedInput = document.getElementById('edit-remove-featured-image');
            const removedGalleryInput = document.getElementById('edit-removed-gallery-images');
            const featuredPreviewList = document.getElementById('edit-featured-preview-list');
            const galleryPreviewList = document.getElementById('edit-gallery-preview-list');
            const addFeaturedImageField = document.getElementById('add-product-featured-image');
            const addGalleryImagesField = document.getElementById('add-product-gallery-images');
            const addFeaturedPreviewList = document.getElementById('add-featured-preview-list');
            const addGalleryPreviewList = document.getElementById('add-gallery-preview-list');

            if (!productTabs.length || !productPanels.length || !modal || !editForm || !deleteForm) {
                return;
            }

            const activateAdminPage = (pageName) => {
                const nextPageName = document.querySelector(`[data-admin-page="${pageName}"]`) ? pageName : 'dashboard';

                adminPageLinks.forEach((link) => {
                    link.classList.toggle('is-active', link.dataset.adminPageLink === nextPageName);
                });

                adminPagePanels.forEach((panel) => {
                    panel.classList.toggle('is-active', panel.dataset.adminPage === nextPageName);
                });
            };

            adminPageLinks.forEach((link) => {
                link.addEventListener('click', (event) => {
                    event.preventDefault();
                    const pageName = link.dataset.adminPageLink;

                    activateAdminPage(pageName);
                    window.history.replaceState(null, '', `#${pageName}`);
                });
            });

            const activatePanel = (panelName) => {
                productTabs.forEach((tab) => {
                    tab.classList.toggle('is-active', tab.dataset.adminProductsTab === panelName);
                });

                productPanels.forEach((panel) => {
                    panel.classList.toggle('is-active', panel.dataset.adminProductsPanel === panelName);
                });
            };

            productTabs.forEach((tab) => {
                tab.addEventListener('click', () => activatePanel(tab.dataset.adminProductsTab));
            });

            const openModal = () => {
                modal.classList.add('is-open');
                document.body.classList.add('admin-modal-open');
            };

            const closeModal = () => {
                modal.classList.remove('is-open');
                document.body.classList.remove('admin-modal-open');
            };

            const createImagePreview = (imageUrl, label, onRemove) => {
                const item = document.createElement('span');
                item.className = 'admin-image-preview-chip';

                const image = document.createElement('img');
                image.src = imageUrl;
                image.alt = label;

                const button = document.createElement('button');
                button.type = 'button';
                button.setAttribute('aria-label', `Remove ${label}`);
                button.textContent = 'x';
                button.addEventListener('click', onRemove);

                item.append(image, button);

                return item;
            };

            const renderSelectedFiles = (files, previewList, maxFiles, clearInput) => {
                previewList.innerHTML = '';

                Array.from(files).slice(0, maxFiles).forEach((file, index) => {
                    const objectUrl = URL.createObjectURL(file);

                    previewList.append(createImagePreview(objectUrl, file.name || `selected image ${index + 1}`, () => {
                        URL.revokeObjectURL(objectUrl);
                        clearInput();
                        previewList.innerHTML = '';
                    }));
                });
            };

            const setInputFiles = (input, files) => {
                const transfer = new DataTransfer();

                files.forEach((file) => transfer.items.add(file));
                input.files = transfer.files;
            };

            const renderAddSelectedFiles = (input, previewList, maxFiles) => {
                const selectedFiles = Array.from(input.files).slice(0, maxFiles);

                if (input.files.length > maxFiles) {
                    setInputFiles(input, selectedFiles);
                }

                previewList.innerHTML = '';

                selectedFiles.forEach((file, index) => {
                    const objectUrl = URL.createObjectURL(file);

                    previewList.append(createImagePreview(objectUrl, file.name || `selected image ${index + 1}`, () => {
                        URL.revokeObjectURL(objectUrl);
                        const nextFiles = selectedFiles.filter((_, fileIndex) => fileIndex !== index);

                        setInputFiles(input, nextFiles);
                        renderAddSelectedFiles(input, previewList, maxFiles);
                    }));
                });
            };

            const renderCurrentImages = (product) => {
                const removedGallery = new Set();

                removeFeaturedInput.value = '0';
                removedGalleryInput.value = '';
                featuredPreviewList.innerHTML = '';
                galleryPreviewList.innerHTML = '';
                featuredImageField.value = '';
                galleryImagesField.value = '';

                if (product.image) {
                    featuredPreviewList.append(createImagePreview(product.image, 'featured image', () => {
                        removeFeaturedInput.value = '1';
                        featuredPreviewList.innerHTML = '';
                    }));
                }

                (product.gallery ?? []).slice(0, 4).forEach((galleryImage, index) => {
                    galleryPreviewList.append(createImagePreview(galleryImage, `gallery image ${index + 1}`, (event) => {
                        removedGallery.add(galleryImage);
                        removedGalleryInput.value = Array.from(removedGallery).join('|');
                        event.currentTarget.closest('.admin-image-preview-chip')?.remove();
                    }));
                });
            };

            modalCloseButtons.forEach((button) => {
                button.addEventListener('click', closeModal);
            });

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeModal();
                }
            });

            const loadProduct = (row) => {
                const product = productData[row.dataset.productId];

                if (!product) {
                    return;
                }

                const productId = product.id;

                editForm.action = `{{ url('/admin/products') }}/${productId}`;
                deleteForm.action = `{{ url('/admin/products') }}/${productId}`;
                titleInput.value = product.name ?? '';
                descriptionInput.value = product.description ?? '';
                priceInput.value = product.price ?? '';
                stockInput.value = product.stock ?? '';
                categoryInput.value = product.category ?? '';
                renderCurrentImages(product);
                openModal();
            };

            featuredImageField.addEventListener('change', () => {
                if (!featuredImageField.files.length) {
                    return;
                }

                removeFeaturedInput.value = '0';
                renderSelectedFiles(featuredImageField.files, featuredPreviewList, 1, () => {
                    featuredImageField.value = '';
                });
            });

            galleryImagesField.addEventListener('change', () => {
                if (galleryImagesField.files.length > 4) {
                    galleryImagesField.value = '';
                    galleryPreviewList.innerHTML = '';
                    alert('Gallery image upload is limited to 4 images.');
                    return;
                }

                removedGalleryInput.value = '';
                renderSelectedFiles(galleryImagesField.files, galleryPreviewList, 4, () => {
                    galleryImagesField.value = '';
                });
            });

            addFeaturedImageField?.addEventListener('change', () => {
                renderAddSelectedFiles(addFeaturedImageField, addFeaturedPreviewList, 1);
            });

            addGalleryImagesField?.addEventListener('change', () => {
                if (addGalleryImagesField.files.length > 4) {
                    alert('Gallery image upload is limited to 4 images.');
                }

                renderAddSelectedFiles(addGalleryImagesField, addGalleryPreviewList, 4);
            });

            addForm?.addEventListener('reset', () => {
                addFeaturedPreviewList.innerHTML = '';
                addGalleryPreviewList.innerHTML = '';
            });

            productRows.forEach((row) => {
                row.addEventListener('click', () => {
                    loadProduct(row);
                });

                row.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        loadProduct(row);
                    }
                });
            });

            if ({{ $errors->any() ? 'true' : 'false' }}) {
                activateAdminPage('products');
                activatePanel('add-products');
            } else {
                activateAdminPage((window.location.hash || '#dashboard').replace('#', ''));
            }
        })();
    </script>
</body>
</html>
